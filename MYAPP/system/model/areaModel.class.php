<?
class areaModelClass extends CommonModelClass
{
	public function __construct(){
		parent::__construct();
	}
	
	function findByNo($area_no){
		$model = D();
		$sql = "SELECT id,area_no,area_name FROM ". 'wh_area' . " where area_no='".$area_no . "'";
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
		$data['area_name'] = $params['area_name'];
		
		if (empty($data['area_name'])) {
			
			return array('code' => 'error', 'msg' => '区域名称信息不能为空');
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
		$sql = "UPDATE ". 'wh_area' . ' SET ' . $fieldsSet . ' WHERE id=:id ';
		// 执行
		$result = $model->execute($sql, $sqlParam);
		if($result){
			return array("code"=>"ok","msg"=>"操作成功");
		}else{
			return array("code"=>"error","msg"=>"操作失败");
		}
	}
	
	// 删除
	function delArea($area_no){
		$model = D();
		$sql = "SELECT count(1) AS count FROM ". 'wh' . " where area_no='".$area_no . "'";
		$rs = $model->query($sql)->find();
		if($rs['count']>0){
			return array("code"=>"error","msg"=>"有仓库信息，不能删除！");
		}
		
		// 删除仓库信息
		$sql = "DELETE FROM " . 'wh_area' . " WHERE area_no='" . $area_no . "'";
		if($model->execute($sql, $sqlParam)){
			return array("code"=>"ok","msg"=>"删除成功");
		}else{
			return array("code"=>"error","msg"=>"删除仓库失败");
		}
		
	}
}
