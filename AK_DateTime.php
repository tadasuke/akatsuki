<?php

class AK_DateTime extends DateTime {
	
	/**
	 * 基本フォーマット
	 * @var string
	 */
	private $baseFormat = 'U';
	public function setBaseFormat( $baseFormat ) {
		$this -> baseFormat = $baseFormat;
	}
	public function getBaseFormat() {
		return $this -> baseFormat;
	}
	
	
	/**
	 * コンストラクタ
	 */
	public function __construct( $time = NULL ) {
		
		parent::__construct( $time );
		
		// パラメータがUNIXタイムだった時はタイムゾーンを再設定
		if ( strpos( $time, '@' ) === 0 ) {
			$this -> setTimezone( new DateTimeZone( 'Asia/Tokyo' ) );
		} else {
			;
		}
		
	}
	
	/**
	 * 時刻変更
	 * @param int $seconds
	 * @param boolean $progressFlg
	 */
	public function moveTimeBySeconds( $seconds, $progressFlg = TRUE ) {
		if ( $progressFlg === TRUE ) {
			$this -> modify( '+' . $seconds . ' seconds' );
		} else {
			$this -> modify( '-' . $seconds . ' seconds' );
		}
	}
	
	
	/**
	 * 日時取得
	 */
	public function getDateTime( $format = NULL ) {
		if ( is_null( $format ) === TRUE ) {
			$format = $this -> baseFormat;
		}
		return $this -> format( $format );
	}
	
	
	/**
	 * 日時データを配列にして返す
	 */
	public function getArray( ...$formatArray ) {
		
		$responseArray = array();
		foreach ( $formatArray as $format ) {
			$responseArray[$format] = $this -> format( $format );
		}
		return $responseArray;
		
	}
	
	
	/**
	 * 差分を秒数で返す
	 * @param AK_DateTime $dateTime
	 */
	public function getDiffSeconds( AK_DateTime $dateTime ) {
		
		return $dateTime -> format( 'U' ) - $this -> format( 'U' ); 
		
	}
	
}