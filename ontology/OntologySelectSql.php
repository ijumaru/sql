<?php
class OntologySelectSql {
	private $subjectName;
	private $subjectUri;
	private $predictName;
	private $predictUri;
	private $objectName;
	private $objecturi;
	private $params;
	private $props = array('subject' => array(), 'predict' => array(), 'object' => array());
	private $id;

	public function setSubject($name, $uri) {
		$this->subjectName = $name;
		$this->subjectUri = $uri;
		$this->props["subject"] = array('name' => $name, 'uri' => $uri);
	}

	public function setPredict($name, $uri) {
		$this->predictName = $name;
		$this->predictUri = $uri;
		$this->props["predict"] = array('name' => $name, 'uri' => $uri);
	}

	public function setObject($name, $uri) {
		$this->objectName = $name;
		$this->objectUri = $uri;
		$this->props["object"] = array('name' => $name, 'uri' => $uri);
	}

	public function setId($id) {
		$this->id = $id;
	}

	/**
	 * SQLを作成して返す
	 * 比較演算子は値に%が含まれているかどうかで判定
	 * 含まれていればLIKE、いなければ=
	 */
	public function toString() {
		$sql = new SelectSql();
		$sql->select("
				ontology.id
				, subject.name AS subject_name
				, subject.uri AS subject_uri
				, predict.name AS predict_name
				, predict.uri AS predict_uri
				, object.name AS object_name
				, object.uri AS object_uri
		");
		$sql->from("ontology");
		$sql->innerJoin("concept subject", "subject.id = ontology.subject");
		$sql->innerJoin("concept predict", "predict.id = ontology.predict");
		$sql->innerJoin("concept object", "object.id = ontology.object");
		if (isset($this->id)) {
			$sql->where("ontology.id = ?");
			$this->params[] = $this->id;
		} else {
			$sql->where("1 = 1");
			foreach ($this->props as $key => $value) {
				foreach ($value as $key2 => $value2) {
					if (isset($value2) && !empty($value2)) {
						$operator = '=';
						if (strpos($value2, '%') !== false) {
							$operator = 'LIKE';
						}
						$sql->andWhere($key.'.'.$key2.' '.$operator.' ?');
						$this->params[] = $value2;
					}
				}
			}
		}
		return $sql->toString();
	}

	public function getParams() {
		return $this->params;
	}
}