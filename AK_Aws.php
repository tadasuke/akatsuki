<?php

require_once 'ak_aws/AK_Ses.class.php';
require_once 'ak_aws/AK_Sqs.class.php';

abstract class AK_Aws {
	
	/**
	 * AWSパラメータ配列
	 * @var array
	 */
	protected $awsParamArray = NULL;
	
	/**
	 * AWS設定配列
	 * @var array $awsConfigArray
	 */
	protected $awsConfigArray = NULL;
	
	/**
	 * コンストラクタ
	 */
	protected function __construct( $awsConfigArray ) {
	
		$this -> awsConfigArray = $awsConfigArray;
		
		// AWSパラメータを設定
		$this -> awsParamArray = [
			'version' => 'latest',
			'region'  => $awsConfigArray['default_region'],
			'credentials' => [
				'key'    => $awsConfigArray['access_key'],
				'secret' => $awsConfigArray['secret_access_key'],
			],
		];
		
	}
	
}