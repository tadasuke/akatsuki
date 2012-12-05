<?php

require_once 'ak_mem/AK_MemConfig.php';

/**
 * Memcacheクラス
 * @author TADASUKE
 */
class AK_Mem extends Memcache{
	
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
	public static function getInstance( AK_MemConfig $config ) {
		if ( is_null( self::$instance ) ) self::$instance = new self( $config );
		return self::$instance;
	}
	
	//-------------------------- コンストラクタ -----------------------------
	
	/**
	 * コンストラクタ
	 * @param AK_MemConfig
	 */
	private function __construct( AK_MemConfig $akMemConfig ) {
		$this -> _connect( $akMemConfig );
	}
	
	//--------------------------- public ----------------------------------
	
	public function set( $key, $value, $keepTime = 0 ) {
		echo( 'set!!<br/>' );
		parent::set( $key, $value, 0, 0 );
	}
	
	public function get( $key ) {
		echo( 'get!!<br/>' );
		$value = parent::get( $key );
		return $value;
	}
	
	//---------------------------- private ---------------------------------
	
	/**
	 * 接続
	 * @param AK_MemConfig
	 */
	private function _connect( $akMemConfig ) {
	
		// Memcacheに接続
		$result = parent::connect( $akMemConfig -> getHostName(), $akMemConfig -> getPort() );
	
	}
	
}