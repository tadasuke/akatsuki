<?php

class AK_Logging extends AK_Log {
	
	/**
	 * ログ出力ベースディレクトリ
	 * @var string
	 */
	private $logFileName = NULL;
	
	/**
	 * ログ出力レベル
	 * @var int
	 */
	private $outLogLevel = NULL;
	
	/**
	 * プロセスID
	 * @var string
	 */
	private $processId = NULL;
	
	//------------------------------- construct -------------------------
	
	protected function __construct( $logFileName, $outLogLevel ) {
		$this -> logFileName = $logFileName;
		$this -> outLogLevel = $outLogLevel;
		// プロセスID作成
		$this -> processId = substr( sha1( microtime( TRUE ) . rand() ), 0, 8 );
	}
	
	//---------------------------- public --------------------------------
	
	/**
	 * ログ出力
	 * @param mixed $string
	 */
	public function log( $logLevel, $method, $line, $message ) {
		
		if ( $this -> outLogLevel < $logLevel ) {
			return;
		} else {
			;
		}
		
		$logString = $this -> makeLogMessage( $logLevel, $method, $line, $message );
		
		$FP = fopen( $this -> logFileName, 'a' );
		fwrite( $FP, $logString . PHP_EOL );
		fclose( $FP );
		
	}
	
	//-------------------------- private --------------------------------
	
	/**
	 * ログメッセージ作成
	 * @param string $method
	 * @param int $line
	 * @param string $message
	 */
	private function makeLogMessage( $logLevel, $method, $line, $message ) {
		
		$date = date( 'H:i:s' );
		$logString = $this -> processId . "\t" . $date . "\t" . '(' . $logLevel . ')' . "\t" . $method . "\t" . $line . "\t" . $message;
		return $logString;
	}
	
	
}