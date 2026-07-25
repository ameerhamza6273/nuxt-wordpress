<?php
/**
 * Snippet Name: Admin Upload Image
 *
 * Registers custom/v1/admin-upload-image (POST, multipart/form-data, file
 * field "file") - lets the admin panel's Add/Edit Product form upload an
 * image file directly (instead of requiring a pre-hosted image URL). The
 * file is registered as a real WordPress media library attachment (so it
 * gets thumbnails, shows up in Media Library, etc.) and the returned public
 * URL is what gets stored in wp_custom_product_images via
 * admin_save_product() - no other change needed there, it already just
 * stores whatever URL strings the images array contains.
 *
 * Every upload is compressed server-side to fit under ADMIN_IMAGE_MAX_BYTES
 * (500KB) before being saved, so large phone/camera photos don't bloat page
 * weight on the live site - the admin doesn't have to pre-shrink anything
 * themselves. To make that reliably achievable, non-JPEG uploads (PNG/GIF/
 * WEBP) are re-encoded to JPEG - fine for product photos, which don't need
 * transparency, and JPEG's quality dial is what actually lets us hit a
 * target file size.
 *
 * Gated by the same X-Import-Secret as the rest of the admin panel
 * (BULK_IMPORT_SECRET, defined in bulk-import-endpoint.php - that snippet
 * must stay active alongside this one).
 */

require_once ABSPATH . 'wp-admin/includes/image.php';
require_once ABSPATH . 'wp-admin/includes/file.php';
require_once ABSPATH . 'wp-admin/includes/media.php';

define('ADMIN_IMAGE_MAX_BYTES', 500 * 1024); // 500KB

add_action('rest_api_init', function () {
    register_rest_route('custom/v1', '/admin-upload-image', array(
        'methods'             => 'POST',
        'callback'            => 'admin_upload_image_handler',
        'permission_callback' => 'admin_upload_image_check_secret',
    ));
});

function admin_upload_image_check_secret($request) {
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Headers: *");
    header("Access-Control-Allow-Methods: POST, OPTIONS");

    if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
        return true;
    }

    $sent = $request->get_header('x-import-secret');
    return !empty($sent) && hash_equals(BULK_IMPORT_SECRET, $sent);
}

/**
 * Re-encodes $file_path as a JPEG, trying progressively lower quality
 * settings until it fits under $max_bytes. If even the lowest quality isn't
 * enough, scales the dimensions down by 30% and tries once more at medium
 * quality. Overwrites $file_path in place. Returns true if the file ends up
 * under the limit, false otherwise (caller decides whether to reject).
 */
function admin_compress_image_to_limit($file_path, $max_bytes) {
    clearstatcache();
    if (filesize($file_path) <= $max_bytes) {
        return true;
    }

    $editor = wp_get_image_editor($file_path);
    if (is_wp_error($editor)) {
        return false;
    }

    foreach (array(82, 70, 60, 50, 40, 30) as $quality) {
        $editor->set_quality($quality);
        $saved = $editor->save($file_path, 'image/jpeg');
        if (is_wp_error($saved)) {
            continue;
        }
        clearstatcache();
        if (filesize($file_path) <= $max_bytes) {
            return true;
        }
    }

    // Still too big even at the lowest quality - shrink dimensions and retry once.
    $editor2 = wp_get_image_editor($file_path);
    if (!is_wp_error($editor2)) {
        $size = $editor2->get_size();
        if ($size) {
            $editor2->resize((int) ($size['width'] * 0.7), (int) ($size['height'] * 0.7), false);
            $editor2->set_quality(60);
            $editor2->save($file_path, 'image/jpeg');
        }
    }

    clearstatcache();
    return filesize($file_path) <= $max_bytes;
}

function admin_upload_image_handler($request) {
    $files = $request->get_file_params();

    if (empty($files['file']) || $files['file']['error'] !== UPLOAD_ERR_OK) {
        return array('success' => false, 'message' => 'No file uploaded, or upload error.');
    }

    $allowed_types = array('image/jpeg', 'image/png', 'image/webp', 'image/gif');
    if (!in_array($files['file']['type'], $allowed_types, true)) {
        return array('success' => false, 'message' => 'Only JPG, PNG, WEBP or GIF images are allowed.');
    }

    // 15MB cap on the ORIGINAL upload before compression - just a sanity limit
    // so nobody can upload a giant/malicious file; the real 500KB ceiling is
    // enforced after compression below.
    if ($files['file']['size'] > 15 * 1024 * 1024) {
        return array('success' => false, 'message' => 'Image is too large (max 15MB before compression).');
    }

    $uploaded = wp_handle_upload($files['file'], array('test_form' => false));
    if (isset($uploaded['error'])) {
        return array('success' => false, 'message' => $uploaded['error']);
    }

    $file_path = $uploaded['file'];
    $compressed_ok = admin_compress_image_to_limit($file_path, ADMIN_IMAGE_MAX_BYTES);

    if (!$compressed_ok) {
        @unlink($file_path);
        return array('success' => false, 'message' => 'Could not compress this image under 500KB - try a smaller/simpler photo.');
    }

    // Compression re-encodes to JPEG regardless of the original format, so make
    // sure the stored filename/url/mime-type all agree it's a .jpg now.
    $path_info = pathinfo($file_path);
    if (strtolower($path_info['extension']) !== 'jpg' && strtolower($path_info['extension']) !== 'jpeg') {
        $new_path = $path_info['dirname'] . '/' . $path_info['filename'] . '.jpg';
        rename($file_path, $new_path);
        $file_path = $new_path;
        $uploaded['url'] = preg_replace('/\.' . preg_quote($path_info['extension'], '/') . '$/i', '.jpg', $uploaded['url']);
    }

    $attachment_id = wp_insert_attachment(array(
        'post_mime_type' => 'image/jpeg',
        'post_title'     => sanitize_file_name(pathinfo($file_path, PATHINFO_FILENAME)),
        'post_status'    => 'inherit',
    ), $file_path);

    if (is_wp_error($attachment_id)) {
        return array('success' => false, 'message' => $attachment_id->get_error_message());
    }

    $metadata = wp_generate_attachment_metadata($attachment_id, $file_path);
    wp_update_attachment_metadata($attachment_id, $metadata);

    return array(
        'success'        => true,
        'url'            => wp_get_attachment_url($attachment_id),
        'attachment_id'  => $attachment_id,
        'final_size_kb'  => round(filesize($file_path) / 1024, 1),
    );
}
