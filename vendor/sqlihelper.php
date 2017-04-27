<?php

class sqlihelper{

    protected $_host;

    protected $_username;

    protected $_password;

    protected $_db;

    protected $_port = 3306;

    protected $charset = "utf8";

    /**
     * 记录 mysqli 实例
     * @var mysqli
     * */
	protected $_mysqli;


    /**
     * 结果集
     * @var mysqli_result
     */
    public $result = false;

    public $stmterrno; //--执行错误码
    public $stmterr; //--执行错误信息
	
    function __construct($mysqli = null){
        if(!is_null($mysqli)){
            $this->_mysqli = $mysqli;
        }
        $this->_host = DB_HOST;
        $this->_username = DB_USR;
        $this->_password = DB_PWD;
        $this->_db = DB_TABLE;
    }
    
    
    //连接数据库
	public function connect(){

        $this->_mysqli = new mysqli($this->_host,$this->_username,$this->_password,$this->_db);

		if ($this->_mysqli->errno) {
            return false;
        }else{
		    $this->_mysqli->set_charset($this->charset);
            return true;
        }
	}
    
    //关闭连接
    public function close(){
        return $this->_mysqli->close();
    }
	
	//执行一条mysql
	public function mysql($sql){

        if(is_null($this->_mysqli)){
            throw new Exception("mysqli is null now");
        }

		$this->result = $this->_mysqli->query($sql);
        $this->stmterrno = $this->_mysqli->errno;
		return $this->result;
	}
    
    //获取错误编号
    public function errorno(){
        return $this->_mysqli->errno;
    }
    
    //获取错误信息
    public function error(){
        return $this->_mysqli->error;
    }

    /**
     * 结合查询，以防sql注入
     * @use bindingQuery($sql,$params,...$bindingvar)
     * @return bool|mysqli_result
     * @throws Exception
     * @internal param string $sql sql语句
     * @internal param string $params 变量绑定规则，参考bind_param()的参数
     * @internal param mixed $bindingvar 变量（多个）
     */
    public function bindingQuery(){

        if(is_null($this->_mysqli)){
            throw new Exception("mysqli is null now");
        }

        $argcount = func_num_args();         //输出参数个数
        if($argcount<3){
            throw new Exception("参数个数不能少于三个");
        }
        $sql = func_get_arg(0);
        $params = func_get_args();
        array_shift($params);
        $stmt = $this->_mysqli->prepare($sql);
        
        
        //语法错误会在这里输出
        if(!$stmt){
            return false;
        }
        call_user_func_array(array($stmt,"bind_param"),$this->refValues($params));
        $this->stmterrno = $stmt->errno;
        $this->stmterr = $stmt->error;
        if(!$stmt->execute()){
            $this->stmterrno = $stmt->errno;
            $this->stmterr = $stmt->error;
            return false;
        }
        $this->stmterrno = $stmt->errno;
        $this->stmterr = $stmt->error;
        $this->result = $stmt->get_result();
        
        return $this->result;
    }
    
    public function isSuccess(){
        if($this->stmterrno==0){
            return true;
        }else{
            
            return false;
        }
    }
	
    //用于转换引用
    private function refValues($arr){ 
        if (strnatcmp(phpversion(),'5.3') >= 0) //Reference is required for PHP 5.3+
        {
            $refs = array();
            foreach($arr as $key => $value)
                $refs[$key] = &$arr[$key];
            return $refs;
        }
        return $arr; 
    }

    public function getMysqli(){
        return $this->_mysqli;
    }

    public function resetDataSeek(){
        if($this->result){
            $this->result->data_seek(0);
        }
    }

    public function fetchRow(){
        if($row = $this->result->fetch_assoc()){
            return $row;
        }
    }
}
