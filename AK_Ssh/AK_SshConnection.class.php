<?php

class AK_SshConnection {
	
	
	/**
	 * ホスト名
	 * @var string
	 */
	private $host = NULL;
	public function getHost() {
		return $this -> host;
	}
	
	/**
	 * ID
	 * @var string
	 */
	private $id = NULL;
	public function getId() {
		return $this -> getId();
	}
	
	/**
	 * パスワード
	 * @var string
	 */
	private $password = NULL;
	public function getPassword() {
		return $this -> password;
	}
	
	/**
	 * ポート
	 * @var int
	 */
	private $port = NULL;
	public function getPort() {
		return $this -> port;
	}
	
	/**
	 * リソース
	 * @var unknown
	 */
	private $resource = NULL;
	
	/**
	 * エラーメッセージ
	 * @var string
	 */
	private $lastErrorMessage = NULL;
	public function getLastErrorMessage() {
		return $this -> lastErrorMessage;
	}
	
	/**
	 * ストリームメッセージ
	 * @var string
	 */
	private $streamMessage = NULL;
	public function getStreamMessage() {
		return $this -> streamMessage;
	}
	
	/**
	 * 今ストラクt
	 * @param string $host
	 * @param string $password
	 */
	public function __construct( $host, $id, $password, $port = 22 ) {
		
		$this -> host     = $host;
		$this -> id       = $id;
		$this -> password = $password;
		$this -> port     = $port;
		
	}
	
	
	/**
	 * 接続
	 */
	public function connect() {
		
		try {
		
			$resouce = ssh2_connect( $this -> host, $this -> port );
			$result = ssh2_auth_password( $resouce, $this -> id, $this -> password );
			
			if ( $result === TRUE ) {
				$this -> resource = $resouce;
			} else {
				;
			}
			
		}
		catch( Exception $e ) {
			$this -> lastErrorMessage = $e -> getMessage();
			$result = FALSE;
		}
		
		return $result;
		
	}
	
	
	/**
	 * 実行
	 * @param string $cmd
	 * @param boolean $sudoFlg
	 * @return boolean
	 */
	public function exec( $cmd, $sudoFlg = FALSE ) {
		
		if ( is_null( $this -> resource ) === TRUE ) {
			return FALSE;
		} else {
			;
		}

		// sudoで実行する場合
		if ( $sudoFlg === TRUE ) {
			$cmd = 'echo ' . $this -> password . ' | sudo -S ' . $cmd;
		} else {
			;
		}
		
		$stream = ssh2_exec( $this -> resource, $cmd );
		$errorStream = ssh2_fetch_stream( $stream, SSH2_STREAM_STDERR );
		
		stream_set_blocking( $stream, TRUE );
		stream_set_blocking( $errorStream, TRUE );
		
		$this -> streamMessage = trim( stream_get_contents( $stream ) );
		$this -> lastErrorMessage = trim( stream_get_contents( $errorStream ) );
		
		fclose( $stream );
		fclose( $errorStream );
		
		return TRUE;
		
	}
	
}