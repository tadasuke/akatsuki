<?php

/**
 * SQSオブジェクト
 * @author tadasuke
 *
 */
class AK_Sqs extends AK_Aws {
	
	/**
	 * インスタンス取得
	 * @var AK_Ses
	 */
	private static $instance = NULL;
	public static function getInstance( $awsConfigArray ) {
		if ( is_null( self::$instance ) === TRUE ) {
			self::$instance = new self( $awsConfigArray );
		} else {
			;
		}
		return self::$instance;
	}
	
	/**
	 * Sqsオブジェクト
	 * @var SqsClient
	 */
	private $sqsClient = NULL;
	
	/**
	 * キューURL
	 * @var string
	 */
	private $queUrl = NULL;
	public function getQueUrl() {
		return $this -> queUrl;
	}
	
	/**
	 * コンストラクタ
	 * @param array $awsConfigArray
	 */
	protected function __construct( $awsConfigArray ) {
	
		parent::__construct( $awsConfigArray );
		$this -> sqsClient = new \Aws\Sqs\SqsClient( $this -> awsParamArray );
		$this -> queUrl = $this -> awsConfigArray['sqs_que_url'];
	
	}
	
	
	public function sendQue() {
		
		$queArray = [
			'QueueUrl'    => $this -> queUrl,
		    'MessageBody' => 'TADSUKE!!!!',
		];
		
		$this -> sqsClient -> sendMessage( $queArray );
		
	}
	
}