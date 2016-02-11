<?

$embed = true;

require_once "lib/convo.class.php";

$send = new convo();
if($send->isBanned()){
    $send->displayBanned();
}else {
    $body = $send->getBody();
    $title = $send->getTitle();
    require_once "lib/inc/template.embed.php";
}

?>
