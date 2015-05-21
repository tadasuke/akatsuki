<?php

class AK_DbConfig {
	
	/**
	 * ホスト
	 * @var string
	 */
	private $host = NULL;
	public function getHost() {
		return $this -> host;
	}
	
	/**
	 * DBユーザ名
	 * @var string
	 */
	private $user = NULL;
	public function getUser() {
		return $this -> user;
	}
	
	/**
	 * DBパスワード
	 * @var string
	 */
	private $password = NULL;
	public function getPassword() {
		return $this -> password;
	}
	
	/**
	 * DB接続文字列
	 * @var string
	 */
	private $dsn = NULL;
	public function getDsn() {
		return $this -> dsn;
	}
	
	//--------------------------------- コンストラクタ --------------------------------
	
	public function __construct( $host, $user, $password ) {
		$this -> host     = $host;
		$this -> user     = $user;
		$this -> password = $password;
		$this -> dsn      = 'mysql:host=' . $host;
	}
	
}