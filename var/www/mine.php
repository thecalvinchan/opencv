<?php
// Processing function takes in associative array with id and pictures 

include 'facebook-php-sdk/src/facebook.php';

$file = 'content.txt';
$access_token = $_POST['access_token'];

$current = 'Access token: ';
$current .= $access_token;

$facebook = new Facebook(array(
  'appId'  => '755517144464680',
  'secret' => '60f3a2763be6b3108359ccabcda159ea',
#  'appId'  => '620801497991130',
#  'secret' => '42a1b5e473f05340246cdaa42c7cbba5',
));

$facebook->setAccessToken($access_token);

// Get User ID
$user = $facebook->getUser();
$current .= '\nUser ID: ';
$current .= $user;
echo $current;

// Calvin UID: 1372209588
// Shenil UID: 100001552561170
// Earl UID: 589405438
$friends_list = $facebook->api('/'.$user.'/friends?fields=name,gender',array('access_token' => $access_token)); 
$results;

echo 'Albums: ';
foreach($friends_list['data'] as $friend) {
  $fid = $friend['id'];
  echo $friend['name'] . "'s albums: ";
  $albums = $facebook->api('/'.$fid.'/albums', array('access_token' => $access_token));
  foreach($albums['data'] as $album) {
    if ($album['name'] == 'Profile Pictures') {
      $prof_pics = $facebook->api('/'.$album['id'].'/photos', array('access_token' => $access_token));
      $pics_arr;
      foreach($prof_pics['data'] as $prof_pic) {
        $pics_arr[] = $prof_pic['source'];
      }
      var_dump($pics_arr);
      break;
    }
  }
  $results[$fid] = $pics_arr;
}

var_dump($results);

echo json_encode($friends_list);

//foreach($result['data'] as $friend) {
//  echo 'Friend';
//  var_dump($friend);
 // $friendID = $friend['id'];
 // break;
 // $asdf = 589405438;
 // $albums = $facebook->api('/'.$friendID.'/albums', array('access_token' => $access_token));
 // var_dump($albums);
 // break;
 // foreach($albums['data'] as $album) {
 //   if ($album['name'] == 'Profile Pictures') {
 //     $prof_pics = $facebook->api('/'.$album['id'].'/photos', array('access_token' => $access_token));
 //     echo $prof_pics;
 //   }
 // }
//}
?>
