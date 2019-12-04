<?
class deptModelClass extends CommonModelClass
{
	public function __construct(){
		parent::__construct();
	}
	
	/**
	 * 列表
	 */
	function getList($params){
		$params = array_map(function($value){return htmlspecialchars(trim($value));}, $params);
		$model = D();
		
		$page 	= $params['pageIndex'];
        $limit 	= $params['pageSize'];
		$page1 	= $page*$limit;
		
		$sqlParam = array(
						array('name' => ':user_no', 'value' => '%'.$params['user_no']."%", 'type' => PDO::PARAM_STR),
					);
						
		$where = " WHERE 1=1 ";
		if(!empty($params['user_no'])){
			$where .= " AND (user_no like :user_no)";
		}
		
		$sql = "SELECT * FROM ". ' dept ';
		$sql .= $where;
		$sql .= ' ORDER BY id ASC ';
		$result = $model->query($sql, $sqlParam)->limitPage($page1, $limit)->select();
		//print_r($model->getLastSql());
		return $result;
		
		$sql = "select count(1) as count from " . 'dept AS a' . $where;
		$count = $model->query($sql)->find();
		//print_r($model->getLastSql());
		
		return array(
			"code" => "0",
            "msg" =>"",
            "total" =>$count['count'],
            "data" =>$result
		);
	}
	
	/**
	 * 添加 - 保存
	 */
	function add($params){
		if(!is_array($params)){
			return array();
		}
		$model = D();
		// 安全过滤
		$params = array_map(function($value){return htmlspecialchars(trim($value));}, $params);
		// 数据集
		$data = array();
		$data['dept_no'] 	= '';
		$data['dept_name'] 	= $params['dept_name'];
		$data['parent_dept_no']	= $params['parent_dept_no'];
		// 构造Sql结构
		$sqlParam = array();
		foreach($data as $key=>$value){
			$sqlParam[] = array('name' => ':'.$key, 'value' => $value);
		}
		
		$fieldKeys = array_keys($data);
		$fields = '(';
		$fields .= implode(',', $fieldKeys);
		$fields .= ')';
		
		$fieldsValues = array_map(function($value){
			return ':'.$value;
			}, $fieldKeys);
		$fieldsValues = '('.implode(',', $fieldsValues).')';
		// Sql语句
		$sql = "insert into ". 'dept' . $fields . "
				values " . $fieldsValues;
		if(empty($data['parent_dept_no'])){
			// 替换
			$sql = str_replace(':dept_no', "(select dept_no from (SELECT CONCAT('DEPT_',LPAD(RIGHT(IFNULL(MAX(dept_no),''),5)+1,5,'0')) as dept_no FROM ". 'dept' .") a)", $sql);
		}else{
			$sql = str_replace(':dept_no', "(select dept_no from (SELECT CONCAT('". $data['parent_dept_no'] ."_',LPAD(RIGHT(IFNULL(MAX(dept_no),''),5)+1,5,'0')) as dept_no FROM ". 'dept'. " where parent_dept_no='". $data['parent_dept_no'] ."' " .") a)", $sql);
		}
		
		// 执行
		$result = $model->execute($sql, $sqlParam);
		//echo $model->getLastSql();exit;
		
		if($result){
			return array("code"=>"ok","msg"=>"操作成功");
		}else{
			return array("code"=>"error","msg"=>"操作失败");
		}
	}
	
	/**
	 * 编辑 - 保存
	 */
	function edit($params){
		if(!is_array($params)){
			return array();
		}
		$model = D();
		// 安全过滤
		$params = array_map(function($value){return htmlspecialchars(trim($value));}, $params);
		// 数据集
		$data = array();
		$data['dept_no'] 	= '';
		$data['dept_name'] 	= $params['dept_name'];
		$data['parent_dept_no']	= $params['parent_dept_no'];
		
		// 构造Sql结构
		$sqlParam = array();
		foreach($data as $key=>$value){
			$sqlParam[] = array('name' => ':'.$key, 'value' => $value, 'type' => PDO::PARAM_STR);
		}
		$sqlParam[] = array('name' => ':id', 'value' => intval($params['id']), 'type' => PDO::PARAM_STR);
		
		$fieldsSet = array_map(function($value){
			return $value. '=:' . $value;
			}, array_keys($data));
		$fieldsSet = implode(',', $fieldsSet);
		// Sql语句
		$sql = "update ". 'dept' . ' SET ' . $fieldsSet . ' WHERE id=:id ';
		
		$dept = $this->getById(intval($params['id']));
		
		/*****修改所属部门  查询是否有下级部门  如有 不可修改  20190724*****/
		if ($data['parent_dept_no'] != $dept['parent_dept_no']) {
			
			$check = $this -> getListByCondition('parent_dept_no = "'.$dept['dept_no'].'"');
		
			if ($check) {
				
				return array("code"=>"error","msg"=>"存在下级部门，不可修改所属部门");
			}
		}
		/***************************************************************/
		
		if($dept['parent_dept_no'] == $data['parent_dept_no']){
			$sql = str_replace(':dept_no', "'". $dept['dept_no'] ."'", $sql);
		}else{
			if(empty($data['parent_dept_no'])){
				// 替换
				$sql = str_replace(':dept_no', "(select dept_no from (SELECT CONCAT('DEPT_',LPAD(RIGHT(IFNULL(MAX(dept_no),''),5)+1,5,'0')) as dept_no FROM ". 'dept' .") a)", $sql);
			}else{
				$sql = str_replace(':dept_no', "(select dept_no from (SELECT CONCAT('". $data['parent_dept_no'] ."_',LPAD(RIGHT(IFNULL(MAX(dept_no),''),5)+1,5,'0')) as dept_no FROM ". 'dept'. " where parent_dept_no='". $data['parent_dept_no'] ."' " .") a)", $sql);
			}
		}
		// 执行
		$result = $model->execute($sql, $sqlParam);
		//echo $model->getLastSql();exit;
		
		if($result !== false){
			return array("code"=>"ok","msg"=>"操作成功");
		}else{
			return array("code"=>"error","msg"=>"操作失败");
		}
	}
	
	/**
	 * 查询单条记录
	 */
	function getById($id){
		$model = D();
		$sqlParam = array(
			array('name' => ':id', 'value' => $id, 'type' => PDO::PARAM_STR),
		);
		$sql ="select * from ". 'dept' ." where id=:id ORDER BY id ASC ";
		$result = $model->query($sql,$sqlParam)->find();
		return $result;
	}
	/**
	 * 查询多条记录
	 */
	function getListByCondition($where, $fields='*'){
		$model = D();
		$sql ="select " . $fields ." from ". 'dept' ." where ".$where;
		$result = $model->query($sql)->select();
		return $result;
	}
	/**
	 * 删除
	 */
	function del($ids){
		$model = D();
		if(strpos($ids, ',') !== false){
			$where = " id in ('".str_replace(',', "','", $ids)."') ";
		}else{
			$where = ' id= '.$ids;
		}
		$deleteList = $this->getListByCondition($where);
		if(!empty($deleteList)){
			$delIds = '';
			foreach($deleteList as $vo){
				if(empty($vo['parent_dept_no'])){ // 有下级
					$delIds .= $sep . "'".$vo['dept_no']."'";
					$sep = ',';
				}
			}
		}
		$sql = "DELETE FROM " . 'dept' . " WHERE " . $where;
		$rs1 = $model->execute($sql);
		$sql = "DELETE FROM " . 'dept' . " WHERE " . " parent_dept_no in(". $delIds .") ";
		$rs2 = $model->execute($sql);
		if($rs1!==false && $res2!==false){
			return array("code"=>"ok","msg"=>"操作成功");
		}else{
			return array("code"=>"error","msg"=>"操作失败");
		}
	}
	
}
