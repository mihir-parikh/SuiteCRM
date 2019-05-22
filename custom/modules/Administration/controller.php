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
		
		// Handle changes after submission
		if(isset($_REQUEST['process']) && $_REQUEST['process'] == 'true'){
			// Server side validation
			$validation_result = $this->validate_inputs($_REQUEST['drupal_url'], $_REQUEST['drupal_username'], $_REQUEST['drupal_password']);
			
			// If the server side validation has passed then only go ahead
			if($validation_result === true){
				$administration_bean = new Administration();
				$administration_bean->retrieveSettings();
				
				$administration_bean->saveSetting("drupal_connector", "drupal_url", html_entity_decode($_REQUEST['drupal_url']));
				$administration_bean->saveSetting("drupal_connector", "drupal_username", $_REQUEST['drupal_username']);
				$administration_bean->saveSetting("drupal_connector", "drupal_password", $_REQUEST['drupal_password']);
				
				SugarApplication::appendSuccessMessage('Yay! Drupal Connector configuration is successfully saved.');
				SugarApplication::redirect('index.php?module=Administration&action=index');				
			}
			else{
				SugarApplication::appendErrorMessage('Mandatory information is missing. Please enter all form fields');
			}
		}	
	}
	
	/**
	 * Controller action sending test request
	 */
	public function action_test_drupal_connection() {
		// Server side validation
		$validation_result = $this->validate_inputs($_REQUEST['drupal_url'], $_REQUEST['drupal_username'], $_REQUEST['drupal_password']);
		
		if($validation_result === false){
			echo -1;
			exit(0);
		}
		
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
	
	/**
	 * Validate the Drupal Connector form inputs
	 * 
	 * @param String $drupal_url URL entered in the form
	 * @param String $drupal_username Drupal username
	 * @param String $drupal_password Drupal Password
	 * 
	 * @return boolean Returns true or false based on validation passed or failed
	 */
	private function validate_inputs($drupal_url, $drupal_username, $drupal_password){
		if(empty($drupal_url) || empty($drupal_username) || empty($drupal_password)){
			return false;
		}
		else {
			return true;
		}
	}
}

