<?php

class albumDefault {
	public function run() {

		echo "Called";

		$imageTempName = $_FILES["photo"]["tmp_name"];
		$imageRealName = $_FILES["photo"]["name"];

		//get image resource from temp location
		$imageResource = imagecreatefromjpeg($imageTempName);

		$randomName = substr(md5($imageRealName),0,10).".jpg";

		$path = "/var/www/selfEducate/albumDefaults/" . $randomName;

		//create/add file
		$imageFile = imagejpeg($imageResource,$path);

		echo $randomName;

	}
}