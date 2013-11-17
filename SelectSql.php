<?php
class SelectSql {
	protected $sql = "";
	private $select = "*";
	private $from = "DUAL";
	private $where = "";
	private $order = "";
	private $limit = "";

	public function toString() {
		$this->sql = "SELECT ".$this->select;
		$this->sql.= " FROM ".$this->from;
		if (!empty($this->where)) {
			$this->sql.= " WHERE ".$this->where;
		}
		if (!empty($this->order)) {
			$this->sql.= " ORDER BY ".$this->order;
		}
		if (!empty($this->limit)) {
			$this->sql.= " LIMIT ".$this->limit;
		}
		return $this->sql;
	}

	public function select($select) {
		$this->select = $select;
	}

	public function from($from) {
		$this->from = $from;
	}

	public function innerJoin($innerJoin, $on) {
		$this->from.= " INNER JOIN ".$innerJoin." on ".$on;
	}

	public function leftJoin($leftJoin, $on) {
		$this->from.= " LEFT JOIN ".$leftJoin." on ".$on;
	}

	public function where($where) {
		$this->where = $where;
	}

	public function andWhere($and) {
		$this->where.= " AND ".$and;
	}

	public function order($order) {
		$this->order = $order;
	}

	public function limit($limit) {
		$this->limit = $limit;
	}
}