<?php

namespace CB\ClientBundle\Security;

class OAuthUserEntity {

	protected $user_id;
	protected $user_username;
	protected $user_email;
	protected $user_role;

    public function setUserEntity($id, $username, $email, $role) {
    	
    	$this->serializeSet($id, $username, $email, $role);

    	return $this->getUserEntity();
    }

    public function getUserEntity() {

    	return $this->serializeGet();
    }

    public function deleteSessionVars() {
        unset($_SESSION['user_id']);
        unset($_SESSION['user_username']);
        unset($_SESSION['user_email']);
        unset($_SESSION['user_role']);
    }

    private function serializeSet($id, $username, $email, $role) {
    	// set local var
    	$this->user_id = $id;
    	$this->user_username = $username;
    	$this->user_email = $email;
    	$this->user_role = $role;

    	// set Session vars
    	$this->setId($this->user_id);
    	$this->setUsername($this->user_username);
    	$this->setEmail($this->user_email);
    	$this->setRole($this->user_role);    	
    }

    private function serializeGet() {

    	$userEntity = array(
    		"user_id" => $this->getId(),
    		"user_username" => $this->getUsername(),
    		"user_email" => $this->getEmail(),
    		"user_role" => $this->getRole()
		);

    	return $userEntity;
    }

    private function setId($user_id) {
    	$_SESSION['user_id'] = $this->user_id;
    }
    
    private function getId() {
    	if(isset($_SESSION['user_id'])) {
    		return $_SESSION['user_id'];
    	}
    	else {
    		return "session_error";
    	}
    }

    private function setUsername($username) {
    	$_SESSION['user_username'] = $this->user_username;
    }
    
    public function getUsername() {
    	if(isset($_SESSION['user_username'])) {
    		return $_SESSION['user_username'];
    	}
    	else {
    		return "session_error";
    	}
    }

    private function setEmail($email) {
    	$_SESSION['user_email'] = $this->user_email;
    }
    
    private function getEmail() {
    	if(isset($_SESSION['user_email'])) {
    		return $_SESSION['user_email'];
    	}
    	else {
    		return "session_error";
    	}
    }

    private function setRole($role) {
    	$_SESSION['user_role'] = $this->user_role;
    }
    
    private function getRole() {
    	if(isset($_SESSION['user_role'])) {
    		return $_SESSION['user_role'];
    	}
    	else {
    		return "session_error";
    	}
    }

}