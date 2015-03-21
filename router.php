<?php

require 'setData/subCreater.php';
require 'getData/subGetter.php';
require 'removeData/subDeleter.php';
require 'getData/lessonGetter.php';
require 'getData/getTags.php';
require 'getData/mySubjectsGrabber.php';
require 'getData/getPubSubjectNames1.php';
require 'getData/getPubSubFull1.php';
require 'getData/getBookmarks.php';

require 'setData/newLesson2.php';
require 'setData/newTag.php';
require 'setData/newBookmark.php';
require 'setData/hideSubject.php';

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
    "/getLocalSubjectData" => [new mySubjectsGrabber,"run"],
    "/getSubNames" => [new pubSubNames1,"run"],
    "/getPubSubFull1" => [new getPubSubFull1,"run"],
    "/newBookmark" => [new newBookmark,"run"],
    "/getBookmarks" => [new getBookmarks,"run"],
    "/hideSubject" => [new hideSubject,"run"]
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