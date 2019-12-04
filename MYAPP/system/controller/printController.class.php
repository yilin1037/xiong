<?
class printControllerClass extends Controller{
	public $model;
	public function __construct(){
		parent::__construct();
		$this->model = M('print');
	}
	
    public function manage(){
        $this->userActionLimitCheck(array(240902));
		$this->display();
    }
	
	public function printModule(){
		$this->display();
    }
	
	/**
	 * 获取模板类型列表
	 */
	public function printModuleList(){
		$result = $this->model->printModuleList($_REQUEST);
		echo json_encode($result);
	}
	
	/**
	 * 获取列表
	 */
	public function getList(){
		$result = $this->model->getList($_REQUEST);
		echo json_encode($result);
	}
	/**
	 * 模板文件上传
	*/
	
	public function fileUpload(){
		$module_type = $_REQUEST['module_type'];
		$module_name = $_REQUEST['module_name'];
		$tmp_name = $_FILES['GRF_FIELD']['tmp_name'];
		$nowtime = $_SERVER['REQUEST_TIME'];
		$file_name = $module_type . '-' . $nowtime . '.grf';
		
		if(file_exists($tmp_name)){
			$file_path = $_SERVER['DOCUMENT_ROOT'] . '/prints/grf/' . $file_name;
			if (!copy($tmp_name, $file_path)) {
				echo '模板文件复制失败';
				exit;
			}else{
				$this->model->fileUpload($module_type, $module_name, $file_name);
			}
		}
	}
	/**
	 * 模板文件下载
	*/
	public function downloadGrf(){
		$file_name = $_GET['FileName'];
		$file_dir = $_SERVER['DOCUMENT_ROOT'].'/prints/grf/'.$file_name;
		
		$file = fopen($file_dir, "r"); // 打开文件
		// 输入文件标签
		Header("Content-type: application/octet-stream");
		Header("Accept-Ranges: bytes");
		Header("Accept-Length: ".filesize($file_dir));
		Header("Content-Disposition: attachment; filename=" . $file_name);
		// 输出文件内容
		echo fread($file,filesize($file_dir));
		fclose($file);
	}
	/**
	 * 模板文件删除
	*/
	public function removeModule(){
		$result = $this->model->removeModule($_REQUEST);
		echo json_encode($result);
	}
	/**
	 * 设置为默认打印模板
	*/
	public function defaultModule(){
		$result = $this->model->defaultModule($_REQUEST);
		echo json_encode($result);
	}
	/**
	 * 下载系统模板
	*/
	public function downloadModule(){
		$result = $this->model->downloadModule($_REQUEST);
		echo json_encode($result);
	}
}
?>