<?php

require_once 'AK_Ssh/AK_SshConnection.class.php';

class AK_Ssh {
	
	
	/**
	 * コネクション配列
	 * @var array[AK_SshConnection]
	 */
	private static $sshConnectionArray = array();
	public static function getSshConnectionArray() {
		return self::$sshConnectionArray;
	}
	
	/**
	 * コネクション設定
	 * @param string $host
	 * @param string $password
	 * @return AK_SshConnection
	 */
	public static function setSshConnection( $host, $id, $password ) {
		self::$sshConnectionArray[$host] = new AK_SshConnection( $host, $id, $password );
		return self::$sshConnectionArray[$host];
	}
	
	/**
	 * 全接続
	 * @return boolean TRUE：全て成功 FALSE:一つでも失敗
	 */
	public static function allConnect() {
		
		$response = TRUE;
		foreach ( self::getSshConnectionArray() as $sshConnection ) {
			$result = $sshConnection -> connect();
			if ( $result === FALSE ) {
				$response = FALSE;
			} else {
				;
			}
		}
		
		return $response;
		
	}
	
	
	/**
	 * コネクションを返す
	 * @param string $hostName
	 * @return AK_SshConnection
	 */
	public static function getSshConnectionByHostName( $hostName ) {
		
		$sshConnectionArray = $this -> getSshConnectionArray();
		$sshConnection = array_key_exists( $hostName, $sshConnectionArray ) ? $sshConnectionArray[$hostName] : NULL;
		
		return $sshConnection;
		
	}
	
	
	/**
	 * 全実行
	 * @param string $cmd
	 * @param boolean $sudoFlg
	 * @return boolean TRUE：全て成功 FALSE:一つでも失敗
	 */
	public static function allExec( $cmd, $sudoFlg = FALSE ) {
		
		$response = TRUE;
		foreach ( self::getSshConnectionArray() as $sshConnection ) {
			$result = $sshConnection -> exec( $cmd, $sudoFlg );
		} 
		
	}

	
}