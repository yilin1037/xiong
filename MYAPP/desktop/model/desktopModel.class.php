<?
class desktopModelClass extends CommonModelClass
{
	public function __construct()//构造函数
	{
		parent::__construct();
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
}
?>