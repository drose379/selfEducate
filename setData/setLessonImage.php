<?php

class lessonImage {
	public function run() {
		$post = file_get_contents("php://input");
		$post = json_decode($post,true);
		$base64Image = $post["base64Image"];
		$imageString = base64_decode($base64Image);
		
		$image = imagecreatefromstring($imageString);

		$randID  = rand(1,1000000);

		$fileName = "lessonImages/" . $randID . ".jpg";

		imagejpeg($image,$fileName,100);

		//need to return the image url to the java code

		$savedURL = "lessonImages/" . $randID . ".jpg";

		echo $savedURL;
	}
}