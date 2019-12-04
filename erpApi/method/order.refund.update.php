<?php
class api
{
	private $data;
	public function checkParam($param)//参数校验
	{
		if(empty($param['data']))
		{
			echo json_encode(array('code' => '1004', 'msg' => '数据格式不正确'));	
			exit;	
		}
		$this->data = json_decode($param['data'], true);
		if(empty($this->data['order_id']))
		{
			echo json_encode(array('code' => '1005', 'msg' => '缺少order_id参数'));	
			exit;	
		}
		if(empty($this->data['time']))
		{
			echo json_encode(array('code' => '1005', 'msg' => '缺少time参数'));	
			exit;	
		}
		if(empty($this->data['data']))
		{
			echo json_encode(array('code' => '1005', 'msg' => '缺少data参数'));	
			exit;	
		}
	}
	
	public function method($param)
	{
		$model = D();
		$sqlParam = array(
			array('name' => ':order_id', 'value' => $param['order_id'], 'type' => PDO::PARAM_STR),
			array('name' => ':update_time', 'value' => $param['time'], 'type' => PDO::PARAM_STR),
		);
		$data = $param['data'];
		if(is_array($data)){
			foreach($data as $item){
				$sqlParamSub = array(
					array('name' => ':order_id', 'value' => $param['order_id'], 'type' => PDO::PARAM_STR),
					array('name' => ':prd_no', 'value' => $item['prd_no'], 'type' => PDO::PARAM_STR),
					array('name' => ':oid', 'value' => $item['oid'], 'type' => PDO::PARAM_STR),
					array('name' => ':return_qty', 'value' => $item['return_qty'], 'type' => PDO::PARAM_STR),
					array('name' => ':return_cause', 'value' => $item['return_cause'], 'type' => PDO::PARAM_STR),
				);
				
				$sql = "select 1 from `out_stock_items` a
				inner join (select order_no from `out_stock_orders` where order_no=:order_id) as b on b.order_no=a.order_no
				WHERE prd_no=:prd_no and oid = :oid";
				if(!$model->query($sql,$sqlParamSub)->find()){
					echo json_encode(array('code' => '0002', 'msg' => 'prd_no['.$item['prd_no'].']、oid['.$item['oid'].']未查找到订单商品数据'));	
					exit;
				}
				
			}
		}else{
			echo json_encode(array('code' => '0001', 'msg' => '数据格式不正确'));	
			exit;
		}
		
		
		$sql = "select dealer_no from `out_stock_orders` WHERE order_no = :order_id  ";
		$orders = $model->query($sql,$sqlParam)->find();
		$dealer_no = $orders['dealer_no'];
		
		foreach($data as $item){
			$sqlParamSub = array(
				array('name' => ':order_id', 'value' => $param['order_id'], 'type' => PDO::PARAM_STR),
				array('name' => ':prd_no', 'value' => $item['prd_no'], 'type' => PDO::PARAM_STR),
				array('name' => ':oid', 'value' => $item['oid'], 'type' => PDO::PARAM_STR),
				array('name' => ':return_qty', 'value' => $item['return_qty'], 'type' => PDO::PARAM_STR),
				array('name' => ':return_cause', 'value' => $item['return_cause'], 'type' => PDO::PARAM_STR),
			);
			
			$sql = "update `out_stock_items` a
			inner join (select order_no from `out_stock_orders` where bill_no=:order_id) as b on b.order_no=a.order_no
			SET a.return_qty = :return_qty, a.return_cause = :return_cause WHERE a.oid = :oid";
			$model->execute($sql, $sqlParamSub);
			
			if($dealer_no != ''){
				$sql = "update ".TABLE('tid_items', $dealer_no)."  SET return_qty = :return_qty, return_cause = :return_cause WHERE tid = :order_id and oid = :oid"; 
				$model->execute($sql, $sqlParamSub);
			}
		}
		
		$sql = "update  `out_stock_orders` SET  update_time = :update_time WHERE order_no = :order_id ";
		$model->execute($sql, $sqlParam);
		
		if($dealer_no != ''){
			$sql = "update ".TABLE('tid_orders', $dealer_no)."  SET modified_time = :update_time WHERE tid = :order_id ";
			$model->execute($sql, $sqlParam);
		}
		
		echo json_encode(array('code' => '0000', 'msg' => 'success'));	
		exit;
	}
}