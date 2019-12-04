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
		$order_id = $param['order_id'];
		$data = $param['data'];
		$model->startTrans();
		$i = 0;
		$tids = array();
		if(is_array($data)){
			foreach($data as $plans)
			{
				$i++;
				$plan_no = $plans['plan_no'];
				$deliver_index = $plans['index'];
				$items = $plans['items'];
				$total_fee = 0;
				if(is_array($items))
				{
					foreach($items as $item)
					{
						$sqlItemParam = array(
							array('name' => ':order_id', 'value' => $order_id),
							array('name' => ':prd_no', 'value' => $item['prd_no']),
							array('name' => ':oid', 'value' => $item['oid']),
							array('name' => ':prd_no', 'value' => $item['prd_no']),
							array('name' => ':qty', 'value' => $item['qty'])
						);
						$arr = $model->query("select box_rate from product where prd_no=:prd_no", $sqlItemParam)->find();
						$box_rate = $arr['box_rate'] ? $arr['box_rate'] : 1;
						$arr = $model->query("select unit_price from out_stock_items where order_no=:order_id and oid=:oid", $sqlItemParam)->find();
						$unit_price = $arr['unit_price'];
						if(!$arr = $model->query("select dealer_no from out_stock_orders where order_no=:order_id", $sqlItemParam)->find())
						{
							$model->rollback();	
							echo json_encode(array('code' => '1010', 'msg' => '不存在的订单号'));	
							exit;	
						}
						$dealer_no = $arr['dealer_no'];
						$box_total_fee = round($total_fee, 2) + round($unit_price * $item['num'], 2);
						$total_fee += $box_total_fee;
						$box_num = round($item['num'] / $box_rate, 6);
						$box_price = round($unit_price / $box_rate, 6);
						
						$sql = "insert into out_stock_items (order_no,oid,prd_no,prd_name,old_num,num,unit_name,unit_price,box_num,box_price,box_total_fee,is_bat,bat_no,is_gift,remark)
						select concat(order_no,'".$i."'),oid,prd_no,prd_name,:qty,:qty,unit_name,'".$unit_price."','".$box_num."','".$box_price."','".$box_total_fee."',is_bat,bat_no,is_gift,remark from out_stock_items where order_no=:order_id and oid=:oid";
						if($model->execute($sql, $sqlItemParam) === false)
						{
							$model->rollback();	
							echo json_encode(array('code' => '1012', 'msg' => '拆分订单明细失败'));	
							exit;
						}
						$sql = "insert into ".TABLE('tid_items', $dealer_no)." (tid,oid,prd_no,prd_name,box_num,box_price,box_total_fee,unit_name,unit_num,unit_price,is_bat,bat_no,is_gift,remark)
						select concat(order_no,'".$i."'),oid,prd_no,prd_name,'".$box_num."','".$box_price."','".$box_total_fee."',unit_name,:qty,'".$unit_price."',is_bat,bat_no,is_gift,remark from out_stock_items where order_no=:order_id and oid=:oid";
						if($model->execute($sql, $sqlItemParam) === false)
						{
							$model->rollback();	
							echo json_encode(array('code' => '1022', 'msg' => '拆分订单明细失败'));	
							exit;
						}
					}
				}	
				
				$sqlParam = array(
					array('name' => ':order_id', 'value' => $order_id),
					array('name' => ':plan_no', 'value' => $plan_no),
					array('name' => ':deliver_index', 'value' => $deliver_index),
				);
				$sql = "insert into out_stock_orders (order_no,dealer_no,cust_no,cust_name,total_fee,payment_type,payment,receiver_province,receiver_city,receiver_district,receiver_address,receiver_name,receiver_mobile,bill_no,is_urgent,urgent_time_type,urgent_begin_time,urgent_end_time,order_type,out_type,delivery_type,wh_no,status,order_time,arrive_time,remark,plan_no,deliver_index,three_pl_deliveryTime,three_pl_esDate,file_url)
				select concat(order_no,'".$i."'),dealer_no,cust_no,cust_name,'".$total_fee."',payment_type,payment,receiver_province,receiver_city,receiver_district,receiver_address,receiver_name,receiver_mobile,bill_no,is_urgent,urgent_time_type,urgent_begin_time,urgent_end_time,0,0,delivery_type,wh_no,1,order_time,arrive_time,remark,:plan_no,:deliver_index,three_pl_deliveryTime,three_pl_esDate,file_url from out_stock_orders where order_no=:order_id
				";
				if($model->execute($sql, $sqlParam) === false)
				{
					
					$model->rollback();	
					echo json_encode(array('code' => '1013', 'msg' => '生成订单失败'));	
					exit;
				}
				
				$sql = "insert into ".TABLE('tid_orders', $dealer_no)." (tid,cust_no,send_status,assign_type,payment,total_fee,post_fee,receiver_province,receiver_city,receiver_district,receiver_address,receiver_name,receiver_mobile,receiver_telephone,express_type,express_status,deliver_car,payment_type,is_urgent,urgent_time_type,urgent_begin_time,urgent_end_time,items_num,sku_num,addtime,modified_time,payment_time,send_time,finish_time,remark,three_pl_deliveryTime,three_pl_esDate,payment_info)
				select concat(tid,'".$i."'),cust_no,send_status,assign_type,'".$total_fee."','".$total_fee."',post_fee,receiver_province,receiver_city,receiver_district,receiver_address,receiver_name,receiver_mobile,receiver_telephone,express_type,express_status,deliver_car,payment_type,is_urgent,urgent_time_type,urgent_begin_time,urgent_end_time,items_num,sku_num,addtime,modified_time,payment_time,send_time,finish_time,concat('多车配送拆单',remark),three_pl_deliveryTime,three_pl_esDate,payment_info from ".TABLE('tid_orders', $dealer_no)." where tid=:order_id
				";
				if($model->execute($sql, $sqlParam) === false)
				{
					$model->rollback();	
					echo json_encode(array('code' => '1023', 'msg' => '生成订单失败'));	
					exit;
				}
				$tids[] = array('tid' => $order_id.$i, 'plan_no' => $plan_no);
			}
		}
		foreach($tids as $list)
		{
			$arr = $this->syncTmdOrder($list['tid'], $dealer_no, $list['plan_no']);
			if(!$arr["success"])
			{
				$model->rollback();	
				echo json_encode(array("code" => "1014", "msg" => $arr["info"]));
				exit;
			}
		}
		$sqlParam = array(
			array('name' => ':order_id', 'value' => $order_id),
		);
		$model->execute("delete from ".TABLE('tid_orders', $dealer_no)." where tid=:order_id", $sqlParam);
		$sqlParam = array(
			array('name' => ':order_id', 'value' => $order_id),
		);
		$model->execute("delete from ".TABLE('tid_items', $dealer_no)." where tid=:order_id", $sqlParam);
		$sqlParam = array(
			array('name' => ':order_id', 'value' => $order_id),
		);
		$model->execute("delete from out_stock_orders where order_no=:order_id", $sqlParam);
		$sqlParam = array(
			array('name' => ':order_id', 'value' => $order_id),
		);
		$model->execute("delete from out_stock_items where order_no=:order_id", $sqlParam);
		$model->commit();
		//M('wms/wave')->createWave($param);
		echo json_encode(array('code' => '0000', 'msg' => 'success'));	
		exit;
	}
	
	public function syncTmdOrder($tid, $dealer_no, $plan_no)
	{
		include_once('SDK/tms-sdk/tmsapi.php');	
		$model = D();
		
		$sql = "select * from ". TABLE('tid_orders', $dealer_no) ." where `tid` = '".$tid."' order by id desc limit 1";
        $row = $model->query($sql)->find();
		
		$sql2 = "select * from ". TABLE('tid_items', $dealer_no) ." where `tid` = '".$tid."' order by id desc";
        $row2 = $model->query($sql2)->select();

        $time_int = time();
        $time_str = (string)$time_int;

        $orderDetailsArray=array();
        foreach ($row2 as  $k => $v ) {
            // TODO 查找商品找到箱系数
            $prd_no= $v["prd_no"];
            $productModel = D();
            $productSql = "select box_rate from product where `prd_no` = '".$prd_no."' order by id desc limit 1";
            $productResult = $productModel->query($productSql)->find();

            // 箱系数
            $box_rate=$productResult["box_rate"];

            // 主单位数量=主单位/箱系数 取余
            // 副单位数量 主单位/箱系数 取整
            $num1 = (int)$v["unit_num"];
            $tmsUnitNum = $num1%$box_rate;
            $auxUnitNum = intval($num1/$box_rate);

            $product = array (
                'goodsNo' => $v["prd_no"],
                'goodsName' => $v["prd_name"],
                'auxUnitNum' =>(int)$auxUnitNum, // 辅单位数量
                'orderId' => $v["tid"],
                'salePrice' =>(float)$v["unit_price"],
                'detailId' =>   $v["tid"]."-".$v["id"],
                'unitNum' => (int)$tmsUnitNum,
                'batchNo' => $v["id"],
            );
            array_push($orderDetailsArray,$product);
        }

        $orderDetailsString = json_encode($orderDetailsArray);
		$sqlParam3 = array(
            array('name' => ':cust_no', 'value' =>$row["cust_no"] , 'type' => PDO::PARAM_STR),
        );

        $sql3="select * from cust where cust_no=:cust_no";

        $row3 = $model->query($sql3, $sqlParam3)->find();

        $orderType=(int)$row["is_urgent"]+1;
        if($row["express_type"]==1) {
            $orderType=3;
        }
		$sqlParam4 = array(
			array('name' => ':dealer_no', 'value' => $dealer_no , 'type' => PDO::PARAM_STR),
		);
		$sql4="select dealer_name from dealer where dealer_no=:dealer_no";
		$row4 = $model->query($sql4, $sqlParam4)->find();
		$orderData = array (
			'orderType' => $orderType, //  //订单类型：1：波次 2：加急 // TODO
			'deliveDay' => date("Y-m-d H:i:s",$row["send_time"]), // //配送日期 // TODO
			'dealerid' => $dealer_no, ////经销商编号
			'dealerName' => $row4["dealer_name"], // //经销商名
			'orderid' => $row["tid"], //  //订单编号
			'deliveType' => 1, // //配送类型 1：送货 2：收货 // TODO
			'custId' => $row["cust_no"], // //客户编号 //
			'custName' => $row3["cust_name"], // //客户名称
			'planNo' => $plan_no, //计划号
			'details' => $orderDetailsString // //订单商品明细数组
		);
        $orderResult = addOrder($orderData);

        return json_decode($orderResult,true);
	}
}