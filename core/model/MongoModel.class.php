<?
/* 2017-04-19
** wenming
** 数据库操作类
** 
*/
class MongoModelClass
{
	public function __construct()//构造函数
	{
		$MONGO_SERVER =  $GLOBALS['MONGO_CONFIG']['IP'];
		$MONGO_USER   =  $GLOBALS['MONGO_CONFIG']['USER'];
		$MONGO_PASS   =  $GLOBALS['MONGO_CONFIG']['PASS'];
		$MONGO_DBNAME =  $GLOBALS["MONGO_CONFIG"]['DBNAME'];
		$clientOption = array(
							'username' => $MONGO_USER,
							'password' => $MONGO_PASS,
							'db' => 'admin'
						);
		$connection = new MongoClient("mongodb://".$MONGO_SERVER,$clientOption);
		$this->mongoconn = $connection->$MONGO_DBNAME;
	}
	
	public function __call($method,$args)
	{
		if(array_key_exists($method,$this->config))
		{
            $this->config[$method] = $args[0];
        }
		return $this;
	}

	public function setNewConn($MONGO_SERVER)
	{
		$confarr = parse_ini_file('inc/mssql/mongo.inc');
		$MONGO_USER   =  $confarr["MONGO_USER"];
		$MONGO_PASS   =  $confarr["MONGO_PASS"];
		$MONGO_DBNAME =  $confarr["MONGO_DBNAME"];
		$clientOption = array(
			'username' => $MONGO_USER,
			'password' => $MONGO_PASS,
			'db' => $MONGO_DBNAME
		);	
		$connection = new MongoClient("mongodb://".$MONGO_SERVER,$clientOption);
		$this->mongoconn = $connection->$MONGO_DBNAME;
	}
	
	public function find($Table, $query, $field = array())//查找 返回一条
	{
		eval("\$collection = \$this->mongoconn->".$Table.";");
		$ROW = $collection->findOne($query, $field);
		if(is_array($ROW)){
			if(count($ROW) == 0){
				return false;
			}else{
				return $ROW;
			}
		}else{
			return false;
		}
	}
	
	public function count($Table, $query)//返回数量
	{
		eval("\$collection = \$this->mongoconn->".$Table.";");
		$count = $collection->count($query);
		return $count;
	}
	
	public function group($Table, $query, $keys, $initial, $reduce)//分组查询
	{
		eval("\$collection = \$this->mongoconn->".$Table.";");
		$ROW = $collection->group($keys, $initial, $reduce, array('condition'=>$query));
		
		$resArray = array();
		foreach ($ROW as $k => $v) {
			$resArray[] = $v;
		}
		
		if(count($resArray) == 0){
			return array();
		}else{
			return $resArray;
		}
	}
	
	public function select($Table, $query, $field = array())//查找 返回全部条件
	{
		eval("\$collection = \$this->mongoconn->".$Table.";");
		$ROW = $collection->find($query, $field);
		
		$resArray = array();
		foreach ($ROW as $k => $v) {
			$resArray[] = $v;
		}
		
		if(count($resArray) == 0){
			return array();
		}else{
			return $resArray;
		}
	}
	
	public function page($Table, $query, $order, $field = array(), $pageIndex, $pageSize)//分页查找 页码从1开始  sort 1正序 -1倒序
	{
		eval("\$collection = \$this->mongoconn->".$Table.";");
		$ROW = $collection->find($query, $field)->sort($order)->skip(($pageIndex-1)*$pageSize)->limit($pageSize);
		
		$resArray = array();
		foreach ($ROW as $k => $v) {
			$resArray[] = $v;
		}
		
		if(count($resArray) == 0){
			return array();
		}else{
			return $resArray;
		}
	}
	
	public function insert($Table, $data){//插入
		eval("\$collection = \$this->mongoconn->".$Table.";");
		
		try{
			$collection->insert($data);
			//writeLog2("mongoDB",print_r($data,true));
		}catch(Exception $e){
			//writeLog2("mongoDB_Error",print_r($data,true));
		}
	}
	
	public function update($Table, $query, $data, $multiple = "multiple"){//更新
		eval("\$collection = \$this->mongoconn->".$Table.";");
		
		$data = array(':set' => $data);//替换内容
		
		if($multiple == "multiple"){
			$option = array('upsert' => 0, 'multiple' => true);
		}else{
			$option = array('upsert' => 0, 'multiple' => false);
		}
		
		//writeLog2("mongoDB","query:".print_r($query,true).",data:".print_r($data,true));
		$collection->update($query, $data, $option);//更新多条数据
	}
	
	public function remove($Table, $query){//删除
		eval("\$collection = \$this->mongoconn->".$Table.";");
		
		$collection->remove($query);
	}
}
?>