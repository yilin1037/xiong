<?
class popupSelectControllerClass extends Controller{
	public $model;
	public function __construct(){
		parent::__construct();
		$this->model = M('popupSelect');
	}
    public function index(){
		$this->display();
    }

	function selectProduct(){
		$displayStock = intval($_REQUEST['displayStock']);
		$this->assign('request', $_REQUEST);
		if($displayStock == 1){
			$this->display('selectProductStock');
		}else{
			$this->display('selectProductNoStock');
		}
	}
	function getProductList() {
		// 是否显示库存 0不显示 1显示
		$displayStock = intval($_REQUEST['displayStock']);
		$result = $this->model->getProductList($_REQUEST, $displayStock);
        echo json_encode($result);
	}

    function getProductListApp(){
        // 是否显示库存 0不显示 1显示

        $result = $this->model->getProductListApp($_REQUEST);
        echo json_encode($result);
    }
	/**
	 * 选择商品 - 不显示库存
	 */
	function selectProductNoStock(){
		$this->display();
	}
	
	/**
	 * 选择经销商
	 * @param multiSelect int 是否多选 1多选 0单选
	 */
	function selectDealer(){
		$multiSelect 	= intval($_REQUEST['multiSelect']);
		$this->assign('multiSelect', $multiSelect);
		$this->display();
	}
	// 经销商列表
	function getDealerList(){
		$result = $this->model->getDealerList($_REQUEST);
        echo json_encode($result);
	}
	/**
	 * 选择客户grid
	 */
	function selectCustomer(){
		$this->display();
	}
	/**
	 * 获取客户数据列表
	 */
	function getCustomerList(){
		$result = $this->model->getCustomerList($_REQUEST);
        echo json_encode($result);
	}

    /**
     * 选择仓库已审核入库订单grid
     */
    function selectWarehouseOrderNum() {
        $this->display();
    }

    /**
     * 获取仓库已审核入库列表
     */
    function getWarehouseOrderNumList() {
        $result = $this->model->getCustomerList($_REQUEST);
        echo json_encode($result);
    }


	
}
?>