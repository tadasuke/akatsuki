<?php

/**
 * メールクラス
 * @author tadasuke
 *
 */
class AK_Mail extends AK_Aws {
	
	/**
	 * SESクライアント
	 * @var SesClient $sesClient
	 */
	private $sesClient = NULL;
	
	/**
	 * 送信元メールアドレス
	 * @var string
	 */
	private $fromMailAddress = NULL;
	public function setFromMailAddress( $fromMailAddress ) {
		$this -> fromMailAddress = $fromMailAddress;
	}
	public function getFromMailAddress() {
		return $this -> fromMailAddress;
	}
	
	/**
	 * 許可メールアドレス配列
	 * @var array
	 */
	private $permitMailAddressArray = [];
	public function setPermitMailAddressArray( array $permitMailAddressArray ) {
		$this -> permitMailAddressArray = $permitMailAddressArray;
	}
	public function getPermitMailAddressArray() {
		return $this -> permitMailAddressArray;
	}
	
	/**
	 * 送信アドレス別名
	 * @var string
	 */
	private $fromAddressAlias = NULL;
	public function setFromAddressAlias( $fromAddressAlias ) {
		$this -> fromAddressAlias = $fromAddressAlias;
	}
	public function getFromAddressAlias() {
		return $this -> fromAddressAlias;
	}
	
	
	/**
	 * コンストラクタ
	 * @param array $awsConfigArray
	 */
	public function __construct( $awsConfigArray ) {
		
		parent::__construct( $awsConfigArray );
		$this -> sesClient = new \Aws\Ses\SesClient( $this -> awsParamArray );
		
	}
	
	/**
	 * メール送信
	 * @param mixed $toMailAddress
	 * @param string $subject
	 * @param string $body
	 */
	public function sendMail( $toMailAddress, $subject, $body ) {
	
		AK_Log::getLogClass() -> log( AK_Log::INFO, __METHOD__, __LINE__, 'START' );
		AK_Log::getLogClass() -> log( AK_Log::INFO, __METHOD__, __LINE__, '$subject:' . $subject );
		AK_Log::getLogClass() -> log( AK_Log::INFO, __METHOD__, __LINE__, '$body:'    . $body );
		
		if ( is_array( $toMailAddress ) === FALSE ) {
			$toAddressArray = [$toMailAddress];
		} else {
			$toAddressArray = $toMailAddress;
		}
	
	
// 		// 送信アドレス配列を作成
// 		$toAddressArray = [];
// 		if ( strcmp( $userBean -> getNoticeMailAddressType(), NOTICE_MAIL_ADDRESS_TYPE_SUB ) == 0 ) {
// 			$toAddressArray[] = $userBean -> getSubMailAddress();
// 		} else if ( strcmp( $userBean -> getNoticeMailAddressType(), NOTICE_MAIL_ADDRESS_TYPE_SUB_GMAIL ) == 0 ) {
// 			$toAddressArray[] = $userBean -> getSubMailAddress();
// 			$toAddressArray[] = $userBean -> getGmailAddress();
// 		} else {
// 			;
// 		}

	
		foreach ( $toAddressArray as $toAddress ) {
				
			AK_Log::getLogClass() -> log( AK_Log::DEBUG, __METHOD__, __LINE__, '$toAddress:' . $toAddress );
	
			// 送信メールアドレス制限対応
			if ( count( $this -> permitMailAddressArray ) > 0 ) {
				if ( in_array( $toAddress, $this -> permitMailAddressArray ) === FALSE ) {
					AK_Log::getLogClass() -> log( AK_Log::INFO, __METHOD__, __LINE__, 'no_permit_mail_address' );
					continue;
				} else {
					;
				}
			} else {
				;
			}
				
			//--------------
			// パラメータ設定
			//-------------
			if ( is_null( $this -> fromAddressAlias ) === TRUE ) {
				$souce = $this -> fromMailAddress;
			} else {
				$souce = $this -> fromAddressAlias . '<' . $this -> fromMailAddress . '>';
			}
			$paramArray = [
				'Source' => $souce,
				'Destination' => [
					'ToAddresses' => [ $toAddress ]
				],
				'Message' => [
					'Subject' => [
						'Data' => $subject,
					],
					'Body' => [
						'Text' => [
							'Data' => $body,
						],
					],
				],
			];
				
			// メール送信
			$result = $this -> sesClient -> sendEmail( $paramArray );
				
		}
	
		AK_Log::getLogClass() -> log( AK_Log::INFO, __METHOD__, __LINE__, 'END' );
	
	}
	
	
	
	
	
	
}