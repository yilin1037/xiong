<?
class handingTeamModelClass extends CommonModelClass
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
		
		$sortField = $params['sortField'];
		$sortOrder = $params['sortOrder'];
		
		if(!empty($sortField)){
			if ($sortOrder != "desc") $sortOrder = "asc";
			$order = " order by " . $sortField . " " . $sortOrder;
		}
		
		$sqlParam = array(
						array('name' => ':handing_name', 'value' => '%'.$params['handing_name']."%", 'type' => PDO::PARAM_STR),
						array('name' => ':linkman', 'value' => '%'.$params['linkman']."%", 'type' => PDO::PARAM_STR),
						array('name' => ':mobile', 'value' => '%'.$params['mobile']."%", 'type' => PDO::PARAM_STR),
					);
						
		$where = " WHERE 1=1 ";
		if(!empty($params['handing_name'])){
			$where .= " AND handing_name like :handing_name";
		}
		if(!empty($params['linkman'])){
			$where .= " AND linkman like :linkman";
		}
		if(!empty($params['mobile'])){
			$where .= " AND mobile like :mobile";
		}
		
		//print_r($where);exit;
		$sql = "select id,handing_no,handing_name,linkman,mobile,remark from handing_team ". $where . $order;
		$result = $model->query($sql, $sqlParam)->limitPage($page1, $limit)->select();
		$sql = "select count(1) as count from handing_team ". $where;
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
		$data = array();
		$data['handing_no'] = $params['handing_no'];
		$data['handing_name'] = $params['handing_name'];
		$data['linkman'] = $params['linkman'];
		$data['mobile'] = $params['mobile'];
		$data['remark'] = $params['remark'];
		
		if (empty($data['handing_no']) || empty($data['handing_name']) || empty($data['linkman'])) {
			
			return array("code" => "error", "msg" => "编号、名称、联系人信息不能为空");
		}
		
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
		$sql = "insert into handing_team" . $fields . "
				values " . $fieldsValues;
		
		// handing_no 系统自动生成
		/* $sql = str_replace(':handing_no', "(select handing_no from (SELECT CONCAT('HANDING_',LPAD(RIGHT(IFNULL(MAX(handing_no),''),5)+1,5,'0')) as handing_no FROM handing_team) a)", $sql); */
		
		// 执行
		$result = $model->execute($sql, $sqlParam);
		//echo $model->getLastSql();exit;
		
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
		$data['handing_name'] = $params['handing_name'];
		$data['linkman'] = $params['linkman'];
		$data['mobile'] = $params['mobile'];
		$data['remark'] = $params['remark'];
		
		if (empty($data['handing_name']) || empty($data['linkman'])) {
			
			return array('code' => 'error', 'msg' => '名称、联系人信息不能为空');
		}
		
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
		$sql = "update handing_team SET " . $fieldsSet . ' WHERE id=:id ';
		// 执行
		$result = $model->execute($sql, $sqlParam);
		//echo $model->getLastSql();exit;
		
		if($result === false){
			return array("code"=>"error","msg"=>"操作失败");
		}else{
			return array("code"=>"ok","msg"=>"操作成功");
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
		$sql ="select * from handing_team where id=:id";
		$result = $model->query($sql,$sqlParam)->find();
		return $result;
	}
	
	/**
	 * 查询单条记录
	 */
	function getByHandingNo($handing_no, $fields='*'){
		$model = D();
		$sqlParam = array(
			array('name' => ':handing_no', 'value' => $handing_no, 'type' => PDO::PARAM_STR),
		);
		$sql ="select " . $fields ." from handing_team where handing_no=:handing_no";
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
		$sql = "DELETE FROM handing_team WHERE " . $where;
		$model->execute($sql, $sqlParam);
		if($model->execute($sql, $sqlParam)){
			return array("code"=>"ok","msg"=>"操作成功");
		}else{
			return array("code"=>"error","msg"=>"操作失败");
		}
	}
	
	/**
	 * 获取装卸队列表
	 * @return array
	 */
	function getHandingList($params){
		$params = array_map(function($value){return htmlspecialchars(trim($value));}, $params);
		$model = D();
		$where = ' WHERE 1=1 ';
		if(!empty($params['key'])){
			$where .= " AND handing_name LIKE '%". $params['key'] ."%'";
		}
		$sql = "select handing_no,handing_name,linkman from handing_team" . $where;
		//echo $sql;exit;
		$result = $model->query($sql)->select();
		return array(
			"code" => "0",
            "msg" =>"",
            "total" =>$count['count'],
            "data" =>$result
		);
	}
	
	/**
	 * 导出
	 */
	function exportExcel(){
		$model = D();
		$sql = "select id,dealer_no,dealer_name,mnem_code,link_man,link_tel,link_mobile,bank_name,bank_no,province,city,district,address,remark from dealer ". $where . " order by dealer_no DESC";
		$list = $model->query($sql, $sqlParam)->limitPage($page1, $limit)->select();
		if(!empty($list)){
			foreach($list as $key=>$vo){
				$list[$key]['province'] = $vo['province'].'/'.$vo['city'].$vo['district'] .' '. $vo['address'];
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


        //设置水平居中
        $objPHPExcel->getActiveSheet()->getStyle('A')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('B')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyle('C')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('D')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('E')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyle('F')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyle('G')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);


        // set table header content
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', '编号')
            ->setCellValue('B1', '经销商名称')
            ->setCellValue('C1', '详细地址')
            ->setCellValue('D1', '联系人')
            ->setCellValue('E1', '联系电话')
			->setCellValue('F1', '联系手机')
			->setCellValue('G1', '备注');


        // Miscellaneous glyphs, UTF-8
        for($i=0;$i<count($list);$i++){
			$objPHPExcel->getActiveSheet(0)->setCellValue('A'.($i+3), $list[$i]['dealer_no']);
			$objPHPExcel->getActiveSheet(0)->setCellValue('B'.($i+3), $list[$i]['dealer_name']);
			$objPHPExcel->getActiveSheet(0)->setCellValue('C'.($i+3), $list[$i]['province']);
			$objPHPExcel->getActiveSheet(0)->setCellValue('D'.($i+3), $list[$i]['link_man']);
			$objPHPExcel->getActiveSheet(0)->setCellValue('E'.($i+3), $list[$i]['link_tel']);
			$objPHPExcel->getActiveSheet(0)->setCellValue('F'.($i+3), $list[$i]['link_mobile']);
			$objPHPExcel->getActiveSheet(0)->setCellValue('G'.($i+3), $list[$i]['remark']);
			
            //$objPHPExcel->getActiveSheet()->getRowDimension($i+3)->setRowHeight(16);
        }


        // sheet命名
        $objPHPExcel->getActiveSheet()->setTitle('经销商表');


        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $objPHPExcel->setActiveSheetIndex(0);


        // excel头参数
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="经销商表('.date('Ymd-His').').xls"');
		// 日期为文件名后缀
        header('Cache-Control: max-age=0');

        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');  //excel5为xls格式，excel2007为xlsx格式

        $objWriter->save('php://output');
	}
	
	
}
