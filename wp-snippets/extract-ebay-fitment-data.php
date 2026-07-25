<?php
/**
 * Snippet Name: Extract eBay Fitment Data
 *
 * One-off admin-only maintenance script (NOT a REST endpoint). Visit any
 * wp-admin page with ?run_fitment_sync=1 while logged in as admin to run it.
 * Reads a large ";"-delimited CSV (uploads/products_data.csv, ~20k rows)
 * whose "Compatibility" column holds PHP-serialized eBay item-specifics data,
 * and populates wp_custom_product_fitment from it. Used historically to
 * backfill/fix Audi/Porsche/BMW fitment data. Treat as a throwaway tool, not
 * part of the standing backend - re-run only if similar bulk fitment repair
 * is needed again from a raw eBay export file.
 */

if (is_admin()) {
    add_action('admin_init', 'parse_entire_20k_file_for_audi');
}

function parse_entire_20k_file_for_audi() {
    if (!isset($_GET['run_fitment_sync']) || $_GET['run_fitment_sync'] !== '1') {
        return;
    }

    global $wpdb;
    $fitment_table = 'wp_custom_product_fitment';

    // Server par majood 20,424 lines wali main file ka path dhoondte hain
    // Agar products_data.csv hi aapki main 20k lines wali badi file hai, toh yahi path chalega.
    $csv_file_path = ABSPATH . 'uploads/products_data.csv';

    if (!file_exists($csv_file_path)) {
        $csv_file_path = WP_CONTENT_DIR . '/uploads/products_data.csv';
        if (!file_exists($csv_file_path)) {
            wp_die("Main CSV file nahi mili! Kindly check karein ke uploads folder mein badi file kis naam se parhi hai.");
        }
    }

    // Memory limit barha rahe hain taake 20,000 lines par script crash na ho
    ini_set('memory_limit', '512M');
    set_time_limit(600); // 10 minutes max execution time

    // Semicolon separator use kar rahe hain
    if (($handle = fopen($csv_file_path, "r")) !== FALSE) {
        $headers = fgetcsv($handle, 0, ";");

        $sku_idx = FALSE;
        $compat_idx = FALSE;

        foreach ($headers as $index => $header_name) {
            $cleaned = strtolower(trim($header_name, " \t\n\r\0\x0B\"'"));
            if ($cleaned === 'sku') {
                $sku_idx = $index;
            }
            if (strpos($cleaned, 'compat') !== FALSE || strpos($cleaned, 'compact') !== FALSE) {
                $compat_idx = $index;
            }
        }

        if ($sku_idx === FALSE) $sku_idx = 4;
        if ($compat_idx === FALSE) $compat_idx = 9;

        $inserted_count = 0;
        $row_count = 0;

        // Line by line scanning through all 20k+ entries
        while (($row = fgetcsv($handle, 0, ";")) !== FALSE) {
            $row_count++;

            if (count($row) <= max($sku_idx, $compat_idx)) {
                continue;
            }

            $sku = trim($row[$sku_idx], " \t\n\r\0\x0B\"'");
            $raw_serialized = $row[$compat_idx];

            if (empty($raw_serialized) || $raw_serialized == 'NULL' || $raw_serialized == 'null') {
                continue;
            }

            // Cleanup broken characters and double-double quotes
            $serialized_data = str_replace('""', '"', $raw_serialized);
            $serialized_data = trim($serialized_data, '"');

            $data = @unserialize($serialized_data);

            if ($data === FALSE && strpos($serialized_data, 's:') !== FALSE) {
                $cleaned_serialized = preg_replace_callback('!s:(\d+):"(.*?)";!', function($m) {
                    return 's:'.strlen($m[2]).':"'.$m[2].'";';
                }, $serialized_data);
                $data = @unserialize($cleaned_serialized);
            }

            if (is_array($data) && isset($data['Compatibility'])) {
                foreach ($data['Compatibility'] as $vehicle) {
                    if (isset($vehicle['NameValueList'])) {
                        $year = ''; $make = ''; $model = ''; $submodel = ''; $engine = '';

                        foreach ($vehicle['NameValueList'] as $param) {
                            if (!isset($param['Name'])) continue;
                            $value = isset($param['Value']) ? $param['Value'] : '';

                            switch ($param['Name']) {
                                case 'Year': $year = $value; break;
                                case 'Make': $make = $value; break;
                                case 'Model': $model = $value; break;
                                case 'Trim': $submodel = $value; break;
                                case 'Engine':
                                case 'Enginer':
                                    $engine = $value;
                                    break;
                            }
                        }

                        if (!empty($make) && !empty($model)) {
                            $wpdb->replace(
                                $fitment_table,
                                array(
                                    'sku' => $sku,
                                    'year' => $year,
                                    'make' => strtoupper(trim($make)),
                                    'model' => $model,
                                    'submodel' => $submodel,
                                    'engine_size' => $engine
                                ),
                                array('%s', '%s', '%s', '%s', '%s', '%s')
                            );
                            $inserted_count++;
                        }
                    }
                }
            }
        }
        fclose($handle);
        wp_die("GRAND SYNC SUCCESSFUL! Total $row_count rows scanned from main file. Successfully extracted and populated $inserted_count global vehicle matching records (including Audi, Porsche & BMW) into '$fitment_table'!");
    } else {
        wp_die("File path open karne mein error aaya.");
    }
}
