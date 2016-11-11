<?php

class AK_ChatWork extends AK_Service
{

	/**
	 * URL
	 * @var string
	 */
	private $url = NULL;

	public function setUrl( $url )
	{
		$this->url = $url;
	}

	public function getUrl()
	{
		return $this->url;
	}


	/**
	 * ポート
	 * @var int
	 */
	private $port = 80;

	public function setPort( $port )
	{
		$this->port = $port;
	}

	public function getPort()
	{
		return $this->port;
	}

	/**
	 * ユーザ名
	 * @var string
	 */
	private $userName = NULL;

	public function setUserName( $userName )
	{
		$this->userName = $userName;
	}

	public function getUserName()
	{
		return $this->userName;
	}

	/**
	 * オプション配列
	 * @var array
	 */
	private $optionArray = [];


	/**
	 * コンストラクタ
	 * 親クラス以外からインスタンスを作成できないようにする
	 */
	protected function __construct()
	{
		;
	}

	/**
	 * オプション追加
	 * @param string $key
	 * @param string $value
	 */
	public function addOption( $key, $value )
	{

		$this->optionArray[$key] = $value;

	}

	/**
	 * 通知
	 * @param string $string
	 */
	public function notice( $string )
	{

		$postDataArray = [
			'body' => $string,
		];

		$postData = http_build_query( $postDataArray );

		// ピンポンダッシュでない場合はCURLを利用
		if ( $this->pinponDashFlg === FALSE ){
			$this->noticeToCurl( $postData );
		// ピンポンダッシュの場合はSocketを利用
		} else {
			$this->noticeToSocket( $postData );
		}

		return;

	}


	/**
	 * ソケットを使って通知
	 * @param string $postData
	 */
	private function noticeToSocket( $postData )
	{

		// URLを分解
		list(, $host) = explode( 'https://', $this->url );
		list($host, $file) = explode( '/', $host, 2 );
		$domain = 'ssl://' . $host;

		// リクエストヘッダ作成
		$request = [
			'POST /' . $file . ' HTTP/1.1',
			'Host: ' . $host,
			'Content-type: application/x-www-form-urlencoded',
			'Content-length: ' . strlen( $postData ) . '',
		];

		foreach ( $this->requestHeaderArray as $header ) {
			$request[] = $header;
		}

		// ソケットオープン
		$FP = fsockopen( $domain, '443' );
		fwrite( $FP, implode( $request, "\r\n" ) . "\r\n\r\n" . $postData );
		fclose( $FP );

	}


	/**
	 * CURLを使って通知
	 * @param string $postData
	 */
	private function noticeToCurl( $postData )
	{

		$ch = curl_init( $this->url );
		curl_setopt( $ch, CURLOPT_POST, TRUE );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, TRUE );
		curl_setopt( $ch, CURLOPT_HTTPHEADER, $this->requestHeaderArray );
		curl_setopt( $ch, CURLOPT_POSTFIELDS, $postData );
		$this->result = curl_exec( $ch );
		$this->error  = curl_error( $ch );

		curl_close( $ch );

	}

}