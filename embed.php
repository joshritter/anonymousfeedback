<?

$embed = true;

require_once "lib/send.class.php";

$send = new send();
if($send->isBanned()){
    $send->displayBanned();
}else {
    $body = $send->getBody();
    $title = $send->getTitle();
    require_once "lib/inc/template.embed.php";
}

?>
