<?php

namespace CB\ClientBundle\Security;

class OAuthRequestor {

	protected $token_uri = "http://cubbyholeapi.com/oauth/v2/token";
	protected $grant_type_password = "password";
	protected $grant_type_refresh = "refresh_token";
	protected $client_id = "5_1mvh7i7ovq68www8o8c4s48ok8k04s80gkkks800sow8kg04cc";
	protected $client_secret = "5gfc6ip7cn8k8so4scgc0kgo8488g8c4k8cg8co8cc8s4kkggg";
	protected $redirect_uri = "http://cubbyholeclient.com/index";

	protected $userGrants = null;
	protected $browser = null;

	public function setUserGrantsManager(OAuthUserGrants $userGrants) {

		$this->userGrants = $userGrants;
	}

	public function setBroswer($browser) {

		$this->browser = $browser;
	}

	public function getRedirectUri() {

		return $this->redirect_uri;
	}

	public function getAccessToken() {

		return $this->userGrants->getAccessToken();
	}

	public function getUserGrants($usr, $psw) {

        $req = $this->formatUserGrantUri($usr, $psw);

        $serverResponse = $this->browser->get($req);

        $response = json_decode($serverResponse->getContent(), true);

        return $this->avoidResponse($response);		
	}

	public function checkStatus() {


		if ($this->userGrants->hasExpired()) {
			
			// get actual token refresh
			$token_refresh = $this->userGrants->getRefreshToken();
			
			// update values via get request
			$this->getTokenRefresh($token_refresh);

			// return access token
			return array(
				"response_header" => "new refresh token",
                "response_type" => "token",
                "response_text" => "Token refreshed",
                "response_data" => array(
                    "access_token" => $this->getAccessToken()
                )
			);
		}

		// return access token
		return array(
			"response_header" => "",
            "response_type" => "token",
            "response_text" => "Token refreshed",
            "response_data" => array(
                "access_token" => $this->getAccessToken()
            )
		);
	}

	public function getTokenRefresh($refresh_token) {

		$req = $this->formatRefreshTokenUri($refresh_token);

        $serverResponse = $this->browser->get($req);

        $response = json_decode($serverResponse->getContent(), true);

		$this->avoidResponse($response);;

		return 0;
	}

	private function formatUserGrantUri($usr, $psw) {
		$url_formated = $this->token_uri."?grant_type=".$this->grant_type_password."&client_id=".$this->client_id."&client_secret=".$this->client_secret."&username=".$usr."&password=".$psw."&redirect_uri=".$this->redirect_uri;

		return $url_formated;
	}

	private function formatRefreshTokenUri($refresh_token) {

		$url_formated = $this->token_uri."?client_id=".$this->client_id."&client_secret=".$this->client_secret."&grant_type=".$this->grant_type_refresh."&refresh_token=".$refresh_token;

		return $url_formated;
	}

	private function avoidResponse($response) {
		if ($response == null) {
            return 500;
        }
        elseif(array_key_exists('error', $response)) {
            return 206;
        }
        elseif (array_key_exists('access_token', $response) && 
                array_key_exists('refresh_token', $response) &&
                array_key_exists('scope', $response) &&
                array_key_exists('expires_in', $response) &&
                array_key_exists('token_type', $response)) {

			// destroy session vars
			$this->userGrants->deleteSessionVars();

            $this->userGrants->setGrants($response['access_token'], $response['refresh_token'], $response['scope'], $response['expires_in'], $response['token_type']);

            return 200;
        }
            
        return 500;
	}
}