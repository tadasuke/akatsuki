<?php

class AK_Config {
	
	/**
	 * 設定配列
	 * @var array
	 */
	public static $config = array();
	
	
	/**
	 * 設定
	 * @param string $key
	 * @param mixed $value
	 */
	public static function setConfig( $key, $value ) {
		
		self::$config[$key] = $value;
		
	}
	
	
	/**
	 * 配列から設定
	 * @param array $configArray
	 */
	public static function setConfigFromArray( array $configArray ) {
		foreach ( $configArray as $key => $value ) {
			self::setConfig( $key, $value );
		}
	}
	
	
	/**
	 * 設定返却
	 * @return Ambigous <boolean, multitype:>
	 */
	public static function getConfig() {
		$keyArray = func_get_args();
		$configArray = self::$config;
		foreach ( $keyArray as $key ) {
			if ( array_key_exists( $key, $configArray ) === TRUE ) {
				$configArray = $configArray[$key];
			} else {
				$configArray = FALSE;
				break;
			}
		}
		return $configArray;
	}
	
	
	/**
	 * 全設定データ返却
	 * @return array
	 */
	public static function getAllConfig() {
		return self::$config;
	}
	
}
