<?php

/**
 * Returns a dump of the given property and kills the process
 *
 * @param $body
 *
 * @return bool
 */
if(!function_exists('wpml_dd')){
	function wpml_dd($body): bool
	{
		return \WPML\Helpers\WPML_Helper::dd($body);
	}	
}

/**
 * Redirects people to a given URL with notifications if needed
 *
 * @param $url
 * @param $type
 * @param $message
 *
 * @return bool
 */
if(!function_exists('wpml_redirect')){
	function wpml_redirect($url, $type = null, $message = null): bool
	{
		return \WPML\Helpers\WPML_Helper::redirect($url, $type, $message);
	}
}

/**
 * If an option exists in the WordPress API it updates it. Or creates a new one if it doesn't exist
 *
 * @param $tag
 * @param $value
 *
 * @return bool
 */
if(!function_exists('add_or_update_option')) {
	function add_or_update_option( $tag, $value ): bool {
		return \WPML\Helpers\WPML_Helper::add_or_update_option( $tag, $value );
	}
}

/**
 * If an user meta exists in the WordPress database it updates it. Or creates a new one if it doesn't exist
 *
 * @param $user_id
 * @param $tag
 * @param $value
 *
 * @return bool
 */
if(!function_exists('add_or_update_user_meta')) {
	function add_or_update_user_meta( $user_id, $tag, $value ): bool {
		return \WPML\Helpers\WPML_Helper::add_or_update_user_meta( $user_id, $tag, $value );
	}
}