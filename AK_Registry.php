<?php

class AK_Registry {
	
	/**
	 * レジストリ配列
	 * @var array
	 */
	public static $registryArray = array();
	
	/**
	 * レジストリ取得
	 * @param string $key
	 */
	public static function get( $key ) {
		return (array_key_exists( $key, self::$registryArray ) === TRUE) ? self::$registryArray[$key] : NULL;
	}
	
	/**
	 * レジストリ設定
	 * @param string $key
	 * @param mixed $value
	 */
	public static function set( $key,  $value ) {
		self::$registryArray[$key] = $value;
	}
	
	/**
	 * レジストリ全削除
	 */
	public static function flush() {
		self::$registryArray = array();
	}
	
	/**
	 * 全レジストリ取得
	 */
	public static function getAllRegistry() {
		return self::$registryArray;
	}
	
	/**
	 * 登録済チェック
	 * @param string $key
	 * @return boolean
	 */
	public static function isRegistry( $key ) {
		return array_key_exists( $key, self::$registryArray );
	}
	
}