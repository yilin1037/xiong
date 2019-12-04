<?
class productPiControllerClass extends Controller{
	public $model;
	public function __construct(){
		parent::__construct();
		$this->model = M('productPi');
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
	 * 查看
	 */
    function view(){
		$this->userActionLimitCheck(array(220502,220502));
		$id = intval($_REQUEST['id']);
		$show = $this->model->getById($id);
		$this->assign('show', $show);
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
	 * 审核订单
	 * @return json
	 */
	function auditOk(){
		$this->userActionLimitCheck(array(220506,220506),true);
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
}
?>