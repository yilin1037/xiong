<?
class damageOrdersModelClass extends CommonModelClass
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

        $start_time=strtotime($params['start_time']);
        $end_time=strtotime($params['end_time']);

        if(!empty($params['order_time'])){
            $order_time=strtotime($params['order_time']);
            if(empty($params['start_time'])) {
                $start_time=$order_time;
            }
            if(empty($end_time)) {
                $end_time=$order_time+24*3600;
            }
        }


        $sqlParam = array(
						array('name' => ':order_no', 'value' => $params['order_no'], 'type' => PDO::PARAM_STR),
                        array('name' => ':dealer_name', 'value' => $params['dealer_name'], 'type' => PDO::PARAM_STR),
						array('name' => ':status', 'value' => $params['status'], 'type' => PDO::PARAM_STR),
                        array('name' => ':start_time', 'value' => $start_time , 'type' => PDO::PARAM_STR),
                        array('name' => ':end_time', 'value' => $end_time, 'type' => PDO::PARAM_STR),
					);
					
		$where = " WHERE 1=1 ";
		if(!empty($params['order_no'])){
			$where .= " AND (a.order_no=:order_no)";
		}

        if(!empty($params['dealer_name'])){
            $where .= " AND d.dealer_name LIKE '%".$params['dealer_name'] ."%'";
        }

		if($params['status'] != '' && $params['status'] != 'all'){
			$where .= " AND a.status=:status";
		}

        if(!empty($start_time)) {
            $where .= " AND order_time >=:start_time";
        }
        if(!empty($end_time)) {
            $where .= " AND order_time <=:end_time";
        }
		
		$sql = "select a.id,a.order_no,a.dealer_no,a.bill_no,a.wh_no,a.order_time,a.status,a.remark,d.dealer_name,w.wh_name from damage_orders AS a";
		$sql .= ' LEFT JOIN dealer AS d ON a.dealer_no=d.dealer_no ';
		$sql .= ' LEFT JOIN wh AS w ON a.wh_no=w.wh_no ';
		$sql .= $where;
		$sql .= ' ORDER BY a.id DESC ';
		
		$result = $model->query($sql, $sqlParam)->limitPage($page1, $limit)->select();
		//echo $model->getLastSql();exit;
		if(!empty($result)){
			
			foreach($result as $key=>$vo){
				
				$result[$key]['dealer_name'] = $vo['dealer_name'];
				$result[$key]['wh_name'] = $vo['wh_name'];
				$result[$key]['order_time'] = date("Y-m-d",$vo['order_time']);
				
				if($vo['status'] ==0){
					$result[$key]['status'] = '未审核';
				}else{
					$result[$key]['status'] = '已审核';
				}
			}
		}
		
		$sql = "select count(1) as count from damage_orders a" . $where;
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
	function saveAdd($params, $data2){
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
		$data['wh_no'] 	= $params['wh_no'];
		$data['bill_no'] 	= $params['bill_no'];
		$data['order_time'] = strtotime($params['order_time']);
		$data['status'] 	= $params['status'];
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
		
	    //echo $data['order_time'];exit;
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
		$sql = "insert into damage_orders" . $fields . "
				values " . $fieldsValues;
		
		// order_no 系统自动生成
		$sql = str_replace(':order_no', "(select order_no from (SELECT CONCAT('ORDER_',LPAD(RIGHT(IFNULL(MAX(order_no),''),5)+1,5,'0')) as order_no FROM damage_orders) a)", $sql);
		
		
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
		$sql_row ="select order_no from damage_orders where id=".$insert_id;
		$row = $model->query($sql_row)->find();
		$order_no = $row['order_no'];
		
		
		//保存 out_stock_items 表数据
		if(!empty($data2)){
			foreach($data2 as $vo){
				$sql = "insert into damage_items(order_no,prd_no,loc_no,num,bat_no,remark) values ('$order_no','".$vo['prd_no']."','".$vo['loc_no']."','". $vo['add_unit_num'] ."','". $vo['bat_no'] ."','". $vo['remark'] ."')";
				$model->execute($sql);
			}
		}
		// 提交事务
		$model->commit();
		return array("code"=>"ok","msg"=>"操作成功");
		
	}
	
	/**
	 * 编辑 - 保存
	 */
	function saveEdit($params, $data2){
		if(!is_array($params)){
			return array();
		}
		$model = D();
		// 安全过滤
		$params = array_map(function($value){return htmlspecialchars(trim($value));}, $params);
		// 数据集
		$data = array();
		//$data['order_no'] 	= $order_no;
		$data['dealer_no'] 	= $params['dealer_no'];
		$data['wh_no'] 	= $params['wh_no'];
		$data['bill_no'] 	= $params['bill_no'];
		$data['order_time'] = strtotime($params['order_time']);
		$data['status'] 	= $params['status'];
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
		$sql = "update damage_orders SET " . $fieldsSet . ' WHERE id=:id ';
		// 执行
		$model->startTrans();
		$result = $model->execute($sql, $sqlParam);
		//echo $model->getLastSql();exit;
		if($result === false){
			$model->rollback();
		}
		
		//保存 damage_items 表数据
		if(!empty($data2)){
			$sql_row ="select order_no from damage_orders where id=".$params['id'];
			$row = $model->query($sql_row)->find();
			$order_no = $row['order_no'];
			//print_r($data2);exit;
			foreach($data2 as $vo){
				if(empty($vo['id']) && !empty($vo['prd_no'])){
					$sql = "INSERT INTO ". 'damage_items' ."(order_no,prd_no,loc_no,num,bat_no,remark) VALUES (";
					$sql .= " '". $order_no ."', ";
					$sql .= " '". $vo['prd_no'] ."', ";
					$sql .= " '". $vo['loc_no'] ."', ";
					$sql .= " '". $vo['add_unit_num'] ."', ";
					$sql .= " '". $vo['bat_no'] ."', ";
					$sql .= " '". $vo['remark'] ."' ";
					$sql .= ")";
					$rs = $model->execute($sql);
					//echo $model->getLastSql();exit;
					if(!$rs){
						$model->rollback();
						return array("code"=>"error", "msg"=>"操作失败");
					}
				}
				if(intval($vo['id']) >0 && !empty($vo['prd_no'])){
					$sql = "UPDATE ". 'damage_items';
					$sql .= " SET order_no = '". $order_no ."', ";
					$sql .= " prd_no = '". $vo['prd_no'] ."', ";
					$sql .= " loc_no = '". $vo['loc_no'] ."', ";
					$sql .= " num = '". $vo['add_unit_num'] ."', ";
					$sql .= " bat_no = '". $vo['bat_no'] ."', ";
					$sql .= " remark = '". $vo['remark'] ."' ";
					$sql .= " WHERE id=". $vo['id'];
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
		$sql ="select o.*,d.dealer_name,w.wh_name from damage_orders o left join dealer d on o.dealer_no=d.dealer_no left join wh w on o.wh_no=w.wh_no where o.id=:id";
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
		
		$sql ="select dealer_no from damage_orders where order_no='". $params['order_no'] ."' ";
		$order = $model->query($sql)->find();
		
		$sql = "select a.*,p.prd_name,p.spec,p.unit_name,p.box_rate,p.unit_name1,p.unit_rate1 from damage_items a left join product p on a.prd_no=p.prd_no where order_no='". $params['order_no'] ."' ";
		$result = $model->query($sql, $sqlParam)->limitPage($page1, $limit)->select();
		//echo $model->getLastSql();exit;
		if(!empty($result)){
			if(1){
				// LOC
				$loc_no = array();
				foreach($result as $key=>$vo){
					if(!empty($vo['loc_no'])){
						$loc_no[] = $vo['loc_no'];
					}
				}
				$loc_no = array_unique($loc_no);
				foreach($loc_no as $vo){
					$loc_no_in .= $sep . "'". $vo ."'";
					$sep = ',';
				}
				if(!empty($loc_no_in)){
					$sql_loc = 'SELECT loc_no,loc_name FROM ' . 'wh_loc' . ' WHERE loc_no in('. $loc_no_in .') ';
					$locList = $model->query($sql_loc)->select();
					$locData = array();
					if(!empty($locList)){
						foreach($locList as $vo){
							$locData[$vo['loc_no']] = $vo['loc_name'];
						}
					}
					unset($locList);
				}
			}
			
			foreach($result as $key=>$vo){	
				$box_rate = floatval($vo['box_rate']);
				if(!empty($vo['bat_no'])){
					$sql  = "select loc_no from product_qty where ";
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
				$result[$key]['loc_no']	= $rs['loc_no'];
				$result[$key]['loc_name']	= $locData[$vo['loc_no']];
				$result[$key]['add_unit_num']	= floatval($vo['num']);
				$result[$key]['add_unit_num1'] 	= $result[$key]['add_unit_num'] * $vo['unit_rate1'];
				$result[$key]['bat_no']		= $vo['bat_no'];
				$result[$key]['remark']		= $vo['remark'];
				if($vo['box_rate']){
					$result[$key]['add_box_num'] 	= $result[$key]['add_unit_num'] / $vo['box_rate'];
				}
			}
		}
		
		$sql = "select count(1) as count from damage_items" . $where;
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
		$where = ' WHERE 1=1 AND status=0 ';
		if(strpos($id, ',') !== false){
			$id = explode(',', $id);
			$id = implode(',', $id);
			$where .= ' AND id in('.$id.') ';
		}else{
			$where .= ' AND id=:id ';
		}
		$sql_list ="SELECT order_no FROM ". 'damage_orders'. $where;
		$list = $model->query($sql_list, $sqlParam)->select();
		if(!empty($list)){
			$model->startTrans();
			$sql = "DELETE FROM ". 'damage_orders'. $where;
			$rs = $model->execute($sql, $sqlParam);
			if($rs !== false){
				$in = "";
				foreach($list as $vo){
					$in .= $sep . "'" . $vo['order_no']. "'";
					$sep = ',';
				}
				// 删除表身
				$where = ' WHERE order_no in('. $in .') ';
				$sql = "DELETE FROM ". 'damage_items'. $where;
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
	function examine($id){
		$model = D();
		$sqlParam = array(
			array('name' => ':id', 'value' => $id, 'type' => PDO::PARAM_STR),
		);
		if(strpos($id, ',') !== false){
			$where = ' id in(:id) ';
		}else{
			$where = ' id=:id ';
		}
		
		$sql = "UPDATE damage_orders SET status = 1 WHERE " . $where;
		$model->execute($sql, $sqlParam);
		if($model->execute($sql, $sqlParam)){
			return array("code"=>"ok","msg"=>"审核成功");
		}else{
			return array("code"=>"error","msg"=>"审核失败");
		}
	}
	
	/**
	 * 取消审核
	 */
	function qxea($id){
		$model = D();
		$sqlParam = array(
			array('name' => ':id', 'value' => $id, 'type' => PDO::PARAM_STR),
		);
		if(strpos($id, ',') !== false){
			$where = ' id in(:id) ';
		}else{
			$where = ' id=:id ';
		}
		
		$sql = "UPDATE damage_orders SET status = 0 WHERE " . $where;
		$model->execute($sql, $sqlParam);
		if($model->execute($sql, $sqlParam)){
			return array("code"=>"ok","msg"=>"取消成功");
		}else{
			return array("code"=>"error","msg"=>"取消失败");
		}
	}
	
	/**
	 * 删除表 stock_items 表 单条记录
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
		$sql = "DELETE FROM damage_items". $where;
		if($model->execute($sql, $sqlParam)){
			return array("code"=>"ok", "msg"=>"操作成功");
		}else{
			return array("code"=>"error", "msg"=>"操作失败");
		}
	}
}
