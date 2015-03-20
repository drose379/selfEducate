<?php

class lessonGetter {

	protected $Subject;
	protected $PDOconnection;

	public function run() {
		$post = file_get_contents("php://input");
		$this->Subject = $post;
		$this->PDOconnection = $this->getConnection();
		$this->sendResponse();
	}

	public function getConnection() {
		$connection = new PDO ('mysql:host=localhost;dbname=codeyour_self_educate','codeyour','dar150267');
		return $connection;
	}

	public function getData() {
		//ADD priority TO THE SELECT QUERY, RESULT WILL BE AN ARRAY. USE FOR LOOP IN JAVA
		$stmt = $this->PDOconnection->prepare("SELECT lesson_name,priority FROM lesson WHERE subject = :subject ORDER BY priority DESC");
		$stmt->bindParam(':subject',$this->Subject);
		$stmt->execute();

		$innerArray = null;
		while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$innerArray[] = $result;
		}
		return $innerArray;
	}

	public function sendResponse() {
		$result = $this->getData();
		$result = json_encode($result);
		file_put_contents("lessonsList.txt",$result);
		header('Content Type:text/plain;charset=utf:8');
		echo $result;
	}

}