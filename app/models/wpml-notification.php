<?php

namespace WPML\Models;

class WPML_Notification{
	public function __construct(WPML_Token $token)
    {
		$this->token = $token;

		$this->send();
	}

	private function send()
    {
		$user = get_user_by('id', $this->token->user_id);
		$content = $this->parse_content();

		wp_mail($user->user_email, get_option('wpml-email-subject'), $this->parse_content());
	}

	private function generate_link()
    {
		$base_url = get_permalink(get_option('wpml-login-page'));
		$url = rtrim($base_url, '/') . '?wpml_token=' . $this->token->unique_token;

		return '<a href="'. $url . '">'. $url .'</a>';
	}

	private function parse_content()
    {
		$content = get_option('wpml-notification-content');
		$user = new \WP_User($this->token->user_id);

		// Look for the token shortcode
		$parsed_for_token = str_replace('{{token}}', $this->generate_link(), $content);

		// Look for the first name
		return str_replace('{{first_name}}', $user->first_name, $parsed_for_token);
	}
}