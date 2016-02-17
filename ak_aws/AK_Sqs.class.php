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
	 * 送信キュー配列
	 * @var array
	 */
	private $sendQueArray = [];
	
	/**
	 * コンストラクタ
	 * @param array $awsConfigArray
	 */
	protected function __construct( $awsConfigArray ) {
	
		parent::__construct( $awsConfigArray );
		$this -> sqsClient = new \Aws\Sqs\SqsClient( $this -> awsParamArray );
		$this -> queUrl = $this -> awsConfigArray['sqs_que_url'];
	
	}
	
	/**
	 * デストラクタ
	 */
	public function __destruct() {
		
		// 10通ずつまとめて送る
		$sendQueArray = array_chunk( $this -> sendQueArray, 10 );
		
		foreach ( $sendQueArray as $queArray ) {
			
			$paramArray = [
				'QueueUrl' => $this -> queUrl,
				'Entries'  => [],
			];
			
			foreach ( $queArray as $key => $que ) {
				$paramArray['Entries'][] = [
					'Id' => $key + 1,
					'MessageBody' => $que,
				];
			};
			
			$this -> sqsClient -> sendMessageBatch( $paramArray );
			
		}
		
	}
	
	
	/**
	 * キュー送信
	 * @param string $message
	 */
	public function sendQue( $message ) {
		$this -> sendQueArray[] = $message;
	}
	
	/**
	 * キュー取得
	 */
	public function receiveQue( $getQueCount = 1 ) {
		
		$queArray = [
			'QueueUrl' => $this -> queUrl,
			'MaxNumberOfMessages' => $getQueCount,
		];
		$result = $this -> sqsClient -> receiveMessage( $queArray );
		return $result;
		
	}
	
	/**
	 * キュー削除
	 * @param string $receiptHandle
	 */
	public function deleteQue( $receiptHandle ) {
		
		$queArray = [
			'QueueUrl' => $this -> queUrl,
			'ReceiptHandle' => $receiptHandle,
		];
		$this -> sqsClient -> deleteMessage( $queArray );
		
	}
	
}