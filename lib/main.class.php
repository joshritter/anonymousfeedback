<?php

require_once 'base.class.php';
class main extends base {

    function main (){
        parent::base();
        $this->title = "How to send anonymous email - ";
    }

    function generateBody() {
        $this->body = '<font size="-1" face="arial"><h1>SEND ANONYMOUS EMAIL FEEDBACK TO ANYBODY!</h1>
<br>Do you want to send someone an anonymous email message but don\'t want them to know who it is from? 
AnonymousFeedback.net lets that happen!!<p>Think of it as an online suggestion box 
where you can suggest, criticize, or just let someone know your true feelings.<p>
<h2>SEND ANONYMOUS LETTERS IN THE MAIL</h2>
We are proud to bring to you our anonymous snailmail feature.  We use our mail system to ensure there is
no way to track your letter back to you.  <a href="send-letter.html">Send a Letter</a>.<p>
<h2>ADD ANONYMOUS EMAIL TO YOUR WEBSITE</h2>
Do you want to allow people to give you feedback but want to keep them on your site?
It is possible with anonymousfeedback.net.  Follow the instructions <a href="add-anonymous-email-to-your-site.html">to add anonymous email to your site</a> and
you will be collect anonymous feedback in no time.<p>
Anything you send will be kept confidential. Anonymousfeedback.net was created so you can give the anonymous feedback you want without
fear of retaliation or harassment. The cool part is you can create a ONE-WAY anonymous email message 
or you can have an anonymous email conversation where the other
person will never know who you are.
Just fill out the form below. The person will NEVER find out who sent the email message unless you want them to. 
<a href="about-us.html"><font size="-2">learn more</font></a>

<p>
'. $this->count.' anonymous email messages have been sent to date.

<p><center>................................................................................<p>
<a href="http://anonymousfeedback.net/cheater.html"></a>
<table><tr><td><a href="send-anonymous-email.html"><img title="click to send anonymous email" src="images/button-online.png" border=0></a></td><td><a title="click to send anonymous letter" href="send-letter.html"><img src="images/button-snail.png" border=0></a></td></tr>

<tr><td colspan=2><font size="-1" face="arial"><i>Sending online anonymous email is free, there is a charge for snailmail messages.</i></font></td></tr></table>';
    }

}
?>
