<?
require_once 'base.class.php';

class page extends base {

    var $current_page;
    function page (){
        parent::base();
        $this->current_page = $_GET["page"];
    }

    function generateBody() {
        if ($this->current_page == "faq") {
            $this->title = "Frequently asked questions - ";
            return $this->displayFAQ();
        }else if($this->current_page == 'ideas'){
            return $this->displayIdeas();
        }else if($this->current_page == 'about'){
            $this->title = "About us - ";
            return $this->displayAbout();
        }else if($this->current_page == 'privacy'){
            return $this->displayPrivacy();
        }else if($this->current_page == 'contact'){
            $this->title = "Contact us - ";
            return $this->displayContact();
        }else if($this->current_page == 'update'){
            return $this->displayUpdate();
        }

        return $this->body;
    }

    function displayUpdate() {
        $this->body = '
            <h1>Anonymousfeedback updates</h1>
            <h4>March 2011</h4>
            Snailmail feature is now live!  You can now send anonymous letters in the mail.  On top of that major updates to the backend system have been put in place to create a more secure enviroment for all to enjoy (the site is much faster also).  Keep sending those anonymous emails!  Let us know what you want us to work on next!  Give us <a href="contact.html">feedback</a>.
            <h4>September 2010</h4>
            Embedable anonymous feedback messages are now online!  You can put the power of anonymousfeedback.net on your website.  Just click on the add to your site link 
            at the top of the page and follow the instructions! Or click <a href="add-anonymous-email-to-your-site.html">here!</a>
            <h4>Nov 2 2006</h4>
            That\'s right! The last few months the site has been kind of a test bed to see if this was a site people wanted to use and other options we should add. <p>
            First off the look of the site has changed a bit and been cleaned up. There is also a new QUICK SEND feature off the front page by-passing all the options.
            The error where "rnrn" would show up on some messages has been fixed too.
            <p>
            Thanks for stopping by and enjoy!';
    }
    
    function displayContact() {

        $this->body = '<p><font size="-1" face="arial"><h1>CONTACT US</h1><br><p>';

        if ($_SERVER['REQUEST_METHOD'] == 'POST') { // send the email

            $name = $_POST["name"]; 
            $email = $_POST["email"];
            $notes = $_POST["notes"];
            $spam = $_POST["spam"];
            $emailIP = $_POST["emailIP"];

            if ($name==""){
                $this->body .= "<p><font color=\"red\"><b>ERROR!</b> No e-mail was sent!<br>Please enter you name in the form.";
            } else if ($email==""){
                $this->body .= "<p><font color=\"red\"><b>ERROR!</b> No e-mail was sent!<br> Please enter your email address in the form.";
            } else if ($notes==""){
                $this->body .= "<p><font color=\"red\"><b>ERROR!</b> No e-mail was sent!<br> Please fill our the notes section.";
            } 

            // ============================================ SPAM / INAPPROPIATE CHECKS =================================== //

            //Checks for bad words
            global $obscenities;
            $obs_string = implode('|',$obscenities);
            if(preg_match("/$obs_string/i", trim($notes))){
                $abuse = "BAD WORD";
            }

            //checks against banned domains
            $domain = strstr($email, '@');
            if ($domain == "@mail.ru" || $domain == "@web.de" || $domain == "@cashette.com" || $domain == "@gawab.com" || $domain == "@wwwfreemail.info"
                || $domain == "@tut.by"|| $domain == "@tramadol.quickfreehost.com"|| $domain == "@instant-profits.biz"){ 
                    $abuse = "DOMAIN";
                }

            //If either one comes us as abuse then do not send
            if ($abuse != "") {	
                $this->body .= "<b><font color=red size=4>NOTICE!</font> Your message has <b>NOT BEEN SENT</b>. It has been tagged as SPAM or as having inappropiate content.
                No bad words, no spam.<p>Your IP address has been recorded: ".$this->ip."

                <input type=hidden name=toEmail value=\"".$this->encode($toEmail)."\">
                <input type=hidden name=toName value=\"".$this->encode($toName)."\">
                <input type=hidden name=fromEmail value=\"".$this->encode($fromEmail)."\">
                <input type=hidden name=\"message\" value=\"".$this->encode($message)."\">
                <input type=hidden name=subject value=\"".$this->encode($subject)."\">
                <input type=hidden name=speed value=\"".$this->encode($speed)."\">
                <input type=hidden name=respond value=\"".$this->encode($respond)."\">
                <input type=hidden name=subject2 value=\"".$this->encode($subject2)."\">
                <input type=hidden name=terms value=\"".$this->encode($terms)."\">
                <input type=hidden name=stage value=\"".$this->encode($stage)."\">";
                return $this->body;
            } else {

                $this->body .= "<p><font size=\"2\">Thank you for your Feedback! We will get back with you very shortly.";


                $from2 = "From: $email";
                $subject2 = "AnonymousFeedback.net Contact";
                $to2 = "<admin@anonymousfeedback.net>";
                $message2 = "Name: $name  
                    Email: $email
                    IP: $emailIP

                    $notes
                    ";
                mail($to2, $subject2, $message2, $from2);
            }
            return $this->body; 
        } //end post

        $this->body .= '
            Now it is your turn to give us Non-Anonymous Feedback!

            <p>
            <table cellpadding="4"><tr><td colspan="2">

            <form method="post" action="contact.html">
            </td></tr><tr><td>
            <b><font face="arial" font size="2">Name:</td><td> <input type="text" background="red" name="name" size="30" value="'.  $this->encode($name).'">

            </td></tr><tr><td>
            <b><font face="arial" font size="2">Email: </td><td><input type="text" bgcolor="red" name="email" size="30" value="'. $this->encode($email).'">


            </td></tr><tr><td valign="top"><font face="arial" font size="2"><b>Message: </td><td> <textarea name="notes" cols="30" rows="3"></textarea></td></tr>
            <!-- <tr><td colspan=2 bgcolor="CCCCCC"><input type=checkbox name=spam value=no> Check this box to verify this is NOT a spam message</td></tr> -->
            <tr><td colspan=2><center>
            <input type=hidden name=emailIP value="'.$this->ip.'">
            <input type=submit value="Send Feedback">
            </td></tr></table>
            </form>';
        return $this->body;
    }
    
    function displayPrivacy() {
        $this->body = '<p><font size="-1" face="arial"><h1>PRIVACY POLICY</h1>
            <p>
            By using the AnonymousFeedback.net service you are agreeing to the following Privacy Policy:<p>
            "We", "our", "us" is <a href="http://www.scoloncs.com">Scoloncs</a>, the company that owns anonymousfeedback.com
            <p>
            <li>We will not give, sell, or rent your email address to anyone.
            <li>We will never send you spam.
            <li>Any personal information given to us will not be given to the person you are sending a message to or to anyone else.
            <li>No message sent over the internet can be 100% secure. We cannot 
            guarantee an unknown party will not intercept a message before being received.
            <li>Even the most secure websites are able to be hacked or accessed by unauthorized people. Even though we will secure your information from outside
            parties as much as possible, we are not able to guarantee its complete security.
            <li>To make sure people are not breaking our terms of service, anonymousfeedback has the ability to monitor the messages sent. If a message breaks our
            terms of service we can hault the message without notification to the sender.
            <li>IP addresses are stored in case of abuse or criminal actions.
            <p>
            <p>
            Last Updated: 11/02/10';
    }
    
    function displayAbout(){
        $this->body = '<p><font size="-1" face="arial"><h1>About Anonymousfeedback.net</h1>
            <p>
            This site was created to give users a quick and easy way to give feedback to anyone or any company.
            Anonymousfeedback.net is the only site that not only allows you to give anonymous feedback, but also
            allows a user to have an ongoing conversation anonymously.<p>
            The site should always be used for conveying helpful criticism, letting off a little steam, and have a little fun. It 
            should never be used for bad. <p>
            Interested in what we are doing?  Find out about site updates <a href="/?action=page&page=update">here</a>.
            <p>
            Sincerely,<br>
            AnonymousFeedback.net <br>
            <a href="http://www.scoloncs.com">Scoloncs.com</a> ';

    }
    
    function displayIdeas(){
        $this->body = '<p><font size="-1" face="arial"><h1>FEEDBACK IDEAS</h1>
                <p>
                Here are some ideas of how to use this service:<p>';

        $i=0;
        $this->dbh->sqlQuery( "SELECT * FROM examples");
        while ($array = $this->dbh->fetchRow()) {
            $id  = $array['id'];
            $example = $array['example'];

            $this->body .= "$example <p>";
            $i++;
        } // end of while loop
        $this->body .= '<hr><p><a href="contact.html">Contact us</a> with more great suggestions and we will add them to the site!';
        return $this->body;
    }
    
    function displayFAQ() {
        $this->body = '<p><font size="-1" face="arial"><h1>FAQ</h1><br>
            You have questions, we have answers.
            <p>
            <b>What exactly is this site?</b><br>
            This site allows you to send an anonymous message to anyone you want without them knowing who you are.

            <p><a name=3></a></a>
            <b>Can I send any message?</b><br>
            Almost. As long as you aren\'t doing anything \'evil\'. No messages can be sent that contain hateful information. Before sending a message you will
            need to agree to our terms of service. 

            <p><b>Can I put something like this on my own site?</b><br>
            Yes you can!  Simply visit the <a href="add-anonymous-email-to-your-site.html">how to add us page</a>. 

            <p><a name=a></a></a>
            <b>Can my school/class/company/etc use this service?</b><br>
            Absolutely.  People at universities use this service to give feedback to professors and companies use it to give feedback to their supervisors. 

            <p><a name=1></a></a><b>Who can I send feedback to?</b><br>
            You can send feedback to anyone with an email address. Be it your best friend, your mom, or the President of the United States.


            <p><a name=2></a><b>Will they know who I am?</b><br>
            The only way they would know is if you tell them. Anonymousfeedback will keep your identity safe.

            <p><b>What if I received an inappropriate message?</b><br>
            Every user must agree to our terms of service, which includes not sending 
            any inappropriate messages. If you have receieved one, please <a href="contact.html">contact us</a> and let us know the situation. We try to do our best to make sure messages of hate are not sent.

            <p><a name=4></a><b>Is the person able to respond?</b><br>
            You have the option to let the person respond. Your conversation will be 
            saved, and you can access it at anytime. To use this option 
            you will need to enter your email address, so you can be informed you of the 
            reply. When sending an anonymous message click the \'Send More Options\' button to add your email.
            Your email address will NEVER be given to the receiver of your 
            feedback. Anonymousfeedback will keep your email address secure, but if 
            you are concerned, you might want to set up a free <a 
            href="http://www.hotmail.com">hotmail</a> or <a 
            href="http://www.gmail.com">gmail</a> account.

            <p><b>What if I lost the email that takes me to my conversation?</b><br>
            You can go <a href="index.php?action=convoFinder">here</a> and an email will be sent to 
            you with a link to your conversations.';
    }
}
?>
