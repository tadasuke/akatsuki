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
	 * ログ出力クラス
	 * @var AK_Logging
	 */
	private static $akLoggingClass = NULL;
	
	/**
	 * ログ出力クラス設定
	 * @param string $baseDir
	 * @param int $outLogLevel
	 * @param string $headerLogFileName
	 */
	public static function setAkLoggingClass( $baseDir, $outLogLevel, $headerLogFileName = NULL ) {
		
		$now = time();
		$baseDir .= '/' . date( 'Ym', $now );
		$logFileName = $headerLogFileName . date( 'Ymd', $now ) . '.log';
		self::setting( $baseDir );
		
		self::$akLoggingClass = new AK_Logging( $baseDir . '/' . $logFileName, $outLogLevel );
	}
	
	/**
	 * インスタンス取得
	 * @return AK_Logging
	 */
	public static function getLogClass() {
		return self::$akLoggingClass;
	}
	
	/**
	 * syslog使用フラグ
	 * @var boolean
	 */
	protected static $useSyslogFlg = FALSE;
	public static function setUseSyslogFlg( $useSyslogFlg ) {
		self::$useSyslogFlg = $useSyslogFlg;
	}
	
	//---------------------------------- private ------------------------------
	
	/**
	 * ログ出力先設定
	 * @param string $baseDir
	 */
	private static function setting( $baseDir ) {
		
		// ログ出力先ディレクトリが存在しなければ作成
		if ( self::$useSyslogFlg === FALSE ) {
			if ( file_exists( $baseDir ) === FALSE ) {
				mkdir( $baseDir );
			} else {
				;
			}
		} else {
			;
		}
	}
	
}
