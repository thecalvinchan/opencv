<?php
// Processing function takes in associative array with id and pictures 
//$response = "Request received. Processing.";
//ignore_user_abort(true);
//header("Connection: close");
//header("Content-Length: " . mb_strlen($response));
//echo $response;
//flush();

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

// Connect to MySQL to store friend info
$db = new PDO('mysql:host=0.0.0.0;dbname=core','root','monster');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Loop through each friend
foreach($friends_list['data'] as $friend) {
  $fid = $friend['id'];
  $name = $friend['name'];
  // Array of Facebook likes
  $list = array();
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

  $check_query = $db->prepare('SELECT * FROM People WHERE fb_id=:fb_id');
  $check_query->bindParam(':fb_id', $fid);
  try {
    $check_query->execute();
  } catch (Exception $e) {
    echo $e;
    exit;
  }
  
  if ($check_query->rowCount() == 0) {
    echo 'DNE';
    $query = 'INSERT INTO People (fb_id, name, likes) VALUES (:fb_id, :name, :likes)';
  } else {
    echo 'Update';
    $query = 'UPDATE People SET name=:name, likes=:likes WHERE fb_id=:fb_id';
  }
  $insert_query = $db->prepare($query);
  $insert_query->bindParam(':fb_id', $fid);
  $insert_query->bindParam(':name',$name);
  $insert_query->bindParam(':likes',$listed_likes);
  try {
    $insert_query->execute();
  } catch (Exception $e) {
    echo $e;
    exit;
  }
}

$location = generateCSV($user,$results);

$classifier_query = $db->prepare('SELECT * FROM Faces WHERE fb_id=:fb_id');
$classifier_query->bindParam(':fb_id',$user);
try {
  $classifier_query->execute();
} catch (Exception $e) {
  echo $e;
  exit;
}

if ($classifier_query->rowCount() == 0) {
  $query_string = 'INSERT INTO Faces (fb_id, face) VALUES (:fb_id, :face)';
} else {
  $query_string = 'UPDATE Faces SET face=:face WHERE fb_id=:fb_id';
}
$classifier_complete = $db->prepare($query_string);
$classifier_complete->bindParam(':fb_id',$user);
$classifier_complete->bindParam(':face',$location);
try {
  $classifier_complete->execute();
} catch (Exception $e) {
  echo $e;
  exit;
}

return;
?>
