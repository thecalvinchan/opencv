<?php
// Processing function takes in associative array with id and pictures 
$response = "Request received. Processing.";
ignore_user_abort(true);
header("Connection: close");
header("Content-Length: " . mb_strlen($response));
echo $response;
flush();

require_once 'facebook-php-sdk/src/facebook.php';
require_once 'opencv.php';

ob_start();

// Handles POST requests
$access_token = $_POST['access_token'];

$facebook = new Facebook(array(
  'appId'  => '755517144464680',
  'secret' => '60f3a2763be6b3108359ccabcda159ea',
));

$facebook->setAccessToken($access_token);

// Get User ID
$user = $facebook->getUser();

$friends_list = $facebook->api('/'.$user.'/friends?fields=name,gender',array('access_token' => $access_token)); 

// Grab each friend's FB albums and find the Profile Pictures album
// Array of Facebook ID's as keys and arrays of links to profile pictures as values
$results;

// Array of Facebook likes
$list;

// Connect to MySQL to store friend info
$con = new mysqli_connect('http://54.200.89.7', 'root', 'monster', 'core');
if (mysqli_connect_errno()) {
  echo 'Failed to connect to MySQL: ' . mysqli_connect_error();
}

// Loop through each friend
foreach($friends_list['data'] as $friend) {
  $fid = $friend['id'];
  $name = $friend['name'];

  // Grab each friend's likes
  $likes = $facebook->api('/'.$fid.'/likes', array('access_token' => $access_token));
  foreach($likes['data'] as $like) {
    $list[] = $like['name'];
  }

  $listed_likes = implode(', ', $list);

  // Grab each friend's FB albums and find the Profile Pictures album
  $albums = $facebook->api('/'.$fid.'/albums', array('access_token' => $access_token));
  foreach($albums['data'] as $album) {
    if ($album['name'] == 'Profile Pictures') {
      // Harvest all profile pictures and put them into an array
      $prof_pics = $facebook->api('/'.$album['id'].'/photos', array('access_token' => $access_token));
      $pics_arr;
      foreach($prof_pics['data'] as $prof_pic) {
        $pics_arr[] = $prof_pic['source'];
      }
      break;
    }
  }
  $results[$fid] = $pics_arr;

  mysqli_query($con, 'INSERT INTO People (fb_id, name, likes) 
                      VALUES ('.$fid.','.$name.','.$listed_likes.')');
}

generateCSV($user,$results);



?>
