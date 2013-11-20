<?php
class OntologyInsertSql {
	private $con;
	private $values = array("subject" => "", "predict" => "", "object" => "");

	/**
	 * コンストラクタ
	 * @param PDO $con
	 */
	public function __construct($con) {
		$this->con = $con;
	}

	/**
	 * conceptIDをセットする
	 * @param unknown $ontologyField ontologyのフィールド（subject/predict/object）
	 * @param unknown $conceptField conceptのフィールド（name, uri）
	 * @param unknown $value 値
	 */
	public function setId($ontologyField, $conceptField, $value) {
		$sql = new SelectSql();
		$sql->from("concept");
		$sql->where("concept.".$conceptField." = ?");
		$stmt = $this->con->prepare($sql->toString());
		$stmt->execute(array($value));
		$conceptData = $stmt->fetchAll();
		if (count($conceptData) === 0) {
			$sql = new InsertSql();
			$sql->into("concept");
			$sql->values(array($conceptField => $value));
			$stmt = $this->con->prepare($sql->toString());
			$stmt->execute($sql->getParams());
			$this->values[$ontologyField] = $this->con->lastInsertId();
		} else {
			$this->values[$ontologyField] = $conceptData[0]["id"];
		}
	}

	public function toString() {
		if (empty($this->values["subject"]) || empty($this->values["predict"]) || empty($this->values["object"])) {
			return;
		}
		$sql = new SelectSql();
		$sql->from("ontology");
		$sql->where("ontology.subject = ?");
		$sql->andWhere("ontology.predict = ?");
		$sql->andWhere("ontology.object = ?");
		$stmt = $this->con->prepare($sql->toString());
		$stmt->execute(array_values($this->values));
		if ($stmt->rowCount() === 0) {
			$sql = new InsertSql();
			$sql->into("ontology");
			$sql->values($this->values);
			return $sql->toString();
		}
	}

	public function getParams() {
		return $this->values;
	}
}