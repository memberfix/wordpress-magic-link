<?php

namespace WPML\Controllers;

use WPML\Models\WPML_Token;

class WPML_Activation_Controller{
	public function __construct()
    {
		$this->create_tables();
	}

	public function create_tables(): void
    {
		(new WPML_Token())->init();
	}
}