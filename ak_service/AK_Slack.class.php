<?php

class AK_Slack extends AK_Servcie{
	
	/**
	 * URL
	 * @var string
	 */
	private $url = NULL;
	public function setUrl( $url ) {
		$this -> url = $url;
	}
	public function getUrl() {
		return $this -> url;
	}
	
	
	/**
	 * ポート
	 * @var int
	 */
	private $port = 80;
	public function setPort( $port ) {
		$this -> port = $port;
	}
	public function getPort() {
		return $this -> port;
	}
	
	
	/**
	 * ユーザ名
	 * @var string
	 */
	private $userName = NULL;
	public function setUserName( $userName ) {
		$this -> userName = $userName;
	}
	public function getUserName() {
		return $this -> userName;
	}
	
	
	/**
	 * チャンネル名
	 * @var string
	 */
	private $channel = NULL;
	public function setChannel( $channel ) {
		$this -> channel = $channel;
	}
	public function getChannel() {
		return $this -> channel;
	}
	
	
	/**
	 * コンストラクタ
	 * 親クラス以外からインスタンスを作成できないようにする
	 */
	protected function __construct() {
		;
	}
	
	
	/**
	 * 通知
	 * @param string $string
	 */
	public function notice( $string ) {
		
		// パラメータ設定
		$paramArray = array(
			  'text'     => $string
			, 'username' => $this -> userName
			, 'channel'  => $this -> channel
		);
		$paramJson = json_encode( $paramArray );
		
		// ピンポンダッシュでない場合はCURLを利用
		if ( $this -> pinponDashFlg === FALSE ) {
			$this -> noticeToCurl( $paramJson );
		// ピンポンダッシュの場合はSocketを利用
		} else {
			$this -> noticeToSocket( $paramJson );
		}
		
		return;
		
	}
	
	
	/**
	 * ソケットを使って通知
	 * @param string $paramJson
	 */
	private function noticeToSocket( $paramJson ) {
		
		// URLを分解
		list(, $host) = explode( 'https://', $this -> url );
		list($host, $file) = explode( '/', $host, 2 );
		$domain = 'ssl://' . $host;
		
		// リクエストヘッダ作成
		$request = array(
				'POST /' . $file . ' HTTP/1.1',
				'Host: ' . $host,
				'Content-type: application/x-www-form-urlencoded',
				'Content-length: ' . strlen( $paramJson ) . '',
		);
		
		// ソケットオープン
		$FP = fsockopen( $domain, $this -> port );
		fwrite( $FP, implode( $request, "\r\n" ) . "\r\n\r\n" . $paramJson );
		fclose( $FP );
		
	}
	
	
	/**
	 * CURLを使って通知
	 * @param string $paramArray
	 */
	private function noticeToCurl( $paramJson ) {
		
		$ch = curl_init( $this -> url );
		curl_setopt( $ch, CURLOPT_PORT, $this -> port );
		curl_setopt( $ch, CURLOPT_POST, TRUE );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, TRUE );
		curl_setopt( $ch, CURLOPT_HTTPHEADER, array() );
		curl_setopt( $ch, CURLOPT_POSTFIELDS, $paramJson );
		$this -> result = curl_exec( $ch );
		$this -> error  = curl_error( $ch );
		
		curl_close( $ch );
		
	}
	
}