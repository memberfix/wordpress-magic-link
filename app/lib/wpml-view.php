<?php

namespace WPML\Lib;

use const WPML\WPML_PATH;

if(!class_exists('WPML_View')){
    class WPML_View{
        private string $view;
        private $with;

        public function __construct(string $view, $with = false)
        {
            $this->view = $view;

            if($with){
                $this->with = $with;
            }

            $this->render();
        }

        /**
         * Adds the required CSS for the views
         *
         * @return void
         */

        private function add_styling(): void
        {
            ?>
                <style>
                    .wpml-message-container .wpml-notice{
                        position: relative;
                        padding: .75rem 1.25rem;
                        margin-bottom: 1rem;
                        border: 1px solid transparent;
                        border-radius: .25rem;
                        margin-right:1.25rem;
                        margin-top:1rem;
                    }

                    .wpml-message-container .wpml-alert{
                        background-color: #fff3cd;
                        color: #856404;
                        border-color: #ffeeba;
                    }

                    .wpml-message-container .wpml-success{
                        background-color:#d4edda;
                        color: #155724;
                        border-color:#c3e6cb;
                    }

                    .wpml-message-container .wpml-error{
                        background-color: #f8d7da;
                        color: #721c24;
                        border-color: #f5c6cb;
                    }

                    .wpml-input{
                        display:block;
                    }

                    .wpml-label{
                        display:block;
                    }

                    .wpml-submit{
                        margin-top:15px;
                    }
                </style>
            <?php
        }

        /**
         * Add the notifications partial
         *
         * @return void
         */

        private function add_notifications(): void
        {
            if(isset($_COOKIE['wpml_redirect_type']) && isset($_COOKIE['wpml_redirect_message'])){
                $type = $this->generate_message_class($_COOKIE['wpml_redirect_type']);
                $message = $_COOKIE['wpml_redirect_message'];

                $this->show_redirect_message($type, $message);
            }
        }

        /**
         * The notifications partial
         *
         * @param string $type
         * @param string $message
         * @return void
         */

        private function show_redirect_message(string $type, string $message): void
        {
            ?>
            <div class="wpml-message-container">
                <div class="wpml-notice <?php echo $type; ?>">
                    <p><?php echo $message; ?></p>
                </div>
            </div>

            <script>
                document.cookie = "wpml_redirect_type=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
                document.cookie = "wpml_redirect_message=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
            </script>
            <?php
        }

        /**
         * Generate which HTML class to assign
         *
         * @param string $type
         * @return void
         */

        private function generate_message_class(string $type): string
        {
            switch ($type){
                case 'success':
                    return 'wpml-success';
                case 'alert':
                    return 'wpml-alert';
                case 'error':
                    return 'wpml-error';
            }
         }

        /**
         * Renders the page with the values passed
         *
         * @return mixed
         */

         private function render()
         {
             $this->add_styling();
             $this->add_notifications();

             if(!empty($this->with)){
                 foreach($this->with as $key => $value){
                     ${$key} = $value;
                 }
             }

             return require WPML_PATH . '/app/views/' . $this->view . '.php';
         }
    }
}