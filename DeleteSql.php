<?php
class DeleteSql {
	private $table;
	private $whereValues;
	private $params;

	public function table($table) {
		$this->table = $table;
	}

	public function whereValues($values) {
		$this->whereValues = $values;
	}

	public function toString() {
		if (empty($this->table)) {
			return;
		}
		foreach ($this->whereValues as $key => $value) {
			if (!empty($where)) {
				$where.= ', ';
			}
			$where.= $key.' = ?';
			$this->params[] = $value;
		}
		$sql = 'DELETE FROM '.$this->table;
		if (!empty($where)) {
			$sql.=' WHERE '.$where;
		}
		return $sql;
	}

	public function getParams() {
		return $this->params;
	}
}