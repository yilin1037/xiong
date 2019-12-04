<?
class whModelClass extends CommonModelClass
{
	public function __construct(){
		parent::__construct();
	}
	
	/**
	 * 获取列表
	 * @return array
	 */
	function getList($params){
		$params = array_map(function($value){return htmlspecialchars(trim($value));}, $params);
		$model = D();
		
		$page 	= $params['pageIndex'];
        $limit 	= $params['pageSize'];
		$page1 	= $page*$limit;
		
		$sqlParam = array(
						array('name' => ':area_no', 'value' => '%'.$params['area_no']."%", 'type' => PDO::PARAM_STR),
						array('name' => ':wh_no', 'value' => '%'.$params['wh_no']."%", 'type' => PDO::PARAM_STR),
						array('name' => ':wh_name', 'value' => '%'.$params['wh_name']."%", 'type' => PDO::PARAM_STR),
					);
						
		$where = " WHERE 1=1 ";
		if(!empty($params['area_no'])){
			$where .= " AND area_no like :area_no";
		}
		if(!empty($params['wh_no'])){
			$where .= " AND wh_no like :wh_no";
		}
		if(!empty($params['wh_name'])){
			$where .= " AND wh_name like :wh_name";
		}
		
		$sql = "SELECT id,area_no,wh_no,wh_name FROM ". 'wh' . $where . " ORDER BY id ASC";
		$result = $model->query($sql, $sqlParam)->limitPage($page1, $limit)->select();
		
		$sql = "SELECT count(1) AS count FROM " . 'wh' . $where;
		$count = $model->query($sql)->find();
		//print_r($model->getLastSql());exit;
		return array(
			"code" => "0",
            "msg" =>"",
            "total" =>$count['count'],
            "data" =>$result
		);
	}
	
	/**
	 * 添加
	 */
	function add($params){
		if(!is_array($params)){
			return array();
		}
		$model = D();
		// 安全过滤
		$params = array_map(function($value){return htmlspecialchars(trim($value));}, $params);
		
		$data = array();
		$data['area_no'] = $params['area_no'];
		$data['wh_no']	 = $params['wh_no'];
		$data['wh_name'] = $params['wh_name'];
		
		// 构造Sql结构
		$sqlParam = array();
		foreach($data as $key=>$value){
			$sqlParam[] = array('name' => ':'.$key, 'value' => $value, 'type' => PDO::PARAM_STR);
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
		$sql = "INSERT INTO ". 'wh' . $fields . "
				values " . $fieldsValues;
		// 执行
		$result = $model->execute($sql, $sqlParam);
		
		if($result){
			return array("code"=>"ok","msg"=>"操作成功");
		}else{
			return array("code"=>"error","msg"=>"操作失败");
		}
	}
	
	/**
	 * 保存
	 */
	function save($params){
		$model = D();
		if(!is_array($params)){
			return array();
		}
		$model = D();
		// 安全过滤
		$params = array_map(function($value){return htmlspecialchars(trim($value));}, $params);
		// 数据集
		$data = array();
		$data['area_no'] = $params['area_no'];
		$data['wh_no']	 = $params['wh_no'];
		$data['wh_name'] = $params['wh_name'];
		
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
		$sql = "UPDATE ". 'wh' . ' SET ' . $fieldsSet . ' WHERE id=:id ';
		// 执行
		$result = $model->execute($sql, $sqlParam);
		
		if($result){
			return array("code"=>"ok","msg"=>"操作成功");
		}else{
			return array("code"=>"error","msg"=>"操作失败");
		}
	}
	/**
	 * 删除
	 */
	function del($id){
		$model = D();
		$sqlParam = array(
			array('name' => ':id', 'value' => $ids, 'type' => PDO::PARAM_STR),
		);
		$sql = "DELETE FROM " . 'wh' . " WHERE id=" . $id;
		if($model->execute($sql, $sqlParam)){
			return array("code"=>"ok","msg"=>"操作成功");
		}else{
			return array("code"=>"error","msg"=>"操作失败");
		}
	}
	
	/**
	 * 添加区域
	 */
	function addArea($params){
		if(!is_array($params)){
			return array();
		}
		$model = D();
		// 安全过滤
		$params = array_map(function($value){return htmlspecialchars(trim($value));}, $params);
		
		$data = array();
		$data['area_no'] = $params['area_no'];
		$data['area_name'] = $params['area_name'];
		
		// 构造Sql结构
		$sqlParam = array();
		foreach($data as $key=>$value){
			$sqlParam[] = array('name' => ':'.$key, 'value' => $value, 'type' => PDO::PARAM_STR);
		}
		
		if (empty($data['area_no']) || empty($data['area_name'])) {
			
			return array('code' => 'error', 'msg' => '区域编号和区域名称信息不能为空');
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
		$sql = "INSERT INTO ". 'wh_area' . $fields . "
				values " . $fieldsValues;
		// 执行
		$result = $model->execute($sql, $sqlParam);
		
		if($result){
			return array("code"=>"ok","msg"=>"操作成功");
		}else{
			return array("code"=>"error","msg"=>"操作失败");
		}
	}
	
	
	
	
	
	// 仓库列表 - 以地区分组
	function whList(){
		$model = D();
		$topArr = array(0=>array('top_no'=>'area','top_name'=>'仓库列表'));
		//print_r($topArr);exit;
		$list = array();
		if($topArr){
			foreach($topArr as $vo){
				$data = array();
				$data['wh_no']	= $vo['top_no'];
				$data['wh_name']= $vo['top_name'];
				$data['pid']	= 0;
				array_push($list, $data);
				
				$sql = "SELECT id,area_no,area_name FROM ". 'wh_area' . " ORDER BY id ASC";
				$areaList = $model->query($sql)->select();
				//print_r($areaList);exit;
				if($areaList){
					foreach($areaList as $vo1){
						$data['wh_no']	= $vo1['area_no'];
						$data['wh_name']= $vo1['area_name'];
						$data['pid']	= 'area';
						array_push($list, $data);
						
						$sql = "SELECT id,area_no,wh_no,wh_name FROM ". 'wh' . " where area_no='".$vo1['area_no'] . "' ORDER BY id ASC";
						$result = $model->query($sql)->select();
						//echo $model->getLastSql();
						if($result){
							foreach($result as $vo2){
								$data = array();
								$data['wh_no']	= $vo2['wh_no'];
								$data['wh_name']= $vo2['wh_name'] . '-' . $vo2['wh_no'];
								$data['pid']	= $vo2['area_no'];
								array_push($list, $data);
							}
						}
					}
				}
			}
		}
		//print_r($list);
		return $list;
	}
	// 
	function findAreaByNo($area_no){
		$model = D();
		$sql = "SELECT id,area_no,area_name FROM ". 'wh_area' . " where area_no='".$area_no . "'";
		$result = $model->query($sql)->find();
		return $result;
	}
	function findWHByNo($wh_no){
		$model = D();
		$sql = "SELECT id,wh_no,wh_name FROM ". 'wh' . " where wh_no='".$wh_no . "'";
		$result = $model->query($sql)->find();
		return $result;
	}
	function edit($params){
		$model = D();
		if(!is_array($params)){
			return array();
		}
		$model = D();
		// 安全过滤
		$params = array_map(function($value){return htmlspecialchars(trim($value));}, $params);
		// 数据集
		$data = array();
		$data['wh_name'] = $params['wh_name'];
		
		if (empty($data['wh_name'])) {
			
			return array('code' => 'error', 'msg' => '仓库名称信息不能为空');
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
		$sql = "UPDATE ". 'wh' . ' SET ' . $fieldsSet . ' WHERE id=:id ';
		// 执行
		$result = $model->execute($sql, $sqlParam);
		
		if($result){
			return array("code"=>"ok","msg"=>"操作成功");
		}else{
			return array("code"=>"error","msg"=>"操作失败");
		}
	}
	
	// 删除
	function delWH($wh_no){
		$model = D();
		$sql = "SELECT count(1) AS count FROM ". 'wh_loc' . " where wh_no='".$wh_no . "'";
		
		$rs = $model->query($sql)->find();
		if($rs['count']>0){
			return array("code"=>"error","msg"=>"有货位信息，不能删除！");
		}
		
		$sql = "SELECT count(1) AS count FROM ". 'in_stock_orders' . " where wh_no='".$wh_no . "'";
		
		$res = $model -> query($sql) -> find();
		if ($res['count'] > 0) {
			
			return array('code' => 'error','msg' => '被入库订单引用，不能删除！');
		}
		
		$sql = "DELETE FROM " . 'wh' . " WHERE wh_no='" . $wh_no . "'";
		if($model->execute($sql, $sqlParam)){
			return array("code"=>"ok","msg"=>"删除成功");
		}else{
			return array("code"=>"error","msg"=>"删除失败");
		}
		
		return $result;
	}
	
	/**
	 * 获取全部列表
	 * @return array
	 */
	function getwhList($params){
		$params = array_map(function($value){return htmlspecialchars(trim($value));}, $params);
		$model = D();
		
		$sqlParam = array(
						array('name' => ':area_no', 'value' => '%'.$params['area_no']."%", 'type' => PDO::PARAM_STR),
						array('name' => ':wh_no', 'value' => '%'.$params['wh_no']."%", 'type' => PDO::PARAM_STR),
						array('name' => ':wh_name', 'value' => '%'.$params['wh_name']."%", 'type' => PDO::PARAM_STR),
					);
						
		$where = " WHERE 1=1 ";
		if(!empty($params['area_no'])){
			$where .= " AND area_no like :area_no";
		}
		if(!empty($params['wh_no'])){
			$where .= " AND wh_no like :wh_no";
		}
		if(!empty($params['wh_name'])){
			$where .= " AND wh_name like :wh_name";
		}
		
		$sql = "SELECT id,area_no,wh_no,wh_name FROM ". 'wh' . $where . " ORDER BY id ASC";
		$result = $model->query($sql, $sqlParam)->select();
		
		$sql = "SELECT count(1) AS count FROM " . 'wh' . $where;
		$count = $model->query($sql)->find();
		return array(
			"code" => "0",
            "msg" =>"",
            "total" =>$count['count'],
            "data" =>$result
		);
	}
}
