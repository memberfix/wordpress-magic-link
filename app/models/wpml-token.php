<?php

namespace WPML\Models;

use DateTime;
use WPML\Traits\WPML_Database;

if(!class_exists('WPML_Token')){
    class WPML_Token{
        use WPML_Database;

		static string $infinite = '0000-00-00 00:00:00';
        private string $table = 'tokens';
		private array $fields = ['id' ,'unique_token', 'user_id', 'created_at', 'expires_at', 'status', 'uses'];

		static function check_token(string $token)
		{
			global $wpdb;

			if($query = $wpdb->get_results("SELECT * FROM wp_wpml_tokens WHERE unique_token = '" . $token . "';")){
				return new static($query[0]->id);
			}

			return false;
		}

		static function update_status($token)
        {
			if($token->uses == 1){
				$token->status = 0;
				$token->save();

				return $token;
			}
		}

		static function deduct_use($token): bool
		{
			if($token->uses == 0){
				return true;
			}

			if($token->uses == 1){
				return true;
			}

			$token->uses = intval($token->uses) - 1;
			$token->save();

			return true;
		}

		static function exists($id): bool
		{
			global $wpdb;

			if($wpdb->get_row('SELECT * FROM wp_wpml_tokens WHERE id=' . $id)){
				return true;
			}

			return false;
		}

		public function __construct($id = null)
		{
			if($id === null){
				return $this->new();
			}

			return $this->load_by_id($id);
		}

	    public function init(): void
        {
            if(!$this->table_exists($this->table)){
                $this->create_table($this->generate_table_name($this->table), "
                    id mediumint(9) NOT NULL AUTO_INCREMENT,
                    unique_token varchar(255) NOT NULL,
                    user_id mediumint(9) NOT NULL,
                    created_at datetime NOT NULL,
                    expires_at datetime NOT NULL,
                    status int(1) DEFAULT 0,
                    uses mediumint(9) DEFAULT 0,
                    PRIMARY KEY  (id)
                ");
            }
        }

		public function generate(int $user_id)
		{
			if(!empty($this->user_id)){
				return $this;
			}

			$length = get_option('wpml-expiry');

			$created_at = new \DateTime();
			$interval = \DateInterval::createFromDateString($length . 'minutes');

			$this->user_id = $user_id;
			$this->created_at = $created_at->format(WPML_TIME_FORMAT);

			if($length == 0){
				$this->expires_at = self::$infinite;
			}else{
				$this->expires_at = $created_at->add($interval)->format(WPML_TIME_FORMAT);
			}

			$this->unique_token = hash('sha256', uniqid($user_id) . $this->created_at);
			$this->status = 1;
			$this->save();

			return $this;
		}

		public function load_by_id(int $id)
		{
			global $wpdb;

			$object = $wpdb->get_results('SELECT * FROM ' . $this->generate_table_name($this->table) . ' WHERE id=' . $id . ';');

			if($object){
				$this->format($object);

				return $this;
			}

			return false;
		}

		public function save()
		{
			if($this->validation()){
				global $wpdb;

				if(empty($this->id) || !self::exists($this->id)) {
					$wpdb->insert($this->generate_table_name( $this->table ), [
						'unique_token' => $this->unique_token,
						'user_id'      => $this->user_id,
						'created_at'   => $this->created_at,
						'expires_at'   => $this->expires_at,
						'uses' => $this->uses,
						'status' => $this->status,
					] );
				}else{
					$wpdb->update($this->generate_table_name( $this->table ), [
						'unique_token' => $this->unique_token,
						'user_id'      => $this->user_id,
						'created_at'   => $this->created_at,
						'expires_at'   => $this->expires_at,
						'uses' => $this->uses,
						'status' => $this->status,
					], ['id' => $this->id]);
				}

				$this->format(array(self::check_token($this->unique_token)));

				return $this;
			}

			return false;
		}

		public function is_active(): bool
		{
			if($this->expires_at == self::$infinite){
				if($this->status === 1){
					return true;
				}

				return false;
			}

			$now = new DateTime();
			$expires = DateTime::createFromFormat(WPML_TIME_FORMAT, $this->expires_at);

			if($this->status == 1 && $now < $expires){
				return true;
			}

			return false;
		}

		private function validation(): bool
		{
			if(!is_string($this->unique_token)){
				return false;
			}

			if(!is_numeric($this->user_id)){
				return false;
			}

			if(!get_user_by('id', $this->user_id)){
				return false;
			}

			if((new DateTime()) < \DateTime::createFromFormat(WPML_TIME_FORMAT, $this->created_at)){
				return false;
			}

			return true;
		}

		private function new()
		{
			foreach ($this->fields as $field){
				$this->{$field} = '';
			}

			$this->uses = get_option('wpml-uses');

			return $this;
		}

		private function format($object)
		{
			foreach($this->fields as $field){
				$this->{$field} = $object[0]->{$field};
			}

			return $this;
		}
    }

    new WPML_Token();
}