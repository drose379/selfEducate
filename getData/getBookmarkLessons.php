<?php

class getBookmarkLessons {

	public function run() {
		$post = file_get_contents("php://input");
		$post = json_decode($post,true);
		$owner_id = $post["owner_id"];

		/*
			* Need to get all bookmarkIDs for the given user, also need to get lesson_privacy of each bookmark to decide where to get lessons from

			* Once each bookmark returns its ID and privacy, loop over each bookmark and check its privacy, if its privacy is PUBLIC,
				*pull its lessons from the "lessons" table. 
				* If the bookmark is PRIVATE, pull the lessons from the bookmark_lesson table.
		*/

		$bookmarkInfo = $this->getBookmarkInfo($owner_id);
		$bookmarkMaster = $this->getFullInfo($bookmarkInfo);
		$this->sendResponse($bookmarkMaster);
	}

	public function getConnection() {
		$connection = new PDO ('mysql:host=localhost;dbname=codeyour_self_educate','codeyour','dar150267');
		return $connection;
	}

	public function getBookmarkInfo($ownerID) {
		$bookmarkData = [];
		$con = $this->getConnection();
		$stmt = $con->prepare("SELECT subject_name,subscribed,lesson_privacy,bookmark_id FROM subject_bookmarks WHERE owner_id = :ownerID");
		$stmt->bindParam(':ownerID',$ownerID);
		$stmt->execute();
		while($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$bookmarkData[] = $result;
		}
		return $bookmarkData;
	}

	public function getFullInfo($bookmarks) {

		/*
			* If subscribed is 0, the user HAS NOT subscribed to the subject
				* NEED to grab lessons from bookmark_lessons table for lessons,
				* Also need to grab lessons from the lessons table that correspond to the bookmarkID of the bookmark
			* If subscribed is 1, the user HAS subscribed to the subeject
				* Need to grab lessons from the lessons table the correspond with the subject name
		*/	

		$masterBookmarks = [];

		foreach ($bookmarks as $bookmark) {
			$bookmarkName = $bookmark["subject_name"];
			$bookmarkID = $bookmark["bookmark_id"];
			$subscribed = $bookmark["subscribed"];
			$lessonPrivacy = $bookmark["lesson_privacy"];

			if ($subscribed == "0") {
			//pulls lessons for person who IS NOT subscribed to subject
				$currentInfo = $this->getUnsubscribedLessons($bookmarkID,$lessonPrivacy);
			} else if ($subscribed == "1" ) {
			//pulls lessons for person who IS subscribed to subject
				$currentInfo = $this->getSubscribedLessons($bookmarkName,$bookmarkID,$lessonPrivacy);
			}
			$masterBookmarks[$bookmarkName] = $currentInfo;
		}
		return $masterBookmarks;
	}

	public function getUnsubscribedLessons($bookmarkID,$lessonPrivacy) {
		//Gets called if user is NOT SUBSCRIBED
		$masterLessons = [];
		$lessons2 = [];
		
		/*
			* IF bookmarks lessons are PRIVATE, this would be UNSUBSCRIBED + PRIVATE
				* Pull from bookmark_lesson and bkmk_private_lessons
			* IF bookmarks lessons are PUBLIC, this would be UNSUBSCRIBED + PUBLIC
				*Pull from bookmark_lesson and lesson WHERE bookmarkID = :bookmarkID
		*/

		$con = $this->getConnection();

		$stmt = $con->prepare("SELECT lesson FROM bookmark_lesson WHERE bookmarkID = :bookmarkID");
		$stmt->bindParam(':bookmarkID',$bookmarkID);
		$stmt->execute();
		while($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$masterLessons[] = $result;
		}
		$masterLessons = array_column($masterLessons,'lesson');

		if ($lessonPrivacy == "PRIVATE") {
			$stmt2 = $con->prepare("SELECT lesson FROM bkmk_private_lessons WHERE bookmarkID = :bookmarkID");
			$stmt2->bindParam(':bookmarkID',$bookmarkID);
			$stmt2->execute();
			while ($result2 = $stmt2->fetch(PDO::FETCH_ASSOC)) {
				$lessons2[] = $result2;
			}
			$lessons2 = array_column($lessons2,'lesson');
		} 
		else if ($lessonPrivacy == "PUBLIC") {
			//pull from lesson where bookmarkID = :bookmarkID
			$stmt3 = $con->prepare("SELECT lesson_name FROM lesson WHERE bookmarkID = :bookmarkID");
			$stmt3->bindParam(':bookmarkID',$bookmarkID);
			$stmt3->execute();
			while($result3 = $stmt3->fetch(PDO::FETCH_ASSOC)) {
				$lessons2[] = $result3;
			}
			$lessons2 = array_column($lessons2,'lesson_name');
		}

		//add lessons from second query to the master lessons array
		foreach ($lessons2 as $lesson) {
			$masterLessons[] = $lesson;
		}

		return $masterLessons;
	}

	public function getSubscribedLessons($subject,$bookmarkID,$lessonPrivacy) {
		$masterLessons = [];
		$lessons2 = [];

		$con = $this->getConnection();
		/*
			* User is subscribed to the subject
				*First, check if users lessons are Public or Private
					* If lessons are private, pull from bkmk_private_lessons where bookmarkID = :bookmarkID
						* Also pull from lesson WHERE subject = :subject
					* If lessons are public, pull from lesson where subject = :subject AND bookmarkID <> :bookmarkID (<> === !=)
						* Also pull from lesson WHERE bookmarkID = :bookmarkID 
		*/

		if ($lessonPrivacy == "PRIVATE") {
			$stmt = $con->prepare("SELECT lesson FROM bkmk_private_lessons WHERE bookmarkID = :bookmarkID");
			$stmt->bindParam(':bookmarkID',$bookmarkID);
			$stmt->execute();
			while($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
				$masterLessons[] = $result;
			}
			$masterLessons = array_column($masterLessons,'lesson');

			$stmt2 = $con->prepare("SELECT lesson_name FROM lesson WHERE subject = :bookmarkName");
			$stmt2->bindParam(':bookmarkName',$subject);
			$stmt2->execute();
			while ($result2 = $stmt2->fetch(PDO::FETCH_ASSOC)) {
				$lessons2[] = $result2;
			}
			$lessons2 = array_column($lessons2,'lesson_name');
		}
		else if ($lessonPrivacy == "PUBLIC") {
			$stmt = $con->prepare("SELECT lesson_name FROM lesson WHERE subject = :bookmark");
			$stmt->bindParam(':bookmark',$subject);
			$stmt->execute();
			while($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
				$masterLessons[] = $result;
			}
			$masterLessons = array_column($masterLessons,'lesson_name');
		}
		if ($lessons2 != null) {
			foreach ($lessons2 as $lesson) {
				$masterLessons[] = $lesson;
			}
		}
		return $masterLessons;
	}

	public function sendResponse($allLessons) {
		header('Content Type:text/plain;charset=utf:8');
		echo json_encode($allLessons);
	}

}