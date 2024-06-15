<?php

namespace WPML\Controllers;

use WPML\Lib\WPML_Form;
use WPML\Lib\WPML_View;
use WPML\Models\WPML_Token;
use const WPML\WPML_CSS;

class WPML_Settings_Controller{
	public function __construct()
	{
		add_action('admin_menu', [$this, 'page']);
		add_action('admin_post_wpml-settings', [$this, 'form_callback']);
	}

	public function page(): void
	{
		add_management_page('Magic Link Login', 'Magic Link Login', 'manage_options', 'wpml-settings', [$this, 'page_callback']);
	}

	public function page_callback(): WPML_View
	{
		return new WPML_View('admin/settings');
	}

	public function form_callback(): void
	{
		if(WPML_Form::check_nonce($_POST['nonce'],'wpml-settings')){
			if(!is_numeric($_POST['token-expiry']) || !is_numeric($_POST['token-uses'])){
				wpml_redirect('/wp-admin/tools.php?page=wpml-settings', 'error', 'Some data was invalid. Please check the fields');
			}

			if(empty($_POST['token-expiry'])){
				add_or_update_option('wpml-expiry', '0');
			}else{
				add_or_update_option('wpml-expiry', $_POST['token-expiry']);
			}

			if(empty($_POST['token-uses'])) {
				add_or_update_option( 'wpml-uses', '0' );
			}else{
				add_or_update_option( 'wpml-uses', $_POST['token-uses'] );
			}

			if(empty($_POST['token-delete'])){
				add_or_update_option('wpml-delete', 'true');
			}else{
				add_or_update_option('wpml-delete', 'false');
			}

			if(empty($_POST['login-redirection'])){
				add_or_update_option('wpml-login-redirection', get_site_url());
			}else{
				add_or_update_option('wpml-login-redirection', $_POST['login-redirection']);
			}

			if(isset($_POST['new-users'])){
				add_or_update_option('wpml-new-users', 'true');
			}else{
				add_or_update_option('wpml-new-users', 'false');
			}

			if(isset($_POST['wp-form'])){
				add_or_update_option('wpml-wp-form', 'true');
			}else{
				add_or_update_option('wpml-wp-form', 'false');
			}

			if(isset($_POST['mp-form'])){
				add_or_update_option('wpml-mp-form', 'true');
			}else{
				add_or_update_option('wpml-mp-form', 'false');
			}

			if(isset($_POST['email-subject'])){
				add_or_update_option('wpml-email-subject', $_POST['email-subject']);
			}

			if(isset($_POST['notification_content'])){
				add_or_update_option('wpml-notification-content', $_POST['notification_content']);
			}

			wpml_redirect('/wp-admin/tools.php?page=wpml-settings', 'success', 'Settings were saved!');
		}
	}
}

new WPML_Settings_Controller();