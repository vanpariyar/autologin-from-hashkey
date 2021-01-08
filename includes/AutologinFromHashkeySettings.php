<?php
/**
 * Class AutologinFromHashkeySettings
 */

class AutologinFromHashkeySettings{
	public static function activationHooks(){
		flush_rewrite_rules();
	}
	public static function deactivationHooks(){
		flush_rewrite_rules();
	}
}
