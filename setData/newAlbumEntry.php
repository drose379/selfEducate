<?php

require 'connect.php';

class newAlbum {

	private $subject;
	private $lesson;
	private $imageLocation;

	public function run() {
		$post = file_get_contents("php://input");
		$this->subject = $post["subject"];
		$this->lesson = $post["lesson"];
		$this->imageLocation = $post["imageLocation"];

		$this->addLessonAlbum();
	}

	public function addLessonAlbum() {
		$connection = Connection::get();
		if ($connection != null) {
			echo "Connection is not null!";
		}
	}
