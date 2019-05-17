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
}

