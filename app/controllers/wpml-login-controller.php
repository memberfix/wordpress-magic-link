<?php

namespace WPML\Controllers;

use \WPML\Models\WPML_Token;

class WPML_Login_Controller{
	public static function login(): void
	{
		if($token = WPML_Token::check_token($_GET['wpml_token'])){
			if($token->is_active()){
				self::create_session($token);

				WPML_Token::update_status($token);
				WPML_Token::deduct_use($token);

				self::redirect();
				exit;
			}
		}
	}

	private static function redirect(): void
    {
		global $wp;

		$redirect = get_option('wpml-login-redirection');

		if(empty($redirect)){
            wpml_redirect(home_url($wp->request));
            return;
		}

        wpml_redirect($redirect);
    }

	private static function create_session($token): void
	{
		wp_clear_auth_cookie();
		wp_set_current_user($token->user_id);
		wp_set_auth_cookie($token->user_id, true);
	}

	public function __construct() {
		add_action('the_content', [$this, 'page']);
	}

	public function page($content)
	{
		if(is_page(get_option('wpml-login-page'))){
			if(isset($_GET['wpml_token'])){
				self::login();
			}
		};

		return $content;
	}
}

new WPML_Login_Controller();