<?
class promotionControllerClass extends Controller{
	public $model;
	public function __construct(){
		parent::__construct();
		$this->model = M('promotion');
	}
    public function index(){
        $this->userActionLimitCheck(array(110302));
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
        $this->userActionLimitCheck(array(110301));
		if($_SERVER['REQUEST_METHOD'] == 'POST'){
			$data	= $_POST['data'];
			$data2 	= $_POST['data2'];
			$data 	= json_decode($data, true);
			$data2 	= json_decode($data2, true);
			
			$return = $this->model->saveAdd($data, $data2);
			echo json_encode($return);
		}else{
			$this->display();
		}
	}
	/*
	 * 编辑页面
	 */
	function edit(){
        $this->userActionLimitCheck(array(110303));
		if($_SERVER['REQUEST_METHOD'] == 'POST'){
			$data	= $_POST['data'];
			$data 	= json_decode($data, true);
			$data2 	= $_POST['data2'];
			//print_r($data);
			//print_r($data2);exit;
			
			$return = $this->model->saveEdit($data, $data2);
			echo json_encode($return);
		}else{
			$id = intval($_REQUEST['id']);
			$show = $this->model->getById($id);
			//print_r($show);eixt;
			$this->assign('show', $show);
			
			$this->display();
		}
	}

    /*
     * 查看页面
     */
    function view() {
        $this->userActionLimitCheck(array(110302));
        $id = intval($_REQUEST['id']);
        $show = $this->model->getById($id);
        //print_r($show);eixt;
        $this->assign('show', $show);
        $this->display();
    }
	/**
	 * 获取 表 stock_items 表数据
	 */
	function getItemList(){
		$result = $this->model->getItemList($_REQUEST);
		echo json_encode($result);
	}
	
	
	/**
	 * 删除
	 * @return json
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
	
	/**
	 * 是否倍数赠送 0不是 1是
	 */
	function mul_arr(){
		$array = array(
			array('id'=>'all', 'text'=>''),
			array('id'=>0, 'text'=>'不是'),
			array('id'=>1, 'text'=>'是')
		);
		echo json_encode($array);
	}
		
	
}
?>