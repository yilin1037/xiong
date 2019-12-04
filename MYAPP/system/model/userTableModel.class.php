<?
class userTableModelClass extends CommonModelClass
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
						array('name' => ':username', 'value' => '%'.$params['username']."%", 'type' => PDO::PARAM_STR),
						array('name' => ':mobile', 'value' => $params['mobile'], 'type' => PDO::PARAM_STR),
						array('name' => ':status', 'value' => $params['status'], 'type' => PDO::PARAM_STR),
					);
						
		$where = " WHERE 1=1 ";
		if(!empty($params['username'])){
			$where .= " AND username like :username";
		}
		if(!empty($params['mobile'])){
			$where .= " AND mobile like :mobile";
		}
		if($params['status'] != '' && $params['status'] != 'all'){
			$where .= " AND status=:status";
		}
		//print_r($where);exit;
		$sql = "select id,userid,username,password,mobile,status,last_login_time,create_login_time from " . TABLE('usertable') . $where . " order by id DESC";
		$result = $model->query($sql, $sqlParam)->limitPage($page1, $limit)->select();
		//print_r($model->getLastSql());
		
		if(!empty($result)){
			
			foreach($result as $key=>$vo){
				
				$result[$key]['create_login_time'] = date("Y-m-d",$vo['create_login_time']);
				if($vo['status'] ==0){
					$result[$key]['status'] = '启用';
				}else{
					$result[$key]['status'] = '禁用';
				}
				if($vo['last_login_time'] ==0){
					$result[$key]['last_login_time'] = '';
				}else{
					$result[$key]['last_login_time'] = date("Y-m-d",$vo['last_login_time']);
				}
			}
		}
		
		$sql = "select count(1) as count from ". TABLE('usertable') . $where;
		$count = $model->query($sql,$sqlParam)->find();
		
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
		$data = array();
		$data['userid'] 	= $params['userid'];
		$data['username'] = $params['username'];
		$data['password'] = md5($data['userid'].md5($params['password']));
		$data['mobile'] = $params['mobile'];
		$data['status'] = $params['status'];
		$data['create_login_time'] = time();
		
		// 构造Sql结构
		$sqlParam = array();
		foreach($data as $key=>$value){
			$sqlParam[] = array('name' => ':'.$key, 'value' => $value, 'type' => PDO::PARAM_STR);
		}
		//print_r($sqlParam);exit;
		$fieldKeys = array_keys($data);
		$fields = '(';
		$fields .= implode(',', $fieldKeys);
		$fields .= ')';
		
		$fieldsValues = array_map(function($value){
			return ':'.$value;
			}, $fieldKeys);
		$fieldsValues = '('.implode(',', $fieldsValues).')';
		// Sql语句
		$sql = "insert into " . TABLE('usertable') . $fields . "
				values " . $fieldsValues;
				
		// 开启事务
		$model->startTrans();
		// 执行
		$result = $model->execute($sql, $sqlParam);
		//echo $model->getLastSql();exit;
		
		if(!$result){
			$model->rollback();
			return array("code"=>"error", "msg"=>"操作失败");
		}
		
		// 提交事务
		$model->commit();
		return array("code"=>"ok","msg"=>"操作成功");
		
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
		$data['userid'] 	= $params['userid'];
		$data['username'] = $params['username'];
		
		if(!empty($params['password'])){
			$data['password'] = md5($data['userid'].md5($params['password']));
		}
		
		$data['mobile'] = $params['mobile'];
		$data['status'] = $params['status'];
		
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
		$sql = "update " . TABLE('usertable') . ' SET ' . $fieldsSet . ' WHERE id=:id ';
		
		//开启事务
		$model->startTrans();
		// 执行
		$result = $model->execute($sql, $sqlParam);
		//echo $model->getLastSql();exit;
		if($result === false){
			$model->rollback();
			return array("code"=>"error", "msg"=>"操作失败");
		}
		
		$model->commit();
		return array("code"=>"ok","msg"=>"操作成功");
	
	}
	
	/**
	 * 查询单条记录
	 */
	function getById($id){
		$model = D();
		$sqlParam = array(
			array('name' => ':id', 'value' => $id, 'type' => PDO::PARAM_STR),
		);
		$sql ="select * from " . TABLE('usertable') . " where id=:id";
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
		$sql = "DELETE FROM " . TABLE('usertable') . " WHERE " . $where;
		if($model->execute($sql, $sqlParam)){
			return array("code"=>"ok","msg"=>"操作成功");
		}else{
			return array("code"=>"error","msg"=>"操作失败");
		}
	}
	
	function exportExcel(){
		$model = D();
		$sql = "select id,userid,username,password,mobile,status,last_login_time,create_login_time from " . TABLE('usertable') . $where . " order by id DESC";
		$list = $model->query($sql, $sqlParam)->limitPage($page1, $limit)->select();
		
		if(!empty($list)){
			
			foreach($list as $key=>$vo){
				
				$list[$key]['create_login_time'] = date("Y-m-d",$vo['create_login_time']);
				if($vo['status'] ==0){
					$list[$key]['status'] = '启用';
				}else{
					$list[$key]['status'] = '禁用';
				}
			}
		}
		
		//引入PHPExcel库文件
        include_once("SDK/PHPExcel/Classes/PHPExcel.php");
        //创建对象
        $objPHPExcel = new PHPExcel();
        
		$objPHPExcel->getProperties()->setCreator("ctos")
            ->setLastModifiedBy("ctos")
            ->setTitle("Office 2007 XLSX Test Document")
            ->setSubject("Office 2007 XLSX Test Document")
            ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
            ->setKeywords("office 2007 openxml php")
            ->setCategory("Test result file");

        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(50);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(50);
		$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(50);


        //设置水平居中
        $objPHPExcel->getActiveSheet()->getStyle('A')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('B')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyle('C')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('D')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('E')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyle('F')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyle('G')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyle('H')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);


        // set table header content
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', '编号')
            ->setCellValue('B1', '登录账号')
			->setCellValue('C1', '名称')
            ->setCellValue('D1', '密码')
            ->setCellValue('E1', '电话')
            ->setCellValue('F1', '账号状态')
			->setCellValue('G1', '上次登录时间')
			->setCellValue('H1', '创建账号时间');


        // Miscellaneous glyphs, UTF-8
        for($i=0;$i<count($list);$i++){
			$objPHPExcel->getActiveSheet(0)->setCellValue('A'.($i+3), $list[$i]['id']);
			$objPHPExcel->getActiveSheet(0)->setCellValue('B'.($i+3), $list[$i]['userid']);
			$objPHPExcel->getActiveSheet(0)->setCellValue('C'.($i+3), $list[$i]['username']);
			$objPHPExcel->getActiveSheet(0)->setCellValue('D'.($i+3), $list[$i]['password']);
			$objPHPExcel->getActiveSheet(0)->setCellValue('E'.($i+3), $list[$i]['mobile']);
			$objPHPExcel->getActiveSheet(0)->setCellValue('F'.($i+3), $list[$i]['status']);
			$objPHPExcel->getActiveSheet(0)->setCellValue('G'.($i+3), $list[$i]['last_login_time']);
			$objPHPExcel->getActiveSheet(0)->setCellValue('H'.($i+3), $list[$i]['create_login_time']);
			
            //$objPHPExcel->getActiveSheet()->getRowDimension($i+3)->setRowHeight(16);
        }


        // sheet命名
        $objPHPExcel->getActiveSheet()->setTitle('账户表');


        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $objPHPExcel->setActiveSheetIndex(0);


        // excel头参数
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="账户表('.date('Ymd-His').').xls"');
		// 日期为文件名后缀
        header('Cache-Control: max-age=0');

        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');  //excel5为xls格式，excel2007为xlsx格式

        $objWriter->save('php://output');
	}
	
}
