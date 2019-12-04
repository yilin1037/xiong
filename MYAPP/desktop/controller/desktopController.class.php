<?
include_once("js/plug-in/mini/UploadModel.class.php");
class desktopControllerClass extends Controller{

	public $desktop;

	public function __construct()
	{
		parent::__construct();
		$this->desktop = M('desktop');
	}
	
    public function index()
	{
		
		$menulist = $this->desktop->getMenu();//菜单
		$this->assign('menulist', $menulist);
		$this->display();
    }
}
?>