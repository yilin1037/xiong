<?
class whLocModelClass extends CommonModelClass
{
	public function __construct(){
		parent::__construct();
	}
	
	/**
	 * 列表
	 */
	function getList($params){
		//print_r($params);exit;
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
						array('name' => ':loc_no', 'value' => '%'.$params['loc_no']."%", 'type' => PDO::PARAM_STR),
						array('name' => ':loc_name', 'value' => $params['loc_name'], 'type' => PDO::PARAM_STR),
						array('name' => ':wh_no', 'value' => $params['wh_no'], 'type' => PDO::PARAM_STR),
					);
						
		$where = " WHERE 1=1 ";
		if(!empty($params['loc_no'])){
			$where .= " AND (loc_no like :loc_no)";
		}
		if(!empty($params['loc_name'])){
			$where .= " AND loc_name like :loc_name";
		}
		if(!empty($params['wh_no'])){
			if($params['wh_no'] == 'area'){	
			}else{
				$tmp = array(
						array('name' => ':wh_no', 'value' => $params['wh_no'], 'type' => PDO::PARAM_STR),
					);
				$sql = "select area_no from wh_area where area_no = :wh_no";
				$result = $model->query($sql,$tmp)->find();
				if(!empty($result)){
					$param = array(
						array('name' => ':area_no', 'value' => $result['area_no'], 'type' => PDO::PARAM_STR),
					);
					$sql = "select wh_no from wh where area_no = :area_no";
					$res = $model->query($sql,$param)->select();
					$str = '';
					if(!empty($res)){
						foreach($res as $val){
							$temp[] = $val['wh_no'];
						}
						$str = implode("','",$temp);
						$where .= " AND wh_no in ('" .$str."')";
					}else{
						$where .= " AND wh_no=:wh_no";
					}
				}else{
					$where .= " AND wh_no=:wh_no";
				}
			}
		}
		$sql = "select a.*,d.dealer_name,u.user_name from wh_loc AS a";
		$sql .= " LEFT JOIN dealer d ON a.dealer_no=d.dealer_no ";
		$sql .= " LEFT JOIN userlist u ON a.curator=u.user_no ";
		$sql .= $where;
		$sql .= $order;
		$result = $model->query($sql, $sqlParam)->limitPage($page1, $limit)->select();
		if(!empty($result)){
			// 所属仓库
			$sqlWh = 'SELECT * FROM ' . 'wh' . '  ';
			$listWh_ = $model->query($sqlWh)->select();
			$listWh = array();
			if(!empty($listWh_)){
				foreach($listWh_ as $vo){
					$listWh[$vo['wh_no']] = $vo['wh_name'];
				}
			}
			
			foreach($result as $key=>$vo){
				$result[$key]['dealer_no'] = $vo['dealer_name'];
				$result[$key]['curator'] = $vo['user_name'];
				$result[$key]['wh_no'] = $listWh[$vo['wh_no']];
				
				if($vo['frequency'] == 0){
					$result[$key]['frequency'] = '无';
				}else if($vo['frequency'] == 1){
					$result[$key]['frequency'] = '低频';
				}else if($vo['frequency'] == 2){
					$result[$key]['frequency'] = '中频';
				}else if($vo['frequency'] == 3){
					$result[$key]['frequency'] = '高频';
				}
			}
		}
		
		$sql = "select count(1) as count from wh_loc" . $where;
		$count = $model->query($sql, $sqlParam)->find();
		//print_r($model->getLastSql());
		return array(
			"code" => "0",
            "msg" =>"",
            "total" =>$count['count'],
            "data" =>$result
		);
	}
	
	/**
	 * 保管员列表
	 */
	function getUserList($params){
		$params = array_map(function($value){return htmlspecialchars(trim($value));}, $params);
		$model = D();
		
		$page 	= $params['pageIndex'];
        $limit 	= $params['pageSize'];
		$page1 	= $page*$limit;
		
		$sqlParam = array(
						array('name' => ':user_no', 'value' => '%'.$params['user_no']."%", 'type' => PDO::PARAM_STR),
						array('name' => ':user_name', 'value' => strtoupper($params['user_name']), 'type' => PDO::PARAM_STR),
					);
						
		$where = " WHERE 1=1 ";
		if(!empty($params['user_no'])){
			$where .= " AND (user_no like :user_no)";
		}
		if(!empty($params['user_name'])){
			$where .= " AND user_name like :user_name";
		}
		
		$sql = "select id,user_no,user_name,dept_no from userlist" . $where . " order by user_no DESC";
		$result = $model->query($sql, $sqlParam)->limitPage($page1, $limit)->select();
		
		$sql = "select count(1) as count from wh_loc" . $where;
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
	 * 添加 - 保存
	 */
	function saveAdd($params){
		if(!is_array($params)){
			return array();
		}	
		$model = D();
		// 安全过滤
		$params = array_map(function($value){return htmlspecialchars(trim($value));}, $params);
		// 数据集
		//$sql ="select MAX(loc_no) AS loc_no from wh_loc LIMIT 1";
		//$rowLast = $model->query($sql)->find();
		//$loc_no = intval(str_replace('LOC_', '', $rowLast['loc_no']))+1;
		//$loc_no = 'LOC_' . str_pad($loc_no, 5, '0', STR_PAD_LEFT);
		
		$data = array();
		$data['wh_no'] 	= $params['wh_no'];
		$data['loc_no'] 	= $params['loc_no'];
		$data['loc_name'] 	= $params['loc_name'];
		$data['loc_barcode'] 	= $params['loc_barcode'];
		$data['curator'] 	= $params['curator'];
		$data['dealer_no'] 	= $params['dealer_no'];
		$data['max_volume'] = $params['max_volume'];
		$data['max_weight'] 	= $params['max_weight'];
		$data['level'] 	= $params['level'];
		$data['loc_type'] 	= $params['loc_type'];
		$data['loc_attr'] 		= $params['loc_attr'];
		$data['stock_up'] 	= $params['stock_up'];
		$data['stock_low'] 	= $params['stock_low'];
		$data['frequency'] 	= $params['frequency'];
		$data['prdt_cat_nos'] 	= $params['prdt_cat_nos'];
		$data['status'] 	= $params['status'];
		$data['disabled'] 	= $params['disabled'];
	
		$sqlParam = array(
						array('name' => ':loc_no', 'value' => $params['loc_no'], 'type' => PDO::PARAM_STR),
						array('name' => ':loc_name', 'value' => $params['loc_name'], 'type' => PDO::PARAM_STR),
						array('name' => ':wh_no', 'value' => $params['wh_no'], 'type' => PDO::PARAM_STR),
					);
		$sql1 = "select id from wh_loc where loc_no = :loc_no and wh_no = :wh_no";
		$sql2 = "select id from wh_loc where loc_name = :loc_name and wh_no = :wh_no";
		$res1 = $model->query($sql1,$sqlParam)->find();
		$res2 = $model->query($sql2,$sqlParam)->find();
		if(!empty($res1) || !empty($res2)){
			return array("code"=>"error","msg"=>"货位编号或者货位名称重复");
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
		$sql = "insert into wh_loc" . $fields . "
				values " . $fieldsValues;
		// 执行
		$result = $model->execute($sql, $sqlParam);
		
		if($result){
			return array("code"=>"ok","msg"=>"操作成功");
		}else{
			return array("code"=>"error","msg"=>"操作失败");
		}
	}
	
	/**
	 * 编辑 - 保存
	 */
	function saveEdit($params){
		if(!is_array($params)){
			return array();
		}
		$model = D();
		// 安全过滤
		$params = array_map(function($value){return htmlspecialchars(trim($value));}, $params);
		// 数据集
		$data = array();
		$data['loc_no'] 	= $params['loc_no'];
		$data['loc_name'] 	= $params['loc_name'];
		$data['loc_barcode'] 	= $params['loc_barcode'];
		$data['curator'] 	= $params['curator'];
		$data['dealer_no'] 	= $params['dealer_no'];
		$data['max_volume'] = $params['max_volume'];
		$data['max_weight'] 	= $params['max_weight'];
		$data['level'] 	= $params['level'];
		$data['loc_type'] 	= $params['loc_type'];
		$data['loc_attr'] 		= $params['loc_attr'];
		$data['stock_up'] 	= $params['stock_up'];
		$data['stock_low'] 	= $params['stock_low'];
		$data['frequency'] 	= $params['frequency'];
		$data['prdt_cat_nos'] 	= $params['prdt_cat_nos'];
		$data['status'] 	= $params['status'];
		$data['disabled'] 	= $params['disabled'];
		
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
		$sql = "update wh_loc SET " . $fieldsSet . ' WHERE id=:id ';
		// 执行
		$result = $model->execute($sql, $sqlParam);
		//echo $model->getLastSql();exit;
		

		if($result !== false){
			return array("code"=>"ok","msg"=>"操作成功");
		}else{
			return array("code"=>"error","msg"=>"操作失败");
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
		$sql ="select a.*,d.dealer_name,u.user_name,w.wh_name from wh_loc a left join dealer d on a.dealer_no = d.dealer_no left join userlist u on a.curator = u.user_no left join wh w on a.wh_no = w.wh_no where a.id=:id";
		$result = $model->query($sql,$sqlParam)->find();
		
		return $result;
	}
	
	/**
	 * 查询所属仓库
	 */
	function getByWh($wh_no){
		$model = D();
		$sqlParam = array(
			array('name' => ':wh_no', 'value' => $wh_no, 'type' => PDO::PARAM_STR),
		);
		$sql ="select * from wh where wh_no=:wh_no";
		$result = $model->query($sql,$sqlParam)->find();
		
		return $result;
	}
	
	/**
	 * 删除
	 */
	function del($id){
		$model = D();
		$sqlParam = array(
			array('name' => ':id', 'value' => $id, 'type' => PDO::PARAM_STR),
		);
		if(strpos($id, ',') !== false){
			$where = ' id in(:id) ';
		}else{
			$where = ' id=:id ';
		}
		$sql = "DELETE FROM wh_loc WHERE " . $where;
		$delVal = $model->execute($sql, $sqlParam);
		if($delVal){
			return array("code"=>"ok","msg"=>"操作成功");
		}else{
			return array("code"=>"error","msg"=>"操作失败"+$id);
		}
	}


    //导入货位
    function whlocExcelInput($param,$objWorksheet,$highestRow){
        $model = D();

        $result = array('code' => 'ok');
        $errorList = array();
        $dataList = array();
        $nowDate = date("Y-m-d H:i:s");

        $errorList = array();

        for($index = 2; $index <= $highestRow; $index++){

            $data = array();

            // 从execl获取信息
            $data['wh_no'] 	= $objWorksheet->getCellByColumnAndRow(0, $index)->getValue();
            $data['curator'] = $objWorksheet->getCellByColumnAndRow(1, $index)->getValue();
            $data['loc_no'] 	= $objWorksheet->getCellByColumnAndRow(2, $index)->getValue();
            $data['loc_name'] 	= $objWorksheet->getCellByColumnAndRow(3, $index)->getValue();
            $data['loc_barcode'] 	= $objWorksheet->getCellByColumnAndRow(4, $index)->getValue();
            $data['dealer_no'] 	= $objWorksheet->getCellByColumnAndRow(5, $index)->getValue();
            $data['loc_attr'] = $objWorksheet->getCellByColumnAndRow(6, $index)->getValue();
            $data['level'] = $objWorksheet->getCellByColumnAndRow(7, $index)->getValue();
            $data['max_volume'] = $objWorksheet->getCellByColumnAndRow(8, $index)->getValue();
            $data['max_weight'] = $objWorksheet->getCellByColumnAndRow(9, $index)->getValue();
            $data['loc_type'] = $objWorksheet->getCellByColumnAndRow(10, $index)->getValue();
            $data['frequency']	= $objWorksheet->getCellByColumnAndRow(11, $index)->getValue();
            $data['status']	= $objWorksheet->getCellByColumnAndRow(12, $index)->getValue();
            $data['disabled'] = $objWorksheet->getCellByColumnAndRow(13, $index)->getValue();


            if(empty( $data['wh_no']))
            {
                $errorList[] = array('index' => $index, 'msg' => '仓库货位不能为空');
            }
            elseif(empty( $data['loc_no']))
            {
                $errorList[] = array('index' => $index, 'msg' => '货位编号不能为空');
            }
            elseif(empty( $data['loc_name']))
            {
                $errorList[] = array('index' => $index, 'msg' => '货位名称不能为空');
            }
            else
            {

                // {"id":"","wh_no":"N002",
                //"curator":"USER_00042",
                //"loc_no":"6666",
                //"loc_name":"6666",
                //"loc_barcode":"",
                //"dealer_no":"773","loc_attr":"0","level":"","max_volume":"","max_weight":"","loc_type":"0"
                //,"frequency":"2","status":"0","disabled":"1"}
                // 转换仓库
                if(!empty($data['wh_no'])){
                    $sqlParam = array(
                        array('name' => ':wh_name', 'value' => strtoupper($data['wh_no']), 'type' => PDO::PARAM_STR)
                    );
                    $where = " WHERE 1=1 ";
                    $where .= " AND wh_name like :wh_name";
                    $productCategoryModel =D();

                    $sql = "SELECT id,wh_no,wh_name FROM ". 'wh' . $where . " ORDER BY id ASC";
                    $catResult = $productCategoryModel->query($sql,$sqlParam)->find();
                    if(!empty($catResult)) {
                        $data['wh_no'] =  $catResult["wh_no"];
                    }

                }

                // 转换保管员
                if(!empty($data['curator'])){
                    $sqlParam = array(
                        array('name' => ':user_name', 'value' => strtoupper($data['curator']), 'type' => PDO::PARAM_STR)
                    );
                    $where = " WHERE 1=1 ";
                    $where .= " AND user_name like :user_name";
                    $productCategoryModel =D();

                    $sql = "SELECT id,user_no,user_name FROM ". 'userlist' . $where . " ORDER BY id ASC";
                    $catResult = $productCategoryModel->query($sql,$sqlParam)->find();
                    if(!empty($catResult)) {
                        $data['curator'] =  $catResult["user_no"];
                    }

                }

                // 转换货位属性
                if(trim($data['loc_attr'])=="常规货位") {
                    $data['loc_attr']=0;
                } else if (trim($data['loc_attr'])=="非常规货位") {
                    $data['loc_attr']=1;
                }
                // 转换仓库类型
                if(trim($data['loc_type'])=="存货区") {
                    $data['loc_type']=0;
                } else if (trim($data['loc_type'])=="不区分检货区") {
                    $data['loc_type']=1;
                } else if (trim($data['loc_type'])=="整货位检货区") {
                    $data['loc_type']=2;
                } else if (trim($data['loc_type'])=="散货位检货区") {
                    $data['loc_type']=3;
                }

                // 转换频次
                if(trim($data['frequency'])=="无") {
                    $data['frequency']=0;
                } else if (trim($data['frequency'])=="低频") {
                    $data['frequency']=1;
                } else if(trim($data['frequency'])=="中频") {
                    $data['frequency']=2;
                } else if(trim($data['frequency'])=="高频") {
                    $data['frequency']=3;
                }

                // 转换锁定
                if(trim($data['status'])=="T") {
                    $data['status']=0;
                } else if (trim($data['status'])=="F") {
                    $data['status']=1;
                }

                // 转换禁用
                if(trim($data['disabled'])=="T") {
                    $data['disabled']=1;
                } else if (trim($data['disabled'])=="F") {
                    $data['disabled']=0;
                }


                $result=$this->saveAdd($data);
                if($result["code"] == "error")
                {
                    $errorList[] = array('index' => $index, 'msg' => $result['msg']);
                }
            }

        }

        if($errorList != "" && count($errorList) >0){
            $errorNum = count($errorList);
            $errorMsg = implode(',',$errorList);
            $result['code'] = 'errorList';
            $result['errorList'] = $errorList;
            return $result;
        } else {
            return $result;
        }



    }
	
	
}
