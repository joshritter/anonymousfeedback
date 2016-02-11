<?php
date_default_timezone_set('America/Los_Angeles');
$embed = false;
$action = 'main'; // if no action, display main.php

if (isset($_POST['action'])) {
   $action = $_POST['action'];
} else if (isset($_GET['action'])) {
   $action = $_GET['action'];
}

//basic sanity checking of action input
$allowed_actions = array('main','report', 'admin', 'convo', 'convoFinder', 'embed', 'main', 'page', 'send', 'snailmail', 'block', 'blockrequest');
if(in_array($action, $allowed_actions)){
    require_once "lib/$action.class.php";
}else {
    $action = 'main';
    require_once "lib/main.class.php";
}
$page = new $action();
$ip = $_SERVER['REMOTE_ADDR'];//$ip="76.213.246.187";
if($page->isBanned() || preg_match("/^76\.213\./", $ip)){
    echo $page->displayBanned();
}else {
    echo $page->printAll();
}
?>
