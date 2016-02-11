<?
require_once 'base.class.php';

class snailmail extends base {

    var $paypal_token;
    function snailmail (){
        parent::base();
        $this->title = "How to send anonymous letters in the mail - ";
        $this->description = "Send an anonymous letter in the mail.  Need to send an anonymous love letter?";
        $this->paypal_token = 'quqkE9ERvtQ0BZCDjlXMFUKOFRaYB_oQZPkCU_8SxkisjEADdZsCifSoeqC';
    }

    function generateBody() {
/*        $this->body =  '<b>Snail Mail</b>
            This option is under development. We will let you send a completely anonymous message through USPS. Check back for updates.
            <p><a href="index.php?action=page&page=contact">Contact us</a> and let us know that you are interested in using this feature.';
 */

        if($_GET['id']){
            $id = decrypt($_GET['id'], $this->encrypt_key);
            $has_error = false;
            if(preg_match("/^anon_salt-[0-9]+$/", $id)){
                $id = str_replace('anon_salt-','', $id);
                $this->dbh->sqlQuery("SELECT * from snailmail WHERE id = ?", array($id));
                $data = $this->dbh->fetchRow();
                if($data){
                    $this->body .= $this->displaySnailMail($data);
                }else {
                    $has_error = true;
                }
            }else {
                $has_error = true;
            }

            if($has_error){
                $this->body .= "Invalid or incorrect url.  If you feel you are sure the url is correct you may try contacting us <a href='contact.html'>here</a>.";
            }
        }else {
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $this->processForm();
            }else {
                $this->displayForm();
            }
        }
    }

    function displaySnailMail($data) {
        $this->body = '';
        $status = $data['status'];
        $this->body .= "<h4>Snailmail request</h4>";
        //are they returning from paypal
        if($this->isFromPayPal()){
            $this->processPayPal($data['id']);
        }
        if($status == 'verify'){
            $this->dbh->sqlQuery("UPDATE snailmail set status = 'verified' WHERE id = ?", array($data['id']));
            $this->body .= "Your message has now been verified.<p>  Click on the paypal pay now button to complete this transaction";
            $this->displayPayInfo();
        }else if($status == 'verified'){
            $this->body .= "Your message has been verified.  We are waiting to receive payment in order to continue with your order.  If you have already sent us payment it may
                take up to 24 hours before our system updates to reflect that.
                <p>  Click on the paypal pay now button to complete this transaction";
            $this->displayPayInfo();
        }else if($status == 'pending'){
            $this->body .= "Your payment has been received.  Please allow 48-72 hours for us to process your request.  You will receive an email to confirm that your message has been sent.";
        }else if($status == 'complete'){
            $this->body .= "Your order is complete.  If you have any questions/concerns/comments please contact us <a href='contact.html'>here</a>.";
        }
        $this->body .= "<h4>Message Details:</h4>
            To: ".$this->encode($data['to_name'])."<br>
            Address: ".$this->encode($data['to_address'])."<br>
            Message: ".$this->encode($data['message']);
    }

    function displayPayInfo(){
        $this->body .= '
            <form action="https://www.paypal.com/cgi-bin/webscr" method="post">
            <input type="hidden" name="cmd" value="_s-xclick">
            <input type="hidden" name="hosted_button_id" value="QY8KFCYJ9SCHN">
            <input type="image" src="https://www.paypal.com/en_US/i/btn/btn_buynowCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
            <img alt="" border="0" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1">
            </form>';
    }

    function isFromPayPal() {
        //XXX how to detect??
        return false;
    }

    function processPayPal($id) {
        $transaction_id = 'X';//XXX TODO fetch this
        $data = '<form method=post action="https://www.paypal.com/cgi-bin/webscr">
            <input type="hidden" name="cmd" value="_notify-synch">
            <input type="hidden" name="tx" value="'.$transaction_id.'">
            <input type="hidden" name="at" value="'.$this->paypal_token.'">
            <input type="submit" value="PDT">
            </form> ';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://www.paypal.com/cgi-bin/webscr");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: text/html","Content-length: ".strlen($data)));

        $cd = curl_exec($ch);
        curl_close($ch);
        $has_paid = false;
        //XXX check $cd succeeded
        /*$will_get_back = 'SUCCESS
            first_name=Jane+Doe
            last_name=Smith
            payment_status=Completed
            payer_email=janedoesmith%40hotmail.com
            payment_gross=3.99
            mc_currency=USD ';*/
        if($has_paid){
            $this->dbh->sqlQuery("UPDATE snailmail set status = 'complete' WHERE id = ?", array($id));

        }

    }

    function processForm() {
        $sender_name = $_POST['your_name'];
        $sender_email = $_POST['your_email'];
        $to_name = $_POST['their_name'];
        $to_address = $_POST['address'];
        $message = $_POST['message'];
        $this->body = '';
        if(!$sender_name || !$sender_email || !$to_name  || !$to_address || !$message || preg_match("/@/", $to_name)){
            if(preg_match("/@/", $to_name)){
                $this->error = "<font size='2' color = 'red'>**You have put an email in the To NAME column, do you want to send an email? <a href=\"send-anonymous-email.html\">Send email here.</a>  Otherwise, please
                    put in a persons name and not an email to continue.</font><p>";
            }else {
                $this->error = "<font size ='2' color = 'red'>**Please fill the entire form out before submitting your anonymous snailmail message</font><p>";
            }
            return $this->displayForm($_POST);
        }
        if($_POST['is_preview']){
            $this->body = "<h4>Preview your message</h4>Your message will be sent to:<hr> ".$this->encode($to_name)."<br/>".str_replace('\n','<br>',$this->encode($to_address));
            $this->body .= "<pre style='width:400px;bgcolor:grey;'>".$this->encode($message)."</pre>";

            $this->body .= "<form name=\"contact\" method=\"post\" action=\"index.php?action=snailmail\">";
            $this->body .= '<input name="your_name" type="hidden" value = "'.$this->encode($sender_name).'" /><br/>
                <input name="your_email" type="hidden" value = "'.$this->encode($sender_email).'" /><br/>
                <input type="hidden" name="their_name" value = "'.$this->encode($to_name).'" /><br/>
                <input type="hidden" name="address" value="'.$this->encode($to_address).'" />
                <input type="hidden" name="message" value="'.$this->encode($message).'" />
                <input type="hidden" value="0" name="is_preview"/>
                <input type=submit name="Submit" value="Submit"/>';
            $this->body .= "</form>";
            return $this->body;
        }else {
            $this->body .= "<font face=arial size=-1><font color=green>SUCCESS!</font> Your anonymous message has been received by us.  You should receive an
                email in the next few minutes.  Please follow the instructions in the email to have your message sent in the mail";
            $this->dbh->sqlQuery("INSERT INTO snailmail (sender_name, sender_email, to_name, to_address, message, status) VALUES (?, ?, ?, ?, ?, 'verify')",
                array($sender_name, $sender_email, $to_name, $to_address, $message));
            $id = $this->dbh->lastInsertID();
            $this->sendMail($id, $_POST);
            return $this->body;
        }
    }
    function sendMail($id, $args = array()) {
        $sender_name = $args['your_name'];
        $sender_email = $args['your_email'];
        $to_name = $args['their_name'];
        $to_address = $args['address'];
        $message = $args['message'];

        if(!$id){
            return;
        }
        $url = "http://anonymousfeedback.net/?action=snailmail&id=".encrypt("anon_salt-".$id,$this->encrypt_key); 
        $to = "<".$sender_email.">";
        
        $from = "From: AnonymousFeedback.net<admin@anonymousfeedback.net>";
        $subject = "Your Snailmail Message Was Received";
        $emailtext= "We have received your snailmail message.  Before we can send your message we need you to do two things.

            1) Verify you are a person.  To do this click on the link below.
            2) Submit your payment.  Once you have clicked on the link you will be brought to a page with a paypal button.  Please use it to complete this transaction.   

            $url

            Upon receipt of your payment we will send your mail.  You will receive a confirmation email when this is complete.  Please save this email as the above url is how you can track the status of your snailmail message.  

            Thanks for using our service and if you have any feedback for us, please reply to this email.

            Sincerely,
            AnonymousFeedback.net Team
            admin@anonymousfeedback.net
            http://www.anonymousfeedback.net 
            ";
        mail($to, $subject, $emailtext, $from);
        mail('<admin@anonymousfeedback.net>', "ANON - SNAILMAIL - $to", $emailtext, $from);
    }

    function displayForm($args = array()) {
        $this->body = "<form name=\"contact\" method=\"post\" action=\"index.php?action=snailmail\">";

        
        $this->body .= '<font size="-1" face="arial">
            <h1>Send Anonymous Letters (SnailMail, ie not email)</h1>
            <font size=2>Want to send an anonymous letter in the mail?  This is the place to make that happen.  This service isn\'t free, the cost of sending an anonymous snailmail is $5.00 (USD).  Please fill out the form below to get started.  <p>If you are looking for free email, you can <a href="send-anonymous-email.html">send anonymous emails</a> for free.</font><p>
            ';
        
        if($this->error){
            $this->body .= $this->error;
        }
        $this->body .= '<h4>Your Information *</h4>
            Name: <input name="your_name" type="text" value = "'.$this->encode($args['your_name']).'" /><br/>
            Email: <input name="your_email" type="text" value = "'.$this->encode($args['your_email']).'" /><br/>
            <font size=1>* Your information will only be used to prove that you are a real person, once verified your message will be sent.
            Anonymousfeedback will not give out or use your name/email ever.</font>
            <h4>Message Information:</h4>
            To (name): <input type="text" name="their_name" value = "'.$this->encode($args['their_name']).'" /> </a>';
            if(isset($args['their_name']) && preg_match('/@/', $args['their_name']) && $this->error){
                $this->body .= "<b><font size='2' color='red'>** Name only please, no emails allowed in this field</font></b>";
            }

            $this->body .= '<br/>
            <font size=1>The above field will appear on the front of the envelope. Enter a name here, not an email address.  <br>Looking to send anonymous email?  <a href="send-anonymous-email.html">Send email here.</a></font><p>
            Location to send (Mailing Address):<br/> <textarea cols=50 rows = 2 name="address">'.$this->encode($args['address']).'</textarea><br/>
            <hr>
            Message: <br/><textarea cols=70 rows = 20 name="message">'.$this->encode($args['message']).'</textarea>
            <p>
            <input type="hidden" value="1" name="is_preview"/>
            <input type=submit name="Submit" value="Submit"/></font>';
        $this->body .= '</form>';

        return $this->body;
    }
}
?>
