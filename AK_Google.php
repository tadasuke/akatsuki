<?php

require_once 'AK_DateTime.php';

class AK_Goole
{

	/**
	 * クライアントID
	 * @var string
	 */
	private $clientId = NULL;

	public function setClientId( $clientId )
	{
		$this->clientId = $clientId;
	}

	public function getClientId()
	{
		return $this->clientId;
	}

	/**
	 * クライアントシークレット
	 * @var string
	 */
	private $clientSecret = NULL;

	public function setClientSecret( $clientSecret )
	{
		$this->clientSecret = $clientSecret;
	}

	public function getClientSecret()
	{
		return $this->clientSecret;
	}

	/**
	 * コールバックURL
	 * @var string
	 */
	private $callbackUrl = NULL;

	public function setCallbackUrl( $callbackUrl )
	{
		$this->callbackUrl = $callbackUrl;
	}

	public function getCallbackUrl()
	{
		return $this->callbackUrl;
	}

	/**
	 * スコープ
	 * @var array
	 */
	private $scopeArray = array ();

	public function setScopeArray( array $scopeArray )
	{
		$this->scopeArray = $scopeArray;
	}

	public function addScope( $scope )
	{
		$this->scopeArray[] = $scope;
	}

	public function getScopeArray()
	{
		return $this->scopeArray;
	}

	/**
	 * リダイレクト許可フラグ
	 * @var boolean
	 */
	private $permitRedirectFlg = FALSE;

	public function setPermitRedirectFlg( $permitRedirectFlg )
	{
		$this->permitRedirectFlg = $permitRedirectFlg;
	}

	/**
	 * セッションID
	 * @var string
	 */
	private $sessionId = NULL;

	public function getSessionId()
	{
		return $this->sessionId;
	}

	/**
	 * Googleクライアント
	 * @var Google_Client
	 */
	private $googleClient = NULL;

	public function getGoogleClient()
	{

		if ( is_null( $this->googleClient ) === TRUE ){
			$this->setGoogleClient();
		} else {
			;
		}
		return $this->googleClient;
	}

	/**
	 * Gmailアドレス
	 * @var string
	 */
	private $gmailAddress = NULL;

	public function getGmailAddress()
	{
		if ( is_null( $this->gmailAddress ) === TRUE ){
			$this->setGoogleClient();
		} else {
			;
		}
		return $this->gmailAddress;
	}

	/**
	 * GoogleユーザID
	 * @var string
	 */
	private $googleUserId = NULL;

	public function getGoogleUserId()
	{
		if ( is_null( $this->googleUserId ) === TRUE ){
			$this->setGoogleClient();
		} else {
			;
		}
		return $this->googleUserId;
	}

	/**
	 * Google認証フラグ
	 * @var boolean
	 */
	private $googleOAuthFlg = FALSE;

	//---------------------------------------- public function -----------------------------------------------

	/**
	 * コンストラクタ
	 * @param string $sessionId
	 */
	public function __construct( $sessionId )
	{
		$this->sessionId = $sessionId;
	}

	/**
	 * Google認証
	 */
	public function googleOAuth()
	{

		// 既に認証済の場合は何もしない
		if ( $this->googleOAuthFlg === TRUE ){
			return;
		} else {
			;
		}

		// セッションからトークンが取得できなかった場合
		session_start();
		if ( isset($_SESSION[$this->sessionId]) === FALSE ){

			// トークン設定
			$result = $this->getGoogleTokenByCode();

			if ( $result === FALSE ){
				return NULL;
			} else {
				;
			}

		// セッションからトークンが取得できた場合
		} else {
			$this->getGoogleClient()->setAccessToken( $_SESSION[$this->sessionId] );
		}

		//--------------------------------
		// Googleトークンの期限が切れていた場合
		//--------------------------------
		$googleClient = $this->getGoogleClient();
		if ( $googleClient->getAuth()->isAccessTokenExpired() === TRUE ){

			// リフレッシュトークンを取得
			$refreshToken = $googleClient->getRefreshToken();

			// リフレッシュトークンが取得できた場合
			if ( is_null( $refreshToken ) === FALSE ){

				// トークンをリフレッシュ
				$googleClient->refreshToken( $refreshToken );

			// リフレッシュトークンが取得できなかった場合
			} else {
				// Google認証
				$googleClient->setApprovalPrompt( 'force' );
				$this->getGoogleTokenByCode();
			}

		} else {
			;
		}

		// トークンからGmailアドレス、GoogleユーザIDを抽出
		$this->extractGmailAddressGoogleUserId();

		// 認証フラグを立てる
		$this->googleOAuthFlg = TRUE;

	}

	/**
	 * GoogleIDセッション有効期限
	 */
	public function getGoogleIdSessionExpirationDate()
	{

		$googleTokenArray = json_decode( $this->getGoogleClient()->getAccessToken(), TRUE );
		$expirationDate = $googleTokenArray['created'] + $googleTokenArray['expires_in'];

		return new AK_DateTime( '@' . $expirationDate );

	}

	/**
	 * プロフィールイメージURL取得
	 */
	public function getProfileImageUrl( $userId = 'me' )
	{

		$plus = new Google_Service_Plus( $this->getGoogleClient() );

		try {
			$people = $plus->people->get( $userId );
		} catch ( Exception $e ) {
			return NULL;
		}

		$imageUrl = $people->getImage()->getUrl();

		// パラメータを除去
		list($imageUrl) = explode( '?', $imageUrl );

		return $imageUrl;

	}


	/**
	 * Googleトークンを取得
	 * @return bool
	 */
	public function getGoogleTokenByCode()
	{

		// GETパラメータにcodeが設定されていない場合
		if ( isset($_GET['code']) === FALSE ){

			// リダイレクトが許可されている場合は、認証URLにリダイレクト
			if ( $this->permitRedirectFlg === TRUE ){
				// 認証URLを作成
				$authUrl = $this->getGoogleClient()->createAuthUrl();

				// アクセス元のURLをセッションに保存
				$protocol = empty($_SERVER['HTTPS']) ? 'http' : 'https';
				$accessUrl = $protocol . '://' . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
				setcookie( 'ACCESS_URL', $accessUrl, time() + 60 );

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
			$this->getGoogleClient()->authenticate( $_GET['code'] );

			// アクセストークンをセッションに設定
			$_SESSION[$this->sessionId] = $this->googleClient->getAccessToken();

			// アクセス元が設定されていた場合
			if ( isset($_COOKIE['ACCESS_URL']) === TRUE ){
				header( 'Location:' . $_COOKIE['ACCESS_URL'] );
				exit;
			} else {
				;
			}

			// コールバックURLにリダイレクト(codeのパラメータを隠すため)
			header( 'Location:' . $this->getCallbackUrl() );
			exit;

		}

	}

	//---------------------------------------- private function -----------------------------------------------

	/**
	 * GoogleClient設定
	 */
	private function setGoogleClient()
	{

		$googleClient = new Google_Client();
		$googleClient->setClientId( $this->clientId );
		$googleClient->setClientSecret( $this->clientSecret );
		$googleClient->setRedirectUri( $this->callbackUrl );
		$googleClient->setScopes( $this->scopeArray );
		$googleClient->setAccessType( 'offline' );
		//$googleClient -> setApprovalPrompt( 'force' );

		$this->googleClient = $googleClient;

	}


	//---------------------------------------- private static function -----------------------------------------------

	/**
	 * トークンからGoogleIDを抽出する
	 */
	private function extractGmailAddressGoogleUserId()
	{

		$googleClient = $this->getGoogleClient();
		$accessToken = json_decode( $googleClient->getAccessToken(), TRUE );
		$id = explode( '.', $accessToken['id_token'] );
		$id = base64_decode( $id[1] );
		$id = json_decode( $id, TRUE );

		$gmailAddress = $id['email'];
		$googleUserId = $id['sub'];

		$this->gmailAddress = $gmailAddress;
		$this->googleUserId = $googleUserId;

	}

}