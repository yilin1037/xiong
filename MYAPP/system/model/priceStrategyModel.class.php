<?
class priceStrategyModelClass extends CommonModelClass
{
	public function __construct(){
		parent::__construct();
	}
	
	/**
	 * 列表
	 */
	function getComboboxList(){
		$model = D();
		
		$result = array();
		$sql_row ="SELECT id,price_name FROM ". TABLE('price_strategy') ." order by id ";
		$row = $model->query($sql_row)->select();
		if($row){
			foreach($row as $list){
				$result[] = array('id' => $list['id'], 'text' => $list['price_name']);
			}
		}
		
		return $result;
	}
	
	function getList($params){
		$params = array_map(function($value){return htmlspecialchars(trim($value));}, $params);
		$model = D();
		
		$page 	= $params['pageIndex'];
        $limit 	= $params['pageSize'];
		$page1 	= $page*$limit;
		
		$sqlParam = array(
						array('name' => ':price_no', 'value' => $params['price_no'], 'type' => PDO::PARAM_STR),
						array('name' => ':price_name', 'value' => '%'.$params['price_name'].'%', 'type' => PDO::PARAM_STR),
					);
		
		$where = " WHERE 1=1 ";
		if(!empty($params['price_no'])){
			$where .= " AND (price_no=:price_no)";
		}
		if(!empty($params['price_name'])){
			$where .= " AND (price_name like :price_name)";
		}
		if(0){
		$dateBegin	= strtotime($params['date_begin']);
		$date_end 	= strtotime($params['date_end']);
		if(!empty($params['date_begin']) && empty($params['date_end'])){
			$where .= ' AND addtime> ' . ($dateBegin-1);
		}
		if(!empty($params['date_begin']) && !empty($params['date_end'])){
			$where .= ' AND (addtime BETWEEN '. ($dateBegin-1) . ' AND '. ($date_end+86400+1) .')';
		}
		if(empty($params['date_begin']) && !empty($params['date_end'])){
			$where .= ' AND addtime < ' . ($date_end+86400+1);
		}
		}
		
		$sql = "SELECT * FROM ". TABLE('price_strategy') .' ';
		$sql .= $where;
		$sql .= ' ORDER BY id DESC ';
		
		$result = $model->query($sql, $sqlParam)->limitPage($page1, $limit)->select();
		//echo $model->getLastSql();exit;
		if(!empty($result)){
			foreach($result as $key=>$vo){
				$result[$key]['addtime']	= date("Y-m-d", $vo['addtime']);
				$result[$key]['update_time']= date("Y-m-d", $vo['update_time']);
				if($vo['status'] ==0){
					$result[$key]['status'] = '<font color="red">禁用</font>';
				}else{
					$result[$key]['status'] = '启用';
				}
			}
		}
		
		$sql = "SELECT count(*) AS count FROM ".TABLE('price_strategy') . $where;
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
	function add($params, $data2){
		if(!is_array($params)){
			return array();
		}
		$model = D();
		// 安全过滤
		$params = array_map(function($value){return htmlspecialchars(trim($value));}, $params);
		// 数据集
		$data = array();
		$data['price_no'] 	= '';
		$data['price_name']	= $params['price_name'];
		$data['status'] 	= 1;
		$data['addtime']	= time();
		$data['update_time']= $data['addtime'];
		$data['remark'] 	= $params['remark'];
		
		
		if (empty($data['price_name'])) {
			return array('code' => 'error', 'msg' => '价格策略名称不能为空');
		}

		if (empty($data2[0]['prd_no'])) {
			return array('code' => 'error', 'msg' => '商品编号不能为空');
		}
		if (empty($data2[0]['unit_price'])) {
			return array('code' => 'error', 'msg' => '商品价格不能为空');
		}
		
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
		$sql = "INSERT INTO ". TABLE('price_strategy') . $fields . " VALUES " . $fieldsValues;
		
		// price_no 系统自动生成
		$sql = str_replace(':price_no', "(SELECT price_no FROM (SELECT CONCAT('ORDER_',LPAD(RIGHT(IFNULL(MAX(price_no),''),5)+1,5,'0')) as price_no FROM ". TABLE('price_strategy') .") a)", $sql);
		
		
		// 开启事务
		$model->startTrans();
		// 执行
		$result = $model->execute($sql, $sqlParam);
		//echo $model->getLastSql();exit;
		if(!$result){
			$model->rollback();
			return array("code"=>"error", "msg"=>"操作失败");
		}
		
		$insert_id = $model->lastInsertId();
		$sql_row ="SELECT price_no FROM ". TABLE('price_strategy') ." WHERE id=".$insert_id;
		$row = $model->query($sql_row)->find();
		$price_no = $row['price_no'];
		
		//保存 items 表数据
		if(!empty($data2)){
			foreach($data2 as $vo){
				if(!empty($vo['prd_no'])){
					$sql = "INSERT INTO ". TABLE('price_strategy_list') ."(price_no,prd_no,unit_price) VALUES ( ";
					$sql .= "'". $price_no ."', ";
					$sql .= "'". $vo['prd_no'] ."', ";
					$sql .= "'". $vo['unit_price'] ."' ";
					$sql .= " )";
					$model->execute($sql);
				}
			}
		}
		// 提交事务
		$model->commit();
		return array("code"=>"ok","msg"=>"操作成功");
	}
	
	/**
	 * 编辑 - 保存
	 */
	function edit($params, $data2){
		if(!is_array($params)){
			return array();
		}
		$model = D();
		// 安全过滤
		$params = array_map(function($value){return htmlspecialchars(trim($value));}, $params);
		// 数据集
		$data = array();
		$data['price_name']	= $params['price_name'];
		$data['update_time']= time();
		$data['remark'] 	= $params['remark'];
		
		if (empty($data['price_name'])) {
			return array('code' => 'error', 'msg' => '价格策略名称不能为空');
		}

		if (empty($data2[0]['prd_no'])) {
			return array('code' => 'error', 'msg' => '商品编号不能为空');
		}
		if (empty($data2[0]['unit_price'])) {
			return array('code' => 'error', 'msg' => '商品价格不能为空');
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
		$sql = "UPDATE ". TABLE('price_strategy') ." SET " . $fieldsSet . ' WHERE id=:id ';
		// 执行
		$model->startTrans();
		$result = $model->execute($sql, $sqlParam);
		if($result === false){
			$model->rollback();
		}
		
		//保存 move_items 表数据
		if(!empty($data2)){
			$sql_row ="SELECT price_no FROM ". TABLE('price_strategy') ." WHERE id=".$params['id'];
			$row = $model->query($sql_row)->find();
			$price_no = $row['price_no'];
			// Delete All Data
			$sqlParam = array(
				array('name' => ':price_no', 'value' => $price_no, 'type' => PDO::PARAM_STR),
			);
			$sql = "DELETE FROM ". TABLE('price_strategy_list') . " where price_no=:price_no ";
			$rs = $model->execute($sql, $sqlParam);
			if($rs === false){
				$model->rollback();
				return array("code"=>"error", "msg"=>"删除失败!");
			}
			foreach($data2 as $vo){
				if(!empty($vo['prd_no'])){
					$sql = "INSERT INTO ". TABLE('price_strategy_list') ."(price_no,prd_no,unit_price) VALUES (";
					$sql .= " '". $price_no ."', ";
					$sql .= " '". $vo['prd_no'] ."', ";
					$sql .= " '". $vo['unit_price'] ."' ";
					$sql .= ")";
					$rs = $model->execute($sql);
					if($rs === false){
						$model->rollback();
						return array("code"=>"error", "msg"=>"操作失败");
					}
				}
			}
		}
		$model->commit();
		return array("code"=>"ok", "msg"=>"操作成功");
	}
	
	/**
	 * 查询单条记录
	 */
	function getById($id){
		$model = D();
		$sqlParam = array(
			array('name' => ':id', 'value' => $id, 'type' => PDO::PARAM_STR),
		);
		$sql ="SELECT * FROM ". TABLE('price_strategy') ." WHERE id=:id";
		$result = $model->query($sql, $sqlParam)->find();
		return $result;
	}
	
	/**
	 * 列表
	 */
	function getItemList($params){
		$params = array_map(function($value){return htmlspecialchars(trim($value));}, $params);
		if(empty($params['price_no'])){return '';}
		$model = D();
		$sqlParam = array(
			array('name' => ':price_no', 'value' => $params['price_no'], 'type' => PDO::PARAM_STR),
		);
		
		$page 	= $params['pageIndex'];
        $limit 	= $params['pageSize'];
		$page1 	= $page*$limit;
		
		$sql = "SELECT a.*,p.prd_name,p.spec,p.unit_name,p.box_rate,p.unit_name1,p.unit_rate1 FROM ". TABLE('price_strategy_list') ." a ";
		$sql .= " LEFT JOIN product p ON a.prd_no=p.prd_no ";
		$sql .= " WHERE a.price_no=:price_no";
		$result = $model->query($sql, $sqlParam)->limitPage($page1, $limit)->select();
		//echo $model->getLastSql();exit;
		if(!empty($result)){
			foreach($result as $key=>$vo){	
				$box_rate = floatval($vo['box_rate']);
				$result[$key]['unit_price']	= floatval($vo['unit_price']);
			}
		}
		
		$sql = "SELECT count(1) as count FROM ".TABLE('price_strategy_list') . ' a ';
		$sql .= " LEFT JOIN product p ON a.prd_no=p.prd_no ";
		$sql .= " WHERE a.price_no=:price_no";
		$count = $model->query($sql, $sqlParam)->find();
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
	function del($ids){
		$model = D();
		$sqlParam = array(
			array('name' => ':status', 'value' => 0, 'type' => PDO::PARAM_INT),
			array('name' => ':id', 'value' => $ids, 'type' => PDO::PARAM_INT),
		);
		$where = ' WHERE status=:status ';
		if(strpos($ids, ',') !== false){
			$ids = explode(',', $ids);
			$ids = implode(',', $ids);
			$where .= ' AND id IN('.$ids.') ';
		}else{
			$where .= ' AND id=:id';
		}
		$sql_list ="SELECT price_no FROM ". TABLE('price_strategy'). $where;
		$list = $model->query($sql_list, $sqlParam)->select();
		if(!empty($list)){
			$model->startTrans();
			$sql = "DELETE FROM ". TABLE('price_strategy'). $where;
			$rs = $model->execute($sql);
			if($rs !== false){
				$IN = "";
				foreach($list as $vo){
					$IN .= $sep . "'" . $vo['price_no']. "'";
					$sep = ',';
				}
				// 删除订单产品
				$where = " WHERE price_no IN(". $IN .") ";
				$sql = "DELETE FROM ". TABLE('price_strategy_list'). $where;
				$rs = $model->execute($sql);
				if($rs !== false){
					$model->commit();
					return array("code"=>"ok", "msg"=>"操作成功");
				}
			}
			$model->rollback();
			return array("code"=>"error", "msg"=>"操作失败");
		}
	}
	
	/**
	 * 审核通过
	 */
	function auditOk($ids){
		$model = D();
		$sqlParam = array(
			array('name' => ':id', 'value' => $ids, 'type' => PDO::PARAM_INT),
		);
		$where = ' WHERE 1=1 ';
		if(strpos($ids, ',') !== false){
			$ids = explode(',', $ids);
			$where .= ' id IN( ';
			foreach($ids as $key=>$vo){
				$where .= $sep . ':id' . $key;
				$sep = ',';
				$sqlParam[] = array('name' => ':id'.$key, 'value' => $vo, 'type' => PDO::PARAM_INT);
			}
			$where .= ') ';
		}else{
			$where .= ' AND id=:id';
		}
		$sql = "UPDATE ". TABLE('price_strategy') ." SET status=1 " . $where;
		$rs = $model->execute($sql, $sqlParam);
		if($rs !== false){
			return array("code"=>"ok","msg"=>"审核成功");
		}else{
			return array("code"=>"error","msg"=>"审核失败");
		}
	}
	
	/**
	 * 取消审核
	 */
	function auditCancel($ids){
		$model = D();
		$sqlParam = array(
			array('name' => ':id', 'value' => $ids, 'type' => PDO::PARAM_INT),
		);
		$where = ' WHERE ';
		if(strpos($ids, ',') !== false){
			$ids = explode(',', $ids);
			//$ids = implode(',', $ids);
			//$where .= ' AND id IN('.$ids.') ';
			$where .= ' id IN( ';
			foreach($ids as $key=>$vo){
				$where .= $sep . ':id' . $key;
				$sep = ',';
				$sqlParam[] = array('name' => ':id'.$key, 'value' => $vo, 'type' => PDO::PARAM_INT);
			}
			$where .= ') ';
		}else{
			$where .= ' id=:id';
		}
		$sql = "UPDATE ". TABLE('price_strategy') ." SET status=0 " . $where;
		$rs = $model->execute($sql, $sqlParam);
		//echo $model->getLastSql();exit;
		if($rs !== false){
			return array("code"=>"ok","msg"=>"审核成功");
		}else{
			return array("code"=>"error","msg"=>"审核失败");
		}
	}
	
	/**
	 * 删除表身记录
	 */
	function delItem($ids){
		$model = D();
		$where = ' WHERE 1=1 ';
		if(strpos($ids, ',') !== false){
			$ids = explode(',', $ids);
			$ids = implode(',', $ids);
			$where .= ' AND id IN('.$ids.') ';
		}else{
			$where .= ' AND id='. intval($ids);
		}
		$sql = "DELETE FROM ". TABLE('price_strategy_list') . $where;
		$rs = $model->execute($sql, $sqlParam);
		if($rs !== false){
			return array("code"=>"ok", "msg"=>"操作成功");
		}else{
			return array("code"=>"error", "msg"=>"操作失败");
		}
	}
	
}
