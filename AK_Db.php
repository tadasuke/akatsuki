<?php

require_once 'db/AK_DaoFactory.class.php';

/**
 * DB基本クラス
 * @author TADASUKE
 */
abstract class AK_Db{
	
	//----------
	// DB接続情報
	//----------
	private static $dsn = NULL;
	public static function setDsn( $dsn ) {
		self::$dsn = $dsn;
	}
	private static $user = NULL;
	public static function setUser( $user ) {
		self::$user = $user;
	}
	private static $password = NULL;
	public static function setPassword( $password ) {
		self::$password = $password;
	}
	
	
	/**
	 * テーブル名
	 * @var string
	 */
	protected $tableName;
	public function getTableName() {
		return $this -> tableName;
	}
	
	/**
	 * SQL
	 * @var string
	 */
	protected $sqlcmd;
	public function setSqlCmd( $sqlcmd ) {
		$this -> sqlcmd = $sqlcmd;
	}
	
	/**
	 * バインド値配列
	 * @var array
	 */
	protected $bindArray = array();
	public function setBindArray( $bindArray ) {
		$this -> bindArray = $bindArray;
	}
	
	/**
	 * SELECT結果配列
	 * @var array
	 */
	protected $valueArray = array();
	public function getValueArray() {
		return $this -> valueArray;
	}
	
	
	
	//--------------------------- public ----------------------------------
	
	/**
	 * トランザクション開始
	 */
	public function startTransaction() {
		
		$dao = DaoFactory::getDao( $this -> tableName );
		$dao -> startTransaction();
		
	}

	
	/**
	 * セレクト
	 */
	public function select() {
		
		$dao = DaoFactory::getDao( $this -> tableName );
		$this -> valueArray = $dao -> select( $this -> sqlcmd, $this -> bindArray );
		
	}
	
	
	/**
	 * 更新
	 * @return int $returnValue
	 */
	public function exec() {
		
		$dao = DaoFactory::getDao( $this -> tableName );
		$returnValue = $dao -> exec( $this -> sqlcmd, $this -> bindArray );
		
		return $returnValue;
	}
	
}