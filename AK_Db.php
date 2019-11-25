<?php

require_once 'ak_db/AK_DaoFactory.class.php';
require_once 'ak_db/AK_Dao.class.php';
require_once 'ak_db/AK_DbConfig.php';

/**
 * DB基本クラス
 * @author TADASUKE
 */
abstract class AK_Db{
	
	const DEFAULT_DB_IDENTIFICATION_NAME = '0';
	
	/**
	 * DB識別名
	 * @var string
	 */
	private $dbIdemtificationName;
	
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
	
	//-------------------------- コンストラクタ -----------------------------
	
	/**
	 * コンストラクタ
	 * @param string $dbIdenTificationName
	 */
	public function __construct( $dbIdenTificationName = self::DEFAULT_DB_IDENTIFICATION_NAME ) {
		$this -> dbIdemtificationName = $dbIdenTificationName;
	}
	
	//--------------------------- public ----------------------------------
	
	/**
	 * トランザクション開始
	 */
	public function startTransaction() {
		
		$dao = AK_DaoFactory::getDao( $this -> dbIdemtificationName );
		$dao -> startTransaction();
		
	}

	
	/**
	 * セレクト
	 */
	public function select() {
		
		$dao = AK_DaoFactory::getDao( $this -> dbIdemtificationName );
		$this -> valueArray = $dao -> select( $this -> sqlcmd, $this -> bindArray );
		
	}
	
	
	/**
	 * PDOステートメント取得
	 */
	public function getPdoStatement() {
		$dao = AK_DaoFactory::getDao( $this -> dbIdemtificationName );
		return $dao -> getPdoStatement( $this -> sqlcmd, $this -> bindArray );
	}
	
	
	/**
	 * 更新
	 * @return int $returnValue
	 */
	public function exec( $startTransactionFlg = TRUE ) {
		
		$dao = AK_DaoFactory::getDao( $this -> dbIdemtificationName );
		$returnValue = $dao -> exec( $this -> sqlcmd, $this -> bindArray, $startTransactionFlg );
		
		return $returnValue;
	}
	
}