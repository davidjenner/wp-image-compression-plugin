<?php
/*
Plugin Name: Image Compression
Plugin URI: https://imdavidjenner.com/
Description: A WordPress plugin that allows users to compress images on the front end.
Version: 1.0.0
Author: David Jenner
Author URI: https://imdavidjenner.com/
License: GPL2
*/

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Define the shortcode
function ic_shortcode()
{
    ob_start();
    include(plugin_dir_path(__FILE__) . 'templates/compression-form.php');
    return ob_get_clean();
}
add_shortcode('ic_compression_form', 'ic_shortcode');

// Define the form submission handler
function ic_compression_handler() {
    if ( isset( $_FILES['image'] ) && ! empty( $_FILES['image']['name'] ) ) {
        $image = $_FILES['image']['tmp_name'];
        $format = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $quality = isset($_POST['compression-level']) ? $_POST['compression-level'] : 60;
        $compressed_image = compress_image($image, $format, $quality);

        // Force the image to download
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="compressed_image.'.$format.'"');
        header('Content-Length: ' . strlen($compressed_image));
        echo $compressed_image;
        exit();
    }
}


// Hook the form submission handler to the 'init' action
add_action('init', 'ic_compression_handler');

// Define the image compressor function
function compress_image($image_path, $format, $quality)
{
    // Load the image
    switch ($format) {
        case 'jpg':
        case 'jpeg':
            $image = imagecreatefromjpeg($image_path);
            break;
        case 'png':
            $image = imagecreatefrompng($image_path);
            break;
        case 'webp':
            $image = imagecreatefromwebp($image_path);
            break;
        default:
            return false;
    }

    // Compress the image
    ob_start();
    switch ($format) {
        case 'jpg':
        case 'jpeg':
            imagejpeg($image, null, $quality);
            break;
        case 'png':
            imagepng($image, null, floor($quality / 10));
            break;
        case 'webp':
            imagewebp($image, null, $quality);
            break;
    }
    $compressed_image = ob_get_clean();
    imagedestroy($image);

    return $compressed_image;
}
