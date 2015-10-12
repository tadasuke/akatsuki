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
	public function getGoogleClient() {
		
		if ( is_null( $this -> googleClient ) === TRUE ) {
			$this -> setGoogleClient();
		} else {
			;
		}
		return $this -> googleClient;
	}
	
	/**
	 * Gmailアドレス
	 * @var string
	 */
	private $gmailAddress = NULL;
	public function getGmailAddress() {
		if ( is_null( $this -> gmailAddress ) === TRUE ) {
			$this -> setGoogleClient();
		} else {
			;
		}
		return $this -> gmailAddress;
	}
	
	/**
	 * GoogleユーザID
	 * @var string
	 */
	private $googleUserId = NULL;
	public function getGoogleUserId() {
		if ( is_null( $this -> googleUserId ) === TRUE ) {
			$this -> setGoogleClient();
		} else {
			;
		}
		return $this -> googleUserId;
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
	 * GoogleIDセッション有効期限
	 */
	public function getGoogleIdSessionExpirationDate() {
		
		$googleTokenArray = json_decode( $this -> getGoogleClient() -> getAccessToken(), TRUE );
		$expirationDate = $googleTokenArray['created'] + $googleTokenArray['expires_in'];
		
		return new AK_DateTime( '@' . $expirationDate );
		
	}
	
	/**
	 * プロフィールイメージURL取得
	 */
	public function getProfileImageUrl( $userId = 'me' ) {
		
		$plus = new Google_Service_Plus( $this -> getGoogleClient() );
		$imageUrl = $plus -> people -> get( $userId ) -> getImage() -> getUrl();
		return $imageUrl;
		
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
		
		$this -> googleClient = $googleClient;
		
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
			$this -> googleClient -> setAccessToken( $_SESSION[$this -> sessionId] );
				
			// Googleトークンの期限が切れていた場合
			if ( $this -> googleClient -> getAuth() -> isAccessTokenExpired() === TRUE ) {
					
				// アクセストークンを再取得
				$this -> googleClient -> refreshToken( $googleClient -> getRefreshToken() );
			
				// セッションにアクセストークンを設定
				$_SESSION['g_token'] = $this -> googleClient -> getAccessToken();
				
			} else {
				;
			}
		}
		
		// Gmailアドレス、GoogleユーザID設定
		$this -> extractGmailAddressGoogleUserId();
		
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
			
			// コールバックURLにリダイレクト(codeのパラメータを隠すため)
			header( 'Location:' . $this -> getCallbackUrl() );
			exit;
			
		}
		
		return TRUE;
		
	}
	
	
	//---------------------------------------- private static function -----------------------------------------------
	
	
	/**
	 * トークンからGoogleIDを抽出する
	 */
	private function extractGmailAddressGoogleUserId() {
	
		$googleClient = $this -> getGoogleClient();
		$accessToken = json_decode( $googleClient -> getAccessToken(), TRUE );
		$id = explode( '.', $accessToken['id_token'] );
		$id = base64_decode( $id[1] );
		$id = json_decode( $id, TRUE );
		
		$gmailAddress = $id['email'];
		$googleUserId = $id['sub'];
		AK_Log::getLogClass() -> log( AK_Log::DEBUG, __METHOD__, __LINE__, '$gmailAddress:' . $gmailAddress );
		AK_Log::getLogClass() -> log( AK_Log::DEBUG, __METHOD__, __LINE__, '$googleUserId:' . $googleUserId );
		
		$this -> gmailAddress = $gmailAddress;
		$this -> googleUserId = $googleUserId;
	
	}
	
}