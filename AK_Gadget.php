<?php

class AK_Gadget {
	
	/**
	 * 日付形式変更
	 * @param string $date
	 * @param string $format
	 */
	public static function dateFormat( $date, $format = 'YmdHis' ) {
		
		$time = strtotime( $date );
		return ($time === FALSE) ? NULL : date( $format, $time );
		
	}
	
	/**
	 * 残り時間取得処理
	 * YmdHis形式の時刻の差を秒数で求める
	 * @param int $nowDate
	 * @param int $oldDate
	 * @return int $seconds;
	 */
	public static function getLastSeconds( $newDate, $oldDate = NULL ) {
		$newTime = strtotime( $newDate );
		$oldTime = (is_null( $oldDate )) ? time() : strtotime( $oldDate );
		return $newTime - $oldTime;
	}
	
	/**
	 * 連想配列の特定のキーを元にソートする
	 * @param array $array
	 * @param string $sortKey
	 * @param int $sortType
	 */
	public static function sortArrayByKey( &$array, $sortKey, $sortType = SORT_ASC ) {
	
		$tmpArray = array();
		foreach ( $array as $key => $row ) {
			$tmpArray[$key] = $row[$sortKey];
		}
		array_multisort( $tmpArray, $sortType, $array );
		unset( $tmpArray );
	}
	
	/**
	 * 配列ランダム抽出
	 * @param array $array
	 * @return string $key
	 */
	public static function selectedArray( array $array ) {
		
		$randArray = array();
		foreach ( $array as $key => $value ) {
			for ( $i = 0; $i < $value; $i++ ) {
				$randArray[] = $key;
			}
		}
		
		$result = (count( $randArray ) == 0) ? NULL : $randArray[array_rand( $randArray ) ];
		return $result;
		
	}
	
	/**
	 * n-nの形式の文字列から、最大値、最小値の配列を作成する
	 * @param string $numWord
	 * @return int
	 */
	public static function getRandamNum( $numWord ) {
		list( $min, $max) = explode( '-', $numWord );
		return rand( $min, $max );
	}
	
	/**
	 * 地域コードを元に都道府県名を返す
	 * @param string $areaCode
	 * @return string
	 */
	public static function getPrefectureNameByAreaCode( $areaCode ) {
	
		$areaArray = array(
			  '01' => '北海道'
			, '02' => '青森県'
			, '03' => '岩手県'
			, '04' => '宮城県'
			, '05' => '秋田県'
			, '06' => '山形県'
			, '07' => '福島県'
			, '08' => '茨城県'
			, '09' => '栃木県'
			, '10' => '群馬県'
			, '11' => '埼玉県'
			, '12' => '千葉県'
			, '13' => '東京都'
			, '14' => '神奈川県'
			, '15' => '新潟県'
			, '16' => '富山県'
			, '17' => '石川県'
			, '18' => '福井県'
			, '19' => '山梨県'
			, '20' => '長野県'
			, '21' => '岐阜県'
			, '22' => '静岡県'
			, '23' => '愛知県'
			, '24' => '三重県'
			, '25' => '滋賀県'
			, '26' => '京都府'
			, '27' => '大阪府'
			, '28' => '兵庫県'
			, '29' => '奈良県'
			, '30' => '和歌山県'
			, '31' => '鳥取県'
			, '32' => '島根県'
			, '33' => '岡山県'
			, '34' => '広島県'
			, '35' => '山口県'
			, '36' => '徳島県'
			, '37' => '香川県'
			, '38' => '愛媛県'
			, '39' => '高知県'
			, '40' => '福岡県'
			, '41' => '佐賀県'
			, '42' => '長崎県'
			, '43' => '熊本県'
			, '44' => '大分県'
			, '45' => '宮崎県'
			, '46' => '鹿児島県'
			, '47' => '沖縄県'
		);
	
		return $areaArray[$areaCode];
	
	}
	
	
	/**
	 * NULLを空文字にする
	 * @param mixed $obj
	 */
	public static function nullToString( &$obj ) {
		
		if ( is_array( $obj ) === FALSE ) {
			$obj = (is_null( $obj )) ? '' : $obj;
		} else {
			foreach ( $obj as &$value ) {
				self::nullToString( $value );
			}
		}
		
	}
	
	
	/**
	 * 2つの配列を比較し、同じ値が存在すればTRUE、存在しなければFALSEを返す
	 * @param array $arrayA
	 * @param array $arrayB
	 */
	public static function isMatchArray( array $arrayA, array $arrayB ) {
				
		$result = (count( array_intersect( $arrayA, $arrayB ) ) > 0) ? TRUE : FALSE;
		
		return $result;
		
	}
	
	
	/**
	 * ファイルオーナー名取得
	 * @param string $fileName
	 */
	public static function getFileOwnerName( $fileName ) {
		
		// ファイルのユーザIDを取得
		$uid = fileowner( $fileName );
		if ( $uid === FALSE ) {
			return NULL;
		} else {
			;
		}
		// ユーザ情報を取得
		$userInfoArray = posix_getpwuid( $uid );
		return $userInfoArray['name'];
		
	}
	
	
	/**
	 * 最後の文字を返す(マルチバイト文字不可)
	 * @param string $string
	 * @return char
	 */
	public static function getLastChara( $string ) {
		return substr( $string, strlen( $string ) - 1 );
	}
	
	
	/**
	 * 最後の文字を削除する
	 * @param string $string
	 * @return string
	 */
	public static function deleteLastWord( $string, $deleteWordCount = 1 ) {
		return substr( $string, 0, strlen( $string ) - $deleteWordCount );
	}
	
	
	/**
	 * キャメルケースの文字列をスネークケースの文字列に変換する
	 * @param unknown $camelString
	 * @return string
	 */
	public static function camel2snake( $camelString ) {
		return strToLower( preg_replace( '/([a-z])([A-Z])/', "$1_$2", $camelString ) );
	}
	
	
	/**
	 * 配列の中のintをstringにする
	 * @param array $array
	 */
	public static function int2stringByArray( array $array ) {
		
		$responseArray = array();
		foreach ( $array as $key => $value ) {
			
			// 値が配列の場合
			if ( is_array( $value ) === TRUE ) {
				$responseArray[$key] = self::int2stringByArray( $value );
			// 値がintの場合
			} else if ( is_int( $value ) === TRUE ) {
				$responseArray[$key] = (string)$value;
			} else {
				$responseArray[$key] = $value;
			}
			
		}
		
		return $responseArray;
		
	}
	
	
	/**
	 * ハッシュキー作成
	 * @param int $num
	 */
	public static function akatsukiHash( $num, $glue = '-', $bodyLength = 6 ) {
		
		$tmpNum =  rand( 1, 9 ) . $num . rand( 0, 9 ) . rand( 0, 9 );;
		$tmpNum *= 3;
		
		$header = '';
		do {
			$header .= self::intTo26Char( $tmpNum );
		} while( strlen( $tmpNum ) > $bodyLength );
		
		return $header . $glue . $tmpNum;
		
	}
	
	/**
	 * 数値から26進数の値を取り出す
	 * @param int $num
	 */
	private static function intTo26Char( &$num ) {
	
		$hashtable = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$hashLength = strlen( $hashtable );
	
		$mod = $num % $hashLength;
		$word = $hashtable[$mod];
		$num = ($num - $mod) / $hashLength;
	
		return $word;
	
	}
	
	
	/**
	 * 1ならTRUE、0ならFALSE、それ以外ならNULLを返す
	 * @param string $string
	 * @param boolean
	 */
	public static function string2Boolean( $string ) {
		
		if ( is_bool( $string ) === TRUE ) {
			return $string;
		} else {
			;
		}
		
		if ( strcmp( $string, '1' ) == 0 ) {
			return TRUE;
		} else if ( strcmp( $string, '0' ) == 0 ) {
			return FALSE;
		} else {
			return NULL;
		}
		
	}
	
}