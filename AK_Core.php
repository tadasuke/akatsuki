<?php

class AK_core {

	private static $instance = NULL;
	
	/**
	 * �R���g���[���[��
	 * @var string
	 */
	private $controllerName = NULL;
	public function getControllerName() {
		return $this -> controllerName;
	}
	
	/**
	 * �A�N�V������
	 * @var string
	 */
	private $actionName = NULL;
	public function getActionName() {
		return $this -> actionName;
	}
	
	/**
	 * �R���g���[���[�f�B���N�g��
	 * @var string
	 */
	private $controllerDir = NULL;
	public function setControllerDir( $controllerDir ) {
		$this -> controllerDir = $controllerDir;
	}
	
	/**
	 * �C���X�^���X�擾
	 * @return AK_core
	 */
	public static function getInstance() {
		return self::$instance = self::$instance ?: new self();
	}
	
	//------------------------------------------------------------------------
	
	/**
	 * �R���X�g���N�^
	 */
	private function __construct() {
		
		$this -> _parse();
		
	}
	
	//------------------------------ public --------------------------------
	
	
	/**
	 * ���s
	 */
	public function run(){
		
		// �R���g���[����PHP�t�@�C�����C���N���[�h
		require_once $this -> controllerDir . '/' . $this -> controllerName . '.php';
		
		// �R���g���[���I�u�W�F�N�g���쐬
		$obj = new $this -> controllerName;
		
		// �O�������s
		if ( call_user_func( array( $obj, 'beforeRun' ) ) === FALSE ) {
			echo( 'exec beforeRun error!!' );
			exit;
		} else {
			;
		}
		
		// �A�N�V�������s
		if ( call_user_func( array( $obj, $this -> actionName ) ) === FALSE ) {
			echo( 'exec action error!!' );
			exit;
		}
		
		// �㏈��
		if ( call_user_func( array( $obj, 'afterRun' ) ) === FALSE ) {
			echo( 'exec afterRun error!!' );
			exit;
		} else {
			;
		}
	
	}
	
	
	//--------------------------------- private -------------------------------
	
	/**
	 * URL����͂��ČĂяo���N���X�A���\�b�h��ݒ�
	 */
	private function _parse(){
		
		$array = explode( '?', $_SERVER['REQUEST_URI'] );
		$array = explode( '/', $array[0] );
		
		$this -> controllerName = ucfirst( $array[1] ) . 'Controller';
		$this -> actionName     = ucfirst( $array[2] ) . 'Action';
		
	}
	
	
		
		
}