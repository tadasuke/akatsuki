<?php

class AK_Registry {
	
	/**
	 * ���W�X�g���z��
	 * @var array
	 */
	public static $registryArray = array();
	
	/**
	 * ���W�X�g���擾
	 * @param string $key
	 */
	public static function get( $key ) {
		return (array_key_exists( $key, self::$registryArray ) === TRUE) ? self::$registryArray[$key] : NULL;
	}
	
	/**
	 * ���W�X�g���Z�b�g
	 * @param string $key
	 * @param mixed $value
	 */
	public static function set( $key,  $value ) {
		self::$registryArray[$key] = $value;
	}
	
	/**
	 * ���W�X�g���S�폜
	 */
	public static function flush() {
		self::$registryArray = array();
	}
	
	/**
	 * �S���W�X�g���擾
	 */
	public static function getAllRegistry() {
		return self::$registryArray;
	}
	
}