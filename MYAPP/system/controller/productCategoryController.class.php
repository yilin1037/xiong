<?
class productCategoryControllerClass extends Controller{
	public $model;
	public function __construct(){
		parent::__construct();
		$this->model = M('productCategory');
	}

    public function index(){
        $this->userActionLimitCheck(array(240502));
		$this->display();
    }
	/**
	 * 分类列表
	 */
	function getList(){
		$data = $this->model->getList($_REQUEST);
        echo json_encode($data);
	}

	// 保存
	function save(){
        $this->userActionLimitCheck(array(240503),true);
		$data = $_POST['data'];
		$data = json_decode($data, true);
		if(empty($data[0]['id'])){ // Add
			$return = $this->model->add($data[0]);
		}else{
			$return = $this->model->save($data[0]);
		}
        echo json_encode($return);
	}

	/**
	 * 删除
	 */
	function del() {
        $this->userActionLimitCheck(array(240504),true);
		$id = intval($_REQUEST['id']);
		$return = $this->model->del($id);
		//echo json_encode($return);
	}

	/**
	 * 不分页分类列表
	 */
	function getProductList(){
		$data = $this->model->getProductList($_REQUEST);
        echo json_encode($data);
	}
	
	/**
	 * 分类列表
	 */
	function getSearchList(){
		$data = $this->model->getSearchList($_REQUEST);
        echo json_encode($data);
	}
}
?>