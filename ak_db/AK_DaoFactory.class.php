<?php

/**
 * DAO作成
 * @author TADASUKE
 */
class AK_DaoFactory{
	
	/**
	 * DAO配列
	 * @var array[AK_Dao]
	 */
	private static $daoArray = array();
	
	/**
	 * DB接続情報配列
	 * @var array[AK_DbConfig]
	 */
	private static $dbConfigArray = array();
	public static function getDbConfigArray() {
		return self::$dbConfigArray;
	}
	
	
	//----------------------------------- public static -------------------------------------
	
	/**
	 * DAO取得
	 * @param string $dbIdemtificationName
	 * @return AK_Dao
	 */
	public static function getDao( $dbIdemtificationName ) {
		
		// 配列の中に存在しなかった場合
		if ( array_key_exists( $dbIdemtificationName, self::$daoArray ) === FALSE ) {
			self::$daoArray[$dbIdemtificationName] = new AK_Dao( self::$dbConfigArray[$dbIdemtificationName] );
		} else {
			;
		}

		return self::$daoArray[$dbIdemtificationName];
	}
	
	
	/**
	 * 全DAO取得
	 * @return array[AK_Dao]
	 */
	public static function getAllDao() {
		return self::$daoArray;
	}
	
	
	/**
	 * DB設定情報配列追加
	 * @param AK_DbConfig $akDbConfigObj
	 * @param string $dbIdentificaitonName
	 */
	public static function addDbConfig( AK_DbConfig $akDbConfigObj, $dbIdentificaitonName = AK_Db::DEFAULT_DB_IDENTIFICATION_NAME ) {
		
		self::$dbConfigArray[$dbIdentificaitonName] = $akDbConfigObj;
		
	}
	
	
	/**
	 * オールコミット
	 */
	public static function allCommit() {
		$daoArray = self::getAllDao();
		foreach ( $daoArray as $dao ) {
			$dao -> commit();
		}
	}
	
	
	/**
	 * オールロールバック
	 */
	public static function allRollback() {
		$daoArray = self::getAllDao();
		foreach ( $daoArray as $dao ) {
			$dao -> rollback();
		}
	}
	
}
