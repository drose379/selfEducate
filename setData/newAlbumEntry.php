<?php


require 'connect.php';

class newAlbum {

	private $subject;
	private $lesson;
	private $imageLocation;
	private $albumName;
	private $albumDesc;

	//Need to grab album name and desc from post

	public function run() {
		$post = file_get_contents("php://input");
		var_dump($post);
		/*
		$this->subject = $post["subject"];
		$this->lesson = $post["lesson"];
		$this->imageLocation = $post["imageLocation"];
		$this->albumName = $post["albumName"];
		$this->albumDesc = $post["albumDesc"];

		$this->addLessonAlbum();
		*/
	}

	public function addLessonAlbum() {
		$connection = Connection::get();
		$stmt = $connection->prepare("INSERT INTO lesson_albums (subject,lesson,album,default_photo,description) VALUES 
			(:subject,:lesson,:albumName,:defaultPhoto,:description)");
	}

}
