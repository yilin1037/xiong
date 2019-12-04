<?
class damageOrdersControllerClass extends Controller{
	public $model;
	public function __construct(){
		parent::__construct();
		$this->model = M('damageOrders');
	}
    public function index(){
        $this->userActionLimitCheck(array(220302));
		$this->display();
    }
	
	/**
	 * 获取列表 - 报损
	 */
	function getList(){
		$result = $this->model->getList($_REQUEST);
		echo json_encode($result);
	}
	
	/*
	 * 新增报损
	 */
	function add(){
        $this->userActionLimitCheck(array(220301));
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
        $this->userActionLimitCheck(array(220301));
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
			$cust = M('customer')->getByCustNo($show['cust_no'], 'cust_name');
			$dealer = M('dealer')->getByDealerNo($show['dealer_no'], 'dealer_name');
			$handing = M('handingTeam')->getByHandingNo($show['handing_no'], 'handing_name');
			//print_r($show);eixt;
			$this->assign('show', $show);
			$this->assign('cust', $cust);
			$this->assign('dealer', $dealer);
			$this->assign('handing', $handing);
			
			$this->display();
		}
	}

    /*
     * 查看页面
     */
    function view() {
            $this->userActionLimitCheck(array(220302));
            $id = intval($_REQUEST['id']);
            $show = $this->model->getById($id);
            $cust = M('customer')->getByCustNo($show['cust_no'], 'cust_name');
            $dealer = M('dealer')->getByDealerNo($show['dealer_no'], 'dealer_name');
            $handing = M('handingTeam')->getByHandingNo($show['handing_no'], 'handing_name');
            //print_r($show);eixt;
            $this->assign('show', $show);
            $this->assign('cust', $cust);
            $this->assign('dealer', $dealer);
            $this->assign('handing', $handing);
            $this->display();
    }
	/**
	 * 获取 表 damage_items 表数据
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
        $this->userActionLimitCheck(array(220304),true);
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
	function examine(){
		$this->userActionLimitCheck(array(220306),true);
		$idStr = htmlspecialchars($_REQUEST['id']);
		if (empty($idStr)) return;
		$ids = explode(',',$idStr);
		for ($i = 0, $l = count($ids); $i < $l; $i++)
		{
			$id = $ids[$i];
			$return = $this->model->examine($id);
			
		}
		echo json_encode($return);
	}
	
	/**
	 * 取消审核
	 * @return json
	 */
	function qxea(){
		$this->userActionLimitCheck(array(220307),true);
		$idStr = htmlspecialchars($_REQUEST['id']);
		if (empty($idStr)) return;
		$ids = explode(',',$idStr);
		for ($i = 0, $l = count($ids); $i < $l; $i++)
		{
			$id = $ids[$i];
			$return = $this->model->qxea($id);
			
		}
		echo json_encode($return);
	}
	
	// 删除表 damage_items 表 单条记录
	function delItem(){
		$ids = htmlspecialchars($_REQUEST['id']);
		$return = $this->model->delItem($ids);
		echo json_encode($return);
	}
	
	/**
	 * 审核状态 0未审核 1已审核
	 */
	function status_arr(){
		$array = array(
			array('id'=>0, 'text'=>'未审核'),
			array('id'=>1, 'text'=>'已审核')
		);
		echo json_encode($array);
	}
		
	
}
?>