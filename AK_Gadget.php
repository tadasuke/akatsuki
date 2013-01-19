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
	
	
}