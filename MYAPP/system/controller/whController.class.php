<?
class whControllerClass extends Controller{
	public $model;
	public function __construct(){
		parent::__construct();
		$this->model = M('wh');
	}
    public function index(){
		$this->display();
    }
	/**
	 * 列表
	 */
	function getList(){
		$data = $this->model->getList($_REQUEST);
        echo json_encode($data);
	}
	// 保存
	function save(){
		$data = $_POST['data'];
		$data = json_decode($data, true);
		if(empty($data[0]['id'])){ // Add
			$return = $this->model->add($data[0]);
		}else{
			$return = $this->model->save($data[0]);
		}
        echo json_encode($data);
	}
	/**
	 * 删除
	 */
	function del(){
		$id = intval($_REQUEST['id']);
		$return = $this->model->del($id);
	}
	
	
	// 所有仓库列表
	function whList(){
		$return = $this->model->whList();
		echo json_encode($return);
	}
	
	// 添加页面
	function addAction(){
		if($_SERVER['REQUEST_METHOD'] == 'POST'){
			$data = $_POST['data'];
			$data = json_decode($data, true);
			$return = $this->model->add($data[0]);
			echo json_encode($return);
		}else{
			$area_no = $_REQUEST['area_no'];
			$area = $this->model->findAreaByNo($area_no);
			$this->assign('area', $area);
			$this->display();
		}
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
			$wh_no = $_REQUEST['wh_no'];
			$wh = $this->model->findWHByNo($wh_no);
			$this->assign('wh', $wh);
			$this->display();
		}
	}
	// 删除
	function delWH(){
		$wh_no = $_REQUEST['wh_no'];
		$rs = $this->model->delWH($wh_no);
		echo json_encode($rs);
	}
	
	
	// 添加区域
	function addArea(){
		if($_SERVER['REQUEST_METHOD'] == 'POST'){
			$data = $_POST['data'];
			$data = json_decode($data, true);
			$return = $this->model->addArea($data);
			echo json_encode($return);
		}else{
			$this->display();
		}
	}
	
	/**
	 * 新增订单获取列表
	 */
	function getwhList(){
		$data = $this->model->getwhList($_REQUEST);
        echo json_encode($data);
	}
	
}
?>