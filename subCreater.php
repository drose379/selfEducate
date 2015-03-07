<?php

class subCreater {

public function run() {
	$post = file_get_contents("php://input");
	$post = json_decode($post,true);

	$subject = $post["subjectName"];
	$serialID = $post["serialID"];
	$privacy = $post["privacy"];
	$date = date('F j, Y');

	$this->insert($subject,$serialID,$date,$privacy);
}

public function getConnection() {
	$connection = new PDO ('mysql:host=localhost;dbname=codeyour_self_educate','codeyour','dar150267');
	return $connection;
}

public function insert($subject,$serialID,$date,$privacy) {
	$connection = $this->getConnection();
	$stmt = $connection->prepare("INSERT INTO subject (name,serial_id,privacy,start_date) VALUES (:subject,:serialID,:privacy,:currentDate)");
	$stmt->bindParam(':subject',$subject);
	$stmt->bindParam(':serialID',$serialID);
	$stmt->bindParam(':currentDate',$date);
	$stmt->bindParam(':privacy',$privacy);
	$stmt->execute();
}


}