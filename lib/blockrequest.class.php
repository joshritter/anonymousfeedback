<?php
require_once 'base.class.php';

class blockrequest extends base {

    function main (){
        parent::base();
    }

    function generateBody() {

        $this->title = "Block your email address - ";
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            return $this->processPost();
        } else {
            $this->generateBanEmailForm();
        }
    }

    function generateBanEmailForm() {
        $this->body .= '<form name="ban" method="post" action="block-email-address.html">';
        $this->body .= '<h1>Block your email address</h1><font size=2> Someone bothering you?  Enter your email below to start the process of adding yourself to our do not send list.  When you submit your request we will automatically generate an email with further instructions!</font><br><br>';
        $this->body .= 'Email Address: <input type="text" name="email_address" maxlength="255" />';
        $this->body .= '<input type=submit name=submit value="Submit"> ';
        $this->body .= '</form>';

    }

    function processPost() {
        $email_address = $this->_fetchParam('email_address');
        if(!$email_address){
            return $this->generateBanEmailForm();
        }

        $email_id = encrypt($email_address, $this->encrypt_key);
        $message = "We recieved a request to block all messages to your email address.

If you don't want emails from anonymousfeedback.net anymore please click this link to confirm: http://www.anonymousfeedback.net/block-email-confirmation.html?id=$email_id

Thank you!

-Anonymousfeeback.net team";
#$this->body .= $message;
        $to = "<$email_address>";
        $from = "From: AnonymousFeedback.net<admin@anonymousfeedback.net>";
        $subject = "Anonymousfeedback.net - block email request confirmation";
        mail($to, $subject, $message, $from);
        $this->body .= '<h2>Success</h2> <font size = 2>We have sent you an email.  Please check your inbox and follow the instructions.</font>';

    }

}
?>
