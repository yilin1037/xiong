<?
class moveOrderControllerClass extends Controller{
	public $model;
	public function __construct(){
		parent::__construct();
		$this->model = M('moveOrder');
	}
    public function index(){

        $this->userActionLimitCheck(array(220102,220202));
		$this->display();
    }
	
	/**
	 * 获取列表 - 出库订单
	 */
	function getList(){

		$result = $this->model->getList($_REQUEST);
		echo json_encode($result);
	}
	
	/*
	 * 新增
	 */
	function add(){
        $this->userActionLimitCheck(array(220101,220201));
		if($_SERVER['REQUEST_METHOD'] == 'POST'){
			$data	= $_POST['data'];
			$data2 	= $_POST['data2'];
			$data 	= json_decode($data, true);
			$data2 	= json_decode($data2, true);
			
			$return = $this->model->add($data, $data2);
			echo json_encode($return);
		}else{
			$this->display();
		}
	}
	/*
	 * 编辑
	 */
	function edit(){
        $this->userActionLimitCheck(array(220103,220203));
		if($_SERVER['REQUEST_METHOD'] == 'POST'){
			$data	= $_POST['data'];
			$data 	= json_decode($data, true);
			$data2 	= $_POST['data2'];
			//print_r($data);
			//print_r($data2);exit;
			
			$return = $this->model->edit($data, $data2);
			echo json_encode($return);
		}else{
			$id = intval($_REQUEST['id']);
			$show = $this->model->getById($id);
			$dealer = M('dealer')->getByDealerNo($show['dealer_no'], 'dealer_name');
			$this->assign('show', $show);
			$this->assign('dealer', $dealer);
			
			$this->display();
		}
	}

/*
 * 查看
 */
    function view(){
            $this->userActionLimitCheck(array(220102,220202));
            $id = intval($_REQUEST['id']);
            $show = $this->model->getById($id);
            $dealer = M('dealer')->getByDealerNo($show['dealer_no'], 'dealer_name');
            $this->assign('show', $show);
            $this->assign('dealer', $dealer);
            $this->display();
    }

	/**
	 * 获取
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
        $this->userActionLimitCheck(array(220104,220204),true);
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
	 * 审核订单
	 * @return json
	 */
	function auditOk(){
		$this->userActionLimitCheck(array(220105,220205),true);
		$idStr = htmlspecialchars($_REQUEST['id']);
		if (empty($idStr)) return;
		$ids = explode(',',$idStr);
		for ($i = 0, $l = count($ids); $i < $l; $i++)
		{
			$id = $ids[$i];
			$return = $this->model->auditOk($id);
		}
		echo json_encode($return);
	}
	
	/**
	 * 取消审核
	 * @return json
	 */
	function auditCancel(){
		$this->userActionLimitCheck(array(220106,220206),true);
		$idStr = htmlspecialchars($_REQUEST['id']);
		if (empty($idStr)) return;
		$ids = explode(',',$idStr);
		for ($i = 0, $l = count($ids); $i < $l; $i++)
		{
			$id = $ids[$i];
			$return = $this->model->auditCancel($id);
			
		}
		echo json_encode($return);
	}
	
	// 删除表 stock_items 表 单条记录
	function delItem(){
		$ids = htmlspecialchars($_REQUEST['id']);
		$return = $this->model->delItem($ids);
		echo json_encode($return);
	}
	//显示仓库货位Tree页面
	function displayWhLoc(){
		$this->assign('request', $_REQUEST);
		$this->display();
	}
	// 获取仓库仓位Tree
	function getWhLocList(){
		$result = $this->model->getWhLocList($_REQUEST);
		echo json_encode($result);
	}
	
}
?>