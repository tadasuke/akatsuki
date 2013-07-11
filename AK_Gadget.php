<?php

class AK_Gadget {
	
	/**
	 * 日付形式変更
	 * @param string $date
	 * @param string $format
	 */
	public static function dateFormat( $date, $format = 'YmdHis' ) {
		
		return date( $format, strtotime( $date ) );
		
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
		
		return $randArray[array_rand( $randArray ) ];
		
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
}