<?php

namespace WPML\Lib;

use const WPML\WPML_CONTROLLER;
use const WPML\WPML_HELPER;
use const WPML\WPML_LIB;
use const WPML\WPML_MODEL;
use const WPML\WPML_TRAIT;
use const WPML\WPML_INTERFACE;

if(!class_exists('WPML_Loader')) {
	class WPML_Loader {
		public function __construct() {
			$this->help();
			$this->lib();
            $this->traits();
            $this->interfaces();
			$this->models();
			$this->controllers();
		}

		/**
		 * Requires all the library files
		 *
		 * @return $this
		 */
		private function lib()
        {
			foreach ( $this->get_files( WPML_LIB ) as $file ) {
				require_once WPML_LIB . "/$file";
			}

			return $this;
		}

        /**
         * Requires all the traits
         *
         * @return $this
         */
        private function traits()
        {
            foreach ( $this->get_files( WPML_TRAIT ) as $file ) {
                require_once WPML_TRAIT . "/$file";
            }

            return $this;
        }

        /**
         * Requires all the interfaces
         *
         * @return $this
         */
        private function interfaces()
        {
            foreach ( $this->get_files( WPML_INTERFACE ) as $file ) {
                require_once WPML_INTERFACE . "/$file";
            }

            return $this;
        }

		/**
		 * Requires all the helpers
		 *
		 * @return $this
		 */
		private function help()
        {
			foreach ( $this->get_files( WPML_HELPER ) as $file ) {
				require_once WPML_HELPER . "/$file";
			}

			return $this;
		}

		/**
		 * Requires all the models
		 *
		 * @return $this
		 */
		private function models()
        {
			foreach ( $this->get_files( WPML_MODEL ) as $file ) {
				require_once WPML_MODEL . "/$file";
			}

			return $this;
		}

		/**
		 * Requires all the controllers
		 *
		 * @return $this
		 */
		private function controllers()
        {
			foreach ( $this->get_files( WPML_CONTROLLER ) as $file ) {
				require_once WPML_CONTROLLER . "/$file";
			}

			return $this;
		}

		/**
		 * Loops through a directory and returns an array of PHP files
		 *
		 * @param $dir
		 *
		 * @return array
		 */
		private function get_files( $dir ): array {
			$dir_object = new \DirectoryIterator( $dir );
			$returnable = [];

			foreach ( $dir_object as $file ) {
				if ( $file->isDot() || $file->isDir() ) {
					continue;
				}

				if ( $file->getExtension() != 'php' ) {
					continue;
				}

				$returnable[] = $file->getFilename();
			}

			return $returnable;
		}
	}

	new WPML_Loader();
}