<?php

require_once 'ak_log/AK_Logging.php';

class AK_Log {
	
	const EMERG  = 0;
	const ALERT  = 1;
	const CRIT   = 2;
	const ERR    = 3;
	const WARN   = 4;
	const NOTICE = 5;
	const INFO   = 6;
	const DEBUG  = 7;
	
	/**
	 * ���M���O�N���X
	 * @var AK_Logging
	 */
	private static $akLoggingClass = NULL;
	
	/**
	 * ���M���O�N���X�Z�b�g
	 * @param string $baseDir
	 * @param int $outLogLevel
	 * @return boolean
	 */
	public static function setAkLoggingClass( $baseDir, $outLogLevel ) {
		
		$now = time();
		$baseDir .= '/' . date( 'Ym', $now );
		$logFileName = date( 'Ymd', $now ) . '.log';
		self::setting( $baseDir );
		
		self::$akLoggingClass = new AK_Logging( $baseDir . '/' . $logFileName, $outLogLevel );
	}
	
	/**
	 * ���M���O�N���X�擾
	 * @return AK_Logging
	 */
	public static function getLogClass() {
		return self::$akLoggingClass;
	}
	
	//---------------------------------- private ------------------------------
	
	/**
	 * ���O�o�͎��O����
	 * @param string $baseDir
	 */
	private static function setting( $baseDir ) {
		
		// �o�̓f�B���N�g�����쐬����
		if ( file_exists( $baseDir ) === FALSE ) {
			mkdir( $baseDir );
		} else {
			;
		}
		
	}
	
}
