<?
class printModelClass extends CommonModelClass
{
	public function __construct(){
		parent::__construct();
	}
	
	/**
	 * 获取模板列表
	 */
	public function printModuleList($param){
		$data = array(
			array('module_type' => 'rukudingdan', 'module_name' => '入库订单', 'pid' => 0),
			array('module_type' => 'rukudan', 'module_name' => '入库单', 'pid' => 0),
			array('module_type' => 'chukudan', 'module_name' => '出库单', 'pid' => 0),
		);
		
		return $data;
	}
	/**
	 * 获取列表
	 */
	public function getList($param){
		$model = D();
		$sqlParam = array(
			array('name' => ':module_type', 'value' => $param['module_type'], 'type' => PDO::PARAM_STR),
		);
		$sql = "SELECT id,module_type,module_name,module_file,is_default from ". 'print_module' . " where module_type = :module_type ";
		$result = $model->query($sql, $sqlParam)->select();
		if($result){
			foreach($result as $key => $list){
				if($list['is_default'] == '1'){
					$result[$key]['is_default'] = '是';
				}else{
					$result[$key]['is_default'] = '';
				}
				$result[$key]['action'] = '<a href="javascript:designModule(\''.$list['module_type'].'\',\''.$list['module_file'].'\')">设计样式</a>&nbsp;&nbsp;&nbsp;<a href="javascript:defaultModule(\''.$list['id'].'\')">设置为默认模板</a>&nbsp;&nbsp;&nbsp;<a href="javascript:removeModule(\''.$list['id'].'\')">删除</a>&nbsp;&nbsp;&nbsp;<a href="index.php?m=system&c=print&a=downloadGrf&FileName='.$list['module_file'].'">导出</a>';
			}
			
			return $result;	
		}else{
			return array();
		}
	}
	/**
	 * 模板文件上传
	 */
	public function fileUpload($module_type, $module_name, $file_name){
		$model = D();
		$sqlParam = array(
			array('name' => ':module_type', 'value' => $module_type, 'type' => PDO::PARAM_STR),
			array('name' => ':module_name', 'value' => $module_name, 'type' => PDO::PARAM_STR),
			array('name' => ':module_file', 'value' => $file_name, 'type' => PDO::PARAM_STR),
		);
		
		$model->execute("insert into print_module(module_type, module_name, module_file)values(:module_type, :module_name, :module_file) ", $sqlParam);
	}
	/**
	 * 删除模板
	 */
	public function removeModule($param){
		$model = D();
		
		$sqlParam = array(
			array('name' => ':id', 'value' => $param['id'], 'type' => PDO::PARAM_STR),
		);
		
		$sql = "SELECT id,module_type,module_name,module_file from ". 'print_module' . " where id = :id ";
		if($result = $model->query($sql, $sqlParam)->find()){
			$file_dir = $_SERVER['DOCUMENT_ROOT'].'/prints/grf/'.$result['module_file'];
			if(file_exists($file_dir)){
				unlink($file_dir);
			}
			
			$model->execute("delete from print_module where id = :id ", $sqlParam);
		}
		
		return array('code' => 'ok');
	}
	/**
	 * 设置为默认模板
	 */
	public function defaultModule($param){
		$model = D();
		
		$sqlParam = array(
			array('name' => ':id', 'value' => $param['id'], 'type' => PDO::PARAM_STR),
		);
		
		$sql = "SELECT id,module_type,module_name,module_file from ". 'print_module' . " where id = :id ";
		if($result = $model->query($sql, $sqlParam)->find()){
			$model->execute("update print_module set is_default = 0  where module_type = '".$result['module_type']."' ", $sqlParam);
			$model->execute("update print_module set is_default = 1  where id = :id ", $sqlParam);
		}
	}
	/**
	 * 下载系统模板
	*/
	public function downloadModule($param){
		$model = D();
		
		$module_type = $param['module_type'];
		$nowtime = $_SERVER['REQUEST_TIME'];
		
		$system_file_name = $_SERVER['DOCUMENT_ROOT'] . '/prints/grf/' .$module_type . '.grf';
		$file_name = $module_type . '-' . $nowtime . '.grf';
		$file_path = $_SERVER['DOCUMENT_ROOT'] . '/prints/grf/' . $file_name;
		if (!copy($system_file_name, $file_path)) {
			return array('code' => 'error', 'msg' => '下载失败');
		}
		
		$sqlParam = array(
			array('name' => ':module_type', 'value' => $module_type, 'type' => PDO::PARAM_STR),
			array('name' => ':module_name', 'value' => '系统模板', 'type' => PDO::PARAM_STR),
			array('name' => ':module_file', 'value' => $file_name, 'type' => PDO::PARAM_STR),
		);
		
		$model->execute("insert into print_module(module_type, module_name, module_file)values(:module_type, :module_name, :module_file) ", $sqlParam);
		
		return array('code' => 'ok');
	}
}
