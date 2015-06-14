<?php

//require 'getData/getLocalSubs.php';
//require 'removeData/subDeleter.php';
require 'getData/getTags.php';
require 'getData/checkLessonItems.php';
require 'getData/getAlbumData.php';
require 'getData/getLocalSubs.php';

require 'setData/albumDefaultInsert.php';
require 'setData/newAlbumEntry.php';
/*
require 'getData/getPubSubjectNames1.php';
require 'getData/getPubSubFull1.php';
require 'getData/getBookmarks.php';
require 'getData/getCategories.php';
require 'getData/getSelectedBookmark.php';
require 'getData/getBookmarkLessons.php';
require 'getData/getLocalLessons.php';
require 'getData/getLessonStockImages.php';
require 'getData/grabImage.php';

require 'setData/subCreater.php';
require 'setData/newLesson2.php';
require 'setData/newTag.php';
require 'setData/newBookmark.php';
require 'setData/hideSubject.php';
require 'setData/newCategory.php';
require 'setData/newBookmarkLesson.php';
require 'setData/setLessonImage.php';
*/

class router {
    
protected $routes = array();
  
public function __construct() {
  #LoadRoutes method will add pattern/action to the routes array.
  $this->LoadRoutes();
}
    

public function loadRoutes() {
    /*
  $this->routes = [
    "/newSubject" => [new subCreater,"run"],
    "/deleteSubject" => [new subDeleter,"run"],
    "/newLesson" => [new newLesson,"run"], //change to new script 
    "/getLocalSubs" => [new getLocalsubs,"run"],
    "/newTag" => [new newTag,"run"],
    "/getTags" => [new getTags,"run"],
    "/getSubNames" => [new pubSubNames1,"run"],
    "/getPubSubFull1" => [new getPubSubFull1,"run"],
    "/newBookmark" => [new newBookmark,"run"],
    "/getBookmarks" => [new getBookmarks,"run"],
    "/hideSubject" => [new hideSubject,"run"],
    "/getCategories" => [new getCategories,"run"],
    "/newCategory" => [new newCategory,"run"],
    "/getBookmarkData" => [new getSelectedBookmark,"run"],
    "/newBookmarkLesson" => [new newBookmarkLesson,"run"],
    "/getBookmarkLessons" => [new getBookmarkLessons,"run"],
    "/getLocalLessons" => [new getLocalLessons,"run"],
    //getting public and local lessons are the same thing, the only difference in getting lessons is with getting bookmark lessons
    "/getPublicLessons" => [new getLocalLessons,"run"],
    "/setDefaultImage" => [new lessonImage,"run"],
    "/getImageFromURL" => [new grabImage,"run"]
    ];
    */

    $this->routes = [
        "/getTags" => [new getTags,"run"],
        "/albumDefInsert" => [new albumDefault,"run"],
        "/newAlbum" => [new newAlbum,"run"],
        "/checkLessonItems" => [new checkLessonItems,"run"],
        "/getAlbumData" => [new getAlbumData,"run"],
        "/getLocalSubs" => [new getLocalSubs,"run"]
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