<?php
require_once('./config.php');
$pdo = new PDO('mysql:host='.DBHOSTNAME.';dbname='.DBNAME, DBUSER, DBPASSWORD);


if(isset($_POST['url'])){
  add_url($_POST['url']);
}elseif($_GET['short']){
  handle_url($_GET['short']);
}


function add_url($url){
  global $pdo;
  header('Content-Type: application/json');
  $insert_request = $pdo->prepare('INSERT INTO `url`(`shorturl`, `longurl`) VALUES (?,?)');
  $short_tag = substr(sha1($url), 0, 10);
  $insert_request->execute(array($short_tag , $url));
  echo json_encode(array( 'shorturl' => $short_tag, 'longurl' => $url), JSON_PRETTY_PRINT);
}

function handle_url($short){
  global $pdo;
  $request = $pdo->prepare('SELECT * FROM `url` WHERE `shorturl` = ?');
  $request->execute(array($short));
  $urldata = $request->fetch();
  header('Location: '. $urldata['longurl']);
}
