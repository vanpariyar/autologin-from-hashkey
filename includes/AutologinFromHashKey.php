<?php

/**
 * Class AutologinFromHashKey
 */
// Your code starts here.
class AutologinFromHashKey {

    public static $queryArgument = 'user-key';

	public static function init() {
		add_action('template_redirect', array( self::class , 'parseUserHashKeyEndpointData') );
		add_action('init', array(self::class, 'addUserHashKeyEndpoint') );
	}

    /**
     * @param null $userId
     * @param int $HASH_KEY_LENGTH
     * @param int $timeStampHours
     * @return string
     *
     * Get the Random Hash key With the expiration timestemp.
     */
    public static function generateUserHashKeyWithTimeStamp( $userId= null , $HASH_KEY_LENGTH = 50, $timeStampHours = 24 ){
        $newHashKey = '';
        if( isset( $userId ) ){
            $newHashKey = wp_generate_password( intval($HASH_KEY_LENGTH), false );

            /**
             * Current Expiration Time same as wordpress Which is One Day
             */
            $expirationTimestamp = current_time('timestamp') + intval($timeStampHours) * HOUR_IN_SECONDS;
            $timestampKey = $expirationTimestamp.':'.$newHashKey;

            /**
             * If user id is provided than we can update the User Meta.
             */

			update_user_meta($userId, 'soft_login_key', $timestampKey);
			return $newHashKey.'.'.base64_encode($userId);
		}

		return $newHashKey;
	}

	/**
	 * @param $userId
	 * @param $urlHashKey
	 * @return bool
	 */
	public static function validateUserHashKeyWithTimeStamp( $userId, $urlHashKey ) : bool {
		$is_valid = false;

		$userHashKey = get_user_meta($userId, 'soft_login_key', true);
		$userHashKey = explode(':', $userHashKey);
		$currentTime = current_time('timestamp');

		if( ( intval( $userHashKey[0] ) > $currentTime ) && ( $userHashKey[1]  == $urlHashKey ) ) {
			$is_valid = true;
		}

		return $is_valid;
	}

	/**
	 * Add User key parameter to the Query
	 *
	 * @hooked 'init'
	 */
	public static function addUserHashKeyEndpoint() {
		$regexMatchParameter = '([^/]+)';
		add_rewrite_tag('%'.self::$queryArgument.'%', $regexMatchParameter);
	}

	/**
	 * See if "user-key" parameter is available
	 *
	 * @hooked 'template_redirect'
	 */
	public static function parseUserHashKeyEndpointData() {
		/**
		 * Can only execute beyond this point if it is part of the 'user-key' endpoint namespace
		 */
		if (!get_query_var('user-key')) {
			return;
		}

		$userQuery =  get_query_var('user-key');
		$userQuery = explode('.', $userQuery);
		$userId = base64_decode( $userQuery[1] );
		$userHashKey = $userQuery[0];

		if( !empty( $userQuery[2] ) ){
			var_dump(self::generateUserHashKeyWithTimeStamp($userId));
		}

		/**
		 * Make the soft Login for the use if is not valid.
		 * remove redirect user to home page without logged in.
		 */
		if( self::validateUserHashKeyWithTimeStamp( $userId, $userHashKey ) ) {
			wp_set_current_user( $userId );
			wp_set_auth_cookie( $userId, true, is_ssl() );
			wp_redirect(home_url().'/');
		}else{
			wp_redirect( home_url() );
		}

	}

}
