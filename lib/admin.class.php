<?
require_once 'base.class.php';

class admin extends base {

    function admin(){
        parent::base();
        $this->title = "*Admin* How to send anonymous email - ";
    }

    var $pwd = "thisismypwd";
    var $snailMailStatus = array( 'verify' => 1, 'verified' => 1, 'pending' => 1, 'complete' => 1);
    
    function generateBody() {
        $this->body .= '<form action="?action=admin" method="post">';
        if(isset($_POST['login'])){
            $this->login($_POST['login_name'], $_POST['password']);
        } else if(!$this->isLoggedIn()){
            $this->displayLoginForm();
        } else if($_POST['lookup_snailmail']){
            if(isset($_POST['snail_mail_status'])){
                $this->updateStatus($this->decryptID($_POST['snailmail_id']), $_POST['snail_mail_status']);
            }
            $this->lookupSnailMail($_POST['snailmail_id']);
            $this->displayLookupSnailMailForm($_POST['snailmail_id']);
        } else {
            $this->body .= "Welcome admin - ";

            $this->displayLookupSnailMailForm();
        }
    }

    function displayLoginForm(){
        if(!$this->isLoggedIn()){
            $this->body .= '
                <b>Login:</b><br><br>
                <table>
                <tr>
                <td class="view">User Name:</td><td><input type="textbox" name="login_name" size=20></td>
                </tr><tr>
                <td class="view">Password:</td><td><input type="password" name="password" size=20></td>
                </tr><tr>
                <td></td><td><br><input type="submit" name="login" value="Login">
                </tr>
                </table>
                </form>'; 
        }
    }

    function updateStatus($id, $new_status){
        if(!$this->snailMailStatus[$new_status]){
            die("bad status - ". $this->encode($new_status));
        }
        $this->dbh->sqlQuery("UPDATE snailmail SET status = ? WHERE id = ?", array($new_status, $id));
    }

    function lookupSnailMail($id){
        $id = $this->decryptID($id); 
        if($id != ""){
            $this->dbh->sqlQuery("SELECT * from snailmail WHERE id = ?", array($id));
            $data = $this->dbh->fetchRow();
            if($data){
                $status_options ="<select name='snail_mail_status'><option value=''>None</option>";
                foreach($this->snailMailStatus as $option => $value){
                    $selected = ($data['status'] == $option) ? "selected=true" : "";
                    $status_options .= "<option $selected value='$option'>$option</option>";
                }
                $status_options .= "</select>";

                $this->body .= "<h4>Message Details:</h4>
                    Status: $status_options 
                    <input type='submit' name='lookup_snailmail' value='Update'>

                    <br>
                    ID: ".$this->encode($data['id'])."<br>
                    To: ".$this->encode($data['to_name'])."<br>
                    Email: ".$this->encode($data['sender_email'])."<br>
                    Sender: ".$this->encode($data['sender_name'])."<br>
                    Address: ".$this->encode($data['to_address'])."<br>
                    Message: <br><code style='width:150px;'>".$this->encode($data['message'])."</code>";

            }

        }

    }

    function decryptID($encrypted_id){

        $id = decrypt($encrypted_id, $this->encrypt_key);
        if(preg_match("/^anon_salt-[0-9]+$/", $id)){
            $id = str_replace('anon_salt-','', $id);
        }else {
            $id = "";
        }
        return $id;
    }

    function displayLookupSnailMailForm($id){
        $this->body .=
                '<table>
                <tr>
                <td class="view">Encrypted ID:</td><td><input type="textbox" name="snailmail_id" value="'.$this->encode($id).'" size=20></td>
                </tr><tr>
                <td></td><td><br><input type="submit" name="lookup_snailmail" value="Lookup">
                </tr>
                </table>
                </form>';
    }
    function isLoggedIn(){
        if(isset($_COOKIE['u']) && md5("admin:"+$this->pwd) == $_COOKIE['u']){
            return true;
        }
        return false;
    }
    function login($user_name, $password) {
        if($user_name == "admin" && $password == $this->pwd){
            setcookie('u',md5("admin:"+$password));
            header('Location: ?action=admin');exit;
        }else {
            $this->body .= "<span>Invalid username or password.<span><p>";
            $this->displayLoginForm();
        }
    }
}
?>
