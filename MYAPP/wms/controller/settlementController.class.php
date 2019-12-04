<?
class settlementControllerClass extends Controller{
	public $model;
	public function __construct(){
		parent::__construct();
		$this->model = M('settlement');
	}
    public function index(){
        $this->userActionLimitCheck(array(230102));
		$this->display();
    }
	
	/**
	 * 获取列表 - 出库订单
	 */
	function getList(){
		$result = $this->model->getList($_REQUEST);
		echo json_encode($result);
	}
	
	function createSettlementList(){
		$result = $this->model->createSettlementList($_REQUEST);
		echo json_encode($result);
	}
	
	/**
	 * 获取订单日期
	 */
	/* function getDateList(){
		$result = $this->model->getDateList($_REQUEST);
		echo json_encode($result);
	} */
	
	/*
	 * 新增出库订单
	 */
	function add(){
        $this->userActionLimitCheck(array(230101));
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
	
	public function saveSettlement()
	{
        $this->userActionLimitCheck(array(230103));
		$return = $this->model->saveSettlement($_REQUEST);
		echo json_encode($return);
	}
	
	/**
	 * 删除 - 未审核的结算单
	 * @return json
	 */
	function del(){
        $this->userActionLimitCheck(array(230104),true);
		$idStr = htmlspecialchars($_REQUEST['id']);
		$return = $this->model->del($idStr);
		echo json_encode($return);
	}
	
	/**
	 * 审核订单
	 * @return json
	 */
	function examine(){
		$this->userActionLimitCheck(array(230105),true);
		$idStr = htmlspecialchars($_REQUEST['id']);
		if (empty($idStr)) return;
		$ids = explode(',',$idStr);
		for ($i = 0, $l = count($ids); $i < $l; $i++)
		{
			$id = $ids[$i];
			$return = $this->model->examine($id);
			if($return['code'] != 'ok')
			{
				echo json_encode($return);
				exit;	
			}
		}
		echo json_encode($return);
	}

	
}
?>