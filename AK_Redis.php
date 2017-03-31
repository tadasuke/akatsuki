<?php

/**
 * Redisクラス
 * @author daisuke
 */
class AK_Redis extends Redis
{
	const DEFAULT_PORT            = 6379;
	const DEFAULT_TIMEOUT_SECONDS = 5;

	/**
	 * ソート済みセット型追加データ配列
	 * @var array
	 */
	private $zAddDataArray = NULL;

	/**
	 * ソート済みセット型データ追加
	 */
	public function zAdd( $key, $score, $value, $beforeScore )
	{
		parent::zAdd( $key, $score, $value );

		$this->zAddDataArray[] = [
			'key'          => $key,
			'score'        => $score,
			'value'        => $value,
			'before_score' => $beforeScore,
		];
	}

	/**
	 * 全ロールバック
	 */
	public function allRollback()
	{
		if ( is_null( $this->zAddDataArray ) === FALSE ) {
			foreach ( $this->zAddDataArray as $zAddData ) {
				parent::zAdd( $zAddData['key'], $zAddData['before_score'], $zAddData['value'] );
			}
		}
	}
}