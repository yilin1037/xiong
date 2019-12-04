<?
class moveOrderModelClass extends CommonModelClass
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
						array('name' => ':order_no', 'value' => $params['order_no'], 'type' => PDO::PARAM_STR),
						array('name' => ':dealer_no', 'value' => $params['dealer_no'], 'type' => PDO::PARAM_STR),
						array('name' => ':status', 'value' => $params['status'], 'type' => PDO::PARAM_STR),
					);
		
		$where = " WHERE 1=1 ";
		if(!empty($params['order_no'])){
			$where .= " AND (a.order_no=:order_no)";
		}
		if(!empty($params['dealer_no'])){
			$where .= " AND (a.dealer_no=:dealer_no)";
		}
		if($params['status'] != '' && $params['status'] != 'all'){
			$where .= " AND a.status=:status";
		}
		$dateBegin	= strtotime($params['date_begin']);
		$date_end 	= strtotime($params['date_end']);
		if(!empty($params['date_begin']) && empty($params['date_end'])){
			$where .= ' AND order_time> ' . ($dateBegin-1);
		}
		if(!empty($params['date_begin']) && !empty($params['date_end'])){
			$where .= ' AND (order_time BETWEEN '. ($dateBegin-1) . ' AND '. ($date_end+86400+1) .')';
		}
		if(empty($params['date_begin']) && !empty($params['date_end'])){
			$where .= ' AND order_time < ' . ($date_end+86400+1);
		}
		
		$sql = "select a.*,d.dealer_name from move_orders AS a";
		$sql .= ' LEFT JOIN dealer AS d ON a.dealer_no=d.dealer_no ';
		$sql .= $where;
		$sql .= ' ORDER BY a.id DESC ';
		
		$result = $model->query($sql, $sqlParam)->limitPage($page1, $limit)->select();
		//echo $model->getLastSql();exit;
		if(!empty($result)){
			foreach($result as $key=>$vo){
				$result[$key]['dealer_no']	= $vo['dealer_no'];
				$result[$key]['dealer_name']= $vo['dealer_name'];
				$result[$key]['order_time']	= date("Y-m-d",$vo['order_time']);
				
				if($vo['status'] ==0){
					$result[$key]['status'] = '未审核';
				}else{
					$result[$key]['status'] = '<font color="green">已审核</font>';
				}
			}
		}
		
		$sql = "select count(1) as count from move_orders a" . $where;
		$count = $model->query($sql,$sqlParam)->find();
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
		$data['order_no'] 	= '';
		$data['dealer_no'] 	= $params['dealer_no'];
		$data['order_time']	= time();
		$data['status'] 	= 0;
		$data['remark'] 	= $params['remark'];
		
		if (empty($data['dealer_no'])) {
			return array('code' => 'error', 'msg' => '经销商信息不能为空');
		}

		if (empty($data2[0]['prd_no'])) {
			return array('code' => 'error', 'msg' => '商品编号不能为空');
		}
		
		if (empty($data2[0]['add_unit_num'])) {
			return array('code' => 'error', 'msg' => '商品数量不能为空');
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
		$sql = "insert into move_orders" . $fields . "
				values " . $fieldsValues;
		
		// order_no 系统自动生成
		$sql = str_replace(':order_no', "(select order_no from (SELECT CONCAT('ORDER_',LPAD(RIGHT(IFNULL(MAX(order_no),''),5)+1,5,'0')) as order_no FROM move_orders) a)", $sql);
		
		
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
		$sql_row ="select order_no from move_orders where id=".$insert_id;
		$row = $model->query($sql_row)->find();
		$order_no = $row['order_no'];
		
		//保存 move_items 表数据
		if(!empty($data2)){
			foreach($data2 as $vo){
				if(!empty($vo['prd_no'])){
					$sql = "INSERT INTO move_items(order_no,prd_no,out_loc_no,in_loc_no,num,bat_no,remark) VALUES ( ";
					$sql .= "'". $order_no ."', ";
					$sql .= "'". $vo['prd_no'] ."', ";
					$sql .= "'". $vo['loc_no'] ."', ";
					$sql .= "'". $vo['in_loc_no'] ."', ";
					$sql .= "'". $vo['add_unit_num'] ."', ";
					$sql .= "'". $vo['bat_no'] ."', ";
					$sql .= "'". $vo['remark'] ."' ";
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
		$data['dealer_no'] 	= $params['dealer_no'];
		$data['remark'] 	= $params['remark'];
		
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
		$sql = "update move_orders SET " . $fieldsSet . ' WHERE id=:id ';
		// 执行
		$model->startTrans();
		$result = $model->execute($sql, $sqlParam);
		//echo $model->getLastSql();exit;
		if($result === false){
			$model->rollback();
		}
		
		//保存 move_items 表数据
		if(!empty($data2)){
			$sql_row ="select order_no from move_orders where id=".$params['id'];
			$row = $model->query($sql_row)->find();
			$order_no = $row['order_no'];
			// Delete All Data
			$sqlParam = array(
				array('name' => ':order_no', 'value' => $order_no, 'type' => PDO::PARAM_STR),
			);
			$sql = "DELETE FROM ". 'move_items'. " where order_no=:order_no ";
			$rs = $model->execute($sql, $sqlParam);
			if($rs === false){
				$model->rollback();
				return array("code"=>"error", "msg"=>"删除失败!");
			}
			foreach($data2 as $vo){
				if(!empty($vo['prd_no'])){
					$sql = "INSERT INTO ". 'move_items' ."(order_no,prd_no,out_loc_no,in_loc_no,num,bat_no,remark) VALUES (";
					$sql .= " '". $order_no ."', ";
					$sql .= " '". $vo['prd_no'] ."', ";
					$sql .= " '". $vo['loc_no'] ."', ";
					$sql .= " '". $vo['in_loc_no'] ."', ";
					$sql .= " '". $vo['add_unit_num'] ."', ";
					$sql .= " '". $vo['bat_no'] ."', ";
					$sql .= " '". $vo['remark'] ."' ";
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
		$sql ="select o.*,d.dealer_name from move_orders o left join dealer d on o.dealer_no=d.dealer_no where o.id=:id";
		$result = $model->query($sql,$sqlParam)->find();
		//print_r($result);exit;
		
		return $result;
	}
	
	/**
	 * 列表
	 */
	function getItemList($params){
		$params = array_map(function($value){return htmlspecialchars(trim($value));}, $params);
		$model = D();
		
		$page 	= $params['pageIndex'];
        $limit 	= $params['pageSize'];
		$page1 	= $page*$limit;
		
		$sql ="select dealer_no from move_orders where order_no='". $params['order_no'] ."' ";;
		$order = $model->query($sql)->find();
		
		$sql = "select a.*,p.prd_name,p.spec,p.unit_name,p.box_rate,p.unit_name1,p.unit_rate1 from move_items a left join product p on a.prd_no=p.prd_no where order_no='". $params['order_no'] ."' ";
		$result = $model->query($sql, $sqlParam)->limitPage($page1, $limit)->select();
		//echo $model->getLastSql();exit;
		if(!empty($result)){
			// LOC
			$loc_no = array();
			foreach($result as $key=>$vo){
				if(!empty($vo['out_loc_no'])){
					$loc_no[] = $vo['out_loc_no'];
				}
				if(!empty($vo['in_loc_no'])){
					$loc_no[] = $vo['in_loc_no'];
				}
			}
			$loc_no = array_unique($loc_no);
			foreach($loc_no as $vo){
				$loc_no_in .= $sep . "'". $vo ."'";
				$sep = ',';
			}
			if(!empty($loc_no_in)){
				$sql_loc = 'SELECT loc_no,loc_name,wh_no FROM ' . 'wh_loc' . ' WHERE loc_no in('. $loc_no_in .') ';
				$locList = $model->query($sql_loc)->select();
				$locData = array();
				$whData = array();
				if(!empty($locList)){
					foreach($locList as $vo){
						$locData[$vo['loc_no']] = $vo['loc_name'];
						$whData[$vo['loc_no']] 	= $vo['wh_no'];
					}
				}
				unset($locList);
			}
				
			foreach($result as $key=>$vo){	
				$box_rate = floatval($vo['box_rate']);
				if(!empty($vo['bat_no'])){
					$sql  = "select unit_num from product_qty where ";
					$sql .= " prd_no ='". $vo['prd_no'] ."' ";
					$sql .= " and dealer_no ='". $order['dealer_no'] ."' ";
					$sql .= " and bat_no ='". $vo['bat_no'] ."' ";
					$rs = $model->query($sql)->find();
				}else{
					$sql  = "select sum(unit_num) as unit_num from product_qty where ";
					$sql .= " prd_no ='". $vo['prd_no'] ."' ";
					$sql .= " and dealer_no ='". $order['dealer_no'] ."' ";
					$rs = $model->query($sql)->find();
				}
				$result[$key]['unit_num']	= floatval($rs['unit_num']);
				
				$result[$key]['add_unit_num']	= floatval($vo['num']);
				if($vo['unit_rate1'] > 0){
					$result[$key]['add_unit_num1'] 	= $result[$key]['add_unit_num'] / $vo['unit_rate1'];
				}
				//$result[$key]['is_bat']		= $vo['is_bat'];
				$result[$key]['bat_no']		= $vo['bat_no'];
				//$result[$key]['is_gift']	= $vo['is_gift'];
				$result[$key]['remark']		= $vo['remark'];
				if($vo['box_rate']){
					$result[$key]['add_box_num'] 	= $result[$key]['add_unit_num'] / $vo['box_rate'];
				}
				
				$result[$key]['wh_no']			= $whData[$vo['out_loc_no']];
				$result[$key]['out_loc_no']		= $vo['out_loc_no'];
				$result[$key]['loc_no']			= $vo['out_loc_no'];
				$result[$key]['loc_name']		= $locData[$vo['out_loc_no']];
				$result[$key]['in_loc_no']		= $vo['in_loc_no'];
				$result[$key]['in_loc_no_name']	= $locData[$vo['in_loc_no']];
			}
		}
		
		$sql = "select count(1) as count from move_items" . $where;
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
	function del($ids){
		$model = D();
		$sqlParam = array(
			array('name' => ':id', 'value' => $ids, 'type' => PDO::PARAM_STR),
		);
		$where = ' WHERE status=0  ';
		if(strpos($ids, ',') !== false){
			$ids = explode(',', $ids);
			$ids = implode(',', $ids);
			$where .= ' AND id in('.$ids.') ';
		}else{
			$where .= ' AND id=:id ';
		}
		$sql_list ="SELECT order_no FROM ". 'move_orders'. $where;
		$list = $model->query($sql_list, $sqlParam)->select();
		if(!empty($list)){
			$model->startTrans();
			$sql = "DELETE FROM ". 'move_orders'. $where;
			$rs = $model->execute($sql, $sqlParam);
			if($rs !== false){
				$in = "";
				foreach($list as $vo){
					$in .= $sep . "'" . $vo['order_no']. "'";
					$sep = ',';
				}
				// 删除订单产品
				$where = ' WHERE order_no in('. $in .') ';
				$sql = "DELETE FROM ". 'move_items'. $where;
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
	function auditOk($id){
		$model = D();
		$sqlParam = array(
			array('name' => ':id', 'value' => $id, 'type' => PDO::PARAM_STR),
		);
		if(strpos($id, ',') !== false){
			$where = ' id in(:id) ';
		}else{
			$where = ' id=:id ';
		}
		
		$sql = "UPDATE move_orders SET status = 1 WHERE " . $where;

		if($model->execute($sql, $sqlParam)){
			return array("code"=>"ok","msg"=>"审核成功");
		}else{
			return array("code"=>"error","msg"=>"审核失败");
		}
	}
	
	/**
	 * 取消审核
	 */
	function auditCancel($id){
		$model = D();
		$sqlParam = array(
			array('name' => ':id', 'value' => $id, 'type' => PDO::PARAM_STR),
		);
		if(strpos($id, ',') !== false){
			$where = ' id in(:id) ';
		}else{
			$where = ' id=:id ';
		}
		
		$sql = "UPDATE move_orders SET status = 0 WHERE " . $where;
		if($model->execute($sql, $sqlParam)){
			return array("code"=>"ok","msg"=>"取消成功");
		}else{
			return array("code"=>"error","msg"=>"取消失败");
		}
	}
	
	/**
	 * 删除表 move_items 表 单条记录
	 */
	function delItem($ids){
		$model = D();
		$sqlParam = array(
			array('name' => ':id', 'value' => $ids, 'type' => PDO::PARAM_STR),
		);
		$where = ' WHERE 1=1 ';
		if(strpos($ids, ',') !== false){
			$ids = explode(',', $ids);
			$ids = implode(',', $ids);
			$where .= ' AND id in('.$ids.') ';
		}else{
			$where .= ' AND id=:id ';
		}
		$sql = "DELETE FROM move_items". $where;
		if($model->execute($sql, $sqlParam)){
			return array("code"=>"ok", "msg"=>"操作成功");
		}else{
			return array("code"=>"error", "msg"=>"操作失败");
		}
	}
	
	// 获取仓库货位List
	function getWhLocList($request){
		$model = D();
		$wh_no 	= $request['wh_no'];
		$page 	= $request['pageIndex'];
        $limit 	= $request['pageSize'];
        $key 	= $request['key'];
		$where = " where wh_no='". $wh_no ."' ";
		if(!empty($key)){
			$where .= " AND loc_name like '%". $key ."%' ";
		}
		$page1 	= $page*$limit;
		$sql = "select loc_no,loc_name from wh_loc ". $where;
//echo $sql;die;
		$result = $model->query($sql)->limitPage($page1, $limit)->select();
		$sql = "select count(1) as count from wh_loc" . $where;
		$count = $model->query($sql)->find();
		//print_r($model->getLastSql());
		//var_dump($result);die;
		return array(
			"code" => "0",
            "msg" =>"",
            "total" =>$count['count'],
            "data" =>$result
		);
	}
}
