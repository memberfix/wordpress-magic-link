<?php

use const WPML\WPML_CSS;
use const WPML\WPML_JS;

class WPML_Enqueue_Controller{
	public function __construct()
    {
		add_action('wp_enqueue_scripts', [$this, 'public_scripts']);
		add_action('login_enqueue_scripts', [$this, 'public_scripts']);
		add_action('admin_enqueue_scripts', [$this, 'css']);
		add_action('wp_enqueue_scripts', [$this, 'css']);
	}

	public function public_scripts(): void
	{
		wp_enqueue_script('wpml-login-ajax', WPML_JS . '/login.js', null, false, true);
		wp_localize_script('wpml-login-ajax', 'wpmlAjax',
			array( 'ajax_url' => admin_url( 'admin-ajax.php')));
	}

	public function css(): void
	{
		wp_enqueue_style('wpml-global-stylesheet', WPML_CSS . '/global.css', null, null);
	}
}

new WPML_Enqueue_Controller();