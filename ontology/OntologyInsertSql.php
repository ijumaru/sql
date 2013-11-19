<?php
class OntologyInsertSql {
	private $ontology = array('subject' => array(), 'predict' => array(), 'object' => array());
	private $params;
	private $con;

	public function __construct($con) {
		$this->con = $con;
	}

	public function setOntology($ontology) {
		$this->ontology = $ontology;
	}

	public function toString() {
		foreach ($this->ontology as $key => $value) {
			$sql = new SelectSql();
			$sql->from("concept");
			$sql->where("concept.name = ?");
			$sql->andWhere("concept.uri = ?");
			$stmt = $this->con->prepare($sql->toString());
			$stmt->execute(array_values($value));
			$conceptData = $stmt->fetchAll();
			if (count($conceptData) === 0) {
				$sql = new InsertSql();
				$sql->into("concept");
				$sql->values($value);
				$stmt = $this->con->prepare($sql->toString());
				$stmt->execute($sql->getParams());
				$conceptid[$key] = $this->con->lastInsertId();
			} else {
				$conceptid[$key] = $conceptData[0]["id"];
			}
		}
		$sql = new SelectSql();
		$sql->from("ontology");
		$sql->where("ontology.subject = ?");
		$sql->andWhere("ontology.predict = ?");
		$sql->andWhere("ontology.object = ?");
		$stmt = $this->con->prepare($sql->toString());
		$stmt->execute(array_values($conceptid));
		if ($stmt->rowCount() === 0) {
			$sql = new InsertSql();
			$sql->into("ontology");
			$sql->values($conceptid);
// 			$this->execute($sql->toString(), $sql->getParams());
			$sqlString = $sql->toString();
			$this->params = $sql->getParams();
			return $sqlString;
		}
	}

	public function getParams() {
		return $this->params;
	}
}