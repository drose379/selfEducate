<?php

class newBookmark {

	public function run() {
		$post = file_get_contents("php://input");
		$post = json_decode($post,true);

		$subject = $post["subName"];
		$category = $post["category"];
		$ownerID = $post["ownerID"];
		$bookmarkID = $post["bookmarkID"];
		$subscribe = $post["subscribe"];
		$publicLessons = $post["publicLessons"];

		if ($publicLessons == "Yes") {
			$publicLessons = "PUBLIC";
		} else if ($publicLessons == "No") {
			$publicLessons = "PRIVATE";
		}

		/*
			* First, check if user wants to subscribe to all public updates of subject
			* If YES, nothing must be inserted into bookmark_lesson, because lessons will be pulled from public "lesson" table
			* If NO, save all current public lessons from public lessons table to bookmark_lesson table.
		*/

		if ($subscribe == "No") {
			//Grab all current lessons for subject and insert them into the bookmark_lesson table with the current bookmarkID
			$currentLessons = [];

			$con = $this->getConnection();
			$stmt = $con->prepare("SELECT lesson_name FROM lesson WHERE subject = :subject");
			$stmt->bindParam(':subject',$subject);
			$stmt->execute();
			while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
				$currentLessons[] = $result;
			}
			$currentLesson = array_column($currentLessons,'lesson_name');

			//Save record of ownerID and bookmarkID to be able to grab bookmark data later for users
			$this->saveBookmarkRecord($subject,$category,$subscribe,$publicLessons,$ownerID,$bookmarkID);
			//Insert lessons from bookmarked subject that already exist, this user does not want to subscribe to subject (get updated lessons)
			$this->insertAsIsLessons($currentLesson,$subject,$bookmarkID);
		} 
		else if ($subscribe == "Yes") {
			//Just make a record of bookmark info in subject_bookmarks, all lessons will be pulled from public lessons table
			$this->savebookmarkRecord($subject,$category,$subscribe,$publicLessons,$ownerID,$bookmarkID);
		}


	}

	public function getConnection() {
		$connection = new PDO ('mysql:host=localhost;dbname=codeyour_self_educate','codeyour','dar150267');
		return $connection;
	}

	public function saveBookmarkRecord($subject,$category,$subscribe,$lessonPrivacy,$ownerID,$bookmarkID) {
		if ($subscribe == "Yes") {
			$subscribe = "1";
		} else if ($subscribe == "No") {
			$subscribe = "0";
		}
		$con = $this->getConnection();
		$stmt = $con->prepare("INSERT INTO subject_bookmarks (category,subject_name,subscribed,lesson_privacy,owner_id,bookmark_id) VALUES (:category,:subject,:subscribed,:lessonPrivacy,:owner,:bookmarkID)");
		$stmt->bindParam(':category',$category);
		$stmt->bindParam(':subject',$subject);
		$stmt->bindParam(':subscribed',$subscribe);
		$stmt->bindParam(':lessonPrivacy',$lessonPrivacy);
		$stmt->bindParam(':owner',$ownerID);
		$stmt->bindParam('bookmarkID',$bookmarkID);
		$stmt->execute();
	}

	public function insertAsIsLessons(array $asIsLessons,$subject,$bookmarkID) {
		$con = $this->getConnection();
		$stmt = $con->prepare("INSERT INTO bookmark_lesson (subject,lesson,bookmarkID) VALUES (:subject,:lesson,:bookmarkID)");
		$stmt->bindParam(':subject',$subject);
		$stmt->bindParam(':bookmarkID',$bookmarkID);
		$stmt->bindParam(':lesson',$currentLesson);
		foreach ($asIsLessons as $currentLesson) {
			$stmt->execute();
		}
	} 

}