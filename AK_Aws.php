<?php

require_once 'ak_aws/AK_Mail.class.php';

abstract class AK_Aws {
	
	/**
	 * AWSパラメータ配列
	 * @var array
	 */
	protected $awsParamArray = NULL;
	
	/**
	 * コンストラクタ
	 */
	public function __construct( $awsConfigArray ) {
	
		// AWSパラメータを設定
		$this -> awsParamArray = [
			'version'     => 'latest',
			'region'      => 'us-east-1',
			'credentials' => [
				'key'    => $awsConfigArray['access_key'],
				'secret' => $awsConfigArray['secret_access_key'],
			],
		];
		
	}
	
}