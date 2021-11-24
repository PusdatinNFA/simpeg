<?php 

// Note: the GenericProvider requires the `urlAuthorize` option, even though
// it's not used in the OAuth 2.0 client credentials grant type.
include "vendor/autoload.php";

class Api_lipi {

	protected $CI;
	public function __construct($params=array()){
		$params_default = array(
			'client_id'                => 'app-perjadin',    // The client ID assigned to you by the provider
			'client_secret'            => 'c91c2a5cc0528eadeb1dd9b79bda7a07',    // The client password assigned to you by the provider
			'redirectUri'             => current_url(),// disini bukan?
			'authorize_url' => 'https://sso.brin.go.id/authorize',
			'token_url'=>'https://api.brin.go.id/oauth2/token',
			'resourceOwner'=>'https://api.brin.go.id/auth/detail'
		);

		$params2 = array_merge($params_default,$params);
		//echo json_encode($params2);
		//die();
		$this->ci = &get_instance();
		$this->provider = new \League\OAuth2\Client\Provider\GenericProvider([
			'clientId'                => $params2['client_id'],    // The client ID assigned to you by the provider
			'clientSecret'            => $params2['client_secret'],    // The client password assigned to you by the provider
			'redirectUri'             => $params2['redirectUri'],
			'urlAuthorize'            => $params2['authorize_url'],
			'urlAccessToken'          => $params2['token_url'],
			'urlResourceOwnerDetails' => $params2['resourceOwner'],
		]);
	}
	
	 
	public function getProvider(){
		return $this->provider;
	}
	public function getAccessToken(){
		//session_start();
		if(isset($_SESSION['accessToken'])){
			
			$accessToken = unserialize($_SESSION['accessToken']);
			//print_r($accessToken);
			if($accessToken->hasExpired()){
				$accessToken = $this->provider->getAccessToken('client_credentials');
				$_SESSION['accessToken'] = serialize($accessToken);
			}
		}
		else {
			$accessToken = $this->provider->getAccessToken('client_credentials');
			$_SESSION['accessToken'] = serialize($accessToken);
		}
		return $accessToken;
	}
	public function authorize(){
		if (!isset($_GET['code'])) {
			// If we don't have an authorization code then get one
			$authUrl = $this->provider->getAuthorizationUrl();
			$this->ci->session->set_userdata('oauth2state',$this->provider->getState());
			header('Location: '.$authUrl);
			exit;

		// Check given state against previously stored one to mitigate CSRF attack
		} elseif (empty($_GET['state']) || ($_GET['state'] !== $_SESSION['oauth2state'])) {
			$this->ci->session->unset_userdata('oauth2state');
			exit('Invalid state');

		} else {

			// Try to get an access token (using the authorization code grant)
			$accessToken = $this->provider->getAccessToken('authorization_code', [
				'code' => $_GET['code']
			]);
			$this->ci->session->set_userdata('lipi_sso_access_token',$accessToken);
			$options['headers']['content-type'] = 'application/json';
			// Optional: Now you have a token you can look up a users profile data
			try {
				$request = $this->provider->getAuthenticatedRequest(
					'GET',
					'https://api.brin.go.id/auth/detail',
					$accessToken,
					$options
				);
				$response = $this->provider->getParsedResponse($request);
				return $response;					
			} catch (Exception $e) {
				echo $e->getMessage();
				// Failed to get user details
				exit('Ups...contact your system administrator.');
				
			}
		}
	}
	public function satker_detail($code){		
		try {
			$accessToken = $this->getAccessToken();
			$request = $this->provider->getAuthenticatedRequest(
				'GET',
				'https://api.brin.go.id/hrms/satuan_kerja?&satker_code='.$code,
				$accessToken
			);
			$response =$this->provider->getParsedResponse($request);
			return json_encode($response);
		}
		 catch (\League\OAuth2\Client\Provider\Exception\IdentityProviderException $e) {
			// Failed to get the access token
			exit($e->getMessage());
		}
	}
}


