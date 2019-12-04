<?
class promotionModelClass extends CommonModelClass
{
	public function __construct(){
		parent::__construct();
	}
	
	/**
	 * 列表 - 组合满赠
	 */
	function getList($params){
		
		$params = array_map(function($value){return htmlspecialchars(trim($value));}, $params);
		$model = D();
		
		$page 	= $params['pageIndex'];
        $limit 	= $params['pageSize'];
		$page1 	= $page*$limit;
		
		$sortField = $params['sortField'];
		$sortOrder = $params['sortOrder'];
		
		if(!empty($sortField)){
			if ($sortOrder != "desc") $sortOrder = "asc";
			$order = " order by " . $sortField . " " . $sortOrder;
		}
		
		$sqlParam = array(
						array('name' => ':addtime', 'value' => strtotime($params['addtime']), 'type' => PDO::PARAM_STR),
						array('name' => ':begin_date', 'value' => strtotime($params['begin_date']), 'type' => PDO::PARAM_STR),
						array('name' => ':end_date', 'value' => strtotime($params['end_date']), 'type' => PDO::PARAM_STR),
						array('name' => ':sales_name', 'value' => $params['sales_name'], 'type' => PDO::PARAM_STR),
						array('name' => ':is_multiple', 'value' => $params['is_multiple'], 'type' => PDO::PARAM_STR),
					);
					
		$where = " WHERE 1=1 ";
		$where .= " AND sales_type = 'zhmz' ";
		if(!empty($params['addtime'])){
			$where .= " AND (addtime=:addtime)";
		}
		if(!empty($params['begin_date'])){
			$where .= " AND (begin_date=:begin_date)";
		}
		if(!empty($params['end_date'])){
			$where .= " AND (end_date=:end_date)";
		}
		if(!empty($params['sales_name'])){
			$where .= " AND (sales_name=:sales_name)";
		}
		if($params['is_multiple'] != '' && $params['is_multiple'] != 'all'){
			$where .= " AND is_multiple=:is_multiple";
		}
		
		$sql = "select id,sales_no,sales_name,begin_date,end_date,addtime,sales_type,update_time,is_multiple,remark from ". TABLE('sales_promotion') . $where . $order;
		
		$result = $model->query($sql, $sqlParam)->limitPage($page1, $limit)->select();
		//echo $model->getLastSql();exit;
		if(!empty($result)){
			
			foreach($result as $key=>$vo){
				
				$result[$key]['begin_date'] = date("Y-m-d",$vo['begin_date']);
				$result[$key]['end_date'] = date("Y-m-d",$vo['end_date']);
				$result[$key]['addtime'] = date("Y-m-d",$vo['addtime']);
				$result[$key]['update_time'] = date("Y-m-d",$vo['update_time']);
				
				if($vo['is_multiple'] == 0){
					$result[$key]['is_multiple'] = '不是';
				}else{
					$result[$key]['is_multiple'] = '是';
				}
			}
		}
		
		$sql = "select count(1) as count from " . TABLE('sales_promotion') . $where;
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
	 * 添加 - 
	 */
	function saveAdd($params, $data2){
		//print_r($data2);exit;
		if(!is_array($params)){
			return array();
		}
		
		$model = D();
		// 安全过滤
		$params = array_map(function($value){return htmlspecialchars(trim($value));}, $params);
		// 数据集
		$time =  time();
		$data = array();
		$data['sales_no'] 	= '';
		$data['sales_name'] 	= $params['sales_name'];
		$data['begin_date'] 	= strtotime($params['begin_date']);
		$data['end_date'] 	= strtotime($params['end_date']);
		$data['addtime'] 	= strtotime($params['addtime']);
		$data['sales_type'] 	= 'zhmz';
		$data['update_time'] 	= $time;
		$data['is_multiple'] 	= $params['is_multiple'];
		$data['remark'] 	= $params['remark'];
		
		if (empty($data['sales_name'])) {
			
			return array('code' => 'error', 'msg' => '策略名称不能为空');
		}
		
		if (empty($data['begin_date'])) {
			
			return array('code' => 'error', 'msg' => '开始日期不能为空');
		}
		
		if (empty($data['end_date'])) {
			
			return array('code' => 'error', 'msg' => '结束日期不能为空');
		}
		
		if ($data['begin_date'] > $data['end_date']) {
			
			return array('code' => 'error', 'msg' => '开始日期不能大于结束日期');
		}
		
		$isSet = false;
		foreach ($data2 as $k => $v) {
			
			if ($v['groupId']) {
				
				$isSet = true;
				break;
			}
		}
		
		if (!$isSet) {
			
			return array('code' => 'error', 'msg' => '组合信息不可为空');
		}
		
		//print_r($data);exit;
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
		$sql = "insert into ".TABLE('sales_promotion') . $fields . "
				values " . $fieldsValues;
		
		// sales_no 系统自动生成
		$sql = str_replace(':sales_no', "(select sales_no from (SELECT CONCAT('SALES_',LPAD(RIGHT(IFNULL(MAX(sales_no),''),5)+1,5,'0')) as sales_no FROM ". TABLE('sales_promotion') .") a)", $sql);
		
		
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
		$sql_row ="select sales_no from " . TABLE('sales_promotion') . " where id=".$insert_id;
		$row = $model->query($sql_row)->find();
		$sales_no = $row['sales_no'];
		
		
		if(!empty($data2)){
			foreach($data2 as $vo){
				//保存 sales_promotion_sub 表数据
				if(is_array($vo['ZH_detail']) && !empty($vo['ZH_detail'])){
					foreach($vo['ZH_detail'] as $vo1){
						if(!empty($vo1['prd_no'])){
							$sql = "insert into ". TABLE('sales_promotion_sub') ."(sales_no,group_name,prd_no,unit_qty,level) values (";
							$sql .= " '". $sales_no ."', ";
							$sql .= " '". $vo['groupId'] ."', ";
							$sql .= " '". $vo1['prd_no'] ."', ";
							$sql .= " '". $vo1['unit_qty'] ."', ";
							$sql .= " '". $vo['orderId'] ."' ";
							$sql .= ")";
							$rs = $model->execute($sql);
							//echo $model->getLastSql();
							if(!$rs){
								$model->rollback();
								return array("code"=>"error", "msg"=>"操作失败");
							}
						}
					}
				}
				//保存 sales_promotion_gift 表数据
				if(is_array($vo['ZP_detail']) && !empty($vo['ZP_detail'])){
					foreach($vo['ZP_detail'] as $vo2){
						if(!empty($vo2['prd_no'])){
							$sql = "insert into ". TABLE('sales_promotion_gift') ."(sales_no,group_name,prd_no,unit_qty) values (";
							$sql .= " '". $sales_no ."', ";
							$sql .= " '". $vo['groupId'] ."', ";
							$sql .= " '". $vo2['prd_no'] ."', ";
							$sql .= " '". $vo2['unit_qty'] ."' ";
							$sql .= ")";
							$rs = $model->execute($sql);
							//echo $model->getLastSql();
							if(!$rs){
								$model->rollback();
								return array("code"=>"error", "msg"=>"操作失败");
							}
						}
					}
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
	function saveEdit($params, $data2){
		//print_r($data2);exit;
		if(!is_array($params)){
			return array();
		}
		$model = D();
		// 安全过滤
		$params = array_map(function($value){return htmlspecialchars(trim($value));}, $params);
		// 数据集
		$time = time();
		$data = array();
		$data['sales_name'] 	= $params['sales_name'];
		$data['begin_date'] 	= strtotime($params['begin_date']);
		$data['end_date'] 	= strtotime($params['end_date']);
		$data['addtime'] 	= strtotime($params['addtime']);
		$data['update_time'] 	= $time;
		$data['is_multiple'] 	= $params['is_multiple'];
		$data['remark'] 	= $params['remark'];
		//echo '<pre />';print_r($data2);exit;
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
		$sql = "update ". TABLE('sales_promotion') . ' SET ' . $fieldsSet . ' WHERE id=:id ';
		// 执行
		$model->startTrans();
		$result = $model->execute($sql, $sqlParam);
		//echo $model->getLastSql();exit;
		if($result === false){
			$model->rollback();
		}
		
		//保存
		if(!empty($data2)){
			
			$sql_row ="select sales_no from " . TABLE('sales_promotion') . " where id=:id";
			$sqlParam[] = array(
				array('name' => ':id', 'value' => $params['id'], 'type' => PDO::PARAM_STR),
			);
			$row = $model->query($sql_row,$sqlParam)->find();
			$sales_no = $row['sales_no'];
			//清除sales_promotion_sub sales_promotion_gift表信息
			$delSql = 'delete t1,t2 from ' . TABLE('sales_promotion_sub') . '  as t1 left join '. TABLE('sales_promotion_gift') .' as t2 on t1.sales_no=t2.sales_no where t1.sales_no="'.$sales_no.'"';
			$delRes = $model->execute($delSql);
			if (!$delRes) {
				
				$model->rollback();
				return array("code"=>"error", "msg"=>"操作失败",'sql'=>$model->getLastSql());
			}
			
			foreach($data2 as $vo){
				if(is_array($vo['ZH_detail']) && !empty($vo['ZH_detail'])){
					foreach($vo['ZH_detail'] as $vo1){
						
						$sql = "insert into ". TABLE('sales_promotion_sub') ."(sales_no,group_name,prd_no,unit_qty,level) values (";
						$sql .= " '". $sales_no ."', ";
						$sql .= " '". $vo['groupId'] ."', ";
						$sql .= " '". $vo1['prd_no'] ."', ";
						$sql .= " '". $vo1['unit_qty'] ."', ";
						$sql .= " '". $vo['orderId'] ."' ";
						$sql .= ")";
						$rs = $model->execute($sql);
						if(!$rs){
							$model->rollback();
							return array("code"=>"error", "msg"=>"操作失败");
						}
					}
				}
				if(is_array($vo['ZP_detail']) && !empty($vo['ZP_detail'])){
					foreach($vo['ZP_detail'] as $vo2){
						
						$sql = "insert into ". TABLE('sales_promotion_gift') ."(sales_no,group_name,prd_no,unit_qty) values (";
						$sql .= " '". $sales_no ."', ";
						$sql .= " '". $vo['groupId'] ."', ";
						$sql .= " '". $vo2['prd_no'] ."', ";
						$sql .= " '". $vo2['unit_qty'] ."' ";
						$sql .= ")";
						$rs = $model->execute($sql);
						//echo $model->getLastSql();exit;
						if(!$rs){
							$model->rollback();
							return array("code"=>"error", "msg"=>"操作失败");
						}
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
		$sql ="select * from ". TABLE('sales_promotion') ." where id=:id";
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
		
		/* $sql ="select sales_no from " . TABLE('sales_promotion') . " where sales_no='". $params['sales_no'] ."' ";
		$sales = $model->query($sql)->find(); */
		
		$sql = "select a.id,a.sales_no,a.group_name,a.prd_no,a.unit_qty,a.level,p.prd_name,p.unit_name from " . TABLE('sales_promotion_sub') . " AS a ";
		$sql .= ' LEFT JOIN product AS p ON a.prd_no=p.prd_no ';
		$sql .= ' where sales_no=:sales_no ';
		$sql .= ' ORDER BY a.id DESC ';
		$sqlParam = array(
			array('name' => ':sales_no', 'value' => $params['sales_no']),
		);
		if($rows = $model->query($sql, $sqlParam)->select())
		{
			foreach($rows as $row)
			{
				$data[$row['group_name']]['groupId'] = $row['group_name'];
				$data[$row['group_name']]['orderId'] = $row['level'];
				$data[$row['group_name']]['ZH_detail'][] = array('id' => $row['id'],'prd_no' => $row['prd_no'],'sales_no' => $row['sales_no'],'group_name' => $row['group_name'],'prd_name' => $row['prd_name'],'unit_name' => $row['unit_name'],'unit_qty' => $row['unit_qty']);
			}
		}

		$sql = "select a.id,a.sales_no,a.group_name,a.prd_no,a.unit_qty,p.prd_name,p.unit_name from " . TABLE('sales_promotion_gift') . " AS a ";
		$sql .= ' LEFT JOIN product AS p ON a.prd_no=p.prd_no ';
		$sql .= ' where sales_no=:sales_no ';
		$sql .= ' ORDER BY a.id DESC ';
		$sqlParam = array(
			array('name' => ':sales_no', 'value' => $params['sales_no']),
		);
		if($rows = $model->query($sql, $sqlParam)->select())
		{
			foreach($rows as $row)
			{
				$data[$row['group_name']]['ZP_detail'][] = array('id' => $row['id'],'prd_no' => $row['prd_no'],'sales_no' => $row['sales_no'],'group_name' => $row['group_name'],'prd_name' => $row['prd_name'],'unit_name' => $row['unit_name'],'unit_qty' => $row['unit_qty']);
			}
		}
		
		$data = array_values($data);
		/*$sql = "select a.*,b.level,c.prd_name,c.unit_name from " . TABLE('sales_promotion_sub') . " a left join ". TABLE('sales_promotion_gift') ." b on a.sales_no=b.sales_no and a.prd_no=b.prd_no left join ". 'product' ." c on a.prd_no=c.prd_no where a.sales_no='". $params['sales_no'] ."' ";
		$result = $model->query($sql, $sqlParam)->limitPage($page1, $limit)->select();
		//echo $model->getLastSql();exit;
		if(!empty($result)){
			foreach($result as $key=>$vo){
				$result[$key]['groupId'] = $vo['group_name']; //组合名称
				$result[$key]['orderId'] = $vo['level']; // 优先级
				
				$result[$key]['ZH_detail'][] = array(
					'prd_no' => $vo['prd_no'],
					'prd_name' => $vo['prd_name'],
					'unit_name' => $vo['unit_name'],
					'unit_qty' => $vo['unit_qty'],
				);
				
			}
		}*/

		//
		$sql = "select count(1) as count from " . TABLE('sales_promotion_sub') . $where;
		$count = $model->query($sql)->find();
		//print_r($model->getLastSql());
		return array(
			"code" => "0",
            "msg" =>"",
            "total" =>$count['count'],
            "data" =>$data
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
		$where = " WHERE 1=1 AND sales_type='zhmz' ";
		if(strpos($id, ',') !== false){
			$id = explode(',', $id);
			$id = implode(',', $id);
			$where .= ' AND id in('.$id.') ';
		}else{
			$where .= ' AND id=:id ';
		}
		$sql_list ="SELECT sales_no FROM ". TABLE('sales_promotion') . $where;
		$list = $model->query($sql_list, $sqlParam)->select();
		if(!empty($list)){
			$model->startTrans();
			$sql = "DELETE FROM ". TABLE('sales_promotion') . $where;
			$rs = $model->execute($sql, $sqlParam);
			if($rs !== false){
				$in = "";
				foreach($list as $vo){
					$in .= $sep . "'" . $vo['sales_no']. "'";
					$sep = ',';
				}
				// 删除组合商品
				$where = ' WHERE sales_no in('. $in .') ';
				$sql_sub = "DELETE FROM ". TABLE('sales_promotion_sub'). $where;
				$rs_sub = $model->execute($sql_sub);
				
				// 删除赠品
				$sql_gift = "DELETE FROM ". TABLE('sales_promotion_gift'). $where;
				$rs_gift = $model->execute($sql_gift);
				if($rs_gift !== false){
					$model->commit();
					return array("code"=>"ok", "msg"=>"操作成功");
				}
			}
			$model->rollback();
			return array("code"=>"error", "msg"=>"操作失败");
		}
	}
	
}
