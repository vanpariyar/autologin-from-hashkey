<?php
/**
 * Class AutologinFromHashkeyShortcodes
 * 
 * This is extra class to get the user Login link to any page.
 */
class AutologinFromHashkeyShortcodes {
	public function __construct(){

	}

	public static function init(){
        /**
         * add user id as argument in order to use Shortcode
         *
         * @use [GET-USER-HASH-LOGIN-LINK user=%{ user id here }%]
         */
	    add_shortcode('GET-USER-HASH-LOGIN-LINK', array( self::class, 'getUserHashLoginLink') );
    }

    /**
     * @return string
     */
    public static function getUserHashLoginLink( $atts = array(), $content = '' ){

        /**
         * This function is already sanitize values so no need to sanitize this
         */
        $atts = shortcode_atts(
            array(
                'user' => '',
            ), $atts, 'GET-USER-HASH-LOGIN-LINK' );
        return
            get_home_url().'?'
            .AutologinFromHashKey::$queryArgument
            .'='.AutologinFromHashKey::generateUserHashKeyWithTimeStamp($atts['user']);
    }
}
