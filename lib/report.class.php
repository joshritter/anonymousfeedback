<?
require_once 'base.class.php';

class report extends base {

    function report (){
        parent::base();
        $this->title = "Report an abusive anonymous email - ";
        $this->description = "How to report an abusive anonymous email - ";
    }

    function generateBody() {
        $this->body = '
            <h1>Report an anonymous email</h1>

        Over 99.9% of the time, our website is used for what it was designed for. However, sometimes people will decide to abuse our website and we do not want that to happen. 
        <p>
        <h4>THINGS WE CAN DO:</h4>
        <ul>
            <li> Block your email address to stop anyone else from sending a message to you from our site</li>
            <li> Suggest you add noreply@anonymousfeedback.net to your spam filter</li>
            <li> Possibly block the sender from our site, if they break our terms of service</li>
        </ul>

        <h4>THINGS WE CANNOT DO:</h4>
        <ul>
            <li> Give you any information on the sender. The site was designed to allow people to anonymously send messages</li>
            <li> Stop someone from using other means to harass you (ie create a free email account, calling, etc). We are more than happy to assist with anything related to the site, but anything not related to the site, please contact your local law enforcement</li>
        </ul>
        If you would like to report this message, please go to our <a href="http://www.anonymousfeedback.net/index.php?action=page&page=contact">CONTACT PAGE</a> and copy and paste the message you received. Please be sure to let us know any emails addresses the messages were sent to. We will quickly track down the issue and make sure you do not get any additional harassing emails.
        <p>
        Thank you for your understanding!
        <p>
        Sincerely,<br>
        AnonymousFeedback.net Team<br>
        admin@anonymousfeedback.net<br>
        http://www.anonymousfeedback.net
        ';
      return $this->body; 
    }

}
?>
