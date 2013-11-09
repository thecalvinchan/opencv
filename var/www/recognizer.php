<?php

define(EXTENSION,'.jpg');

if(isset($_POST['base64']) && isset($_POST['user'])) {
    $data = $_POST['base64'];
    $user = $_POST['user'];
    //$link = Look up in database;
} else {
    header('HTTP/1.0 404 Not Found');
    header('Content-type: text/html; charset=utf-8');
    exit;
}

$data = base64_decode($data);
$im = imagecreatefromstring($data);
$filename = '/tmp/data/'.$user.'_query.jpg';
$imSave = imagejpeg($im, $filename);
$output = shell_exec('home/ubuntu/face/./facerecognizer '.$link.' '.$filename);
//output will contain the facebook id of the person i think...
//Query database for that person and return his/her information.
echo $output;

?>

