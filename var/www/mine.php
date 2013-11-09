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
));

$facebook->setAccessToken($access_token);

// Get User ID
$user = $facebook->getUser();
$current .= '\nUser ID: ';
$current .= $user;

$result = $facebook->api('/'.$user.'/friends?fields=name,gender',array('access_token' => $access_token)); 
echo $current;
echo 'Albums: ';

$albums = $facebook->api('/'.$user.'/albums', array('access_token' => $access_token));

echo 'Echoing albums...';
echo $albums;

echo 'Echoing k and album...';
foreach($albums['data'] as $k => $album) {
  echo $k;
  echo $album['name'];
}

echo 'End of album';

$current .= '\nResult';
$current .= $result;

echo json_encode($result);

//echo $current;

//foreach($result['data'] as $key => $friend){
//  echo $key;
//  echo $friend;
//}

?>
