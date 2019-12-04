<?php

include_once("../SDK/PHPExcel/PHPExcelGetDataArr.php");
include_once("../SDK/PHPExcel/IOFactory.php");
include_once('../SDK/PHPExcel/Reader/Excel5.php');
include_once("../SDK/PHPExcel/PHPExcel.php");


class whLocControllerClass extends Controller{
	public $model;
	public function __construct(){
		parent::__construct();
		$this->model = M('whLoc');
	}
    public function index(){
        $this->userActionLimitCheck(array(240302));
		$this->display();
    }
	
	//选择保管员
	public function user(){
		$this->display();
	}
	
	/**
	 * 获取保管员列表
	 */
	function getUserList(){
		$result = $this->model->getUserList($_REQUEST);
        echo json_encode($result);
	}
	
	//选择经销商
	public function dealer(){
		$this->display();
	}

	
	/**
	 * 获取列表 - 货位管理
	 */
	function getList(){
		$result = $this->model->getList($_REQUEST);
        echo json_encode($result);
	}
	/*
	 * 新增货位管理
	 */
	function add(){
        $this->userActionLimitCheck(array(240301));
		if($_SERVER['REQUEST_METHOD'] == 'POST'){
			$data = $_POST['data'];
			$data = json_decode($data, true);
			$return = $this->model->saveAdd($data);
			echo json_encode($return);
		}else{
			$wh_no = $_REQUEST['wh_no'];
			$res = $this->model->getByWh($wh_no);
			$this->assign('res', $res);
			$this->display();
		}
	}
	/*
	 * 编辑货位管理
	 */
	function edit(){
        $this->userActionLimitCheck(array(240303));
		if($_SERVER['REQUEST_METHOD'] == 'POST'){
			$data = $_POST['data'];
			$data = json_decode($data, true);
			$return = $this->model->saveEdit($data[0]);
			echo json_encode($return);
		}else{
			$id = intval($_REQUEST['id']);
			$show = $this->model->getById($id);
			$this->assign('show', $show);
			
			$this->display();
		}
	}

    /*
     * 查看货位管理
     */
    function view(){
        $this->userActionLimitCheck(array(240302));
        $id = intval($_REQUEST['id']);
        $show = $this->model->getById($id);
        $this->assign('show', $show);
        $this->display();
    }
	/**
	 * 删除 - 货位管理
	 * @return json
	 */
	function del(){
        $this->userActionLimitCheck(array(240304),true);
		$idStr = htmlspecialchars($_REQUEST['id']);
		if (empty($idStr)) return;
		$ids = explode(',',$idStr);
		for ($i = 0, $l = count($ids); $i < $l; $i++)
		{
			$id = $ids[$i];
			$return = $this->model->del($id);
			
		}
		echo json_encode($return);
	}
	
	function addArea(){
		
	}


    public function whlocExcelInput(){
        $param = $_REQUEST;

        $Rname = strrchr($_FILES["file"]["name"],'.');
        $_FILES["file"]['name'] = microtime(true).$Rname;
        if ($_FILES["file"]["size"] < 5 * 1024 * 1024) {
            if ($_FILES["file"]["error"] > 0) {
                $result['code'] = 'error';
                $result['msg'] = $_FILES["file"]["error"];
                return $result;
            }else {
                $result = move_uploaded_file($_FILES["file"]["tmp_name"],$_SERVER['DOCUMENT_ROOT'] ."/tempfile/".$_FILES["file"]["name"]);
                if($result){
                    $filePath = $_SERVER['DOCUMENT_ROOT'] ."/tempfile/".$_FILES["file"]['name'];//文件名
                    $objReader = PHPExcel_IOFactory::createReader('Excel5');
                    $objPHPExcel = $objReader->load($filePath);

                    $objWorksheet = $objPHPExcel->getActiveSheet();
                    $highestRow = $objWorksheet->getHighestRow();

                    $result = $this->model->whlocExcelInput($param,$objWorksheet,$highestRow);
                    echo json_encode($result);
                }else{
                    $result['code'] = 'error';
                    $result['msg'] = '上传异常';
                    return $result;
                }
            }
        }else{
            $result['code'] = 'error';
            $result['msg'] = '上传文件大小超过5M';
            return $result;
        }
    }



	
}
?>