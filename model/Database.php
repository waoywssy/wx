<?php
class Database{
	private $db;
	private $db_name;
	private $db_serv;
	private $db_user;
	private $db_pass;
	private $db_host;
	private $connect = true;	//是否开启长连接
	//构造函数以及连接数据库
	public function __construct($connect = true){
		$this->db_name = get_db_config('db_name');
		$this->db_host = get_db_config('db_host');
		$this->db_serv = "mysql:dbname=".$this->db_name.";host=".$this->db_host.";charset=utf-8";
		$this->db_user = get_db_config('db_user');;
		$this->db_pass = get_db_config('db_pass');;
		$this->connect = $connect;
	}
	//关闭数据库，析构函数
	public function __destruct(){
		$this->db = null;
	}

	//抽象方法，获取当前表名
	abstract function get_table_name();
	//执行SQL语句，返回值为sql执行结果
	public function run_query($sql){
		try{
			$this->db = new PDO($this->db_serv, $this->db_user, $this->db_pass, array(PDO::ATTR_PERSISTENT => $this->connect));
		}catch(PDOException $pdoerr){
			echo "数据库连接失败".$pdoerr->getMessage();
		}
		$this->db->query("set names utf8");		//设置PHP+MySQL连接的编码格式为UTF8
		$row = $this->db->query($sql);
		$row->setFetchMode(PDO::FETCH_ASSOC);	//设置查询结果显示为键值对数组模式
		return $row;
	}
	//执行SQL语句，返回值为sql影响行数
	public function run_exec($sql){
		try{
			$this->db = new PDO($this->db_serv, $this->db_user, $this->db_pass, array(PDO::ATTR_PERSISTENT => $this->connect));
		}catch(PDOException $pdoerr){
			echo "数据库连接失败".$pdoerr->getMessage();
		}
		$this->db->query("set names utf8");		//设置PHP+MySQL连接的编码格式为UTF8
		return $this->db->exec($sql);
	}
	/**
	* @abstract 插入操作
	* @param 	$table:表名; $data:插入数据键值对数组; 
	* @return 	返回值:插入结果，boolean
	*/
	public function data_insert($table, $data, $return = true,$debug=false){
		if(!$table) {
			return false;
		}
		$fields = array();
		$values = array();
		foreach ($data as $field => $value){
			$fields[] = '`'.$field.'`';
			$values[] = "'".addslashes($value)."'";
		}
		if(empty($fields) || empty($values)) {
			return false;
		}
		$sql = 'INSERT INTO `'.$table.'` 
				('.join(',',$fields).') 
				VALUES ('.join(',',$values).')';
		if($debug){
			return $sql;
		}
		$query = $this->run_exec($sql);
		return $return ? $this->db->lastInsertId() : $query;
	}
	/**
	* @abstract 更新操作
	* @param 	$table:表名; $condition:更新查询条件; $data:更新数据键值对数组; $limit:更新数据条数上限
	*			$debug:测试时给true，则不执行update，直接返回完整的sql语句
	* @return 	返回值:插入结果，boolean
	*/
	public function data_update($table, $condition, $data, $limit = 1,$debug=false) {
		if(!$table) {
			return false;
		}
		$set = array();

		foreach ($data as $field => $value) {
			$set[] = '`'.$field.'`='."'".addslashes($value)."'";
		}
		if(empty($set)) {
			return false;
		}
		$sql = 'UPDATE `'.$table.'` 
				SET '.join(',',$set).' 
				WHERE '.$condition.' '.
				($limit ? 'LIMIT '.$limit : '');
		if($debug){
			return $sql;
		}
		return $this->run_exec($sql);
	}
	/**
	* @abstract 查询单个字段值
	* @param 	$sql:sql语句
	* @return 	返回值:string
	*/
	public function getOne($sql){
		$row = $this->run_query($sql);
		$data = $row->fetch();
		$data = array_shift($data);
		return $data;
	}
	/**
	* @abstract 查询单条记录，多个字段
	* @param 	$sql:sql语句
	* @return 	返回值:键值对数组
	*/
	public function getRow($sql){
		$row = $this->run_query($sql);
		$data = $row->fetch();
		return $data;
	}
	/**
	* @abstract 查询多条记录
	* @param 	$sql:sql语句
	* @return 	返回值:以键值对数组为元素的数组
	*/
	public function getRows($sql){
		$row = $this->run_query($sql);
		$data = $row->fetchAll();
		return $data;
	}
}