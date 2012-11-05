<?php

/**
 * データベース接続クラス
 * @author TADASUKE
 */
class Dao {
	
	/**
	 * コネクション
	 * @access private
	 * @var PDO
	 */
	private $connection = null;
	
	/**
	 * トランザクションフラグ
	 * @access private
	 * @var boolean
	 */
	private $transactionFlg = FALSE;
	
	/**
	 * @access private
	 * @var string
	 */
	private $dsn = null;
	
	/**
	 * @access private
	 * @var string
	 */
	private $user = null;
	
	/**
	 * @access private
	 * @var string
	 */
	private $password = null;
	
	
	
	/**
	 * コンストラクタ
	 * @param string $tableType
	 */
	public function __construct( $tableType ) {
		
		//OutputLog::outLog( OutputLog::INFO , __METHOD__, __LINE__, 'START' );
		//OutputLog::outLog( OutputLog::INFO , __METHOD__, __LINE__, 'table_type:' . $tableType );
		
		// 接続情報設定
		$this -> dsn      = Config::getConfig( $tableType, 'dsn' );
		$this -> user     = Config::getConfig( $tableType, 'user' );
		$this -> password = Config::getConfig( $tableType, 'password' );
		//OutputLog::outLog( OutputLog::DEBUG , __METHOD__, __LINE__, 'dsn:'      . $this -> dsn );
		//OutputLog::outLog( OutputLog::DEBUG , __METHOD__, __LINE__, 'user:'     . $this -> user );
		//OutputLog::outLog( OutputLog::DEBUG , __METHOD__, __LINE__, 'password:' . $this -> password );
		
		$this -> connect();
		
		//OutputLog::outLog( OutputLog::INFO , __METHOD__, __LINE__, 'END' );
	}
	
	
	
	/**
	 * SELECT
	 * @param string $sqlcmd
	 * @param array $bindArray
	 */
	public function select( $sqlcmd, $bindArray = array() ) {
		
		//OutputLog::outLog( OutputLog::INFO, __METHOD__, __LINE__, 'START' );
		//OutputLog::outLog( OutputLog::INFO, __METHOD__, __LINE__, 'sqlcmd:' . $sqlcmd );
		/*
		foreach ( $bindArray as $data ) {
			OutputLog::outLog( OutputLog::INFO, __METHOD__, __LINE__, 'data:' . $data[0] . ',' . $data[1] );
		}
		*/
		
		$sth = $this -> connection -> prepare( $sqlcmd );
		$i = 1;
		foreach ( $bindArray as $bind ) {
			$sth -> bindParam( $i, $bind[0], $bind[1] );
			$i++;
		}
		$sth -> execute();
		$valueArray = array();
		while ( $value = $sth -> fetch( PDO::FETCH_ASSOC ) ) {
			/*
			foreach ( $value as $key => $val ) {
				OutputLog::outLog( OutputLog::DEBUG, __METHOD__, __LINE__, $key . '=>' . $val );
			} 
			*/
			$valueArray[] = $value;
		}

		//OutputLog::outLog( OutputLog::INFO , __METHOD__, __LINE__, 'END' );
		
		return $valueArray;
		
	}
	
	
	
	/**
	 * 更新処理実行
	 * @param string $sqlcmd
	 * @param array $bindArray
	 * @return int $returnValue
	 */
	public function exec( $sqlcmd, $bindArray = array() ) {
		
		//OutputLog::outLog( OutputLog::INFO , __METHOD__, __LINE__, 'START' );
		OutputLog::outLog( OutputLog::INFO , __METHOD__, __LINE__, 'sqlcmd:' . $sqlcmd );
		foreach ( $bindArray as $data ) {
			OutputLog::outLog( OutputLog::INFO , __METHOD__, __LINE__, 'data:' . $data[0] . ',' . $data[1] );
		}
		
		// トランザクション開始
		$this -> startTransaction();
		
		$sth = $this -> connection -> prepare( $sqlcmd );
		$i = 1;
		foreach ( $bindArray as $bind ) {
			$sth -> bindParam( $i, $bind[0], $bind[1] );
			$i++;
		}
		$sth -> execute();
		
		$lastInsertId = $this -> getLastInsertId();
		$returnValue = ($lastInsertId == 0) ? $sth -> rowCount() : $lastInsertId;

		OutputLog::outLog( OutputLog::INFO , __METHOD__, __LINE__, 'returnValue:' . $returnValue );
		//OutputLog::outLog( OutputLog::INFO , __METHOD__, __LINE__, 'END' );
		return $returnValue;
		
	}
	
	
	
	/**
	 * ラストインサートID取得処理
	 */
	public function getLastInsertId() {
		
		//OutputLog::outLog( OutputLog::INFO , __METHOD__, __LINE__, 'START' );
		
		$lastInserId = $this -> connection -> lastInsertId();
		
		//OutputLog::outLog( OutputLog::INFO , __METHOD__, __LINE__, 'END' );
		return $lastInserId;
	}
	
	
	
	/**
	 * コミット
	 */
	public function commit() {
		
		// トランザクション処理が開始されていた場合
		if ( $this -> transactionFlg === TRUE ) {
			
			$this -> connection -> commit();
			$this -> transactionFlg = FALSE;
			OutputLog::outLog( OutputLog::INFO, __METHOD__, __LINE__, 'COMMIT!!' );
		} else {
			;
		}
		
	}
	
	
	
	/**
	 * ロールバック
	 */
	public function rollback() {
		
		// トランザクション処理が開始されていた場合
		if ( $this -> transactionFlg === TRUE ) {
			$this -> connection -> rollback();
			$this -> transactionFlg = FALSE;
		} else {
			;
		}
		
	}
	
	/**
	 * テーブルロック解除
	 */
	public function unlockTables() {
		$this -> connection -> exec( "UNLOCK TABLES" );
	}
	
	
	/**
	 * トランザクション開始
	 */
	public function startTransaction() {
		
		// トランザクションが開始されていない場合
		if ( $this -> transactionFlg === FALSE ) {
			$this -> connection -> beginTransaction();
			$this -> transactionFlg = TRUE;
			OutputLog::outLog( OutputLog::INFO, __METHOD__, __LINE__, 'START_TRANSCTION!!:' . $this -> dsn );
		} else {
			;
		}
	}
	
	
	// ------------------------------ private ---------------------------------------
	
	
	
	/**
	 * DB接続
	 */
	private function connect() {
		$this -> connection = new PDO(
			  $this -> dsn
			, $this -> user
			, $this -> password
			, array( PDO::ATTR_PERSISTENT => FALSE )
		);
		$this -> connection -> setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
	}
	
}