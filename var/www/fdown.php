<?php
// Processing function takes in associative array with id and pictures 

require '/home/ub12/Documents/facebook-php-sdk-master/src/facebook.php';

//$access_token = $_POST['access_token'];
$access_token = 'CAAKvI4sEfSgBAMKPn7fixPPv2ZAcchDUfV82ssN0PueZBLQN9eJI0eNTZC4DadG7xzLvuzJWZAtIbW20I1lN1cklLhrUB86F4s4caFZB8gN9GIZA2CGQTDB5xirQQjELnV87Qpdsd3OBNRqupQymD40tVTycohv8PU3B6V4kh1TKLT5kcwUF9Y';


echo 'Access token: ' . $access_token . '<br />';

/*
$facebook = new Facebook(array(
  'appId'  => '154862557989368',
  'secret' => '2b0c092e9034284d6ef8ffc7e9d7e5fd',
));
*/

$facebook = new Facebook(array(
  'appId'  => '755517144464680',
  'secret' => '60f3a2763be6b3108359ccabcda159ea',
));

$facebook->setAccessToken($access_token);
//$facebook->setAppSecret("60f3a2763be6b3108359ccabcda159ea");
//$facebook->setAppId("755517144464680");

// Get User ID
$user = $facebook->getUser();
echo 'UZserrrr<br/>';
echo 'User ID: ' . $user . '<br/>';

// iterate through all of friends list:
$result = $facebook->api('/'.$user.'/friends?fields=name,gender',array('access_token' => $access_token));
foreach($result['data'] as $key => $friend){
             //access any data item you want, such as ['id']...  
			echo $friend['name'] . '<br/>';
            }


$albums = $facebook->api('/'.$user.'/albums', array('access_token' => $access_token));

$theid = -1;
foreach($albums['data'] as $key => $album) {
	echo $album['name'];
}

//$user2 = $facebook->api('/me?fields=id');
//echo $user2;
/*
echo 'actual req:<br/>';
echo '/me?fields=id';
echo '<br/>';


$result = $facebook->api('/'.$user.'/friends?fields=name,gender',array('access_token' => $access_token)); 

echo 'resulttt<br/>';
echo $result;


foreach($result['data'] as $key => $friend){
  echo $key;
  echo $friend;
}
*/
?>
