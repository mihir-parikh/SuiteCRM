<?php
/**
 * Drupal Connector View
 * @author Mihir Parikh
 */

if(!defined ('sugarEntry') || !sugarEntry) {
	die ('Not a valid entry point');
}

global $current_user;

// ACL check
if(!is_admin ($current_user)) {
	sugar_die ("Unauthorised access to Administration area");
}

require_once 'include/MVC/View/SugarView.php';

/**
 * Drupal Connector view class
 *
 * @author mparikh
 */
class ViewDrupalConnector extends \SugarView {
	public function __construct() {
		parent::SugarView ();
	}
	
	public function preDisplay() {
		// Set up a custom tpl
		$this->dv = new \stdClass();
		$this->dv->tpl = 'custom/modules/Administration/tpl/drupal_connector.tpl';
	}
	
	public function display() {
		global $mod_strings, $app_strings;
		
		$smarty = new Sugar_Smarty();
		
		// Assign variable for use in smarty template
		$smarty->assign('MOD', $mod_strings);
		$smarty->assign('APP', $app_strings);
		//$smarty->assign('config', $sugar_config['authentication']);
		
		// Administration bean
		$admin = new Administration();
		$admin->retrieveSettings();

		if (array_key_exists('drupal_connector_drupal_url', $admin->settings)) {
			$smarty->assign('DRUPAL_URL', $admin->settings['drupal_connector_drupal_url']);
		}

		if (array_key_exists('drupal_connector_drupal_username', $admin->settings)) {
			$smarty->assign('DRUPAL_USERNAME', $admin->settings['drupal_connector_drupal_username']);
		}

		// Decrypt the password

		if (array_key_exists('drupal_connector_drupal_password', $admin->settings)) {
			$smarty->assign('DRUPAL_PASSWORD', $admin->settings['drupal_connector_drupal_password']);
		}

		// Display the custom tpl
		$smarty->display($this->dv->tpl);
	}

	/**
	 * TODO: Write PHPDoc
	 */
	private function decrypt_password($admin) {
		$crypted_token = $admin->settings['drupal_connector_drupal_password'];
		list($crypted_token, $enc_iv) = explode("::", $crypted_token);
		$cipher_method = 'aes-128-ctr';
		$enc_key = $admin->settings['drupal_connector_drupal_enc_key'];
	}
}

