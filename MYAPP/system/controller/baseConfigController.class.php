<?
class baseConfigControllerClass extends Controller{
	public $model;
	public function __construct(){
		parent::__construct();
		$this->model = M('baseConfig');
	}
    public function index(){
        $this->userActionLimitCheck(array(241102));
		$show = $this->model->getList($_REQUEST);
		//print_r($show);exit;
		$this->assign('show', $show);
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
		if($_SERVER['REQUEST_METHOD'] == 'POST'){
			//print_r($_REQUEST);exit;
			$data = $_POST['data'];
			$data = json_decode($data, true);
			$return = $this->model->saveEdit($data);
			echo json_encode($return);
		}else{
			print_r($_REQUEST);exit;
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
        $id = intval($_REQUEST['id']);
        $show = $this->model->getById($id);
        $this->assign('show', $show);
        $this->display();
    }
	/**
	 * 删除
	 */
	function del(){
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
}
?>