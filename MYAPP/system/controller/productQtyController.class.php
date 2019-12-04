<?

include_once("../SDK/PHPExcel/PHPExcelGetDataArr.php");
include_once("../SDK/PHPExcel/IOFactory.php");
include_once('../SDK/PHPExcel/Reader/Excel5.php');
include_once("../SDK/PHPExcel/PHPExcel.php");


class productQtyControllerClass extends Controller{
	public $model;
	public function __construct(){
		parent::__construct();
		$this->model = M('productQty');
	}
    public function index(){
        $this->userActionLimitCheck(array(120102,220402));
		$this->display();
    }
	
	/**
	 * 获取列表 - 货位管理
	 */
	function getList(){
		$result = $this->model->getList($_REQUEST);
        echo json_encode($result);
	}
	
	
	//导入
	public function qtyExcelInput(){
		
		$param = $_REQUEST;
		
        $Rname = strrchr($_FILES["file"]["name"],'.');
        $_FILES["file"]['name'] = microtime(true).$Rname;
        // if ($_FILES["file"]["size"] < 5 * 1024 * 1024) {
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
					
                    $result = $this->model->qtyExcelInput($param,$objWorksheet,$highestRow);
				
                    echo json_encode($result);
                }else{
                    $result['code'] = 'error';
                    $result['msg'] = '上传异常';
                    return $result;
                }
            }
        // }else{
            // $result['code'] = 'error';
            // $result['msg'] = '上传文件大小超过5M';
            // return $result;
        // }
    }
	
	
	
	
	
	
	
	
	
	
	
	
}
?>