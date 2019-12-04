<?
class areaControllerClass extends Controller{
	public $model;
	public function __construct(){
		parent::__construct();
		$this->model = M('area');
	}
    public function index(){
		$this->display();
    }
	//编辑
	function edit(){
		$data = $_POST['data'];
		$data = json_decode($data, true);
		if($_SERVER['REQUEST_METHOD'] == 'POST'){
			$data = $_POST['data'];
			$data = json_decode($data, true);
			$return = $this->model->edit($data[0]);
			echo json_encode($return);
		}else{
			$area_no = $_REQUEST['area_no'];
			$area = $this->model->findByNo($area_no);
			$this->assign('area', $area);
			$this->display();
		}
	}
	
	// 删除
	function delArea(){
		$area_no = $_REQUEST['area_no'];
		$rs = $this->model->delArea($area_no);
		echo json_encode($rs);
	}
	
}
?>