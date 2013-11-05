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
	
	const DEFAULT_MEM_IDENTIFICATION_NAME = 'ak_mem';
	
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
	private $defaultKeepTime = self::DEFAULT_KEEP_TIME;
	public function setDefaultKeepTime( $defaultKeepTime ) {
		$this -> defaultKeepTime = $defaultKeepTime;
	}
	public function getDefaultKeepTime() {
		return $this -> defaultKeepTime;
	}
	
	/**
	 * インスタンス配列
	 * @var array[AK_Mem]
	 */
	private static $instanceArray = array();
	public static function getInstanceArray() {
		return self::$instanceArray;
	}
	
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
	 * Memcache識別子
	 * @var string
	 */
	private $identificationName = NULL;
	public function getIdentificationName() {
		return $this -> identificationName;
	}
	
	/**
	 * インスタンス設定
	 * @param mixed $config
	 * @return AK_Mem
	 */
	public static function setInstance( $config, $identificationName = self::DEFAULT_MEM_IDENTIFICATION_NAME ) {
		return self::$instanceArray[$identificationName] = new self( $identificationName, $config );
	}
	
	/**
	 * インスタンス取得
	 * @param mixed
	 * @return AK_Mem
	 */
	public static function getInstance( $identificationName = self::DEFAULT_MEM_IDENTIFICATION_NAME ) {
		return self::$instanceArray[$identificationName];
	}
	
	//-------------------------- コンストラクタ -----------------------------
	
	/**
	 * コンストラクタ
	 * @param mixed
	 */
	private function __construct( $identificationName, $akMemConfig ) {
		$this -> identificationName = $identificationName;
		$this -> _addServer( $akMemConfig );
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
		$keepTime = $keepTime ?: $this -> defaultKeepTime;
		
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
			
			try {
				$value = parent::get( $key );
			}
			catch ( Exception $e ) {
				AK_Log::getLogClass() -> log( AK_Log::ALERT, __METHOD__, __LINE__, 'memcache_connect_error!!' );
				return FALSE;
			}
			
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
				$result = parent::set( $key, $data['value'], self::$compressedFlg, $data['keep_time'] );
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