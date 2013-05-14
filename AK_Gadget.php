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
	
}