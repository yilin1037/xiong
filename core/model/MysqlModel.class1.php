<?
/* 2015-11-04
** lizhilin
** 数据库操作类
** 
*/
class MysqlModelClass 
{
	public $db;
	public $debug;
	public $debug_type;
	public $PREFIX;
	protected $config = array(
		"db_name"	=>	'',
		"res"		=>	'',
		"sql"		=>	'',
		"sqlError"	=>	'',
		"param"		=>	null,
	);
	
	public function __construct($type = 'user', $userIP = '', $userDB = '', $userPREFIX = '', $db_user = '', $db_psw = '' )//构造函数
	{
		$this->debug = $GLOBALS['MYSQL_CONFIG']['DEBUG'];
		$this->debug_type = $GLOBALS['MYSQL_CONFIG']['DEBUG_TYPE'];
		if($type == 'system')
		{
			$this->setSystemConn();	
		}else if($type == 'shop')
		{
			$this->setShopConn();	
		}else if($type == 'rds'){
			$this->setRdsConn($userIP, $userDB, $db_user, $db_psw);	
		}
		else
		{
			$this->setUserConn($userIP, $userDB, $userPREFIX);	
		}
	}
	
	public function __call($method,$args)
	{
		if(array_key_exists($method,$this->config))
		{
            $this->config[$method] = $args[0];
        }
		return $this;
	}
	
	public function getRes()
	{
		return $this->config['res'];
	}
	
	public function setSystemConn()
	{
		$this->db = null;
		try {
			$opt = array (PDO::ATTR_PERSISTENT => true, PDO::ATTR_ERRMODE => PDO::ERRMODE_SILENT);
			$this->db = new PDO ('mysql:host='.$GLOBALS['MYSQL_CONFIG']['IP'].';port='.$GLOBALS['MYSQL_CONFIG']['PORT'].';dbname='.$GLOBALS['MYSQL_CONFIG']['DBNAME'], $GLOBALS['MYSQL_CONFIG']['USER'], $GLOBALS['MYSQL_CONFIG']['PASS'], $opt);
			$this->config['db_name'] = $GLOBALS['MYSQL_CONFIG']['DBNAME'];
		} catch (PDOException $e) {
			print "Error!: " . $e->getMessage() . "<br/>";
			die();
		}
	}
	public function setShopConn(){
        //$this->db = null;
		//$opt = array (PDO::ATTR_PERSISTENT => true);
		//$this->db = new PDO ('mysql:host='.$GLOBALS['MYSQL_CONFIG']['IP'].';port='.$GLOBALS['MYSQL_CONFIG']['PORT'].';dbname='.$GLOBALS['MYSQL_CONFIG']['DBNAME'], $GLOBALS['MYSQL_CONFIG']['USER'], $GLOBALS['MYSQL_CONFIG']['PASS'], $opt);
		//$this->config['db_name'] = $GLOBALS['MYSQL_CONFIG']['DBNAME'];
		try {
			$this->db = null;
			$opt = array (PDO::ATTR_PERSISTENT => true, PDO::ATTR_ERRMODE => PDO::ERRMODE_SILENT);
			$this->db = new PDO ('mysql:host='.'114.55.14.31'.';port='.'30004'.';dbname='.'chaoqun', 'root2', 'root2', $opt);
			$this->config['db_name'] = 'chaoqun';
		} catch (PDOException $e) {
			print "Error!: " . $e->getMessage() . "<br/>";
			die();
		}
	}
	public function setUserConn($userIP, $userDB, $userPREFIX)
	{
		$this->db = null;
		if($userIP != '')
		{
			try {
				$opt = array (PDO::ATTR_PERSISTENT => true, PDO::ATTR_ERRMODE => PDO::ERRMODE_SILENT);
				$this->db = new PDO ('mysql:host='.$userIP.';port='.$GLOBALS['MYSQL_CONFIG']['PORT'].';dbname='.$userDB, $GLOBALS['MYSQL_CONFIG']['USER'], $GLOBALS['MYSQL_CONFIG']['PASS'], $opt);
				$this->config['db_name'] = $userDB;
				$this->PREFIX = $userPREFIX;
			} catch (PDOException $e) {
				print "Error!: " . $e->getMessage() . "<br/>";
				die();
			}
		}
		else
		{
			try {
				$opt = array (PDO::ATTR_PERSISTENT => true, PDO::ATTR_ERRMODE => PDO::ERRMODE_SILENT);
				$this->db = new PDO ('mysql:host='.$_SESSION['LOGIN_DBIP'].';port='.$GLOBALS['MYSQL_CONFIG']['PORT'].';dbname='.$_SESSION['LOGIN_DBNAME'], $GLOBALS['MYSQL_CONFIG']['USER'], $GLOBALS['MYSQL_CONFIG']['PASS'], $opt);
				$this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT);
				$this->config['db_name'] = $_SESSION['LOGIN_DBNAME'];	
			} catch (PDOException $e) {
				print "Error!: " . $e->getMessage() . "<br/>";
				die();
			}
		}
	}
	
	public function setRdsConn($userIP, $userDB, $db_user, $db_psw){
		$this->db = null;
		if($userIP != '')
		{
			try {
				$opt = array (PDO::ATTR_PERSISTENT => true, PDO::ATTR_ERRMODE => PDO::ERRMODE_SILENT);
				$this->db = new PDO ('mysql:host='.$userIP.';port='.$GLOBALS['RDS_CONFIG']['PORT'].';dbname='.$userDB, $db_user, $db_psw, $opt);
				$this->config['db_name'] = $userDB;
			} catch (PDOException $e) {
				print "Error!: " . $e->getMessage() . "<br/>";
				die();
			}
		}
		else
		{
			try {
				$opt = array (PDO::ATTR_PERSISTENT => true, PDO::ATTR_ERRMODE => PDO::ERRMODE_SILENT);
				$this->db = new PDO ('mysql:host='.$_SESSION['LOGIN_DBIP'].';port='.$GLOBALS['MYSQL_CONFIG']['PORT'].';dbname='.$_SESSION['LOGIN_DBNAME'], $GLOBALS['MYSQL_CONFIG']['USER'], $GLOBALS['MYSQL_CONFIG']['PASS'], $opt);
				$this->config['db_name'] = $_SESSION['LOGIN_DBNAME'];	
			} catch (PDOException $e) {
				print "Error!: " . $e->getMessage() . "<br/>";
				die();
			}
		}
	}
	
	public function query($sql, $PARAM = null)
	{
		if($PARAM && is_array($PARAM))
		{
			$tempArr = array();
			foreach($PARAM as $item)
			{
				$item['value'] = isset($item['value']) ? $item['value'] : '';
				$tempArr[$item['name']] = $item;
			}
			krsort($tempArr, 5);
			$NEW_PARAM = array();
			$i = 0;	
			foreach($tempArr as $item)
			{
				while(strpos($sql,$item['name']) !== false)
				{
					$i++;
					$sql = $this->str_replace_once($item['name'], ':SQL_PARAM'.$i, $sql);
					$NEW_PARAM[] = array('name' => ':SQL_PARAM'.$i, 'value' => ($item['type'] == PDO::PARAM_INT ? (int)$item['value'] : $item['value']), 'type' => ($item['type'] ? $item['type'] : PDO::PARAM_STR));
				}
				
			}
			krsort($NEW_PARAM, 5);
			$this->config['sql'] = $sql;
			$this->config['param'] = $NEW_PARAM;
		}
		else
		{
			$this->config['sql'] = $sql;
		}
		return $this;	
	}
	
	public function limit($nowPage, $pageSize)
	{
		$nowPage = (int)$nowPage == 0 ? 1 : $nowPage;
		$pageSize = (int)$pageSize == 0 ? 10 : $pageSize;	
		$this->config['sql'] = $this->config['sql']." limit ".((int)$nowPage-1)*$pageSize.",".(int)$pageSize;
		return $this;
	}
    public function limitPage($star, $pageSize)
    {
        $star = (int)$star == 0 ? 0 : $star;
        $pageSize = (int)$pageSize == 0 ? 10 : $pageSize;
        $this->config['sql'] = $this->config['sql']." limit ".$star.",".(int)$pageSize;
        return $this;
    }
	
	public function getColumns()
	{
		if(strtoupper(substr(PHP_OS,0,3)) === 'WIN')
		{
			for($i=0;$i<$this->config['res']->columnCount();$i++)
			{
				$arr = $this->config['res']->getColumnMeta($i);	
				$Columns[] = $arr['name'];
			}
		}
		else
		{
			$Columns = mssql_fetch_field($this->config['res']);	
		}
		return $Columns;
	}
	public function execute($sql, $PARAM = null)//执行 返回执行变更记录条数
	{
		if($PARAM && is_array($PARAM))
		{
			$tempArr = array();
			foreach($PARAM as $item)
			{
				$tempArr[$item['name']] = $item;
			}
			krsort($tempArr, 5);
			$NEW_PARAM = array();
			$i = 0;	
			foreach($tempArr as $item)
			{
				while(strpos($sql,$item['name']) !== false)
				{
					$i++;
					$sql = $this->str_replace_once($item['name'], ':SQL_PARAM'.$i, $sql);
					$NEW_PARAM[] = array('name' => ':SQL_PARAM'.$i, 'value' => ($item['type'] == PDO::PARAM_INT ? (int)$item['value'] : $item['value']), 'type' => ($item['type'] ? $item['type'] : PDO::PARAM_STR));
				}
				
			}
			krsort($NEW_PARAM, 5);
			$res = $this->db->prepare($sql);
			foreach($NEW_PARAM as $item)
			{
				$item['value'] = $item['value'] == '' ? '' : $item['value'];
				$res->bindValue($item['name'], ($item['type'] == PDO::PARAM_INT ? (int)$item['value'] : $item['value']), ($item['type'] ? $item['type'] : PDO::PARAM_STR));
				$sql = str_replace(rtrim($item['name']),"'".$item['value']."'", $sql);		
			}
			if(!$res->execute())
			{
				$this->_saveLog(false);	
			}
			$rowCount = $res->rowCount();
		}
		else
		{
			$rowCount = $this->db->exec($sql);	
		}
		//$rowCount = $this->db->exec($sql);
		$this->config['sql'] = $sql;
		if($rowCount !== false)
		{
			$this->_saveLog(true);
			return $rowCount;
		}
		else
		{
			$this->_saveLog(false);
			return false;
		}
	}
	
	public function lastInsertId()
	{
		return $this->db->lastInsertId();
	}
	
	public function find()//查找 返回一条
	{
		$sql = $this->config['sql'];
		if($this->config['param'] && is_array($this->config['param']))
		{
			$res = $this->db->prepare($this->config['sql']);
			foreach($this->config['param'] as $item)
			{
				@$res->bindValue($item['name'], ($item['type'] == PDO::PARAM_INT ? (int)$item['value'] : $item['value']), ($item['type'] ? $item['type'] : PDO::PARAM_STR));
				$sql = str_replace(rtrim($item['name']),"'".$item['value']."'", $sql);
			}
			$res->execute();
		}
		else
		{
			$res = $this->db->query($this->config['sql']);	
		}
		$this->config['sql'] = $sql;
		if($res)
		{
			$this->_saveLog(true);
			if($ROW = $res->fetch(PDO::FETCH_ASSOC))
			{	
				$res = null;
				return $ROW;
			}
			else
			{
				return 0;
			}
		}
		else
		{
			//$this->_saveLog(true);
			$this->_saveLog(false);
			return false;
		}
	}
	
	public function select()
	{
		$sql = $this->config['sql'];
		if($this->config['param'] && is_array($this->config['param']))
		{
			$res = $this->db->prepare($this->config['sql']);
			foreach($this->config['param'] as $item)
			{
				@$res->bindValue($item['name'], ($item['type'] == PDO::PARAM_INT ? (int)$item['value'] : $item['value']), ($item['type'] ? $item['type'] : PDO::PARAM_STR));
				$sql = str_replace(rtrim($item['name']),"'".$item['value']."'", $sql);		
			}
			$res->execute();
		}
		else
		{
			$res = $this->db->query($this->config['sql']);	
		}
		$this->config['sql'] = $sql;
		if($res)
		{
			$this->_saveLog(true);
			if($ROWS = $res->fetchAll(PDO::FETCH_ASSOC))
			{	
				$res = null;
				return $ROWS;
			}
			else
			{
				return 0;
			}
		}
		else
		{
			//$this->_saveLog(true);
			$this->_saveLog(false);
			return false;
		}
	}
	
	public function getLastSql()
	{
		return $this->config['sql'];
	}
	
	public function getSqlError()
	{
		return $this->db->errorInfo();
	}
	
	public function startTrans()//开始事务
	{
		$this->db->beginTransaction();
	}
	
	public function commit() //提交事务
	{
		
		$this->db->commit();
	}
	
	public function rollback()//回滚事务
	{	
		return $this->db->rollBack();
	}
	
	public function escape_string($str)
	{
		return str_replace("'", "\'", $str);
	}
	
	protected function _saveLog($isSuccess)//保存日志
	{
		if($this->debug)
		{
			if($this->debug_type == 'mongoDB')
			{
				$mongoDB = mongoDB();
				$insertData = array(
					'db_name'	=>	$_SESSION['PREFIX'],
					'time'		=>	time(),
					'sql_str'	=>	$this->config['sql'],
				);
				$mongoDB->insert('mysql_log', $insertData);
				if(!$isSuccess)
				{
					$insertData = array(
						'db_name'	=>	$_SESSION['PREFIX'],
						'time'		=>	time(),
						'sql_str'	=>	$this->config['sql'],
						'error_str' =>	$pdoError[2],
					);
					$mongoDB->insert('mysql_log_error', $insertData);
				}
			}
			else
			{
				$file_url = $GLOBALS['JQ_SYSTEM']['APP_ROOT'].'/log/'.$this->config['MSSQL_DB'].'/'.date("Ymd").'.txt';
				$file = fopen($file_url, "a");
				if (is_writable($file_url))
				{
					$str = "--".$this->config['sql'];
					fwrite($file,time().$str."\r\n");
				}
				fclose($file);
				if(!$isSuccess)
				{
					$file_url = $GLOBALS['JQ_SYSTEM']['APP_ROOT'].'/log/'.$this->config['MSSQL_DB'].'/'.date("Ymd").'_err.txt';
					$file = fopen($file_url, "a");
					if (is_writable($file_url))
					{
						
						$str = "--".$this->config['sql'];
						$pdoError = $this->db->errorInfo();
						if(is_array($pdoError))
						{
							$this->config['sqlError'] = $pdoError[2];
							$str .= "\r\n".$pdoError[2];
						}
						fwrite($file,time().$str."\r\n");
					}
					fclose($file);
				}	
			}	
		}
	}
	
	private function str_replace_once($needle, $replace, $haystack) 
	{
		$pos = strpos($haystack, $needle);
		if ($pos === false) {
			return $haystack;
		}
		return substr_replace($haystack, $replace, $pos, strlen($needle));
	}
}
?>