<?php
/**
 * Plugin Name:     FSG Address Check
 * Plugin URI:      https://theitdept.au
 * Description:     NBN Address Checker
 * Author:          Nick Pratley
 * Author URI:      https://theitdept.au
 * Text Domain:     fsg-address-check
 * Domain Path:     /languages
 * Version:         0.0.2
 *
 * @package         Fsg_Address_Check
 */

use FSGAddressCheck\AddressSearch;
use FSGAddressCheck\Settings;
use FSGAddressCheck\NBNPlans;

require 'vendor/autoload.php';

new Settings();
new NBNPlans();
new AddressSearch();
