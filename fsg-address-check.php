<?php
/**
 * Plugin Name:     FSG Address Check
 * Plugin URI:      https://theitdept.au
 * Description:     NBN Address Checker
 * Author:          Nick Pratley
 * Author URI:      https://theitdept.au
 * Text Domain:     fsg-address-check
 * Domain Path:     /languages
 * Version:         0.0.3
 *
 * @package         Fsg_Address_Check
 */

use Auryn\Injector;
use FSGAddressCheck\FSGAddressCheck;

$plugin_path = plugin_dir_path(__FILE__);
$autoload = $plugin_path . 'vendor/autoload.php';

if (is_readable($autoload)) {
	require_once $autoload;
}

try {
	add_action('plugins_loaded', [new FSGAddressCheck($plugin_path, new Injector()), 'run']);
} catch (Throwable $e) {
	// There was an error loading the plugin, so we'll just log it and move on.
	add_action(
		'admin_init',
		function () {
			deactivate_plugins(__FILE__);
		}
	);
}
