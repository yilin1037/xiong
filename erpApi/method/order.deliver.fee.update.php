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
		if(empty($this->data['total_fee']))
		{
			echo json_encode(array('code' => '1005', 'msg' => '缺少total_fee参数'));	
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
		$data = $param['data'];
		$cost_info = array();
		if(is_array($data)){
			foreach($data as $item){
				$cost_info[] = array('cost_type' => $item['cost_type'], 'cost' => $item['cost']);
			}
		}
		
		$cost_info = json_encode($cost_info);
		
		$sqlParam = array(
			array('name' => ':order_id', 'value' => $param['order_id'], 'type' => PDO::PARAM_STR),
			array('name' => ':cost_fee', 'value' => $param['total_fee'], 'type' => PDO::PARAM_STR),
			array('name' => ':cost_info', 'value' => $cost_info, 'type' => PDO::PARAM_STR),
			array('name' => ':update_time', 'value' => $param['time'], 'type' => PDO::PARAM_STR),
		);
		
		$sql = "update  `out_stock_orders` SET cost_fee = :cost_fee, cost_info = :cost_info, update_time = :update_time WHERE order_no = :order_id ";
		$model->execute($sql, $sqlParam);
		
		$sql = "select dealer_no from `out_stock_orders` WHERE order_no = :order_id  ";
		$orders = $model->query($sql,$sqlParam)->find();
		$dealer_no = $orders['dealer_no'];
		if($dealer_no != ''){
			$sql = "update ".TABLE('tid_orders', $dealer_no)."  SET cost_fee = :cost_fee, cost_info = :cost_info, update_time = :update_time WHERE tid = :order_id ";
			$model->execute($sql, $sqlParam);
		}
		
		echo json_encode(array('code' => '0000', 'msg' => 'success'));	
		exit;
	}
}