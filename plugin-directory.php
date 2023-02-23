<?php
/*
 * Plugin Name: Country Switcher
 * Description:
 * Version: 1.0
 * Author: Gage Bateman
 */

$kyani_plugin_includes = array(
	'/country-switcher/country-switcher.php',
);

foreach ($plugin_includes as $file) {
	require_once plugin_dir_path(__FILE__) . 'includes' . $file;
}
