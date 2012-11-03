<?php

class AK_BaseController {
	
	/**
	 * GET�p�����[�^�z��
	 * @var array
	 */
	private $getParam = array();
	
	/**
	 * �|�X�g�p�����[�^�z��
	 */
	private $postParam = array();
	
	//---------------------------------- public ----------------------------------
	
	/**
	 * �O����
	 */
	public function beforeRun() {
		
		$this -> getParam  = $_GET;
		$this -> postParam = $_POST;
		unset( $_GET );
		unset( $_POST );
		
	}
	
	/**
	 * �㏈��
	 */
	public function afterRun() {
		;
	}
	
	//--------------------------------- protected ----------------------------------
	
	/**
	 * �Q�b�g�p�����[�^�擾
	 * @param string $key
	 * @return string
	 */
	protected function getGetParam( $key ) {
		return ( array_key_exists( $key, $this -> getParam ) === TRUE) ? $this -> getParam[$key] : NULL;
	}
	
	/**
	 * �|�X�g�p�����[�^�擾
	 * @param string $key
	 * @return string
	 */
	protected function getPostParam( $key ) {
		return ( array_key_exists( $key, $this -> postParam ) === TRUE) ? $this -> postParam[$key] : NULL; 
	}
	
	/**
	 * �p�����[�^�擾
	 * ����̃L�[���������ꍇ��GET�D��
	 * @param string $key
	 * @return string
	 */
	protected function getParam( $key ) {
		$value = $this -> getGetParam( $key );
		$value = $value ?: $this -> getPostParam( $key );
		return $value;
	}
	
	
}