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
	
	public function action_drupal_connector(){
		$this->view = 'drupalconnector';
	}
}

