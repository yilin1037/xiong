<?
class deptControllerClass extends Controller{
	public $model;
	public function __construct(){
		parent::__construct();
		$this->model = M('dept');
	}
    public function index(){
        $this->userActionLimitCheck(array(240802));
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
	function add() {
        $this->userActionLimitCheck(array(240801));
		if($_SERVER['REQUEST_METHOD'] == 'POST'){
			$data = $_POST['data'];
			$data = json_decode($data, true);
			$return = $this->model->add($data);
			echo json_encode($return);
		}else{
			$this->display();
		}
	}
	/**
	 * 获取部门列表
	 */
	function getDeptTopList(){
		$result = $this->model->getListByCondition(" parent_dept_no='' ");
		array_unshift($result, array('id'=>'','dept_no'=>'', 'dept_name'=>'顶级分类'));
        echo json_encode($result);
	}
	/*
	 * 编辑页面
	 */
	function edit(){
        $this->userActionLimitCheck(array(240803));
		if($_SERVER['REQUEST_METHOD'] == 'POST'){
			$data = $_POST['data'];
			$data = json_decode($data, true);
			$return = $this->model->edit($data);
			echo json_encode($return);
		}else{
			$id = intval($_REQUEST['id']);
			$show = $this->model->getById($id);
			if(!empty($show['parent_dept_no'])){
				$parent = $this->model->getListByCondition(" dept_no='". $show['parent_dept_no'] ."' ");
				$this->assign('parent', $parent[0]);
			}
			$this->assign('show', $show);
			$this->display();
		}
	}
	/**
	 * 删除
	 * @return json
	 */
	function del(){
        $this->userActionLimitCheck(array(240804),true);
		$ids = htmlspecialchars($_REQUEST['id']);
		$return = $this->model->del($ids);
		echo json_encode($return);
	}
}
?>