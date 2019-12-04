<?
class handingTeamControllerClass extends Controller{
	public $model;
	public function __construct(){
		parent::__construct();
		$this->model = M('handingTeam');
	}
    public function index(){
        $this->userActionLimitCheck(array(240202));
        $this->display();
    }
	/**
	 * 获取列表
	 */
	function getList(){
		$result = $this->model->getList($_REQUEST);
		echo json_encode($result);
	}
	
	/*
	 * 新增
	 */
	function add(){
        $this->userActionLimitCheck(array(240201));
		if($_SERVER['REQUEST_METHOD'] == 'POST'){
			$data = $_POST['data'];
			//print_r($data);exit;
			$data = json_decode($data, true);
			$return = $this->model->saveAdd($data);
			echo json_encode($return);
		}else{
			$this->display();
		}
	}
	/*
	 * 编辑页面
	 */
	function edit(){
        $this->userActionLimitCheck(array(240203));
		if($_SERVER['REQUEST_METHOD'] == 'POST'){
			$data = $_POST['data'];
			$data = json_decode($data, true);
			$return = $this->model->saveEdit($data);
			echo json_encode($return);
		}else{
			$id = intval($_REQUEST['id']);
			$show = $this->model->getById($id);
			$this->assign('show', $show);
			$this->display();
		}
	}

/*
 * 查看页面
 */
    function view(){
        $this->userActionLimitCheck(array(240202));
        $id = intval($_REQUEST['id']);
        $show = $this->model->getById($id);
        $this->assign('show', $show);
        $this->display();
    }
	/**
	 * 删除
	 */
	function del(){
        $this->userActionLimitCheck(array(240204),true);
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
	
	/**
	 * 获取装卸队列表
	 */
	function handingList(){
		$this->display();
	}
	function getHandingList(){
		$data = $this->model->getHandingList($_REQUEST);
		echo json_encode($data);
	}
	
	//导出EXCEL
	public function excel(){
		
		$this->model->exportExcel();
		
	}
	
}
?>