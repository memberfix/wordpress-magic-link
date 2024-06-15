<h1>Magic Link Login</h1>
<?php

$form = new \WPML\Lib\WPML_Form('wpml-settings');
// Link Availability
$form->heading([
	'id' => 'token-availability',
	'content' => __('Link Availability', \WPML\WPML_SLUG)
], 'h2');

// Minutes
$form->label([
    'label' => __('How many minutes should a link be available for?', \WPML\WPML_SLUG),
    'id' => 'token-expiry-label',
    'for' => 'token-expiry'
]);
$form->number([
    'id' => 'token-expiry',
    'name' => 'token-expiry',
    'default' => get_option('wpml-expiry')
]);

// Uses
$form->label([
    'label' => __('How many times should a link be used?', \WPML\WPML_SLUG),
    'id' => 'token-uses-label',
    'for' => 'token-uses'
]);
$form->number([
	'id' => 'token-uses',
	'name' => 'token-uses',
    'default' => get_option('wpml-uses')
]);

// Infinite notification
$form->label([
	'label' => __('Setting any of the values above to 0 will make them unlimited.', \WPML\WPML_SLUG),
	'id' => 'disclaimer',
	'for' => 'none',
	'class' => 'wpml-red-text'
]);

// User experience
$form->heading([
        'id' => 'experience-heading',
        'content' => __('User Experience', \WPML\WPML_SLUG)
], 'h2');

// Redirection link
$form->label([
	'label' => __('Where should the users be redirected upon logging in?', \WPML\WPML_SLUG),
	'id' => 'login-redirection-label',
	'for' => 'login-redirection'
]);
$form->single_text([
	'id' => 'login-redirection',
	'name' => 'login-redirection',
	'default' => get_option('wpml-login-redirection')
]);

// New users
$form->checkbox([
	'id' => 'new-users',
	'name' => 'new-users',
	'class' => 'wpml-checkbox',
	'checked' => get_option('wpml-new-users'),
    'group' => 'start'
]);
$form->label([
	'label' => __('Should new users receive an email with a login link?', \WPML\WPML_SLUG),
	'id' => 'new-users-label',
	'for' => 'new-users',
	'class' => 'wpml-checkbox-label',
	'group' => 'end'
]);

// WP Form
$form->checkbox([
	'id' => 'wp-form',
	'name' => 'wp-form',
	'class' => 'wpml-checkbox',
	'checked' => get_option('wpml-wp-form'),
	'group' => 'start'
]);
$form->label([
	'label' => __('Display the magic login widget on the WordPress login form?', \WPML\WPML_SLUG),
	'id' => 'wp-form-label',
	'for' => 'wp-form',
	'class' => 'wpml-checkbox-label',
	'group' => 'end'
]);

// Content heading
$form->heading([
	'id' => 'email-content',
	'content' => __('Notification Content', \WPML\WPML_SLUG)
], 'h2');

// Subject
$form->label([
	'label' => __('Subject', \WPML\WPML_SLUG),
	'id' => 'email-subject-label',
	'for' => 'email-subject',
    'group' => 'start'
]);
$form->single_text([
	'id' => 'email-subject',
	'name' => 'email-subject',
	'default' => get_option('wpml-email-subject'),
    'group' => 'end'
]);

// Email Content
$form->label([
	'label' => __('Content', \WPML\WPML_SLUG),
	'id' => 'notification-content-label',
	'for' => 'notification-content',
	'group' => 'start',
    'class' => 'wpml-margin-bottom'
]);

$form->editor(get_option('wpml-notification-content'), 'notification_content', [
    'textarea_rows' => 10,
]);
$form->label([
	'label' => __("{{first_name}} - User's first name", \WPML\WPML_SLUG),
	'id' => 'first_name',
	'for' => 'none',
	'class' => 'wpml-regular-cursor'
]);
$form->label([
	'label' => __("{{token}} - Tokenized login URL", \WPML\WPML_SLUG),
	'id' => 'first_name',
	'for' => 'none',
    'class' => 'wpml-regular-cursor'
]);

$form->submit();
$form->render();
?>
