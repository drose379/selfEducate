<?php

class Connection {
	public static function get() {
		$connection = new PDO ('mysql:host=localhost;dbname=self_educate','root','HwAlJAgstN');
		return $connection;
	}
}