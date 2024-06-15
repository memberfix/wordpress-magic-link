<?php

class WPML_Shortcode_Controller{
	public function __construct()
	{
		add_shortcode('wpml_login_form', [$this, 'shortcode']);
	}

	public function shortcode(): string
	{
        $contents = '<style>';
        $contents .= '.wpml-form-group input{ padding:10px 5px; margin-top:5px; }';
        $contents .= '.wpml-login-btn{ padding:5px 10px; margin-left:10px; }';
        $contents .= '</style>';
        $contents .= '<div id="wpml-loader" class="wpml-loader-wrap wpml-d-none">';
        $contents .= '<div class="lds-dual-ring"></div>';
        $contents .= '</div>';
        $contents .= '<div id="wpml-in-form-wrapper">';
        $contents .= $this->form();
        $contents .= '</div>';

        return $contents;
	}

    private function form(): string
    {
        return \WPML\Controllers\WPML_Forms_Controller::form_html();
    }
}

new WPML_Shortcode_Controller();