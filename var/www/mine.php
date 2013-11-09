<?php
// Processing function takes in associative array with id and pictures 

require 'facebook-php-sdk/src/facebook.php';

$access_token = $_POST['access_token'];
echo $access_token;

$facebook = new Facebook(array(
  'appId'  => '154862557989368',
  'secret' => '2b0c092e9034284d6ef8ffc7e9d7e5fd',
));

$facebook->setAccessToken($access_token);

// Get User ID
$user = $facebook->getUser();

$result = $facebook->api('/'.$user.'/friends?fields=name,gender',array('access_token' => $access_token)); 

foreach($result['data'] as $key => $friend){
  echo $key;
  echo $friend;
}

?>
