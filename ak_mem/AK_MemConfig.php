<?php

/**
 * Memcache設定情報クラス
 * @author tadasuke
 */
class AK_MemConfig {
	
	/**
	 * ホスト名
	 * @var string
	 */
	private $hostName = NULL;
	public function getHostName() {
		return $this -> hostName;
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
	 * コンストラクタ
	 * @param string $hostName
	 * @param int $port
	 */
	public function __construct( $hostName, $port ) {
		$this -> hostName = $hostName;
		$this -> port     = $port;
	}
	
}