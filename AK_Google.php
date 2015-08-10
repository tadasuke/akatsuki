<?php

require_once 'AK_DateTime.php';

class AK_Goole {
	
	/**
	 * クライアントID
	 * @var string
	 */
	private $clientId = NULL;
	public function setClientId( $clientId ) {
		$this -> clientId = $clientId;
	}
	public function getClientId() {
		return $this -> clientId;
	}
	
	/**
	 * クライアントシークレット
	 * @var string
	 */
	private $clientSecret = NULL;
	public function setClientSecret( $clientSecret ) {
		$this -> clientSecret = $clientSecret;
	}
	public function getClientSecret() {
		return $this -> clientSecret;
	}
	
	/**
	 * コールバックURL
	 * @var string
	 */
	private $callbackUrl = NULL;
	public function setCallbackUrl( $callbackUrl ) {
		$this -> callbackUrl = $callbackUrl;
	}
	public function getCallbackUrl() {
		return $this -> callbackUrl;
	}
	
	/**
	 * スコープ
	 * @var array
	 */
	private $scopeArray = array();
	public function setScopeArray( array $scopeArray ) {
		$this -> scopeArray = $scopeArray;
	}
	public function addScope( $scope ) {
		$this -> scopeArray[] = $scope;
	}
	public function getScopeArray() {
		return $this -> scopeArray;
	}
	
	/**
	 * リダイレクト許可フラグ
	 * @var boolean
	 */
	private $permitRedirectFlg = FALSE;
	public function setPermitRedirectFlg( $permitRedirectFlg ) {
		$this -> permitRedirectFlg = $permitRedirectFlg;
	}
	
	/**
	 * セッションID
	 * @var string
	 */
	private $sessionId = NULL;
	public function getSessionId() {
		return $this ->sessionId;
	}
	
	/**
	 * Googleクライアント
	 * @var Google_Client
	 */
	private $googleClient = NULL;
	private function getGoogleClient() {
		if ( is_null( $this -> googleClient ) === TRUE ) {
			$this -> setGoogleClient();
		} else {
			;
		}
		return $this -> googleClient;
	}
	
	//---------------------------------------- public function -----------------------------------------------
	
	/**
	 * コンストラクタ
	 * @param string $sessionId
	 */
	public function __construct( $sessionId ) {
		session_start();
		$this -> sessionId = $sessionId;
	}
	
	/**
	 * グーグルID取得
	 */
	public function getGoogleId() {
		
		// Googleクライアントオブジェクト取得
		$googleClient = $this -> getGoogleClient();
		
		$googleToken = json_decode( $googleClient -> getAccessToken(), TRUE );
		return self::extractGoogleIdByToken( $this -> googleClient );
		
	}
	
	
	
	/**
	 * GoogleIDセッション有効期限
	 */
	public function getGoogleIdSessionExpirationDate() {
		
		$googleTokenArray = json_decode( $this -> getGoogleClient() -> getAccessToken(), TRUE );
		$expirationDate = $googleTokenArray['created'] + $googleTokenArray['expires_in'];
		
		return new AK_DateTime( '@' . $expirationDate );
		
	}
	
	
	//---------------------------------------- private function -----------------------------------------------
	
	/**
	 * GoogleClient設定
	 */
	private function setGoogleClient() {
		
		$googleClient = new Google_Client();
		$googleClient -> setClientId( $this -> clientId );
		$googleClient -> setClientSecret( $this -> clientSecret );
		$googleClient -> setRedirectUri( $this -> callbackUrl );
		$googleClient -> setScopes( $this -> scopeArray );
		$googleClient -> setAccessType( 'offline' );
		$googleClient -> setApprovalPrompt( 'force' );
		
		// セッションからトークンが取得できなかった場合
		if ( isset( $_SESSION[$this -> sessionId] ) === FALSE ) {
			// トークン設定
			$result = $this -> getGoogleTokenByCode();
			
			if ( $result === FALSE ) {
				return NULL;
			} else {
				;
			}
		// セッションからトークンが取得できた場合
		} else {
			$googleClient -> setAccessToken( $_SESSION[$this -> sessionId] );
				
			// Googleトークンの期限が切れていた場合
			if ( $googleClient -> getAuth() -> isAccessTokenExpired() === TRUE ) {
					
				// アクセストークンを再取得
				$googleClient -> refreshToken( $googleClient -> getRefreshToken() );
			
				// セッションにアクセストークンを設定
				$_SESSION['g_token'] = $googleClient -> getAccessToken();
				
			} else {
				;
			}
		}
		
		$this -> googleClient = $googleClient;
		
	}
	
	
	/**
	 * コードを元にGoogleトークンを取得
	 */
	private function getGoogleTokenByCode() {
		
		// GETパラメータにcodeが設定されていない場合
		if ( isset( $_GET['code'] ) === FALSE ) {
			
			// リダイレクトが許可されている場合は、認証URLにリダイレクト
			if ( $this -> permitRedirectFlg === TRUE ) {
				// 認証URLを作成
				$authUrl = $this -> getGoogleClient() -> createAuthUrl();
				
				// 認証URLにリダイレクト
				header( 'Location:' . $authUrl );
				exit;
				
			// リダイレクトが許可されていない場合
			} else {
				return FALSE;
			}
			
		// GETパラメータにcodeが設定されていた場合
		} else {
			
			// アクセストークンを取得
			$this -> googleClient -> authenticate( $_GET['code']);
			
			// アクセストークンをセッションに設定
			$_SESSION[$this -> sessionId] = $this -> googleClient -> getAccessToken();
			
		}
		
		return TRUE;
		
	}
	
	
	//---------------------------------------- private static function -----------------------------------------------
	
	
	/**
	 * トークンからGoogleIDを抽出する
	 * @param Google_Client $googleClient
	 * @return string $gmailAddress
	 */
	private function extractGoogleIdByToken( Google_Client $googleClient ) {
	
		$accessToken = json_decode( $googleClient -> getAccessToken(), TRUE );
		$id = explode( '.', $accessToken['id_token'] );
		$id = base64_decode( $id[1] );
		$id = json_decode( $id, TRUE );
		$googleId = $id['email'];
	
		return $googleId;
	
	}
	
}