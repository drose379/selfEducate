<?php

class subGetter {

	protected $PDOconnection;

	public function getConnection() {
		$connection = new PDO ('mysql:host=localhost;dbname=codeyour_self_educate','codeyour','dar150267');
		return $connection;
	}

	public function run() {
		$this->PDOconnection = $this->getConnection();
		$subjectArray = $this->getSubjects($this->PDOconnection);
		header('Content Type:text/plain;charset=utf:8');
		echo json_encode($subjectArray);
	}

	public function getSubjects($con) {
		$subjectArray = null;
		$stmt = $con->prepare("SELECT name,start_date,lesson_count FROM subject");
		$stmt->execute();
		$innerArray = null;
		while($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$innerArray[] = $result;
		}
		return $innerArray; 
	}
}