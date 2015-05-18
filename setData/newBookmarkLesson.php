<?php

class newBookmarkLesson {
	public function run() {
		file_put_contents("newBookmarkL.txt","BookmarkLesson called");
		$post = file_get_contents("php://input");
		$post = json_decode($post,true);

		$bookmarkName = $post["subject_name"];
		$lessonName = $post["lesson_name"];
		$objectives = $post["objectives"];
		$tags = $post["tags"];

		$lessons_privacy = $post["lesson_privacy"];
		$subscribed = $post["subscribed"];
		$bookmarkID = $post["bookmarkID"];

		//Use the routes from getBookmarkLessons to determine where to save each type of lesson

		if ($subscribed == "0") {
			$this->insertUnsubscribedLesson($bookmarkName,$lessonName,$lessons_privacy,$bookmarkID);
			$this->addTags($tags,$bookmarkName,$lessonName);
			$this->addObjectives($objectives,$bookmarkName,$lessonName);
		} else {
			$this->insertSubscribedLesson($bookmarkName,$lessonName,$lessons_privacy,$bookmarkID);
			$this->addTags($tags,$bookmarkName,$lessonName);
			$this->addObjectives($objectives,$bookmarkName,$lessonName);
		}
	}

		public function getConnection() {
		$connection = new PDO ('mysql:host=localhost;dbname=codeyour_self_educate','codeyour','dar150267');
		return $connection;
	}

	public function insertUnsubscribedLesson($bookmark,$lesson,$lesson_privacy,$bookmarkID) {
		$con = $this->getConnection();
		/*
			* check for private or public
			* If private, insert into bkmk_private_lessons
			* If public, insert into lesson GIVE BOOKMARK_ID BECAUSE USER WANTS THEIR LESSONS PUBLIC BUT IS NOT SUBSCRIBED TO OTHER PUBLIC LESSONS
		*/
		if ($lesson_privacy == "PRIVATE") {
			$stmt = $con->prepare("INSERT INTO bkmk_private_lessons (subject,lesson_name,bookmarkID) VALUES (:bookmark,:lesson,:bookmarkID)");
			$stmt->bindParam(':bookmark',$bookmark);
			$stmt->bindParam(':lesson',$lesson);
			$stmt->bindParam(':bookmarkID',$bookmarkID);
			$stmt->execute();
		} else if ($lesson_privacy == "PUBLIC") {
			$stmt2 = $con->prepare("INSERT INTO lesson (subject,lesson_name,bookmarkID VALUES (:subject,:lesson,:bookmarkID)");
			$stmt2->bindParam(':subject',$bookmark);
			$stmt2->bindParam(':lesson',$lesson);
			$stmt2->bindParam(':bookmarkID',$bookmarkID);
			$stmt2->execute();
		}
	}

	public function insertSubscribedLesson($bookmark,$lesson,$lesson_privacy,$bookmarkID) {
		$con = $this->getConnection();
		/*
			* check for private or public
			* If private, insert into bkmk_private_lessons
			* If public, insert into lessons
		*/

		if ($lesson_privacy == "PRIVATE") {
			$stmt = $con->prepare("INSERT INTO bkmk_private_lessons (subject,lesson_name,bookmarkID) VALUES (:bookmark,:lesson,:bookmarkID)");
			$stmt->bindParam(':bookmark',$bookmark);
			$stmt->bindParam(':lesson',$lesson);
			$stmt->bindParam(':bookmarkID',$bookmarkID);
			$stmt->execute();
		} else if ($lesson_privacy == "PUBLIC") {
			//issue inserting lesson here, need to insert imageURL but not bookmarkID (null). Find way to skip over bookmarkID here
			$stmt2 = $con->prepare("INSERT INTO lesson (subject,lesson_name) VALUES (:bookmark,:lesson)");
			$stmt2->bindParam(':bookmark',$bookmark);
			$stmt2->bindParam(':lesson',$lesson);
			$stmt2->execute();
		}
	}

	public function addTags($tags,$bookmark,$lesson) {
		$con = $this->getConnection();
		$stmt = $con->prepare("INSERT INTO tags_attached (subject,tag,lesson) VALUES (:subject,:tag,:lesson)");
		$stmt->bindParam(':subject',$bookmark);
		//$stmt->bindParam(':tag',$tag);
		$stmt->bindParam(':lesson',$lesson);
		foreach ($tags as $tag) {
			$stmt->bindParam(':tag',$tag); //Need to check the $this->Tags
			$stmt->execute();
		}
	}

	public function addObjectives($objectives,$bookmark,$lesson) {
		$con = $this->getConnection();
		$stmt = $con->prepare("INSERT INTO lesson_objectives (subject,lesson,objective) VALUES (:subject,:lesson,:objective)");
		$stmt->bindParam(':subject',$bookmark);
		$stmt->bindParam(':lesson',$lesson);
		foreach ($objectives as $objective) {
			$stmt->bindParam(':objective',$objective);
			$stmt->execute();
		}
	}
}