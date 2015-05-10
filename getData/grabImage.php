<?php

class grabImage {
	public function run() {

		$allImages = [];

		$post = file_get_contents("php://input");
		$imageUrls = json_decode($post,true);
		

		foreach ($imageUrls as $url) {
			//$fullPath = "http://codeyourweb.net/httpTest/" . $url;
			//$fullPath = __DIR__ . "/../" . $url;

			$currentImage = [];

			$fullPath = trim("/home/codeyour/public_html/httpTest/").trim($url);
			file_put_contents("fullPath.txt",$fullPath);
			$image = file_get_contents($fullPath);
			file_put_contents("image.txt",$image);
			$base64 = base64_encode($image);


			$currentImage["url"] = $url;
			$currentImage["base64"] = $base64;

			$allImages[] = $currentImage;
		}

		echo json_encode($allImages);
	}
}