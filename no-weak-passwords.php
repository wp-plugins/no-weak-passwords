<?php

/*
Plugin Name: No Weak Passwords
Version: 1.0.1
Plugin URI: http://wordpress.org/extend/plugins/no-weak-passwords
Description: Forbid use of any password from an included list of the most easily guessable
Author: David Anderson
Donate: http://david.dw-perspective.org.uk/donate
Author URI: http://david.dw-perspective.org.uk
License: MIT
*/


// Globals
define ('NOWEAKPASSWORDS_SLUG', "no-weak-passwords");
define ('NOWEAKPASSWORDS_DIR', WP_PLUGIN_DIR . '/' . NOWEAKPASSWORDS_SLUG);
define ('NOWEAKPASSWORDS_VERSION', '1.0.1');

// Add our hook to investigate prospective new passwords
add_action('user_profile_update_errors', 'no_weak_passwords_update', 1, 3);

// Add our hook to refuse logins on existing passwords
// The regular WordPress login goes with priority 20 - so we go higher in order to run later, and thus avoid indicating account status if their password already failed. Let's go much higher, as our only function is to over-rule a granted login.
// See WP's own use in http://svn.automattic.com/wordpress/tags/3.4/wp-includes/user.php
// This function is defined in the library
add_filter('authenticate', 'no_weak_passwords_authenticate_login', 999, 3);
# This is a filter for authenticate - it checks (for non-admin users) that the password is not forbidden
function no_weak_passwords_authenticate_login($user, $username, $password) {

	// If WP has not successfully obtained a user object, then bail out already
	if ( !is_a($user, 'WP_User') ) return $user;

	$code = no_weak_passwords_isonlist($password);

	if ($code == 2) {
		return new WP_Error( 'could_not_check_password', __( '<strong>ERROR</strong>: We tried to check the strength of this password, but failed. Please contact your site admin (reference: plugin: no-weak-passwords).' ));
	} elseif ($code == 1) {
		return new WP_Error( 'forbidden_password', __( '<strong>ERROR</strong>: Your password exists on a list of known easy-to-guess passwords, and has been forbidden by the administrator. You <strong>must</strong> use the "Lost Password" function to obtain a new password for yourself before you can log in again.' ));
	}

	return $user;
}

// Add our hook to display an options page for our plugin in the admin menu
add_action('admin_menu', 'no_weak_passwords_options_menu');
function no_weak_passwords_options_menu() {
	# http://codex.wordpress.org/Function_Reference/add_options_page
	add_options_page('No Weak Passwords', 'No Weak Passwords', 'manage_options', 'no_weak_passwords', 'no_weak_passwords_options_printpage');
}

function no_weak_passwords_update( $errors, $update, $user ) {

	if (isset($user->user_pass)) {

		$code = no_weak_passwords_isonlist($user->user_pass);

		if ($code == 2) {
			$errors->add( 'could_not_check_password', __( '<strong>ERROR</strong>: We tried to check the strength of this password, but failed. Please contact your site admin (reference: plugin: no-weak-passwords).' ));
		} elseif ($code == 1) {
			$errors->add( 'forbidden_password', __( '<strong>ERROR</strong>: Your password exists on a list of known easy-to-guess passwords, and hence was forbidden.' ));
		}

	}

}

function no_weak_passwords_isonlist($password) {

	$passfile = NOWEAKPASSWORDS_DIR.'/password-2011.lst';
	if (!is_readable($passfile)) return 2;

	if (! ($handle = @fopen($passfile, "r"))) return 2;

	while (($buffer = fgets($handle, 4096)) !== false) {
		$fline = trim($buffer);
		if (strpos($fline, "#!comment") === false && $password == $fline) {
			fclose($handle);
			return 1;
		}
	}
	if (!feof($handle)) return 2;
	fclose($handle);

	// Passed the checks. Return.
	return 0;

}

add_filter( 'plugin_action_links', 'no_weak_passwords_action_links', 10, 2 );
function no_weak_passwords_action_links($links, $file) {

	if ( $file == NOWEAKPASSWORDS_SLUG."/".NOWEAKPASSWORDS_SLUG.".php" ){
		array_unshift( $links, 
			'<a href="options-general.php?page=no_weak_passwords">Settings</a>',
			'<a href="http://wordshell.net">WordShell - WordPress from the CLI</a>',
			'<a href="http://david.dw-perspective.org.uk/donate">Donate</a>'
		);
	}

	return $links;

}

# This is the function outputing the HTML for our options page
function no_weak_passwords_options_printpage() {
	if (!current_user_can('manage_options'))  {
		wp_die( __('You do not have sufficient permissions to access this page.') );
	}

	$pver = NOWEAKPASSWORDS_VERSION;

	echo <<<ENDHERE
<div class="wrap">
	<h1>No Weak Passwords (version $pver)</h1>

	Maintained by <strong>David Anderson</strong> (<a href="http://david.dw-perspective.org.uk">Homepage</a> | <a href="http://wordshell.net">WordShell - WordPress command line</a> | <a href="http://david.dw-perspective.org.uk/donate">Donate</a> | <a href="http://wordpress.org/extend/plugins/no-weak-passwords/faq/">FAQs</a>)
	</p>

<div style="width:650px; float: left; margin-right: 20px;">
	<h2>Other great plugins and WordPress products</h2>

<p><a href="http://wordshell.net"><strong>WordShell (WordPress from the CLI)</strong></a><br>Manage and maintain all your WordPress installations from the command-line - <strong>huge time saver.</strong></p>

<p><strong><a href="http://wordpress.org/extend/plugins/updraftplus">UpdraftPlus (backup plugin)</strong></a><br>Automated, scheduled WordPress backups via email, FTP, Amazon S3 or Google Drive
</p>

<p><strong><a href="http://www.simbahosting.co.uk">WordPress maintenance and hosting</strong></a><br>We recommend Simba Hosting - 1-click WordPress installer and other expert services available - since 2007</p>

<p><strong><a href="http://wordpress.org/extend/plugins/use-administrator-password">Use Administrator Password (plugin)</strong></a><br>When installed, this plugin allows any administrator to use their own password to log in to any valid user's account. Very useful for logging in as another user without having to change passwords back and forth.</p>

<p><strong><a href="http://wordpress.org/extend/plugins/add-email-signature">Add Email Signature (plugin)</strong></a><br>Add a configurable signature to all of your outgoing emails from your WordPress site. Add branding, or fulfil regulatory requirements, etc.</p>

	<h2>No Weak Passwords FAQs</h2>
	<p>This plugin has no configurable options... but to help you, here are the FAQs:</p>

<p><strong>Where are the configuration settings?</strong><br>
There are none. If the plugin is active, then it is banning all of its known weak passwords.</p>

<p><strong>What if one of my users is already using one of those passwords?</strong><br>
If they try to log in with one of these weak passwords, then they will not succeed, and they will be told to use the 'Lost Password' procedure to obtain a new password.</p>

<p><strong>What passwords does this plugin ban?</strong><br>
The 3546 listed in the "common passwords list" as obtained from http://www.openwall.com/passwords/wordlists/ on 16th November 2012.</p>

<p><strong>I'd like to change the policy; add some different words; forbid too-short passwords, etc.</strong><br>
Please either send a patch, or <a href="http://david.dw-perspective.org.uk/donate">make a donation on my donation page</a>, and I will be glad to help. Otherwise, this plugin does all I wanted it to do and I've not got time to develop it further without some compensation.</p>
</div>


</div>
ENDHERE;
}

?>
