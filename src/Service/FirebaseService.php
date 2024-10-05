<?php

namespace App\Service;

class FirebaseService
{
	private \Google_Client $client;
	private string $oauthToken;
	
	public function __construct(
		private readonly string $firebaseServiceAccountAbsPath,
	) {
		$this->client = new \Google_Client();
	}
	
	
	//###> API ###
	
	/**
	 * 
	 */
	public function getOAuthToken(): string {
		return $this->oauthToken;
	}
	
	/**
	 * 
	 */
	public function getClient() {
		return $this->client;
	}
	
	//###< API ###


	/**
	 * 
	 */
	public function configureClient(): static {
		return $this;
		try {
			$this->client->setAuthConfig($this->firebaseServiceAccountAbsPath);
			$this->client->addScope(\Google_Service_FirebaseCloudMessaging::CLOUD_PLATFORM);

			// retrieve the saved oauth token if it exists, you can save it on your database or in a secure place on your server
			//$savedTokenJson = $this->readFile();
			$savedTokenJson = null;

			if (null !== $savedTokenJson) {
				// the token exists, set it to the client and check if it's still valid
				$this->client->setAccessToken($savedTokenJson);
				if ($this->client->isAccessTokenExpired()) {
					// the token is expired, generate a new token and set it to the client
					$accessToken = $this->generateToken();
					$this->client->setAccessToken($accessToken);
				}
			} else {
				// the token doesn't exist, generate a new token and set it to the client
				$accessToken = $this->generateToken();
				$this->client->setAccessToken($accessToken);
			}

			$this->oauthToken = $accessToken["access_token"];

		} catch (\Google_Exception $e) {
			throw $e;
		}
		
		return $this;
	}
	
	/**
	 * 
	 */
	private function generateToken() {
		/**
		 * //TODO: Firebase Выкинул исключение
		 *
		 * Client error: `POST https://oauth2.googleapis.com/token` resulted in a `400 Bad Request` response:
		 * {"error":"invalid_grant","error_description":"Invalid JWT: Token must be a short-lived token (60 minutes) and in a reasonable timeframe. Check your iat and exp values in the JWT claim
		 *
		 */
		$this->client->fetchAccessTokenWithAssertion();
		$accessToken = $this->client->getAccessToken();

		// save the oauth token json on your database or in a secure place on your server
		$tokenJson = \json_encode($accessToken);
		//$this->saveFile($tokenJson);

		return $accessToken;
	}
}
