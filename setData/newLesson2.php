<?php

class newLesson {

	private $subjectName;
	private $lessonName;
	private $startDate;
	private $tags;
	private $objectives;
	private $imgUri;

	public function run() {
		$post = file_get_contents("php://input");
		$post = json_decode($post,true);

		$this->subjectName = $post["subject_name"];
		$this->lessonName = $post["lesson_name"];
		$this->tags = $post["tags"];
		$this->objectives = $post["objectives"];   

		$connection = $this->getConnection();

		file_put_contents("testLesson.txt",$this->imgUri);

		$this->addBase();
		
		$this->addTags($connection);
		$this->addObjectives($connection);
		$count = $this->getLessonCount($connection);
		$this->updateLessonCount($connection,$count);
		
	}

	public function getConnection() {
		$connection = new PDO ('mysql:host=localhost;dbname=codeyour_self_educate','codeyour','dar150267');
		return $connection;
	} 

	public function getLessonCount() {
		$con = $this->getConnection();
		$stmt = $con->prepare("SELECT lesson_name FROM lesson WHERE subject = :subject ");
		$stmt->bindParam(':subject',$this->subjectName);
		$stmt->execute();
		while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
		$resultArray[] = $result;
		}
		return count($resultArray);
	}

	public function updateLessonCount($con,$count) {
		$stmt = $con->prepare("UPDATE subject SET lesson_count = :count WHERE name = :subject");
		$stmt->bindParam(':count',$count);
		$stmt->bindValue(':subject',$this->subjectName);
		$stmt->execute();
	}

	public function addBase() {
		$con = $this->getConnection();
		$a = $this->subjectName;
		$b = $this->lessonName;
		$stmt = $con->prepare("INSERT INTO lesson (subject,lesson_name) VALUES (:subject,:lesson)");
		$stmt->bindParam(':subject',$a);
		$stmt->bindParam(':lesson',$b);
		$stmt->execute();
	}

	public function addTags($con) {
		$stmt = $con->prepare("INSERT INTO tags_attached (subject,tag,lesson) VALUES (:subject,:tag,:lesson)");
		$stmt->bindParam(':subject',$this->subjectName);
		//$stmt->bindParam(':tag',$tag);
		$stmt->bindParam(':lesson',$this->lessonName);
		foreach ($this->tags as $tag) {
			file_put_contents("regLessonObjectives.txt",$tag,FILE_APPEND);
			$stmt->bindParam(':tag',$tag); //Need to check the $this->Tags
			$stmt->execute();
		}
	}

	public function addObjectives($con) {
		$stmt = $con->prepare("INSERT INTO lesson_objectives (subject,lesson,objective) VALUES (:subject,:lesson,:objective)");
		$stmt->bindParam(':subject',$this->subjectName);
		$stmt->bindParam(':lesson',$this->lessonName);
		foreach ($this->objectives as $objective) {
			$stmt->bindParam(':objective',$objective);
			$stmt->execute();
		}
	}

}