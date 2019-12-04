<?
class userControllerClass extends Controller{
	public $model;
	public function __construct(){
		parent::__construct();
		$this->model = M('user');
	}
    public function index(){
        $this->userActionLimitCheck(array(240702));
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
		
        $this->userActionLimitCheck(array(240701));

		if($_SERVER['REQUEST_METHOD'] == 'POST'){
			$data = $_POST['data'];
            $data = json_decode($data, true);
            $return = $this->model->add($data);
            echo json_encode($return);

		}else{
			$this->display();
		}
	}
	/*
	 * 编辑页面
	 */
    function edit(){
        $this->userActionLimitCheck(array(240703));
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $data = $_POST['data'];
            $data = json_decode($data, true);
            $return = $this->model->edit($data);
            echo json_encode($return);
        }else{
            $id = intval($_REQUEST['id']);
            $show = $this->model->getById($id);

            $this->assign('show', $show);
            $this->display();
        }
    }

    /*
	 * 设置权限
	 */
    function permissionConfig() {

        $data = $_POST;

        if($_SERVER['REQUEST_METHOD'] == 'POST'){

            $return = $this->model->editPermission($data);
            echo json_encode($return);
        }else{

            $id = intval($_REQUEST['id']);
            $show = $this->model->getById($id);
            $menu = $this->model->menuList();
            $permission = explode(",", $show["permission"]);

            $this->assign('permission', $permission);
            $this->assign('show', $show);
            $this->assign('menu', $menu);
            $this->display();
        }
    }

	/*
	 * 移动端设置权限
	 */
    function permissionMobileConfig() {
		$this->userActionLimitCheck(array(240706));
		$id = intval($_REQUEST['id']);
		$show = $this->model->getById($id);
		$menu = $this->model->getMobileMenu();
		$permission = explode(",", $show["mobile_permission"]);

		$this->assign('permission', $permission);
		$this->assign('show', $show);
		$this->assign('menu', $menu);
		$this->display();
    }
	function editPermission(){
		$return = $this->model->editPermission($_REQUEST);
		echo json_encode($return);
	}
    /*
	 * 查看页面
	 */
    function view(){
        $this->userActionLimitCheck(array(240702));
        $id = intval($_REQUEST['id']);
        $show = $this->model->getById($id);
        $this->assign('show', $show);
        $this->display();
    }

	/**
	 * 删除
	 * @return json
	 */
	function del(){
        $this->userActionLimitCheck(array(240704),true);
		$ids = htmlspecialchars($_REQUEST['id']);
		$return = $this->model->del($ids);
		echo json_encode($return);
	}
	
	/**
	 * 获取业务员列表
	 * @return json
	 */
	function getSalesManList(){
		$data = $this->model->getSalesManList();
		echo json_encode($data);
	}
	
	/**
	 * 客户类型列表
	 */
	function baseConfig(){
		$this->display();
	}
	function baseConfigList(){
		$data = $this->model->baseConfigList();
        echo json_encode($data);
	}
	// 保存 - 配置
	function saveBaseConfig(){
		$data = $_POST['data'];
		$data = json_decode($data, true);
		if(empty($data[0]['id'])){ // Add
			$return = $this->model->addBaseConfig($data[0]);
		}else{
			$return = $this->model->saveBaseConfig($data[0]);
		}
        echo json_encode($return);
	}
	/**
	 * 删除 - 配置
	 */
	function delBaseConfig(){
		$id = intval($_REQUEST['id']);
		$return = $this->model->delBaseConfig($id);
		//echo json_encode($return);
	}
	/**
	 * 获取移动端菜单权限
	 */
	function getMobileUserLimit(){
		$return = $this->model->getMobileUserLimit();
		echo json_encode($return);
	}
}
?>