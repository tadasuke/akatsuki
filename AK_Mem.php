<?php

require_once 'ak_mem/AK_MemConfig.php';

/**
 * Memcacheクラス
 * @author TADASUKE
 */
class AK_Mem extends Memcached
{

	const DEFAULT_PORT      = 11211;
	const DEFAULT_KEEP_TIME = 0;

	const GET_TYPE_VARIABLE = 1;
	const GET_TYPE_MEMCACHE = 2;

	const DEFAULT_MEM_IDENTIFICATION_NAME = 'ak_mem';

	/**
	 * 自動コミットフラグ
	 * @var boolean
	 */
	private static $autoCommitFlg = FALSE;

	public static function setAutoCommitFlg( $autoCommitFlg )
	{
		self::$autoCommitFlg = $autoCommitFlg;
	}

	public static function getAutoCommitFlg()
	{
		return self::$autoCommitFlg;
	}

	/**
	 * デフォルト保持秒数
	 * @var int
	 */
	private $defaultKeepTime = self::DEFAULT_KEEP_TIME;

	public function setDefaultKeepTime( $defaultKeepTime )
	{
		$this->defaultKeepTime = $defaultKeepTime;
	}

	public function getDefaultKeepTime()
	{
		return $this->defaultKeepTime;
	}

	/**
	 * インスタンス配列
	 * @var array[AK_Mem]
	 */
	protected static $instanceArray = [];

	public static function getInstanceArray()
	{
		return self::$instanceArray;
	}

	/**
	 * 値配列
	 * @var array
	 */
	private $valueArray = [];

	/**
	 * 獲得タイプ
	 * @var int
	 */
	private $getType = NULL;

	public function getGetType()
	{
		return $this->getType;
	}

	/**
	 * Memcache識別子
	 * @var string
	 */
	private $identificationName = NULL;

	public function getIdentificationName()
	{
		return $this->identificationName;
	}

	/**
	 * インスタンス設定
	 * @param mixed $config
	 * @param string $identificationName
	 * @return AK_Mem
	 */
	public static function setInstance( $config, $identificationName = self::DEFAULT_MEM_IDENTIFICATION_NAME )
	{
		return self::$instanceArray[$identificationName] = new self( $identificationName, $config );
	}

	/**
	 * インスタンス取得
	 * @param mixed
	 * @return AK_Mem
	 */
	public static function getInstance( $identificationName = self::DEFAULT_MEM_IDENTIFICATION_NAME )
	{
		return self::$instanceArray[$identificationName];
	}

	//-------------------------- コンストラクタ -----------------------------

	/**
	 * コンストラクタ
	 * @param string $identificationName
	 * @param AK_MemConfig $akMemConfig
	 */
	public function __construct( $identificationName, AK_MemConfig $akMemConfig )
	{
		$this->identificationName = $identificationName;
		parent::__construct( $identificationName );
		$this->_addServer( $akMemConfig );
	}

	//---------------------------- デストラクタ ------------------------------

	/**
	 * デストラクタ
	 */
	public function __destruct()
	{
		if ( self::$autoCommitFlg === TRUE ){
			$this->commit();
		} else {
			;
		}
	}


	//--------------------------- public ----------------------------------

	/**
	 * @param string $key
	 * @param mixed $value
	 * @param null $keepTime
	 */
	public function _set( $key, $value, $keepTime = NULL )
	{
		$keepTime = $keepTime ?: $this->defaultKeepTime;

		$this->valueArray[$key] = [
			'value'       => $value,
			'keep_time'   => $keepTime,
			'set_mem_flg' => TRUE,
		];
	}

	/**
	 * ゲット
	 * @param string $key
	 * @param callable $cache_cb [optional]
	 * @param float $cas_token [optional]
	 * @return mixed
	 */
	public function _get( $key, callable $cache_cb = NULL, &$cas_token = NULL )
	{
		if ( isset( $this->valueArray[$key] ) === FALSE ){

			$value = parent::get( $key, $cache_cb, $cas_token );

			$this->valueArray[$key] = [
				'value'       => $value,
				'keep_time'   => 0,
				'set_mem_flg' => FALSE,
			];
			$this->getType          = self::GET_TYPE_MEMCACHE;
		} else {
			$value         = $this->valueArray[$key]['value'];
			$this->getType = self::GET_TYPE_VARIABLE;
		}
		return $value;
	}

	/**
	 * 削除
	 * @param string $key
	 * @param int $time
	 * @return bool
	 */
	public function delete( $key, $time = 0 )
	{
		$result = parent::delete( $key, $time );
		unset( $this->valueArray[$key] );
		return $result;
	}

	/**
	 * 初期化
	 * @param int $delay
	 * @return bool
	 */
	public function flush( $delay = 0 )
	{
		$result           = parent::flush( $delay );
		$this->valueArray = [];
		return $result;
	}

	/**
	 * 接続先追加
	 * @param mixed
	 */
	public function _addServer( $akMemConfigArray )
	{

		if ( is_array( $akMemConfigArray ) === FALSE ){
			$akMemConfigArray = [$akMemConfigArray];
		} else {
			;
		}

		// Memcacheに接続
		foreach ( $akMemConfigArray as $akMemConfig ) {
			parent::addServer( $akMemConfig->getHostName(), $akMemConfig->getPort(), FALSE );
		}

	}

	/**
	 * コミット
	 */
	public function commit()
	{

		foreach ( $this->valueArray as $key => $data ) {

			if ( $data['set_mem_flg'] === TRUE ){
				parent::set( $key, $data['value'], $data['keep_time'] );
			} else {
				;
			}
		}
	}

	/**
	 * ロールバック
	 */
	public function rollback()
	{
		$this->valueArray = [];
	}

}
