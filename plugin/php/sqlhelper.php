<?php
require_once(dirname(dirname(dirname(__FILE__)))."/config/config.php");

@header('Content-Type:text/html; charset=utf-8');//使用gb2312编码，使中文不会变成乱码
class sqlhelper{
	//$link = @mysql_connect('127.0.0.1','621788','19950815');
	protected $sqlaccount= DB_USER; //--sql数据库账号
	protected $databasePwd=DB_PASSWORD; //--sql数据库密码
	protected $databaseName=DB_DBNAME; //--数据库名称
	protected $host=DB_HOSTNAME; //--连接地址
	protected $errorReportingLevel = 0; //--php提醒错误级别
	protected $link;
	
    //连接数据库
	public function connect(){
		error_reporting($this->errorReportingLevel);
		$this->link = @mysql_connect($this->host,$this->sqlaccount,$this->databasePwd);
		if(!$this->link){
			return 1; //--网络错误1	
		}else{
			if(!mysql_select_db($this->databaseName,$this->link)){
				return 2; //--网络错误2
			}else{
				return 0;//---成功
			}
		}
	}
	
	//执行一条mysql
	public function mysql($sql){
		global $rerere;
		mysql_query("SET NAMES utf8");  //必须使用这一个才能确保中文不乱码.注意,我的数据库全部使用utf8格式
		$rerere = mysql_query($sql);
		return $rerere;
	}
	
    //获取数据库中某一行数据
	public function getTableRow($tablename){
		$rerere = mysql("select * from $tablename");
		return mysql_num_rows($rerere);
	}
	
}


?>