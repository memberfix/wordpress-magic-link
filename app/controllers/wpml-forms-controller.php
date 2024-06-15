<?php

namespace WPML\Controllers;

use WPML\Lib\WPML_Form;
use WPML\Models\WPML_Notification;
use WPML\Models\WPML_Token;
use const WPML\WPML_JS;

class WPML_Forms_Controller{
    public static function form_html(): string
    {
        // CSS
        $contents = '<style>';
        $contents .= '.wpml-login-btn { float:none !important; }';
        $contents .= '.wpml-login-heading { margin-bottom:10px; text-align: center; }';
        $contents .= '.wpml-error-border { border: 1px red solid !important; transition: 0.3s; }';
        $contents .= '.wpml-error-message { font-size: 10px; color:red; margin-top: -10px; margin-bottom: 10px; }';
        $contents .= '.wpml-d-none { display:none !important; }';
        $contents .= '.lds-dual-ring { display: inline-block; width: 80px; height: 80px; }';
        $contents .= '.lds-dual-ring:after { content: " "; display: block; width: 40px; height: 40px; margin: 5px; border-radius: 50%; border: 6px solid blue; border-color: blue transparent blue transparent; animation: lds-dual-ring 1.2s linear infinite; }';
        $contents .= '@keyframes lds-dual-ring { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }';
        $contents .= '.wpml-loader-wrap { display:flex; align-content: center; justify-content: center; }';
        $contents .= '.wpml-green-text { color: green !important; }';
        $contents .= '.wpml-form-group label { display:block; }';
        $contents .= '</style>';

        // HTML
        $contents .= '<form id="wpml-magic-login" action="' . esc_url( admin_url( 'admin-post.php' ) ) . '" method="POST">';
        $contents .= '<input type="hidden" name="action" value="wpml-magic-login">';
        $contents .= '<input type="hidden" name="nonce" value="'. wp_create_nonce('wpml-magic-login') .'">';
        $contents .= '<h3 id="success-heading" class="wpml-login-heading wpml-green-text wpml-d-none"></h3>';
        $contents .= '<h3 id="title-heading" class="wpml-login-heading">' . __('Request a login link', \WPML\WPML_SLUG ) . '</h3>';
        $contents .='<div class="wpml-form-group">';
        $contents .= '<label id="wpml-magic-email-label" for="wpml-magic-email">' . __('Email Address', \WPML\WPML_SLUG) . '</label>';
        $contents .= '<input type="email" id="wpml-magic-email" name="wpml-magic-email" class="wpml-login-email input" required>';
        $contents .= '<input type="submit" id="wpml-magic-submit" name="wpml-magic-submit" class="wpml-login-btn button button-primary button-large">';
        $contents .= '</div>';
        $contents .= '</form>';

        return $contents;
    }

	public function __construct()
    {
		add_action('login_footer', [$this, 'wp_form']);
		add_action('wp_ajax_nopriv_wpml-magic-login', [$this, 'callback']);
	}

	public function callback(): void
	{
		if(WPML_Form::check_nonce($_POST['nonce'], 'wpml-magic-login')){
			$email = sanitize_email($_POST['wpml-email']);

			if($user = get_user_by('email', $email)){
				$token = new WPML_Token();
				$token->generate($user->ID);

				new WPML_Notification($token);

				echo json_encode([
					'status' => 'success',
					'message' => 'Check your email for the login link!'
				]);

				return;
			}

			echo json_encode([
				'status' => 'error',
				'message' => 'There is no user with that email address'
			]);

			return;
		}

		echo json_encode([
			'status' => 'error',
			'message' => 'Something went wrong with your request'
		]);
	}

	public function wp_form(): void
	{
		if ( get_option( 'wpml-wp-form' ) == 'true' ) {
			new \WPML\Lib\WPML_View( 'widgets/wp_form' );
		}
	}
}

new WPML_Forms_Controller();