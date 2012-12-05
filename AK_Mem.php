<?php

require_once 'ak_mem/AK_MemConfig.php';

/**
 * Memcacheクラス
 * @author TADASUKE
 */
class AK_Mem extends Memcache{
	
	const DEFAULT_PORT = 11211;
	
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
	
	//--------------------------- public ----------------------------------
	
	/**
	 * セット
	 * @param string $key
	 * @param mixed $value
	 * @param int $keepTime
	 */
	public function set( $key, $value, $keepTime = 0 ) {
		parent::set( $key, $value, 0, 0 );
	}
	
	/**
	 * ゲット
	 * @param string $key
	 * @return mixed
	 */
	public function get( $key ) {
		$value = parent::get( $key );
		return $value;
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
			$result = parent::addServer( $akMemConfig -> getHostName(), $akMemConfig -> getPort() );
		}
	
	}
	
	//---------------------------- private ---------------------------------
	
}