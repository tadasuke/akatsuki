<?php

/**
 * バスボールデータ
 * @author TADASUKE
 */
class BathBallData extends BaseDb {
	
	public function __construct() {
		$this -> tableName = 'bath_ball_data';
	}
	
	
	
	/**
	 * バスボールデータ更新
	 * @param int $thermaeUserId
	 * @param string $bathBallId
	 * @param int $bathBallCount
	 */
	public function updateBathBallCount( $thermaeUserId, $bathBallId, $bathBallCount ) {
		
		OutputLog::outLog( OutputLog::INFO , __METHOD__, __LINE__, 'START' );
		OutputLog::outLog( OutputLog::INFO , __METHOD__, __LINE__, 'thermae_user_id:' . $thermaeUserId );
		OutputLog::outLog( OutputLog::INFO , __METHOD__, __LINE__, 'bath_ball_id:'    . $bathBallId );
		OutputLog::outLog( OutputLog::INFO , __METHOD__, __LINE__, 'bath_ball_count:' . $bathBallCount );
		
		// SQL作成
		$sqlcmd =
			  "UPDATE " . $this -> tableName . " "
			. "SET "
				. "bath_ball_count = ? "
			. "WHERE thermae_user_id = ? "
			. "AND bath_ball_id = ? "
			//. "AND delete_flg = ? "
			;
		
		// バインド値設定
		$bindArray = array(
			  array( $bathBallCount, PDO::PARAM_INT )
			, array( $thermaeUserId, PDO::PARAM_INT )
			, array( $bathBallId   , PDO::PARAM_STR )
			//, array( DELETE_FLG_FALSE, PDO::PARAM_STR )  
		);
		
		$this -> sqlcmd = $sqlcmd;
		$this -> bindArray = $bindArray;
		$this -> exec();
		
		OutputLog::outLog( OutputLog::INFO , __METHOD__, __LINE__, 'START' );
	}
	
	
	
	
	/**
	 * 熟練度更新
	 * @param int $thermaeUserId
	 * @param string $bathBallId
	 * @param int $exp
	 */
	public function updateExp( $thermaeUserId, $bathBallId, $exp ) {
		
		OutputLog::outLog( OutputLog::INFO, __METHOD__, __LINE__, 'START' );
		OutputLog::outLog( OutputLog::INFO, __METHOD__, __LINE__, 'thermaeUserId:' . $thermaeUserId );
		OutputLog::outLog( OutputLog::INFO, __METHOD__, __LINE__, 'bathBallId:'    . $bathBallId );
		OutputLog::outLog( OutputLog::INFO, __METHOD__, __LINE__, 'exp:'           . $exp );
		
		$this -> sqlcmd =
			"UPDATE " . $this -> tableName . " "
			. "SET exp = ? "
			. "WHERE thermae_user_id = ? "
			. "AND bath_ball_id = ? "
			//. "AND delete_flg = ? "
			;

		$this -> bindArray = array(
			  array( $exp            , PDO::PARAM_INT )
			, array( $thermaeUserId  , PDO::PARAM_INT )
			, array( $bathBallId     , PDO::PARAM_STR )
			//, array( DELETE_FLG_FALSE, PDO::PARAM_STR )
		);
		
		$this -> exec();
		
		OutputLog::outLog( OutputLog::INFO, __METHOD__, __LINE__, 'END' );
		
	}
	
	
	
	/**
	 * データ
	 * テルマエユーザID、バスボールIDを元にデータを取得する
	 * @param int $thermaeUserId
	 * @param string $bathBallId
	 */
	public function getDataByThermaeUserIdBathBallId( $thermaeUserId, $bathBallId ) {
		
		OutputLog::outLog( OutputLog::INFO, __METHOD__, __LINE__, 'START' );
		//OutputLog::outLog( OutputLog::INFO, __METHOD__, __LINE__, 'thermaeUserId:' . $thermaeUserId );
		//OutputLog::outLog( OutputLog::INFO, __METHOD__, __LINE__, 'bathBallId:'    . $bathBallId );
		
		$this -> sqlcmd =
			"SELECT "
				. "  thermae_user_id "
				. ", bath_ball_id "
				. ", bath_ball_count "
				. ", exp "
			. "FROM " . $this -> tableName . " "
			. "WHERE thermae_user_id = ? "
			. "AND bath_ball_id = ? "
			. "AND delete_flg = ? "
			;
			
		$this -> bindArray = array(
			  array( $thermaeUserId  , PDO::PARAM_INT )
			, array( $bathBallId     , PDO::PARAM_STR )
			, array( DELETE_FLG_FALSE, PDO::PARAM_STR )
		);
		
		$this -> select();
		
		OutputLog::outLog( OutputLog::INFO, __METHOD__, __LINE__, 'END' );
		return $this -> valueArray;
		
	}
	
	
	
	
}