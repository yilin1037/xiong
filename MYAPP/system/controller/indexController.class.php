<?
class indexControllerClass extends Controller{
	public $indexModel;
	public function __construct()
	{
		parent::__construct();

		$this->indexModel = M('index');
	}
    public function index()
	{
		$userConfigObj = $this->indexModel->getUserConfig();
        //微商城状态
		
		$menulist = $this->indexModel->getMenu();//菜单
		$this->assign('menulist', $menulist);
		
		/*$menulimit = $this->indexModel->getMenuLimit();//菜单权限
		$this->assign('menulimit', $menulimit);*/
		
        $mallShopStatusObj = $this->indexModel->mallShopStatus();
		if($_SESSION['LOGIN_SYSTEM_ID'] == $_SESSION['LOGIN_USER_ID']){
			$this->assign('sys_user_name', $_SESSION['LOGIN_SYSTEM_ID']);	
		}else{
			$this->assign('sys_user_name', $_SESSION['LOGIN_USER_ID']);
		}
        $this->assign('mallShopStatusObj', $mallShopStatusObj);
        $this->assign('userConfigObj', $userConfigObj);
        $this->assign('chaoQunBalance', getChaoQunBalance());
        $this->assign('chaoQunGiftCount', getChaoQunGiftCount());
		$this->assign('chaoQunSMSBalance', getChaoQunSMSBalance());
		
		$this->display();
    }

    public function getShopConfig(){
        $shopConfigObj = $this->indexModel->getShopConfigs();
        $this->original($shopConfigObj);
    }
    public function outLogin(){
        $outLoginObj = $this->indexModel->outLogin();
        $this->original($outLoginObj);
    }
    public function lastGetOrderTime(){
        $lastGetOrderTimeObj = $this->indexModel->lastGetOrderTime();
        $this->original($lastGetOrderTimeObj);
    }
	public function getData()
	{
		$result = $this->indexModel->getData();
        echo json_encode($result);
	}
	public function ggTimeShow()
	{
		$result = $this->indexModel->ggTimeShow($_REQUEST);
        echo json_encode($result);
	}
   
}
?>