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
		if(empty($this->data['wait_status']))
		{
			echo json_encode(array('code' => '1005', 'msg' => '缺少wait_status参数'));	
			exit;	
		}
		if(empty($this->data['time']))
		{
			echo json_encode(array('code' => '1005', 'msg' => '缺少time参数'));	
			exit;	
		}
	}
	
	public function method($param)
	{
		$model = D();
		$sqlParam = array(
			array('name' => ':order_id', 'value' => $param['order_id'], 'type' => PDO::PARAM_STR),
			array('name' => ':wait_status', 'value' => $param['wait_status'], 'type' => PDO::PARAM_STR),
			array('name' => ':wait_time', 'value' => time(), 'type' => PDO::PARAM_STR),
			array('name' => ':update_time', 'value' => $param['time'], 'type' => PDO::PARAM_STR),
		);
		
		if($param['wait_status'] == '1'){
			$sql = "update  `out_stock_orders` SET wait_status = :wait_status, wait_time_start = :wait_time, update_time = :update_time WHERE order_no = :order_id ";
			$model->execute($sql, $sqlParam);
		}else{
			$sql = "update  `out_stock_orders` SET wait_status = :wait_status, wait_time_end = :wait_time, update_time = :update_time WHERE order_no = :order_id ";
			$model->execute($sql, $sqlParam);
		}
		
		
		$sql = "select dealer_no from `out_stock_orders` WHERE order_no = :order_id  ";
		$orders = $model->query($sql,$sqlParam)->find();
		$dealer_no = $orders['dealer_no'];
		if($dealer_no != ''){
			if($param['wait_status'] == '1'){
				$sql = "update ".TABLE('tid_orders', $dealer_no)."  SET wait_time_start = :wait_status, wait_time = :wait_time, modified_time = :update_time WHERE tid = :order_id ";
				$model->execute($sql, $sqlParam);
			}else{
				$sql = "update ".TABLE('tid_orders', $dealer_no)."  SET wait_time_end = :wait_status, wait_time = :wait_time, modified_time = :update_time WHERE tid = :order_id ";
				$model->execute($sql, $sqlParam);
			}
		}
		
		echo json_encode(array('code' => '0000', 'msg' => 'success'));	
		exit;
	}
}