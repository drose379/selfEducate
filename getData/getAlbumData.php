<?php

require_once 'connect.php';

class getAlbumData {
	public function run() {
		$post = file_get_contents("php://input");
		$post = json_decode($post,true);

		$subject = $post["subject"];
		$lesson = $post["lesson"];

		$data = $this->getAlbumData($subject,$lesson);

		echo json_encode($data);
	}

	public function getAlbumData($subject,$lesson) {
		$albumData = [];

		$connection = Connection::get();
		$stmt = $connection->prepare("SELECT * FROM lesson_albums WHERE subject = :subject AND lesson = :lesson");
		$stmt->bindParam(':subject',$subject);
		$stmt->bindParam(':lesson',$lesson);
		$stmt->execute();
		while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$albumData[] = $row;
		}
		return $albumData;
	}
}