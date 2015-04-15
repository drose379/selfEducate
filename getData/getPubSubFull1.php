<?php

class getPubSubFull1 {

	public function run() {
		$post = file_get_contents("php://input");
		$post = json_decode($post,true);
		$subNames = $post;
		$subFullInfo = $this->getSubInfo($subNames);
		$catInfo = $this->getCategoryInfo($subFullInfo);
		$this->sendResponse($subFullInfo,$catInfo);
	}

	public function getConnection() {
		$connection = new PDO ('mysql:host=localhost;dbname=codeyour_self_educate','codeyour','dar150267');
		return $connection;
	}

	public function getSubInfo($subNames) {
		$master = [];
		$con = $this->getConnection();
		$stmt = $con->prepare("SELECT name,category FROM subject WHERE name = :name");
		$stmt->bindParam(':name',$sub);
		foreach ($subNames as $sub) {
			$stmt->execute();
			while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
				$master[] = $result;
			}
		}
		return $master;
	}

	public function getCategoryInfo($subInfo) {
		$categoryInfo = [];
		$categories = array_column($subInfo,'category');
		$categories = array_unique($categories);
		file_put_contents("categoriesGrabbed.txt",$categories);
		$con = $this->getConnection();
		$stmt = $con->prepare("SELECT category,description FROM subject_category WHERE category = :category");
		$stmt->bindParam(':category',$cat);
		foreach($categories as $cat) {
			$stmt->execute();
			while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
				$categoryInfo[] = $result;
			}
		}
		return $categoryInfo;
	}

	public function sendResponse($subInfo,$catInfo) {
		header('Content Type:text/plain;charset=utf:8');
		$masterArray = [];
		$masterArray["subjectInfo"] = $subInfo;
		$masterArray["catInfo"] = $catInfo;
		echo json_encode($masterArray);
	}

}