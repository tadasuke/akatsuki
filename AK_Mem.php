<?php

require_once 'ak_mem/AK_MemConfig.php';

/**
 * Memcacheクラス
 * @author TADASUKE
 */
class AK_Mem extends Memcache{
	
	const DEFAULT_PORT = 11211;
	const DEFAULT_KEEP_TIME = 0;
	
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
	 * デフォルト保持秒数
	 * @var int
	 */
	private static $defaultKeepTime = self::DEFAULT_KEEP_TIME;
	public static function setDefaultKeepTime( $defaultKeepTime ) {
		self::$defaultKeepTime = $defaultKeepTime;
	}
	public static function getDefaultKeepTime() {
		return self::$defaultKeepTime;
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
	 * インスタンス設定
	 * @param mixed $config
	 */
	public static function setInstance( $config ) {
		self::$instance = new self( $config );
	}
	
	/**
	 * インスタンス取得
	 * @param mixed
	 * @return AK_Mem
	 */
	public static function getInstance( $config = NULL ) {
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
	 * @param mixed
	 */
	private function __construct( $akMemConfig = NULL ) {
		if ( is_null( $akMemConfig ) === FALSE ) {
			$this -> _addServer( $akMemConfig );
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
	public function set( $key, $value, $keepTime = NULL ) {
		$keepTime = $keepTime ?: self::$defaultKeepTime;
		
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
	public function _addServer( $akMemConfigArray ) {
	
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
				parent::set( $key, $data['value'], self::$compressedFlg, $data['keep_time'] );
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