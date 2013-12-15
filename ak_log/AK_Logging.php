<?php

class AK_Logging extends AK_Log {
	
	/**
	 * ログファイル名
	 * @var string
	 */
	private $logFileName = NULL;
	
	/**
	 * エラーログファイル名
	 * @var string
	 */
	private $errorLogFileName = NULL;
	
	/**
	 * 出力ログレベル
	 * @var int
	 */
	private $outLogLevel = NULL;
	
	/**
	 * ログヘッダ
	 * @var string
	 */
	private $logHeader = NULL;
	public function setLogHeader( $logHeader ) {
		$this -> logHeader = $logHeader;
	}
	public function getLogHeader() {
		return $this -> logHeader;
	}
	
	/**
	 * エラーログ出力フラグ
	 * @var boolean
	 */
	private $outputErrorLogFlg = TRUE;
	public function setOutputErrorLogFlg( $outputErrorLogFlg ) {
		$this -> outputErrorLogFlg = $outputErrorLogFlg;
	}
	
	/**
	 * エラーログレベル
	 * @var int
	 */
	private $errorLogLevel = self::NOTICE;
	public function setErrorLogLevel( $errorLogLevel ) {
		$this -> errorLogLevel = $errorLogLevel;
	}
	
	
	/**
	 * プロセスID
	 * @var string
	 */
	private $processId = NULL;
	public function getProcessId() {
		return $this -> processId;
	}
	
	
	/**
	 * ログ出力時刻
	 * @var string
	 */
	private $logOutputDate = NULL;
	
	
	/**
	 * ログ出力フラグ
	 * @var boolean
	 */
	private $logOutputFlg = TRUE;
	public function setLogOutputFlg( $logOutputFlg ) {
		$this -> logOutputFlg = $logOutputFlg;
	}
	
	//------------------------------- construct -------------------------
	
	protected function __construct( $logFileName, $outLogLevel ) {
		$this -> logFileName = $logFileName;
		$this -> errorLogFileName = $logFileName . '.error';
		$this -> outLogLevel = $outLogLevel;
		$this -> logOutputDate = date( 'H:i:s' );
		// プロセスID設定
		$this -> processId = substr( sha1( microtime( TRUE ) . rand() ), 0, 8 );
	}
	
	//---------------------------- public --------------------------------
	
	/**
	 * ログ出力
	 * @param mixed $string
	 */
	public function log( $logLevel, $method, $line, $message ) {
		
		if ( $this -> logOutputFlg === FALSE ) {
			return;
		} else {
			;
		}
		
		if ( $this -> outLogLevel < $logLevel ) {
			return;
		} else {
			;
		}
		
		$logString = $this -> makeLogMessage( $logLevel, $method, $line, $message );
		
		$FP = fopen( $this -> logFileName, 'a' );
		fwrite( $FP, $logString . PHP_EOL );
		fclose( $FP );
		
		// エラーログ対応
		if ( $this -> outputErrorLogFlg === TRUE && $logLevel <= $this -> errorLogLevel ) {
			$FP = fopen( $this -> errorLogFileName, 'a' );
			fwrite( $FP, $logString . PHP_EOL );
			fclose( $FP );
		} else {
			;
		}
		
	}
	
	//-------------------------- private --------------------------------
	
	/**
	 * ログメッセージ作成
	 * @param string $method
	 * @param int $line
	 * @param string $message
	 */
	private function makeLogMessage( $logLevel, $method, $line, $message ) {
		
		/*
		$date = date( 'H:i:s' );
		$logString = $this -> processId . "\t" . $date . "\t" . $this -> logHeader . "\t" . '(' . $logLevel . ')' . "\t" . $method . "\t" . $line . "\t" . $message;
		*/
		$logString = $this -> processId . "\t" . $this -> logHeader . "\t" . '(' . $logLevel . ')' . "\t" . $method . "\t" . $line . "\t" . $message;
		$logString = $this -> processId . "\t" . $this -> logOutputDate . "\t" . $this -> logHeader . "\t" . '(' . $logLevel . ')' . "\t" . $method . "\t" . $line . "\t" . $message;
		
		return $logString;
	}
	
	
}