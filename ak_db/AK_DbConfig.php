<?php

class AK_DbConfig {
	
	/**
	 * データベース名
	 * @var string
	 */
	private $databaseName = NULL;
	public function getDatabaseName() {
		return $this -> databaseName;
	}
	
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
	
	public function __construct( $databaseName, $host, $user, $password, $dsn = NULL ) {
		$this -> databaseName = $databaseName;
		$this -> host         = $host;
		$this -> user         = $user;
		$this -> password     = $password;
		if ( is_null( $dsn ) === TRUE ) {
			$this -> dsn = 'mysql:dbname=' . $databaseName . ';host=' . $host;
		} else {
			$this -> dsn = $dsn;
		}
	}
	
	
}