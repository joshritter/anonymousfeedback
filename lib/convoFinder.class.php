<?
require_once 'base.class.php';

class convoFinder extends base {

    function convoFinder (){
        parent::base();
    }

    function generateBody() {

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if($_POST['email']==''){
                $page = $this->displayForm($_POST);
                $this->body = $page['body'];
                return;
            }
            $this->dbh->sqlQuery("SELECT * FROM conversations WHERE
                toEmail = ? OR fromEmail = ?
                GROUP BY convoID", array($_POST['email'], encrypt($_POST['email'], $encrypt_key)));
            if($this->dbh->rowCount() > 0){
                $email = array();
                $email['body'] = "A request was made to find all your conversations on www.anonymousfeedback.net.  You can view your conversations 
                    at the following web addresses:

                    ";
                $email['to']= $_POST['email'];
                $email['from'] = "From: Found! <noreply@anonymousfeedback.net>";
                $email['subject'] = "Your Anonymous Conversations";
                while($r = $this->dbh->fetchRow()){
                    $email['body'] .= "To http://www.anonymousfeedback.net/index.php?action=convo&convoID={$r['convoID']}
                        ";
                }
                mail($email['to'], $email['subject'], $email['body'], $email['from']);

            }

            $page['body'] ="<p><b><font face=arial><font color=green>Success!</b></font> You should recieve an email shortly with links to all of your
                conversations. <p>*NOTE* Due to privacy issues this is not a confirmation that the email entered is in the database.";

        }else { 
            $page = $this->displayForm(null);
        }

        $this->body = $page['body'];
        return $page['body'];
    }

    function displayForm($r=false){
        $display = array();
        $display['title']='Get Conversations';
        $display['body'] = "
            <form method=post action=''>
            <font size=1 face=arial>Need to find old conversations you have had? Enter your email address and an email will be sent you. 
            <p><font size=1 face=arial>Email: <input type=text name=email value={$this->encode($r['email'])}><p>
            <input type=submit name='submit' value='Submit >'></font><p></form>";
        return $display;
    }
}
?>
