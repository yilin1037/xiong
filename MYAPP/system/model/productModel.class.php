<?php
include_once('../SDK/tms-sdk/tmsapi.php');

class productModelClass extends CommonModelClass
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
						array('name' => ':prd_no', 'value' => '%'.$params['prd_no']."%", 'type' => PDO::PARAM_STR),
						array('name' => ':prd_name', 'value' => '%'.strtoupper($params['prd_name'])."%", 'type' => PDO::PARAM_STR),
						array('name' => ':cat_no', 'value' => $params['cat_no'], 'type' => PDO::PARAM_STR)
					);

		$where = " WHERE 1=1 ";
		if(!empty($params['prd_no'])){
			$where .= " AND prd_no like :prd_no";
		}
		if(!empty($params['prd_name'])){
			$where .= " AND (prd_name like :prd_name OR mnem_code like :prd_name)";
		}
		if(!empty($params['cat_no'])){
			$where .= " AND cat_no = :cat_no";
		}

		$sql = "SELECT id,prd_no,prd_name,cat_no,box_barcode,box_volume,box_weight,unit_name,unit_barcode,unit_name1,unit_barcode1,created_time FROM ". 'product' . $where . " ORDER BY id DESC";
		$result = $model->query($sql, $sqlParam)->limitPage($page1, $limit)->select();

		if(!empty($result)){
			// 商品分类
			$sqlProductCategory = 'SELECT * FROM ' . 'product_category' . '  ';
			$listPCate_ = $model->query($sqlProductCategory)->select();
			$listPCate = array();
			if(!empty($listPCate_)){
				foreach($listPCate_ as $vo){
					$listPCate[$vo['category_no']] = $vo['category_name'];
				}
			}
			unset($listCustType_);

			foreach($result as $key=>$vo){
				$result[$key]['cat_no'] = $listPCate[$vo['cat_no']];
				$result[$key]['box_volume'] = floatval($vo['box_volume']);
			}
		}

		$sql = "SELECT count(1) AS count FROM " . 'product' . $where;
		$count = $model->query($sql)->find();
		//print_r($model->getLastSql());

		return array(
			"code" => "0",
            "msg" =>"",
            "total" =>$count['count'],
            "data" =>$result
		);
	}
    function getListapp($params){
        $params = array_map(function($value){return htmlspecialchars(trim($value));}, $params);
        $model = D();

        $page 	= $params['pageIndex'];
        $limit 	= $params['pageSize'];
        $page1 	= $page*$limit;

        $sqlParam = array(
            array('name' => ':prd_no', 'value' => '%'.$params['prd_no']."%", 'type' => PDO::PARAM_STR),
            array('name' => ':prd_name', 'value' => '%'.strtoupper($params['prd_name'])."%", 'type' => PDO::PARAM_STR),
            array('name' => ':cat_no', 'value' => $params['cat_no'], 'type' => PDO::PARAM_STR)
        );

        $where = " WHERE 1=1 ";
        if(!empty($params['prd_no'])){
            $where .= " AND A.prd_no like :prd_no";
        }
        if(!empty($params['prd_name'])){
            $where .= " AND (A.prd_name like :prd_name OR A.mnem_code like :prd_name)";
        }
        if(!empty($params['cat_no'])){
            $where .= " AND A.cat_no = :cat_no";
        }

        $sql = "SELECT A.*,B.* FROM ". "product A right join ". TABLE("product_info") . " B  ON A.prd_no=B.prd_no" . $where . "  ORDER BY A.id DESC";
        $result = $model->query($sql, $sqlParam)->limitPage($page1, $limit)->select();
        $listPCate_ = array();
        if(!empty($result)){
            // 商品分类
            $sqlProductCategory = 'SELECT * FROM ' . 'product_category' . '  ';
            $listPCate_ = $model->query($sqlProductCategory)->select();
            $listPCate = array();
            if(!empty($listPCate_)){
                foreach($listPCate_ as $vo){
                    $listPCate[$vo['category_no']] = $vo['category_name'];
                }
            }
            unset($listCustType_);

            foreach($result as $key=>$vo){
                $result[$key]['cat_no'] = $listPCate[$vo['cat_no']];
                $result[$key]['box_volume'] = floatval($vo['box_volume']);
                $result[$key]['versionName'] = $vo['prd_name'];
                $result[$key]['synopsis'] = $vo['prd_name'];
                $result[$key]['name'] = $vo['prd_name']; $result[$key]['title'] = $vo['prd_name'];
                $result[$key]['stock'] = 90000;
                $result[$key]['price'] = $vo['box_price'];
                $result[$key]['version'] =  array(
                    array(
                        'name'=>'箱',
                        'price'=>$vo['box_price'],
                        'stock'=>6,
                        'image'=> 'https://bbc.jetm3.com/attachment/images/7/2018/03/iq6EJJZ2EMJjfm22PMmJ2EymEEqepe.png'
                    ),
                    array(
                        'name'=>'主单位:'.$vo['unit_name'],
                        'price'=>$vo['unit_price'],
                        'stock'=>6,
                        'image'=> 'https://bbc.jetm3.com/attachment/images/7/2018/03/iq6EJJZ2EMJjfm22PMmJ2EymEEqepe.png'
                    ),
                    array(
                        'name'=>'副单位:'.$vo['unit_name1'],
                        'price'=>$vo['unit_price1'],
                        'stock'=>6,
                        'image'=> 'https://bbc.jetm3.com/attachment/images/7/2018/03/iq6EJJZ2EMJjfm22PMmJ2EymEEqepe.png')
                );

            }
        }

        $sql = "SELECT count(1) AS count FROM " . 'product' . $where;
        $count = $model->query($sql)->find();
        //print_r($model->getLastSql());

        return array(
            "code" => "0",
            "msg" =>"",
            "total" =>$count['count'],
            "data" =>$result,
            "category" =>$listPCate_,
            "categorytotal" =>count($listPCate_)
        );
    }
	/**
	 * 添加 - 保存
	 */
	function add($params){
		if(!is_array($params)){
			return array();
		}

		$model = D();
		// 安全过滤
		$params = array_map(function($value){return htmlspecialchars(trim($value));}, $params);
		// 数据集
		$data = array();
		$data['prd_no'] 		= $params['prd_no'];
		$data['prd_name'] 		= $params['prd_name'];
		$data['mnem_code'] 		= $params['mnem_code'];
		$data['cat_no'] 		= $params['cat_no'];
		$data['unit_name'] 		= $params['unit_name'];
		$data['unit_barcode'] 	= $params['unit_barcode'];
		$data['unit_weight'] 	= $params['unit_weight'];
		$data['spec'] 			= $params['spec'];
		$data['box_barcode'] 	= $params['box_barcode'];
		$data['box_weight']		= $params['box_weight'];
		$data['box_length']		= $params['box_length'];
		$data['box_width'] 		= $params['box_width'];
		$data['box_height']		= $params['box_height'];
		$data['box_volume'] 	= $params['box_volume'];
		$data['box_rate'] 		= $params['box_rate'];
		$data['unit_name1'] 	= $params['unit_name1'];
		$data['unit_barcode1'] 	= $params['unit_barcode1'];
		$data['unit_weight1'] 	= $params['unit_weight1'];
		$data['unit_rate1'] 	= $params['unit_rate1'];
		$data['stock_up'] 		= $params['stock_up'];
		$data['stock_low'] 		= $params['stock_low'];
		$data['frequency'] 		= $params['frequency'];
		$data['disabled'] 		= $params['disabled'];
		$data['expiry_day'] 	= $params['expiry_day'];
		$data['early_day'] 		= $params['early_day'];
		$data['disperse_type'] 	= $params['disperse_type'];
		$data['tray_box'] 		= $params['tray_box'];
		$data['producer'] 		= $params['producer'];
		$data['remark'] 		= $params['remark'];
		$data['created_time']	= time();
		$data['modified_time']	= $data['created_time'];
		$data['modified_usr']	= $_SESSION['LOGIN_USERNAME'];

		$param[] = array('name' => ':prd_no', 'value' => $data['prd_no'], 'type' => PDO::PARAM_STR);
		if($model->query("select id from " .'product'. " where prd_no = :prd_no",$param)->find()){
			return array("code"=>"error", "msg"=>"商品编号重复");
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
		$sql = "INSERT INTO ". 'product' . $fields . "
				values " . $fieldsValues;
		// prd_no 系统自动生成
		/*$sql = str_replace(':prd_no', "(select prd_no from (SELECT CONCAT('PRD_',LPAD(RIGHT(IFNULL(MAX(prd_no),''),5)+1,5,'0')) as prd_no FROM product) a)", $sql);*/

		// 执行
		$result = $model->execute($sql, $sqlParam);
		//echo $model->getLastSql();exit;
		if($result){

            // 3 TMS 添加商品

            $sql = "select * from product where `mnem_code` = '".$data['mnem_code']."' order by id desc limit 1";
            $row = $model->query($sql)->find();

            $time_int=time();
            $time_str=(string)$time_int;

            $goodData= array(
                "goodsNo"=>$row["prd_no"],                    //商品编号
                "goodsName"=>$row["prd_name"],                //商品名称
                "goodsId"=>$row["id"],                    //商品ID
                "barCode"=>$row["unit_barcode"],                    //条形码
                "cartonCode"=>$row["box_barcode"],                 //箱条码
                "mnemCode"=>$row["mnem_code"],                 //助记码
                "standard"=>$row["spec"],                   //规格
                "unit"=>$row["unit_name"],                        //主单位
                "auxunit"=>$row["unit_name1"],                    //辅单位
                "conversionRate"=>(int)$row["box_rate"],                  //换算率
                "salePrice"=>0,                       //销售价
                "floorPrice"=>0,                     //最低售价
                "volume"=>(float)$row["box_rate"],                       //体积
                "auxVolume"=>0,                    //辅助体积
                "weight"=>$row["box_width"],                        //重量
                "auxWeight"=>$row["unit_weight1"],                     //辅助重量
                "glength"=>$row["box_length"],                       //长度
                "gwidth"=>$row["box_width"],                        //宽度
                "gheight"=>$row["box_height"],                       //高度
                "manuFacturer"=>$row["producer"],               //生产厂商
                "deliveFeeType"=>0                    //配送计费方式 0：重量；1：体积；2：件
            );

            $goodResult = addGood($goodData);



			return array("code"=>"ok", "msg"=>"操作成功");
		}else{
			return array("code"=>"error", "msg"=>"操作失败");
		}
	}

	/**
	 * 编辑 - 保存
	 */
	function edit($params){
		if(!is_array($params)){
			return array();
		}
		$model = D();
		// 安全过滤
		$params = array_map(function($value){return htmlspecialchars(trim($value));}, $params);
		// 数据集
		$data = array();
		$data['prd_name'] 		= $params['prd_name'];
		$data['mnem_code'] 		= $params['mnem_code'];
		$data['cat_no'] 		= $params['cat_no'];
		$data['unit_name'] 		= $params['unit_name'];
		$data['unit_barcode'] 	= $params['unit_barcode'];
		$data['unit_weight'] 	= $params['unit_weight'];
		$data['spec'] 			= $params['spec'];
		$data['box_barcode'] 	= $params['box_barcode'];
		$data['box_weight']		= $params['box_weight'];
		$data['box_length']		= $params['box_length'];
		$data['box_width'] 		= $params['box_width'];
		$data['box_height']		= $params['box_height'];
		$data['box_volume'] 	= $params['box_volume'];
		$data['box_rate'] 		= $params['box_rate'];
		$data['unit_name1'] 	= $params['unit_name1'];
		$data['unit_barcode1'] 	= $params['unit_barcode1'];
		$data['unit_weight1'] 	= $params['unit_weight1'];
		$data['unit_rate1'] 	= $params['unit_rate1'];
		$data['stock_up'] 		= $params['stock_up'];
		$data['stock_low'] 		= $params['stock_low'];
		$data['frequency'] 		= $params['frequency'];
		$data['disabled'] 		= $params['disabled'];
		$data['expiry_day'] 	= $params['expiry_day'];
		$data['early_day'] 		= $params['early_day'];
		//$data['disperse_type'] 	= $params['disperse_type']=='true'?1:0;
		$data['disperse_type'] 	= $params['disperse_type'];
		$data['tray_box'] 		= $params['tray_box'];
		$data['producer'] 		= $params['producer'];
		$data['remark'] 		= $params['remark'];
		$data['modified_time']	= time();
		$data['modified_usr']	= $_SESSION['LOGIN_USERNAME'];

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
		$sql = "UPDATE ". 'product' . ' SET ' . $fieldsSet . ' WHERE id=:id ';
		// 执行
		$result = $model->execute($sql, $sqlParam);
		//echo $model->getLastSql();exit;
		if($result !== false){
            // 3 TMS 添加商品

            $sql = "select * from product where `mnem_code` = '".$data['mnem_code']."' order by id desc limit 1";
            $row = $model->query($sql)->find();

            $time_int=time();
            $time_str=(string)$time_int;

            $goodData= array(
                "goodsNo"=>$row["prd_no"],                    //商品编号
                "goodsName"=>$row["prd_name"],                //商品名称
                "goodsId"=>$row["id"],                    //商品ID
                "barCode"=>$row["unit_barcode"],                    //条形码
                "cartonCode"=>$row["box_barcode"],                 //箱条码
                "mnemCode"=>$row["mnem_code"],                 //助记码
                "standard"=>$row["spec"],                   //规格
                "unit"=>$row["unit_name"],                        //主单位
                "auxunit"=>$row["unit_name1"],                    //辅单位
                "conversionRate"=>(int)$row["box_rate"],                  //换算率
                "salePrice"=>0,                       //销售价
                "floorPrice"=>0,                     //最低售价
                "volume"=>(float)$row["box_rate"],                       //体积
                "auxVolume"=>0,                    //辅助体积
                "weight"=>$row["box_width"],                        //重量
                "auxWeight"=>$row["unit_weight1"],                     //辅助重量
                "glength"=>$row["box_length"],                       //长度
                "gwidth"=>$row["box_width"],                        //宽度
                "gheight"=>$row["box_height"],                       //高度
                "manuFacturer"=>$row["producer"],               //生产厂商
                "deliveFeeType"=>0                    //配送计费方式 0：重量；1：体积；2：件
            );

            $goodResult = editGood($goodData);

            return array("code"=>"ok", "msg"=>"操作成功");
		}else{
			return array("code"=>"error", "msg"=>"操作失败");
		}
	}

	/**
	 * 查询单条记录
	 */
	function getById($id){
		$model = D();
		$sqlParam = array(
			array('name' => ':id', 'value' => $id, 'type' => PDO::PARAM_STR),
		);
		$sql ="SELECT * FROM ". 'product' ." WHERE id=:id";
		$result = $model->query($sql, $sqlParam)->find();
		return $result;
	}

	/**
	 * 删除
	 */
	function del($ids){
		$model = D();
		$sqlParam = array(
			array('name' => ':id', 'value' => $ids, 'type' => PDO::PARAM_STR),
		);
		if(strpos($ids, ',') !== false){
			$ids = explode(',', $ids);
			$ids = implode(',', $ids);
			$where = ' id in('.$ids.') ';
			$tmp = ' b.id in('.$ids.') ';
		}else{
			$where = ' id=:id ';
			$tmp = ' b.id=:id ';
		}

		$sql = "select a.id from " .TABLE('tid_items'). " a left join " .'product'. " b on b.prd_no = a.prd_no where " .$tmp;
		$res = $model->query($sql,$sqlParam)->select();
		if(!empty($res)){
			return array("code"=>"error", "msg"=>"操作失败，在订单中的商品无法删除");
		}
		$sql = "DELETE FROM " . 'product' . " WHERE " . $where;
		if($model->execute($sql, $sqlParam)){
			return array("code"=>"ok", "msg"=>"操作成功");
		}else{
			return array("code"=>"error", "msg"=>"操作失败");
		}
	}

}
