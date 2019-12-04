<?
class mainModelClass
{
	public function __construct()//构造函数
	{
		
	}
	
	public function setLogin($param)
	{
        if($param['SESSION']){
            session_id($param['SESSION']);
        }
		session_start(); 
		session_unset();
		$_SESSION['LOGIN_SYSTEM'] = 'T'; 
		$model = D_SYS();
		$username = $param['username'];
		$password = $param['password'];
		$_SESSION['LOGIN_SYSTEM_ID'] = 'system';
		$_SESSION['PREFIX'] = '';
		$model = D();
		if(!$row = $model->query("select user_no,user_name,password,status,is_operator,user_type from userlist where user_no=:user_no", array(array('name' => ':user_no', 'value' => $username, 'type' => PDO::PARAM_STR)))->find())
		{
			//正常登录
			$data = array(
				'system_id' => $_SESSION['LOGIN_SYSTEM_ID'],
				'user_id' => $param['username'],
				'STATUS' => 0,
				'remark' => '用户名或密码错误1',
			);
			$this -> userLoginLog($data);
			return array('code' => 'error', 'msg' => '用户名或密码错误1');
		}
		if($row['is_operator'] == '0')
		{
			$data = array(
				'system_id' => $_SESSION['LOGIN_SYSTEM_ID'],
				'user_id' => $param['username'],
				'STATUS' => 0,
				'remark' => '不是操作员帐号',
			);
			$this -> userLoginLog($data);
			return array('code' => 'error', 'msg' => '不是操作员帐号');		
		}
		if($row['status'] == '0')
		{
			$data = array(
				'system_id' => $_SESSION['LOGIN_SYSTEM_ID'],
				'user_id' => $param['username'],
				'STATUS' => 0,
				'remark' => '账户已经被禁用',
			);
			$this -> userLoginLog($data);
			return array('code' => 'error', 'msg' => '账户已经被禁用');		
		}
		if(md5($username.$password) != $row['password'] )
		{
			$data = array(
				'system_id' => $_SESSION['LOGIN_SYSTEM_ID'],
				'user_id' => $param['username'],
				'STATUS' => 0,
				'remark' => '用户名或密码错误!',
			);
			$this -> userLoginLog($data);
			return array('code' => 'error', 'msg' => '用户名或密码错误!');

		}

		$_SESSION['LOGIN_USER_ID'] = $username;
		
		$_SESSION['LOGIN_USERNAME'] = $row['user_name'];
		
		
		$this -> userLoginLog($data);
		if($param['isMobile'] == '1'){	//移动端版本信息
			return array('code' => 'ok', 'msg' => '登录成功', 'data' => $mobileData);
		}else{
			return array('code' => 'ok', 'msg' => '登录成功');
		}
	}
	
	public function setOmsLogin($param)
	{
        if($param['SESSION']){
            session_id($param['SESSION']);
        }
		session_start(); 
		session_unset();
		$_SESSION['LOGIN_SYSTEM'] = 'F'; 
		$model = D_SYS();
		$dealer_no = $param['dealer_no']?$param['dealer_no']:$param['dealer'];
		$username = $param['username'];
		$password = $param['password'];

		if(!$row = $model->query("select dealer_no,dealer_name,status from dealer where dealer_no=:dealer_no", array(array('name' => ':dealer_no', 'value' => $dealer_no, 'type' => PDO::PARAM_STR)))->find())
		{
			$data = array(
				'system_id' => $dealer_no,
				'user_id' => $username,
				'STATUS' => 0,
				'remark' => '无效的经销商编号',
			);
			$this -> userLoginLog($data);
			return array('code' => 'error', 'msg' => '无效的经销商编号');
		}

        $_SESSION['DEALER_NAME'] = $row['dealer_name'];

		if($row['status'] == '1'){
			$data = array(
				'system_id' => $dealer_no,
				'user_id' => $username,
				'STATUS' => 0,
				'remark' => '您的系统已被停用',
			);
			$this -> userLoginLog($data);
			return array('code' => 'error', 'msg' => '您的软件无法登录,请联系开发商审核');	
		}

		$_SESSION['LOGIN_SYSTEM_ID'] = $dealer_no;
		$_SESSION['PREFIX'] = 'z'.$dealer_no.'_';
		$_SESSION['LOGIN_UNIQUE_PREFIX'] = $row['id'];
		$model = D();
		if(!$row = $model->query("select username,password,status,type from ".TABLE('usertable')." where userid=:userid", array(array('name' => ':userid', 'value' => $username, 'type' => PDO::PARAM_STR)))->find())
		{

			//正常登录
			$data = array(
				'system_id' => $_SESSION['LOGIN_SYSTEM_ID'],
				'user_id' => $param['username'],
				'STATUS' => 0,
				'remark' => '用户名或密码错误1',
			);
			//$this -> userLoginLog($data);
			return array('code' => 'error', 'msg' => '用户名或密码错误1');
		}
		if($row['status'] == '1')
		{

			$data = array(
				'system_id' => $_SESSION['LOGIN_SYSTEM_ID'],
				'user_id' => $param['username'],
				'STATUS' => 0,
				'remark' => '账户已经被禁用',
			);
			$this -> userLoginLog($data);
			return array('code' => 'error', 'msg' => '账户已经被禁用');		
		}
		if(md5($username.$password) != $row['password'] )
		{

			$data = array(
				'system_id' => $_SESSION['LOGIN_SYSTEM_ID'],
				'user_id' => $param['username'],
				'STATUS' => 0,
				'remark' => '用户名或密码错误!',
			);
			$this -> userLoginLog($data);
			return array('code' => 'error', 'msg' => '用户名或密码错误!');

		}

		$_SESSION['LOGIN_USER_ID'] = $username;
		$_SESSION['LOGIN_USERNAME'] = $row['username'];

		
		$this -> userLoginLog($data);
		if($param['isMobile'] == '1'){	//移动端版本信息
			$mobileData = array(
				'WMS_MODEL' => $_SESSION['WMS_MODEL'],
				'SYNC_FA' => $UNIQUE_CODE['DROP_SHIPPING_SYNC'],
				'SYNC_NA' => $UNIQUE_CODE['ASSIST_CUST'],
			);
			return array('code' => 'ok', 'msg' => '登录成功', 'data' => $mobileData);
		}else{
			return array('code' => 'ok', 'msg' => '登录成功');
		}
	}
	
	//记录登陆IP地址
	public function userLoginLog($data){
		/*$model = D_SYS();
		$nowip = ip();
		if($data['STATUS'] == 1){
			$remark = '登陆IP地址：'.$nowip;
		}else{
			$remark = $data['remark'];
		}
		$sqlParam = array(
			array('name' => ':system_id', 'value' => $data['system_id'], 'type' => PDO::PARAM_STR),
			array('name' => ':user_id', 'value' => $data['user_id'], 'type' => PDO::PARAM_STR),
			array('name' => ':login_time', 'value' => time(), 'type' => PDO::PARAM_STR),
			array('name' => ':STATUS', 'value' => $data['STATUS'], 'type' => PDO::PARAM_STR),
			array('name' => ':remark', 'value' => $remark, 'type' => PDO::PARAM_STR),
		);
		$sql = "SELECT 1 FROM userlist WHERE system_id = :system_id";
		$row = $model->query($sql,$sqlParam)->find();
		if($row){
			$sql = "INSERT INTO user_login_log (system_id,user_id,login_time,STATUS,remark) 
					VALUES (:system_id,:user_id,:login_time,:STATUS,:remark)";
			$model->execute($sql, $sqlParam);
		}
		*/
		//M('accountSafe')->ychLogin($data['STATUS'],$remark);
	}
	
	
	
	public function getCode($phone)
	{
		if(S($phone)){
			$arr = S($phone);
			$phone = $arr['phone'];
			$code = $arr['code'];
			return array("phone"=>$phone,"code"=>$code);
		}else{
			return array("phone"=>$phone,"code"=>"error");
		}
		
	}
	
	
}
?>