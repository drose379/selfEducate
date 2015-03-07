<?php

require 'subCreater.php';
require 'subGetter.php';
require 'subDeleter.php';
require 'newLesson2.php';
require 'lessonGetter.php';
require 'newTag.php';
require 'getTags.php';
require 'mySubjectsGrabber.php';

class router {
    
protected $routes = array();
  
public function __construct() {
  #LoadRoutes method will add pattern/action to the routes array.
  $this->LoadRoutes();
}
    

public function loadRoutes() {
$this->routes = [
    "/newSubject" => [new subCreater,"run"],
    "/getSubjects" => [new subGetter,"run"],
    "/deleteSubject" => [new subDeleter,"run"],
    "/newLesson" => [new newLesson,"run"], //change to new script 
    "/getLessons" => [new lessonGetter,"run"],
    "/newTag" => [new newTag,"run"],
    "/getTags" => [new getTags,"run"],
    "/getLocalSubjectData" => [new mySubjectsGrabber,"run"]
    ];
}
  
public function match($path) {
  foreach ($this->routes as $route => $action) {
    if (preg_match("#^$route/?$#i",$path,$params)) {
      return [$action,$params];
    }
  }
}
  
public function run($path) {
    list($action,$params) = $this->match($path);
    $action($params);
}
    
}