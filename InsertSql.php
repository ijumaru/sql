<?php
class InsertSql {
	protected $into;
	protected $values;
	protected $params = array();

	public function into($into) {
		$this->into = $into;
	}

	public function values($values) {
		$this->values = $values;
	}

	public function toString() {
		$fields = '';
		$prepare = '';
		foreach ($this->values as $key => $value) {
			if (count($this->params) > 0) {
				$fields.= ',';
				$prepare.= ',';
			}
			$fields.= $key;
			$prepareKey = ':'.$key;
			$prepare.= $prepareKey;
			$this->params[$prepareKey] = $value;
		}
		$sql = 'INSERT INTO '.$this->into.'('.$fields.') VALUES('.$prepare.')';
		return $sql;
	}

	/**
	 * パラメータの取得
	 * toString()の後でないと値が取れない。要修正。
	 */
	public function getParams() {
		return $this->params;
	}
}