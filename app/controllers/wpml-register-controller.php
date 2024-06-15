<?php

namespace WPML\Controllers;

use WPML\Models\WPML_Notification;
use WPML\Models\WPML_Token;

class WPML_Register_Controller{
	public function __construct()
    {
		add_action('user_register', [$this, 'job']);
	}

	public function job($user_id)
    {
		if(get_option('wpml-new-users') && get_option('wpml-new-users') === 'true'){
			$token = new WPML_Token();
			$token->generate($user_id);

			return new WPML_Notification($token);
		}
	}
}

new WPML_Register_Controller();