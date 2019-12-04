<?
/* 2017-04-19
** wenming
** 数据库操作类
** 
*/
class MongoDBModelClass
{
	public function __construct()//构造函数
	{
		$MONGO_SERVER =  $GLOBALS['MONGO_CONFIG']['IP'];
		$MONGO_USER   =  $GLOBALS['MONGO_CONFIG']['USER'];
		$MONGO_PASS   =  $GLOBALS['MONGO_CONFIG']['PASS'];
		
		$this->MONGO_DBNAME = $GLOBALS["MONGO_CONFIG"]['DBNAME'];
		$this->mongoconn = new MongoDB\Driver\Manager('mongodb://'.$MONGO_USER.':'.$MONGO_PASS.'@'.$MONGO_SERVER.'/admin');
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
		$MONGO_USER   =  $confarr['MONGO_USER'];
		$MONGO_PASS   =  $confarr['MONGO_PASS'];
		
		$this->mongoconn = new MongoDB\Driver\Manager('mongodb://'.$MONGO_USER.':'.$MONGO_PASS.'@'.$MONGO_SERVER.'/admin');
	}
	
	public function find($Table, $query, $field = array()){
		$query = new MongoDB\Driver\Query($query,array('limit' => 1));
		$rows = $this->mongoconn->executeQuery($this->MONGO_DBNAME.'.'.$Table, $query);
		foreach($rows as $rowlist){
			$ROW = $this->_object_array($rowlist);
		}
		
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
		$query = array('count' => $Table, 'query' => $query);
		$query = new MongoDB\Driver\Command($query);
		$cursor = $this->mongoconn->executeCommand($this->MONGO_DBNAME, $query);
		foreach ($cursor as $k => $v) {
			$ROW = $v;
		}
		
		$ROW = $this->_object_array($ROW);
		$count = $ROW['n'];
		
		return $count;
	}
	
	/*public function group($Table, $query, $keys, $initial, $reduce)//分组查询
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
	}*/
	
	public function select($Table, $query, $field = array())//查找 返回全部条件
	{
		$MONGO_DBNAME = $confarr["MONGO_CONFIG"]['DBNAME'];
		$query = new MongoDB\Driver\Query($query);
		$rows = $this->mongoconn->executeQuery($this->MONGO_DBNAME.'.'.$Table, $query);
		
		$resArray = array();
		foreach($rows as $rowlist){
			$ROW = $this->_object_array($rowlist);
			if(is_array($ROW)){
				$resArray[] = $ROW;	
			}
		}
		
		if(count($resArray) == 0){
			return array();
		}else{
			return $resArray;
		}
	}
	
	/*public function page($Table, $query, $order, $field = array(), $pageIndex, $pageSize)//分页查找 页码从1开始  sort 1正序 -1倒序
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
	}*/
	
	public function insert($Table, $data){//插入
		$bulk = new MongoDB\Driver\BulkWrite(['ordered' => true]);
		$bulk->insert($data);
		
		$result = $this->mongoconn->executeBulkWrite($this->MONGO_DBNAME.'.'.$Table, $bulk);
		$is_success = $result->getInsertedCount() ? 1 : 0;
		if($is_success == 0){
			//writeLog2("mongoDB_Error",print_r($data,true));
		}else{
			//writeLog2("mongoDB",print_r($data,true));
		}

		return $is_success;
	}
	
	public function update($Table, $query, $data, $multiple = "multiple"){//更新
		if($multiple == "multiple"){
			$option = array('upsert' => 0, 'multiple' => true);
		}else{
			$option = array('upsert' => 0, 'multiple' => false);
		}

		$bulk = new MongoDB\Driver\BulkWrite(['ordered' => true]);
		$bulk->update($query, array('$set' => $data),$option);
		
		$result = $this->mongoconn->executeBulkWrite($this->MONGO_DBNAME.'.'.$Table, $bulk);
		$is_success = $result->getModifiedCount() ? $result->getModifiedCount() : 0;
		//writeLog2("mongoDB","query:".print_r($query,true).",data:".print_r($data,true));

		return $is_success;
	}
	
	public function remove($Table, $query){//删除
		$bulk = new MongoDB\Driver\BulkWrite;
		$bulk->delete($query);
		
		$result = $this->mongoconn->executeBulkWrite($this->MONGO_DBNAME.'.'.$Table, $bulk);
	}
 
	private	function _object_array($array){
		if(is_object($array)){
			$array = (array)$array;
		}
		if(is_array($array)){
			foreach($array as $key=>$value){
				$array[$key] = $this->_object_array($value);
			}
		}
		return $array;
	}
	
}
?>
