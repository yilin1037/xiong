<?
/* 2015-11-04
** lizhilin
** 核心库
** 
*/
class CommonModelClass 
{
	public function __construct($NO_LOGIN = '')
	{
		/*$arr = getbrowser();
		if($arr['browser'] != 'Chrome' && $arr['browser'] != 'Safari' || $arr['browser'] == 'Chrome' && substr($arr['version'],0,2)<20 || $arr['browser'] == 'Safari' && substr($arr['version'],0,1)<6)
		{
			include_once('/'.MYAPP.'/system/view/main/compatibility.php');
			exit;	
		}*/
		if($NO_LOGIN != "T"){
			$this->checkLogin();	
		}
	}
	
	public function checkLogin()
	{
		session_write_close();
		if(empty($_SESSION['LOGIN_DBNAME']) || empty($_SESSION['LOGIN_USER_ID']))
		{
			header("location:".U('system/main/login'));	
		}
	}
	
	public function getUserInfo(){
		$model = D();
		
		$result_data = array();
		
		$sql = "select userid,username from ".TABLE('usertable');
		if($result = $model->query($sql)->select()){
			foreach($result as $list){
				$result_data[$list['userid']] = array(
					'username' => $list['username']
                );
			}
		}
		
		return $result_data;
	}
	
	public function getShopConfig($system_id = ''){
		$model = D($system_id);
		
		$result_data = array();
		$where = '';
        $sql = "select shop_permission from ".TABLE('usertable',$system_id)." where `userid` = '".$_SESSION['LOGIN_USER_ID']."' ";
		if($row = $model->query($sql)->find()){
			if($row['shop_permission'] != ''){
				$where = " where shopid in ('".str_replace(",","','",$row['shop_permission'])."') ";
			}
		}
		
        $sql = "select shoptype,shopid,shopname,expire_time,down_time,shop_print_province,shop_print_city,shop_print_district,shop_print_detail,shop_print_username,shop_print_tel from ".TABLE('shop_config',$system_id).$where;
        if($result = $model->query($sql)->select()){
			foreach($result as $list){
				if($list['down_time'] == "0"){
					$down_time = "";
				}else{
					$down_time = date('Y-m-d H:i:s',$list['down_time']);
				}
				
				$result_data[$list['shopid']] = array(
					'shoptype' => $list['shoptype'],
                    'shopid' => $list['shopid'],
                    'shopname' => $list['shopname'],
                    'expire_time' => date('Y-m-d',$list['expire_time']),
					'down_time' => $down_time,
					'shop_print_province' => $list['shop_print_province'],
					'shop_print_city' => $list['shop_print_city'],
					'shop_print_district' => $list['shop_print_district'],
					'shop_print_detail' => $list['shop_print_detail'],
					'shop_print_username' => $list['shop_print_username'],
					'shop_print_tel' => $list['shop_print_tel'],
                );
			}
		}
		
		return $result_data;
	}
	public function getShopConfigStatus1(){
		$model = D();
		
		$result_data = array();
        $sql = "select shoptype,shopid,shopname,expire_time,down_time from ".TABLE('shop_config')." where status='1' and shoptype != 'XX' and shoptype != 'TPLUS'";
        if($result = $model->query($sql)->select()){
			foreach($result as $list){
				if($list['down_time'] == "0"){
					$down_time = "";
				}else{
					$down_time = date('Y-m-d H:i:s',$list['down_time']);
				}
				
				$result_data[$list['shopid']] = array(
					'shoptype' => $list['shoptype'],
                    'shopid' => $list['shopid'],
                    'shopname' => '['.$this->getPlatName($list['shoptype']).']-'.$list['shopname'],
                    'expire_time' => date('Y-m-d',$list['expire_time']),
					'down_time' => $down_time,
                );
			}
		}
		
		return $result_data;
	}
	public function getPlatName($platId){
		$result = array(
			'TB' => '淘宝天猫',
			'TM' => '淘宝天猫',
			'PDD' => '拼多多',
			'MGJ' => '蘑菇街',
			'JD' => '京东',
			'XX' => '线下',
			'ALBB' => '阿里巴巴',
			'CCJ' => '楚楚街',
            'CQSHOP' => '超群商城',
			'ZHE800' => '折800',
			'FXG' => '放心购',
			'BB' => '贝贝',
			'JP' => '卷皮',
			'YZ' => '有赞',
			'TPLUS' => '用友T+',
			'XP' => '虾皮',
			'SN' => '苏宁',
			'VIP' => '唯品会',
		);
		
		return $result[$platId];
	}
	
	public function getExpressConfig($system_id = ''){
		$model = D($system_id);
		
		$result_data = array();
		$sql = "SELECT id,shopid,`no`,`name`,sort_name,`type`,express_id,express_form,type_name,`default`,`status`,`site`,send_province,send_city,send_district,
			    send_detail,send_username,send_tel,ratio,assist_print,send_address_json,print_province,print_city,print_district,print_detail 
				FROM ".TABLE('express',$system_id)." WHERE status='0'";
		if($result = $model->query($sql)->select()){
			foreach($result as $list){
				$result_data[$list['express_id']] = array('express_type' => $list['type'], 'type' => $list['express_id'], 'express_form' => $list['express_form'], 'name' => $list['name'], 'print_province' => $list['print_province'], 'print_city' => $list['print_city'], 'print_district' => $list['print_district'], 'print_detail' => $list['print_detail'], 'send_username' => $list['send_username'], 'send_tel' => $list['send_tel']);
			}
		}
		
		return $result_data;
	}
	
	public function getStorageConfig($system_id = ''){
		$model = D($system_id);
		
		$result_data = array();
		$sql = "SELECT wh,name FROM ".TABLE('my_wh',$system_id);
		if($result = $model->query($sql)->select()){
			foreach($result as $list){
				$result_data[$list['wh']] = array('no' => $list['wh'], 'name' => $list['name']);
			}
		}
		
		return $result_data;
	}
	
	public function getSignStatusList($system_id = ''){
		$model = D($system_id);
		
		$result_data = array('0' => '');
		$sql = "SELECT id,statusName FROM ".TABLE('sign_status',$system_id);
		if($result = $model->query($sql)->select()){
			foreach($result as $list){
				$result_data[$list['id']] = $list['statusName'];
			}
		}
		
		return $result_data;
	}
	
	public function getFxLevel(){
		return array(
			'1' => '等级一',
			'2' => '等级二',
			'3' => '等级三',
			'4' => '等级四',
			'5' => '等级五',
		);
	}
	
	public function getExpressName($system_id = ''){
		$model = D($system_id);
		
		$result_data = array();
		if($GLOBALS['WL']){
			foreach($GLOBALS['WL'] as $WL_KEY => $WL){
				if(substr($WL_KEY,0,3) == "DF_"){
					$result_data[$WL_KEY] = array(
						'type' => $WL_KEY,
						'name' => $WL['WL_NAME'],
						'sort_name' => $WL['SORT_NAME'],
						'express_form' => 'DF',
						'express_fee' => 0,
					);	
				}
			}
		}
        
		$sql = "SELECT `name`,`express_id`,sort_name,express_form,`type`,express_fee  FROM ".TABLE('express',$system_id);
		if($result = $model->query($sql)->select())
		{
			foreach($result as $list)
			{
				$result_data[$list['express_id']] = array
				(
                    'type' => $list['express_id'],
					'express_type' => $list['type'],
                    'name' => $list['name'],
					'sort_name' => $list['sort_name'],
					'express_form' => $list['express_form'],
					'express_fee' => $list['express_fee'],
                );
			}
		}
		
		return $result_data;
	}
	
	public function getLocalStatus(){
		$result_data = array();
		if($_SESSION['ORDER_APPROVAL'] == '1'){
			$result_data['WAIT_ASSIGN'] = '待审核';
		}else{
			$result_data['WAIT_ASSIGN'] = '待发货';
		}
		
		$result_data['WAIT_SENDED_ASSIGN'] = '预发货';
		$result_data['WAIT_FINISH_ASSIGN'] = '待发货';
		$result_data['WAIT_SENDED'] = '已发货';
		$result_data['WAIT_FAULT'] = '取消';
		
		return $result_data;
	}
	
	public function getWebStatus(){
		$result_data = array();
		$result_data['WAIT_SELLER_SEND_GOODS'] = '待发货';
		$result_data['SELLER_CONSIGNED_PART'] = '部分发货';
		$result_data['WAIT_BUYER_CONFIRM_GOODS'] = '已发货';
		$result_data['TRADE_FINISHED'] = '交易成功';
		$result_data['TRADE_CLOSED'] = '交易关闭';
		$result_data['LOCKED'] = '锁定';
		
		return $result_data;
	}
	
	public function getRefundStatus(){
		$result_data = array();
		$result_data['WAIT_SELLER_AGREE'] = '申请退款';
		$result_data['SUCCESS'] = '退款成功';
		$result_data['CLOSED'] = '退款关闭';
		
		return $result_data;
	}
	
	public function getSellerFlagSrc(){
		$sellerFlag = array(
			'0' => 'images/hui.png',
			'1' => 'images/red.png',
			'2' => 'images/yellow.png',
			'3' => 'images/green.png',
			'4' => 'images/blue.png',
			'5' => 'images/fen.png',
		);
		
		return $sellerFlag;
	}
	
	
	public function menuList(){
		$menu = array(
			'0001' => array(
				'name' => '订单',
				'show' => 'show',
				'width' => '70px',
				'childWidth' => '150px',
				'child' => array(
					'0001_0' => array(
						'name' => '订单处理',
						'show' => 'show',
						'child' => array(
							'0001_017' => array(
								'name' => '订单审核', 
								'url' => '?m=system&c=delivery&a=delivery&sysPlan=approval', 
								'show' => $_SESSION['ORDER_APPROVAL'] == "1" ? 'show' : 'hidden', 
							),
							'0001_018' => array(
								'name' => '打单发货', 
								'url' => '?m=system&c=delivery&a=index&APPROVAL=T', 
								'show' => $_SESSION['ORDER_APPROVAL'] == "1" ? 'show' : 'hidden', 
							),
							'0001_001' => array(
								'name' => '打单发货', 
								'url' => '?m=system&c=delivery&a=index', 
								'show' => $_SESSION['ORDER_APPROVAL'] == "1" ? 'hidden' : 'show', 
							),
							'0001_009' => array(
								'name' => '超期未发订单处理', 
								'url' => '?m=system&c=notShipDeal&a=notShipDeal', 
								'show' => 'show', 
							),
							'0001_010' => array(
								'name' => '有退款挂单', 
								'url' => '?m=WMS&c=theyReturned&a=index', 
								'show' => 'show', 
							),
							'0001_011' => array(
								'name' => '订单导入', 
								'url' => '?m=system&c=orderImport&a=orderImport', 
								'show' => 'show', 
							),
							'0001_012' => array(
								'name' => '自由打印', 
								'url' => '?m=system&c=freePrinting&a=freePrinting', 
								'show' => 'show', 
							),
							'0001_014' => array(
								'name' => '货齐订单管理', 
								'url' => '?m=WMS&c=mobileWare&a=index', 
								'show' => 'show', 
							),
							'0001_015' => array(
								'name' => '快递单号导入', 
								'url' => '?m=system&c=orderImport&a=expressImport', 
								'show' => 'show', 
							),
							'0001_020' => array(
								'name' => '订单身份证信息导入', 
								'url' => '?m=system&c=orderImport&a=cardNoImport', 
								'show' => $_SESSION['CROSS_BORDER'] == "1" ? 'show' : 'hidden', 
							),
						),
					),
					
					
					
					'0001_002' => array(
						'name' => '标签打印', 
						'url' => '?m=system&c=labelPrinting&a=labelPrinting', 
						'show' => 'show', 
					),
					'0001_003' => array(
						'name' => '波次管理', 
						'url' => '?m=WMS&c=waveMana&a=index', 
						'show' => $_SESSION['WMS_MODEL'] == "T" ? 'show' : 'hidden',
					),
					'0001_004' => array(
						'name' => '爆款无标快打', 
						'url' => '?m=system&c=quickStrike&a=quickStrike', 
						'show' => 'show', 
						'class' => 'bottom_border',
					),
					'0001_005' => array(
						'name' => '标签扫描发货', 
						'url' => '?m=system&c=printShip&a=printShip', 
						'show' => 'show', 
					),
					'0001_006' => array(
						'name' => '标签挂单', 
						'url' => '?m=system&c=pendingOrder&a=pendingOrder', 
						'show' => 'show', 
						'class' => 'show',
					),
					'0001_016' => array(
						'name' => '订单扫描发货', 
						'url' => '?m=PT&c=moreDelivery&a=index', 
						'show' => 'show', 
						'class' => 'bottom_border',
					),
					'0001_007' => array(
						'name' => '到货点货', 
						'url' => '?m=system&c=uniqueCheck&a=uniqueCheck', 
						'show' => 'show', 
					),
					'0001_008' => array(
						'name' => '档口到货', 
						'url' => '?m=system&c=cusUniqueCheck&a=index', 
						'show' => 'show', 
					),
					
					
					'0001_013' => array(
						'name' => '档口直接出库', 
						'url' => '?m=PT&c=cusDelivery&a=index', 
						'show' => 'show', 
					),
					
					
					'0001_019' => array(
						'name' => '订单称重',
						'url' => '?m=system&c=orderWeight&a=orderWeight', 
						'show' => 'show', 
					),
				)
			),
			'0002' => array(
				'name' => 'WMS',
				'show' => $_SESSION['WMS_MODEL'] == "T" ? 'show' : 'hidden',
				'width' => '70px',
				'childWidth' => '150px',
				'child' => array(
					'0002_001' => array(
						'name' => '波次策略设置', 
						'url' => '?m=WMS&c=waveTime&a=index', 
						'show' => 'show', 
					),
					'0002_002' => array(
						'name' => '波次管理', 
						'url' => '?m=WMS&c=waveMana&a=index', 
						'show' => 'show', 
						'class' => 'bottom_border',
					),
					'0002_003_000' => array(
						'name' => '采购订单', 
						'url' => '?m=goods&c=otherOut&a=index&act=purchaseOrder', 
						'show' => 'show', 
						'class' => '',
					),
					'0002_003_001' => array(
						'name' => '采购入库', 
						'url' => '?m=goods&c=otherOut&a=index&act=purchase', 
						'show' => 'show', 
						'class' => '',
					),
					'0002_003' => array(
						'name' => '普通入库', 
						'url' => '?m=goods&c=otherOut&a=index', 
						'show' => 'show', 
						'class' => 'bottom_border',
					),
					'0002_004' => array(
						'name' => '爆款入库', 
						'url' => '?m=goods&c=hotLoc&a=index', 
						'show' => 'show', 
						'class' => 'bottom_border',
					),
					'0002_005' => array(
						'name' => '库存详情查看', 
						'url' => '?m=WMS&c=viewSerialNo&a=index', 
						'show' => 'show', 
					),
					/*'0002_006' => array(
						'name' => '待入库区条码管理', 
						'url' => '?m=goods&c=NotOnBarcode&a=index', 
						'show' => 'show', 
					),*/
					'0002_007' => array(
						'name' => '库存唯一码管理', 
						'url' => '?m=goods&c=setBarcode&a=index', 
						'show' => 'show', 
					),
					'0002_008' => array(
						'name' => '库存盘入盘出', 
						'url' => '?m=WMS&c=inOutRefund&a=index', 
						'show' => 'show', 
                        'class' => 'bottom_border',
					),
					'0002_010' => array(
						'name' => '仓库/货位设置', 
						'url' => '?m=WMS&c=viewSerialNo&a=prdtLocManage', 
						'show' => 'show', 
					),
					'0002_011' => array(
						'name' => '货位盘点', 
						'url' => '?m=WMS&c=locInventory&a=index', 
						'show' => 'show', 
					),
					'0002_012' => array(
						'name' => '二次分拣', 
						'url' => '?m=WMS&c=inventory&a=inventory', 
						'show' => 'show', 
                        'class' => 'bottom_border',
					),
                    '0002_013' => array(
						'name' => '唯一码日志', 
						'url' => '?m=report&c=prdtSerialNoLog&a=index', 
						'show' => 'show',
					),
					'0002_014' => array(
						'name' => '质检上架', 
						'url' => '?m=report&c=prdtSerialNoLog&a=userGroup', 
						'show' => 'show',
					),
					'0002_015' => array(
						'name' => '唯一码采购退回', 
						'url' => '?m=WMS&c=BarcodeOutbound&a=index', 
						'show' => 'show',
					),
					'0002_016' => array(
						'name' => '供应商管理', 
						'url' => '?m=system&c=stall_navigation&a=supplier', 
						'show' => 'show',
					),
				)
			),
			'0003' => array(
				'name' => '商品',
				'show' => 'show',
				'width' => '70px',
				'childWidth' => '170px',
				'child' => array(
					'0003_001' => array(
						'name' => '下载货品库存类型设置', 
						'url' => '?m=system&c=setup&a=storage', 
						'show' => $_SESSION['WMS_MODEL'] != "" ? 'show' : 'hidden', 
					),
					'0003_002' => array(
						'name' => '商品下载与绑定关系', 
						'url' => '?m=goods&c=association&a=index', 
						'show' => 'show', 
					),
					'0003_003' => array(
						'name' => '商品信息', 
						'url' => '?m=goods&c=association&a=commodity', 
						'show' => 'show', 
					),
					'0003_004' => array(
						'name' => '套装解析公式', 
						'url' => '?m=goods&c=association&a=groupFormula', 
						'show' => 'show', 
					),
					'0003_005' => array(
						'name' => '商品改码替换', 
						'url' => '?m=afterSale&c=goodOffAdministration&a=goodOnAdministration', 
						'show' => 'show', 
					),
					'0003_006' => array(
						'name' => '已下架货品管理', 
						'url' => '?m=soldOut&c=soldOut&a=index', 
						'show' => 'show', 
					),
					'0003_007' => array(
						'name' => '库存同步日志', 
						'url' => '?m=goods&c=association&a=stocklog', 
						'show' => ($_SESSION['WMS_MODEL'] == "T" || $_SESSION['WMS_MODEL'] == "PT") ? 'show' : 'hidden',
					),
					'0003_008' => array(
						'name' => '线下质检信息导入', 
						'url' => '?m=goods&c=goodsImport&a=goodsImport', 
						'show' => 'show', 
					),
					'0003_012' => array(
						'name' => '货品资料导入', 
						'url' => '?m=goods&c=goodsImport&a=productsImport', 
						'show' => 'show', 
					),
					'0003_009' => array(
						'name' => '预售商品管理', 
						'url' => '?m=goods&c=booking&a=index', 
						'show' => 'show', 
					),
					'0003_010' => array(
						'name' => '分销商品价格策略', 
						'url' => '?m=goods&c=association&a=priceStrategy', 
						'show' => $_SESSION['DROP_SHIPPING_SYNC'] == "T" ? 'show' : 'hidden', 
					),
					'0003_011' => array(
						'name' => '半成品组装公式', 
						'url' => '?m=goods&c=assemble&a=index', 
						'show' => $_SESSION['WMS_MODEL'] == 'PT' ? 'show' : 'hidden', 
					),
					'0003_013' => array(
						'name' => '赠品规则', 
						'url' => '?m=goods&c=giftRule&a=index', 
						'show' => 'show', 
					),
				)
			),
			'0004' => array(
				'name' => '代发',
				'show' => (($_SESSION['BE_DAIFA'] == "T" || $_SESSION['DROP_SHIPPING'] == "T") && $_SESSION['DROP_SHIPPING_SYNC'] != "T") ? 'show' : 'hidden',
				'width' => '70px',
				'childWidth' => '150px',
				'child' => array(
					'0004_001' => array(
						'name' => '代发快递设置', 
						'url' => '?m=system&c=ForBusinessman&a=Index', 
						'show' => $_SESSION['BE_DAIFA'] == "T" ? 'show' : 'hidden',
					),
					'0004_002' => array(
						'name' => '代发订单管理', 
						'url' => '?m=system&c=delivery&a=index&DROP_SHIPPING=T', 
						'show' => $_SESSION['DROP_SHIPPING'] == "T" ? 'show' : 'hidden',
					),
					'0004_0021' => array(
						'name' => '代发订单扫描发货', 
						'url' => '?m=PT&c=moreDelivery&a=index&DROP_SHIPPING_PAGE=T',
						'show' => $_SESSION['DROP_SHIPPING'] == "T" ? 'show' : 'hidden', 
					),
					'0004_003' => array(
						'name' => '代发订单查询', 
						'url' => '?m=system&c=ForOrder&a=Index', 
						'show' => $_SESSION['DROP_SHIPPING'] == "T" ? 'show' : 'hidden',
					),
					'0004_004' => array(
						'name' => '代发客户管理', 
						'url' => '?m=system&c=ForCustomer&a=Index', 
						'show' => $_SESSION['DROP_SHIPPING'] == "T" ? 'show' : 'hidden',
					),
					'0004_005' => array(
						'name' => '代发账目汇总', 
						'url' => '?m=system&c=ForSummary&a=Index', 
						'show' => ($_SESSION['DROP_SHIPPING'] == "T" || $_SESSION['BE_DAIFA'] == "T") ? 'show' : 'hidden',
					),
					'0004_006' => array(
						'name' => '代发账目明细', 
						'url' => '?m=system&c=ForDetail&a=Index', 
						'show' => ($_SESSION['DROP_SHIPPING'] == "T" || $_SESSION['BE_DAIFA'] == "T") ? 'show' : 'hidden',
					),
					'0004_0063' => array(
						'name' => '分销发货对账', 
						'url' => '?m=report&c=sendOrderDetail&a=fenxiaoSum', 
						'show' => $_SESSION['BE_DAIFA'] == "T" ? 'show' : 'hidden',
					),
					'0004_0061' => array(
						'name' => '代发订单发货汇总', 
						'url' => '?m=report&c=sendOrderDetail&a=summaryShipping', 
						'show' => $_SESSION['DROP_SHIPPING'] == "T" ? 'show' : 'hidden',
					),
					'0004_0062' => array(
						'name' => '代发订单发货明细', 
						'url' => '?m=report&c=sendOrderDetail&a=detailSumShipping', 
						'show' => $_SESSION['DROP_SHIPPING'] == "T" ? 'show' : 'hidden',
					),
					'0004_007' => array(
						'name' => '代发商品发货明细', 
						'url' => '?m=report&c=sendOrderDetail&a=detailShipping', 
						'show' => $_SESSION['DROP_SHIPPING'] == "T" ? 'show' : 'hidden',
					),
					'0004_0071' => array(
						'name' => '代发订单快递信息', 
						'url' => '?m=report&c=sendOrderDetail&a=detailOrderShipping', 
						'show' => $_SESSION['DROP_SHIPPING'] == "T" ? 'show' : 'hidden',
					),
					'0004_008' => array(
						'name' => '代拿标签打印', 
						'url' => '?m=system&c=labelPrinting&a=labelPrinting&SYNC_UNIQUE_CODE=T', 
						'show' => $_SESSION['SYNC_UNIQUE_CODE'] == "T" ? 'show' : 'hidden',
					),
				)
			),
			'0014' => array(
				'name' => '分销',
				'show' => $_SESSION['DROP_SHIPPING_SYNC'] == "T" ? 'show' : 'hidden',
				'width' => '70px',
				'childWidth' => '150px',
				'child' => array(
					'0004_001' => array(
						'name' => '分销快递设置', 
						'url' => '?m=system&c=ForBusinessman&a=Index', 
						'show' => 'show',
					),
					'0004_004' => array(
						'name' => '分销客户管理', 
						'url' => '?m=system&c=ForCustomer&a=Index', 
						'show' => 'show',
					),
					'0004_005' => array(
						'name' => '分销账目汇总', 
						'url' => '?m=system&c=ForSummary&a=Index', 
						'show' => 'show'
					),
					'0004_006' => array(
						'name' => '分销账目明细', 
						'url' => '?m=system&c=ForDetail&a=Index', 
						'show' => 'show'
					),
					'0008_011' => array(
						'name' => '分销发货汇总', 
						'url' => '?m=report&c=sendOrderDetail&a=fenxiaoDetail', 
						'show' => $_SESSION['DROP_SHIPPING_SYNC'] == "T" ? 'show' : 'hidden',
					),
					'0008_012' => array(
						'name' => '分销发货对账', 
						'url' => '?m=report&c=sendOrderDetail&a=fenxiaoSum', 
						'show' => $_SESSION['DROP_SHIPPING_SYNC'] == "T" ? 'show' : 'hidden',
					),
					'0004_0071' => array(
						'name' => '分销订单快递信息', 
						'url' => '?m=report&c=sendOrderDetail&a=detailOrderShipping', 
						'show' => $_SESSION['DROP_SHIPPING_SYNC'] == "T" ? 'show' : 'hidden',
					),
				)
			),
			'0013' => array(
				'name' => '代拿',
				'show' => ($_SESSION['useUniqueSync'] == 'on' || $_SESSION['ASSIST_CUST'] == "T") ? 'show' : 'hidden',
				'width' => '70px',
				'childWidth' => '150px',
				'child' => array(
					'0013_001' => array(
						'name' => '代拿商家设置', 
						'url' => '?m=system&c=TakeBusinessman&a=Index', 
						'show' => $_SESSION['ASSIST_CUST'] == "F" ? 'show' : 'hidden',
					),
					'0013_002' => array(
						'name' => '代拿客户管理', 
						'url' => '?m=system&c=TakeCustomer&a=index', 
						'show' => $_SESSION['ASSIST_CUST'] == "T" ? 'show' : 'hidden',
					),
					'0013_003' => array(
						'name' => '代拿波次管理', 
						'url' => '?m=system&c=InsteadRule&a=index', 
						'show' => 'show',
					),
					'0013_004' => array(
						'name' => '代拿打印设置', 
						'url' => '?m=system&c=InsteadAudit&a=index', 
						'show' => $_SESSION['ASSIST_CUST'] == "F" ? 'show' : 'hidden',
					),
					'0013_005' => array(
						'name' => '代拿二次分拣', 
						'url' => '?m=system&c=InsteadSorting&a=index', 
						'show' => $_SESSION['ASSIST_CUST'] == "T" ? 'show' : 'hidden',
					),
					'0013_006' => array(
						'name' => '代拿标签打印', 
						'url' => '?m=system&c=labelPrinting&a=labelPrinting&SYNC_UNIQUE_CODE=T', 
						'show' => $_SESSION['ASSIST_CUST'] == "T" ? 'show' : 'hidden',
					),
				)
			),
			'0005' => array(
				'name' => '进销存',
				'show' => $_SESSION['WMS_MODEL'] == "PT" ? 'show' : 'hidden',
				'width' => '70px',
				'childWidth' => '600px',
				'child' => array(
					'0005_001' => array(
						'name' => '采购信息', 
						'show' => 'show',
						'child' => array(
							'0005_001_001' => array(
								'name' => '采购入库', 
								'url' => '?m=PT&c=purchase&a=index', 
								'show' => 'show',
							),
							'0005_001_002' => array(
								'name' => '采购退回', 
								'url' => '?m=PT&c=purchaseReturn&a=index', 
								'show' => 'show',
							),
							'0005_001_003' => array(
								'name' => '条码采购入库', 
								'url' => '?m=PT&c=BarcodeLibrary&a=index', 
								'show' => 'show',
							),
							'0005_001_004' => array(
								'name' => '条码采购退回', 
								'url' => '?m=PT&c=BarcodeOutbound&a=index&refund=T', 
								'show' => 'show',
							),
						)
					),
					'0005_006' => array(
						'name' => '销售信息', 
						'show' => 'show',
						'child' => array(
							'0005_006_001' => array(
								'name' => '条码销售出库', 
								'url' => '?m=PT&c=BarcodeOutbound&a=index', 
								'show' => 'show',
							),
							'0005_006_002' => array(
								'name' => '条码销售退回', 
								'url' => '?m=PT&c=BarcodeLibrary&a=index&refund=T', 
								'show' => 'show',
							),
						)
					),
					'0005_002' => array(
						'name' => '库存信息', 
						'show' => 'show',
						'child' => array(
							'0005_002_001' => array(
								'name' => '库存盘点', 
								'url' => '?m=PT&c=locInventory&a=index', 
								'show' => 'show',
							),
							'0005_002_002' => array(
								'name' => '库存盘入', 
								'url' => '?m=PT&c=locInventory&a=index&stock=in', 
								'show' => 'show',
							),
							'0005_002_005' => array(
								'name' => '库存盘出', 
								'url' => '?m=PT&c=locInventory&a=index&stock=out', 
								'show' => 'show',
							),
							'0005_002_003' => array(
								'name' => '盘点导入', 
								'url' => '?m=PT&c=locInventory&a=import', 
								'show' => 'show',
							),
							'0005_002_004' => array(
								'name' => '普通入库', 
								'url' => '?m=PT&c=ordinary&a=index', 
								'show' => 'show',
							),
						)
					),
					'0005_003' => array(
						'name' => '基础信息', 
						'show' => 'show',
						'child' => array(
							'0005_003_001' => array(
								'name' => '供应商管理', 
								'url' => '?m=system&c=stall_navigation&a=supplier', 
								'show' => 'show',
							),
							'0005_003_003' => array(
								'name' => '客户管理', 
								'url' => '?m=system&c=customer&a=customer', 
								'show' => 'show',
							),
							'0005_003_002' => array(
								'name' => '仓库/货位设置', 
								'url' => '?m=WMS&c=viewSerialNo&a=prdtLocManage', 
								'show' => 'show',
							),
						)
					),
					'0005_004' => array(
						'name' => '拣货管理', 
						'show' => 'show',
						'child' => array(
							'0005_004_001' => array(
								'name' => '波次策略设置', 
								'url' => '?m=WMS&c=waveTime&a=index', 
								'show' => 'show',
							),
							'0005_004_002' => array(
								'name' => '波次管理', 
								'url' => '?m=WMS&c=waveMana&a=index', 
								'show' => 'show',
							),
							'0005_004_003' => array(
								'name' => '二次分拣', 
								'url' => '?m=PT&c=secondSorting&a=index', 
								'show' => 'show',
							),
							'0005_004_004' => array(
								'name' => '发货+', 
								'url' => '?m=PT&c=saveDelivery&a=index', 
								'show' => 'show',
							),
						)
					),
					'0005_005' => array(
						'name' => '库存报表', 
						'show' => 'show',
						'child' => array(
							'0005_005_001' => array(
								'name' => '库存详情查看', 
								'url' => '?m=WMS&c=viewSerialNo&a=index&type=PT', 
								'show' => 'show',
							),
						)
					),
				)
			),
			'0006' => array(
				'name' => '售后',
				'show' => 'show',
				'width' => '70px',
				'childWidth' => '150px',
				'child' => array(
					'0006_001' => array(
						'name' => '拆包退货', 
						'url' => '?m=afterSale&c=unpacking&a=unpacking', 
						'show' => 'show',
					),
					'0006_002' => array(
						'name' => '售后退货管理', 
						'url' => '?m=afterSale&c=afterSaleManage&a=aftersaleManage', 
						'show' => 'show',
					),
					'0006_007' => array(
						'name' => '退款处理', 
						'url' => '?m=afterSale&c=aftersaleAG&a=index', 
						'show' => 'show',
					),
					'0006_004' => array(
						'name' => '售后单查询', 
						'url' => '?m=afterSale&c=aftersaleSuccess&a=index', 
						'show' => 'show',
					),
					'0006_003' => array(
						'name' => '退货点货', 
						'url' => '?m=system&c=uniqueRefund&a=index', 
						'show' => 'show',
					),
					'0006_006' => array(
						'name' => '售后物流登记', 
						'url' => '?m=afterSale&c=afterReg&a=index', 
						'show' => 'show',
					),
				)
			),
			'0007' => array(
				'name' => '财务',
				'show' => 'show',
				'width' => '70px',
				'childWidth' => '150px',
				'child' => array(
					'0007_001' => array(
						'name' => '流水汇总', 
						'url' => '?m=system&c=finance&a=flowSummary', 
						'show' => 'show',
					),
					'0007_002' => array(
						'name' => '流水明细', 
						'url' => '?m=system&c=finance&a=waterDetail', 
						'show' => 'show',
					),
					'0007_003' => array(
						'name' => '账户充值', 
						'url' => '?m=system&c=finance&a=recharge', 
						'show' => 'show',
					),
					'0007_004' => array(
						'name' => '利润报表', 
						'url' => '?m=financial&c=analysis&a=index', 
						'show' => 'show',
					),
				)
			),
			'0008' => array(
				'name' => '统计分析',
				'show' => 'show',
				'width' => '80px',
				'childWidth' => '150px',
				'child' => array(
					'0008_001' => array(
						'name' => '发货明细', 
						'url' => '?m=report&c=sendOrderDetail&a=index', 
						'show' => 'show',
					),
					'0008_002' => array(
						'name' => '商品发货明细', 
						'url' => '?m=report&c=sendOrderDetail&a=goodsDetail', 
						'show' => 'show',
					),
					'0008_003' => array(
						'name' => '销售分析', 
						'url' => '?m=report&c=salesAnalysis&a=index', 
						'show' => 'show',
					),
					'0008_004' => array(
						'name' => '到缺货报表', 
						'url' => '?m=report&c=outOfStockReport&a=index', 
						'show' => 'show',
					),
					'0008_005' => array(
						'name' => '发货明细表', 
						'url' => '?m=soldOut&c=soldTable&a=index', 
						'show' => 'show',
					),
					'0008_006' => array(
						'name' => '发货汇总表', 
						'url' => '?m=soldOut&c=detailTable&a=index', 
						'show' => 'show',
					),
					'0008_007' => array(
						'name' => '单据历程', 
						'url' => '?m=PT&c=statistics&a=index', 
						'show' => $_SESSION['WMS_MODEL'] != ""  ? 'show' : 'hidden',
					),
					'0008_008' => array(
						'name' => '出入库统计', 
						'url' => '?m=goods&c=InOutWh&a=index', 
						'show' => $_SESSION['WMS_MODEL'] == "T" ? 'show' : 'hidden',
					),
					'0008_009' => array(
						'name' => '打包报表', 
						'url' => '?m=report&c=PackReport&a=ReportOrder', 
						'show' => 'show',
					),
					'0008_010' => array(
						'name' => '现存量明细表', 
						'url' => '?m=goods&c=existing&a=index', 
						'show' => 'show',
					),
					'0008_013' => array(
						'name' => '计划采购建议', 
						'url' => '?m=goods&c=existing&a=proposal', 
						'show' => $_SESSION['WMS_MODEL'] == "T" ? 'show' : 'hidden',
					),
					'0008_014' => array(
						'name' => '库存预警', 
						'url' => '?m=goods&c=existing&a=propostock', 
						'show' => $_SESSION['WMS_MODEL'] == "T" ? 'show' : 'hidden',
					),
					'0008_011' => array(
						'name' => '分销发货汇总', 
						'url' => '?m=report&c=sendOrderDetail&a=fenxiaoDetail', 
						'show' => $_SESSION['DROP_SHIPPING_SYNC'] == "T" ? 'show' : 'hidden',
					),
					'0008_012' => array(
						'name' => '分销发货对账', 
						'url' => '?m=report&c=sendOrderDetail&a=fenxiaoSum', 
						'show' => $_SESSION['DROP_SHIPPING_SYNC'] == "T" ? 'show' : 'hidden',
					),
					'0008_015' => array(
						'name' => '每日订单汇总',
						'url' => '?m=PT&c=TodaySum&a=index', 
						'show' => 'show', 
					),
					'0008_016' => array(
						'name' => '盘亏报表', 
						'url' => '?m=goods&c=InOutWh&a=index&loss=T', 
						'show' => $_SESSION['WMS_MODEL'] == "T" ? 'show' : 'hidden',
					),
				)
			),
			'0009' => array(
				'name' => '设置',
				'show' => 'show',
				'width' => '50px',
				'url' => '?m=system&c=setup&a=index',
			),
			'0015' => array(
				'name' => '短信管理',
				'show' => 'show',
				'width' => '80px',
				'childWidth' => '150px',
				'child' => array(
					'0015_001' => array(
						'name' => '短信设置', 
						'url' => '?m=system&c=message&a=index', 
						'show' => 'show',
					),
					'0015_002' => array(
						'name' => '群发短信', 
						'url' => '?m=SMS&c=massSend&a=index', 
						'show' => 'show',
					),
					'0015_003' => array(
						'name' => '短信充值', 
						'url' => '?m=system&c=message&a=dxadd', 
						'show' => 'show',
					),
					'0015_004' => array(
						'name' => '充值记录', 
						'url' => '?m=system&c=message&a=dxaddlist', 
						'show' => 'show',
					),
				)
			),
			'0010' => array(
				'name' => '待办事项',
				'show' => 'show',
				'width' => '70px',
				'url' => '?m=system&c=beDone&a=beDone',
			),
			'0011' => array(
				'name' => '更多服务',
				'show' => 'show',
				'width' => '70px',
				'url' => '?m=payment&c=buy&a=index',
			),
			'0012' => array(
				'name' => '帮助',
				'show' => 'show',
				'width' => '70px',
				'url' => 'https://zybhelp.jetm3.com',
			),
			
		);
		
		
		return $menu;
	}
	
	public function getUserLimit(){//获取菜单权限
		$model = D();
		$menulimit = array();
		
		$sql = "select permission from userlist where `user_no` = '".$_SESSION['LOGIN_USER_ID']."' ";
		if($row = $model->query($sql)->find()){
			$permission = $row['permission'];
			if($permission == ""){//权限没设置当做全部权限
				return array();
			}else{
				$permissionList = explode(',',$permission);
				foreach($permissionList as $permissionItem){
					$menulimit[$permissionItem] = $permissionItem;
				}
				return $menulimit;
			}
		}else{
			return array();
		}
	}
	
	public function getProvince($province){
		$result = array(
			'陕西' => '陕西省',
			'甘肃' => '甘肃省',
			'青海' => '青海省',
			'宁夏' => '宁夏回族自治区',
			'新疆' => '新疆维吾尔自治区',
			'重庆' => '重庆市',
			'四川' => '四川省',
			'贵州' => '贵州省',
			'云南' => '云南省',
			'西藏' => '西藏自治区',
			'上海' => '上海市',
			'江苏' => '江苏省',
			'浙江' => '浙江省',
			'安徽' => '安徽省',
			'江西' => '江西省',
			'北京' => '北京市',
			'天津' => '天津市',
			'河北' => '河北省',
			'山西' => '山西省',
			'内蒙古' => '内蒙古自治区',
			'山东' => '山东省',
			'福建' => '福建省',
			'广东' => '广东省',
			'广西' => '广西壮族自治区',
			'海南' => '海南省',
			'河南' => '河南省',
			'湖北' => '湖北省',
			'湖南' => '湖南省',
			'辽宁' => '辽宁省',
			'吉林' => '吉林省',
			'黑龙江' => '黑龙江省',
			'台湾' => '台湾省',
			'香港' => '香港特别行政区',
			'澳门' => '澳门特别行政区',
		);
		
		if($province == "returnAll"){
			return $result;
		}else if($result[$province]){
			return $result[$province];
		}else{
			return $province;
		}
	}
	
	public function setOptionHistory($param, $system_id = '')//置操作日志  tid, option_type, option_explain
	{
		$userid = $_SESSION['LOGIN_USER_ID'];
		if($param['option_user'] != ''){
			$userid = $param['option_user'];
		}
		$model = D($system_id);
		$sqlParam = array(
			array('name' => ':tid', 'value' => $param['tid'], 'type' => PDO::PARAM_STR),
			array('name' => ':option_type', 'value' => $param['option_type'], 'type' => PDO::PARAM_STR),
			array('name' => ':option_explain', 'value' => $param['option_explain'], 'type' => PDO::PARAM_STR),
			array('name' => ':userid', 'value' => $userid, 'type' => PDO::PARAM_STR),
		);
		$model->execute("insert into ".TABLE('option_history',$system_id)." (tid, option_type, option_datetime, option_explain, userid) values (:tid, :option_type, ".time().", :option_explain, :userid)",$sqlParam);
	}
	
	/**
	* @author => zn,
	* @time   => 2018-07-26
	* @anno	  => 唯一码日志
	* @param  => serial_no		唯一码
				 action_device	设备 PC,MOBILE
				 action_type	'1'=>'盘入','2'=>'盘出','3'=>'锁定','4'=>'激活','5'=>'捡货','6'=>'发货'
				 rem			备注
	*/
	public function setSerialNoHistory($param, $system_id = '')
	{
		$userid = $_SESSION['LOGIN_USER_ID'];
		if($param['option_user'] != ''){
			$userid = $param['option_user'];
		}
		//ip地址获取
		if(getenv('HTTP_CLIENT_IP')) {
			$action_ip = getenv('HTTP_CLIENT_IP');
		} elseif(getenv('HTTP_X_FORWARDED_FOR')) {
			$action_ip = getenv('HTTP_X_FORWARDED_FOR');
		} elseif(getenv('REMOTE_ADDR')) {
			$action_ip = getenv('REMOTE_ADDR');
		} else {
			$action_ip = $HTTP_SERVER_VARS['REMOTE_ADDR'];
		}
		$model = D($system_id);
		$sqlParam = array(
			array('name' => ':serial_no', 'value' => $param['serial_no'], 'type' => PDO::PARAM_STR),
			array('name' => ':userid', 'value' => $userid, 'type' => PDO::PARAM_STR),
			array('name' => ':action_ip', 'value' => $action_ip, 'type' => PDO::PARAM_STR),
			array('name' => ':action_time', 'value' => time(), 'type' => PDO::PARAM_STR),
			array('name' => ':action_date', 'value' => date('Y-m-d',time()), 'type' => PDO::PARAM_STR),
			array('name' => ':action_device', 'value' => $param['action_device'], 'type' => PDO::PARAM_STR),
			array('name' => ':action_type', 'value' => $param['action_type'], 'type' => PDO::PARAM_STR),
			array('name' => ':rem', 'value' => $param['rem'], 'type' => PDO::PARAM_STR),
		);
		$model->execute("INSERT INTO ".TABLE('prdt_serial_no_log',$system_id)."  (serial_no,userid,action_ip,action_time,action_date,action_device,action_type,rem) values (:serial_no,:userid,:action_ip,:action_time,:action_date,:action_device,:action_type,:rem)",$sqlParam);
	}
	
	public function getPrintLabelData($unprintall){//标签数据模板
		$datas = array();
		$m = 0;
		$model = D();
		$nowDate = date("Y-m-d");
		$shopArray = $this->getShopConfig();//取店铺设置
		$sql = "select print_space from ".TABLE('unique_code_config');
		$config = $model->query($sql)->find();
		$sql = "select configValue from ".TABLE('base_config')." where type='fourCode' and configKey='left'";
		$row = $model->query($sql)->find();
		$fourCodeLeft = $row['configValue'];
		$lastCusNo = '';
		for($j = 0; $j < ceil(count($unprintall) / 100); $j++){
			$data = array();
			for($i = 0; $i < 100; $i++){
				if($unprintall[$m]){
					$row = array();
					if($config['print_space'] == '1')//不同供应商要打印空白页
					{
						if($lastCusNo != $unprintall[$m]['cus_no'] && $lastCusNo != '')
						{
							if(count($data) % 2 == 0)	
							{
								$data[] = $row;
								$data[] = $row;	
							}
							else
							{
								$data[] = $row;	
							}
						}
						$lastCusNo = $unprintall[$m]['cus_no'];
					}
					if($unprintall[$m]['first_print_time'] == 0)
					{
						$model->execute("update ".TABLE('tid_items')." set first_print_time=".time().",last_print_time=".time()." where unique_code='".$unprintall[$m]['unique_code']."'");
						$row['cust_data']['expire_code'] = '';
					}
					else
					{
						$model->execute("update ".TABLE('tid_items')." set last_print_time=".time()." where unique_code='".$unprintall[$m]['unique_code']."'");
						if(date('d',$unprintall[$m]['first_print_time']) != date('d'))
						{
							$row['cust_data']['expire_code'] = '▍';	
						}
						else
						{
							$row['cust_data']['expire_code'] = '';	
						}
					}
					$row['cust_data']['unique_code'] = $unprintall[$m]['unique_code'];
					$row['cust_data']['outer_id'] = $unprintall[$m]['outer_id'];
					
					if($unprintall[$m]['sort_name'] == ""){
						$row['cust_data']['prdt_sort_name'] = $unprintall[$m]['prd_no'];
					}else{
						$row['cust_data']['prdt_sort_name'] = $unprintall[$m]['sort_name'];
					}
					$row['cust_data']['outer_sku_id'] = $unprintall[$m]['outer_sku_id'] ? $unprintall[$m]['outer_sku_id'] : $unprintall[$m]['outer_id'];
					$row['cust_data']['outer_sku_id'] = $row['cust_data']['prdt_sort_name'] ? $row['cust_data']['prdt_sort_name'] : $row['cust_data']['outer_sku_id'];
					$row['cust_data']['sku_outer_id'] = $row['cust_data']['outer_sku_id'];
					$row['cust_data']['four_code'] = mb_substr($row['cust_data']['outer_sku_id'], (int)$fourCodeLeft, 4);
					$row['cust_data']['title'] = $unprintall[$m]['title'];
					$row['cust_data']['sku_name'] = $unprintall[$m]['sku_name'];
					$row['cust_data']['more_code'] = $unprintall[$m]['more_code'];
					$row['cust_data']['wl_sort'] = $unprintall[$m]['express_sort'];
					$row['cust_data']['payment_date'] = date("Y-m-d",$unprintall[$m]['payment_time']);
                    $row['cust_data']['shopname'] = $shopArray[$unprintall[$m]['shopid']]['shopname'];
                    $row['cust_data']['shopsortname'] = $shopArray[$unprintall[$m]['shopid']]['shopsortname'];
					$row['cust_data']['prdt_sum'] = $unprintall[$m]['items_num'];
					$row['cust_data']['print_index'] = $m + 1;
					$row['cust_data']['print_date'] = $nowDate;
					$row['cust_data']['buyer_nick'] = $unprintall[$m]['buyer_nick'];
					$row['cust_data']['shopsortname'] = $unprintall[$m]['shopsortname'];
					$row['cust_data']['seller_memo'] = $unprintall[$m]['seller_memo'];
					$row['cust_data']['buyer_message'] = $unprintall[$m]['buyer_message'];
					$row['cust_data']['prd_loc'] = $unprintall[$m]['prd_loc'];
					$row['cust_data']['receiver_name'] = $unprintall[$m]['receiver_name'];
					$row['cust_data']['prd_no'] = $unprintall[$m]['prd_no'];
					$row['cust_data']['sort_name'] = $unprintall[$m]['sort_name'];
					$row['cust_data']['cost_price'] = str_replace(".00","",$unprintall[$m]['cost_price']);
					$row['cust_data']['old_more_code'] = $unprintall[$m]['old_more_code'];
					$data[] = $row;
					$m = $m + 1;
				}
			}
			$datas[] = $data;
		}
		
		return $datas;
	}
	
	public function getPrintStockLabelData($unprintall){//库存条码模板
		$datas = array();
		$m = 0;
		$model = D();
		$nowDate = date("Y-m-d");
		for($j = 0; $j < ceil(count($unprintall) / 100); $j++){
			$data = array();
			for($i = 0; $i < 100; $i++){
				if($unprintall[$m]){
					$row = array();
					$row['cust_data']['unique_code'] = $unprintall[$m]['unique_code'];
					$row['cust_data']['prd_no'] = $unprintall[$m]['prd_sku_no'] ? $unprintall[$m]['prd_sku_no'] : $unprintall[$m]['prd_no'];
					
					if($unprintall[$m]['sort_name'] == ""){
						$row['cust_data']['prdt_sort_name'] = $row['cust_data']['prd_no'];
					}else{
						$row['cust_data']['prdt_sort_name'] = $unprintall[$m]['sort_name'];
					}
					
					$row['cust_data']['title'] = $unprintall[$m]['title'];
					$row['cust_data']['sku_name'] = $unprintall[$m]['sku_name'];
					$row['cust_data']['prd_loc'] = $unprintall[$m]['prd_loc'];
					$row['cust_data']['print_index'] = $m + 1;
					$row['cust_data']['print_date'] = $nowDate;
					
					$row['cust_data']['cid_name'] = $unprintall[$m]['cid_name'];
					$row['cust_data']['goods_no'] = $unprintall[$m]['goods_no'];
					$row['cust_data']['brand'] = $unprintall[$m]['brand'];
					$row['cust_data']['material'] = $unprintall[$m]['material'];
					$row['cust_data']['material_in'] = $unprintall[$m]['material_in'];
					$row['cust_data']['standard'] = $unprintall[$m]['standard'];
					$row['cust_data']['security'] = $unprintall[$m]['security'];
					$row['cust_data']['retailPrice'] = $unprintall[$m]['retailPrice'];
					$row['cust_data']['company'] = $unprintall[$m]['company'];
					$row['cust_data']['productMan'] = $unprintall[$m]['productMan'];
					$row['cust_data']['productAddress'] = $unprintall[$m]['productAddress'];
					$row['cust_data']['productTel'] = $unprintall[$m]['productTel'];
					
					$data[] = $row;
					$m = $m + 1;
				}
			}
			$datas[] = $data;
		}
		
		return $datas;
	}
	
	public function getPrintYunDanData($unprintall){//运单数据模板
		$datas = array();
		$m = 0;
		$shopArray = $this->getShopConfig();//取店铺设置
		
		for($j = 0; $j < ceil(count($unprintall) / 100); $j++){
			$date = array();
			for($i = 0; $i < 100; $i++){
				$row = array();
				if($unprintall[$m]){
					$row['cust_data']['shopid'] = $unprintall[$m]['shopid'];
                    $row['cust_data']['shopname'] = $shopArray[$unprintall[$m]['shopid']]['shopname'];
                    $row['cust_data']['shopsortname'] = $shopArray[$unprintall[$m]['shopid']]['shopsortname'];
					$row['cust_data']['show_tid'] = $unprintall[$m]['show_tid'];
					$row['cust_data']['express_no'] = $unprintall[$m]['express_no'];
					$row['cust_data']['seller_memo'] = $unprintall[$m]['seller_memo'];
					$row['cust_data']['print_index'] = $m + 1;
					$date[] = $row;
					$m = $m + 1;
				}
			}
			$datas[] = $date;
		}
		
		return $datas;
	}
	
	/*********
	unprintall 订单信息
	unprintall 订单商品信息
	
	**********/
	public function getPrintWaybillData($unprintall, $system_id = ''){//快递单数据模板
		$model = D();
		$datas = array();
		$expressArray = $this->getExpressConfig($system_id);//取快递配置
		$shopArray = $this->getShopConfig($system_id);//取快递配置
		
		$rowDf = $model->query("select configValue from ".TABLE('base_config')." where configKey='address_df' and `type`='wg_daifa_config'")->find();
		$address_df = $rowDf['configValue'];//代发地址
		
		for($j = 0; $j < count($unprintall); $j++){
			$unprintallArr = json_decode($unprintall[$j]['cainiao_json'],true);
			$print_configArr = json_decode($unprintallArr['print_config'],true);
			$express_form = $expressArray[$unprintall[$j]['express_type']]['express_form'];
			
			$row = array();
			if(substr($express_form,0,3) == 'PT_'){
				$row['recipient']['address']['city'] = $unprintall[$j]['receiver_city'];
				$row['recipient']['address']['detail'] = $unprintall[$j]['receiver_address'];
				$row['recipient']['address']['district'] = $unprintall[$j]['receiver_district'];
				$row['recipient']['address']['province'] = $unprintall[$j]['receiver_state'];
				$row['recipient']['address']['town'] = "";
				$row['recipient']['mobile'] = $unprintall[$j]['receiver_mobile'];
				$row['recipient']['name'] = $unprintall[$j]['receiver_name'];
				$row['recipient']['phone'] = $unprintall[$j]['receiver_telephone'];
			}else{
				$row['recipient']['address']['city'] = $print_configArr['data']['recipient']['address']['city'];
				$row['recipient']['address']['detail'] = $print_configArr['data']['recipient']['address']['detail'];
				$row['recipient']['address']['district'] = $print_configArr['data']['recipient']['address']['district'];
				$row['recipient']['address']['province'] = $print_configArr['data']['recipient']['address']['province'];
				$row['recipient']['address']['town'] = "";
				$row['recipient']['mobile'] = $print_configArr['data']['recipient']['mobile'];
				$row['recipient']['name'] = $print_configArr['data']['recipient']['name'];
				$row['recipient']['phone'] = $print_configArr['data']['recipient']['phone'];
			}
			
			$row['routingInfo']['consolidation']['name'] = $print_configArr['data']['routingInfo']['consolidation']['name'];
			$row['routingInfo']['consolidation']['code'] = $print_configArr['data']['routingInfo']['consolidation']['code'];
			$row['routingInfo']['origin']['code'] = $print_configArr['data']['routingInfo']['origin']['code'];
			$row['routingInfo']['origin']['customerCode'] = $print_configArr['data']['routingInfo']['origin']['customerCode'];
			$row['routingInfo']['sortation']['code'] = $print_configArr['data']['routingInfo']['sortation']['code'];
			$row['routingInfo']['sortation']['name'] = $print_configArr['data']['routingInfo']['sortation']['name'];
			$row['routingInfo']['routeCode'] = $print_configArr['data']['routingInfo']['routeCode'];
			$row['routingInfo']['tid'] = $unprintall[$j]['new_tid'];
			
			
			$expressInfo = $expressArray[$unprintall[$j]['express_type']];
			$shopInfo = $shopArray[$unprintall[$j]['shopid']];
			
			if($unprintall[$j]['name_df'] != ""){
				if($unprintall[$j]['order_type'] == "WEIGONG"){
					$row['sender']['address']['detail'] = $address_df;
				}else{
					$row['sender']['address']['detail'] = $unprintall[$j]['address_df'];	
				}
				$row['sender']['address']['city'] = "";
				$row['sender']['address']['district'] = "";
				$row['sender']['address']['province'] = "";
				$row['sender']['mobile'] = $unprintall[$j]['mobile_df'];
				$row['sender']['name'] = $unprintall[$j]['name_df'];
				$row['sender']['phone'] = "";
			}else if($shopInfo['shop_print_detail'] != ''){
				$row['sender']['address']['city'] = $shopInfo['shop_print_city'];
				$row['sender']['address']['detail'] = $shopInfo['shop_print_detail'];
				$row['sender']['address']['district'] = $shopInfo['shop_print_district'];
				$row['sender']['address']['province'] = $shopInfo['shop_print_province'];
				$row['sender']['mobile'] = $shopInfo['shop_print_tel'];
				$row['sender']['name'] = $shopInfo['shop_print_username'];
				$row['sender']['phone'] = $shopInfo['shop_print_tel'];
			}else if($expressInfo['print_detail'] != ''){
				$row['sender']['address']['city'] = $expressInfo['print_city'];
				$row['sender']['address']['detail'] = $expressInfo['print_detail'];
				$row['sender']['address']['district'] = $expressInfo['print_district'];
				$row['sender']['address']['province'] = $expressInfo['print_province'];
				$row['sender']['mobile'] = $expressInfo['send_tel'];
				$row['sender']['name'] = $expressInfo['send_username'];
				$row['sender']['phone'] = $expressInfo['send_tel'];
			}else{
				$row['sender']['address']['city'] = $print_configArr['data']['sender']['address']['city'];
				$row['sender']['address']['detail'] = $print_configArr['data']['sender']['address']['detail'];
				$row['sender']['address']['district'] = $print_configArr['data']['sender']['address']['district'];
				$row['sender']['address']['province'] = $print_configArr['data']['sender']['address']['province'];
				$row['sender']['mobile'] = $print_configArr['data']['sender']['mobile'];
				$row['sender']['name'] = $print_configArr['data']['sender']['name'];
				$row['sender']['phone'] = $print_configArr['data']['sender']['phone'];
			}
			
			$row['sender']['address']['town'] = "";
			
			if($print_configArr['data']['shippingOption']['services']['SVC-COD']['value'] == ""){
				$print_configArr['data']['shippingOption']['services']['SVC-COD']['value'] = 0;
			}
			
			$row['shippingOption']['code'] = $print_configArr['data']['shippingOption']['code'];
			$row['shippingOption']['services']['SVC-COD']['value'] = $print_configArr['data']['shippingOption']['services']['SVC-COD']['value'];
			$row['shippingOption']['services']['TIMED-DELIVERY']['value'] = $print_configArr['data']['shippingOption']['services']['TIMED-DELIVERY']['value'];
			$row['shippingOption']['services']['TIMED-DELIVERY']['text'] = $print_configArr['data']['shippingOption']['services']['TIMED-DELIVERY']['text'];
			$row['shippingOption']['title'] = $print_configArr['data']['shippingOption']['title'];
			
			if(substr($express_form,0,3) == 'PT_'){
				$row['waybillCode'] = $unprintall[$j]['express_no'];
			}else{
				$row['waybillCode'] = $print_configArr['data']['waybillCode'];
			}
			$row['buyer_message'] = $unprintall[$j]['buyer_message'];
			$row['seller_memo'] = $unprintall[$j]['seller_memo'];
			$row['more_code'] = $unprintall[$j]['more_code'];
			$row['rem'] = $unprintall[$j]['rem'];
			
			$datas[] = $row;
		}

		return $datas;
	}
	
	public function getPrintCQLabelData($unique_codes){//质检标签模板
		$model = D();
		
		$unique_codes = str_replace(",","','",$unique_codes);
		
		$datas = array();
		$sql = "select a.tid,a.unique_code,a.prd_no,a.sku_name,
				(case when d.shoptype = 'XX' then e.cid_name else c.cid_name end) as cid_name,
				(case when d.shoptype = 'XX' then e.goods_no else c.goods_no end) as goods_no,
				(case when d.shoptype = 'XX' then e.brand else c.brand end) as brand,
				(case when d.shoptype = 'XX' then e.material else c.material end) as material,
				(case when d.shoptype = 'XX' then e.material_in else c.material_in end) as material_in,
				(case when d.shoptype = 'XX' then e.standard else c.standard end) as standard,
				(case when d.shoptype = 'XX' then e.security else c.security end) as security,
				(case when d.shoptype = 'XX' then e.retailPrice else c.retailPrice end) as retailPrice,
				d.zj_company,d.zj_productMan,d.zj_productAddress,d.zj_productTel from ".TABLE('tid_items')." a 
				left join (select shopid,tid from ".TABLE('tid_orders')." ) b on a.tid=b.tid
				left join (select shopid,num_iid,cid_name,goods_no,brand,material,material_in,standard,security,retailPrice from  ".TABLE('product_online')." ) c on b.shopid=c.shopid and a.num_iid = c.num_iid
				left join (select shopid,prd_id,cid_name,goods_no,brand,material,material_in,standard,security,retailPrice from  ".TABLE('product_online')." where prd_id <> '' ) e on b.shopid=e.shopid and a.prd_id = e.prd_id
				left join (select shoptype,shopid,zj_company,zj_productMan,zj_productAddress,zj_productTel from ".TABLE('shop_config')." ) d on b.shopid=d.shopid
				where a.unique_code in ('".$unique_codes."')
		";
		if($unprintalls = $model->query($sql)->select()){
			foreach($unprintalls as $unprintall){
				$row = array();
				$row['cust_data']['unique_code'] = $unprintall['unique_code'];//唯一码
				$row['cust_data']['goods_no'] = $unprintall['goods_no'];//货号
				$row['cust_data']['prd_no'] = $unprintall['prd_no'];//商品编码
				$row['cust_data']['sku_name'] = $unprintall['sku_name'];//销售属性
				$row['cust_data']['cid_name'] = $unprintall['cid_name'];//品名
				$row['cust_data']['brand'] = $unprintall['brand'];//品牌
				$row['cust_data']['material'] = $unprintall['material'];//材质
				$row['cust_data']['material_in'] = $unprintall['material_in'];//内里材质
				$row['cust_data']['standard'] = $unprintall['standard'];//执行标准
				$row['cust_data']['security'] = $unprintall['security'];//安全级别
				$row['cust_data']['company'] = $unprintall['zj_company'];//质检-企业名称
				$row['cust_data']['productMan'] = $unprintall['zj_productMan'];//质检-生产厂家
				$row['cust_data']['productAddress'] = $unprintall['zj_productAddress'];//质检-生产地址
				$row['cust_data']['productTel'] = $unprintall['zj_productTel'];//质检-厂家电话
				$row['cust_data']['retailPrice'] = $unprintall['retailPrice'];//零售价
				
				$paramHis = array();
				$paramHis['tid'] = $unprintall['tid'];
				$paramHis['option_type'] = '打质检标签';
				$paramHis['option_explain'] = $unprintall['unique_code'];
				$this->setOptionHistory($paramHis);
						
				$datas[] = $row;
			}
		}
		
		return $datas;
	}
	
	public function costAccounting($prd_id, $prd_sku_id = '', $prd_no, $storage, $qty, $up_cst = 0, $sprd_time = 0){//成本核算
		if($_SESSION['WMS_MODEL'] != "PT" && $_SESSION['WMS_MODEL'] != "T"){
			return;
		}
		
		$model = D();
		
		$now_time = time();
		$sprd_time = $sprd_time == 0 ? strtotime(date('Y-m-d')) : $sprd_time;
		$sprd_date = date('Y-m-d', $sprd_time);
		
		$sqlParam = array(
			array('name' => ':now_time', 'value' => $now_time, 'type' => PDO::PARAM_STR),
			array('name' => ':sprd_time', 'value' => $sprd_time, 'type' => PDO::PARAM_STR),
			array('name' => ':sprd_date', 'value' => $sprd_date, 'type' => PDO::PARAM_STR),
			array('name' => ':prd_id', 'value' => $prd_id, 'type' => PDO::PARAM_STR),
			array('name' => ':prd_sku_id', 'value' => $prd_sku_id, 'type' => PDO::PARAM_STR),
			array('name' => ':prd_no', 'value' => $prd_no, 'type' => PDO::PARAM_STR),
			array('name' => ':storage', 'value' => $storage, 'type' => PDO::PARAM_STR),
			array('name' => ':qty', 'value' => abs($qty), 'type' => PDO::PARAM_STR),
			array('name' => ':up_cst', 'value' => $up_cst, 'type' => PDO::PARAM_STR),
		);
		
		$sql = "select qty_end,cst_end,up_cst from ".TABLE('sprd')." where storage = :storage and prd_id = :prd_id and prd_sku_id = :prd_sku_id and sprd_time = :sprd_time ";
		if($row = $model->query($sql, $sqlParam)->find()){
			if($qty > 0){
				if($now_time > $sprd_time){
					$model->execute("update ".TABLE('sprd')." set 
									 qty_begin = qty_begin + :qty, 
								     qty_in = qty_in + :qty, 
								     qty_end = qty_end + :qty,
									 cst_begin = cst_begin + (:qty * :up_cst),
								     cst_end = cst_end + (:qty * :up_cst),
								     up_cst = (cst_end + (:qty * :up_cst)) / (qty_begin + qty_in - qty_out)
								     where storage = :storage and prd_id = :prd_id and prd_sku_id = :prd_sku_id and sprd_time <= :now_time ",$sqlParam);	
				}else if($now_time == $sprd_time){
					$model->execute("update ".TABLE('sprd')." set 
								     qty_in = qty_in + :qty, 
								     qty_end = qty_end + :qty,
								     cst_end = cst_end + (:qty * :up_cst),
								     up_cst = (cst_end + (:qty * :up_cst)) / (qty_begin + qty_in - qty_out)
								     where storage = :storage and prd_id = :prd_id and prd_sku_id = :prd_sku_id and sprd_time = :now_time ",$sqlParam);	
				}
			}else if($qty < 0){
				if($now_time > $sprd_time){
					$model->execute("update ".TABLE('sprd')." set 
									 qty_begin = qty_begin - :qty, 
								     qty_out = qty_out + :qty, 
								     qty_end = qty_end - :qty,
									 cst_begin = cst_begin - (:qty * up_cst),
								     cst_end = cst_end - (:qty * up_cst)
								     where storage = :storage and prd_id = :prd_id and prd_sku_id = :prd_sku_id and sprd_time <= :now_time ",$sqlParam);	
				}else if($now_time == $sprd_time){
					$model->execute("update ".TABLE('sprd')." set 
								     qty_out = qty_out + :qty, 
								     qty_end = qty_end - :qty,
								     cst_end = cst_end - (:qty * up_cst)
								     where storage = :storage and prd_id = :prd_id and prd_sku_id = :prd_sku_id and sprd_time = :now_time ",$sqlParam);	
				}
			}
		}else{
			$qty_begin = 0;
			$cst_begin = 0;
			$sql = "select qty_end,cst_end,up_cst from ".TABLE('sprd')." where storage = :storage and prd_id = :prd_id and prd_sku_id = :prd_sku_id and sprd_time < :sprd_time order by sprd_time desc limit 1 ";
			if($row = $model->query($sql, $sqlParam)->find()){
				$qty_begin = $row['qty_end'];
				$cst_begin = $row['cst_end'];
				if($qty < 0){
					$up_cst = $row['up_cst'];
				}
			}
			
			$qty_end = $qty_end + $qty;
			$cst_end = $cst_begin + ($qty * $up_cst);
			
			$sqlParam = array(
				array('name' => ':now_time', 'value' => $now_time, 'type' => PDO::PARAM_STR),
				array('name' => ':sprd_time', 'value' => $sprd_time, 'type' => PDO::PARAM_STR),
				array('name' => ':sprd_date', 'value' => $sprd_date, 'type' => PDO::PARAM_STR),
				array('name' => ':prd_id', 'value' => $prd_id, 'type' => PDO::PARAM_STR),
				array('name' => ':prd_sku_id', 'value' => $prd_sku_id, 'type' => PDO::PARAM_STR),
				array('name' => ':prd_no', 'value' => $prd_no, 'type' => PDO::PARAM_STR),
				array('name' => ':storage', 'value' => $storage, 'type' => PDO::PARAM_STR),
				array('name' => ':qty', 'value' => abs($qty), 'type' => PDO::PARAM_STR),
				array('name' => ':up_cst', 'value' => $up_cst, 'type' => PDO::PARAM_STR),
				array('name' => ':qty_begin', 'value' => $qty_begin, 'type' => PDO::PARAM_STR),
				array('name' => ':qty_end', 'value' => $qty_end, 'type' => PDO::PARAM_STR),
				array('name' => ':cst_begin', 'value' => $cst_begin, 'type' => PDO::PARAM_STR),
				array('name' => ':cst_end', 'value' => $cst_end, 'type' => PDO::PARAM_STR),
			);
			
			if($qty > 0){
				$model->execute("insert into ".TABLE('sprd')."(sprd_date,sprd_time,storage,prd_id,prd_sku_id,prd_no,qty_begin,qty_in,qty_end,cst_begin,cst_end,up_cst)
								 values(:sprd_date,:sprd_time,:storage,:prd_id,:prd_sku_id,:prd_no,:qty_begin,:qty,:qty_end,:cst_begin,:cst_end,:up_cst) ",$sqlParam);
			}else if($qty < 0){
				$model->execute("insert into ".TABLE('sprd')."(sprd_date,sprd_time,storage,prd_id,prd_sku_id,prd_no,qty_begin,qty_out,qty_end,cst_begin,cst_end,up_cst)
								 values(:sprd_date,:sprd_time,:storage,:prd_id,:prd_sku_id,:prd_no,:qty_begin,:qty,:qty_end,:cst_begin,:cst_end,:up_cst) ",$sqlParam);
			}
		}
	}
	
	public function TplusStockUploadCreate(){//T+库存同步任务创建
		if($_SESSION['ISAUTOUPLOAD'] == "true"){
			$model = D();
		
			$sql = "select appkey,secretkey,sessionkey,server_url,down_time from ".TABLE('shop_config')." where shoptype='TPLUS' and `status` = 1 ";
			if($row = $model->query($sql)->find()){
				
			}else{
				return array('code' => 'ok');
			}
			
			# 配置参数
			$apioptions = array(
				'API_HOST' => $row['server_url'],// API主机信息
				'account' => array( // 账套账号配置 <account模式下必须>
					'id' => $row['appkey'], // 账套账号ID <account模式下必须>
					'password' => $row['secretkey'], // 账套账号密码 <account模式下必须>
					'number' => $row['sessionkey'], // 账套编号 <account模式下必须>
				),
			);

			$apioptions = json_encode($apioptions);

			$apiField = array(
				'TimeBegin', // 外部系统单据编码
				'TimeEnd' // 存货名称
			);

			$apiFieldStr = implode(',',$apiField);
			$apiField = json_encode($apiField);
			
			$apiParam = array(
				'queryParam' => array(
					'TimeBegin' => date('Y-m-d H:i:s',$row['down_time']),
					'TimeEnd' => date('Y-m-d H:i:s'),
					'SelectFields' => $apiFieldStr,
				)
			);
			$apiParam = json_encode($apiParam);

			$apiMethod = '/currentStock/QueryByTime';

			$postFields = array(
				'apioptions' => $apioptions,
				'apiField' => $apiField,
				'apiParamFrom' => $apiParam,
				'apiMethod' => $apiMethod,
			);

			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, "http://192.168.1.10:8080/server.php");
			curl_setopt($ch, CURLOPT_FAILONERROR, false);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
			$reponse = curl_exec($ch);
			curl_close($ch);
			$result = json_decode($reponse,true);
			if(is_array($result)){
				if(!$result['RestException']){
					$prd_id_list = array();
					$prd_sku_id_list = array();
					$prd_stock_list = array();
					foreach($result as $list){
						$prd_no = $list['InventoryCode'];
						$AvailableQuantity = $list['AvailableQuantity'];
						
						$sqlP = "select prd_id,prd_sku_id from ".TABLE('product_sku')." where prd_sku_no = :prd_no 
								 union
								 select prd_id,'' as prd_sku_id from ".TABLE('product')." where prd_no = :prd_no 
								 ";
						if($rowP = $model->query($sqlP,array(
							array('name' => ':prd_no', 'value' => $prd_no, 'type' => PDO::PARAM_STR),
						))->find()){
							if($rowP['prd_sku_id'] == '' && $rowP['prd_id'] != ''){
								$prd_id_list[] = $rowP['prd_id'];
								$prd_stock_list[$rowP['prd_id']][''] = $AvailableQuantity;
							}else if($rowP['prd_sku_id'] != ''){
								$prd_sku_id_list[] = $rowP['prd_sku_id'];
								$prd_stock_list[$rowP['prd_id']][$rowP['prd_sku_id']] = $AvailableQuantity;
							}
						}
					}
					
					if(count($prd_id_list) > 0){
						$this->stockUploadCreate(implode(',',$prd_id_list), '', '', '', $prd_stock_list);
					}
					
					if(count($prd_sku_id_list) > 0){
						$this->stockUploadCreate('', implode(',',$prd_sku_id_list), '', '', $prd_stock_list);
					}
				}
			}
		}
	}
	
	public function stockUploadCreate($prd_id = '', $prd_sku_id = '', $tid = '', $sql_prd = '', $prd_stock_list = array()){//库存同步任务创建
		if($_SESSION['ISAUTOUPLOAD'] == "true"){
			$model = D();
			$model_SYS = D_SYS();
			$system_id = $_SESSION['LOGIN_SYSTEM_ID'];
			$action_time = time();//任务创建时间
			
			$tokenArray = array();//token 
			$sqlToken = "select shopid,session_key from ".TABLE('session_token')." where session_key != '' ";
			if($rowToken = $model->query($sqlToken)->select()){
				foreach($rowToken as $listToken){
					$tokenArray[$listToken['shopid']] = $listToken['session_key'];
				}
			}
			
			$stockArray = array();//库存数量
			
			if($prd_sku_id != ""){
				$PRD_SKU_IDS = str_replace(",","','",$prd_sku_id);
				
				if(count($prd_stock_list) > 0){
					$stockArray = $prd_stock_list;
				}else{
					$sqlStock = "select prd_id,prd_sku_id,sum(qty) as qty from ".TABLE('prdt_wms_qty')."
								 where prd_sku_id in ('".$PRD_SKU_IDS."') group by prd_id,prd_sku_id";
					if($rowStock = $model->query($sqlStock)->select()){
						foreach($rowStock as $listStock){
							$stockArray[$listStock['prd_id']][$listStock['prd_sku_id']] = $listStock['qty'];
						}
					}
				}
				
				$sql = "select b.shoptype,a.shopid,a.num_iid,a.sku_id,a.outer_iid,a.outer_sku_id,b.appkey,b.secretkey,b.sessionkey,b.server_url,
						a.prd_id,a.prd_sku_id,b.upload_type,b.upload_ratio,b.upload_beyond,c.prd_sku_no as prd_no from ".TABLE('product_online_sku')." a 
						inner join (select shoptype,shopid,appkey,secretkey,sessionkey,server_url,upload_type,upload_ratio,upload_beyond from ".TABLE('shop_config')." ) b on a.shopid = b.shopid 
						inner join (select prd_id,prd_sku_id,prd_sku_no from ".TABLE('product_sku')." ) c on a.prd_id = c.prd_id and a.prd_sku_id = c.prd_sku_id 
						inner join (select prd_id,upload_stock from ".TABLE('product')." ) d on a.prd_id = d.prd_id 
						where b.shoptype in ('TB','PDD','ALBB','CQSHOP') and a.prd_sku_id in ('".$PRD_SKU_IDS."') and d.upload_stock = 0 ";
			}else if($prd_id != ""){
				$PRD_IDS = str_replace(",","','",$prd_id);
				
				if(count($prd_stock_list) > 0){
					$stockArray = $prd_stock_list;
				}else{
					$sqlStock = "select prd_id,prd_sku_id,sum(qty) as qty from ".TABLE('prdt_wms_qty')."
								 where prd_id in ('".$PRD_IDS."') group by prd_id,prd_sku_id";
					if($rowStock = $model->query($sqlStock)->select()){
						foreach($rowStock as $listStock){
							$stockArray[$listStock['prd_id']][$listStock['prd_sku_id']] = $listStock['qty'];
						}
					}
				}
				
				$sql = "select b.shoptype,a.shopid,a.num_iid,a.sku_id,a.outer_iid,a.outer_sku_id,b.appkey,b.secretkey,b.sessionkey,b.server_url,
						a.prd_id,a.prd_sku_id,b.upload_type,b.upload_ratio,b.upload_beyond,c.prd_sku_no as prd_no from ".TABLE('product_online_sku')." a 
						inner join (select shoptype,shopid,appkey,secretkey,sessionkey,server_url,upload_type,upload_ratio,upload_beyond from ".TABLE('shop_config')." ) b on a.shopid = b.shopid 
						inner join (select prd_id,prd_sku_id,prd_sku_no from ".TABLE('product_sku')." ) c on a.prd_id = c.prd_id and a.prd_sku_id = c.prd_sku_id 
						inner join (select prd_id,upload_stock from ".TABLE('product')." ) d on a.prd_id = d.prd_id 
						where b.shoptype in ('TB','PDD','ALBB','CQSHOP') and a.prd_id in ('".$PRD_IDS."') and d.upload_stock = 0 
						union
						select b.shoptype,a.shopid,a.num_iid,'' as sku_id,a.outer_iid, '' as outer_sku_id,b.appkey,b.secretkey,b.sessionkey,b.server_url,
						a.prd_id,'' as prd_sku_id,b.upload_type,b.upload_ratio,b.upload_beyond,c.prd_no from ".TABLE('product_online')." a 
						inner join (select shoptype,shopid,appkey,secretkey,sessionkey,server_url,upload_type,upload_ratio,upload_beyond from ".TABLE('shop_config')." ) b on a.shopid = b.shopid 
						inner join (select prd_id,prd_no,upload_stock from ".TABLE('product')." ) c on a.prd_id = c.prd_id 
						where b.shoptype in ('TB','PDD','ALBB','CQSHOP') and a.prd_id in ('".$PRD_IDS."') and c.upload_stock = 0 
						";
			}else if($tid != ""){
				$PRD_TIDS = str_replace(",","','",$tid);
				$sqlStock = "select a.prd_id,a.prd_sku_id,sum(a.qty) as qty from ".TABLE('prdt_wms_qty')." a
							 inner join (
								select aa.prd_id,aa.prd_sku_id from ".TABLE('tid_items')." aa
								inner join (select new_tid,tid from ".TABLE('tid_orders')." ) bb on aa.tid = bb.tid 
								where bb.new_tid in ('".$PRD_TIDS."') and aa.refund_status != 'SUCCESS'
							 ) b on a.prd_id = b.prd_id and a.prd_sku_id = b.prd_sku_id 
							 group by a.prd_id,a.prd_sku_id";
				if($rowStock = $model->query($sqlStock)->select()){
					foreach($rowStock as $listStock){
						$stockArray[$listStock['prd_id']][$listStock['prd_sku_id']] = $listStock['qty'];
					}
				}
				
				$sql = "select b.shoptype,a.shopid,a.num_iid,a.sku_id,a.outer_iid,a.outer_sku_id,b.appkey,b.secretkey,b.sessionkey,b.server_url,
						a.prd_id,a.prd_sku_id,b.upload_type,b.upload_ratio,b.upload_beyond,c.prd_sku_no as prd_no from ".TABLE('product_online_sku')." a 
						inner join (select shoptype,shopid,appkey,secretkey,sessionkey,server_url,upload_type,upload_ratio,upload_beyond from ".TABLE('shop_config')." ) b on a.shopid = b.shopid 
						inner join (select prd_id,prd_sku_id,prd_sku_no from ".TABLE('product_sku')." ) c on a.prd_id = c.prd_id and a.prd_sku_id = c.prd_sku_id 
						inner join (
								select aa.prd_id,aa.prd_sku_id from ".TABLE('tid_items')." aa
								inner join (select new_tid,tid from ".TABLE('tid_orders')." ) bb on aa.tid = bb.tid 
								where bb.new_tid in ('".$PRD_TIDS."') and aa.refund_status != 'SUCCESS'
							 ) d on a.prd_id = d.prd_id and a.prd_sku_id = d.prd_sku_id 
						inner join (select prd_id,upload_stock from ".TABLE('product')." ) e on a.prd_id = e.prd_id 
						where b.shoptype in ('TB','PDD','ALBB','CQSHOP')  and d.prd_sku_id != '' and e.upload_stock = 0 
						union 
						select b.shoptype,a.shopid,a.num_iid, '' as sku_id,a.outer_iid, '' as outer_sku_id,b.appkey,b.secretkey,b.sessionkey,b.server_url,
						a.prd_id,'' as prd_sku_id,b.upload_type,b.upload_ratio,b.upload_beyond,c.prd_no from ".TABLE('product_online')." a 
						inner join (select shoptype,shopid,appkey,secretkey,sessionkey,server_url,upload_type,upload_ratio,upload_beyond from ".TABLE('shop_config')." ) b on a.shopid = b.shopid 
						inner join (select prd_id,prd_no,upload_stock from ".TABLE('product')." ) c on a.prd_id = c.prd_id 
						inner join (
								select aa.prd_id,aa.prd_sku_id from ".TABLE('tid_items')." aa
								inner join (select new_tid,tid from ".TABLE('tid_orders').") bb on aa.tid = bb.tid 
								where bb.new_tid in ('".$PRD_TIDS."') and aa.refund_status != 'SUCCESS'
							 ) d on a.prd_id = d.prd_id
						where b.shoptype in ('TB','PDD','ALBB','CQSHOP')  and d.prd_sku_id = '' and c.upload_stock = 0  ";
			}else if($sql_prd != ""){
				$sqlStock = "select a.prd_id,a.prd_sku_id,sum(a.qty) as qty from ".TABLE('prdt_wms_qty')." a
							 inner join (".$sql_prd.") b on a.prd_id = b.prd_id and a.prd_sku_id = b.prd_sku_id 
							 group by a.prd_id,a.prd_sku_id";
				if($rowStock = $model->query($sqlStock)->select()){
					foreach($rowStock as $listStock){
						$stockArray[$listStock['prd_id']][$listStock['prd_sku_id']] = $listStock['qty'];
					}
				}
				
				$sql = "select b.shoptype,a.shopid,a.num_iid,a.sku_id,a.outer_iid,a.outer_sku_id,b.appkey,b.secretkey,b.sessionkey,b.server_url,
						a.prd_id,a.prd_sku_id,b.upload_type,b.upload_ratio,b.upload_beyond,c.prd_sku_no as prd_no from ".TABLE('product_online_sku')." a 
						inner join (select shoptype,shopid,appkey,secretkey,sessionkey,server_url,upload_type,upload_ratio,upload_beyond from ".TABLE('shop_config')." ) b on a.shopid = b.shopid 
						inner join (select prd_id,prd_sku_id,prd_sku_no from ".TABLE('product_sku')." ) c on a.prd_id = c.prd_id and a.prd_sku_id = c.prd_sku_id 
						inner join (".$sql_prd.") d on a.prd_id = d.prd_id and a.prd_sku_id = d.prd_sku_id 
						inner join (select prd_id,prd_no,upload_stock from ".TABLE('product')." ) e on a.prd_id = e.prd_id 
						where b.shoptype in ('TB','PDD','ALBB','CQSHOP') and e.upload_stock = 0 
						union
						select b.shoptype,a.shopid,a.num_iid,'' as sku_id,a.outer_iid,'' as outer_sku_id,b.appkey,b.secretkey,b.sessionkey,b.server_url,
						a.prd_id,'' as prd_sku_id,b.upload_type,b.upload_ratio,b.upload_beyond,c.prd_no from ".TABLE('product_online')." a 
						inner join (select shoptype,shopid,appkey,secretkey,sessionkey,server_url,upload_type,upload_ratio,upload_beyond from ".TABLE('shop_config')." ) b on a.shopid = b.shopid 
						inner join (select prd_id,prd_no,upload_stock from ".TABLE('product')." ) c on a.prd_id = c.prd_id 
						inner join (".$sql_prd.") d on a.prd_id = d.prd_id 
						where b.shoptype in ('TB','PDD','ALBB','CQSHOP') and d.prd_sku_id = '' and c.upload_stock = 0  ";
			}
			
			if($sql == ""){
				return false;
			}
			
			if($rows = $model->query($sql)->select()){
				foreach($rows as $list){
					$qty_now = $stockArray[$list['prd_id']][$list['prd_sku_id']];
					if($tokenArray[$list['shopid']]){
						$list['sessionkey'] = $tokenArray[$list['shopid']];
					}
					$sql = "SELECT nimble_nums FROM ".TABLE('product_sku')." WHERE prd_id='".$list['prd_id']."' AND prd_sku_id='".$list['prd_sku_id']."'";
					$nimble = $model->query($sql)->find();
					if($list['upload_type'] == "2"){//可用库存
						$sqlLock = "select count(1) as num_lock from ".TABLE('tid_items')." a 
									inner join (select tid,send_status,exception_online from ".TABLE('tid_orders')." ) b on a.tid = b.tid 
									where b.send_status in ('WAIT_ASSIGN','WAIT_FINISH_ASSIGN','WAIT_SENDED_ASSIGN') and  b.exception_online = '0'
									and a.prd_id = '".$list['prd_id']."' and a.prd_sku_id = '".$list['prd_sku_id']."' and a.send_time = 0 ";
						$rowLock = $model->query($sqlLock)->find();
						$num_lock = $rowLock['num_lock'];//锁定库存
						if($nimble['nimble_nums']){
							$qty = floor((($qty_now - $num_lock) * $list['upload_ratio'] / 100) + $nimble['nimble_nums']);
						}else{
							$qty = floor((($qty_now - $num_lock) * $list['upload_ratio'] / 100) + $list['upload_beyond']);
						}	
					}else if($list['upload_type'] == "1"){//实际库存
						if($nimble['nimble_nums']){
							$qty = floor(($qty_now * $list['upload_ratio'] / 100) + $nimble['nimble_nums']);
						}else{
							$qty = floor(($qty_now * $list['upload_ratio'] / 100) + $list['upload_beyond']);
						}
					}
					
					$sqlParam = array(
						array('name' => ':system_id', 'value' => $system_id, 'type' => PDO::PARAM_STR),
						array('name' => ':action_time', 'value' => $action_time, 'type' => PDO::PARAM_STR),
						array('name' => ':shoptype', 'value' => $list['shoptype'], 'type' => PDO::PARAM_STR),
						array('name' => ':shopid', 'value' => $list['shopid'], 'type' => PDO::PARAM_STR),
						array('name' => ':appkey', 'value' => $list['appkey'], 'type' => PDO::PARAM_STR),
						array('name' => ':secretkey', 'value' => $list['secretkey'], 'type' => PDO::PARAM_STR),
						array('name' => ':sessionkey', 'value' => $list['sessionkey'], 'type' => PDO::PARAM_STR),
						array('name' => ':server_url', 'value' => $list['server_url'], 'type' => PDO::PARAM_STR),
						array('name' => ':num_iid', 'value' => $list['num_iid'], 'type' => PDO::PARAM_STR),
						array('name' => ':sku_id', 'value' => $list['sku_id'], 'type' => PDO::PARAM_STR),
						array('name' => ':outer_iid', 'value' => $list['outer_iid'], 'type' => PDO::PARAM_STR),
						array('name' => ':outer_sku_id', 'value' => $list['outer_sku_id'], 'type' => PDO::PARAM_STR),
						array('name' => ':prd_id', 'value' => $list['prd_id'], 'type' => PDO::PARAM_STR),
						array('name' => ':prd_sku_id', 'value' => $list['prd_sku_id'], 'type' => PDO::PARAM_STR),
						array('name' => ':prd_no', 'value' => $list['prd_no'], 'type' => PDO::PARAM_STR),
						array('name' => ':upload_type', 'value' => $list['upload_type'], 'type' => PDO::PARAM_STR),
						array('name' => ':upload_ratio', 'value' => $list['upload_ratio'], 'type' => PDO::PARAM_STR),
						array('name' => ':upload_beyond', 'value' => $nimble['nimble_nums'] == "" ? $list['upload_beyond'] : $nimble['nimble_nums'], 'type' => PDO::PARAM_STR),
						array('name' => ':qty_now', 'value' => $qty_now, 'type' => PDO::PARAM_STR),
						array('name' => ':qty', 'value' => $qty, 'type' => PDO::PARAM_STR),
					);
					
					$sql = "select 1 from stock_upload_list where system_id = :system_id and prd_id = :prd_id and prd_sku_id = :prd_sku_id and num_iid = :num_iid and sku_id = :sku_id  ";
					if($model_SYS->query($sql,$sqlParam)->find()){
						$model_SYS->execute("update stock_upload_list set action_time=:action_time, upload_type = :upload_type, upload_ratio = :upload_ratio,
									 appkey = :appkey, secretkey = :secretkey, sessionkey = :sessionkey, server_url = :server_url,
									 upload_beyond = :upload_ratio, qty_now = :qty_now, qty = :qty where system_id = :system_id and prd_id = :prd_id and prd_sku_id = :prd_sku_id and num_iid = :num_iid and sku_id = :sku_id ",$sqlParam);
					}else{
						$model_SYS->execute("insert into stock_upload_list(system_id, action_time, shoptype, shopid, appkey, secretkey, sessionkey, server_url, num_iid, sku_id, outer_iid,
									 outer_sku_id, prd_id, prd_sku_id, prd_no, upload_type, upload_ratio, upload_beyond, qty_now, qty) values (:system_id, :action_time, 
									 :shoptype, :shopid, :appkey, :secretkey, :sessionkey , :server_url,:num_iid, :sku_id, :outer_iid,
									 :outer_sku_id, :prd_id, :prd_sku_id, :prd_no, :upload_type, :upload_ratio,:upload_beyond, :qty_now, :qty)",$sqlParam);
					}
				}
			}
		}
	}
	
	public function stockUploadAction($param){//库存同步
		$model = D_SYS();
		
		include_once("MYAPP/system/model/ApiOptionDB.php");
		$myOptionDB = new ApiOptionDB();
		
		$system_id = $param['system_id'];
		$action_time = $param['action_time'];//任务创建时间
		$shoptype = $param['shoptype'];
		$shopid = $param['shopid'];
		$num_iid = $param['num_iid'];
		$sku_id = $param['sku_id'];
		$outer_iid = $param['outer_iid'];
		$outer_sku_id = $param['outer_sku_id'];
		$appkey = $param['appkey'];
		$secretkey = $param['secretkey'];
		$sessionkey = $param['sessionkey'];
		$server_url = $param['server_url'];
		$prd_id = $param['prd_id'];
		$prd_sku_id = $param['prd_sku_id'];
		$prd_no = $param['prd_no'];
		$upload_type = $param['upload_type'];//库存同步类型
		$upload_ratio = $param['upload_ratio'];//库存同步比例
		$upload_beyond = $param['upload_beyond'];//库存同步增减数
		$qty_now = $param['qty_now'];//现存量
		$param['qty'] = $param['qty'] < 0 ? 0 : $param['qty'];
		$qty = $param['qty'];//同步量
		$manual = $param['manual'];//手动自动标记
		
		if($shoptype == "TB"){
			$result_data = $myOptionDB->TaobaoItemQuantityUpdate($param);//库存同步
			$myOptionDB->TaobaoItemUpdateListing($param);//上架
			$is_success = $result_data['is_success'];
			$result = $result_data['result'];
		}else if($shoptype == "ALBB"){
			$result_data = $myOptionDB->ALBBItemQuantityUpdate($param);//库存同步
			$is_success = $result_data['is_success'];
			$result = $result_data['result'];
		}else if($shoptype == "PDD"){
			$result_data = $myOptionDB->PDDItemQuantityUpdate($param);//库存同步
			$is_success = $result_data['is_success'];
			$result = $result_data['result'];
		}else if($shoptype == "CQSHOP"){
			$result_data = $myOptionDB->CQSHOPItemQuantityUpdate($param);//库存同步
			$is_success = $result_data['is_success'];
			$result = $result_data['result'];
		}else{
			return;
		}
		
		$sqlParam = array(
			array('name' => ':system_id', 'value' => $system_id, 'type' => PDO::PARAM_STR),
			array('name' => ':action_time', 'value' => $action_time, 'type' => PDO::PARAM_STR),
			array('name' => ':shoptype', 'value' => $shoptype, 'type' => PDO::PARAM_STR),
			array('name' => ':shopid', 'value' => $shopid, 'type' => PDO::PARAM_STR),
			array('name' => ':num_iid', 'value' => $num_iid, 'type' => PDO::PARAM_STR),
			array('name' => ':sku_id', 'value' => $sku_id, 'type' => PDO::PARAM_STR),
			array('name' => ':outer_iid', 'value' => $outer_iid, 'type' => PDO::PARAM_STR),
			array('name' => ':outer_sku_id', 'value' => $outer_sku_id, 'type' => PDO::PARAM_STR),
			array('name' => ':prd_id', 'value' => $prd_id, 'type' => PDO::PARAM_STR),
			array('name' => ':prd_sku_id', 'value' => $prd_sku_id, 'type' => PDO::PARAM_STR),
			array('name' => ':prd_no', 'value' => $prd_no, 'type' => PDO::PARAM_STR),
			array('name' => ':upload_type', 'value' => $upload_type, 'type' => PDO::PARAM_STR),
			array('name' => ':upload_ratio', 'value' => $upload_ratio, 'type' => PDO::PARAM_STR),
			array('name' => ':upload_beyond', 'value' => $upload_beyond, 'type' => PDO::PARAM_STR),
			array('name' => ':qty_now', 'value' => $qty_now, 'type' => PDO::PARAM_STR),
			array('name' => ':qty', 'value' => $qty, 'type' => PDO::PARAM_STR),
			array('name' => ':manual', 'value' => $manual, 'type' => PDO::PARAM_STR),
			array('name' => ':is_success', 'value' => $is_success, 'type' => PDO::PARAM_STR),
			array('name' => ':result', 'value' => $result, 'type' => PDO::PARAM_STR)
		);
		
		$model->execute("insert into stock_upload_log(system_id, now_time, action_time, shoptype, shopid, num_iid, sku_id, outer_iid,
						 outer_sku_id, prd_id, prd_sku_id, prd_no, upload_type, upload_ratio, upload_beyond, qty_now, qty, `manual`, is_success, 
						 result) values (:system_id, UNIX_TIMESTAMP(NOW()), :action_time, :shoptype, :shopid, :num_iid, :sku_id, :outer_iid,
						 :outer_sku_id, :prd_id, :prd_sku_id, :prd_no, :upload_type, :upload_ratio,:upload_beyond, :qty_now, :qty, :manual, :is_success, 
						 :result)",$sqlParam);
	}
	
	/**
	* @author => zn,
	* @time   => 2018-11-19
	* @anno	  => 功能按钮权限判断
	* @param  => $param['type'] 当前按钮标识
	*/
	public function authorityJudge($param){
		$result = array();
		$model = D();
		$sql = "select type,func_permission from ".TABLE('usertable',$system_id)." where `userid` = '".$_SESSION['LOGIN_USER_ID']."' ";
		$row = $model->query($sql)->find();
		if($row && $row['type']=='system'){
			return array("code" => "ok");
		}else{
			if($row && $row['func_permission'] && $row['func_permission'] != 'T' && $row['func_permission'] != 'F'){
				$lines = explode(",",$row['func_permission']);
				foreach($lines as $line){
					if($line == $param['type']){
						return array("code" => "ok");
					}
				}
			}else if($row['func_permission'] == 'T' || $row['func_permission'] == ''){
				return array("code" => "ok");
			}else if($row['func_permission'] == 'F'){
				return array("code" => "error");
			}
		}
		return array("code" => "error");
	}
	
	public function doSecret($str,$plan){
		if($str == ""){
			return "";
		}
		if($plan == "mobile"){
			$p = substr($str,0,3)."****".substr($str,7,4);
		}else if($plan == "phone"){
			$p = "*******";
		}else if($plan == "idcard"){
			$p = substr($str,0,10)."****".substr($str,14,4);
		}
		return $p; 
	}
	
	public function aliAgOrderCancel($shopid, $down_time, $appkey, $secretkey, $sessionkey){//AG取消订单
		$model = D();
		
		$rowZT = $model->query("select configValue from ".TABLE('base_config')." where type='aliAGConfig' and configKey='unshipped' ")->find();
		if($rowZT['configValue'] == 'on'){
			$sqlRL = "select refund_id,tid,oid from ".TABLE('tid_refund')." where seller_nick = :seller_nick and modified >= :modified and ag_status = '0' and status='WAIT_SELLER_AGREE' ";
			$rowRL = $model->query($sqlRL,array(
						array('name' => ':seller_nick', 'value' => $shopid, 'type' => PDO::PARAM_STR),
						array('name' => ':modified', 'value' => $down_time, 'type' => PDO::PARAM_STR),
					 ))->select();
			if($rowRL){
				foreach($rowRL as $listRL){
					$payload = array(
						'appkey' => $appkey, 
						'secretkey' => $secretkey, 
						'sessionkey' => $sessionkey,
						'oid' => $listRL['oid'],
						'refund_id' => $listRL['refund_id'],
						'tid' => $listRL['tid'],
					);
					$resultInfo = $apiOptionDB->TaobaoRdcAligeniusAccountValidate($payload);
					if($resultInfo['result']['success'] == 'true'){
						$sqlNT = "select new_tid from ".TABLE('tid_orders')." where show_tid= :show_tid group by new_tid ";
						$rowNT = $model->query($sqlNT,array(
							array('name' => ':show_tid', 'value' => $listRL['tid'], 'type' => PDO::PARAM_STR),
						))->select();
						if($rowNT){
							foreach($rowNT as $listNT){
								$model->execute("update ".TABLE('tid_orders')." set mark_status='LOCK' where new_tid = :new_tid  ",array(
									array('name' => ':new_tid', 'value' => $listNT['new_tid'], 'type' => PDO::PARAM_STR),
								));
								
								$sql = "select tid from ".TABLE('tid_orders')." where new_tid=:new_tid ";
								if($rows = $model->query($sql,array(
									array('name' => ':new_tid', 'value' => $listNT['new_tid'], 'type' => PDO::PARAM_STR),
								))->select()){
									foreach($rows as $list){
										$paramHis = array();
										$paramHis['tid'] = $list['tid'];
										$paramHis['option_type'] = 'AG售后退款锁定订单';
										$this->setOptionHistory($paramHis);
									}
								}
								
							}
						}
						
						$model->execute("update ".TABLE('tid_refund')." set ag_status='1' where refund_id = :refund_id ",array(
							array('name' => ':refund_id', 'value' => $listRL['refund_id'], 'type' => PDO::PARAM_STR),
						));
					}else{
						$model->execute("update ".TABLE('tid_refund')." set ag_status='2',ag_reason = :ag_reason where refund_id = :refund_id ",array(
							array('name' => ':refund_id', 'value' => $listRL['refund_id'], 'type' => PDO::PARAM_STR),
							array('name' => ':ag_reason', 'value' => $resultInfo['result']['error_info'], 'type' => PDO::PARAM_STR),
						));
					}
				}
			}
		}
	}
	
	public function aliAgOrderReturn($sh_no, $appkey, $secretkey, $sessionkey){//AG退货入仓
		$model = D();
		
		$rowZT = $model->query("select configValue from ".TABLE('base_config')." where type='aliAGConfig' and configKey='shipped' ")->find();
		if($rowZT['configValue'] == 'on'){
			$sqlOR = "select oid from ".TABLE('aftersale_items')." where sh_no = :sh_no group by oid ";
			$rowOR = $model->query($sqlOR,array(
						array('name' => ':sh_no', 'value' => $sh_no, 'type' => PDO::PARAM_STR),
					 ))->select();
			if($rowOR){
				foreach($rowOR as $listOR){
					$sqlNM = "select sum(num) as num from ".TABLE('aftersale_items')." where oid = :oid ";
					$rowNM = $model->query($sqlNM,array(
						array('name' => ':oid', 'value' => $listOR['oid'], 'type' => PDO::PARAM_STR),
					))->find();
					
					$sqlRL = "select refund_id,num from ".TABLE('tid_refund')." where oid = :oid and ag_status = '0' and status='WAIT_SELLER_AGREE' ";
					$rowRL = $model->query($sqlRL,array(
						array('name' => ':oid', 'value' => $listOR['oid'], 'type' => PDO::PARAM_STR),
					))->find();
					if($rowRL['num'] > 0 && $rowNM['num'] >= $rowRL['num']){
						$payload = array(
							'appkey' => $appkey, 
							'secretkey' => $secretkey, 
							'sessionkey' => $sessionkey,
							'refund_id' => $rowRL['refund_id'],
						);
						$resultInfo = $apiOptionDB->TaobaoNextoneLogisticsWarehouseUpdate($payload);
						if($resultInfo['success'] == 'true'){
							$model->execute("update ".TABLE('tid_refund')." set ag_status='1' where refund_id = :refund_id ",array(
								array('name' => ':refund_id', 'value' => $rowRL['refund_id'], 'type' => PDO::PARAM_STR),
							));
						}else{
							$model->execute("update ".TABLE('tid_refund')." set ag_status='2',ag_reason = :ag_reason where refund_id = :refund_id ",array(
								array('name' => ':refund_id', 'value' => $rowRL['refund_id'], 'type' => PDO::PARAM_STR),
								array('name' => ':ag_reason', 'value' => $resultInfo['err_info'], 'type' => PDO::PARAM_STR),
							));
						}
					}
				}
			}
			
		}
	}
	
	public function payAcquireCustoms($param){//支付报关接口
		$key = $param['key'];//接口配置
		$mch_id = $param['mch_id'];//接口配置 微信商户号 
		$secret = $param['secret'];//接口配置 
		
		$out_request_no = $param['out_request_no'];//报关流水号/商户订单号
		$trade_no = $param['trade_no'];//支付宝交易号/微信支付订单号
		$customs_code = $param['customs_code'];//商户海关备案编号
		$customs_name = $param['customs_name'];//商户海关备案名称
		$amount = $param['amount'];//报关金额
		$customs_place = $param['customs_place'];//海关编号
	
		if($param['paytype'] == 'alipay'){//支付宝
			$cacert_url = $_SERVER['DOCUMENT_ROOT']."/cacert/apipay_cacert.pem";
			$parameter = array(
				"service"               => "alipay.acquire.customs",
				"partner"               => trim($key),
				"out_request_no"        => $out_request_no,
				"trade_no"              => $trade_no,
				"merchant_customs_code" => $customs_code,
				"merchant_customs_name" => $customs_name,
				"amount"                => $amount,
				"customs_place"         => $customs_place,
				"_input_charset"        => 'UTF-8',
			);
			
			ksort($parameter);
			
			$signStr = '';
			foreach ($parameter as $key => $val) {
				$signStr .= $key."=".$val."&";
			}
			$signStr = substr($signStr,0,count($signStr)-2);
			if(get_magic_quotes_gpc()){
				$signStr = stripslashes($signStr);
			}
			$mysign = md5($signStr. $secret);
			
			$parameter['sign'] = $mysign;
			$parameter['sign_type'] = 'MD5';
			
			$url = 'https://mapi.alipay.com/gateway.do?_input_charset=UTF-8';
			$curl = curl_init($url);
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);//SSL证书认证
			curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);//严格认证
			curl_setopt($curl, CURLOPT_CAINFO,$cacert_url);//证书地址
			curl_setopt($curl, CURLOPT_HEADER, 0 ); // 过滤HTTP头
			curl_setopt($curl,CURLOPT_RETURNTRANSFER, 1);// 显示输出结果
			curl_setopt($curl,CURLOPT_POST,true); // post传输数据
			curl_setopt($curl,CURLOPT_POSTFIELDS,$parameter);// post传输数据
			$responseText = curl_exec($curl);
			curl_close($curl);
			
			$xml = new SimpleXMLElement($responseText);
			$json = json_encode($xml);
			$result = json_decode($json,true);
			
			return $result;
		}else if($param['paytype'] == 'weixin'){//微信
			$cacert_url = $_SERVER['DOCUMENT_ROOT']."/cacert/weixin_cacert.pem";
			$parameter = array(
				"appid"              	=> $key,
				"mch_id"                => $mch_id,
				"transaction_id"        => $trade_no,
				"out_trade_no"        	=> $out_request_no,
				"customs" 				=> $customs_place,
				"merchant_customs_code" => $customs_code,
			);
			
			ksort($parameter);
			
			$signStr = '';
			foreach ($parameter as $key => $val) {
				$signStr .= $key."=".$val."&";
			}
			$signStr = substr($signStr,0,count($signStr)-2);
			if(get_magic_quotes_gpc()){
				$signStr = stripslashes($signStr);
			}
			$mysign = md5($signStr. $secret);
			
			$url = 'https://api.mch.weixin.qq.com/cgi-bin/mch/customs/customdeclareorder';
			$curl = curl_init($url);
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);//SSL证书认证
			curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);//严格认证
			curl_setopt($curl, CURLOPT_CAINFO,$cacert_url);//证书地址
			curl_setopt($curl, CURLOPT_HEADER, 0 ); // 过滤HTTP头
			curl_setopt($curl,CURLOPT_RETURNTRANSFER, 1);// 显示输出结果
			curl_setopt($curl,CURLOPT_POST,true); // post传输数据
			curl_setopt($curl,CURLOPT_POSTFIELDS,$parameter);// post传输数据
			$responseText = curl_exec($curl);
			curl_close($curl);
			
			$xml = new SimpleXMLElement($responseText);
			$json = json_encode($xml);
			$result = json_decode($json,true);
			
			return $result;
		}
	}
}
?>