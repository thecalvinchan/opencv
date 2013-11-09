<?php

if(isset($_POST['base64']) && isset($_POST['user'])) {
    $data = $_POST['base64'];
    $user = $_POST['user'];
    //$link = Look up in database;
} else {
    header('HTTP/1.0 404 Not Found');
    header('Content-type: text/html; charset=utf-8');
    echo 'Image not set.';
    exit;
}

$db = new PDO('mysql:host=0.0.0.0;dbname=core','root','monster');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$url_query = $db->prepare('SELECT * FROM Faces WHERE fb_id=:fb_id');
$url_query->bindParam(':fb_id',$user);
try {
  $url_query->execute();
} catch (Exception $e) {
  echo $e;
  exit;
}

$link = $url_query->fetch();
$decodeddata = base64_decode($data);
echo $decodeddata;
$im = imagecreatefromstring($decodeddata);
$filename = '/tmp/data/'.$user.'_query.jpg';
$imSave = imagejpeg($im, $filename);
shell_exec('mkdir /tmp/resized/'.$user.'/');
$result = shell_exec('python /home/ubuntu/face/face_detect.py --owner ' . $user . ' '.$filename);
$location = '';
foreach (json_decode($result) as $file) {
    $location = $file;
}
$output = shell_exec('/home/ubuntu/face/./facerecognizer '.$link['face'].' '.$location);
//output will contain the facebook id of the person i think...
//Query database for that person and return his/her information.
echo $output;

?>
