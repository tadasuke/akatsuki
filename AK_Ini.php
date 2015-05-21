<?php

class AK_Ini {
	
	/**
	 * 設定をマージする
	 * @param array $fromArray
	 * @param array $toArray
	 */
	protected function mergeConfig( array &$fromArray, array &$toArray ) {
			
		foreach ( $fromArray as $key => $value ) {
			if ( is_array( $fromArray[$key] ) === TRUE ) {
				if ( array_key_exists( $key, $toArray ) === FALSE ) {
					$toArray[$key] = array();
				} else {
					;
				}
				$this -> mergeConfig( $fromArray[$key], $toArray[$key] );
			} else {
				$toArray[$key] = $fromArray[$key];
			}
		}
	
	}
	
}