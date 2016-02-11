<?php
require_once 'base.class.php';

class block extends base {

    function main (){
        parent::base();
        $this->title = "Block your email address - ";
    }

    function generateBody() {
        $email_id = $this->_fetchParam("id");
        if($email_id == null || $email_id == "") {
            $this->body .= "Invalid input.";
            return;
        }

        $email_address = decrypt($email_id, $this->encrypt_key);

        $valid_looking = (preg_match("/@/",$email_address)) ? true : false;
        if($email_address == null || $email_address == "" || !$valid_looking){
            $this->body .= "Invalid input.";
            return;
        }
        //XXX TODO email validation????
        //

        $success = $this->addBannedEmail($email_address);
#        $this->dbh->sqlQuery("INSERT INTO bannedEmail VALUES (?, ?, ?)", array($email_address, 1, 'nosalt'));

        if($success) {
            $this->body .= "Your email address (".$this->encode($email_address).") has been placed onto our ban list.  You will no longer recieve emails from users using our site.<br>";
        } else {
            $this->body .= "Problem adding your email address, please try again later.  If this problem persists, please email admin@anonymousfeedback.net";
        }
    }

}
?>
