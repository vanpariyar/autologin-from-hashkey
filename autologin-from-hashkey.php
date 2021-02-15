<?php
/**
 * Plugin Name:     Autologin From Hashkey
 * Plugin URI:      https://github.com/vanpariyar
 * Description:     This plugin used to generate the Time Based hashkey for the perticuler user and validate then login the user
 * Author:          Vanpariyar
 * Author URI:      https://github.com/vanpariyar
 * Text Domain:     autologin-from-hashkey
 * Domain Path:     /languages
 * Version:         1.0
 *
 * @package         Autologin_From_Hashkey
 */

/**
 * Plugin constants
 */

// Make sure we don't expose any info if called directly
if ( !function_exists( 'add_action' ) ) {
	echo __('Hi there!  I\'m just a plugin, not much I can do when called directly.', 'wppv');
	exit;
}

/** Plugin Constants */
if (!defined('AUTOLOGIN_FROM_HASHKEY_PLUGIN_URI')) {
	define('AUTOLOGIN_FROM_HASHKEY_PLUGIN_URI', plugin_dir_url(__FILE__));
}

if (!defined('AUTOLOGIN_FROM_HASHKEY_FILE_PATH')) {
	define('AUTOLOGIN_FROM_HASHKEY_FILE_PATH', plugin_dir_path(__FILE__));
}


/**
 * Setup Plugin
 */
require_once (AUTOLOGIN_FROM_HASHKEY_FILE_PATH . '/includes/AutologinFromHashkeySettings.php');

/**
 * Shortcode Related Functions
 */
require_once (AUTOLOGIN_FROM_HASHKEY_FILE_PATH . '/includes/AutologinFromHashkeyShortcodes.php');

/**
 * Activation and Deavtivation Hooks
 */
register_activation_hook( __FILE__, array('AutologinFromHashkeySettings','activationHooks') );
register_deactivation_hook(__FILE__, array('AutologinFromHashkeySettings','deactivationHooks'));

/**
 * Main Class
 */
require_once (AUTOLOGIN_FROM_HASHKEY_FILE_PATH . '/includes/AutologinFromHashKey.php');

/**
 * Kickstart Plugin
 */
AutologinFromHashkeyShortcodes::init();
