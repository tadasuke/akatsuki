<?php

class AK_Logging extends AK_Log {
	
	/**
	 * ���O�o�̓x�[�X�f�B���N�g��
	 * @var string
	 */
	private $logFileName = NULL;
	
	/**
	 * ���O�o�̓��x��
	 * @var int
	 */
	private $outLogLevel = NULL;
	
	/**
	 * �v���Z�XID
	 * @var string
	 */
	private $processId = NULL;
	
	//------------------------------- construct -------------------------
	
	protected function __construct( $logFileName, $outLogLevel ) {
		$this -> logFileName = $logFileName;
		$this -> outLogLevel = $outLogLevel;
		// �v���Z�XID�쐬
		$this -> processId = substr( sha1( microtime( TRUE ) . rand() ), 0, 8 );
	}
	
	//---------------------------- public --------------------------------
	
	/**
	 * ���O�o��
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
	 * ���O���b�Z�[�W�쐬
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