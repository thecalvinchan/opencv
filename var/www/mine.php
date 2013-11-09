<?php
// Processing function takes in associative array with id and pictures 

include 'facebook-php-sdk/src/facebook.php';

$file = 'content.txt';
$access_token = $_POST['access_token'];
//$access_token = 'CAAKvI4sEfSgBAHRNvbsCKxBBjksUv0cNmxNjIb5ZAmjWu4kf7bmXb85DJWFgcK3M4DXGWC8rcm0HQDZBo14PxkmWgk8EoxKR7fJx5ycoRZAMm1mXZAkfeNljp2nHquv5E2yy21aOnsG0dwgy3Jmjjk7KDIqJsqIlNtViDEYS0nFOXkcLKhbO';

$current = 'Access token: ';
$current .= $access_token;

$facebook = new Facebook(array(
  'appId'  => '154862557989368',
  'secret' => '2b0c092e9034284d6ef8ffc7e9d7e5fd',
));

$facebook->setAccessToken($access_token);

// Get User ID
$user = $facebook->getUser();
$current .= '\nUser ID: ';
$current .= $access_token;

$result = $facebook->api('/'.$user.'/friends?fields=name,gender',array('access_token' => $access_token)); 

$current .= '\nResult';
$current .= $result;

echo json_encode($result);

//echo $current;

//foreach($result['data'] as $key => $friend){
//  echo $key;
//  echo $friend;
//}

?>
