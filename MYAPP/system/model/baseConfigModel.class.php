<?php
class baseConfigModelClass extends CommonModelClass
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
		
		$sql = "select configKey,configValue from base_config where type = 'baseConfig'";
		$result = $model->query($sql)->limitPage($page1, $limit)->select();
		
		if (!empty($result)) {
			
			$res = array();
			foreach ($result as $k =>$v) {
				
				switch ($v['configKey']) {
					case "tidEndHour":
						$result[$k]['show'] = '当日订单截止时间';
					break;
					default:
					
				}
			}
		}
		//echo '<pre />';var_dump($res);exit;
		$count = count($result);
		//print_r($model->getLastSql());
		return array(
			"code" => "0",
            "msg" =>"",
            "total" =>$count,
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
		//print_r($params);exit;
		$model = D();
		// 安全过滤
		foreach($params as $k => $v){
			
			$params[$k] = array_map(function($value){return htmlspecialchars(trim($value));}, $v);
		}
		// 数据集
		$data = array_combine ($params['configKey'],$params['configValue']);
		//print_r($data);exit;
		//var_dump($data);exit;
		$model->startTrans();
		foreach ($data as $k => $v) {
			
			//查询是否有此条记录
			$sqlParam[] = array('name' => ':configValue', 'value' => $v, 'type' => PDO::PARAM_STR);
			$sqlParam[] = array('name' => ':configKey', 'value' => $k, 'type' => PDO::PARAM_STR);
			//$r = $model -> execute('select configKey from base_config where configKey=:configKey',$sqlParam);
			//var_dump($r);exit;
			if ( $model -> execute('select configKey from base_config where configKey=:configKey',$sqlParam) ) {
				//echo 123;exit;
				$sql = 'update base_config SET configValue=:configValue WHERE configKey=:configKey and type="baseConfig"';
			} else {
				//echo 123;exit;
				$sql = 'insert into base_config (configKey,configValue,type) VALUES (:configKey,:configValue,"baseConfig")';
			}
			
			if (!$result = $model->execute($sql, $sqlParam)) {
				//var_dump($result);
				$model->rollback();
				return array("code" => "error", "msg" => "修改失败");	
			}
		}
		$model->commit();
		return array("code" => "ok", "msg" => "操作成功");
	}
}
