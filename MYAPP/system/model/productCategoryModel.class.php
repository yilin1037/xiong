<?
class productCategoryModelClass extends CommonModelClass
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
						array('name' => ':category_no', 'value' => '%'.$params['category_no']."%", 'type' => PDO::PARAM_STR),
						array('name' => ':category_name', 'value' => strtoupper($params['category_name']), 'type' => PDO::PARAM_STR),
					);
						
		$where = " WHERE 1=1 ";
		if(!empty($params['category_no'])){
			$where .= " AND category_no like :category_no";
		}
		if(!empty($params['category_name'])){
			$where .= " AND category_name like :category_name";
		}
		
		$sql = "SELECT id,category_no,category_name FROM ". 'product_category' . $where . " ORDER BY id ASC";
		$result = $model->query($sql, $sqlParam)->limitPage($page1, $limit)->select();
		
		$sql = "SELECT count(1) AS count FROM " . 'product_category' . $where;
		$count = $model->query($sql)->find();
		//print_r($model->getLastSql());exit;
		return array(
			"code" => "0",
            "msg" =>"",
            "total" =>$count['count'],
            "data" =>$result
		);
	}
	function getList111(){
		$model = D();
		$sqlParam = array();
		$where = ' WHERE 1=1 ';
		$sql = "SELECT id,category_no,category_name from ". 'product_category' . $where . " ORDER BY id ASC ";
		$result = $model->query($sql, $sqlParam)->select();
		if(empty($result)){
			$result = array();
		}
		return $result;
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
		$data['category_no']	= $params['category_no'];
		$data['category_name']	= $params['category_name'];
		
		if($data['category_no'] == '' || $data['category_name'] == ''){
			return array("code"=>"error","msg"=>"分类编号与分类名称不能为空");
		}
		
		// 构造Sql结构
		$sqlParam = array();
		foreach($data as $key=>$value){
			$sqlParam[] = array('name' => ':'.$key, 'value' => $value, 'type' => PDO::PARAM_STR);
		}
		
		$tmp = $model->query("select id from " .'product_category'. " where category_no = :category_no",$sqlParam)->find();
		$temp = $model->query("select id from " .'product_category'. " where category_name =:category_name",$sqlParam)->find();
		
		if(!empty($tmp) || !empty($temp)){
			return array("code"=>"error","msg"=>"分类编号与分类名称重复");
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
		$sql = "INSERT INTO ". 'product_category' . $fields . "
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
		$data['category_no']	= $params['category_no'];
		$data['category_name']	= $params['category_name'];
		
		if (empty($data['category_name'])) {
			return array('code' => 'error', 'msg' => '分类名称不能为空');
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
		$sql = "UPDATE ". 'product_category' . ' SET ' . $fieldsSet . ' WHERE id=:id ';
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
		$sql = "DELETE FROM " . 'product_category' . " WHERE id=" . $id;
		if($model->execute($sql, $sqlParam)){
			return array("code"=>"ok","msg"=>"操作成功");
		}else{
			return array("code"=>"error","msg"=>"操作失败");
		}
	}
	
	/**
	 * 获取列表
	 * @return array
	 */
	function getProductList($params){
		$params = array_map(function($value){return htmlspecialchars(trim($value));}, $params);
		$model = D();
		
		$sqlParam = array(
						array('name' => ':category_no', 'value' => '%'.$params['category_no']."%", 'type' => PDO::PARAM_STR),
						array('name' => ':category_name', 'value' => strtoupper($params['category_name']), 'type' => PDO::PARAM_STR),
					);
						
		$where = " WHERE 1=1 ";
		if(!empty($params['category_no'])){
			$where .= " AND category_no like :category_no";
		}
		if(!empty($params['category_name'])){
			$where .= " AND category_name like :category_name";
		}
		
		$sql = "SELECT id,category_no,category_name FROM ". 'product_category' . $where . " ORDER BY id ASC";
		$result = $model->query($sql, $sqlParam)->select();
		
		$sql = "SELECT count(1) AS count FROM " . 'product_category' . $where;
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
	 * 获取商品分类搜索列表
	 * @return array
	 */
	function getSearchList($params){
		$params = array_map(function($value){return htmlspecialchars(trim($value));}, $params);
		$model = D();
		
		$sqlParam = array(
						array('name' => ':category_no', 'value' => '%'.$params['category_no']."%", 'type' => PDO::PARAM_STR),
						array('name' => ':category_name', 'value' => strtoupper($params['category_name']), 'type' => PDO::PARAM_STR),
					);
		
		$where = " WHERE 1=1 ";
		if(!empty($params['category_no'])){
			$where .= " AND category_no like :category_no";
		}
		if(!empty($params['category_name'])){
			$where .= " AND category_name like :category_name";
		}
		
		$sql = "SELECT id,category_no,category_name FROM ". 'product_category' . $where . " ORDER BY id ASC";
		$res = $model->query($sql, $sqlParam)->select();
		
		if(is_array($res)){
			$result[] = array('id' => '', 'category_no' => 'all', 'category_name' => '');
			foreach($res as $val){
				$result[] = $val;
			}
		}
		
		$sql = "SELECT count(1) AS count FROM " . 'product_category' . $where;
		$count = $model->query($sql)->find();
		//print_r($model->getLastSql());exit;
		return array(
			"code" => "0",
            "msg" =>"",
            "total" =>$count['count'],
            "data" =>$result
		);
	}
	
}
