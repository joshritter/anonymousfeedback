<?php
require_once 'base.class.php';
require_once('recaptchalib.php');
require_once('securimage/securimage.php');

class send extends base {

    var $securimage;// = new Securimage();
    var $to_name;
    var $to_email;
    var $use_from;
    var $embed;
    function send (){
        parent::base();
        global $embed;
        $this->embed = $embed;
        $this->to_name = isset($_GET['to_name']) ? $_GET['to_name'] : '';
        $this->to_email = isset($_GET['to_email']) ? $_GET['to_email'] : '';
        if(isset($_GET['email_prefix']) && isset($_GET['email_suffix'])){
            $this->to_email = $_GET['email_prefix'] ."@".$_GET['email_suffix'];
        }
        $this->use_from = isset($_GET['use_from']) ? $_GET['use_from'] : '';
        if($this->embed){
            $this->title = "- Email will be sent to: ".$this->encode($this->to_name);
        }

    }

    function generateBody(){

        if ($_SERVER['REQUEST_METHOD'] == 'POST' && !$_GET['from_fb']) {
            return $this->processPost();
        }
        $this->body .= '</font></font></font><form name="contact" method="post" action="';
        if($this->embed) {
            $this->body .= "send-anonymous-email/embed/";
        }else {
            $this->body .= "send-anonymous-email.html";
        }
        $this->body.= '">';
        if(!$this->embed){
            $this->body .= '<font size="-1" face="arial"><h1>Send Anonymous Email To Anyone</h1>
Do you want to send an anonymous email message but don\'t want the person to know who it is from? 
AnonymousFeedback.net lets that happen!!<p>Send anonymous emails to your friends, family, neighbors, co-workers, etc.  Think of it as an online suggestion box 
where you can suggest, criticize, or just let someone know your true feelings. Send anonymous email today!!  Just fill out the form below.<p>';

        }

        $this->body .= '  <center>      
            <table border=1 bordercolor=#25324C cellspacing=0 bgcolor=#8BA5C4 width=420>
            <tr bgcolor=#BDC9DE><th><b><center><font color=#25324C face=arial size=4>Send Anonymous Email</b></font></center>
            </th></tr><tr><td><table width=100% cellspacing="0" border="0" cellpadding="5">';
        if($this->embed){
            $referer = (isset($_SERVER['HTTP_REFERER']))? $_SERVER['HTTP_REFERER']:"No Referer" ;

            $this->body .= '<tr><td><font size=-1 face=arial>Your email is going to: </td> <td>';
            if($this->to_name){
                $this->body .= $this->encode($this->to_name)." <input type='hidden' value='".$this->encode($this->to_name)."' name='toName'>";
            }else {
                $this->body .= "<input type=text name=toName size=30>";
            }
            $this->body .="<input type='hidden' name='referer' value='$referer'></td>";
            if($this->use_from){
                $this->body .= ' <tr>    <td colspan=2 style="font-size:.8em;font-weight:bolder;">A reply address has been requested.  This address is optional and will never be given out.  Providing an email address will allow an anonymous conversation to take place.  The link to this conversation will be provided once your email has been sent.</td></tr><tr>        <td>From Email:</td><td><input type="text" name="fromEmail"></td>    </tr>';
            }
            $this->body .= '</tr>';

            if($this->to_email){
                if(isset($_GET['email_prefix']) && isset($_GET['email_suffix'])){
                    $this->body .= "<input type='hidden' value='".$this->encode($_GET['email_prefix'])."' name='email_prefix'>";
                    $this->body .= "<input type='hidden' value='".$this->encode($_GET['email_suffix'])."' name='email_suffix'>";
                } else {
                    $this->body .= "<input type='hidden' value='".$this->encode($this->to_email)."' name='toEmail'>";
                }
            }else {
                $this->body .= "<tr>            <td>            <font size=-1 face=arial>To Email:            </td>            <td>
                    <input type=text name=toEmail size=30>            </td>            </tr>            ";
            }
        } else {
            $this->body .= '<tr> <td><font size=-1 face=arial>To Name: </td>   <td>        <input type=text name=toName size=30>    </td></tr><tr>
                <td>        <font size=-1 face=arial>To Email:    </td>    <td>        <input type=text name=toEmail size=30>    </td></tr>';
        }
        $this->body .= '<tr><td><font size=-1 face=arial>Message:</td><td><textarea name="message" cols=30 rows=3></textarea></font><br><font size=-2>no html, using it will tag your message as spam.</font></td></tr>';
        if(!$this->embed){
            $this->body .= '
            <tr><td colspan=2 align=center >
            <p><table border=1 cellspacing=0 bgcolor=#EEEEEE bordercolor=#000000 width=75%><tr><td><p><font size=1>Your Email: <i>(optional)</i><input type=text name=fromEmail size=35 title="Enable a conversation by entering your email address.  AnonymousFeedback.net allows you to have an anonymous conversation with a person. This is *NOT* required to send anonymous email. If you want to have an anonymous conversation, please enter your email securly in the box below. If you only want to send a one-way anonymous email message please leave the box below empty and send your email."> 
            <br>We will <b>NEVER</b> give out your email and it will always stay anonymous.
            <a href="faq.html#4" target=_blank>more info </a><br><br>
            </td></tr></table><p>
            </td></tr>';
           
            $this->body .= '<tr><td colspan=2><center><img id="captcha" src="securimage/securimage_show.php" alt="CAPTCHA Image" />
                <div style="margin-top:5px;"><input type="text" name="captcha_code" size="10" maxlength="6" />
                <a href="#" onclick="document.getElementById(\'captcha\').src = \'securimage/securimage_show.php?\' + Math.random(); return false">[ Different Image ]</a></div></center>
                </td></tr>';
        }
        $this->body .= '<tr><td colspan=2>
        <font size=-1>
        <p><b>You MUST agree to these terms before continuing:</b><br>
        Emails cannot be of a hateful nature. No email shall insult a person\'s sex, age, race, religion, disability, sexual orientation, etc.
        No email can contain <a href=\"http://en.wikipedia.org/wiki/Spam_(electronic)\">SPAM</a>.
        You agree to our <a href="privacy.html" target=_blank>Privacy Policy</a>.
        <input type=hidden value=yes name=terms>
        <br>Verify you are a human. By doing so,<b> you are agreeing to our Terms of Service and Privacy Policy</b>
        <p>';//<center>'.recaptcha_get_html('6LfAigcAAAAAAHUN9o23RFTibRAf56VdaTGzoavz').'<tr><td colspan=2><center><P> ';
        if($this->embed){
            $this->body .= '<input type=submit name=speed value="Send Now"> ';
        } else {
            $this->body .= '<input type=submit name=speed value="Quick Send Now"> OR <input type=submit name=speed value="See More Options"></center>';
        }

        $this->body .= '<p></td></tr></table></td></tr></table>';

        if($this->embed){
            $this->body .= "<a target='_blank' href='http://www.anonymousfeedback.net/send-anonymous-email.html'>Send More Anonymous Email</a>";
        }
        $this->body .= '</center>';
        return $this->body;
    }

    function processPost() {
        $this->body = '';
        $securimage = new Securimage();
        $speed = $_POST["speed"];

        $footer = "This message was sent via AnonymousFeedback.net by an anonymous user. Do not hit reply to this email. REPORT: http://www.anonymousfeedback.net/index.php?action=report Sent: ".$this->getDate();
        if($speed == "See More Options" || $speed == "Send Now"|| $speed == "Quick Send Now"){

            //RE CAPTCHA
            $privatekey = "6LfAigcAAAAAAAqhFx0qP8NcV_ars7rRbzinIMAs";
            $resp = recaptcha_check_answer ($privatekey,
                $_SERVER["REMOTE_ADDR"],
                $_POST["recaptcha_challenge_field"],
                $_POST["recaptcha_response_field"]);
            if ($securimage->check($_POST['captcha_code']) == false) {
                die ("You did not fill out the reCAPTCH correctly. It is ok, sometimes it can be difficult to see. Feel free to hit back and try it again.");
            }
            //RE CAPTCHA
        }

        $ip = $_SERVER['REMOTE_ADDR'];
        $message = stripslashes($_POST['message']);
        if(isset($_POST['email_prefix']) && isset($_POST['email_suffix'])){
            $toEmail = $_POST['email_prefix'] ."@".$_POST['email_suffix'];
        }else {
            $toEmail = $_POST["toEmail"];
        }
        $toName = $_POST["toName"];
        $fromEmail = $_POST["fromEmail"];
        $message = $_POST["message"];
        $stage = $_POST["stage"];
        $terms = $_POST["terms"];
        $respond = $_POST["respond"];
        $subject = $_POST["subject"];
        $subject2 = $_POST["subject2"];
        $speed = $_POST["speed"];

        if ($terms != "yes") {
            $this->body .= "<p><font size=5 color=red><b>ERROR!</font></b> You must accept and abide by our terms before the message will send. <p>Please hit back and read and click on the box next to \"I accept these terms.\"";
            return $this->body;
        }

        //Checks for bad words
        global $obscenities;
        $obs_string = implode('|',$obscenities);
        if(preg_match("/$obs_string/i", trim($message))){
            $abuse = "BAD WORD";
        }

        //list of emails that anon will NOT allow to be sent to.
        if ($this->isBlockedEmail($toEmail)){
            $abuse = "BAD EMAIL";
        }
        //checks against banned domains
        $domain = strstr($toEmail, '@');
        if ($domain == "@mail.ru"
            || $domain == "@web.de"
            || $domain == "@cashette.com"
            || $domain == "@gawab.com"
            || $domain == "@wwwfreemail.info"
            || $domain == "@tut.by"
            || $domain == "@tramadol.quickfreehost.com"
            || $domain == "@instant-profits.biz"
            || $domain == "@zinsco.cn"
            || $domain == "@mymail-in.net"
            || $ip == "112.198.207.19"
            || preg_match("/183\.10/", $ip) ){
                $abuse = "DOMAIN";
            }

        //If either one comes us as abuse then do not send
        if ($abuse != "") {
            $this->body .= "<b><font color=red size=4>NOTICE!</font> Your message has <b>NOT BEEN SENT</b>. It has been tagged as SPAM or as having inappropriate content that breaks our Terms of Service. Please note that most HTML or using \"http://\" will tag your message as spam. If you remove all HTML and http:// your message should be sent.</b><p>If this is a mistake, please <a href=\"index.php?action=page&page=contact\">go to the contact page</a> and send us a copy of what is in the box below. Or you can hit back and change your message.<p><table border=1><tr><td>To: ".$this->encode($toEmail)."<br> IP: $ip <br> Message: ".$this->encode($message)."</td></tr></table><p><p>We are sorry for this inconvenience, but too many people have been abusing this very helpful service.<input type=hidden name=toEmail value=\"".$this->encode($toEmail)."\"><input type=hidden name=toName value=\"".$this->encode($toName)."\"><input type=hidden name=fromEmail value=\"".$this->encode($fromEmail)."\"><input type=hidden name=\"message\" value=\"".$this->encode($message)."\"><input type=hidden name=subject value=\"".$this->encode($subject)."\"><input type=hidden name=speed value=\"".$this->encode($speed)."\"><input type=hidden name=respond value=\"".$this->encode($respond)."\"><input type=hidden name=subject2 value=\"".$this->encode($subject2)."\"><input type=hidden name=terms value=\"".$this->encode($terms)."\"><input type=hidden name=stage value=\"".$this->encode($stage)."\">";
            return $this->body;
        }

        // ============================================ *END* SPAM / INAPPROPRIATE CHECKS =================================== //

        if ($toEmail==""){
            $this->body .= "<p><font size=5 color=red><b>ERROR!</font></b>You need to fill out the email address of the person you want the message sent to.<p>
                Please hit your browser's BACK button and please fill it out.";
            return $this->body ;
        }

        if ($message==""){
            $this->body .= "<p><font size=5 color=red><b>ERROR!</font></b>You need to fill out a message.<p>Please hit your browser's BACK button and please fill it out.";
            return $this->body;
        }

        if ($fromEmail == "") {
            $fromEmail = "none";
        }

        if ($speed == "Quick Send Now" || $speed == 'Send Now') {//if want to quick send
            if(!$this->embed) {
                $this->body .= "<font face=arial size=-1>Quick Send Worked! Make sure you next time you check out the other options where you can change the subject, who it is from, and even get a reply!<p></font>";
            }
            $subject = "Anonymous Feedback for You";
            $stage = "send";
            $from = "AnonymousFeedback.net - no reply";
            $respond = "no";
            $terms = "yes";
            $quick = "QUICKsend";
        } else {
            $quick = "Standard";
        }

        if ($subject == "custom") {
            $subject = $subject2;
        }

        if($stage == "send") {  ///////////////////STAGE == SEND

            srand ((double) microtime( )*1000000);
            $random_number = rand(10,99999);
            $convoID = "$random_number-".$this->count;

            if ($fromEmail == "none") {
                $reply = "From AnonymousFeedback.net: This is a one way, anonymous message. You are not able to respond to the sender and we are  not able to tell you who sent you this message.";
            } else {
                $reply = "From AnonymousFeedback.net: The person who sent you this message has requested a reply from you. Please go to the
                    following web address and respond: http://www.anonymousfeedback.net/index.php?action=convo&tp=r&convoID=$convoID";


                // SENDS EMAIL TO THE SENDER
                $to3 = "<$fromEmail>";
                $from3 = "From: AnonymousFeedback.net<admin@anonymousfeedback.net>";
                $subject3 = "Your Message Was Sent";
                $emailtext3= "We just wanted to let you know we have sent your message to $toName at $toEmail. Do not worry, we are keeping all your information VERY safe!

In their email they are told that they can reply to you on our website. If they choose to reply you will get another email letting you know. If they reply (not everyone does) you will be given a link where you can have a 100% anonymous conversation with them. The only way they will know who you are is if you tell them, we never will.

Thanks for using our service and if you have any feedback for us, please reply to this email.

Sincerely,
AnonymousFeedback.net Team
admin@anonymousfeedback.net
http://www.anonymousfeedback.net
";
                mail($to3, $subject3, $emailtext3, $from3);
            }

            $emailMessage = str_replace("\\","",$message);
            $email_id = encrypt($toEmail, $this->encrypt_key);


            $footer = "This message was sent via AnonymousFeedback.net by an anonymous user. Do not hit reply to this email. 
Don't want these emails anymore? Block your email address: http://www.anonymousfeedback.net/block-email-confirmation.html?id=$email_id
REPORT: http://www.anonymousfeedback.net/index.php?action=report Sent: ".$this->getDate();
            $to = "<$toEmail>";
            $from = "From: $from<noreply@anonymousfeedback.net>";
            $subject = "$subject";
            $emailtext= "$emailMessage



$reply

................................................
$footer. Sent to: $toName at $toEmail.";
            mail($to, $subject, $emailtext, $from);


            $to2 = "<admin@anonymousfeedback.net>";
            $from2 = "From: Anon Admin<noreply@anonymousfeedback.net>";
            $subject2 = $this->embed ? "ADMIN (anon) EMBED CONVO" : "ADMIN (anon) NEW CONVO $quick";
            $referer = $_POST['referer'];
            $emailtext2= "Someone started a convo
                To: $toName
                To Email: $toEmail
                From: $fromEmail
                Subj: $subject
                REF: $referer
                IP: $ip
                Msg:
                $emailMessage

                Link: http://www.anonymousfeedback.net/index.php?action=convo&convoID=$convoID
                ";
            mail($to2, $subject2, $emailtext2, $from2);

            $this->dbh->sqlQuery("INSERT INTO conversations VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)",
                array('',$toEmail, $toName, encrypt($fromEmail, $this->encrypt_key) , $message , $convoID , $this->getDate(), 's', '0.0.0.0' , $quick));

            $this->body .= "<font face=arial size=-1><font color=green>SUCCESS!</font> Your anonymous message was sent to ".$this->encode($toEmail).".";
            if($respond == "yes") {
                $this->body .= "If the person responds, you will receive an email letting you know and telling you how to reply.";
            }
            if($this->embed) {
                $this->body .= "<p>This message was sent using <a target='_blank' href=\"index.php\">Anonymousfeedback.net</a>?</font>";
                global $hide_ads_array;
                $obs_string = implode('|',$hide_ads_array);

                if(!preg_match("/$obs_string/i",$toEmail)){
                //if($toEmail != "tclearwater@assiniboinepark.ca") {
                    $this->body .= '<br><script type="text/javascript">google_ad_client = "pub-8920375401504772";/* anonymous_image_only */google_ad_slot = "6463507896";google_ad_width = 468;google_ad_height = 60;</script><script type="text/javascript"  src="http://pagead2.googlesyndication.com/pagead/show_ads.js"></script>';
                }
            }else {
                $this->body .= "<p>How about <a href=\"index.php\">another</a>? Be sure to tell your friends about this nifty service...</font>";
            }

            return $this->body;
        } //end if send email stage

        else{ //Preview Stage

            $previewMessage = str_replace("\n", "<br>",$this->encode($message));
            $footer = "This message was sent via AnonymousFeedback.net by an anonymous user. Do not hit reply to this email. 
REPORT: http://www.anonymousfeedback.net/index.php?action=report Sent: ".$this->getDate();


            $display_from_email = ($fromEmail != 'none') ? $fromEmail : '';
            $this->body .= "<form name=\"contact\" method=\"post\" action=\"index.php?action=send\"><b><font size=-1>PREVIEW AND SEND</font></b><br><font size=-1>Below is what you message will look like. If you need to make changes, hit back on your browser.<p><b>To: ".$this->encode($toName) .' - '.$this->encode($toEmail)."</b><table border=1 cellspacing=0 bgcolor=#BDC9DE width=75% bordercolor=#25324C><tr><td><font face=arial size=-1 color=black> $previewMessage<br><font size=-2>..........................................................<br>$footer. Sent to: ".$this->encode($toEmail) .$this->encode($toName).".</font></td></tr></table><br><font size=-1><b>SUBJECT LINE</b><br>Please choose a following subject or make one up of your own
                <br><input type=radio name=subject value=\"Anonymous Feedback for You\" checked><i>Anonymous Feedback for You</i>
                <br><input type=radio name=subject value=\"You Have Anonymous Feedback\"><i>You Have Anonymous Feedback</i>
                <br><input type=radio name=subject value=\"custom\">Or Create Your Own <input type=text name=subject2 size=25>

                <p><font size=-1><b>FROM WHOM?</b><br>This will show in the \"From\" section of the email.
                <br><input type=radio name=from value=\"AnonymousFeedback.net - no reply\" checked><i>AnonymousFeedback.net - no reply</i>
                <br><input type=radio name=from value=\"Anonymous Person\"><i>Anonymous Person</i>
                <br><input type=radio name=from value=\"Secret Admirer\"><i>Secret Admirer</i>

                <p><table border=1 cellspacing=0 bgcolor=#EEEEEE bordercolor=#000000 width=75%><tr><td><p><font size=-1><b>CONVERSATION OPTION</b><i><font size=-2>(optional)</font></i><br>AnonymousFeedback.net allows you to have an anonymous conversation with a person. This is <b>NOT</b> required to use our service. If you want to have an anonymous conversation, please enter your email securly in the box below. If you only want to send a one-way anonymous message please leave the box below empty and send your message. <a href=\"index.php?action=page&page=faq#4\" target=_blank>more info ></a><br><br>Your Email: <input type=text name=fromEmail value=\"".$this->encode($fromEmail)."\"size=35>
                <br>We will <b>NEVER</b> give out your email and it will always stay anonymous.
                </td></tr></table><p>
                <input type=hidden name=terms value=\"$terms\">
                <input type=hidden name=toEmail value=\"".$this->encode($toEmail)."\">
                <input type=hidden name=toName value=\"".$this->encode($toName)."\">
                <input type=hidden name=\"message\" value=\"".$this->encode($message)."\">
                <input type=hidden name=stage value=\"send\">
                <input type=submit value=\"Send Your Feedback\">";

            return $this->body;
        }

    }

}

?>
