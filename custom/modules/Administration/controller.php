<?php
/**
 * Custom Administration controller
 * @author Mihir Parikh 
 */

if(!defined('sugarEntry') || !sugarEntry){
	die('Not a valid entry point');
}

class AdministrationController extends \SugarController {
	public function __construct() {
		parent::__construct ();
	}
	
	/**
	 * Controller for setting up the view & Drupal Connector form submission
	 *
	 * @return void
	 */
	public function action_drupal_connector(){
		// This will call the view.drupalconnector.php file
		$this->view = 'drupalconnector';
	}
	
	/**
	 * TODO Write PHPDoc
	 */
	public function action_test_drupal_connection() {
		// Send a request to Drupal
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $_REQUEST['drupal_url']);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: application/json'));
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(array("name" => $_REQUEST['drupal_username'], "pass" => $_REQUEST['drupal_password'])));
		$response_json = curl_exec($ch);
		$response = json_decode($response_json);
		$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch); 
		
		// Display a success or failure message
		if($httpcode === 200 && (isset($response->csrf_token) && !empty($response->csrf_token) 
				&& isset($response->logout_token) && !empty($response->logout_token)) && isset($response->current_user)){
			// Success
			echo 1;
		}
		else{
			// Failure
			echo 0;
		}
		exit(0);
	}
}

