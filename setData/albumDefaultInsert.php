<?php

class albumDefault {
	public function run() {

		echo "Called";

		$imageTempName = $_FILES["photo"]["tmp_name"];
		$imageRealName = $_FILES["photo"]["name"];

		var_dump($_POST);

		//get image resource from temp location
		$imageResource = imagecreatefromjpeg($imageTempName);

		$path = "/var/www/selfEducate/albumDefaults/" . $imageRealName;

		//create/add file
		$imageFile = imagejpeg($imageResource,$path);

	}
}