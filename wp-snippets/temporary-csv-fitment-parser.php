<?php
/**
 * Snippet Name: Temporary CSV Fitment Parser
 *
 * Registers two public (permission_callback => '__return_true') REST routes
 * on the live backend (qsz.zoy.temporary.site/website_11f3c7a8):
 *
 *   GET custom/v2/vehicle  - drives all cascading vehicle dropdowns
 *   (pa_year, pa_make, pa_model, pa_submodel, pa_engine) plus the
 *   products-page sidebar tree (pa_sidebar_tree), querying the flat
 *   wp_custom_product_fitment table.
 *
 *   GET custom/v1/get-user-billing - looks up a WP user by email and
 *   returns display name/email/phone/postcode. No auth check - flagged as
 *   a PII exposure risk (see wordpress_backend_plugins memory notes),
 *   anyone who supplies a valid registered email gets that user's data back.
 */

// REGISTER CUSTOM API ENDPOINTS FOR VEHICLE DROPDOWNS (VERSION 2 - FIXED)
add_action('rest_api_init', function () {
    register_rest_route('custom/v2', '/vehicle', array(
        'methods'             => 'GET',
        'callback'            => 'get_complete_vehicle_flow_data_v2',
        'permission_callback' => '__return_true',
    ));
});

function get_complete_vehicle_flow_data_v2($request) {
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Headers: *");
    header("Access-Control-Allow-Methods: GET");

    global $wpdb;
    $slug_type    = $request->get_param('slug');
    $parent_param = $request->get_param('parent');
    $make_param   = $request->get_param('make');

    // 🟢 SIDEBAR DYNAMIC TREE (Smart System: Handle with or without Year)
    if ($slug_type === 'pa_sidebar_tree') {
        $make = !empty($make_param) ? trim($make_param) : '';
        if (empty($make) && !empty($parent_param)) {
            $make = trim(urldecode($parent_param));
        }

        if (empty($make)) return array();

        // 🛠️ FIX: Agar year mojood ho to SQL query narrow down karein, warna pure make ka data nikaalein
        $year_param = $request->get_param('year');

        if (!empty($year_param) && String($year_param) !== '') {
            $rows = $wpdb->get_results($wpdb->prepare(
                "SELECT DISTINCT `model`, `submodel`, `engine`
                 FROM wp_custom_product_fitment
                 WHERE LOWER(make) = LOWER(%s) AND `year` = %d
                 AND `model` IS NOT NULL AND `model` != ''
                 ORDER BY `model` ASC, `submodel` ASC, `engine` ASC",
                $make, (int)$year_param
            ), ARRAY_A);
        } else {
            // Without Year System Dynamic Fallback
            $rows = $wpdb->get_results($wpdb->prepare(
                "SELECT DISTINCT `model`, `submodel`, `engine`
                 FROM wp_custom_product_fitment
                 WHERE LOWER(make) = LOWER(%s)
                 AND `model` IS NOT NULL AND `model` != ''
                 ORDER BY `model` ASC, `submodel` ASC, `engine` ASC",
                $make
            ), ARRAY_A);
        }

        $tree = array();

        foreach ($rows as $row) {
            $m_name = $row['model'];
            $s_name = !empty($row['submodel']) ? $row['submodel'] : 'Base';
            $e_name = !empty($row['engine']) ? $row['engine'] : '';

            $m_slug = strtolower(sanitize_title($m_name));
            $s_slug = strtolower(sanitize_title($s_name));

            if (!isset($tree[$m_slug])) {
                $tree[$m_slug] = array(
                    'id'        => $m_slug,
                    'name'      => $m_name,
                    'slug'      => $m_slug,
                    'submodels' => array()
                );
            }

            if (!isset($tree[$m_slug]['submodels'][$s_slug])) {
                $tree[$m_slug]['submodels'][$s_slug] = array(
                    'id'      => $s_slug,
                    'name'    => $s_name,
                    'slug'    => $s_slug,
                    'engines' => array()
                );
            }

            if (!empty($e_name) && !in_array($e_name, $tree[$m_slug]['submodels'][$s_slug]['engines'])) {
                $tree[$m_slug]['submodels'][$s_slug]['engines'][] = $e_name;
            }
        }

        $final_response = array();
        foreach ($tree as $m_data) {
            $sub_array = array();
            foreach ($m_data['submodels'] as $s_data) {
                $sub_array[] = $s_data;
            }
            $m_data['submodels'] = $sub_array;
            $final_response[] = $m_data;
        }

        return $final_response;
    }

    // 1. YEAR DROPDOWN (UNCHANGED - SAFE)
    if ($slug_type === 'pa_year') {
        $years = $wpdb->get_col("SELECT DISTINCT `year` FROM wp_custom_product_fitment WHERE `year` IS NOT NULL AND `year` != '' ORDER BY `year` DESC");
        $response = array();
        foreach ($years as $yr) { $response[] = array('name' => (string)$yr, 'slug' => (string)$yr); }
        return $response;
    }

   // 2. MAKE DROPDOWN (UNCHANGED - SAFE)
    if ($slug_type === 'pa_make') {
        if (empty($parent_param)) {
            $makes = $wpdb->get_col("SELECT DISTINCT `make` FROM wp_custom_product_fitment WHERE `make` IS NOT NULL AND `make` != '' ORDER BY `make` ASC");
        } else {
            $year = (int)trim(urldecode($parent_param));
            $makes = $wpdb->get_col($wpdb->prepare("SELECT DISTINCT `make` FROM wp_custom_product_fitment WHERE `year` = %d ORDER BY `make` ASC", $year));
        }

        $response = array();
        foreach ($makes as $mk) {
            $response[] = array('name' => strtoupper($mk), 'slug' => strtolower($mk));
        }
        return $response;
    }

    // 3. MODEL DROPDOWN (UNCHANGED - SAFE)
    if ($slug_type === 'pa_model') {
        if (empty($parent_param)) return array();
        $decoded_parent = urldecode($parent_param);

        if (strpos($decoded_parent, '|') === false) {
            $make = trim($decoded_parent);
            $models = $wpdb->get_col($wpdb->prepare("SELECT DISTINCT `model` FROM wp_custom_product_fitment WHERE LOWER(`make`) = LOWER(%s) AND `model` IS NOT NULL ORDER BY `model` ASC", $make));
        } else {
            $parts = explode('|', $decoded_parent);
            $year  = isset($parts[0]) ? (int)trim($parts[0]) : 0;
            $make  = isset($parts[1]) ? trim($parts[1]) : '';
            $models = $wpdb->get_col($wpdb->prepare("SELECT DISTINCT `model` FROM wp_custom_product_fitment WHERE `year` = %d AND LOWER(`make`) = LOWER(%s) ORDER BY `model` ASC", $year, $make));
        }

        $response = array();
        foreach ($models as $md) {
            if(empty($md)) continue;
            $response[] = array('name' => $md, 'slug' => strtolower(sanitize_title($md)));
        }
        return $response;
    }

    // 4. SUBMODEL DROPDOWN (UNCHANGED - SAFE)
    if ($slug_type === 'pa_submodel') {
        if (empty($parent_param)) return array();
        $decoded_parent = urldecode($parent_param);

        if (strpos($decoded_parent, '|') === false) {
            $make = trim($decoded_parent);
            $submodels = $wpdb->get_col($wpdb->prepare("SELECT DISTINCT `submodel` FROM wp_custom_product_fitment WHERE LOWER(`make`) = LOWER(%s) AND `submodel` IS NOT NULL ORDER BY `submodel` ASC", $make));
        } else {
            $parts = explode('|', $decoded_parent);
            $year  = isset($parts[0]) ? (int)trim($parts[0]) : 0;
            $make  = isset($parts[1]) ? trim($parts[1]) : '';
            $model = isset($parts[2]) ? trim($parts[2]) : '';
            $submodels = $wpdb->get_col($wpdb->prepare("SELECT DISTINCT `submodel` FROM wp_custom_product_fitment WHERE `year` = %d AND LOWER(`make`) = LOWER(%s) AND LOWER(`model`) = LOWER(%s) ORDER BY `submodel` ASC", $year, $make, $model));
        }

        $response = array();
        foreach ($submodels as $sb) {
            if(empty($sb)) continue;
            $response[] = array('name' => $sb, 'slug' => strtolower(sanitize_title($sb)));
        }
        return $response;
    }

    // 5. ENGINE DROPDOWN (UNCHANGED - SAFE)
    if ($slug_type === 'pa_engine') {
        if (empty($parent_param)) return array();
        $parts = explode('|', urldecode($parent_param));
        $year  = isset($parts[0]) ? (int)trim($parts[0]) : 0;
        $make  = isset($parts[1]) ? trim($parts[1]) : '';
        $model = isset($parts[2]) ? trim($parts[2]) : '';
        $sub   = isset($parts[3]) ? trim($parts[3]) : '';

        if (empty($sub) || strtolower($sub) === 'base') {
            $engines = $wpdb->get_col($wpdb->prepare(
                "SELECT DISTINCT `engine` FROM wp_custom_product_fitment
                 WHERE `year` = %d
                 AND LOWER(`make`) = LOWER(%s)
                 AND LOWER(`model`) = LOWER(%s)
                 ORDER BY `engine` ASC",
                $year, $make, $model
            ));
        } else {
            $engines = $wpdb->get_col($wpdb->prepare(
                "SELECT DISTINCT `engine` FROM wp_custom_product_fitment
                 WHERE `year` = %d
                 AND LOWER(`make`) = LOWER(%s)
                 AND LOWER(`model`) = LOWER(%s)
                 AND LOWER(`submodel`) LIKE LOWER(%s)
                 ORDER BY `engine` ASC",
                $year, $make, $model, '%' . $sub . '%'
            ));
        }

        $response = array();
        foreach ($engines as $eg) {
            if(empty($eg)) continue;
            $clean_slug = strtolower(preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $eg)));
            $response[] = array('name' => $eg, 'slug' => $clean_slug);
        }
        return $response;
    }
    return array();
}

// REGISTER NEW ENDPOINT TO GET LOGGED IN USER DATA DIRECTLY FROM DATABASE (UNCHANGED - SAFE)
add_action('rest_api_init', function () {
    register_rest_route('custom/v1', '/get-user-billing', array(
        'methods'             => 'GET',
        'callback'            => 'get_custom_logged_in_user_billing_data',
        'permission_callback' => '__return_true',
    ));
});

function get_custom_logged_in_user_billing_data($request) {
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Headers: *");
    header("Access-Control-Allow-Methods: GET");

    global $wpdb;
    $email = isset($request['email']) ? trim($request['email']) : '';

    if (empty($email)) {
        return array('success' => false, 'message' => 'Email parameter is missing');
    }

    $user = get_user_by('email', $email);

    if (!$user) {
        return array('success' => false, 'message' => 'User not found in database');
    }

    $user_id = $user->ID;

    $first_name = get_user_meta($user_id, 'first_name', true);
    $last_name  = get_user_meta($user_id, 'last_name', true);
    $phone      = get_user_meta($user_id, 'billing_phone', true);
    if(empty($phone)) $phone = get_user_meta($user_id, 'phone', true);

    $postcode   = get_user_meta($user_id, 'billing_postcode', true);
    if(empty($postcode)) $postcode = get_user_meta($user_id, 'postcode', true);

    return array(
        'success'    => true,
        'firstName'  => !empty($first_name) ? $first_name . ' ' . $last_name : $user->display_name,
        'email'      => $user->user_email,
        'phone'      => !empty($phone) ? $phone : '',
        'postcode'   => !empty($postcode) ? $postcode : ''
    );
}
