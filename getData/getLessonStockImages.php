<?php

class getStockImages {
	/*
		* use glob() function to get all images from stock image directory
		* use a foreach or for loop to loop over each item returned by glob and encode it to Base64
		* return array of Base64 strings 
	*/


	public function run() {

		$master = [];

		$dirPath = $_SERVER{DOCUMENT_ROOT} . "/httpTest/lessonStockImages/";
		$dirIterator = new DirectoryIterator($dirPath);
		
		foreach ($dirIterator as $file) {
			$currentInfo = [];

			$imagePath = $file->getPath() . "/" . $file->getFilename();

			$image = file_get_contents($imagePath);

			$base64Image = base64_encode($image);
			
			/*
				* Need to create a master array for each image holding its path on the server and its Base64 encoded string
				* Image holds the full file path, use the image variable to create the Base64 string and to save URL to string 

				* This script is used to supply all stock lesson images to user when they are creating a new lesson and need a stock image
				* Java code will decode the Base64 string into a bitmap and display it to the user to choose from
			*/

			$currentInfo["imagePath"] = $imagePath;
			$currentInfo["base64"] = $base64Image;

			$master[] = $currentInfo;

		}

		//echo json_encode($master);
		echo json_encode($master);
	}
}