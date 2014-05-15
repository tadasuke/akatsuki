<?php

require_once 'ak_core/AK_BaseController.php';

class AK_Core {

	/**
	 * モジュール機能使用フラグ
	 * @var boolean
	 */
	private static $useModuleFlg = FALSE;
	public static function setUseModuleFlg( $useModuleFlg ) {
		self::$useModuleFlg = $useModuleFlg;
	}
	
	/**
	 * デフォルトモジュール名
	 * @var string
	 */
	private static $defaultModuleName = 'index';
	public static function setDefaultModuleName( $moduleName ) {
		self::$defaultModuleName = $moduleName;
	}
	
	/**
	 * アクション実行フラグ
	 * @var boolean
	 */
	private static $execActionFlg = TRUE;
	public static function setExecActionFlg( $execActionFlg ) {
		self::$execActionFlg = $execActionFlg;
	}
	
	/**
	 * 後処理実行フラグ
	 * @var boolean
	 */
	private static $execAfterRunFlg = TRUE;
	public static function setExecAfterRunFlg( $execAfterRunFlg ) {
		self::$execAfterRunFlg = $execAfterRunFlg;
	}
	
	/**
	 * 終了処理実行フラグ
	 * @var boolean
	 */
	private static $execTerminalFlg = TRUE;
	public static function setExecTerminalFlg( $execTerminalFlg ) {
		self::$execTerminalFlg = $execTerminalFlg;
	}
	
	/**
	 * インスタンス
	 * @var AK_Core
	 */
	private static $instance = NULL;
	
	/**
	 * モジュール名
	 * @var string
	 */
	private $moduleName = '';
	public function getModuleName() {
		return $this -> moduleName;
	}
	
	/**
	 * コントローラ名
	 * @var string
	 */
	private $controllerName = '';
	public function getControllerName() {
		return $this -> controllerName;
	}
	
	/**
	 * アクション名
	 * @var string
	 */
	private $actionName = '';
	public function getActionName() {
		return $this -> actionName;
	}
	
	/**
	 * ユーザパラメータ配列
	 * @var array
	 */
	private $userParamArray = array();
		
	/**
	 * コントローラディレクトリ
	 * @var string
	 */
	private $controllerDir = NULL;
	public function setControllerDir( $controllerDir ) {
		$this -> controllerDir = $controllerDir;
	}
	
	/**
	 * リクエストオブジェクト
	 * @var AK_BaseController
	 */
	private $requestObj = NULL;
	public function getRequestObj() {
		return $this -> requestObj;
	}
	
	/**
	 * レスポンスフラグ
	 * @var boolean
	 */
	private $responseFlg = TRUE;
	public function setResponseFlg( $responseFlg ) {
		$this -> responseFlg = $responseFlg;
	}
	
	/**
	 * インスタンス取得
	 * @return AK_core
	 */
	public static function getInstance() {
		return self::$instance = self::$instance ?: new self();
	}
	
	//------------------------------------------------------------------------
	
	/**
	 * コンストラクタ
	 */
	private function __construct() {
		
		$this -> _parse();
		
	}
	
	//------------------------------ public --------------------------------
	
	
	/**
	 * 実行
	 */
	public function run(){
		
		// コントローラ読み込み
		if ( self::$useModuleFlg === FALSE ) {
			require_once $this -> controllerDir . '/' . $this -> controllerName . '.php';
		} else {
			require_once $this -> controllerDir . '/' . $this -> moduleName . '/' . $this -> controllerName . '.php';
		}
		
		// コントローラオブジェクト作成
		$this -> requestObj = new $this -> controllerName;
		$this -> requestObj -> setControllerName( $this -> controllerName );
		$this -> requestObj -> setActionName( $this -> actionName );
		$this -> requestObj -> setResponseFlg( $this -> responseFlg );
		
		// 初期処理
		if ( call_user_func( array( $this -> requestObj, 'initial' ), $this -> userParamArray ) === FALSE ) {
			echo( 'exec beforeRun error!!' );
			exit;
		} else {
			;
		}
		
		// 前処理
		if ( call_user_func( array( $this -> requestObj, 'beforeRun' ) ) === FALSE ) {
			//echo( 'exec beforeRun error!!' );
			//exit;
			return;
		} else {
			;
		}
		
		// 処理実行
		if ( self::$execActionFlg === TRUE ) {
			if ( call_user_func( array( $this -> requestObj, $this -> actionName ) ) === FALSE ) {
				echo( 'exec action error!!' );
				exit;
			}
		} else {
			;
		}
		
		// 後処理
		if ( self::$execAfterRunFlg === TRUE ) {
			if ( call_user_func( array( $this -> requestObj, 'afterRun' ) ) === FALSE ) {
				echo( 'exec afterRun error!!' );
				exit;
			} else {
				;
			}
		} else {
			;
		}
		
		// 終了処理
		if ( self::$execTerminalFlg === TRUE ) {
			if ( call_user_func( array( $this -> requestObj, 'terminal' ) ) === FALSE ) {
				echo( 'exec terminal error!!' );
				exit;
			} else {
				;
			}
		} else {
			;
		}
		
		// レスポンス返却後処理
		call_user_func( array( $this -> requestObj, 'afterResponse' ) );
	
	}
	
	
	//--------------------------------- private -------------------------------
	
	/**
	 * URLを元に呼び出すコントローラとアクションを設定
	 */
	private function _parse(){
		
		$array = explode( '?', $_SERVER['REQUEST_URI'] );
		$array = explode( '/', $array[0] );
		
		// モジュール機能を利用する場合
		if ( self::$useModuleFlg === TRUE ) {
			if ( strlen( $array[1] ) == 0 ) {
				$this -> moduleName = self::$defaultModuleName;
			} else {
				$this -> moduleName = $array[1];
				array_shift( $array );
			}
		} else {
			;
		}
		
		// コントローラ名設定
		if ( strlen( $array[1] ) == 0 ) {
			$this -> controllerName = 'IndexController';
		} else {
			$this -> controllerName = ucfirst( $array[1] ) . 'Controller';
		}
		
		// アクション名設定
		if ( isset( $array[2] ) === FALSE ) {
			$this -> actionName = 'index';
		} else {
			$actionArray = explode( '-', $array[2] );
			$this -> actionName = $actionArray[0];
			for ( $i = 1; $i < count( $actionArray ); $i++ ) {
				$this -> actionName .= ucfirst( $actionArray[$i] );
			}
		}
		$this -> actionName .= 'Action';
		
		$i = 0;
		foreach ( $array as $data ) {
			$i++;
			if ( $i <= 3 ) {
				continue;
			} else {
				;
			}
			$this -> userParamArray[] = $data;
		}
		
	}
		
}