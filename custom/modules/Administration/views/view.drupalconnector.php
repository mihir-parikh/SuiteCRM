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
		$drupal_password = $this->decrypt_password($admin);

		if (array_key_exists('drupal_connector_drupal_password', $admin->settings)) {
			$smarty->assign('DRUPAL_PASSWORD', $drupal_password);
		}

		// Display the custom tpl
		$smarty->display($this->dv->tpl);
	}

	/**
	 * An internal function to decrypt Drupal password
	 * @param Administration $admin Administration bean
	 * @return String Decrypted password
	 */
	private function decrypt_password($admin) {
		$crypted_token = $admin->settings['drupal_connector_drupal_password'];
		list($crypted_token, $enc_iv) = explode("::", $crypted_token);
		$cipher_method = 'aes-128-ctr';
		$enc_key_hex = $admin->settings['drupal_connector_drupal_enc_key'];
		$enc_key = hex2bin($enc_key_hex);
		$drupal_password = openssl_decrypt($crypted_token, $cipher_method, $enc_key, 0, hex2bin($enc_iv));
		unset($crypted_token, $cipher_method, $enc_key, $enc_iv, $enc_key_hex);

		return $drupal_password;
	}
}

