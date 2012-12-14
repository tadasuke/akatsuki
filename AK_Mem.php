<?php

require_once 'ak_mem/AK_MemConfig.php';

/**
 * Memcacheクラス
 * @author TADASUKE
 */
class AK_Mem extends Memcache{
	
	const DEFAULT_PORT = 11211;
	
	const GET_TYPE_VARIABLE = 1;
	const GET_TYPE_MEMCACHE = 2;
	
	/**
	 * 圧縮フラグ
	 * @var int
	 */
	private static $compressedFlg = NULL;
	public static function setCompressedFlg( $compressedFlg ) {
		self::$compressedFlg = $compressedFlg;
	}
	public static function getCompressedFlg() {
		return self::$compressedFlg;
	}
	
	/**
	 * 自動コミットフラグ
	 * @var boolean
	 */
	private static $autoCommitFlg = FALSE;
	public static function setAutoCommitFlg( $autoCommitFlg ) {
		self::$autoCommitFlg = $autoCommitFlg;
	}
	public static function getAutoCommitFlg() {
		return self::$autoCommitFlg;
	}
	
	/**
	 * インスタンス
	 * @var AK_Mem
	 */
	private static $instance = NULL;
	
	/**
	 * 値配列
	 * @var array
	 */
	private $valueArray = array();
	
	/**
	 * 獲得タイプ
	 * @var int
	 */
	private $getType = NULL;
	public function getGetType() {
		return $this -> getType;
	}
	
	/**
	 * インスタンス取得
	 * @param AK_MemConfig
	 * @return AK_Mem
	 */
	public static function getInstance( AK_MemConfig $config = NULL ) {
		if ( is_null( self::$instance ) === TRUE ) {
			self::$instance = new self( $config );
		} else {
			;
		}
		return self::$instance;
	}
	
	//-------------------------- コンストラクタ -----------------------------
	
	/**
	 * コンストラクタ
	 * @param AK_MemConfig
	 */
	private function __construct( AK_MemConfig $akMemConfig = NULL ) {
		if ( is_null( $akMemConfig ) === FALSE ) {
			$this -> addServer( $akMemConfig );
		} else {
			;
		}
	}
	
	//---------------------------- デストラクタ ------------------------------
	
	/**
	 * デストラクタ
	 */
	public function __destruct() {
		if ( self::$autoCommitFlg === TRUE ) {
			$this -> commit();
		} else {
			;
		}
	}
	
	
	//--------------------------- public ----------------------------------
	
	/**
	 * セット
	 * @param string $key
	 * @param mixed $value
	 * @param int $keepTime
	 */
	public function set( $key, $value, $keepTime = 0 ) {
		//parent::set( $key, $value, 0, 0 );
		$this -> valueArray[$key] = array(
			  'value'       => $value
			, 'keep_time'   => $keepTime
			, 'set_mem_flg' => TRUE
		);
	}
	
	/**
	 * ゲット
	 * @param string $key
	 * @return mixed
	 */
	public function get( $key ) {
		if ( isset( $this -> valueArray[$key] ) === FALSE ) {
			$value = parent::get( $key );
			$this -> valueArray[$key] = array(
				  'value'       => $value
				, 'keep_time'   => 0
				, 'set_mem_flg' => FALSE
			);
			$this -> getType = self::GET_TYPE_MEMCACHE;
		} else {
			$value = $this -> valueArray[$key]['value'];
			$this -> getType = self::GET_TYPE_VARIABLE;
		}
		return $value;
	}
	
	/**
	 * 削除
	 * @param string $key
	 */
	public function delete( $key ) {
		parent::delete( $key );
		unset( $this -> valueArray[$key] );
	}
	
	/**
	 * 初期化
	 */
	public function flush() {
		parent::flush();
		$this -> valueArray = array();
	}
	
	/**
	 * 接続先追加
	 * @param mixed
	 */
	public function addServer( $akMemConfigArray ) {
	
		if ( is_array( $akMemConfigArray ) === FALSE ) {
			$akMemConfigArray = array( $akMemConfigArray );
		} else {
			;
		}
		
		// Memcacheに接続
		foreach ( $akMemConfigArray as $akMemConfig ) {
			$result = parent::addServer( $akMemConfig -> getHostName(), $akMemConfig -> getPort(), FALSE );
		}
	
	}
	
	/**
	 * コミット
	 */
	public function commit() {
		foreach ( $this -> valueArray as $key => $data ) {
			if ( $data['set_mem_flg'] === TRUE ) {
				parent::set( $key, $data['value'], $data['keep_time'], self::$compressedFlg );
			} else {
				;
			}
		}
	}
	
	/**
	 * ロールバック
	 */
	public function rollback() {
		$this -> valueArray = array();
	}
	
}