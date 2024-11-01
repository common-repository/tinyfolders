<?php
/**
 * Plugin Name: TinyFolders
 * Description: Add categories for media library
 * Version: 1.0.0
 * Requires at least: 4.6
 * Requires PHP: 7.4
 * Author: Avirtum
 * Author URI: https://avirtum.com
 * License: GPLv3
 * Text Domain: tinyfolders
 * Domain Path: /languages
 */

namespace TinyFolders;

defined( 'ABSPATH' ) || exit;

define( 'TINYFOLDERS_PLUGIN_NAME', 'tinyfolders' );
define( 'TINYFOLDERS_PLUGIN_VERSION', '1.0.0' );
define( 'TINYFOLDERS_PLUGIN_URL', plugin_dir_url(__FILE__) );
define( 'TINYFOLDERS_PLUGIN_FILE', __FILE__ );
define( 'TINYFOLDERS_PLUGIN_BASE_NAME', plugin_basename(__FILE__) );

spl_autoload_register(function($class) {
    $prefix = __NAMESPACE__;
    $base = __DIR__ . '/includes/';

    // does the class use the namespace prefix?
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        // no, move to the next registered autoloader
        return;
    }

    // get the relative class name
    $relative_class = substr($class, $len);

    // replace the namespace prefix with the base directory, replace namespace
    // separators with directory separators in the relative class name, append
    // with .php
    $file = $base . str_replace('\\', DIRECTORY_SEPARATOR, $relative_class) . '.php';

    if (file_exists($file)) {
        require $file;
    }
});

Plugin::run();