<?
require_once 'base.class.php';

class convo extends base {

    var $id;
    var $is_embed;
    function convo (){
        parent::base();
        $this->id = isset($_GET["convoID"]) ? $_GET["convoID"] : (isset($_POST['convoID']) ? $_POST['convoID'] : '');
        global $embed;
        $this->embed = $embed;
    }

    function generateBody() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->body .= $this->sendEmail();
        }//end POST 

        if(!$this->id){
            return $this->body;
        }
        ///// Displays convo
        $recent = "BDC9DE";
        $this->dbh->sqlQuery("SELECT * FROM conversations WHERE convoID = ? ORDER BY id DESC", array($this->id));
        while ($array = $this->dbh->fetchRow()) {
            $id  = $array['id'];
            $toEmail = $array['toEmail'];
            $toName = $array['toName'];
            $fromEmail = decrypt($array['fromEmail'], $this->encrypt_key);
            $convo = $array['convo'];
            $convoID = $array['convoID'];
            $time = $array['time'];
            $type = $array['type'];

            if($type == "r"){ 
                $to1 = "Anonymous"; 
                $from1 = "$toEmail";
            } else if($type == "s"){ 
                $to1 = "$toEmail"; 
                $from1 = "Anonymous";
            }else {
                $to1 = "Anonymous";
                $from1 = "Anonymous";
            }
            
            $timeStamp = date("F j, Y, g:i a",$time - 10800);
            $timeStamp = "$timeStamp PST";
            if ($recent == "BDC9DE") {
                $this->body .= "<b><font face=arial size=-1 color=#024364><b>Most Recent Message:</b></font>";
            }
            else { 
                $this->body .= "<p>"; 
            }

            $this->body .= "<table cellspacing=0 border=1 width=500 bgcolor=\"#$recent\"><tr><td><table cellspacing=0>";

            //XXX this shouldn't be doing anything anymore, remove?
            $convo = str_replace("\\r","",$convo);
            $convo = str_replace("\\n","",$convo);
            $convo = str_replace("\\","",$convo);


            $this->body .= "<tr><td><font face=arial size=-2>SENT:</td><td><font face=arial size=-2> $time</td></tr>
                <tr><td><font face=arial size=-2>TO: </td><td><font face=arial size=-2>".$this->encode($to1)."</td></tr>
                <tr><td><font face=arial size=-2>FROM: </td><td><font face=arial size=-2>".$this->encode($from1)."</td></tr>
                <tr><td colspan=2><font face=arial size=-2>LETTER:<br>". str_replace("\n", "<br>",$this->encode($convo))."
                </td></tr></table>
                </td></tr></table>";

            if ($recent == "BDC9DE") {
                $tp = $this->encode($_GET['tp']);
                $person = ($type == 'r' ? 's' : 'r');
                $form_action = $this->embed ? '/embed_convo.php' : "/index.php?action=convo&tp=$person";
                $this->body .= "<p> <form name=\"contact\" method=\"post\" action=\"$form_action\">
                    <font size=-1 face=arial>
                    <p><b>Send a reply:</b> <br>
                    <textarea name=convo rows=5 cols=59></textarea>
                    <input type=hidden name=toEmail value=\"".$this->encode($toEmail)."\">
                    <input type=hidden name=toName value=\"".$this->encode($toName)."\">
                    <input type=hidden name=fromEmail value=\"".$this->encode($fromEmail)."\">
                    <input type=hidden name=convoID value=\"".$convoID."\">
                    <input type=hidden name=type value=\"".$person."\">
                    <br><font size=-2>When you submit the receiver will get an email notifying them of the reply.<br> <br>
                    <input type=submit value=\"Send Reply >>>\">
                    <p>

                    <p><hr>
                    <br><b>Previous Messages <font size=-2>(most recent on top)</font></b><br>";
                $recent = "EEEEEE"; 
            }

        } // end of while loop

        $this->body .= "<p><hr><p>";
        if($this->embed){
            $this->body .= "<a target='_blank' href='http://www.anonymousfeedback.net/send-anonymous-email.html'>Send More Anonymous Email</a>";
        }

        return $this->body;
    }

    function sendEmail(){
        $toEmail = $_POST["toEmail"];
        $toName = $_POST["toName"];
        $fromEmail = $_POST["fromEmail"];
        $convoID = $_POST["convoID"];
        $convo = $_POST["convo"];
        $type = $_POST["type"];

        $this->body = '';
        if ($this->isBlockedEmail($toEmail)){
            $this->body .= "sorry you cannot send this message. it has been tagged as spam or the person has asked their email be blocked.";
            return $this->body;
        }

        if($type == "r"){ 
            $to = "<$fromEmail>"; 
            $person = "s";
        }

        if($type == "s"){ 
            $to = "<$toEmail>"; 
            $person = "r";
        }

        $emailConvo = str_replace("\\","",$convo);

        $from = "From: Anonymous Reply <noreply@anonymousfeedback.net>";
        $subject = "Re: New Anonymous Reply";
        $emailtext= "
            $emailConvo 

            Please reply or see your full conversation at:
            http://www.anonymousfeedback.net/index.php?action=convo&tp=$person&convoID=$convoID

            ..........................................
            Message was sent from http://www.anonymousfeedback.net. Please do not hit reply to this email address, since it is not monitored.
            Sent: ".$this->getDate();
        mail($to, $subject, $emailtext, $from);


        $to4 = " <josh.ritter@gmail.com>";
        $from4 = "From: Anon <noreply@anonymousfeedback.net>";
        $subject4 = "ADMIN (anon) NEW REPLY";
        $emailtext4 = "
            Someone replied to an anon convo. See it here:

            To: $toName, $toEmail
            From: $fromName, $fromEmail
            Message:
            $convo

            See the entire convo @:
            http://www.anonymousfeedback.net/index.php?action=convo&tp=$person&convoID=$convoID
            ..........................................
            Sent: ".$this->getDate();
        mail($to4, $subject4, $emailtext4, $from4);


        $this->dbh->sqlQuery("INSERT INTO conversations VALUES ('', ?, ?, ?, ?, ?, ?, ?, ?, 'REPLY')", 
            array($toEmail, $toName, encrypt($fromEmail, $this->encrypt_key), $convo, $convoID, $this->getDate(), $type, '0.0.0.0'));


        $this->body .= "<p><font size=-1 face=arial><font color=green>SUCCESS!</font> Your reply was emailed. Now they will be able to reply to you in the same fashion.<p><p>Do want to send anonymous feedback to anyone? <a href=\"index.php\">Send</a> some now!</font><p>";
    }
}
?>
