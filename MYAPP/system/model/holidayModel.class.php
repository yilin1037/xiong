<?php
class holidayModelClass extends CommonModelClass
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
		
		$sql = "select * from holiday";
		$result = $model->query($sql)->limitPage($page1, $limit)->select();
		if(!empty($result)){
			foreach($result as $key=>$vo){
				$result[$key]['province'] = $vo['province'].'/'.$vo['city'].$vo['district'] .' '. $vo['address'];
			}
		}
		
		$sql = "select count(1) as count from holiday ";
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
	 * 删除
	 */
	function del($id){
		
		$model = D();
		$sqlParam = array(
			array('name' => ':id', 'value' => $id, 'type' => PDO::PARAM_STR),
		);
		if(strpos($id, ',') !== false){
			$where = ' id in(:id) ';
		}else{
			$where = ' id=:id ';
		}
		$sql = "DELETE FROM holiday WHERE " . $where;
		$model->execute($sql, $sqlParam);
		if($model->execute($sql, $sqlParam)){
			return array("code"=>"ok","msg"=>"操作成功");
		}else{
			return array("code"=>"error","msg"=>"操作失败");
		}
	}
	
	/**
	 * 添加 - 保存
	 */
	function saveAdd($params){
		if(!is_array($params)){
			return array();
		}
		$model = D();
		// 安全过滤
		$params = array_map(function($value){return htmlspecialchars(trim($value));}, $params);
		// 数据集
		$data = array();
		$data['name'] = $params['name'];
		$data['begin_date'] = $params['begin_date'];
		$data['end_date'] = $params['end_date'];
		$data['multiple'] = $params['multiple'];
		
		if (empty($data['name']) || empty($data['begin_date']) || empty($data['end_date'])) {
			
			return array("code" => "error", "msg" => "节日名称、开始日期、结束日期信息不能为空");
		}
		
		if ($data['begin_date'] >= $data['end_date']) {
			
			return array("code" => "error", "msg" => "开始日期须小于结束日期");
		}
		
		if (!preg_match('/^((\d|([1-9]\d+))|(\d|([1-9]\d+))\.\d{1,2})$/', $data['multiple'])) {
			
			return array("code" => "error", "msg" => "收费倍数格式不正确");
		}
		//var_dump($data);exit;
		// 构造Sql结构
		$sqlParam = array();
		foreach($data as $key=>$value){
			$sqlParam[] = array('name' => ':'.$key, 'value' => $value, 'type' => PDO::PARAM_STR);
		}
		//print_r($data);exit;
		$fieldKeys = array_keys($data);
		$fields = '(';
		$fields .= implode(',', $fieldKeys);
		$fields .= ')';
		
		$fieldsValues = array_map(function($value){
			return ':'.$value;
			}, $fieldKeys);
		$fieldsValues = '('.implode(',', $fieldsValues).')';
		// Sql语句
		$sql = "insert into holiday" . $fields . "
				values " . $fieldsValues;
		//echo $sql;exit;
		// handing_no 系统自动生成
		/* $sql = str_replace(':handing_no', "(select handing_no from (SELECT CONCAT('HANDING_',LPAD(RIGHT(IFNULL(MAX(handing_no),''),5)+1,5,'0')) as handing_no FROM handing_team) a)", $sql); */
		
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
	 * 查询单条记录
	 */
	function getById($id){
		$model = D();
		$sqlParam = array(
			array('name' => ':id', 'value' => $id, 'type' => PDO::PARAM_STR),
		);
		$sql ="select * from holiday where id=:id";
		$result = $model->query($sql,$sqlParam)->find();
		return $result;
	}
	
	/**
	 * 编辑 - 保存
	 */
	function saveEdit($params){
		if(!is_array($params)){
			return array();
		}
		$model = D();
		// 安全过滤
		$params = array_map(function($value){return htmlspecialchars(trim($value));}, $params);
		// 数据集
		$data['name'] = $params['name'];
		$data['begin_date'] = $params['begin_date'];
		$data['end_date'] = $params['end_date'];
		$data['multiple'] = $params['multiple'];
		
		if (empty($data['name']) || empty($data['begin_date']) || empty($data['end_date'])) {
			
			return array("code" => "error", "msg" => "节日名称、开始日期、结束日期信息不能为空");
		}
		
		if ($data['begin_date'] >= $data['end_date']) {
			
			return array("code" => "error", "msg" => "开始日期须小于结束日期");
		}
		
		if (!preg_match('/^((\d|([1-9]\d+))|(\d|([1-9]\d+))\.\d{1,2})$/', $data['multiple'])) {
			
			return array("code" => "error", "msg" => "收费倍数格式不正确");
		}
		
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
		$sql = "update holiday SET " . $fieldsSet . ' WHERE id=:id ';
		// 执行
		$result = $model->execute($sql, $sqlParam);
		//echo $model->getLastSql();exit;
		
		if($result === false){
			return array("code"=>"error","msg"=>"操作失败");
		}else{
			return array("code"=>"ok","msg"=>"操作成功");
		}
	}
}
