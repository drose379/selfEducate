<?php

require_once 'connect.php';

class subCreater {

public function run() {
	$post = file_get_contents("php://input");
	$post = json_decode($post,true);

	$category = $post[0];
	$ownerID = $post[1];
	$subject = $post[2];
	$privacy = $post[3];

	$date = date('F j, Y');
	$this->insert($subject,$ownerID,$date,$privacy,$category);
}


public function insert($subject,$ownerID,$date,$privacy,$category) {
	$connection = Connection::get();
	$stmt = $connection->prepare("INSERT INTO subject (name,owner_id,privacy,category,start_date) VALUES (:subject,:ownerID,:privacy,:category,:currentDate)");
	$stmt->bindParam(':subject',$subject);
	$stmt->bindParam(':ownerID',$ownerID);
	$stmt->bindParam(':privacy',$privacy);
	$stmt->bindParam(':category',$category);
	$stmt->bindParam(':currentDate',$date);
	$stmt->execute();
}


}