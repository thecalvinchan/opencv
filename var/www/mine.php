<?php
// Processing function takes in associative array with id and pictures 

require 'facebook-php-sdk/src/facebook.php';

//$access_token = $_POST['access_token'];
$access_token = 'CAAKvI4sEfSgBAMKPn7fixPPv2ZAcchDUfV82ssN0PueZBLQN9eJI0eNTZC4DadG7xzLvuzJWZAtIbW20I1lN1cklLhrUB86F4s4caFZB8gN9GIZA2CGQTDB5xirQQjELnV87Qpdsd3OBNRqupQymD40tVTycohv8PU3B6V4kh1TKLT5kcwUF9Y';

echo 'Access token: ' . $access_token;

$facebook = new Facebook(array(
  'appId'  => '154862557989368',
  'secret' => '2b0c092e9034284d6ef8ffc7e9d7e5fd',
));

$facebook->setAccessToken($access_token);

// Get User ID
$user = $facebook->getUser();
echo 'User ID: ' . $user;

$result = $facebook->api('/'.$user.'/friends?fields=name,gender',array('access_token' => $access_token)); 

foreach($result['data'] as $key => $friend){
  echo $key;
  echo $friend;
}

?>
