<?php

class subCreater {

public function run() {
	$post = file_get_contents("php://input");
	$post = json_decode($post,true);

	$subject = $post["subject"];
	$ownerID = $post["owner_id"];
	$privacy = $post["privacy"];
	$category = $post["category"];
	$date = date('F j, Y');

	$this->insert($subject,$ownerID,$date,$privacy,$category);
}

public function getConnection() {
	$connection = new PDO ('mysql:host=localhost;dbname=codeyour_self_educate','codeyour','dar150267');
	return $connection;
}

public function insert($subject,$ownerID,$date,$privacy,$category) {
	$connection = $this->getConnection();
	$stmt = $connection->prepare("INSERT INTO subject (name,owner_id,privacy,category,start_date) VALUES (:subject,:ownerID,:privacy,:category,:currentDate)");
	$stmt->bindParam(':subject',$subject);
	$stmt->bindParam(':ownerID',$ownerID);
	$stmt->bindParam(':privacy',$privacy);
	$stmt->bindParam(':category',$category);
	$stmt->bindParam(':currentDate',$date);
	$stmt->execute();
}


}