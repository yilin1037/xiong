<?

class setupModelClass extends CommonModelClass
{
    public function __construct()//构造函数
    {
        parent::__construct();
    }

    public function setPass($param)
    {
        $model = D();
        $oldpass = $param['oldpass'];
        $newpass = $param['newpass'];

        $usertable="userlist";
        if($_SESSION['LOGIN_SYSTEM']=="T"){
            $usertable="userlist";
            if (!$row = $model->query("select password from  " . $usertable . " where user_no=:user_no", array(array('name' => ':user_no', 'value' => $_SESSION['LOGIN_USER_ID'], 'type' => PDO::PARAM_STR)))->find()) {
                return array('code' => 'error', 'msg' => '系统超时 请重新登录！！');
            }
            if (md5($_SESSION['LOGIN_USER_ID'] . md5($oldpass)) != $row['password']) {
                return array('code' => 'error', 'msg' => '原密码错误！');
            }
            $model->execute("update  " . $usertable . " set password ='" . md5($_SESSION['LOGIN_USER_ID'] . md5($newpass)) . "' where user_no=:user_no", array(array('name' => ':user_no', 'value' => $_SESSION['LOGIN_USER_ID'], 'type' => PDO::PARAM_STR)));

            return array('code' => 'ok', 'msg' => '密码修改成功！！');
        } else  if($_SESSION['LOGIN_SYSTEM']=="F") {
            $usertable= TABLE('usertable');
            if (!$row = $model->query("select password from  " . $usertable . " where userid=:userid", array(array('name' => ':userid', 'value' => $_SESSION['LOGIN_USER_ID'], 'type' => PDO::PARAM_STR)))->find()) {
                return array('code' => 'error', 'msg' => '系统超时 请重新登录！！');
            }
            if (md5($_SESSION['LOGIN_USER_ID'] . md5($oldpass)) != $row['password']) {
                return array('code' => 'error', 'msg' => '原密码错误！');
            }
            $model->execute("update  " . $usertable . " set password ='" . md5($_SESSION['LOGIN_USER_ID'] . md5($newpass)) . "' where userid=:userid", array(array('name' => ':userid', 'value' => $_SESSION['LOGIN_USER_ID'], 'type' => PDO::PARAM_STR)));

            return array('code' => 'ok', 'msg' => '密码修改成功！！');
        }

    }

    public function getChildAccount1($param)
    {
        $model = D();
        $infos = array();
        $row = $model->query("SELECT  id, userid,username,mobile,create_login_time,STATUS FROM    " . TABLE('usertable')   )->select();
        $cuont=1;
        $re=array(
            "draw" => "false",
            "recordsTotal" =>$cuont,
            "recordsFiltered" =>$cuont,
            "data" =>$row
        );

        return $re;
    }

	public function setstatus($param)
    {
        $model = D();
		if($param['type']=='effective'){
			$status = '0';
		}
		if($param['type']=='invalid'){
			$status = '1';
		}
		$ids = $param['ids'];
		$ids = str_replace(",","','",$ids);
        $row = $model->execute("update " . TABLE('Express')." set status='".$status."' where id in ('".$ids."')");
        if($row){
			$re=array(
				"code" => "0000",
				"msg" =>"修改完成！",
			);
		}
        return $re;
    }

	public function deleteExpress($param)
    {
        $model = D();
		$ids = $param['ids'];
		$ids = str_replace(",","','",$ids);
	
		$sql = "select 1 from " . TABLE('Express')."  where id in ('".$ids."') and `type` like 'DF_%' ";
		if($model->query($sql)->find()){
			$re = array(
				"code" => "error",
				"msg" =>"代发快递无法删除",
			);
			return $re;
		}
		
        //$row = $model->execute("delete from " . TABLE('Express')."  where id in ('".$ids."')");
		$row = $model->execute("update ".TABLE('express')." set status = 1,del_logo = 1 where id in ('".$ids."')");
	   if($row){
			$re=array(
				"code" => "0000",
				"msg" =>"删除成功！",
			);
		}else{
			$re = array(
				"code" => "error",
				"msg" =>"删除失败",
			);
		}
        return $re;
    }
	 public function getshoplist($param)
    {
        $model = D();
		$sql = "select id,shopid,shopname from ".TABLE('shop_config');
		$result = $model->query($sql)->select();
        return $result;
    }
	 public function getdetail($param)
    {
		$models=D_SYS();
		$data_city = $models->QUERY("SELECT city_code,city_name,province_code FROM data_city")->select();
		
		$data_province = $models->QUERY("SELECT province_code,province_name,'1' AS parentid FROM data_province")->select();
		foreach($data_province as $provincerow){
			$data_provinces[(int)$provincerow['province_code']]=$provincerow['province_name'];
	
		}
		foreach($data_city as $cityrow){
			$data_citys[(int)$cityrow['province_code']][(int)$cityrow['city_code']]=$cityrow['city_name'];
	
		}
		$data['data_city']=	$data_citys;
		$data['data_province']= $data_provinces;
        return $data;
    }
	 public function updatecity($param)
    {
		$model = D();
		
		$param['shop_id'] = $param['shop_id'] == "0" ? "" : $param['shop_id'];
		
		$model->execute("delete from " . TABLE('Express_reach') . " where express_type=:wl_id and shop_id=:shop_id", 
				array(array('name' => ':wl_id', 'value' => $param['addressid'], 'type' => PDO::PARAM_STR),
					  array('name' => ':shop_id', 'value' => $param['shop_id'], 'type' => PDO::PARAM_STR),
				));
		$cityids = explode(",",$param['cityid']);
		
		foreach($cityids as $cityid){
				$model->execute("insert into  " . TABLE('Express_reach') . "(shop_id,express_type,city_code) values(:shop_id,:wl_id,:cityid)", 
				array(array('name' => ':wl_id', 'value' => $param['addressid'], 'type' => PDO::PARAM_STR),
					  array('name' => ':cityid', 'value' => $cityid, 'type' => PDO::PARAM_STR),
					  array('name' => ':shop_id', 'value' => $param['shop_id'], 'type' => PDO::PARAM_STR),
				));
			
		}
		
		$model->execute("update " . TABLE('express') . " set replaceExpress=:replaceExpress  where `express_id`=:wl_id and status='0' ",
				array(array('name' => ':wl_id', 'value' => $param['addressid'], 'type' => PDO::PARAM_STR),
					  array('name' => ':replaceExpress', 'value' => $param['replaceExpress'], 'type' => PDO::PARAM_STR),
				));
				
        return $data;
    }
	
	//可选区域选择
	public function updatecityon($param)
    {
		$data = $param['data'];
		$addattrid = str_replace('_on','',$param['addattrid']);
		$model = D();
		$model->startTrans();
		$express = explode(",",$data);
		$expArr = implode("','",$express);
		if($expArr != ""){
			$sql = "select 1 from ".TABLE('express_use')." where express_type<>'".$addattrid."' and city_code in ('".$expArr."')";
			$cityCode = $model->query($sql)->find();
			if($cityCode){
				$model->rollback();
				return array('code'=>'error','msg'=>'同一地区之允许存在一个快递',);
			}
		}
		
		$sql = "select 1 from ".TABLE('express_use')." where express_type=:addattrid";
		$find = $model->query($sql,
			array(array('name' => ':addattrid', 'value' => $addattrid, 'type' => PDO::PARAM_STR),
		))->find();
		if($find){
			$result = $model->execute("delete from ".TABLE('express_use')." where express_type=:addattrid", 
				array(array('name' => ':addattrid', 'value' => $addattrid, 'type' => PDO::PARAM_STR),
			));
			if($result == 0){
				$model->rollback();
				return array('code'=>'error','msg'=>'修改失败',);
			}
		}
		foreach($express as $keyid => $cityid){
			$sqlParam = array(
				array('name' => ':express_type', 'value' => $addattrid, 'type' => PDO::PARAM_STR),
				array('name' => ':city_code', 'value' => $cityid, 'type' => PDO::PARAM_STR),
			);
			$sql = "insert into ".TABLE('express_use')." (express_type, city_code) values (:express_type, :city_code)";
			$row = $model->execute($sql,$sqlParam);
			if($row == 0){
				$model->rollback();
				return array('code'=>'error','msg'=>'修改失败',);
			}
		}
        $model->commit();
		return array( 'code' => 'ok', 'msg' => '绑定成功');
    }
	public function getcitys($param)
    {
		$model = D();
		$param['shop_id'] = $param['shop_id'] == "0" ? "" : $param['shop_id'];
		$data = $model->query("select city_code from  " . TABLE('Express_reach') . " where express_type=:express_type and shop_id=:shop_id ", 
				array(array('name' => ':express_type', 'value' => $param['type'], 'type' => PDO::PARAM_STR),
					  array('name' => ':shop_id', 'value' => $param['shop_id'], 'type' => PDO::PARAM_STR),
				))->select();
        return $data;
    }
	public function getcityson($param)
    {
		$model = D();
		$addattrid = str_replace('_on','',$param['addattrid']);		
		$data = $model->query("select city_code from  " . TABLE('express_use') . " where express_type=:addattrid", 
				array(array('name' => ':addattrid', 'value' => $addattrid, 'type' => PDO::PARAM_STR),
				))->select();
        return $data;
    }
	public function getReplaceExpress($param)
    {
		$model = D();		
		
		$data = $model->query("select replaceExpress from  " . TABLE('express') . " where `type`=:type and status='0' ", 
				array(array('name' => ':type', 'value' => $param['type'], 'type' => PDO::PARAM_STR),
				))->find();
				
        return array('replaceExpress' => $data['replaceExpress']);
    }
	 public function updateExpress($param)
    {
        $model = D();
		if($param['status']=='on'){
			$status='0';
		}else{
			$status='1';
		}
		if($param['default']=='on'){
			$default='1';
			$model->execute("update  " . TABLE('Express') . " set `default` = '0'");
			
		}else{
			$default='0';
		}
		$assist_print = $param['assist_print'];
		$express_name = $param['express_name'];
		$express_fee = $param['express_fee'];
		$send_username = $param['send_username'];
		$send_tel = $param['send_tel'];
		$ratio = $param['ratio'];
		$print_province = $param['province'];
		$print_city = $param['city'];
		$print_district = $param['area1'];
		$print_detail = $param['detail'];
		$id = $param['id'];
		$no = $param['no'];
		
		//中通信息
		$zto_partner = $param['zto_partner'];
		
		//顺丰信息
		$SF_appid = $param['SF_appid'];					//商家代码
		$SF_appkey = $param['SF_appkey'];				//商家秘钥
		$expressType = $param['expressType'];			//产品类别
		$payMethod = $param['payMethod'];				//付款方式
		$custId = $param['custId'];						//月结卡号
		$isDoCall = $param['isDoCall'];					//是否下call
		$isGenBillno = $param['isGenBillno'];			//是否申请运单号
		$isGenEletricPic = $param['isGenEletricPic'];	//是否生成电子运单图片
		$SFdata = array(
			'SF_appid' => $SF_appid,
			'SF_appkey' => $SF_appkey,
			'expressType' => $expressType,
			'payMethod' => $payMethod,
			'custId' => $custId,
			'isDoCall' => $isDoCall,
			'isGenBillno' => $isGenBillno,
			'isGenEletricPic' => $isGenEletricPic,
		);
		
		//京东到付外单信息
		$JD_customerCode = $param['JD_customerCode'];
		$JD_wareHouseCode = $param['JD_wareHouseCode'];
		$JD_shopid = $param['JD_shopid'];
		$JDdata = array(
			'JD_customerCode' => $JD_customerCode,
			'JD_wareHouseCode' => $JD_wareHouseCode,
			'JD_shopid' => $JD_shopid,
		);
		
		//圆通信息
		$yto_clientId = $param['yto_clientId'];
		$yto_partnerId = $param['yto_partnerId'];
		$YTOdata = array(
			'yto_clientId' => $yto_clientId,
			'yto_partnerId' => $yto_partnerId,
		);
		
		//韵达信息
		$yunda_clientId = $param['yunda_clientId'];
		$yunda_partnerId = $param['yunda_partnerId'];
		$YUNDAdata = array(
			'yunda_clientId' => $yunda_clientId,
			'yunda_partnerId' => $yunda_partnerId,
		);
		
		$express_config = "";
		if($zto_partner != ''){
			$express_config = $zto_partner;
		}else if($JD_customerCode != '' || $JD_wareHouseCode != '' || $JD_shopid != ''){
			$express_config = json_encode($JDdata);
		}else if($SF_appid != '' || $SF_appkey != '' || $expressType != '' || $custId != ''){
			$express_config = json_encode($SFdata);
		}else if($yto_clientId != '' || $yto_partnerId != ''){
			$express_config = json_encode($YTOdata);
		}else if($yunda_clientId != '' || $yunda_partnerId != ''){
			$express_config = json_encode($YUNDAdata);
		}
		
		$sql = "select id,shopid,shopname,send_erpress_type from ".TABLE('shop_config');
		$rows = $model->query($sql)->select();
		
		$sql = "select express_id as type from ".TABLE('Express')." where id = '".$id."'";
		$types = $model->query($sql)->find();
		
		$model->execute("update  " . TABLE('shop_config') . " set 
				send_erpress_type =''  where send_erpress_type=:send_erpress_type", 
				array(array('name' => ':send_erpress_type', 'value' => $types['type'], 'type' => PDO::PARAM_STR)));
		
		$shopnames = "";
		foreach($rows as $row){
			if($param[$row['shopid']]=='on'){
				//echo "update  " . TABLE('shop_config') . " set send_shopid ='".$id."'  where shopname='".$row['shopname']."'";
				$model->execute("update  " . TABLE('shop_config') . " set 
				send_erpress_type =:id  where shopid=:shopname", 
					array(
						array('name' => ':id', 'value' => $types['type'], 'type' => PDO::PARAM_STR),
						array('name' => ':shopname', 'value' => $row['shopid'], 'type' => PDO::PARAM_STR),
						array('name' => ':express_config', 'value' => $express_config, 'type' => PDO::PARAM_STR),
					)
				);
			}
		
		}
		if($no=='ZJ_ZTO' || $no=='ZJ_SF' || $no=='ZJ_YTO_COD' || $no=='ZJ_YUNDA_COD' || $no=='JDKD_YTH_COD'){
			$sql = "update  " . TABLE('Express') . " set 
				status =:status,`default` =:default,assist_print =:assist_print,`name`=:express_name,express_fee=:express_fee,send_username =:send_username,send_tel =:send_tel,ratio =:ratio, print_province=:print_province, print_city=:print_city, print_district=:print_district, print_detail=:print_detail, express_config=:express_config
				where id=:id";
		}else{
			$sql = "update  " . TABLE('Express') . " set 
				status =:status,`default` =:default,assist_print =:assist_print,`name`=:express_name,express_fee=:express_fee,send_username =:send_username,send_tel =:send_tel,ratio =:ratio, print_province=:print_province, print_city=:print_city, print_district=:print_district, print_detail=:print_detail
				where id=:id";
		}
		$result = $model->execute($sql, array(array('name' => ':id', 'value' => $id, 'type' => PDO::PARAM_STR),
							array('name' => ':status', 'value' => $status, 'type' => PDO::PARAM_STR),
							array('name' => ':default', 'value' => $default, 'type' => PDO::PARAM_STR),
							array('name' => ':assist_print', 'value' => $assist_print, 'type' => PDO::PARAM_STR),
							array('name' => ':express_name', 'value' => $express_name, 'type' => PDO::PARAM_STR),
							array('name' => ':express_fee', 'value' => $express_fee, 'type' => PDO::PARAM_STR),
							array('name' => ':send_username', 'value' => $send_username, 'type' => PDO::PARAM_STR),
							array('name' => ':send_tel', 'value' => $send_tel, 'type' => PDO::PARAM_STR),
							array('name' => ':print_province', 'value' => $print_province, 'type' => PDO::PARAM_STR),
							array('name' => ':print_city', 'value' => $print_city, 'type' => PDO::PARAM_STR),
							array('name' => ':print_district', 'value' => $print_district, 'type' => PDO::PARAM_STR),
							array('name' => ':print_detail', 'value' => $print_detail, 'type' => PDO::PARAM_STR),
							array('name' => ':ratio', 'value' => $ratio, 'type' => PDO::PARAM_STR),
							array('name' => ':express_config', 'value' => $express_config, 'type' => PDO::PARAM_STR),
						)
		);
		if($result!== false){
			$data = array('code' => '0000', 'msg' => 'ok');
		}else{
			$data = array('code' => '0001', 'msg' => '保存失败！');
		}
		
		$model->execute("UPDATE " . TABLE('Express') . " SET express_id = CONCAT(`type`,'_',`id`) WHERE express_id = ''");
		return $data;
    }
    public function getExpress($param)
    {
        $model = D();
        $infos = array();
		include_once("SDK/taobao-sdk/TopSdk.php");
        include_once("SDK/taobao-sdk/top/request/LogisticsAddressSearchRequest.php");
		
		include_once("SDK/jd_SDK/JosClient.php");
		include_once("SDK/jd_SDK/JosRequest.php");
        include_once("SDK/jd_SDK/request/providerSignSuccessRequest.php");
		
		include_once("SDK/pinduoduo-SDK/model/OpenApiPDDModel.Class.php");
        include_once("SDK/pinduoduo-SDK/model/waybillSearchPDDModel.Class.php");
		$tmp = '';
		if($param['del_type'] == 1){
			$tmp = ' where del_logo = 0';
		}else{
			$tmp = ' where del_logo = 1';
		}
		$re = array();
		if($rows = $model->query("SELECT  *  FROM  " . TABLE('Express') . " " .$tmp."")->select())
		{
			foreach($rows as $row){
				$row['send_shopid'] = '';
				$row['send_shopids'] = '';
				$row['quantity'] = 0;
				$row['allocated_quantity'] = 0;
				if($row['express_form'] == "cainiao"){
					$row['send_address'] = $row['send_province']." ".$row['send_city']." ".$row['send_district']." ".$row['send_detail'];	
				}else{
					$row['send_address'] = $row['send_detail'];
				}
				$row['print_address'] = $row['print_province']." ".$row['print_city']." ".$row['print_district']." ".$row['print_detail'];
				if(strpos($row['no'],'ZJ') === 0){
					if($row['express_config']){
						$row['express_config'] = $row['express_config'];
					}else{
						$row['express_config'] = '';
					}
				}
				$data[$row['no']] = $row;
			}
			foreach($data as $key => $row){
			   
				$sql = "select id,shopname from ".TABLE('shop_config')." where send_erpress_type = '".$row['express_id']."'";
				$shopnamerows = $model->query($sql)->select();
				$send_erpress_type = "";
				$send_shopids = "";
				if($shopnamerows){
						foreach($shopnamerows as $shopnamerow){
						if($send_erpress_type==''){
							$send_erpress_type = $shopnamerow['shopname'];
							$send_shopids = $shopnamerow['id'];
						}else{
							$send_erpress_type = $send_erpress_type .",". $shopnamerow['shopname'];
							$send_shopids = $send_shopids .",". $shopnamerow['id'];
						}
					}
				}
				
				$row['send_address'] = $row['send_province']." ".$row['send_city']." ".$row['send_district']." ".$row['send_detail'];
				$sql = "select shoptype,appkey, sessionkey, secretkey, shopid, shopname from ".TABLE('shop_config')." where shopid = '".$row['shopid']."'";
				$result = $model->query($sql)->find();
				if($result['shoptype'] == "TB" || $result['shoptype'] == "TM" || $result['shoptype'] == "FX"){
					$c = new TopClient;
					$c->appkey = $result['appkey'];
					$c->secretKey = $result['secretkey'];
					$sessionkey= $result['sessionkey'];
					//实例化具体API对应的Request类
					$req = new CainiaoWaybillIiSearchRequest;
					$req -> setCpCode($row['type']);
					$arr = $c->execute($req,$sessionkey);
					$arr = $arr['waybill_apply_subscription_cols']['waybill_apply_subscription_info'];
					if(is_array($arr))
					{
						foreach($arr as $list){
							$allList = $list['branch_account_cols']['waybill_branch_account'];
							if(!$allList[0])
							{
								$allList = array();
								$allList[0] = $row['branch_account_cols']['waybill_branch_account'];	
							}
		
							foreach($allList as $rowarr)
							{
								$quantity = $rowarr['quantity'];//电子面单余额数量
								$allocated_quantity = $rowarr['allocated_quantity'];//已用面单数量
								$shopid = $row['shopid'];
								$no = $list['cp_code'].'-'.$shopid.'-'. $rowarr['branch_code'];//物流信息编号
								if($data[$no]){
									$data[$no]['quantity'] = $quantity;//电子面单余额数量
									$data[$no]['allocated_quantity'] = $allocated_quantity;//已用面单数量
									$data[$no]['shopname'] = $result['shopname'];//店铺名称
								}
							}
						}
					}
				}else if($result['shoptype'] == "JD"){
					$jos = new JosClient();
			
					$jos->appkey = $result['appkey'];
					$jos->secretKey = $result['secretkey'];
					
					$req = new providerSignSuccessRequest();
					$req->setVendorCode($result['shopid']);
					$arr = $jos->execute($req, $result['sessionkey']);
					if(is_array($arr['resultInfo']['data'])){
						foreach($arr['resultInfo']['data'] as $list){
							$operationType = $list['operationType'];//1 直营，2 加盟
							$providerCode = $list['providerCode'];//承运商编码
							if($GLOBALS['JDWJ'][$providerCode]){//本地快递编码
								$type = $GLOBALS['JDWJ'][$providerCode];
							}else{
								$type = $providerCode;
							}
							$allocated_quantity = "";//已用面单数量
							if($operationType == "1"){
								$no = $type.'-'.$result['shopid'].'-'. $list['settlementCode'];//物流信息编号
								$quantity = "直营型";//电子面单余额数量
							}else{
								$no = $type.'-'.$result['shopid'].'-'. $list['branchCode'];//物流信息编号
								$quantity = $list['amount'];//电子面单余额数量
							}
							if($data[$no]){
								$data[$no]['quantity'] = $quantity;//电子面单余额数量
								$data[$no]['allocated_quantity'] = $allocated_quantity;//已用面单数量
								$data[$no]['shopname'] = $result['shopname'];//店铺名称
							}
						}
					}
				}else if($result['shoptype'] == "PDD"){
					$token_data = array('shopid' => $result['shopid']);
					$c = M('system/getTempToken');
					$c->platType = 'PDD';
					$c->appkey = $result['appkey'];
					$c->secretKey = $result['secretkey'];
					$c->sessionKey = $result['sessionkey'];
					$resultToken = $c->getTempToken($token_data);
					
					$timestamp = time();
					$c = new OpenApiPDDModelClass();
					$c->appkey = $result['appkey'];
					$c->secretKey = $result['secretkey'];
					
					$req = new waybillSearchPDDModelClass();
					$req->setMallId($result['appkey']);
					$req->setTimeStamp($timestamp);
					$arr = $c->execute($req, $resultToken['access_token']);
					$dataArr = json_decode($arr, true);
					
					if(is_array($dataArr['pdd_waybill_search_response']['waybill_apply_subscription_cols'])){
						foreach($dataArr['pdd_waybill_search_response']['waybill_apply_subscription_cols'] as $list){
							$operationType = $list['wp_type'];//1 加盟型
							$providerCode = $list['wp_code'];//物流服务商ID
							if($GLOBALS['PDD_JHQ'][$providerCode]){//本地快递编码
								$type = $GLOBALS['PDD_JHQ'][$providerCode];
							}else{
								$type = $providerCode;
							}
							$branch_account_cols = $list['branch_account_cols'];
							if(is_array($branch_account_cols)){
								foreach($branch_account_cols as $branch_account_col){
									$no = $type.'-'.$result['shopid'].'-'. $branch_account_col['branch_code'];//物流信息编号
									$data[$no]['quantity'] = $branch_account_col['quantity'];//电子面单余额数量
									$data[$no]['allocated_quantity'] = $branch_account_col['allocated_quantity'];//已用面单数量
									$data[$no]['shopname'] = $result['shopname'];//店铺名称
								}
							}
						}
					}
				}
				
				$data[$key]['send_shopid'] =  $send_erpress_type;
				$data[$key]['send_shopids'] =  $send_shopids;
			}
			$rows = array();
			foreach($data as $row)
			{
				$rows[] = $row;
			}
			$re=array(
				"draw" => "false",
				"recordsTotal" =>$cuont,
				"recordsFiltered" =>$cuont,
				"data" =>$rows
			);
		}else{
			$re=array(
				"draw" => "false",
				"recordsTotal" =>0,
				"recordsFiltered" =>0,
				"data" =>$rows
			);
		}
        return $re;
    }
	
	public function getExpressJd($param)
    {
		$model = D();
		
		$result = array('code' => 'ok');
		
		$sql = "SELECT 1 FROM ".TABLE('express')." WHERE `type`='JDKD'";
		if(!$model->query($sql)->find()){
			$model->execute("INSERT INTO ".TABLE('express')."(shopid,`no`,express_id,`name`,sort_name,`type`,type_name,
							`default`,`status`,site,send_province,send_city,send_district,send_detail,send_username,
							send_tel,print_province,print_city,print_district,print_detail,ratio,assist_print,send_address_json)VALUES
							('','JDKD','JDKD','京东快递','京','JDKD','京东快递','0','0','','','','','','','','','','','','0','0','')");
		}
		
		$model->execute("UPDATE " . TABLE('Express') . " SET express_id = CONCAT(`type`,'_',`id`) WHERE express_id = ''");
		
		return $result;
	}
	
	public function getExpressJdYth($param)
    {
		$model = D();
		
		$result = array('code' => 'ok');
		
		$sql = "SELECT 1 FROM ".TABLE('express')." WHERE `type`='JDKD_YTH'";
		if(!$model->query($sql)->find()){
			$model->execute("INSERT INTO ".TABLE('express')."(shopid,`no`,express_id,`name`,sort_name,`type`,type_name,
							`default`,`status`,site,send_province,send_city,send_district,send_detail,send_username,
							send_tel,print_province,print_city,print_district,print_detail,ratio,assist_print,send_address_json)VALUES
							('','JDKD_YTH','JDKD_YTH','京东快递一体化','京','JDKD_YTH','京东快递一体化','0','0','','','','','','','','','','','','0','0','')");
		}
		
		$model->execute("UPDATE " . TABLE('Express') . " SET express_id = CONCAT(`type`,'_',`id`) WHERE express_id = ''");
		
		return $result;
	}
	
	public function getExpressJdYthCod($param)
    {
		$model = D();
		
		$result = array('code' => 'ok');
		
		$sql = "SELECT 1 FROM ".TABLE('express')." WHERE `type`='JDKD_YTH_COD'";
		if(!$model->query($sql)->find()){
			$model->execute("INSERT INTO ".TABLE('express')."(shopid,`no`,express_id,`name`,sort_name,`type`,type_name,
							`default`,`status`,site,send_province,send_city,send_district,send_detail,send_username,
							send_tel,print_province,print_city,print_district,print_detail,ratio,assist_print,send_address_json,express_form)VALUES
							('','JDKD_YTH_COD','JDKD_YTH_COD','京东到付外单','京','JDKD_YTH_COD','京东到付外单','0','0','','','','','','','','','','','','0','0','','JDKD_YTH_COD')");
		}
		
		$model->execute("UPDATE " . TABLE('Express') . " SET express_id = CONCAT(`type`,'_',`id`) WHERE express_id = ''");
		
		return $result;
	}
	
	public function getShopJD(){
		$model = D();
		
		$result = array();
		$sql = "select shopid,shopname from ".TABLE('shop_config')." where shoptype='JD' ";
		if($rows = $model->query($sql)->select()){
			foreach($rows as $list){
				$result[] = array('shopid' => $list['shopid'], 'shopname' => $list['shopname']);
			}
		}
		
		return $result;
	}
	
	public function getExpresszjzto($param)
    {
		$model = D();
		$result = array('code' => 'ok');
		$sql = "SELECT 1 FROM ".TABLE('express')." WHERE `no`='ZJ_ZTO'";
		if(!$model->query($sql)->find()){
			$row = $model->execute("INSERT INTO ".TABLE('express')."(shopid,`no`,`name`,sort_name,`type`,type_name,
							`default`, `status`, site,send_province, send_city, send_district, send_detail, send_username,
							send_tel, print_province, print_city, print_district, print_detail, ratio, assist_print, send_address_json,express_form )VALUES ('', 'ZJ_ZTO', '直接-中通', '中', 'ZTO', '直接-中通', '0', '0','','','','','','','','','','','','0','0','','ZJ_ZTO')");
		}
		
		$model->execute("UPDATE " . TABLE('Express') . " SET express_id = CONCAT(`type`,'_',`id`) WHERE express_id = ''");
		return $row;
	}
	
	public function getExpresszjsf($param){
		$model = D();
		$result = array('code' => 'ok');
		$sql = "SELECT 1 FROM ".TABLE('express')." WHERE `no`='ZJ_SF'";
		if(!$model->query($sql)->find()){
			$row = $model->execute("INSERT INTO ".TABLE('express')."(shopid,`no`,`name`,sort_name,`type`,type_name,
							`default`, `status`, site,send_province, send_city, send_district, send_detail, send_username,
							send_tel, print_province, print_city, print_district, print_detail, ratio, assist_print, send_address_json,express_form )VALUES ('', 'ZJ_SF', '直接-顺丰', '顺', 'SF', '直接-顺丰', '0', '0','','','','','','','','','','','','0','0','','ZJ_SF')");
		}
		
		$model->execute("UPDATE " . TABLE('Express') . " SET express_id = CONCAT(`type`,'_',`id`) WHERE express_id = ''");
		return $row;
	}
	
	
    public function getExpressTaobao($param)
    {
        $model = D();
		//echo '23058998  87a57cf4c552f4357785fcdce728253f  6200e184d7b63d79391d2319b2ZZ6fc56166c05deec3580881151298';
        $sql = "select appkey, sessionkey, secretkey, shopid from ".TABLE('shop_config')." where shoptype in ('TB','TM','FX') and `status` = '1'";
		$result = $model->query($sql)->select();
		include_once("SDK/taobao-sdk/TopSdk.php");
        include_once("SDK/taobao-sdk/top/request/LogisticsAddressSearchRequest.php");
        for($i = 0; $i < count($result); $i++){
			$c = new TopClient;
			$c->appkey = $result[$i]['appkey'];
			$c->secretKey = $result[$i]['secretkey'];
			$sessionkey= $result[$i]['sessionkey'];
			//实例化具体API对应的Request类
			$req = new CainiaoWaybillIiSearchRequest;
			$arr = $c->execute($req,$sessionkey);
			$arr = $arr['waybill_apply_subscription_cols']['waybill_apply_subscription_info'];
			$int = 0;
			if(is_array($arr))
			{
				foreach($arr as $row){
					$int = $int+1;
					$allList = $row['branch_account_cols']['waybill_branch_account'];
					if(!$allList[0])
					{
						$allList = array();
						$allList[0] = $row['branch_account_cols']['waybill_branch_account'];	
					}
					foreach($allList as $rowarr)
					{
						$send_addressall = $rowarr['shipp_address_cols'];
						$send_address_json = json_encode($rowarr['shipp_address_cols']);
						$quantity = $rowarr['quantity'];//电子面单余额数量
						$allocated_quantity = $rowarr['allocated_quantity'];//已用面单数量
						$site = $rowarr['branch_name'];//网点名称
						$shopid = $result[$i]['shopid'];
						$no = $row['cp_code'].'-'.$shopid.'-'. $rowarr['branch_code'];//物流信息编号
						$type = $row['cp_code'];
						$name = $GLOBALS['WL'][$type]['WL_NAME'];//物流信息名称
						$sort_name = $GLOBALS['WL'][$type]['SORT_NAME'];//物流信息名称
						$send_city = $send_addressall['address_dto'][0]['city'];//发货地址
						$send_detail = $send_addressall['address_dto'][0]['detail'];//发货地址
						$send_district = $send_addressall['address_dto'][0]['district'];//发货地址
						$send_province = $send_addressall['address_dto'][0]['province'];//发货地址

						if(!$send_city){
							$send_city = $send_addressall['address_dto']['city'];//发货地址
						}
						if(!$send_detail){
							$send_detail = $send_addressall['address_dto']['detail'];//发货地址
						}
						if(!$send_district){
							$send_district = $send_addressall['address_dto']['district'];//发货地址
						}
						if(!$send_province){
							$send_province = $send_addressall['address_dto']['province'];//发货地址
						}
                        $sql = "select 1 from ".TABLE('Express')." where no = :no";
                        if(!$model->query($sql,array(
                            array('name' => ':no', 'value' =>$no, 'type' => PDO::PARAM_STR),
                        ))->find()){
                            $model->execute("update " . TABLE('Express')." set status='1' where type='$type'");
                            $res = $model->execute("insert into " . TABLE('Express')."(shopid,no, name, sort_name, type,type_name,`default`,status,site,send_province,send_city,send_district,send_detail,send_address_json,express_form)
							VALUES (:shopid,:no,:name,:sort_name,:type,:type_name,'0','0',:site,:send_province,:send_city,:send_district,:send_detail,:send_address_json,'cainiao')",array(
								array('name' => ':shopid', 'value' =>$shopid, 'type' => PDO::PARAM_STR),
								array('name' => ':no', 'value' =>$no, 'type' => PDO::PARAM_STR),
								array('name' => ':name', 'value' =>$name, 'type' => PDO::PARAM_STR),
								array('name' => ':sort_name', 'value' =>$sort_name, 'type' => PDO::PARAM_STR),
								array('name' => ':type', 'value' =>$type, 'type' => PDO::PARAM_STR),
								array('name' => ':type_name', 'value' =>$name, 'type' => PDO::PARAM_STR),
								array('name' => ':site', 'value' =>$site, 'type' => PDO::PARAM_STR),
								array('name' => ':send_province', 'value' =>$send_province, 'type' => PDO::PARAM_STR),
								array('name' => ':send_city', 'value' =>$send_city, 'type' => PDO::PARAM_STR),
								array('name' => ':send_district', 'value' =>$send_district, 'type' => PDO::PARAM_STR),
								array('name' => ':send_detail', 'value' =>$send_detail, 'type' => PDO::PARAM_STR),
								array('name' => ':send_address_json', 'value' =>$send_address_json, 'type' => PDO::PARAM_STR),
							));
    						if($res !== false)
    						{
    							//物流模版空
                                $sql = "SELECT 1 FROM ".TABLE('print_tpl')." WHERE express_no='$type'";
                                if(!$model->query($sql)->find()){
                                    //创建默认模版
                                    $dzmdDesignModel = M('print/dzmdDesign');
                                    $param = array();
                                    $param['template_id'] = $GLOBALS['WL'][$type]['TEMPLATE_ID'];
                                    $param['template_name'] = $GLOBALS['WL'][$type]['WL_NAME'];
                                    $param['template_url'] = $GLOBALS['WL'][$type]['TEMPLATE_URL'];
                                    $param['tpl_name'] = $GLOBALS['WL'][$type]['WL_NAME'].'标准快递';
                                    $param['express_no'] = $type;
                                    $param['type'] = 'YUN';
                                    $createResult = $dzmdDesignModel->creatTemplate($param);
                                    if($createResult['code'] == 'ok'){
                                        $param = array();
                                        $param['id'] = $createResult['id'];
                                        $param['tpl_name'] = $GLOBALS['WL'][$type]['WL_NAME'].'标准快递';
                                        $param['express_no'] = $type;
                                        $param['def'] = 'T';
                                        $param['express_logo'] = 'T';
                                        $param['tpl_json'] = '{"1503383689859":{"ID":1503383689859,"FIELD":"print_date","VALUE":"打印时间","FIELD_TYPE":"","TYPE":"field","newLeft":0,"newTop":0,"newWidth":150,"newHeight":20,"newFontSize":12,"STYLE":{"left":"2px","bottom":"1.17188px","width":"150px","top":"119px","right":"225.938px","height":"20px","position":"absolute"}},"1503383691762":{"ID":1503383691762,"FIELD":"tid","VALUE":"订单号","FIELD_TYPE":"","TYPE":"field","newLeft":0,"newTop":0,"newWidth":150,"newHeight":20,"newFontSize":12,"STYLE":{"left":"225px","bottom":"-0.828125px","width":"150px","top":"121px","right":"2.9375px","height":"20px","position":"absolute"}},"1503383749383":{"ID":1503383749383,"FIELD":"tableGrid","VALUE":"","FIELD_TYPE":"","TYPE":"gridList","newLeft":0,"newTop":0,"newWidth":200,"newHeight":100,"newFontSize":12,"STYLE":{"left":"0px","bottom":"64.1719px","width":"377px","top":"0px","right":"0.9375px","height":"87px","position":"absolute"},"tdName":["prd_no","sku","qty"]}}';
                                        $dzmdDesignModel->saveTemplate($param);
                                    }
                                }
    						}
                        }else{
							 $model->execute("update " . TABLE('Express')." set site=:site,send_province=:send_province,send_city=:send_city,send_district=:send_district,send_detail=:send_detail,send_address_json=:send_address_json where no=:no",array(
								array('name' => ':shopid', 'value' =>$shopid, 'type' => PDO::PARAM_STR),
								array('name' => ':no', 'value' =>$no, 'type' => PDO::PARAM_STR),
								array('name' => ':name', 'value' =>$name, 'type' => PDO::PARAM_STR),
								array('name' => ':sort_name', 'value' =>$sort_name, 'type' => PDO::PARAM_STR),
								array('name' => ':type', 'value' =>$type, 'type' => PDO::PARAM_STR),
								array('name' => ':type_name', 'value' =>$name, 'type' => PDO::PARAM_STR),
								array('name' => ':site', 'value' =>$site, 'type' => PDO::PARAM_STR),
								array('name' => ':send_province', 'value' =>$send_province, 'type' => PDO::PARAM_STR),
								array('name' => ':send_city', 'value' =>$send_city, 'type' => PDO::PARAM_STR),
								array('name' => ':send_district', 'value' =>$send_district, 'type' => PDO::PARAM_STR),
								array('name' => ':send_detail', 'value' =>$send_detail, 'type' => PDO::PARAM_STR),
								array('name' => ':send_address_json', 'value' =>$send_address_json, 'type' => PDO::PARAM_STR),
							));
						}
					}
				}
			}
			
		}
		
		$model->execute("UPDATE " . TABLE('Express') . " SET express_id = CONCAT(`type`,'_',`id`) WHERE express_id = ''");
        return $int;
    }
	
	public function getExpressJdwj($param)
    {
        $model = D();
		//echo '23058998  87a57cf4c552f4357785fcdce728253f  6200e184d7b63d79391d2319b2ZZ6fc56166c05deec3580881151298';
        $sql = "select appkey, sessionkey, secretkey, shopid from ".TABLE('shop_config')." where shoptype = 'JD' ";//and `status` = '1'
		$result = $model->query($sql)->select();
		include_once("SDK/jd_SDK/JosClient.php");
		include_once("SDK/jd_SDK/JosRequest.php");
        include_once("SDK/jd_SDK/request/providerSignSuccessRequest.php");
		
        for($i = 0; $i < count($result); $i++){
			$shopid = $result[$i]['shopid'];
			
			$jos = new JosClient();
			
			$jos->appkey = $result[$i]['appkey'];
			$jos->secretKey = $result[$i]['secretkey'];
			
			$req = new providerSignSuccessRequest();
			$req->setVendorCode($shopid);
			$data = $jos->execute($req, $result[$i]['sessionkey']);
			$int = 0;
			
			
			if(is_array($data['resultInfo']['data']))
			{
				foreach($data['resultInfo']['data'] as $row){
					$int = $int + 1;
					
					$operationType = $row['operationType'];//1 直营，2 加盟
					$providerCode = $row['providerCode'];//承运商编码
					if($GLOBALS['JDWJ'][$providerCode]){//本地快递编码
						$type = $GLOBALS['JDWJ'][$providerCode];
					}else{
						$type = $providerCode;
					}
					
					if($operationType == "1"){
						$no = $type.'-'.$shopid.'-'. $row['settlementCode'];//物流信息编号
						$site = "直营型";//网点名称						
					}else{
						$no = $type.'-'.$shopid.'-'. $row['branchCode'];//物流信息编号
						$site = $row['branchName'];//网点名称
					}
					
					$name = $GLOBALS['WL'][$type]['WL_NAME'];//物流信息名称
					$sort_name = $GLOBALS['WL'][$type]['SORT_NAME'];//物流信息名称
					$send_province = $row['address']['provinceName'];//发货地址 省
					$send_city = $row['address']['cityName'];//发货地址 市
					$send_district = $row['address']['countryName'];//发货地址 区
					$send_detail = $row['address']['address'];//发货地址 详细地址
					$send_address_array = array('address_dto' => array(0 => array('city' => $send_city, 'detail' => $send_detail, 'district' => $send_district, 'province' => $send_province)));
					$send_address_json = json_encode($send_address_array);
					$express_config = json_encode($row);
					$sql = "select 1 from ".TABLE('Express')." where no = :no";
					if(!$model->query($sql,array(
						array('name' => ':no', 'value' => $no, 'type' => PDO::PARAM_STR)))->find()){
						$model->execute("update " . TABLE('Express')." set status='1' where type='".$type."'");
						$res = $model->execute("insert into " . TABLE('Express')."(shopid,no, name, sort_name, type,type_name,`default`,status,site,send_province,send_city,send_district,send_detail,send_address_json,express_form,express_config)
						VALUES (:shopid,:no,:name,:sort_name,:type,:type_name,'0','0',:site,:send_province,:send_city,:send_district,:send_detail,:send_address_json,'wujie',:express_config)",array(
							array('name' => ':shopid', 'value' =>$shopid, 'type' => PDO::PARAM_STR),
							array('name' => ':no', 'value' =>$no, 'type' => PDO::PARAM_STR),
							array('name' => ':name', 'value' =>$name, 'type' => PDO::PARAM_STR),
							array('name' => ':sort_name', 'value' =>$sort_name, 'type' => PDO::PARAM_STR),
							array('name' => ':type', 'value' =>$type, 'type' => PDO::PARAM_STR),
							array('name' => ':type_name', 'value' =>$name, 'type' => PDO::PARAM_STR),
							array('name' => ':site', 'value' =>$site, 'type' => PDO::PARAM_STR),
							array('name' => ':send_province', 'value' =>$send_province, 'type' => PDO::PARAM_STR),
							array('name' => ':send_city', 'value' =>$send_city, 'type' => PDO::PARAM_STR),
							array('name' => ':send_district', 'value' =>$send_district, 'type' => PDO::PARAM_STR),
							array('name' => ':send_detail', 'value' =>$send_detail, 'type' => PDO::PARAM_STR),
							array('name' => ':send_address_json', 'value' =>$send_address_json, 'type' => PDO::PARAM_STR),
							array('name' => ':express_config', 'value' =>$express_config, 'type' => PDO::PARAM_STR),
						));
						if($res !== false)
						{
							//物流模版空
							/*$sql = "SELECT 1 FROM ".TABLE('print_tpl')." WHERE express_no='$type'";
							if(!$model->query($sql)->find()){
								//创建默认模版
								$dzmdDesignModel = M('print/dzmdDesign');
								$param = array();
								$param['template_id'] = $GLOBALS['WL'][$type]['TEMPLATE_ID'];
								$param['template_name'] = $GLOBALS['WL'][$type]['WL_NAME'];
								$param['template_url'] = $GLOBALS['WL'][$type]['TEMPLATE_URL'];
								$param['tpl_name'] = $GLOBALS['WL'][$type]['WL_NAME'].'标准快递';
								$param['express_no'] = $type;
								$param['type'] = 'YUN';
								$createResult = $dzmdDesignModel->creatTemplate($param);
								if($createResult['code'] == 'ok'){
									$param = array();
									$param['id'] = $createResult['id'];
									$param['tpl_name'] = $GLOBALS['WL'][$type]['WL_NAME'].'标准快递';
									$param['express_no'] = $type;
									$param['def'] = 'T';
									$param['express_logo'] = 'T';
									$param['tpl_json'] = '{"1503383689859":{"ID":1503383689859,"FIELD":"print_date","VALUE":"打印时间","FIELD_TYPE":"","TYPE":"field","newLeft":0,"newTop":0,"newWidth":150,"newHeight":20,"newFontSize":12,"STYLE":{"left":"2px","bottom":"1.17188px","width":"150px","top":"119px","right":"225.938px","height":"20px","position":"absolute"}},"1503383691762":{"ID":1503383691762,"FIELD":"tid","VALUE":"订单号","FIELD_TYPE":"","TYPE":"field","newLeft":0,"newTop":0,"newWidth":150,"newHeight":20,"newFontSize":12,"STYLE":{"left":"225px","bottom":"-0.828125px","width":"150px","top":"121px","right":"2.9375px","height":"20px","position":"absolute"}},"1503383749383":{"ID":1503383749383,"FIELD":"tableGrid","VALUE":"","FIELD_TYPE":"","TYPE":"gridList","newLeft":0,"newTop":0,"newWidth":200,"newHeight":100,"newFontSize":12,"STYLE":{"left":"0px","bottom":"64.1719px","width":"377px","top":"0px","right":"0.9375px","height":"87px","position":"absolute"},"tdName":["prd_no","sku","qty"]}}';
									$dzmdDesignModel->saveTemplate($param);
								}
							}*/
						}
					}
				}
			}
			
		}
		
		$model->execute("UPDATE " . TABLE('Express') . " SET express_id = CONCAT(`type`,'_',`id`) WHERE express_id = ''");
        return $int;
    }
	
	public function getExpressPdd($param)
    {
        $model = D();
		//echo '23058998  87a57cf4c552f4357785fcdce728253f  6200e184d7b63d79391d2319b2ZZ6fc56166c05deec3580881151298';
        $sql = "select appkey, sessionkey, secretkey, shopid from ".TABLE('shop_config')." where shoptype = 'PDD' ";//and `status` = '1' 
		$result = $model->query($sql)->select();
		include_once("SDK/pinduoduo-SDK/model/OpenApiPDDModel.Class.php");
        include_once("SDK/pinduoduo-SDK/model/waybillSearchPDDModel.Class.php");
		
        for($i = 0; $i < count($result); $i++){
			$shopid = $result[$i]['shopid'];
			
			$token_data = array('shopid' => $shopid);
			$c = M('system/getTempToken');
			$c->platType = 'PDD';
			$c->appkey = $result[$i]['appkey'];
			$c->secretKey = $result[$i]['secretkey'];
			$c->sessionKey = $result[$i]['sessionkey'];
			$resultToken = $c->getTempToken($token_data);
			
			$timestamp = time();
			$c = new OpenApiPDDModelClass();
			$c->appkey = $result[$i]['appkey'];
			$c->secretKey = $result[$i]['secretkey'];
			
			$req = new waybillSearchPDDModelClass();
			$req->setMallId($result[$i]['appkey']);
			$req->setTimeStamp($timestamp);
			$arr = $c->execute($req, $resultToken['access_token']);
			$data = json_decode($arr, true);
			$int = 0;
			
			if(is_array($data['pdd_waybill_search_response']['waybill_apply_subscription_cols']))
			{
				foreach($data['pdd_waybill_search_response']['waybill_apply_subscription_cols'] as $row){
					$int = $int + 1;
					
					$operationType = $row['wp_type'];//1 加盟型
					$providerCode = $row['wp_code'];//物流服务商ID
					if($GLOBALS['PDD_JHQ'][$providerCode]){//本地快递编码
						$type = $GLOBALS['PDD_JHQ'][$providerCode];
					}else{
						$type = $providerCode;
					}
					
					$branch_account_cols = $row['branch_account_cols'];
					if(is_array($branch_account_cols)){
						foreach($branch_account_cols as $branch_account_col){
							$no = $type.'-'.$shopid.'-'. $branch_account_col['branch_code'];//物流信息编号
							$site = $branch_account_col['branch_name'];//网点名称
							
							$name = $GLOBALS['WL'][$type]['WL_NAME'];//物流信息名称
							$sort_name = $GLOBALS['WL'][$type]['SORT_NAME'];//物流信息名称
							$send_province = $branch_account_col['shipp_address_cols'][0]['province'];//发货地址 省
							$send_city = $branch_account_col['shipp_address_cols'][0]['city'];//发货地址 市
							$send_district = $branch_account_col['shipp_address_cols'][0]['district'];//发货地址 区
							$send_detail = $branch_account_col['shipp_address_cols'][0]['detail'];//发货地址 详细地址
							$send_address_array = array('address_dto' => array(0 => array('city' => $send_city, 'detail' => $send_detail, 'district' => $send_district, 'province' => $send_province)));
							$send_address_json = json_encode($send_address_array);
							$express_config = json_encode($branch_account_col);
							$sql = "select 1 from ".TABLE('Express')." where no = :no";
							if(!$model->query($sql,array(
								array('name' => ':no', 'value' => $no, 'type' => PDO::PARAM_STR)))->find()){
								$model->execute("update " . TABLE('Express')." set status='1' where type='".$type."'");
								$res = $model->execute("insert into " . TABLE('Express')."(shopid,no, name, sort_name, type,type_name,`default`,status,site,send_province,send_city,send_district,send_detail,send_address_json,express_form,express_config)
								VALUES (:shopid,:no,:name,:sort_name,:type,:type_name,'0','0',:site,:send_province,:send_city,:send_district,:send_detail,:send_address_json,'pinduoduo',:express_config)",array(
									array('name' => ':shopid', 'value' =>$shopid, 'type' => PDO::PARAM_STR),
									array('name' => ':no', 'value' =>$no, 'type' => PDO::PARAM_STR),
									array('name' => ':name', 'value' =>$name, 'type' => PDO::PARAM_STR),
									array('name' => ':sort_name', 'value' =>$sort_name, 'type' => PDO::PARAM_STR),
									array('name' => ':type', 'value' =>$type, 'type' => PDO::PARAM_STR),
									array('name' => ':type_name', 'value' =>$name, 'type' => PDO::PARAM_STR),
									array('name' => ':site', 'value' =>$site, 'type' => PDO::PARAM_STR),
									array('name' => ':send_province', 'value' =>$send_province, 'type' => PDO::PARAM_STR),
									array('name' => ':send_city', 'value' =>$send_city, 'type' => PDO::PARAM_STR),
									array('name' => ':send_district', 'value' =>$send_district, 'type' => PDO::PARAM_STR),
									array('name' => ':send_detail', 'value' =>$send_detail, 'type' => PDO::PARAM_STR),
									array('name' => ':send_address_json', 'value' =>$send_address_json, 'type' => PDO::PARAM_STR),
									array('name' => ':express_config', 'value' =>$express_config, 'type' => PDO::PARAM_STR),
								));
							}
						}	
					}
				}
			}
			
		}
		
		$model->execute("UPDATE " . TABLE('Express') . " SET express_id = CONCAT(`type`,'_',`id`) WHERE express_id = ''");
        return $int;
    }
	
    public function addChildAccount($param){
        $model = D();
        $userid = $param['userid'];
        $username = $param['username'];
        $mobile = $param['mobile'];
		$password = $param['password'];
        $create_login_time=time();
        $STATUS= $param['STATUS'] ? 0 : 1;
		
		$modelSYS = D_SYS();
		$system_id = $_SESSION['LOGIN_SYSTEM_ID'];
		
		$sqlup = "SELECT user_count FROM userlist where system_id='".$system_id."' ";//取发货单价
		$rowup = $modelSYS->query($sqlup)->find();
		$user_count = $rowup['user_count'];
		if($user_count > 0){
			if($rowShop = $model->query("select count(1) as count from " . TABLE('usertable')." ")->find()){
				if($rowShop['count'] > $user_count){
					return array('code' => 'no', 'msg' => '添加失败！！，允许添加的用户数已超过最大限制');
				}
			}
		}
		
		if(!$model->query("select 1 from " . TABLE('usertable')." where userid=:userid",array(array('name' => ':userid', 'value' =>$userid, 'type' => PDO::PARAM_STR)))->find()){
			$model->execute("insert into   " . TABLE('usertable')."(userid,username, password, mobile,create_login_time,type,status)
			VALUES ( :userid,:username, :password, :mobile,:create_login_time, :type, :status  )",   array(
				array('name' => ':userid', 'value' =>$userid, 'type' => PDO::PARAM_STR),
				array('name' => ':username', 'value' =>$username, 'type' => PDO::PARAM_STR),
				array('name' => ':password', 'value' =>md5($userid . md5($password)), 'type' => PDO::PARAM_STR),
				array('name' => ':mobile', 'value' =>$mobile, 'type' => PDO::PARAM_STR),
				array('name' => ':create_login_time', 'value' =>$create_login_time, 'type' => PDO::PARAM_STR),
				array('name' => ':type', 'value' =>'sub', 'type' => PDO::PARAM_STR),
				array('name' => ':status', 'value' =>$STATUS, 'type' => PDO::PARAM_STR),
			));


			if($model>0){
				return array('code' => 'ok', 'msg' => '添加成功！！');
			}
			else{
				return array('code' => 'no', 'msg' => '添加失败！！');
			}
		}else{
			return array('code' => 'no', 'msg' => '此id已存在！！');
		}

        

    }
    public function editChildAccount($param){
        $model = D();
        $username = $param['username'];
		$userid = $param['userid'];
        $id = $param['id'];
        $mobile = $param['mobile'];
        //$create_login_time=time();
        $STATUS= $param['STATUS'] ? 0 : 1;
		$password = $param['password'];
		if($password == '******')
		{
			$model->execute("update  " . TABLE('usertable') . " set 
			username ='" .$username . "',mobile ='" .$mobile . "',STATUS='".$STATUS."' 
			where id=:id", array(array('name' => ':id', 'value' => $id, 'type' => PDO::PARAM_STR)));
		}
		else
		{
			$model->execute("update  " . TABLE('usertable') . " set 
			username ='" .$username . "',mobile ='" .$mobile . "',STATUS='".$STATUS."', password='".md5($userid . md5($password))."'
			where id=:id", array(array('name' => ':id', 'value' => $id, 'type' => PDO::PARAM_STR)));	
		}
        if($model>0){
            return array('code' => 'ok', 'msg' => '修改成功！！');
        }
        else{
            return array('code' => 'no', 'msg' => '修改失败！！');
        }
    }
    public function delChildAccount($param){
        $model = D();
        $ids = $param['ids'];


        $model->execute("delete from   " . TABLE('usertable') . "   WHERE FIND_IN_SET(id,:id)  ", array(array('name' => ':id', 'value' => $ids, 'type' => PDO::PARAM_STR)));

        if($model>0){
            return array('code' => 'ok', 'msg' => '删除成功！！');
        }
        else{
            return array('code' => 'no', 'msg' => '删除失败！！');
        }
    }
    public function getChildAccount($param)
    {
        $ordercol =$param["mDataProp_".$param["iSortCol_0"]];
        $desc=$param["sSortDir_0"];
        $keyword=$param["keyword"];
        $iDisplayStart=$param["iDisplayStart"];    //  iDisplayStart:10 第3页
        $iDisplayLength=$param["iDisplayLength"];  //  iDisplayLength:5
        $cuont=0;
        $admintype='system';
        $model = D();
        //获取总数
        if (!$rowcount = $model->query("SELECT  count(id) as count FROM   " . TABLE('usertable')." 
        where (userid like :userid or username like :username or mobile like :mobile ) and  `type` <> :type",
            array(
                array('name' => ':userid', 'value' => "%".$keyword."%", 'type' => PDO::PARAM_STR),
                array('name' => ':username', 'value' => "%".$keyword."%", 'type' => PDO::PARAM_STR),
                array('name' => ':mobile', 'value' => "%".$keyword."%", 'type' => PDO::PARAM_STR),
                array('name' => ':type', 'value' => $admintype , 'type' => PDO::PARAM_STR)
            ))->find()) {
            return array();
        }else{
            $cuont=$rowcount["count"];
        }
        //获取数据
       $row = $model->query("SELECT id,userid,username,mobile,create_login_time,STATUS FROM   " . TABLE('usertable')." 
       where (userid like :userid or username like :username or mobile like :mobile)   and  `type` <> :type  order by  $ordercol  $desc ",
        array(
            array('name' => ':userid', 'value' => "%".$keyword."%", 'type' => PDO::PARAM_STR),
            array('name' => ':username', 'value' => "%".$keyword."%", 'type' => PDO::PARAM_STR),
            array('name' => ':mobile', 'value' => "%".$keyword."%", 'type' => PDO::PARAM_STR),
            array('name' => ':type', 'value' => $admintype , 'type' => PDO::PARAM_STR)
        ))->limitPage($iDisplayStart,$iDisplayLength)->select();
		if($row){
			for($i = 0; $i < count($row); $i++){
				$row[$i]['show_userid'] = $_SESSION['LOGIN_SYSTEM_ID'].':'.$row[$i]['userid'];
				$row[$i]['STATUS'] = $row[$i]['STATUS'] == 0 ? 1 : 0;
				$row[$i]['create_login_time'] = date("Y-m-d H:i:s",$row[$i]['create_login_time']);
			}
		}
		
        //返回数据格式
        $re=array(
            "draw" => "false",
            "recordsTotal" =>$cuont,
            "recordsFiltered" =>$cuont,
            "data" =>$row
        );
        return $re;
    }
	
	//标签排序插入
	public function insertLabel($data)
	{
        $model = D();
		$sum = 0;
		$sqlParam = array(
							array('name' => ':split_str', 'value' => $data[0], 'type' => PDO::PARAM_STR),
							array('name' => ':node1', 'value' => $data[1], 'type' => PDO::PARAM_STR),
							array('name' => ':node2', 'value' => $data[2], 'type' => PDO::PARAM_STR),
							array('name' => ':node3', 'value' => $data[3], 'type' => PDO::PARAM_STR),
							array('name' => ':node4', 'value' => $data[4], 'type' => PDO::PARAM_STR),
							array('name' => ':node5', 'value' => $data[5], 'type' => PDO::PARAM_STR),
							array('name' => ':node6', 'value' => $data[6], 'type' => PDO::PARAM_STR),
							array('name' => ':order_one', 'value' => $data[7], 'type' => PDO::PARAM_STR),
							array('name' => ':print_space', 'value' => $data[8], 'type' => PDO::PARAM_STR),
							array('name' => ':fourCode', 'value' => $data[9], 'type' => PDO::PARAM_STR),
						);
						
		//$sql = "insert into ".TABLE('unique_code_config')."(split_str,node1,node2,node3,node4,node5,node6,order_one,print_space) VALUES(:split_str,:node1,:node2,:node3,:node4,:node5,:node6,:order_one,:print_space)";
		$sql = "select 1 from ".TABLE('unique_code_config')." where split_str <> ''";
		if($model->query($sql)->find()){
			$sql = "update ".TABLE('unique_code_config')." set split_str = :split_str, node1 = :node1, node2 = :node2, node3 = :node3, node4 = :node4, node5 = :node5, node6 = :node6, order_one = :order_one, print_space = :print_space";
		}else{
			$sql = "insert into ".TABLE('unique_code_config')."(split_str,node1,node2,node3,node4,node5,node6,order_one,print_space) VALUES(:split_str,:node1,:node2,:node3,:node4,:node5,:node6,:order_one,:print_space)";
		}
		$sum += $model->execute($sql,$sqlParam);
		$sql = "select 1 from ".TABLE('base_config')." where type='fourCode' and configKey='left'";
		if($row = $model->query($sql,$sqlParam)->find()){
			$model->execute("update ".TABLE('base_config')." set configValue=:fourCode where type='fourCode' and configKey='left'",$sqlParam);
		}else{
			$model->execute("insert into ".TABLE('base_config')."(configKey,configValue,type)values('left',:fourCode,'fourCode')  ",$sqlParam);
		}
		if($sum > 0){
			$result = array('code' => 'ok', 'msg' => '修改成功！！');
		}else{
			$result = array('code' => 'error', 'msg' => '修改失败！！');
		}
		
		return $result;
		
    }
	
	//获取排序插入数据
	public function getLabel()
	{
        $model = D();
		$sql = "select split_str,node1,node2,node3,node4,node5,node6,order_one,print_space from ".TABLE('unique_code_config');
		$result = $model->query($sql)->select();
		$sql = "select configValue from ".TABLE('base_config')." where type='fourCode' and configKey='left'";
		$row = $model->query($sql)->find();
		$result[0]['fourCode'] = $row['configValue'];
		return $result;
    }
	//获取群单码list
	public function getgroupcode()
	{
        $model = D();
		$sql = "SELECT * FROM 
			  (SELECT DISTINCT CONCAT('group-code-',TYPE) AS ids,CONCAT(type_name,'：') AS type_name FROM ".TABLE('express').") AS a
			  LEFT JOIN ".TABLE('base_config')." AS b ON a.ids=b.configKey";
		$result['groupcode'] = $model->query($sql)->select();
		$sql = "SELECT configValue FROM ".TABLE('base_config')." where configKey = 'library_number'";
		$configValue = $model->query($sql)->find();
		$result['library_number'] = $configValue['configValue'];
		$sql = "SELECT configValue FROM ".TABLE('base_config')." where configKey = 'num_library'";
		$configValue = $model->query($sql)->find();
		$result['num_library'] = $configValue['configValue'];
		$sql = "SELECT configValue FROM ".TABLE('base_config')." where configKey = 'item_library'";
		$configValue = $model->query($sql)->find();
		$result['item_library'] = $configValue['configValue'] ? $configValue['configValue'] : 50;
		return $result;
    }//保存群单码list
	public function savegroupcode($param)
	{
        $model = D();
		if($param['savedata']['library_number'] > 500 || $param['savedata']['library_number'] < 50)
		{
			return array('code' => 'error', 'msg' => '库位数需要大于50且小于500！');
		}
		if($param['savedata']['num_library'] > 150 || $param['savedata']['num_library']<1)
		{
			return array('code' => 'error', 'msg' => '库位订单数需要大于1且小于150！');
		}
		$model->execute("delete from ".TABLE("unique_grid_id"));
		for($i = 1; $i <= $param['savedata']['library_number']; $i++)
		{
			$sql = "INSERT INTO ".TABLE("unique_grid_id")." (grid_id,tid_id) values ";
			for($j = 1; $j <= $param['savedata']['num_library']; $j++)
			{
				$sql .= "(".$i.",".$j."),";	
			}	
			$model->execute(rtrim($sql, ','));
		}
		foreach($param['savedata'] as $key=>$value){
			
			$sql = "SELECT 1 from  ".TABLE('base_config')." where configKey=:configKey";
			$row = $model->query($sql,array(array('name' => ':configKey', 'value' => $key, 'type' => PDO::PARAM_STR),))->find();
			
			if($row){
				$sql = "update  ".TABLE('base_config')." set configValue = :configValue where configKey=:configKey";
				$result = $model->execute($sql,array(array('name' => ':configKey', 'value' => $key, 'type' => PDO::PARAM_STR),
									array('name' => ':configValue', 'value' => $value, 'type' => PDO::PARAM_STR),));
			}else{
				$sql = "insert into  ".TABLE('base_config')." (configKey,configValue) values(:configKey,:configValue)";
				$result = $model->execute($sql,array(array('name' => ':configKey', 'value' => $key, 'type' => PDO::PARAM_STR),
									array('name' => ':configValue', 'value' => $value, 'type' => PDO::PARAM_STR),));
			}
		}
		$result = array('code'=>'ok', 'msg'=>'修改成功！');
		return $result;
    }
	
	public function getPhone($data)
	{
		include_once 'api/sendSMSApi.php';
		session_set_cookie_params(60);    //有效期1分钟
		session_start();
		$sendSmsAPI = new sendSmsAPI();
		$mobile = $data['phone'];
		$model = D_SYS();
		$system_id = $_SESSION['LOGIN_SYSTEM_ID'];
		$sqlParam = array(
							array('name' => ':phone', 'value' => $mobile, 'type' => PDO::PARAM_STR),
						);
		
		$sql = "select 1 from userlist where system_id = :phone";
		if($model->query($sql,$sqlParam)->find()){
			$numbers = range (0,9); 
			//shuffle 将数组顺序随即打乱 
			shuffle ($numbers); 
			//array_slice 取该数组中的某一段 
			$num=4; 
			$result = array_slice($numbers,0,$num); 
			$code = $result[0].$result[1].$result[2].$result[3];
			
			$_SESSION['code1']=$code;
			
			$tpl_str = '【超群打单】您的验证码是'.$code.'。请在页面中提交验证码完成验证。';

			$result = $sendSmsAPI->sendSMS($mobile, $tpl_str,'system');
			return json_decode($result);
		}else{
			return array("code"=>"error","msg"=>"请填写正确原手机号");
		}
    }
	
	public function resetPhone($param)
	{
		$model = D_SYS();
		$code = $_SESSION['code1'];
		if($param['phoneRes'] != $code){
			return array("code"=>"error","msg"=>"验证码错误");
		}else{
			$sqlParam = array(
							array('name' => ':phone', 'value' => $param['phone'], 'type' => PDO::PARAM_STR),
							array('name' => ':newPhone', 'value' => $param['newPhone'], 'type' => PDO::PARAM_STR),
						);
				
			$sql = "update userlist set mobile = :newPhone where system_id = :phone";
			$num = $model->execute($sql,$sqlParam);
			if($num > 0){
				return array("code"=>"success","msg"=>"修改成功");
			}else{
				return array("code"=>"error","msg"=>"修改失败");
			}
		}
	}
	
	public function savePrint($param)
	{
		$model = D();
		$shopkeeper = $param['shopkeeper'];
		$shipper = $param['shipper'];
		$num = 0;
		$sqlParam = array(
							array('name' => ':shopkeeper', 'value' => $shopkeeper, 'type' => PDO::PARAM_STR),
							array('name' => ':shipper', 'value' => $shipper, 'type' => PDO::PARAM_STR),
						);
						
		$sql = "select 1 from ".TABLE('base_config')." where configKey='shopkeeper'";
		if($model->query($sql)->find()){
			$sql = "update ".TABLE('base_config')." set configValue=:shopkeeper where configKey='shopkeeper'";
			$num += $model->execute($sql,$sqlParam);
		}else{
			$sql = "insert into ".TABLE('base_config')."(configKey,configValue) VALUES('shopkeeper',:shopkeeper)";
			$num += $model->execute($sql,$sqlParam);
		}
		
		$sql = "select 1 from ".TABLE('base_config')." where configKey='shipper'";
		if($model->query($sql)->find()){
			$sql = "update ".TABLE('base_config')." set configValue=:shipper where configKey='shipper'";
			$num += $model->execute($sql,$sqlParam);
		}else{
			$sql = "insert into ".TABLE('base_config')."(configKey,configValue) VALUES('shipper',:shipper)";
			$num += $model->execute($sql,$sqlParam);
		}
		
		if($num > 0){
			return array("code"=>"ok","msg"=>"修改成功");
		}else{
			return array("code"=>"error","msg"=>"修改失败");
		}
		
	}
	
	public function getPrint()
	{
		$model = D();
		
		$sql = "select configValue from ".TABLE('base_config')." where configKey='shopkeeper'";
		$shopkeeper = $model->query($sql)->find();
		$sql = "select configValue from ".TABLE('base_config')." where configKey='shipper'";
		$shipper = $model->query($sql)->find();
		return array("shopkeeper"=>$shopkeeper['configValue'],"shipper"=>$shipper['configValue']);
	}
	
	public function saveStorageType($param){
		$model = D();
		
		$storage_type = $param['storage_type'];
		
		$sqlParam = array(
			array('name' => ':storage_type', 'value' => $storage_type, 'type' => PDO::PARAM_STR),
		);
		
		$sql = "select 1 from ".TABLE('base_config')." where type='storage_type'";
		if($row = $model->query($sql,$sqlParam)->find()){
			$model->execute("update ".TABLE('base_config')." set configKey=:storage_type,configValue=:storage_type where type='storage_type' ",$sqlParam);
		}else{
			$model->execute("insert into ".TABLE('base_config')."(configKey,configValue,type)values(:storage_type,:storage_type,'storage_type')  ",$sqlParam);
		}
		
		return array("code" => "ok");
	}
	
	public function getStorageType(){
		$model = D();
		
		$storage_type = "";
		
		$sql = "select configKey from ".TABLE('base_config')." where type='storage_type'";
		if($row = $model->query($sql)->find()){
			$storage_type = $row['configKey'];
		}
		
		if($storage_type == ""){
			$storage_type = "shop";
		}
		
		return array('storage_type' => $storage_type);
	}
	
	public function saveApproval($param){
		$model = D();
		$data = $param['data'];
		
		$autoApproval = $data['autoApproval'];//自动审单
		$contain_prd = $data['contain_prd'];//指定商品
		$contain_prd = str_replace("，",",",$contain_prd);
		$exclude_prd = $data['exclude_prd'];//排除商品
		$exclude_prd = str_replace("，",",",$exclude_prd);
		$min_qty = $data['min_qty'];//数量范围
		$max_qty = $data['max_qty'];//数量范围
		$min_payment = $data['min_payment'];//金额范围
		$max_payment = $data['max_payment'];//金额范围
		$ignore_buyer_message = $data['ignore_buyer_message'];//忽略买家留言
		$no_buyer_message = $data['no_buyer_message'];//买家留言不含以下信息
		$no_buyer_message = str_replace("，",",",$no_buyer_message);
		$ignore_seller_memo = $data['ignore_seller_memo'];//忽略卖家备注
		$no_seller_memo = $data['no_seller_memo'];//卖家备注不含以下信息
		$no_seller_memo = str_replace("，",",",$no_seller_memo);
		
		$contain_flag = "";//指定小旗
		if($data['contain_flag_1'] == 'on'){
			$contain_flag .= "1,";
		}
		if($data['contain_flag_2'] == 'on'){
			$contain_flag .= "2,";
		}
		if($data['contain_flag_3'] == 'on'){
			$contain_flag .= "3,";
		}
		if($data['contain_flag_4'] == 'on'){
			$contain_flag .= "4,";
		}
		if($data['contain_flag_5'] == 'on'){
			$contain_flag .= "5,";
		}
		
		$exclude_flag = "";//排除小旗
		if($data['exclude_flag_1'] == 'on'){
			$exclude_flag .= "1,";
		}
		if($data['exclude_flag_2'] == 'on'){
			$exclude_flag .= "2,";
		}
		if($data['exclude_flag_3'] == 'on'){
			$exclude_flag .= "3,";
		}
		if($data['exclude_flag_4'] == 'on'){
			$exclude_flag .= "4,";
		}
		if($data['exclude_flag_5'] == 'on'){
			$exclude_flag .= "5,";
		}
		
		
		$sqlParam = array(
			array('name' => ':autoApproval', 'value' => $autoApproval, 'type' => PDO::PARAM_STR),
			array('name' => ':contain_prd', 'value' => $contain_prd, 'type' => PDO::PARAM_STR),
			array('name' => ':exclude_prd', 'value' => $exclude_prd, 'type' => PDO::PARAM_STR),
			array('name' => ':min_qty', 'value' => $min_qty, 'type' => PDO::PARAM_STR),
			array('name' => ':max_qty', 'value' => $max_qty, 'type' => PDO::PARAM_STR),
			array('name' => ':min_payment', 'value' => $min_payment, 'type' => PDO::PARAM_STR),
			array('name' => ':max_payment', 'value' => $max_payment, 'type' => PDO::PARAM_STR),
			array('name' => ':ignore_buyer_message', 'value' => $ignore_buyer_message, 'type' => PDO::PARAM_STR),
			array('name' => ':no_buyer_message', 'value' => $no_buyer_message, 'type' => PDO::PARAM_STR),
			array('name' => ':ignore_seller_memo', 'value' => $ignore_seller_memo, 'type' => PDO::PARAM_STR),
			array('name' => ':no_seller_memo', 'value' => $no_seller_memo, 'type' => PDO::PARAM_STR),
			array('name' => ':contain_flag', 'value' => $contain_flag, 'type' => PDO::PARAM_STR),
			array('name' => ':exclude_flag', 'value' => $exclude_flag, 'type' => PDO::PARAM_STR),
		);
		
		$sql = "select 1 from ".TABLE('base_config')." where type='approvalRule' and configKey='autoApproval' ";
		if($row = $model->query($sql,$sqlParam)->find()){
			$model->execute("update ".TABLE('base_config')." set configValue=:autoApproval where type='approvalRule' and configKey='autoApproval' ",$sqlParam);
		}else{
			$model->execute("insert into ".TABLE('base_config')."(configKey,configValue,type)values('autoApproval',:autoApproval,'approvalRule')  ",$sqlParam);
		}
		
		$sql = "select 1 from ".TABLE('base_config')." where type='approvalRule' and configKey='contain_prd' ";
		if($row = $model->query($sql,$sqlParam)->find()){
			$model->execute("update ".TABLE('base_config')." set configValue=:contain_prd where type='approvalRule' and configKey='contain_prd' ",$sqlParam);
		}else{
			$model->execute("insert into ".TABLE('base_config')."(configKey,configValue,type)values('contain_prd',:contain_prd,'approvalRule')  ",$sqlParam);
		}
		
		$sql = "select 1 from ".TABLE('base_config')." where type='approvalRule' and configKey='exclude_prd' ";
		if($row = $model->query($sql,$sqlParam)->find()){
			$model->execute("update ".TABLE('base_config')." set configValue=:exclude_prd where type='approvalRule' and configKey='exclude_prd' ",$sqlParam);
		}else{
			$model->execute("insert into ".TABLE('base_config')."(configKey,configValue,type)values('exclude_prd',:exclude_prd,'approvalRule')  ",$sqlParam);
		}
		
		$sql = "select 1 from ".TABLE('base_config')." where type='approvalRule' and configKey='min_qty' ";
		if($row = $model->query($sql,$sqlParam)->find()){
			$model->execute("update ".TABLE('base_config')." set configValue=:min_qty where type='approvalRule' and configKey='min_qty' ",$sqlParam);
		}else{
			$model->execute("insert into ".TABLE('base_config')."(configKey,configValue,type)values('min_qty',:min_qty,'approvalRule')  ",$sqlParam);
		}
		
		$sql = "select 1 from ".TABLE('base_config')." where type='approvalRule' and configKey='max_qty' ";
		if($row = $model->query($sql,$sqlParam)->find()){
			$model->execute("update ".TABLE('base_config')." set configValue=:max_qty where type='approvalRule' and configKey='max_qty' ",$sqlParam);
		}else{
			$model->execute("insert into ".TABLE('base_config')."(configKey,configValue,type)values('max_qty',:max_qty,'approvalRule')  ",$sqlParam);
		}
		
		$sql = "select 1 from ".TABLE('base_config')." where type='approvalRule' and configKey='min_payment' ";
		if($row = $model->query($sql,$sqlParam)->find()){
			$model->execute("update ".TABLE('base_config')." set configValue=:min_payment where type='approvalRule' and configKey='min_payment' ",$sqlParam);
		}else{
			$model->execute("insert into ".TABLE('base_config')."(configKey,configValue,type)values('min_payment',:min_payment,'approvalRule')  ",$sqlParam);
		}
		
		$sql = "select 1 from ".TABLE('base_config')." where type='approvalRule' and configKey='max_payment' ";
		if($row = $model->query($sql,$sqlParam)->find()){
			$model->execute("update ".TABLE('base_config')." set configValue=:max_payment where type='approvalRule' and configKey='max_payment' ",$sqlParam);
		}else{
			$model->execute("insert into ".TABLE('base_config')."(configKey,configValue,type)values('max_payment',:max_payment,'approvalRule')  ",$sqlParam);
		}
		
		$sql = "select 1 from ".TABLE('base_config')." where type='approvalRule' and configKey='ignore_buyer_message' ";
		if($row = $model->query($sql,$sqlParam)->find()){
			$model->execute("update ".TABLE('base_config')." set configValue=:ignore_buyer_message where type='approvalRule' and configKey='ignore_buyer_message' ",$sqlParam);
		}else{
			$model->execute("insert into ".TABLE('base_config')."(configKey,configValue,type)values('ignore_buyer_message',:ignore_buyer_message,'approvalRule')  ",$sqlParam);
		}
		
		$sql = "select 1 from ".TABLE('base_config')." where type='approvalRule' and configKey='no_buyer_message' ";
		if($row = $model->query($sql,$sqlParam)->find()){
			$model->execute("update ".TABLE('base_config')." set configValue=:no_buyer_message where type='approvalRule' and configKey='no_buyer_message' ",$sqlParam);
		}else{
			$model->execute("insert into ".TABLE('base_config')."(configKey,configValue,type)values('no_buyer_message',:no_buyer_message,'approvalRule')  ",$sqlParam);
		}
		
		$sql = "select 1 from ".TABLE('base_config')." where type='approvalRule' and configKey='ignore_seller_memo' ";
		if($row = $model->query($sql,$sqlParam)->find()){
			$model->execute("update ".TABLE('base_config')." set configValue=:ignore_seller_memo where type='approvalRule' and configKey='ignore_seller_memo' ",$sqlParam);
		}else{
			$model->execute("insert into ".TABLE('base_config')."(configKey,configValue,type)values('ignore_seller_memo',:ignore_seller_memo,'approvalRule')  ",$sqlParam);
		}
		
		$sql = "select 1 from ".TABLE('base_config')." where type='approvalRule' and configKey='no_seller_memo' ";
		if($row = $model->query($sql,$sqlParam)->find()){
			$model->execute("update ".TABLE('base_config')." set configValue=:no_seller_memo where type='approvalRule' and configKey='no_seller_memo' ",$sqlParam);
		}else{
			$model->execute("insert into ".TABLE('base_config')."(configKey,configValue,type)values('no_seller_memo',:no_seller_memo,'approvalRule')  ",$sqlParam);
		}
		
		$sql = "select 1 from ".TABLE('base_config')." where type='approvalRule' and configKey='contain_flag' ";
		if($row = $model->query($sql,$sqlParam)->find()){
			$model->execute("update ".TABLE('base_config')." set configValue=:contain_flag where type='approvalRule' and configKey='contain_flag' ",$sqlParam);
		}else{
			$model->execute("insert into ".TABLE('base_config')."(configKey,configValue,type)values('contain_flag',:contain_flag,'approvalRule')  ",$sqlParam);
		}
		
		$sql = "select 1 from ".TABLE('base_config')." where type='approvalRule' and configKey='exclude_flag' ";
		if($row = $model->query($sql,$sqlParam)->find()){
			$model->execute("update ".TABLE('base_config')." set configValue=:exclude_flag where type='approvalRule' and configKey='exclude_flag' ",$sqlParam);
		}else{
			$model->execute("insert into ".TABLE('base_config')."(configKey,configValue,type)values('exclude_flag',:exclude_flag,'approvalRule')  ",$sqlParam);
		}
		
		return array("code" => "ok");
	}
	
	public function saveBaseConfig($param){
		$modelSYS = D_SYS();
		$model = D();
		$system_id = $_SESSION['LOGIN_SYSTEM_ID'];
		$data = $param['data'];

		$autoMarketLabel = $data['autoMarketLabel'] ? $data['autoMarketLabel'] : 'off';////自动生成市场标签
		$useUniqueSync = $data['useUniqueSync'];////使用代拿标签功能
		$stockPrint = $data['stockPrint'];////库存订单打印标签
		$brushOrder = $data['brushOrder'];////刷单订单金额不清0
		$isexpress = $data['isexpress'];////直接打印快递单
		$sellerMemoAfter = $data['sellerMemoAfter'];//拆包退货回传卖家备注
		$sellerMemoSplit = $data['sellerMemoSplit'];//拆包发货回传卖家备注
		$exchangeSend = $data['exchangeSend'];//换货订单回传卖家备注
		$scanSendSingleCheck = $data['scanSendSingleCheck'];//扫描发货单品验货
		$unpackingRefund = $data['unpackingRefund'];//拆包退货仅自动带出退款商品
		$down_delay = $data['down_delay'];//抓单推迟时间
		$down_delay = $down_delay == "" ? 0 : $down_delay;
		$check_serial_no = $data['check_serial_no'];
		$check_regular_cust = $data['check_regular_cust'];
		$mogujie_warning = $data['mogujie_warning'];//蘑菇街大数据拦截
		$scanHaveCheck = $data['scanHaveCheck'];//到货点货扫描后自动挂单
		$waveAllPrint = $data['waveAllPrint'];//波次可直接打单发货
		$waveAllCreate = $data['waveAllCreate'];
		$autoItem = $data['autoItem'];//自动下载商品
		$uniqueNotTitle = $data['uniqueNotTitle'];//订单内所有商品编码都含有 时整单不生成标签
		$uniqueNotSkuName = $data['uniqueNotSkuName'];//订单内所有商品销售属性都含有 时整单不生成标签
		$uniqueNotTitleSingle = $data['uniqueNotTitleSingle'];//商品标题都含有 时单品不生成标签
		$setupPrint = $data['setupPrint'];//打印省内设置
		$autoDeListing = $data['autoDeListing'];//自动删除下架商品的绑定关系
		$sellerMemoAfterFlag = $data['sellerMemoAfterFlag'];//拆包退货回传旗帜
		$exchangeSendFlag = $data['exchangeSendFlag'];//换货补发回传旗帜
		$localPicPath = $data['localPicPath'];//抓单取本地图片
		$multiPackage = $data['multiPackage'];//多包裹子母件
		$autoSpotGoods = $data['autoSpotGoods'];//扫描发货单后自动挂单
		$splitMerge = $data['splitMerge'];//拆分单自动合并

		$sqlParam = array(
			array('name' => ':system_id', 'value' => $system_id, 'type' => PDO::PARAM_STR),
			array('name' => ':autoMarketLabel', 'value' => $autoMarketLabel, 'type' => PDO::PARAM_STR),
			array('name' => ':useUniqueSync', 'value' => $useUniqueSync, 'type' => PDO::PARAM_STR),
			array('name' => ':stockPrint', 'value' => $stockPrint, 'type' => PDO::PARAM_STR),
			array('name' => ':brushOrder', 'value' => $brushOrder, 'type' => PDO::PARAM_STR),
			array('name' => ':isexpress', 'value' => $isexpress, 'type' => PDO::PARAM_STR),
			array('name' => ':sellerMemoAfter', 'value' => $sellerMemoAfter, 'type' => PDO::PARAM_STR),
			array('name' => ':sellerMemoSplit', 'value' => $sellerMemoSplit, 'type' => PDO::PARAM_STR),
			array('name' => ':exchangeSend', 'value' => $exchangeSend, 'type' => PDO::PARAM_STR),
			array('name' => ':scanSendSingleCheck', 'value' => $scanSendSingleCheck, 'type' => PDO::PARAM_STR),
			array('name' => ':unpackingRefund', 'value' => $unpackingRefund, 'type' => PDO::PARAM_STR),
			array('name' => ':down_delay', 'value' => $down_delay, 'type' => PDO::PARAM_STR),
			array('name' => ':check_serial_no', 'value' => $check_serial_no, 'type' => PDO::PARAM_STR),
			array('name' => ':check_regular_cust', 'value' => $check_regular_cust, 'type' => PDO::PARAM_STR),
			array('name' => ':mogujie_warning', 'value' => $mogujie_warning, 'type' => PDO::PARAM_STR),
			array('name' => ':scanHaveCheck', 'value' => $scanHaveCheck, 'type' => PDO::PARAM_STR),
			array('name' => ':waveAllPrint', 'value' => $waveAllPrint, 'type' => PDO::PARAM_STR),
			array('name' => ':waveAllCreate', 'value' => $waveAllCreate, 'type' => PDO::PARAM_STR),
			array('name' => ':uniqueNotTitle', 'value' => $uniqueNotTitle, 'type' => PDO::PARAM_STR),
			array('name' => ':uniqueNotSkuName', 'value' => $uniqueNotSkuName, 'type' => PDO::PARAM_STR),
			array('name' => ':setupPrint', 'value' => $setupPrint, 'type' => PDO::PARAM_STR),
			array('name' => ':uniqueNotTitleSingle', 'value' => $uniqueNotTitleSingle, 'type' => PDO::PARAM_STR),
			array('name' => ':sellerMemoAfterFlag', 'value' => $sellerMemoAfterFlag, 'type' => PDO::PARAM_STR),
			array('name' => ':exchangeSendFlag', 'value' => $exchangeSendFlag, 'type' => PDO::PARAM_STR),
			array('name' => ':localPicPath', 'value' => $localPicPath, 'type' => PDO::PARAM_STR),
			array('name' => ':multiPackage', 'value' => $multiPackage, 'type' => PDO::PARAM_STR),
			array('name' => ':autoSpotGoods', 'value' => $autoSpotGoods, 'type' => PDO::PARAM_STR),
			array('name' => ':splitMerge', 'value' => $splitMerge, 'type' => PDO::PARAM_STR),
		);
		
		session_start();
		$_SESSION['autoMarketLabel'] = $autoMarketLabel;//自动生成市场标签
		$_SESSION['useUniqueSync'] = $useUniqueSync;//自动生成市场标签
		session_write_close();
		
		$sql = "select 1 from ".TABLE('base_config')." where type='baseConfig' and configKey='autoMarketLabel' ";
		if($row = $model->query($sql,$sqlParam)->find()){
			$model->execute("update ".TABLE('base_config')." set configValue=:autoMarketLabel where type='baseConfig' and configKey='autoMarketLabel' ",$sqlParam);
		}else{
			$model->execute("insert into ".TABLE('base_config')."(configKey,configValue,type)values('autoMarketLabel',:autoMarketLabel,'baseConfig')  ",$sqlParam);
		}
		
		$sql = "select 1 from ".TABLE('base_config')." where type='baseConfig' and configKey='useUniqueSync' ";
		if($row = $model->query($sql,$sqlParam)->find()){
			$model->execute("update ".TABLE('base_config')." set configValue=:useUniqueSync where type='baseConfig' and configKey='useUniqueSync' ",$sqlParam);
		}else{
			$model->execute("insert into ".TABLE('base_config')."(configKey,configValue,type)values('useUniqueSync',:useUniqueSync,'baseConfig')  ",$sqlParam);
		}
		
		$sql = "select 1 from ".TABLE('base_config')." where type='baseConfig' and configKey='stockPrint' ";
		if($row = $model->query($sql,$sqlParam)->find()){
			$model->execute("update ".TABLE('base_config')." set configValue=:stockPrint where type='baseConfig' and configKey='stockPrint' ",$sqlParam);
		}else{
			$model->execute("insert into ".TABLE('base_config')."(configKey,configValue,type)values('stockPrint',:stockPrint,'baseConfig')  ",$sqlParam);
		}
		
		$sql = "select 1 from ".TABLE('base_config')." where type='baseConfig' and configKey='brushOrder' ";
		if($row = $model->query($sql,$sqlParam)->find()){
			$model->execute("update ".TABLE('base_config')." set configValue=:brushOrder where type='baseConfig' and configKey='brushOrder' ",$sqlParam);
		}else{
			$model->execute("insert into ".TABLE('base_config')."(configKey,configValue,type)values('brushOrder',:brushOrder,'baseConfig')  ",$sqlParam);
		}
		
		$sql = "select 1 from ".TABLE('base_config')." where type='baseConfig' and configKey='isexpress' ";
		if($row = $model->query($sql,$sqlParam)->find()){
			$model->execute("update ".TABLE('base_config')." set configValue=:isexpress where type='baseConfig' and configKey='isexpress' ",$sqlParam);
		}else{
			$model->execute("insert into ".TABLE('base_config')."(configKey,configValue,type)values('isexpress',:isexpress,'baseConfig')  ",$sqlParam);
		}
		
		$sql = "select 1 from ".TABLE('base_config')." where type='baseConfig' and configKey='sellerMemoAfter' ";
		if($row = $model->query($sql,$sqlParam)->find()){
			$model->execute("update ".TABLE('base_config')." set configValue=:sellerMemoAfter where type='baseConfig' and configKey='sellerMemoAfter' ",$sqlParam);
		}else{
			$model->execute("insert into ".TABLE('base_config')."(configKey,configValue,type)values('sellerMemoAfter',:sellerMemoAfter,'baseConfig')  ",$sqlParam);
		}
		
		$sql = "select 1 from ".TABLE('base_config')." where type='baseConfig' and configKey='sellerMemoSplit' ";
		if($row = $model->query($sql,$sqlParam)->find()){
			$model->execute("update ".TABLE('base_config')." set configValue=:sellerMemoSplit where type='baseConfig' and configKey='sellerMemoSplit' ",$sqlParam);
		}else{
			$model->execute("insert into ".TABLE('base_config')."(configKey,configValue,type)values('sellerMemoSplit',:sellerMemoSplit,'baseConfig')  ",$sqlParam);
		}
		
		$sql = "select 1 from ".TABLE('base_config')." where type='baseConfig' and configKey='exchangeSend' ";
		if($row = $model->query($sql,$sqlParam)->find()){
			$model->execute("update ".TABLE('base_config')." set configValue=:exchangeSend where type='baseConfig' and configKey='exchangeSend' ",$sqlParam);
		}else{
			$model->execute("insert into ".TABLE('base_config')."(configKey,configValue,type)values('exchangeSend',:exchangeSend,'baseConfig')  ",$sqlParam);
		}
		
		$sql = "select 1 from ".TABLE('base_config')." where type='baseConfig' and configKey='scanSendSingleCheck' ";
		if($row = $model->query($sql,$sqlParam)->find()){
			$model->execute("update ".TABLE('base_config')." set configValue=:scanSendSingleCheck where type='baseConfig' and configKey='scanSendSingleCheck' ",$sqlParam);
		}else{
			$model->execute("insert into ".TABLE('base_config')."(configKey,configValue,type)values('scanSendSingleCheck',:scanSendSingleCheck,'baseConfig')  ",$sqlParam);
		}
		
		$sql = "select 1 from ".TABLE('base_config')." where type='baseConfig' and configKey='unpackingRefund' ";
		if($row = $model->query($sql,$sqlParam)->find()){
			$model->execute("update ".TABLE('base_config')." set configValue=:unpackingRefund where type='baseConfig' and configKey='unpackingRefund' ",$sqlParam);
		}else{
			$model->execute("insert into ".TABLE('base_config')."(configKey,configValue,type)values('unpackingRefund',:unpackingRefund,'baseConfig')  ",$sqlParam);
		}
		
		$sql = "select 1 from ".TABLE('base_config')." where type='baseConfig' and configKey='down_delay' ";
		if($row = $model->query($sql,$sqlParam)->find()){
			$model->execute("update ".TABLE('base_config')." set configValue=:down_delay where type='baseConfig' and configKey='down_delay' ",$sqlParam);
		}else{
			$model->execute("insert into ".TABLE('base_config')."(configKey,configValue,type)values('down_delay',:down_delay,'baseConfig')  ",$sqlParam);
		}

		$sql = "select 1 from ".TABLE('base_config')." where type='baseConfig' and configKey='check_serial_no' ";
		if($row = $model->query($sql,$sqlParam)->find()){
			$model->execute("update ".TABLE('base_config')." set configValue=:check_serial_no where type='baseConfig' and configKey='check_serial_no' ",$sqlParam);
		}else{
			$model->execute("insert into ".TABLE('base_config')."(configKey,configValue,type)values('check_serial_no',:check_serial_no,'baseConfig')  ",$sqlParam);
		}
		$sql = "select 1 from ".TABLE('base_config')." where type='baseConfig' and configKey='check_regular_cust' ";
		if($row = $model->query($sql,$sqlParam)->find()){
			$model->execute("update ".TABLE('base_config')." set configValue=:check_regular_cust where type='baseConfig' and configKey='check_regular_cust' ",$sqlParam);
		}else{
			$model->execute("insert into ".TABLE('base_config')."(configKey,configValue,type)values('check_regular_cust',:check_regular_cust,'baseConfig')  ",$sqlParam);
		}
		$sql = "select 1 from ".TABLE('base_config')." where type='baseConfig' and configKey='mogujie_warning' ";
		if($row = $model->query($sql,$sqlParam)->find()){
			$model->execute("update ".TABLE('base_config')." set configValue=:mogujie_warning where type='baseConfig' and configKey='mogujie_warning' ",$sqlParam);
		}else{
			$model->execute("insert into ".TABLE('base_config')."(configKey,configValue,type)values('mogujie_warning',:mogujie_warning,'baseConfig')  ",$sqlParam);
		}
        $sql = "select 1 from ".TABLE('base_config')." where type='baseConfig' and configKey='scanHaveCheck' ";
		if($row = $model->query($sql,$sqlParam)->find()){
			$model->execute("update ".TABLE('base_config')." set configValue=:scanHaveCheck where type='baseConfig' and configKey='scanHaveCheck' ",$sqlParam);
		}else{
			$model->execute("insert into ".TABLE('base_config')."(configKey,configValue,type)values('scanHaveCheck',:scanHaveCheck,'baseConfig')  ",$sqlParam);
		}
		$sql = "select 1 from ".TABLE('base_config')." where type='baseConfig' and configKey='waveAllPrint' ";
		if($row = $model->query($sql,$sqlParam)->find()){
			$model->execute("update ".TABLE('base_config')." set configValue=:waveAllPrint where type='baseConfig' and configKey='waveAllPrint' ",$sqlParam);
		}else{
			$model->execute("insert into ".TABLE('base_config')."(configKey,configValue,type)values('waveAllPrint',:waveAllPrint,'baseConfig')  ",$sqlParam);
		}
		$sql = "select 1 from ".TABLE('base_config')." where type='baseConfig' and configKey='waveAllCreate' ";
		if($row = $model->query($sql,$sqlParam)->find()){
			$model->execute("update ".TABLE('base_config')." set configValue=:waveAllCreate where type='baseConfig' and configKey='waveAllCreate' ",$sqlParam);
		}else{
			$model->execute("insert into ".TABLE('base_config')."(configKey,configValue,type)values('waveAllCreate',:waveAllCreate,'baseConfig')  ",$sqlParam);
		}
		$sql = "select 1 from ".TABLE('base_config')." where type='baseConfig' and configKey='uniqueNotTitle' ";
		if($row = $model->query($sql,$sqlParam)->find()){
			$model->execute("update ".TABLE('base_config')." set configValue=:uniqueNotTitle where type='baseConfig' and configKey='uniqueNotTitle' ",$sqlParam);
		}else{
			$model->execute("insert into ".TABLE('base_config')."(configKey,configValue,type)values('uniqueNotTitle',:uniqueNotTitle,'baseConfig')  ",$sqlParam);
		}
		$sql = "select 1 from ".TABLE('base_config')." where type='baseConfig' and configKey='uniqueNotSkuName' ";
		if($row = $model->query($sql,$sqlParam)->find()){
			$model->execute("update ".TABLE('base_config')." set configValue=:uniqueNotSkuName where type='baseConfig' and configKey='uniqueNotSkuName' ",$sqlParam);
		}else{
			$model->execute("insert into ".TABLE('base_config')."(configKey,configValue,type)values('uniqueNotSkuName',:uniqueNotSkuName,'baseConfig')  ",$sqlParam);
		}
		$sql = "select 1 from ".TABLE('base_config')." where type='baseConfig' and configKey='uniqueNotTitleSingle' ";
		if($row = $model->query($sql,$sqlParam)->find()){
			$model->execute("update ".TABLE('base_config')." set configValue=:uniqueNotTitleSingle where type='baseConfig' and configKey='uniqueNotTitleSingle' ",$sqlParam);
		}else{
			$model->execute("insert into ".TABLE('base_config')."(configKey,configValue,type)values('uniqueNotTitleSingle',:uniqueNotTitleSingle,'baseConfig')  ",$sqlParam);
		}
		
		$sql = "select 1 from ".TABLE('base_config')." where type='baseConfig' and configKey='localPicPath' ";
		if($row = $model->query($sql,$sqlParam)->find()){
			$model->execute("update ".TABLE('base_config')." set configValue=:localPicPath where type='baseConfig' and configKey='localPicPath' ",$sqlParam);
		}else{
			$model->execute("insert into ".TABLE('base_config')."(configKey,configValue,type)values('localPicPath',:localPicPath,'baseConfig')  ",$sqlParam);
		}
		
		$sql = "select 1 from ".TABLE('base_config')." where type='baseConfig' and configKey='multiPackage' ";
		if($row = $model->query($sql,$sqlParam)->find()){
			$model->execute("update ".TABLE('base_config')." set configValue=:multiPackage where type='baseConfig' and configKey='multiPackage' ",$sqlParam);
		}else{
			$model->execute("insert into ".TABLE('base_config')."(configKey,configValue,type)values('multiPackage',:multiPackage,'baseConfig')  ",$sqlParam);
		}
		$sql = "select 1 from ".TABLE('base_config')." where type='baseConfig' and configKey='autoSpotGoods' ";
		if($row = $model->query($sql,$sqlParam)->find()){
			$model->execute("update ".TABLE('base_config')." set configValue=:autoSpotGoods where type='baseConfig' and configKey='autoSpotGoods' ",$sqlParam);
		}else{
			$model->execute("insert into ".TABLE('base_config')."(configKey,configValue,type)values('autoSpotGoods',:autoSpotGoods,'baseConfig')  ",$sqlParam);
		}
		$sql = "select 1 from ".TABLE('base_config')." where type='baseConfig' and configKey='splitMerge' ";
		if($row = $model->query($sql,$sqlParam)->find()){
			$model->execute("update ".TABLE('base_config')." set configValue=:splitMerge where type='baseConfig' and configKey='splitMerge' ",$sqlParam);
		}else{
			$model->execute("insert into ".TABLE('base_config')."(configKey,configValue,type)values('splitMerge',:splitMerge,'baseConfig')  ",$sqlParam);
		}
		
		if($autoItem == '0'){
			$sql = "select 1 from ".TABLE('base_config')." where type='baseConfig' and configKey='autoItem' ";
			if($row = $model->query($sql,$sqlParam)->find()){
				$model->execute("update ".TABLE('base_config')." set configValue='0' where type='baseConfig' and configKey='autoItem' ");
			}else{
				$model->execute("insert into ".TABLE('base_config')."(configKey,configValue,type)values('autoItem','0','baseConfig')  ");
			}
			
			$modelSYS->execute("update userlist set isAutoItem='0' where system_id=:system_id", $sqlParam);
		}else if($autoItem == '1'){
			$sql = "select 1 from ".TABLE('base_config')." where type='baseConfig' and configKey='autoItem' ";
			if($row = $model->query($sql,$sqlParam)->find()){
				$model->execute("update ".TABLE('base_config')." set configValue='1' where type='baseConfig' and configKey='autoItem' ");
			}else{
				$model->execute("insert into ".TABLE('base_config')."(configKey,configValue,type)values('autoItem','1','baseConfig')  ");
			}
			
			$modelSYS->execute("update userlist set isAutoItem='1' where system_id=:system_id", $sqlParam);
		}else if($autoItem == '2'){
			$sql = "select 1 from ".TABLE('base_config')." where type='baseConfig' and configKey='autoItem' ";
			if($row = $model->query($sql,$sqlParam)->find()){
				$model->execute("update ".TABLE('base_config')." set configValue='2' where type='baseConfig' and configKey='autoItem' ");
			}else{
				$model->execute("insert into ".TABLE('base_config')."(configKey,configValue,type)values('autoItem','2','baseConfig')  ");
			}
			
			$modelSYS->execute("update userlist set isAutoItem='2' where system_id=:system_id", $sqlParam);
		}
		
		if($autoDeListing == '0'){
			$sql = "select 1 from ".TABLE('base_config')." where type='baseConfig' and configKey='autoDeListing' ";
			if($row = $model->query($sql,$sqlParam)->find()){
				$model->execute("update ".TABLE('base_config')." set configValue='0' where type='baseConfig' and configKey='autoDeListing' ");
			}else{
				$model->execute("insert into ".TABLE('base_config')."(configKey,configValue,type)values('autoDeListing','0','baseConfig')  ");
			}
			
			$modelSYS->execute("update userlist set isAutoDeListing='0' where system_id=:system_id", $sqlParam);
		}else if($autoDeListing == '1'){
			$sql = "select 1 from ".TABLE('base_config')." where type='baseConfig' and configKey='autoDeListing' ";
			if($row = $model->query($sql,$sqlParam)->find()){
				$model->execute("update ".TABLE('base_config')." set configValue='1' where type='baseConfig' and configKey='autoDeListing' ");
			}else{
				$model->execute("insert into ".TABLE('base_config')."(configKey,configValue,type)values('autoDeListing','1','baseConfig')  ");
			}
			
			$modelSYS->execute("update userlist set isAutoDeListing='1' where system_id=:system_id", $sqlParam);
		}
		
		//打印省内设置
		$sql = "SELECT 1 FROM ".TABLE('base_config')." WHERE `type`='PrintAttr' AND configKey='PrintAttr'";
		if($row = $model->query($sql,$sqlParam)->find()){
			$model->execute("update ".TABLE('base_config')." set configValue=:setupPrint where type='PrintAttr' and configKey='PrintAttr' ",$sqlParam);
		}else{
			$model->execute("insert into ".TABLE('base_config')."(configKey,configValue,type)values('PrintAttr',:setupPrint,'PrintAttr')  ",$sqlParam);
		}
		
		$sql = "select 1 from ".TABLE('base_config')." where type='baseConfig' and configKey='sellerMemoAfterFlag' ";
		if($row = $model->query($sql,$sqlParam)->find()){
			$model->execute("update ".TABLE('base_config')." set configValue=:sellerMemoAfterFlag where type='baseConfig' and configKey='sellerMemoAfterFlag' ",$sqlParam);
		}else{
			$model->execute("insert into ".TABLE('base_config')."(configKey,configValue,type)values('sellerMemoAfterFlag',:sellerMemoAfterFlag,'baseConfig')  ",$sqlParam);
		}
		
		$sql = "select 1 from ".TABLE('base_config')." where type='baseConfig' and configKey='exchangeSendFlag' ";
		if($row = $model->query($sql,$sqlParam)->find()){
			$model->execute("update ".TABLE('base_config')." set configValue=:exchangeSendFlag where type='baseConfig' and configKey='exchangeSendFlag' ",$sqlParam);
		}else{
			$model->execute("insert into ".TABLE('base_config')."(configKey,configValue,type)values('exchangeSendFlag',:exchangeSendFlag,'baseConfig')  ",$sqlParam);
		}
		
		return array("code" => "ok");
	}
	
	public function getApproval(){
		$model = D();
		
		$result = array();
		
		$sql = "select configKey,configValue from ".TABLE('base_config')." where type='approvalRule' ";
		if($rows = $model->query($sql)->select()){
			foreach($rows as $list){
				if($list['configKey'] == "autoApproval"){
					$result['autoApproval'] = $list['configValue'];
				}else if($list['configKey'] == "contain_prd"){
					$result['contain_prd'] = $list['configValue'];
				}else if($list['configKey'] == "exclude_prd"){
					$result['exclude_prd'] = $list['configValue'];
				}else if($list['configKey'] == "max_qty"){
					$result['max_qty'] = $list['configValue'];
				}else if($list['configKey'] == "min_qty"){
					$result['min_qty'] = $list['configValue'];
				}else if($list['configKey'] == "max_payment"){
					$result['max_payment'] = $list['configValue'];
				}else if($list['configKey'] == "min_payment"){
					$result['min_payment'] = $list['configValue'];
				}else if($list['configKey'] == "ignore_buyer_message"){
					$result['ignore_buyer_message'] = $list['configValue'];
				}else if($list['configKey'] == "no_buyer_message"){
					$result['no_buyer_message'] = $list['configValue'];
				}else if($list['configKey'] == "ignore_seller_memo"){
					$result['ignore_seller_memo'] = $list['configValue'];
				}else if($list['configKey'] == "no_seller_memo"){
					$result['no_seller_memo'] = $list['configValue'];
				}else if($list['configKey'] == "contain_flag"){
					if(stristr($list['configValue'],"1,")){
						$result['contain_flag_1'] = "on";
					}
					if(stristr($list['configValue'],"2,")){
						$result['contain_flag_2'] = "on";
					}
					if(stristr($list['configValue'],"3,")){
						$result['contain_flag_3'] = "on";
					}
					if(stristr($list['configValue'],"4,")){
						$result['contain_flag_4'] = "on";
					}
					if(stristr($list['configValue'],"5,")){
						$result['contain_flag_5'] = "on";
					}
				}else if($list['configKey'] == "exclude_flag"){
					if(stristr($list['configValue'],"1,")){
						$result['exclude_flag_1'] = "on";
					}
					if(stristr($list['configValue'],"2,")){
						$result['exclude_flag_2'] = "on";
					}
					if(stristr($list['configValue'],"3,")){
						$result['exclude_flag_3'] = "on";
					}
					if(stristr($list['configValue'],"4,")){
						$result['exclude_flag_4'] = "on";
					}
					if(stristr($list['configValue'],"5,")){
						$result['exclude_flag_5'] = "on";
					}
				}
			}
		}
		
		
		return $result;
	}
	
	public function getBaseConfig(){
		$model = D();
		
		$result = array();
		$result['autoItem'] = 0;
		$result['autoDeListing'] = 0;
		
		$sql = "select configKey,configValue from ".TABLE('base_config')." where type='baseConfig'";
		if($rows = $model->query($sql)->select()){
			foreach($rows as $list){
				if($list['configKey'] == "sellerMemoAfter"){
					$result['sellerMemoAfter'] = $list['configValue'];
				}else if($list['configKey'] == "sellerMemoSplit"){
					$result['sellerMemoSplit'] = $list['configValue'];
				}else if($list['configKey'] == "exchangeSend"){
					$result['exchangeSend'] = $list['configValue'];
				}else if($list['configKey'] == "scanSendSingleCheck"){
					$result['scanSendSingleCheck'] = $list['configValue'];
				}else if($list['configKey'] == "unpackingRefund"){
					$result['unpackingRefund'] = $list['configValue'];
				}else if($list['configKey'] == "down_delay"){
					$result['down_delay'] = $list['configValue'];
				}else if($list['configKey'] == "check_serial_no"){
					$result['check_serial_no'] = $list['configValue'];
				}else if($list['configKey'] == "localPicPath"){
					$result['localPicPath'] = $list['configValue'];
				}else if($list['configKey'] == "check_regular_cust"){
					$result['check_regular_cust'] = $list['configValue'];
				}else if($list['configKey'] == "mogujie_warning"){
					$result['mogujie_warning'] = $list['configValue'];
				}else if($list['configKey'] == "autoMarketLabel"){
					$result['autoMarketLabel'] = $list['configValue'] ? $list['configValue'] : (($_SESSION['WMS_MODEL'] != 'PT' && $_SESSION['WMS_MODEL'] != 'WMS') ? 'on' : 'off');
				}else if($list['configKey'] == "useUniqueSync"){
					$result['useUniqueSync'] = $list['configValue'];
				}else if($list['configKey'] == "stockPrint"){
					$result['stockPrint'] = $list['configValue'];
				}else if($list['configKey'] == "brushOrder"){
					$result['brushOrder'] = $list['configValue'];
				}else if($list['configKey'] == "isexpress"){
					$result['isexpress'] = $list['configValue'];
				}else if($list['configKey'] == "scanHaveCheck"){
					$result['scanHaveCheck'] = $list['configValue'];
				}else if($list['configKey'] == "waveAllPrint"){
					$result['waveAllPrint'] = $list['configValue'];
				}else if($list['configKey'] == "waveAllCreate"){
					$result['waveAllCreate'] = $list['configValue'];
				}else if($list['configKey'] == "autoItem"){
					$result['autoItem'] = $list['configValue'];
				}else if($list['configKey'] == "autoDeListing"){
					$result['autoDeListing'] = $list['configValue'];
				}else if($list['configKey'] == "uniqueNotTitle"){
					$result['uniqueNotTitle'] = $list['configValue'];
				}else if($list['configKey'] == "uniqueNotSkuName"){
					$result['uniqueNotSkuName'] = $list['configValue'];
				}else if($list['configKey'] == "uniqueNotTitleSingle"){
					$result['uniqueNotTitleSingle'] = $list['configValue'];
				}else if($list['configKey'] == "sellerMemoAfterFlag"){
					$result['sellerMemoAfterFlag'] = $list['configValue'] ? $list['configValue'] : '0';
				}else if($list['configKey'] == "exchangeSendFlag"){
					$result['exchangeSendFlag'] = $list['configValue'] ? $list['configValue'] : '0';
				}else if($list['configKey'] == "multiPackage"){
					$result['multiPackage'] = $list['configValue'];
				}else if($list['configKey'] == "autoSpotGoods"){
					$result['autoSpotGoods'] = $list['configValue'];
				}else if($list['configKey'] == "splitMerge"){
					$result['splitMerge'] = $list['configValue'];
				}
			}
		}
		$result['autoMarketLabel'] = $result['autoMarketLabel'] == 'off' ? '' : 'on';
		$sql = "SELECT configKey,configValue,`type` FROM ".TABLE('base_config')." WHERE `type`='PrintAttr' AND configKey='PrintAttr'";
		$Items = $model->query($sql)->find();
		if($Items){
			$result['PrintAttr'] = $Items['configValue'];
		}else{
			$result['PrintAttr'] = array();
		}
		
		return $result;
	}
	
	public function getShopAllList(){
		$model = D();
		
		$shopList = array();
		$sql = "select shoptype,shopid,shopname from ".TABLE('shop_config');
		if($result = $model->query($sql)->select()){
			foreach($result as $list){
				$shopList[] = array('shoptypename' => parent::getPlatName($list['shoptype']), 'shopid' => $list['shopid'], 'shopname' => $list['shopname']);
			}
		}
		
		return $shopList;
	}
	
	//店铺权限列表
	public function getShopPremList($param){
		$id = $param['id'];
		$sqlParam = array(
			array('name' => ':id', 'value' => $id, 'type' => PDO::PARAM_STR),
		);
		$model = D();
		$rows = array();
		$row = array();
		for($i=0;$i<9;$i++){
			$rows[] = $row;
		}
		$sql = "select shop_permission from ".TABLE('usertable')." where id=:id";
		$result = $model->query($sql,$sqlParam)->find();
		if($result){
			$lines = explode(",",$result['shop_permission']);
		}else{
			$lines = array();
		}
		return $lines;
	}
	
	//功能权限列表
	public function getFuncAllList(){
		$lines = array();
		$lines[] = array('id'=>'printShip','page'=>'标签扫描发货','text'=>'强制补打快递单');
		$lines[] = array('id'=>'addOrders','page'=>'打单发货（待发货）','text'=>'新增手工订单');
		$lines[] = array('id'=>'copyOrders','page'=>'打单发货（待发货）','text'=>'复制订单');
		$lines[] = array('id'=>'warehouse','page'=>'分销客户管理只显示充值','text'=>'分销客户管理只显示充值');
		$lines[] = array('id'=>'shipment','page'=>'打单发货','text'=>'发货');
		return $lines;
	}
	
	public function saveShopPremList($param){
		$id = $param['id'];
		$datas = $param['data'];
		
		$permission = $datas;
		$sqlParam = array(
			array('name' => ':id', 'value' => $id, 'type' => PDO::PARAM_STR),
			array('name' => ':shop_permission', 'value' => $permission, 'type' => PDO::PARAM_STR),
		);
		$model = D();
		$sql = "update ".TABLE('usertable')." set shop_permission=:shop_permission where id=:id";
		$result = $model->execute($sql,$sqlParam);
		if($result === false){
			return array('code'=>'error','msg'=>'修改失败');
		}
		return array('code'=>'ok','msg'=>'修改成功');
	}
	
	//权限列表
	public function getPremList($param){
		$id = $param['id'];
		$sqlParam = array(
			array('name' => ':id', 'value' => $id, 'type' => PDO::PARAM_STR),
		);
		$model = D();
		$rows = array();
		$row = array();
		for($i=0;$i<9;$i++){
			$rows[] = $row;
		}
		$sql = "select permission from ".TABLE('usertable')." where id=:id";
		$result = $model->query($sql,$sqlParam)->find();
		if($result){
			$lines = explode(",",$result['permission']);
		}else{
			$lines = array();
		}
		return $lines;
	}
	
	public function savePremList($param){
		$id = $param['id'];
		$datas = $param['data'];
		
		$permission = $datas;
		$sqlParam = array(
			array('name' => ':id', 'value' => $id, 'type' => PDO::PARAM_STR),
			array('name' => ':permission', 'value' => $permission, 'type' => PDO::PARAM_STR),
		);
		$model = D();
		$sql = "update ".TABLE('usertable')." set permission=:permission where id=:id";
		$result = $model->execute($sql,$sqlParam);
		if($result === false){
			return array('code'=>'error','msg'=>'修改失败');
		}
		return array('code'=>'ok','msg'=>'修改成功');
	}
	
	public function saveWuliuRemark($param){
		$model = D();
		
		$submitData = $param['submitData'];		
		$submitData = json_decode($submitData,true);

		foreach($submitData as $dataValue){
			$_state = $dataValue['_state'];
			$id = $dataValue['id'];
			$type = $dataValue['type'];
			$keyword = $dataValue['keyword'];
			$express_type = $dataValue['express_type'];
			
			$sqlParam = array(
				array('name' => ':id', 'value' => $id, 'type' => PDO::PARAM_STR),
				array('name' => ':type', 'value' => $type, 'type' => PDO::PARAM_STR),
				array('name' => ':keyword', 'value' => $keyword, 'type' => PDO::PARAM_STR),
				array('name' => ':express_type', 'value' => $express_type, 'type' => PDO::PARAM_STR),
			);
			
			if($_state == "added"){
				$model->execute("insert into ".TABLE('express_memo')."(`type`,`keyword`,`express_type`)values(:type,:keyword,:express_type)",$sqlParam);
			}else if($_state == "modified"){
				$model->execute("update ".TABLE('express_memo')." set `type`=:type,`keyword`=:keyword,`express_type`=:express_type  where id=:id ",$sqlParam);
			}else if($_state == "removed"){
				$model->execute("delete from ".TABLE('express_memo')." where id=:id ",$sqlParam);
			}
		}

		return array('code' => 'ok');
	}
	
	public function saveUserOnline($param){
		$model = D();
		
		$submitData = $param['submitData'];		
		$submitData = json_decode($submitData,true);

		foreach($submitData as $dataValue){
			$_state = $dataValue['_state'];
			$id = $dataValue['id'];
			$userid = $dataValue['userid'];
			
			$sqlParam = array(
				array('name' => ':id', 'value' => $id, 'type' => PDO::PARAM_STR),
				array('name' => ':userid', 'value' => $userid, 'type' => PDO::PARAM_STR),
			);
			
			if($_state == "modified"){
				$model->execute("update ".TABLE('user_online')." set `userid`=:userid  where id=:id ",$sqlParam);
			}else if($_state == "removed"){
				$model->execute("delete from ".TABLE('user_online')." where id=:id ",$sqlParam);
			}
		}

		return array('code' => 'ok');
	}
	
	public function getUser(){
		$model = D();
		
		$result = array();
		$sql = "select userid,username from ".TABLE('usertable')." where `status` = '0' and type = 'sub' ";
		if($results = $model->query($sql)->select()){
			foreach($results as $list){
				$result[] = array('no' => $list['userid'], 'name' => $list['username']);
			}
		}
		
		return $result;
	}
	
	public function downloadUserOnline(){
		$model = D();
		
		include_once("MYAPP/system/model/ApiOptionDB.php");
		$apiOptionDB = new ApiOptionDB();
		
		$userdata = array();
		$sql = "select tb_userid,shoptype,shopid,appkey,secretkey,sessionkey from ".TABLE('shop_config')." where shoptype in ('TB') and status = '1' ";
		if($results = $model->query($sql,$sqlParam)->select()){
			foreach($results as $list){
				$userdata[] = array('shopid' => $list['shopid'], 'user_id' => $list['tb_userid'], 'user_name' => $list['shopid']);	
				$param = array('shopid' => $list['shopid'], 'appkey' => $list['appkey'], 'secretkey' => $list['secretkey'], 'sessionkey' => $list['sessionkey']);
				if($list['shoptype'] == "TB"){
					$sub_user_info = $apiOptionDB->taobaoSubUserInfo($param);
					if(is_array($sub_user_info)){
						foreach($sub_user_info as $sub_user_item){
							$userdata[] = array('shopid' => $list['shopid'], 'user_id' => $sub_user_item['sub_id'], 'user_name' => $sub_user_item['nick']);	
						}
					}
				}
			}
		}
		
		foreach($userdata as $useritem){
			$sqlParam = array(
				array('name' => ':shopid', 'value' => $useritem['shopid'], 'type' => PDO::PARAM_STR),
				array('name' => ':user_id', 'value' => $useritem['user_id'], 'type' => PDO::PARAM_STR),
				array('name' => ':user_name', 'value' => $useritem['user_name'], 'type' => PDO::PARAM_STR),
			);
			
			$sql = "select 1 from ".TABLE('user_online')." where user_id=:user_id";
			if($result = $model->query($sql,$sqlParam)->find()){
				
			}else{
				$model->execute("insert into ".TABLE('user_online')."(shopid, user_id, user_name)values(:shopid, :user_id, :user_name) ",$sqlParam);
			}
		}
		
		return array('code' => 'ok');
	}
	
	public function expressMemoMain(){
		$model = D();
		
		$result_data = array();
		$sql = "select id,`type`,`keyword`,`express_type` from ".TABLE('express_memo');
		if($results = $model->query($sql,$sqlParam)->select()){
			$result_data = $results;
		}
		
		return $result_data;
	}
	
	public function userOnlineMain(){
		$model = D();
		
		$result_data = array();
		$sql = "SELECT a.id,a.user_name,a.userid,b.shoptype,b.shopname,c.username FROM ".TABLE('user_online')." a
				LEFT JOIN (SELECT shopid,shoptype,shopname FROM ".TABLE('shop_config')." ) b ON a.shopid = b.shopid
				LEFT JOIN (SELECT userid,username FROM ".TABLE('usertable').") c ON a.userid = c.userid";
		if($results = $model->query($sql,$sqlParam)->select()){
			foreach($results as $key => $item){
				$results[$key]['shopname'] = parent::getPlatName($item['shoptype'])."-".$item['shopname'];
			}
			$result_data = $results;
		}
		
		return $result_data;
	}
	
	public function getLoadTable($param){
		$model = D();
		$result = array();
		$sql = "SELECT a.id,a.province,a.express_type,a.among,b.shopname FROM ".TABLE('express_among')." AS a
			LEFT JOIN ".TABLE('shop_config')." b ON a.shopid = b.shopid where a.among <> 0 order by a.province,a.among";
		$rows = $model->query($sql)->select();
		$express = parent::getExpressName();
		if($rows){
			foreach($rows as $row){
				$exps = "";
				foreach($express as $exp){
					if($exp['type'] == $row['express_type']){
						$exps = $exp['name'];
					}
				}
				$result[] = array(
					'id' => $row['id'],
					'province' => $row['province'],
					'express_type' => $exps,
					'among' => $row['among'],
					'shopname' => $row['shopname'],
				);
			}
		}
		return $result;
	}
	
	//获取基本信息（店铺、快递、省份）
	public function getBasicSetUp($param){
		$express = parent::getExpressConfig();
		$expressList = array();
		foreach($express as $exp){
			$expressList[] = array(
				'name' => $exp['name'],
				'type' => $exp['type'],
			);
		}
		$model = D();
		$sql = "SELECT shopid,shopname FROM ".TABLE('shop_config')."";
		$shopList = $model->query($sql)->select();
		$model = D_SYS();
		$sql = "SELECT province_code,province_name FROM data_province order by id";
		$provinceList = $model->query($sql)->select();
		return array('expressList' =>$expressList,'shopList' =>$shopList,'provinceList' =>$provinceList);
	}
	
	//增加修改快递比例
	public function addAmongList($param){
		$data = $param['data'];
		if(count($data) == 0){
			return array('code'=>'error','msg'=>'请选择一个或多个地址');
		}
		$amongTop = $param['amongTop'];
		if((int)$amongTop>100){
			return array( 'code' => 'error', 'msg' => '同一地区下所有快递比例和不能超过100');
		}
		$express = $param['express'];
		$shop = $param['shop'];
		
		$model = D();
		$model->startTrans();
		$index = 0;
		foreach($data as $province => $tag){
			$index++;
			$total = 0;
			$sqlParam = array(
				array('name' => ':express', 'value' => $express, 'type' => PDO::PARAM_STR),
				array('name' => ':province', 'value' => $province, 'type' => PDO::PARAM_STR),
				array('name' => ':amongTop', 'value' => $amongTop, 'type' => PDO::PARAM_STR),
				array('name' => ':shop', 'value' => $shop, 'type' => PDO::PARAM_STR),
			);
			//有当前地址 的当前店铺  当前物流 修改
			$sql = "select among from ".TABLE('express_among')." where 
			express_type=:express and province=:province and shopid=:shop";
			$state = $model->query($sql,$sqlParam)->find();
			if($state){   //修改
				if($state['among'] != $amongTop){
					$sql = "select among from ".TABLE('express_among')." where express_type<>:express and province=:province and shopid=:shop";
					$amongs = $model->query($sql,$sqlParam)->select();
					if($amongs){
						foreach($amongs as $among){
							$total = $total + $among['among'];
						}
					}
					$total = $total + $amongTop;
					if($total > '100'){
						$model->rollback();
						return array( 'code' => 'error', 'msg' => $total);
					}
					$sql = "select id,shopid from ".TABLE('express_among')." where 
					province=:province and express_type=:express and among<>0";
					$amongs = $model->query($sql,$sqlParam)->find();
					if($amongs['id'] && $amongs['shopid'] == "" && $shop != ""){
						$model->rollback();
						return array( 'code' => 'error', 'msg' => $amongs['province'].'已设置全部店铺，不用额外设置');
					}
					if($amongs['id'] && $amongs['shopid'] != "" && $shop == ""){
						$model->rollback();
						return array( 'code' => 'error', 'msg' => $amongs['province'].'已设置店铺，不用额外设置全部店铺');
					}
					$sql = "update ".TABLE('express_among')." set among=:amongTop where province=:province and express_type=:express and shopid=:shop";
					$result = $model->execute($sql,$sqlParam);
					if($result == 0){
						$model->rollback();
						return array( 'code' => 'error', 'msg' => '修改失败');
					}
				}
			}else{   //新增
				$total = 0;
				$sql = "select among from ".TABLE('express_among')." where express_type<>:express and province=:province and shopid=:shop";
				$amongs = $model->query($sql,$sqlParam)->select();
				if($amongs){
					foreach($amongs as $among){
						$total = $total + $among['among'];
					}
				}
				$total = $total + $amongTop;
				if($total>100){
					$model->rollback();
					return array( 'code' => 'error', 'msg' => '同一地区下所有快递比例和不能超过100');
				}
				$sql = "select id,shopid from ".TABLE('express_among')." where 
				province=:province and express_type=:express and among<>0";
				$amongs = $model->query($sql,$sqlParam)->find();
				if($amongs['id'] && $amongs['shopid'] == "" && $shop != ""){
					$model->rollback();
					return array( 'code' => 'error', 'msg' => $amongs['province'].'已设置全部店铺，不用额外设置');
				}
				if($amongs['id'] && $amongs['shopid'] != "" && $shop == ""){
					$model->rollback();
					return array( 'code' => 'error', 'msg' => $amongs['province'].'已设置店铺，不用额外设置全部店铺');
				}
				$sql = "insert into ".TABLE('express_among')." (province,express_type,among,shopid) values (:province,:express,:amongTop,:shop)";
				$result = $model->execute($sql,$sqlParam);
				if($result == 0){
					$model->rollback();
					return array( 'code' => 'error', 'msg' => '添加失败');
				}
			}
		}
		$model->commit();
		return array( 'code' => 'ok', 'msg' => '添加成功');
	}
	
	//获取基本信息（店铺、快递、省份）
	public function delBasicSetUp($param){
		$id = $param['id'];
		$model = D();
		$sqlParam = array(
			array('name' => ':id', 'value' => $id, 'type' => PDO::PARAM_STR),
		);
		$sql = "delete FROM ".TABLE('express_among')." where id=:id";
		$result = $model->execute($sql,$sqlParam);
		if($result == 0){
			return array( 'code' => 'error', 'msg' => '添加失败');
		}
		return array( 'code' => 'ok', 'msg' => '添加成功');
	}
	
	//批量删除
	public function selectedTable($param){
		$selectedTable = $param['selectedTable'];
		$model = D();
		$model->startTrans();
		foreach($selectedTable as $key => $data){
			$sqlParam = array(
				array('name' => ':id', 'value' => $data['id'], 'type' => PDO::PARAM_STR),
			);
			$sql = "delete FROM ".TABLE('express_among')." where id=:id";
			$result = $model->execute($sql,$sqlParam);
			if($result == 0){
				$model->rollback();
				return array( 'code' => 'error', 'msg' => '删除失败');
			}
		}
		$model->commit();
		return array( 'code' => 'ok', 'msg' => '删除成功');
	}
	
	/**
	* @author => zn,
	* @time   => 2018-06-30
	* @anno	  => 保密设置
	* @param  => 
	*/
	public function secretSetup($param){
		/**
		$is_set = $param['is_set'];
		if($is_set == "true"){
			$onOff = 1;
		}else{
			$onOff = 0;
		}
		*/
		$address_df = $param['address_df'];
		$model = D();
		//$model->startTrans();
		$user = $_SESSION['LOGIN_USER_ID'];
		$sqlParam = array(
			//array('name' => ':is_set', 'value' => $onOff, 'type' => PDO::PARAM_STR),
			array('name' => ':address_df', 'value' => $address_df, 'type' => PDO::PARAM_STR),
		);
		/**
		$sql = "select configValue from ".TABLE('base_config')." where `type`='wg_daifa_config' and configKey='is_set'";
		$has = $model->query($sql,$sqlParam)->find();
		if($has){
			if($has['configValue'] == $onOff){
				$model->rollback();
				return array( 'code' => 'ok', 'msg' => '保存成功');
			}else{
				$sql = "update ".TABLE('base_config')." set configValue=:is_set where `type`='wg_daifa_config' and configKey = 'is_set'";
			}
		}else{
			$sql = "insert into ".TABLE('base_config')." (configKey,configValue,`type`) values ('is_set',:is_set,'wg_daifa_config')";
		}
		$result = $model->execute($sql,$sqlParam);
		if($result == 0){
			$model->rollback();
			return array( 'code' => 'error', 'msg' => '保存失败');
		}
		*/
		$sql = "select configValue from ".TABLE('base_config')." where `type`='wg_daifa_config' and configKey='address_df'";
		$has = $model->query($sql,$sqlParam)->find();
		if($has){
			if($has['configValue'] == $address_df){
				//$model->rollback();
				return array( 'code' => 'ok', 'msg' => '保存成功');
			}else{
				$sql = "update ".TABLE('base_config')." set configValue=:address_df where `type`='wg_daifa_config' and configKey = 'address_df'";
			}
		}else{
			$sql = "insert into ".TABLE('base_config')." (configKey,configValue,`type`) values ('address_df',:address_df,'wg_daifa_config')";
		}
		$result = $model->execute($sql,$sqlParam);
		if($result == 0){
			//$model->rollback();
			return array( 'code' => 'error', 'msg' => '保存失败');
		}
		//$model->commit();
		return array( 'code' => 'ok', 'msg' => '保存成功');
	}
	
	/**
	* @author => zn,
	* @time   => 2018-06-30
	* @anno	  => 获取保密设置信息
	* @param  => 
	*/
	public function secretSel($param){
		$model = D();
		$result = array();
		//$sql_set = "select configValue from ".TABLE('base_config')." where `type`='wg_daifa_config' and configKey='is_set'";
		$sql_add = "select configValue from ".TABLE('base_config')." where `type`='wg_daifa_config' and configKey='address_df'";
		//$sql_is_set = $model->query($sql_set)->find();
		$sql_address_df = $model->query($sql_add)->find();
		$is_set = "";
		$address_df = "";
		/**
		if($sql_is_set){
			$is_set = $sql_is_set['configValue'];
		}else{
			$is_set = 1;
		}
		*/
		if($sql_address_df){
			$address_df = $sql_address_df['configValue'];
		}
		$result = array(
			//'is_set' => $is_set,
			'address_df' => $address_df,
		);
		return $result;
	}
	
	/**
	* @author => zn,
	* @time   => 2018-07-06
	* @anno	  => 物流设置
	* @param  => 
	*/
	public function getWuliuList($param){
		$express = $GLOBALS['WL'];
		$result = array();
		foreach($express as $key => $value){
			if(substr($key,0,3) == "DF_" || substr($key,0,4) == "JDKD"){
				continue;
			}else{
				$result[] = $value;
			}
		}
		return $result;
	}
	
	/**
	* @author => zn,
	* @time   => 2018-07-06
	* @anno	  => 批量设置直接对接物流，普通物流
	* @param  => 
	*/
	public function setAllWuliu($param){
		$express = $GLOBALS['WL'];
		$datas = $param['data'];
		$model = D();
		$model->startTrans();
		foreach($datas as $data){
			$type = substr($data,3);
			$type = str_replace('_COD','',$type);
			$type_name = '';
			$sort_name = '';
			foreach($express as $key => $exp){
				if(substr($key,0,3) != 'DF_'){
					if($type == $exp['TB_ID']){
						$type_name = $exp['WL_NAME'];
						$sort_name = $exp['SORT_NAME'];
					}
				}
			}
			if(strpos($data,'ZJ') === 0){
				if(stristr($data,'COD')){
					$type_name .= '到付';
				}
				$sqlParam = array(
					array('name' => ':no', 'value' => $data, 'type' => PDO::PARAM_STR),
					array('name' => ':type', 'value' => $type, 'type' => PDO::PARAM_STR),
					array('name' => ':type_name', 'value' => '直接-'.$type_name, 'type' => PDO::PARAM_STR),
					array('name' => ':sort_name', 'value' => $sort_name, 'type' => PDO::PARAM_STR),
				);
				$sql = "SELECT 1 FROM ".TABLE('express')." WHERE `no`=:no";
				if(!$model->query($sql,$sqlParam)->find()){
					$sql = "INSERT INTO ".TABLE('express')."(shopid,`no`,`name`,sort_name,`type`,type_name,
							`default`, `status`, site,send_province, send_city, send_district, send_detail, send_username,
							send_tel, print_province, print_city, print_district, print_detail, ratio, assist_print, send_address_json,express_form )VALUES ('', :no, :type_name, :sort_name, :type, :type_name, '0', '0','','','','','','','','','','','','0','0','',:no)";
					$row = $model->execute($sql,$sqlParam);
					if($row == 0){
						$model->rollback();
						return array( 'code' => 'error', 'msg' => '添加失败');
					}
					$upd = $model->execute("UPDATE ".TABLE('Express')." SET express_id = CONCAT(`type`,'_',`id`) WHERE express_id = ''");
					if($upd  == 0){
						$model->rollback();
						return array( 'code' => 'error', 'msg' => '添加失败');
					}
				}
			}else if(strpos($data,'PT') === 0){
				$sqlParam = array(
					array('name' => ':no', 'value' => $data, 'type' => PDO::PARAM_STR),
					array('name' => ':type', 'value' => $type, 'type' => PDO::PARAM_STR),
					array('name' => ':type_name', 'value' => '普通-'.$type_name, 'type' => PDO::PARAM_STR),
					array('name' => ':sort_name', 'value' => $sort_name, 'type' => PDO::PARAM_STR),
				);
				$sql = "SELECT 1 FROM ".TABLE('express')." WHERE `no`=:no";
				if(!$model->query($sql,$sqlParam)->find()){
					$sql = "INSERT INTO ".TABLE('express')."(shopid,`no`,`name`,sort_name,`type`,type_name,
							`default`, `status`, site,send_province, send_city, send_district, send_detail, send_username,
							send_tel, print_province, print_city, print_district, print_detail, ratio, assist_print, send_address_json,express_form )VALUES ('', :no, :type_name, :sort_name, :type, :type_name, '0', '0','','','','','','','','','','','','0','0','',:no)";
					$row = $model->execute($sql,$sqlParam);
					if($row == 0){
						$model->rollback();
						return array( 'code' => 'error', 'msg' => '添加失败');
					}
					$upd = $model->execute("UPDATE ".TABLE('Express')." SET express_id = CONCAT(`type`,'_',`id`) WHERE express_id = ''");
					if($upd  == 0){
						$model->rollback();
						return array( 'code' => 'error', 'msg' => '添加失败');
					}
				}
			}
		}
		$model->commit();
		return array( 'code' => 'ok', 'msg' => '添加成功');
	}
	
	/**
	* @author => zn,
	* @time   => 2018-09-03
	* @anno	  => 设置打印省份选择
	* @param  => 
	*/
	public function setupPrintList($param){
		$models=D_SYS();
		$sql = "SELECT province_code,province_name FROM data_province ORDER BY id";
		$rows = $models->query($sql)->select();
		$model = D();
		$sql = "SELECT configKey,configValue,`type` FROM ".TABLE('base_config')." WHERE `type`='PrintAttr' AND configKey='PrintAttr'";
		$Items = $model->query($sql)->find();
		if($Items){
			$attrs = explode(",",$Items['configValue']);
			foreach($rows as $key => $row){
				foreach($attrs as $attr){
					if($row['province_name'] == $attr){
						$rows[$key]['LAY_CHECKED'] = true;
					}
				}
			}
			$result = $rows;
		}else{
			$result = $rows;
		}
		return $result;
	}
	
	/**
	* @author => zn,
	* @time   => 2018-11-19
	* @anno	  => 查询功能权限
	* @param  => 
	*/
	public function getFuncPremList($param){
		$id = $param['id'];
		$sqlParam = array(
			array('name' => ':id', 'value' => $id, 'type' => PDO::PARAM_STR),
		);
		$model = D();
		$rows = array();
		$row = array();
		for($i=0;$i<9;$i++){
			$rows[] = $row;
		}
		$sql = "select func_permission from ".TABLE('usertable')." where id=:id";
		$result = $model->query($sql,$sqlParam)->find();
		if($result && $result['func_permission'] && $result != 'F'){
			$lines = explode(",",$result['func_permission']);
		}else if($result == 'F'){
			$lines = 'F';
		}else{
			$lines = 'T';
		}
		return $lines;
	}
	
	public function saveFuncPremList($param){
		$id = $param['id'];
		$datas = $param['data'];
		$permission = $datas?$datas:'F';
		
		$sqlParam = array(
			array('name' => ':id', 'value' => $id, 'type' => PDO::PARAM_STR),
			array('name' => ':func_permission', 'value' => $permission, 'type' => PDO::PARAM_STR),
		);
		$model = D();
		$sql = "update ".TABLE('usertable')." set func_permission=:func_permission where id=:id";
		$result = $model->execute($sql,$sqlParam);
		if($result === false){
			return array('code'=>'error','msg'=>'修改失败');
		}
		return array('code'=>'ok','msg'=>'修改成功');
	}
	
	public function getAGList($param){
		$model = D();
		$sql = "select shopid,shopname,isAG from ".TABLE('shop_config')."  where shoptype in ('TB','TM') and `status` = '1' ";
		if($result = $model->query($sql)->select()){
			return $result;
		}
	}
	
	public function getAGConfig(){
		$model = D();
		
		$sql = "select configKey,configValue from ".TABLE('base_config')." where type='aliAGConfig' and configKey in ('unshipped','shipped') ";
		if($rows = $model->query($sql)->select()){
			foreach($rows as $list){
				if($list['configKey'] == "unshipped"){
					$result['unshipped'] = $list['configValue'];
				}else if($list['configKey'] == "shipped"){
					$result['shipped'] = $list['configValue'];
				}
			}
		}
		
		return $result;
	}
	
	public function setAGConfig($param){
		$model = D();
		
		include_once("MYAPP/system/model/ApiOptionDB.php");
		$apiOptionDB = new ApiOptionDB();
		
		$unshipped = $param['unshipped'];
		$unshipped = $unshipped == "true" ? "on" : "";
		$shipped = $param['shipped'];
		$shipped = $shipped == "true" ? "on" : "";
		
		$submitData = $param['submitData'];
		$submitData = json_decode($submitData,true);
		
		$sqlParam = array(
			array('name' => ':unshipped', 'value' => $unshipped, 'type' => PDO::PARAM_STR),
			array('name' => ':shipped', 'value' => $shipped, 'type' => PDO::PARAM_STR),
		);
		
		$sql = "select 1 from ".TABLE('base_config')." where type='aliAGConfig' and configKey='unshipped' ";
		if($row = $model->query($sql,$sqlParam)->find()){
			$model->execute("update ".TABLE('base_config')." set configValue=:unshipped where type='aliAGConfig' and configKey='unshipped' ",$sqlParam);
		}else{
			$model->execute("insert into ".TABLE('base_config')."(configKey,configValue,type)values('unshipped',:unshipped,'aliAGConfig')  ",$sqlParam);
		}
		
		$sql = "select 1 from ".TABLE('base_config')." where type='aliAGConfig' and configKey='shipped' ";
		if($row = $model->query($sql,$sqlParam)->find()){
			$model->execute("update ".TABLE('base_config')." set configValue=:shipped where type='aliAGConfig' and configKey='shipped' ",$sqlParam);
		}else{
			$model->execute("insert into ".TABLE('base_config')."(configKey,configValue,type)values('shipped',:shipped,'aliAGConfig')  ",$sqlParam);
		}
		
		$return_data = array('code' => 'ok');
		$errorMsg = '';
		foreach($submitData as $data){
			$shopid = $data['shopid'];
			$isAG = $data['isAG'];
			
			if($isAG == '1'){
				$sql = "select shoptype,shopname,appkey,secretkey,sessionkey from ".TABLE('shop_config')." where shopid = :shopid ";
				$row = $model->query($sql,array(
					array('name' => ':shopid', 'value' => $shopid, 'type' => PDO::PARAM_STR),
				))->find();
				$appkey = $row['appkey'];
				$secretkey = $row['secretkey'];
				$sessionkey = $row['sessionkey'];
				
				$resultInfo = $apiOptionDB->TaobaoRdcAligeniusAccountValidate(array('appkey' => $appkey, 'secretkey' => $secretkey, 'sessionkey' => $sessionkey));
				if($resultInfo['result']['success'] == '1'){
					if($resultInfo['result']['result_data']['ag_account'] == '1'){
						$model->execute("update ".TABLE('shop_config')." set isAG='1' where shopid = :shopid ",array(
							array('name' => ':shopid', 'value' => $shopid, 'type' => PDO::PARAM_STR),
						));
					}else{
						$return_data['code'] = 'error';
						$errorMsg .= '店铺['.$row['shopname'].']未开启AG，请先开启<br/>';
					}
				}else{
					$return_data['code'] = 'error';
					$errorMsg .= '店铺['.$row['shopname'].']获取AG权限失败，请检查授权或稍后再尝试<br/>';
				}
			}else{
				$model->execute("update ".TABLE('shop_config')." set isAG='0' where shopid = :shopid ",array(
					array('name' => ':shopid', 'value' => $shopid, 'type' => PDO::PARAM_STR),
				));
			}
		}
		
		$return_data['msg'] = $errorMsg;
		return $return_data;
	}
	
	public function getAlipayCustoms(){
		$model = D();
		
		$page = $param['pageSize'];
        $limit = $param['pageIndex'];
		$page1 = ($page*1-1)*$limit;
		$result = array();
		
		$sql = "SELECT a.shoptype,a.shopid,a.shopname,b.partner,b.secret,b.customs_place,b.customs_code,b.customs_name,
				b.customs_code_zongshu,b.customs_name_zongshu FROM ".TABLE('shop_config')." a 
				left join (select shopid,partner,secret,customs_place,customs_code,customs_name,customs_code_zongshu,customs_name_zongshu from ".TABLE('payapi_set')." where apitype = 'alipay' ) b on a.shopid = b.shopid 
				WHERE a.status = '1' ";
		$rows = $model->query($sql,$sqlParam)->limitPage($page1,$limit)->select();
		if($rows){
			foreach($rows as $row){
				$result[] = array(
					'shopid' => $row['shopid'],
					'shopname' => parent::getPlatName($row['shoptype']).'-'.$row['shopname'],
					'partner' => $row['partner'],
					'secret' => $row['secret'],
					'customs_place' => $row['customs_place'],
					'customs_code' => $row['customs_code'],
					'customs_name' => $row['customs_name'],
					'customs_code_zongshu' => $row['customs_code_zongshu'],
					'customs_name_zongshu' => $row['customs_name_zongshu'],
				);
			}
		}
		$sql = "SELECT count(1) as nums FROM ".TABLE('shop_config')." WHERE status = '1' ";
		$pageCount = $model->query($sql)->find();
		$return['total'] = $pageCount['nums'];
		$return['data'] = $result;
		return $return;
	}
	
	public function saveAlipayCustoms($param){
		$submitData = $param['submitData'];		
		$submitData = json_decode($submitData,true);
		$model = D();
		
		foreach($submitData as $data){
			$sqlParam = array(
				array('name' => ':shopid', 'value' => $data['shopid'], 'type' => PDO::PARAM_STR),
				array('name' => ':partner', 'value' => $data['partner'], 'type' => PDO::PARAM_STR),
				array('name' => ':secret', 'value' => $data['secret'], 'type' => PDO::PARAM_STR),
				array('name' => ':customs_place', 'value' => $data['customs_place'], 'type' => PDO::PARAM_STR),
				array('name' => ':customs_code', 'value' => $data['customs_code'], 'type' => PDO::PARAM_STR),
				array('name' => ':customs_name', 'value' => $data['customs_name'], 'type' => PDO::PARAM_STR),
				array('name' => ':customs_code_zongshu', 'value' => $data['customs_code_zongshu'], 'type' => PDO::PARAM_STR),
				array('name' => ':customs_name_zongshu', 'value' => $data['customs_name_zongshu'], 'type' => PDO::PARAM_STR),
			);
			if($data['_state'] == 'modified'){
				$sql = "select 1 from ".TABLE('payapi_set')." where shopid=:shopid and apitype = 'alipay' ";
				if($result = $model->query($sql,$sqlParam)->find()){
					$sql = "UPDATE ".TABLE('payapi_set')." SET shopid=:shopid, partner=:partner, secret=:secret, customs_place=:customs_place, customs_code=:customs_code, customs_name=:customs_name, customs_code_zongshu=:customs_code_zongshu, customs_name_zongshu=:customs_name_zongshu WHERE shopid=:shopid and apitype = 'alipay' ";
					$model->execute($sql,$sqlParam);
				}else{
					$sql = "insert into ".TABLE('payapi_set')."(apitype, shopid, partner, secret, customs_place, customs_code, customs_name, customs_code_zongshu, customs_name_zongshu)
							values('alipay', :shopid, :partner, :secret, :customs_place, :customs_code, :customs_name, :customs_code_zongshu, :customs_name_zongshu)";
					$model->execute($sql,$sqlParam);
				}
			}
		}
		return array("code"=>"ok","msg"=>"操作成功");
	}
	
	public function getWeixinCustoms(){
		$model = D();
		
		$page = $param['pageSize'];
        $limit = $param['pageIndex'];
		$page1 = ($page*1-1)*$limit;
		$result = array();
		
		$sql = "SELECT a.shoptype,a.shopid,a.shopname,b.partner,b.mch_id,b.app_key,b.secret,b.customs_place,b.customs_code,
				b.customs_code_zongshu FROM ".TABLE('shop_config')." a 
				left join (select shopid,partner,mch_id,app_key,secret,customs_place,customs_code,customs_code_zongshu from ".TABLE('payapi_set')." where apitype = 'weixin' ) b on a.shopid = b.shopid 
				WHERE a.status = '1' ";
		$rows = $model->query($sql,$sqlParam)->limitPage($page1,$limit)->select();
		if($rows){
			foreach($rows as $row){
				$result[] = array(
					'shopid' => $row['shopid'],
					'shopname' => parent::getPlatName($row['shoptype']).'-'.$row['shopname'],
					'partner' => $row['partner'],
					'mch_id' => $row['mch_id'],
					'app_key' => $row['app_key'],
					'secret' => $row['secret'],
					'customs_place' => $row['customs_place'],
					'customs_code' => $row['customs_code'],
					'customs_code_zongshu' => $row['customs_code_zongshu'],
				);
			}
		}
		$sql = "SELECT count(1) as nums FROM ".TABLE('shop_config')." WHERE status = '1' ";
		$pageCount = $model->query($sql)->find();
		$return['total'] = $pageCount['nums'];
		$return['data'] = $result;
		return $return;
	}
	
	public function saveWeixinCustoms($param){
		$submitData = $param['submitData'];		
		$submitData = json_decode($submitData,true);
		$model = D();
		
		foreach($submitData as $data){
			$sqlParam = array(
				array('name' => ':shopid', 'value' => $data['shopid'], 'type' => PDO::PARAM_STR),
				array('name' => ':partner', 'value' => $data['partner'], 'type' => PDO::PARAM_STR),
				array('name' => ':mch_id', 'value' => $data['mch_id'], 'type' => PDO::PARAM_STR),
				array('name' => ':app_key', 'value' => $data['app_key'], 'type' => PDO::PARAM_STR),
				array('name' => ':secret', 'value' => $data['secret'], 'type' => PDO::PARAM_STR),
				array('name' => ':customs_place', 'value' => $data['customs_place'], 'type' => PDO::PARAM_STR),
				array('name' => ':customs_code', 'value' => $data['customs_code'], 'type' => PDO::PARAM_STR),
				array('name' => ':customs_code_zongshu', 'value' => $data['customs_code_zongshu'], 'type' => PDO::PARAM_STR),
			);
			if($data['_state'] == 'modified'){
				$sql = "select 1 from ".TABLE('payapi_set')." where shopid=:shopid and apitype = 'weixin' ";
				if($result = $model->query($sql,$sqlParam)->find()){
					$sql = "UPDATE ".TABLE('payapi_set')." SET shopid=:shopid, partner=:partner, mch_id=:mch_id, app_key=:app_key, secret=:secret, customs_place=:customs_place, customs_code=:customs_code, customs_code_zongshu=:customs_code_zongshu WHERE shopid=:shopid and apitype = 'weixin' ";
					$model->execute($sql,$sqlParam);
				}else{
					$sql = "insert into ".TABLE('payapi_set')."(apitype, shopid, partner, mch_id, app_key, secret, customs_place, customs_code, customs_code_zongshu)
							values('weixin', :shopid, :partner, :mch_id, :app_key, :secret, :customs_place, :customs_code, :customs_code_zongshu)";
					$model->execute($sql,$sqlParam);
				}
			}
		}
		
		return array("code"=>"ok","msg"=>"操作成功");
	}
	
	//app设置
	public function getMobileConfig(){
		$model = D();
		$result = array();
		$result['autoItem'] = 0;
		$sql = "select configKey,configValue from ".TABLE('base_config')." where type='mobileConfig' ";
		if($rows = $model->query($sql)->select()){
			foreach($rows as $list){
				if($list['configKey'] == "dataStatistics"){
					$result['dataStatistics'] = $list['configValue'];
				}
			}
		}
		return $result;
	}
	
	public function saveMobileConfig($param){
		$modelSYS = D_SYS();
		$model = D();
		$system_id = $_SESSION['LOGIN_SYSTEM_ID'];
		$data = $param['data'];
		
		$dataStatistics = $data['dataStatistics'];//app数据统计设计
		
		$sqlParam = array(
			array('name' => ':dataStatistics', 'value' => $dataStatistics, 'type' => PDO::PARAM_STR),
		);
		
		$sql = "select 1 from ".TABLE('base_config')." where type='mobileConfig' and configKey='dataStatistics' ";
		if($row = $model->query($sql,$sqlParam)->find()){
			$model->execute("update ".TABLE('base_config')." set configValue=:dataStatistics where type='mobileConfig' and configKey='dataStatistics' ",$sqlParam);
		}else{
			$model->execute("insert into ".TABLE('base_config')."(configKey,configValue,type)values('dataStatistics',:dataStatistics,'mobileConfig')  ",$sqlParam);
		}
		return array("code" => "ok");
	}
	
	public function delTableOnceExpress($param){
		$model = D();
		$id = $param['type'];
		$sqlParam = array(
			array('name' => ':id', 'value' => $id, 'type' => PDO::PARAM_STR),
		);
		$sql = "UPDATE ".TABLE('express')." SET del_logo=1,status=1 WHERE id=:id";
		$result = $model->execute($sql,$sqlParam);
		if($result){
			return array("code" => "ok","msg"=>"保存成功");
		}else{
			return array("code" => "error","msg"=>"保存失败");
		}
	}
	
	public function areaLoadNodes($param){
		$modelSYS = D_SYS();
		$model = D();
		
		$express_type = $param['express_type'];
		$sqlParam = array(
			array('name' => ':express_type', 'value' => $express_type, 'type' => PDO::PARAM_STR),
		);
		
		$sql = "select 0 as pid, province_code as id, province_name as CityName
				from  data_province 
				union all 
				select  province_code as pid, city_code as id, city_name as CityName
				from  data_city
				order by id ";
		if($rowArea = $modelSYS->query($sql)->select()){
			$sql = "select city_code from  ".TABLE('freight_area')." where express_type = :express_type ";
			if($rowCity = $model->query($sql,$sqlParam)->select()){
				$cityList = array();
				foreach($rowCity as $listCity){
					$cityList[$listCity['city_code']] = $listCity['city_code'];
				}
				
				foreach($rowArea as $key => $listArea){
					if($cityList[$listArea['id']]){
						$rowArea[$key]['checked'] = true;
					}
				}
			}
		}
		
		return $rowArea;
	}
	
	public function areaAdd($param){
		$model = D();
		$model->startTrans();
		$AreaName = $param['AreaName'];
		$AreaScope = $param['AreaScope'];
		$Remarks = $param['Remarks'];
		
		$sqlParam = array(
			array('name' => ':AreaName', 'value' => $AreaName, 'type' => PDO::PARAM_STR),
			array('name' => ':Remarks', 'value' => $Remarks, 'type' => PDO::PARAM_STR),
		);
		
		$sql = "insert into ".TABLE('areaInfo')."(areaName,remark)values(:AreaName,:Remarks)";
		$result = $model->execute($sql,$sqlParam);
		if($result == 0){
			$model->rollback();
			return array('code'=>'error','msg'=>'新增失败');
		}
			
		$areaId = $model->lastInsertId();
		$AreaScopeList = explode(',',$AreaScope);
		foreach($AreaScopeList as $city_code){
			$sqlParam = array(
				array('name' => ':area_id', 'value' => $areaId, 'type' => PDO::PARAM_STR),
				array('name' => ':city_code', 'value' => $city_code, 'type' => PDO::PARAM_STR),
			);
		
			$result = $model->execute("insert into ".TABLE('freight_area')."(area_id,city_code)values(:area_id, :city_code)",$sqlParam);
			if($result == 0){
				$model->rollback();
				return array('code'=>'error','msg'=>'新增失败');
			}
		}
		
		$model->commit();
		return array('code'=>'ok');
	}
	
	public function areaDel($param){
		$model = D();
		
		$id = $param['id'];
		$id = str_replace(",","','",$id);
		
		$model->execute("delete from ".TABLE('areaInfo')." where id in ('".$id."') ");
		$model->execute("delete from ".TABLE('freight_area')." where area_id in ('".$id."') ");
		//预留删除快递规则
	}
	
	public function expressExpensesAreaMain($param){
		$model = D();
		
		$PER_PAGE = $param["pageSize"];
		$CUR_PAGE = $param["pageIndex"];
		$CUR_PAGE = ($CUR_PAGE * 1) * $PER_PAGE;
		$keyword = $param["keyword"];

		$rowcount = $model->query("select count(1) as count FROM ".TABLE("areaInfo"),$sqlParam)->find();
		$arr = array();
		$rows = $model->query("select id,areaName,remark from ".TABLE("areaInfo") ,$sqlParam)->limitPage($CUR_PAGE,$PER_PAGE)->select();
		if($rows){
			foreach($rows as $list){
				$action = "<a onclick='edit(\"".$list["id"]."\")' >编辑</a>";
				$AreaScope = "<a onclick='look(\"".$list["id"]."\")' >查看</a>";
	   
				$arr[] = array('id' => $list['id'], 'areaName' => $list['areaName'], 'remark' => $list['remark'], 'action' => $action, 'AreaScope' => $AreaScope);
			}
		}
		
		$result['total'] = $rowcount['count'];
		$result['data'] = $arr;
		
		return $result;
	}
	
	public function areaLook($param){
		$model = D();
		
		$sqlParam = array(
			array('name' => ':id', 'value' => $param['id'], 'type' => PDO::PARAM_STR),
		);
		
		$AreaScope = '';
		$sql = "select city_code from ".TABLE('freight_area')." where area_id = :id ";
		if($rowArea = $model->query($sql,$sqlParam)->select()){
			foreach($rowArea as $listArea){
				$AreaScope .= $listArea['city_code'].',';
			}
		}
		$AreaScope = rtrim($AreaScope,',');
		
		return array('AreaScope' => $AreaScope);
	}
	
	public function areaGet($param){
		$model = D();
		
		$sqlParam = array(
			array('name' => ':id', 'value' => $param['id'], 'type' => PDO::PARAM_STR),
		);
		
		$sql = "select id,areaName,remark from ".TABLE('areaInfo')." where id = :id ";
		$row = $model->query($sql,$sqlParam)->find();
		
		$AreaScope = '';
		$sqlArea = "select city_code from ".TABLE('freight_area')." where area_id = :id ";
		if($rowArea = $model->query($sqlArea,$sqlParam)->select()){
			foreach($rowArea as $listArea){
				$AreaScope .= $listArea['city_code'].',';
			}
		}
		$AreaScope = rtrim($AreaScope,',');
		
		return array('id' => $row['id'], 'areaName' => $row['areaName'], 'remark' => $row['remark'], 'AreaScope' => $AreaScope);
	}
	
	public function areaUpdate($param){
		$model = D();
		
		$model->startTrans();
		$AreaId = $param['AreaId'];
		$AreaName = $param['AreaName'];
		$AreaScope = $param['AreaScope'];
		$Remarks = $param['Remarks'];
		
		$sqlParam = array(
			array('name' => ':id', 'value' => $AreaId, 'type' => PDO::PARAM_STR),
			array('name' => ':AreaName', 'value' => $AreaName, 'type' => PDO::PARAM_STR),
			array('name' => ':Remarks', 'value' => $Remarks, 'type' => PDO::PARAM_STR),
		);
		
		$sql = "update ".TABLE('areaInfo')." set areaName = :AreaName, remark = :Remarks where id = :id ";
		$result = $model->execute($sql,$sqlParam);
		if($result === false){
			$model->rollback();
			return array('code'=>'error','msg'=>'修改失败');
		}
		
		$model->execute("delete from ".TABLE('freight_area')." where area_id = :id ",$sqlParam);
		
		$AreaScopeList = explode(',',$AreaScope);
		foreach($AreaScopeList as $city_code){
			$sqlParam = array(
				array('name' => ':area_id', 'value' => $AreaId, 'type' => PDO::PARAM_STR),
				array('name' => ':city_code', 'value' => $city_code, 'type' => PDO::PARAM_STR),
			);
		
			$result = $model->execute("insert into ".TABLE('freight_area')."(area_id,city_code)values(:area_id, :city_code)",$sqlParam);
			if($result == 0){
				$model->rollback();
				return array('code'=>'error','msg'=>'修改失败');
			}
		}
		
		$model->commit();
		return array('code'=>'ok');
	}
	
	public function getWlComp($param){
		$model = D();
		
		$result = array();
		$expressList = parent::getExpressConfig();
		if(is_array($expressList)){
			foreach($expressList as $id => $express){
				$result[] = array('id' => $id, 'text' => $express['name']);
			}
		}
		
		return $result;
	}
	
	public function expensesUpdate($param){
		$model = D();
		
		$Area_id = $param['Area_id'];
		$data = $param['data'];
		
		for($i = 0; $i < count($data); $i++){    
			$state = $data[$i]['_state'];
			$id = $data[$i]['id'];
			$express_type = $data[$i]['express_type'];
			$start_weight = $data[$i]['start_weight'];
			$start_amtn = $data[$i]['start_amtn'];
			$continue_weight = $data[$i]['continue_weight'];
			$continue_amtn = $data[$i]['continue_amtn'];
			$round_way = $data[$i]['round_way'];
			if($round_way == "" || $round_way == "null"){
				$round_way = "0";
			}
			
			$start_weight = $start_weight == "" ? 0 : $start_weight;
			$start_amtn = $start_amtn == "" ? 0 : $start_amtn;
			$continue_weight = $continue_weight == "" ? 0 : $continue_weight;
			$continue_amtn = $continue_amtn == "" ? 0 : $continue_amtn;
			
			$sqlParam = array(
				array('name' => ':id', 'value' => $id, 'type' => PDO::PARAM_STR),
				array('name' => ':area_id', 'value' => $Area_id, 'type' => PDO::PARAM_STR),
				array('name' => ':express_type', 'value' => $express_type, 'type' => PDO::PARAM_STR),
				array('name' => ':start_weight', 'value' => $start_weight, 'type' => PDO::PARAM_STR),
				array('name' => ':start_amtn', 'value' => $start_amtn, 'type' => PDO::PARAM_STR),
				array('name' => ':continue_weight', 'value' => $continue_weight, 'type' => PDO::PARAM_STR),
				array('name' => ':continue_amtn', 'value' => $continue_amtn, 'type' => PDO::PARAM_STR),
				array('name' => ':round_way', 'value' => $round_way, 'type' => PDO::PARAM_STR),
			);
		
			if($state == "added"){
				$model->execute("insert into ".TABLE('freight_rule')."(area_id,express_type,start_weight,start_amtn,continue_weight,continue_amtn,round_way)
								 values(:area_id, :express_type, :start_weight, :start_amtn, :continue_weight, :continue_amtn, :round_way)",$sqlParam);
			}else if($state == "modified"){
				$model->execute("update ".TABLE('freight_rule')." set express_type=:express_type,start_weight=:start_weight,start_amtn=:start_amtn,continue_weight=:continue_weight,continue_amtn=:continue_amtn,round_way=:round_way  where id = :id ",$sqlParam);
			}else if($state == "removed"){
			    $model->execute("delete from  ".TABLE('freight_rule')."  where id = :id ",$sqlParam);
			}
		}
		
		return array('code' => 'ok');
	}
	
	public function expensesMain($param){
		$model = D();
		
		$PER_PAGE = $param["pageSize"];
		$CUR_PAGE = $param["pageIndex"];
		$CUR_PAGE = ($CUR_PAGE * 1) * $PER_PAGE;
		$Area_id = $param["Area_id"];
		
		$sqlParam = array(
			array('name' => ':area_id', 'value' => $Area_id, 'type' => PDO::PARAM_STR),
		);
			
		$rowcount = $model->query("select count(1) as count FROM ".TABLE("freight_rule")." where area_id = :area_id ",$sqlParam)->find();
		$arr = array();
		$rows = $model->query("select id,area_id,express_type,start_weight,start_amtn,continue_weight,continue_amtn,round_way from ".TABLE("freight_rule")." where area_id = :area_id " ,$sqlParam)->limitPage($CUR_PAGE,$PER_PAGE)->select();
		if($rows){
			foreach($rows as $list){
				$arr[] = array('id' => $list['id'], 'area_id' => $list['area_id'], 'express_type' => $list['express_type'], 'start_weight' => $list['start_weight'],
							   'start_amtn' => $list['start_amtn'], 'continue_weight' => $list['continue_weight'], 'continue_amtn' => $list['continue_amtn'], 'round_way' => $list['round_way']);
			}
		}
		
		$result['total'] = $rowcount['count'];
		$result['data'] = $arr;
		
		return $result;
	}
	
	public function recoveryExpress($param)
    {
        $model = D();
		$ids = $param['ids'];
		$ids = str_replace(",","','",$ids);
	
		$row = $model->execute("update ".TABLE('express')." set del_logo = 0 where id in ('".$ids."')");
		if($row){
			$re=array(
				"code" => "0000",
				"msg" =>"恢复成功！",
			);
		}else{
			$re = array(
				"code" => "error",
				"msg" =>"恢复失败",
			);
		}
        return $re;
    }
	
	public function getPrintTemplate($param){
		$model = D();
		
		$sqlParam = array(
			array('name' => ':module_type', 'value' => $param['module_type'], 'type' => PDO::PARAM_STR),
		);
		
		$result = $model->query("select module_file FROM `print_module` where module_type = :module_type and is_default = 1 ",$sqlParam)->find();
		$module_file = $result['module_file'];
		if($module_file == ''){
			return array('code' => 'error', 'msg' => '请先设置默认模板');
		}else{
			return array('code' => 'ok', 'report' => $module_file);
		}
	}
}