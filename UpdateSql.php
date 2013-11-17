<?php
class UpdateSql {
	private $table;
	private $setValues;
	private $whereValues;
	private $params;

	public function table($table) {
		$this->table = $table;
	}

	public function setValues($values) {
		$this->setValues = $values;
	}

	public function whereValues($values) {
		$this->whereValues = $values;
	}

	public function toString() {
		$set = '';
		foreach ($this->setValues as $key => $value) {
			if (!empty($set)) {
				$set.= ', ';
			}
			$set.= $key.' = ?';
			$this->params[] = $value;
		}
		$where = '';
		foreach ($this->whereValues as $key => $value) {
			if (!empty($where)) {
				$where.= ', ';
			}
			$where.= $key.' = ?';
			$this->params[] = $value;
		}
		$sql = 'UPDATE '.$this->table.' SET '.$set.' WHERE '.$where;
		return $sql;
	}

	public function getParams() {
		return $this->params;
	}
}