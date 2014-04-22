<?php

namespace CB\ClientBundle\Security;

use \DateTime;
use \DateInterval;

class OAuthUserGrants {

    protected $access_token = "test";
    protected $refresh_token;
    protected $scope;
    protected $expires_in;
    protected $token_type; 

    protected $date_income;
    protected $date_outcome;

    public function setGrants($access_token, $refresh_token, $scope, $expires_in, $token_type) {

    	$this->access_token = $access_token;
    	$this->refresh_token = $refresh_token;
    	$this->scope = $scope;
    	$this->expires_in = $expires_in;
    	$this->token_type = $token_type;

    	$this->setTimeout();

    	$this->setSessionVars();

    }

    public function hasExpired() {

    	$this->getSessionVars();
		
		$date1 = new DateTime("now");
		$date2 = new DateTime($this->date_outcome->format('Y-m-d H:i:s'));

		if ($date1 < $date2) {
			return false;
		}
		else {
			return true;
		}
    }

    public function getAccessToken() {
    	
    	$this->getSessionVars();
    	
    	return $this->access_token;
    }

    public function getRefreshToken() {
    	
    	$this->getSessionVars();
    	
    	return $this->refresh_token;
    }

    public function getDateOutcome() {
    	$this->getSessionVars();
    	if ($this->date_outcome != "session_error") {
    		return $this->date_outcome->format('Y-m-d H:i:s');
    	}
    	else {
    		return $this->date_outcome;
    	}
    }

    private function setTimeout() {

    	$this->date_income = new DateTime();
		$this->date_outcome = $this->date_income->add(new DateInterval('PT'.$this->expires_in.'S'));

    }

    private function setSessionVars() {
    	// session_start();
    	$_SESSION['oaug_access_token'] = $this->access_token;
    	$_SESSION['oaug_refresh_token'] = $this->refresh_token;
    	$_SESSION['oaug_scope'] = $this->scope;
    	$_SESSION['oaug_expires_in'] = $this->expires_in;
    	$_SESSION['oaug_token_type'] = $this->token_type;
    	$_SESSION['oaug_date_income'] = $this->date_income;
    	$_SESSION['oaug_date_outcome'] = $this->date_outcome;
    }

    private function getSessionVars() {
    	// session_start();
    	if(!isset($_SESSION['oaug_access_token'])) {
    		$this->access_token = "session_error";
    	}
    	else {
    		$this->access_token = $_SESSION['oaug_access_token'];
    	}

    	if(!isset($_SESSION['oaug_refresh_token'])) {
    		$this->refresh_token = "session_error";
    	}
    	else {
    		$this->refresh_token = $_SESSION['oaug_refresh_token'];
    	}

    	if(!isset($_SESSION['oaug_scope'])) {
    		$this->scope = "session_error";
    	}
    	else {
    		$this->scope = $_SESSION['oaug_scope'];
    	}

    	if(!isset($_SESSION['oaug_expires_in'])) {
    		$this->expires_in = "session_error";
    	}
    	else {
    		$this->expires_in = $_SESSION['oaug_expires_in'];
    	}

    	if(!isset($_SESSION['oaug_token_type'])) {
    		$this->token_type = "session_error";
    	}
    	else {
    		$this->token_type = $_SESSION['oaug_token_type'];
    	}

    	if(!isset($_SESSION['oaug_date_income'])) {
    		$this->date_income = "session_error";
    	}
    	else {
    		$this->date_income = $_SESSION['oaug_date_income'];
    	}

    	if(!isset($_SESSION['oaug_date_outcome'])) {
    		$this->date_outcome = "session_error";
    	}
    	else {
    		$this->date_outcome = $_SESSION['oaug_date_outcome'];
    	}

    }

    public function deleteSessionVars() {
    	unset($_SESSION['oaug_access_token']);
		unset($_SESSION['oaug_refresh_token']);
		unset($_SESSION['oaug_scope']);
		unset($_SESSION['oaug_expires_in']);
		unset($_SESSION['oaug_token_type']);
		unset($_SESSION['oaug_date_income']);
		unset($_SESSION['oaug_date_outcome']);
    }

}