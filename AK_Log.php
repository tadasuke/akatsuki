<?php

require_once 'log/AK_Logging.php';

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
	 * ロギングクラス
	 * @var AK_Logging
	 */
	private static $akLoggingClass = NULL;
	
	/**
	 * ロギングクラスセット
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
	 * ロギングクラス取得
	 * @return AK_Logging
	 */
	public static function getLogClass() {
		return self::$akLoggingClass;
	}
	
	//---------------------------------- private ------------------------------
	
	/**
	 * ログ出力事前準備
	 * @param string $baseDir
	 */
	private static function setting( $baseDir ) {
		
		// 出力ディレクトリを作成する
		if ( file_exists( $baseDir ) === FALSE ) {
			mkdir( $baseDir );
		} else {
			;
		}
		
	}
	
}
