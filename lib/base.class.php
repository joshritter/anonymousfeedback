<?php

require_once 'database.class.php';
require_once 'encrypt.php';
require_once 'settings.inc.php';

class base {

    var $dbh;
    var $count;
    var $time;
    var $encrypt_key;
    var $ip;
    var $body;
    var $title;
    var $description;
    var $banned_emails;
    var $examples;

    function base() {
        global $settings;
        $this->dbh = new database($settings['host'], $settings['user'], $settings['pwd'], $settings['port'], $settings['name']);
        $this->dbh->sqlQuery("SELECT count(convoID) as count FROM conversations");
        $data = $this->dbh->fetchRow();
        $this->count= $data['count'];
        $this->time = time();
        $this->encrypt_key = $settings['encrypt_key'];

        $this->ip = $_SERVER['REMOTE_ADDR']; 
        $this->banned_emails = [];
        $success = false;
	$emails = [];
        //$emails_initialized = apc_fetch("anon_email_list_loaded");
        //if($emails_initialized) {
//            $emails = apc_fetch("anon_banned_emails", $success);
//        } else {
            $this->dbh->sqlQuery("SELECT *  from bannedEmail");
            if($this->dbh->rowCount() > 0){
                while($row = $this->dbh->fetchRow()){
                    array_push($emails, $row['email_address']);
                }
            }
            $success = true;
  //          apc_store("anon_email_list_loaded", 1);
    //        apc_store("anon_banned_emails", $emails);
  //      }
        if($success) {
            $this->banned_emails = $emails;
        }

    }

    function __destruct() {
        if($this->dbh) {
            $this->dbh->close();
        }
    }

    function getDate() {
        $timeStamp = date("F j, Y, g:i a",$this->time- 10800);
        return "$timeStamp PST";
    }
    function getBannedEmailList() {
        return $this->banned_emails;
    }

    function addBannedEmail($email_address) {
        $this->dbh->sqlQuery("INSERT INTO bannedEmail VALUES (?, ?, ?)", array($email_address, 1, 'nosalt'));
        array_push($this->banned_emails, $email_address) ;
//        $emails = apc_store("anon_banned_emails", $this->banned_emails);
        return true;
    }


    function isBanned() {
        $this->dbh->sqlQuery("SELECT * FROM banIP");
        while ($array = $this->dbh->fetchRow()) {
            $id  = $array['id'];
            $dbIP = $array['ip'];

            if($dbIP == $this->ip) {
                return true;
            }
        }//while

        return false;

    }

    function displayBanned() {
        return "<html><title>AnonymousFeedback.net</title><body><h1>You have been banned</h1></body></html>".
            "<p><b>Anonymousfeedback.net<p><font color=red>ACCESS RESTRICTED</font></b><p>It has been decided that you have broken our Terms of Service (no hatred messages, no spam, no abusing the site) and you have been restricted from sending any more anonymous messages. Please understand we made this website to help people and we do not want it abused. If you feel you have been banned in error, please accept our apologies and <a href=\"index.php?action=page&page=contact\">contact us</a>. Thank you for your understanding.</body></html>"; 
    }

    function getBody(){ 
        $this->generateBody();
        return $this->body; 
    }

    function getTitle() {
        return $this->title;
    }

    function printAll() {

        $this->generateExamples();
        $this->generateBody();

        if ($_SERVER['HTTP_HOST'] == '127.0.0.1' || $_SERVER['HTTP_HOST'] == 'localhost') { // local testing; show MySQL queries
            $this->body .= '<table bgcolor="white"><tr><td><div style="bgcolor:white;padding:15px"><tt>';
            $this->body .= '<h4>' . count($this->dbh->queries) . ' queries total</h4><ol type=1>';
            foreach ($this->dbh->queries as $query) {
                $this->body.=  '<li>' . $query;
            }
            $this->body .= '</ol></td></tr></table>';
        }
        require_once 'inc/template.main.php';
    }

    function encode($string){
        return htmlentities($string, ENT_QUOTES);
    }

    function isBlockedEmail($email){
        global $do_not_send_array;

        if (in_array(strtolower($email), $do_not_send_array)){
            return true;
        }
 	if (in_array(strtolower($email), $this->banned_emails)){
            return true;
        }       
	return false;
    }

    function generateExamples() {
        $today = getdate();
        $month = $today["mon"];
        $day = $today["mday"];
        $this->year = $today["year"];

        // RANDOM EXAMPLES ON THE RIGHT
        $f=0;
        $i=0;
        srand ((double) microtime( )*1000000);
        $this->dbh->sqlQuery("SELECT * from examples");
        $examples = array();
        while($data = $this->dbh->fetchRow()){
            $id  = $data['id'];
            $example = $data['example'];
            $random_number = rand(0,15);
            if($random_number > 4){
                $usedAds[$example] = 1; // code for no repeats
                $this->examples .= "<b><font color=#4C6B8F><img src=\"images/list2.gif\">";
                $this->examples .= $example;
                $this->examples .= "<br><img src=\"images/dots.gif\"><br>";
            } // end if $random_number = 1
        } // end while 4
    } 

    function _fetchParam($param_name) {
        $value = null;
        if (isset($_POST[$param_name])) {
            $value = $_POST[$param_name];
        } else if (isset($_GET[$param_name])) {
            $value = $_GET[$param_name];
        }
        return $value;
    }
}
?>
