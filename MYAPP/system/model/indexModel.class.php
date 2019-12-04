<?
class indexModelClass extends CommonModelClass
{
	public function __construct()//构造函数
	{
		parent::__construct();
	}
	
	public function getShopConfigs(){
        $shopArray = parent::getShopConfig();//取店铺设置
        return $shopArray;
    }
	
	public function getMenu(){//获取菜单
		if($_SESSION['LOGIN_SYSTEM'] == 'T')
		{
			$menu = parent::menuList();	
		}
		else
		{
			$menu = parent::omsMemuList();	
		}
		return $menu;
	}
	
	public function getMenuLimit(){//获取菜单权限
		$menulimit =  parent::getUserLimit();
		return $menulimit;
	}
	
    public function getUserConfig(){
        $model = D_SYS();
		$result = array();
		$sql = "select username,expire_time from userlist where system_id=".'\''.$_SESSION['LOGIN_SYSTEM_ID'].'\'';
		$row = $model->query($sql)->find();
        $result['username'] = $row['username'];
        $result['expire_time'] = date('Y-m-d',$row['expire_time']);
        return $result;
    }
    public function outLogin(){
        session_start();
        $_SESSION['LOGIN_DBNAME'] = '';
        $_SESSION['LOGIN_USER_ID'] = '';
        return array();
    }
    public function lastGetOrderTime(){
        $model = D();
		$result = array();
		
		$limit_ddfh = "T";//打单发货权限
		$userlimit = $this->getMenuLimit();
		if(is_array($userlimit)){
			if(!$userlimit['0001_001']){
				$limit_ddfh = 'F';
			}
		}
		
        $sql = "SELECT MIN(down_time) AS down_time  FROM ".TABLE('shop_config')." WHERE `status`='1'";
		$result = $model->query($sql)->find();
		if($result['down_time'] == "0"){
			$down_time = "[暂未刷单]";
		}else{
			$down_time = date('Y-m-d',$result['down_time']);
		}
		
        $result = array();
        $result['configValue'] = $down_time;
		$result['limit_ddfh'] = $limit_ddfh;
        return $result;
    }
    //微商城状态sql
    public function mallShopStatus(){
        $model = D_SYS();
        $result = array();
        $sql = "SELECT expire_time FROM servicelist WHERE `system_id`=".$_SESSION['LOGIN_SYSTEM_ID'];
        $result = $model->query($sql)->find();
        if($result['expire_time'] - time() < 0){
            $status = 1;
        }
        return $status;
    }
	
	//取待办事项状态
	public function getData()
	{
		$model = D();
		$user = $_SESSION['LOGIN_USER_ID'];
		$sqlParam = array(
			array('name' => ':user', 'value' => $user, 'type' => PDO::PARAM_STR),
		);
		
		$limit_ddfh = "T";//打单发货权限
		$limit_dbsx = "T";//代办事项权限
		$userlimit = $this->getMenuLimit();
		if(is_array($userlimit)){
			if(!$userlimit['0001_001']){
				$limit_ddfh = "F";//打单发货权限
			}
			if(!$userlimit['0010']){
				$limit_dbsx = "F";//打单发货权限
			}
		}
		
		$sql = "select count(*) AS shopBang from ".TABLE('shop_config')." where status = 1";
		$shop = $model->query($sql,$sqlParam)->find();
		$sql = "select count(*) AS labelBang from ".TABLE('user_printer')." where usr = :user AND type = 'unique_code' AND printer <> ''";
		$label = $model->query($sql,$sqlParam)->find();
		$sql = "select count(*) AS printBang from ".TABLE('user_printer')." where usr = :user AND type = 'waybill' AND printer <> ''";
		$print = $model->query($sql,$sqlParam)->find();
		$sql = "select count(*) AS express_no from ".TABLE('user_printer')." where usr = :user AND type = 'express_no' AND printer <> ''";
		$express_no = $model->query($sql,$sqlParam)->find();
		
		if($shop['shopBang'] > 0 && $label['labelBang'] > 0 && $print['printBang'] > 0 && $express_no['express_no'] > 0){
			return array("code"=>"ok", 'limit_ddfh' => $limit_ddfh, 'limit_dbsx' => $limit_dbsx, 'ORDER_APPROVAL' => $_SESSION['ORDER_APPROVAL']);
		}else{
			return array("code"=>"error", 'limit_ddfh' => $limit_ddfh, 'limit_dbsx' => $limit_dbsx, 'ORDER_APPROVAL' => $_SESSION['ORDER_APPROVAL']);
		}
	}
	
	 //弹出公告
    public function ggTimeShow($param){
        $model = D_SYS();
        $data = array();
		$id = $param['id'];
		if($id != ""){
			$where = " and id =".$id;
		}
        $sql = "SELECT id,title,content,begin_time FROM notice where '".time()."' BETWEEN begin_time AND end_time ".$where;
        $row = $model->query($sql)->select();
		if($row){
			foreach($row as $list){
				$data[] = array(
					'begin_time' => date('Y-m-d',$list['begin_time']),
					'id' => $list['id'],
					'title' => $list['title'],
					'content' => $list['content']
				);
			}
		}
        return $data;
    }
}
