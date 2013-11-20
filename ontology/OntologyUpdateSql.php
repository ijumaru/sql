<?php
class OntologyUpdateSql {
	private $con;
	private $values = array("subject" => "", "predict" => "", "object" => "");
	private $params;

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
	 * @param unknown $conceptField conceptのフィールド（name/uri）
	 * @param unknown $value 値
	 */
	public function setConcept($ontologyField, $conceptField, $value) {
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

	public function toString($ontologyid) {
		if (empty($this->values["subject"]) || empty($this->values["predict"])
			|| empty($this->values["object"]) || empty($ontologyid)) {
			return;
		}
		$sql = new UpdateSql();
		$sql->table("ontology");
		$sql->setValues($this->values);
		$sql->whereValues(array("id" => $ontologyid));
		$sqlString = $sql->toString();
		$this->params = $sql->getParams();
		return $sqlString;
	}

	public function getParams() {
		return $this->params;
	}
}