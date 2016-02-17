<?php

class AK_Excepiton extends Exception{
	
	public function __construct( $errno, $errstr, $errfile, $errline ) {
	
		$this -> message = $errstr;
		$this -> file    = $errfile;
		$this -> line    = $errline;
	}
	
}

class AK_NoControllerException extends AK_Excepiton {}