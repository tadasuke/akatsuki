<?php

/**
 * データベース接続クラス
 * @author TADASUKE
 */
class AK_Dao {
	
	/**
	 * コネクション
	 * @var PDO
	 */
	private $connection = null;
	
	/**
	 * トランザクションフラグ
	 * @var boolean
	 */
	private $transactionFlg = FALSE;
	
	/**
	 * 接続文字列
	 * @var string
	 */
	private $dsn = null;
	public function getDsn() {
		return $this -> dsn;
	}
	
	/**
	 * ユーザ名
	 * @var string
	 */
	private $user = null;
	
	/**
	 * パスワード
	 * @var string
	 */
	private $password = null;
	
	
	
	/**
	 * コンストラクタ
	 * @param string $tableType
	 */
	public function __construct( AK_DbConfig $akDbConfigObj ) {
		
		$this -> dsn      = $akDbConfigObj -> getDsn();
		$this -> user     = $akDbConfigObj -> getUser();
		$this -> password = $akDbConfigObj -> getPassword();
		
		$this -> connect();
		
	}
	
	
	
	/**
	 * SELECT
	 * @param string $sqlcmd
	 * @param array $bindArray
	 */
	public function select( $sqlcmd, $bindArray = array() ) {
		
		$sth = $this -> connection -> prepare( $sqlcmd );
		$i = 1;
		foreach ( $bindArray as $bind ) {
			$sth -> bindValue( $i, $bind );
			$i++;
		}
		$sth -> execute();
		$valueArray = array();
		while ( $value = $sth -> fetch( PDO::FETCH_ASSOC ) ) {
			$valueArray[] = $value;
		}

		return $valueArray;
		
	}
	
	
	
	/**
	 * 更新処理実行
	 * @param string $sqlcmd
	 * @param array $bindArray
	 * @param boolean $startTransactionFlg
	 * @return int $returnValue
	 */
	public function exec( $sqlcmd, $bindArray = array(), $startTransactionFlg = TRUE ) {
		
		// トランザクション開始
		if ( $startTransactionFlg === TRUE ) {
			$this -> startTransaction();
		} else {
			;
		}
		
		$sth = $this -> connection -> prepare( $sqlcmd );
		$i = 1;
		foreach ( $bindArray as $bind ) {
			$sth -> bindValue( $i, $bind );
			$i++;
		}
		$sth -> execute();
		
		$lastInsertId = $this -> getLastInsertId();
		$returnValue = ($lastInsertId == 0) ? $sth -> rowCount() : $lastInsertId;

		return $returnValue;
		
	}
	
	
	
	/**
	 * ラストインサートID取得処理
	 */
	public function getLastInsertId() {
		
		$lastInserId = $this -> connection -> lastInsertId();
		
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
	 * トランザクション開始
	 */
	public function startTransaction() {
		
		// トランザクションが開始されていない場合
		if ( $this -> transactionFlg === FALSE ) {
			$this -> connection -> beginTransaction();
			$this -> transactionFlg = TRUE;
		} else {
			;
		}
	}
	
	
	/**
	 * 接続解除
	 */
	public function closeConnection() {
		$this -> connection = NULL;
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
			, array(
				  PDO::ATTR_PERSISTENT         => FALSE
			)
		);
		$this -> connection -> setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
	}
	
}