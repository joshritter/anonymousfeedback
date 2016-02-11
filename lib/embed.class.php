<?

require_once 'base.class.php';
class embed extends base {
    function embed(){
        parent::base();
        $this->title = "How to add anonymous email to your site - ";
        $this->description = "How to add anonymous email to your site - ";
    }

    function generateBody() {
        $this->body = "<script>
    function generateCode() {
        var text = document.getElementById('generated_code');
        var to_name = document.getElementById('to_name').value;
        var to_email = document.getElementById('to_email').value;
        var use_from = document.getElementById('use_from');
        
        var missing_name_container = document.getElementById('missing_name');
        var missing_email_container = document.getElementById('missing_email');
        var code_helper = document.getElementById('code_helper');

        missing_name_container.innerHTML = '';
        missing_email_container.innerHTML = '';
        var has_errors = false;
        if(to_name == null || to_name == ''){
            has_errors = true;
            missing_name_container.innerHTML = \"Invalid or missing name.\";    
        }
        if(to_email == null || to_email == ''){
            has_errors = true;
            missing_email_container.innerHTML = \"Invalid or missing email.\";    
        }
        
        if(use_from && use_from.checked){
            use_from = '&use_from=1';
        }else {
            use_from = '';
        }

        if(has_errors){
            text.value = '';
            text.innerHTML = '';
            code_helper.style.display = 'none';
            return;
        }
        
    var code = \"<iframe width=450 height='700px' src=\\\"http://www.anonymousfeedback.net/send-anonymous-email/embed/?to_name=\"+to_name+\"&to_email=\"+to_email+\"\"+use_from+\"\\\" style='border:0px;'></iframe><br/><div style='margin:3px;padding-left:60px'>Powered by <a target='_blank' href=\\\"http://www.anonymousfeedback.net/\\\">AnonymousFeedback.net</a></div> \";
        text.value = code;
        text.innerHTML = code;
        code_helper.style.display = 'block';
    }
</script><font size=\"-1\" face=\"arial\">
<h1>Add anonymous email to your website</h1>
Do you want to get anonymous feedback on your website?  Now you can!<p/>  
Fill out the form below and  hit the 'Generate Code' button. Copy the html 
generated in the box and paste it onto your site.  <p/>
<br>
<b>Your name/business name:</b> <br><input type=\"text\" id=\"to_name\" value=\"\"> <span style=\"color:red;\" id=\"missing_name\"></span><br>
<b>Your email/business email:</b><br><input type=\"text\" id=\"to_email\" value=\"\"> <span style=\"color:red;\" id=\"missing_email\"></span><br>
<b>Allow replies ?</b><input title=\"Respond to messages that you receive.\" type=\"checkbox\" id=\"use_from\" value=\"1\"><p/>
<a href=\"javascript:void(0);\" onclick=\"generateCode();\">Generate Code</a></p>

<textarea cols=\"50\" readonly=\"readonly\" id=\"generated_code\"></textarea><br>
<span id=\"code_helper\" style=\"font-size:.8em;display:none;\"><b>* Copy the code above and place it onto your website</b></span>

<p> If you have questions or comments please <a href='contact.html'>contact us</a>.  Enjoy sending and receiving anonymous email using our software!</font>";
       return $this->body;
    }
}

?>
