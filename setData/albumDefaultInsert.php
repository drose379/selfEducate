<?php

class albumDefault {
	public function run() {

		echo "Called";

		$imageTempName = $_FILES["photo"]["tmp_name"];
		$imageRealName = $_FILES["photo"]["name"];

		//get image resource from temp location
		$imageResource = imagecreatefromjpeg($imageTempName);

		//create file
		$imageFile = imagejpeg($imageResource);

		$path = "/var/www/selfEducate/albumDefaults/" . $imageRealName;

		file_put_contents($imageRealName,$imageFile);

	}
}