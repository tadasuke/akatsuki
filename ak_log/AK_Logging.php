<?php

class AK_Logging extends AK_Log
{

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

	public function setLogHeader( $logHeader )
	{
		$this->logHeader = $logHeader;
	}

	public function getLogHeader()
	{
		return $this->logHeader;
	}

	/**
	 * エラーログ出力フラグ
	 * @var boolean
	 */
	private $outputErrorLogFlg = TRUE;

	public function setOutputErrorLogFlg( $outputErrorLogFlg )
	{
		$this->outputErrorLogFlg = $outputErrorLogFlg;
	}

	/**
	 * エラーログレベル
	 * @var int
	 */
	private $errorLogLevel = self::NOTICE;

	public function setErrorLogLevel( $errorLogLevel )
	{
		$this->errorLogLevel = $errorLogLevel;
	}


	/**
	 * プロセスID
	 * @var string
	 */
	private $processId = NULL;

	public function getProcessId()
	{
		return $this->processId;
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

	public function setLogOutputFlg( $logOutputFlg )
	{
		$this->logOutputFlg = $logOutputFlg;
	}

	/**
	 * ログ一括出力フラグ
	 * @var boolean
	 */
	private $batchOutputFlg = FALSE;

	public function setBatchOutputFlg( $batchOutputFlg )
	{
		$this->batchOutputFlg = $batchOutputFlg;
	}

	public function getBatchOutputFlg()
	{
		return $this->batchOutputFlg;
	}

	/**
	 * ログ出力時刻詳細フラグ
	 * @var boolean
	 */
	private $logOutputDateDetailFlg = FALSE;

	public function setLogOutputDateDetailFlg( $logOutputDateDetailFlg )
	{
		$this->logOutputDateDetailFlg = $logOutputDateDetailFlg;
	}

	/**
	 * 一括出力ログ
	 * @var string
	 */
	private $batchOutputLog = '';


	/**
	 * コンストラクタ
	 * @param string $logFileName
	 * @param int $outLogLevel
	 * @param string $errorLogFileName
	 */
	protected function __construct( $logFileName, $outLogLevel, $errorLogFileName = NULL )
	{
		$this->logFileName = $logFileName;
		if ( is_null( $errorLogFileName ) === TRUE ){
			$this->errorLogFileName = $logFileName . '.error';
		} else {
			$this->errorLogFileName = $errorLogFileName;
		}
		$this->outLogLevel   = $outLogLevel;
		$this->logOutputDate = date( 'H:i:s' );
		// プロセスID設定
		$processId = substr( sha1( microtime( TRUE ) . rand() ), 0, 8 );
		$this->processId = $processId;

		// Apacheログ出力設定
		if ( function_exists( 'apache_note' ) === TRUE ){
			apache_note( 'ak_process_id', $processId );
		}

	}


	/**
	 * 溜め込んだログを出力する
	 */
	public function outputBatchLog()
	{

		// 一括出力ログがなければ何もしない
		if ( strlen( $this->batchOutputLog ) == 0 ){
			return;
		}

		// 一括ログ出力
		file_put_contents( $this->logFileName, $this->batchOutputLog, FILE_APPEND );

		// 一括ログを初期化
		$this->batchOutputLog = '';

	}

	/**
	 * デストラクタ
	 */
	public function __destruct()
	{

		// 一括出力ログがなければ何もしない
		if ( strlen( $this->batchOutputLog ) == 0 ){
			return;
		}

		// ログ一括出力フラグが立っていればログ出力
		if ( $this->batchOutputFlg === TRUE ){
			file_put_contents( $this->logFileName, $this->batchOutputLog, FILE_APPEND );
		}

		// ファイルパーミッション対応
		if ( is_null( parent::$logFilePermission ) === FALSE ){
			chmod( $this->logFileName, intval( parent::$logFilePermission, 8 ) );
		}

	}

	//---------------------------- public --------------------------------

	/**
	 * ログ出力
	 * @param mixed $string
	 * @return string
	 */
	public function log( $logLevel, $method, $line, $message )
	{

		if ( $this->logOutputFlg === FALSE ){
			return '';
		}

		if ( $this->outLogLevel < $logLevel ){
			return '';
		}

		//-------------
		// ログ文字列作成
		//-------------
		$logString = $this->makeLogMessage( $logLevel, $method, $line, $message ) . PHP_EOL;

		//-------------------
		// syslogに出力する場合
		//-------------------
		if ( self::$useSyslogFlg === TRUE ){
			syslog( $logLevel, $logString );
			//--------------------
			// ファイルに出力する場合
			//--------------------
		} else {

			//---------------
			// 一括出力する場合
			//---------------
			if ( $this->batchOutputFlg === TRUE ){
				// プロパティに追記
				$this->batchOutputLog .= $logString;

				//-----------------
				// 一括出力しない場合
				//-----------------
			} else {
				// ログファイルに出力
				file_put_contents( $this->logFileName, $logString, FILE_APPEND );
			}

			//---------------------------
			// エラーログのファイルにも出力する
			//---------------------------
			if ( $this->outputErrorLogFlg === TRUE && $logLevel <= $this->errorLogLevel ){
				$FP = fopen( $this->errorLogFileName, 'a' );
				fwrite( $FP, $logString . PHP_EOL );
				fclose( $FP );
			}
		}
		return $logString;
	}

	//-------------------------- private --------------------------------

	/**
	 * ログメッセージ作成
	 * @param int $logLevel
	 * @param string $method
	 * @param int $line
	 * @param string $message
	 * @return string
	 */
	private function makeLogMessage( $logLevel, $method, $line, $message )
	{

		// 詳細な日時を出力しない場合
		if ( $this->logOutputDateDetailFlg === FALSE ){
			$logString = $this->processId . "\t" . $this->logOutputDate . "\t" . $this->logHeader . "\t" . '(' . $logLevel . ')' . "\t" . $method . "\t" . $line . "\t" . $message;
			// 詳細な日時を出力する場合
		} else {
			// 詳細な秒数出力
			list( $msec ) = explode( ' ', microtime() );
			$msec      *= 1000000;
			$logString = $this->processId . "\t" . date( 'H:i:s' ) . '.' . $msec . "\t" . $this->logHeader . "\t" . '(' . $logLevel . ')' . "\t" . $method . "\t" . $line . "\t" . $message;
		}
		return $logString;
	}


}