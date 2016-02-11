<?php
class database {

    var $dbh;
    var $stmt;
    var $results;

    var $queries = array();

    function database($host, $user, $pwd, $port, $name) {
        $this->dbh = new mysqli($host, $user, $pwd, $name, $port);
        if(mysqli_connect_errno()) {
            echo "Connection Failed: " . mysqli_connect_errno();
            exit();
        }

    }

    function close() {
        mysqli_close($this->dbh);
    }

    function _getDBH(){
        return $this->dbh;
    }


    function lastInsertID() {
        return $this->dbh->insert_id;
    }

    function sqlQuery($sql, $params = array()) {
        $this->stmt = null;
        $statement = $this->_getDBH()->prepare($sql);

        $this->queries[] = $sql; // debugging
	$bind_params = array();
        $bind_string = '';
        for($i=0;$i<sizeof($params);$i++){
            array_push($bind_params, $params[$i]);
            $bind_string .= 's';
        }
        if($bind_string){
            array_unshift($bind_params, $bind_string);
	    call_user_func_array(array($statement, "bind_param"),$this->refValues($bind_params)); 
#            call_user_func_array(array($statement, 'bind_param'), $bind_params);
        }
        $statement->execute();

        $statement->store_result();

        $this->stmt = $statement;
        if($statement){
            $this->sr = new Statement_Result($statement);
            return $statement;
        }
    return null;
    }

    function refValues($arr){
        $refs = array();
        foreach($arr as $key => $value)
            $refs[$key] = &$arr[$key];
        return $refs;
    } 
    function fetchRow() {
        if($this->stmt){
            if($this->stmt->fetch()){
                return $this->sr->getArray();
            }else {
                $this->stmt->close();
                return null;
            }
        }
        return null;    
    }

    function rowCount(){
        if($this->stmt){
            return $this->stmt->num_rows;
        }
        return null;
    }
}

class Statement_Result {
    private $_bindVarsArray = array();
    private $_results = array();

    public function __construct(&$stmt){
        $meta = $stmt->result_metadata();

        if($meta){
            while ($columnName = $meta->fetch_field())
                $this->_bindVarsArray[] = &$this->_results[$columnName->name];

            call_user_func_array(array($stmt, 'bind_result'), $this->_bindVarsArray);

            $meta->close();
        }
    }

    public function getArray()
    {
        return $this->_results;   
    }

    public function get($column_name){
        return $this->_results[$column_name];
    }
} 
?>
