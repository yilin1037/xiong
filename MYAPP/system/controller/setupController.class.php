<?

class setupControllerClass extends Controller
{
    public $setupModel;

    public function __construct()
    {
        parent::__construct();
        $this->setupModel = M('setup');
    }

    public function index()
    {
		parent::userLimitCheck('0009');//检验权限-----菜单ID
		$this->assign('CROSS_BORDER', $_SESSION['CROSS_BORDER']);//跨境设置
        $this->display();
    }
	
	public function base()
    {
		$this->assign('WMS_MODEL', $_SESSION['WMS_MODEL']);
        $this->display();
    }
	
	public function mobile()
    {
		$this->assign('WMS_MODEL', $_SESSION['WMS_MODEL']);
        $this->display();
    }
	
	public function wuliuRemark()
    {
        $this->display();
    }
	
	public function userOnline()
    {
        $this->display();
    }
	
    public function updatepassword()
    {
        $this->display();
    }
	
	public function printSet()
    {
        $this->display();
    }

    public function updatetel()
    {
        $this->display();
    }
	
	public function AGAutoSetup(){
		$this->display();
	}

    public function childAccount()
    {
		$menulist = M('system/index')->getMenu();//菜单
		$shoplist = $this->setupModel->getShopAllList();//店铺
		$funclist = $this->setupModel->getFuncAllList();//权限
		$this->assign('menulist', $menulist);
		$this->assign('shoplist', $shoplist);
		$this->assign('funclist', $funclist);
        $this->display();
    }

    public function boundDk()
    {
        $this->display();
    }

    public function boundShop()
    {
        $this->display();
    }

    public function dfShangjia()
    {
        $this->display();
    }

    public function dkSetup()
    {
        $this->display();
    }

    public function downlodCainiao()
    {
        $this->display();
    }

    public function dzMiandan()
    {
        $this->display();
    }

    public function groupCode()
    {
        $this->display();
    }

    public function tagEdit()
    {
        $this->display();
    }

    public function tagPrint()
    {
        $this->display();
    }

    public function wuliuSetup()
    {
		$this->assign('PDD_WHITE_LIST', $_SESSION['PDD_WHITE_LIST']);
        $this->display();
    }
	
	public function approvalSetup()
    {
        $this->display();
    }

    public function logs()
    {
        $this->display();
    }

    public function sendGoods()
    {
        $this->display();
    }
	
	public function storage()
    {
        $this->display();
    }

    public function setPass()
    {
        $result = $this->setupModel->setPass($_REQUEST);
        echo json_encode($result);
    }

    public function getChildAccount()
    {
        $result = $this->setupModel->getChildAccount($_REQUEST);

        echo json_encode($result);

    }

    public function delChildAccount()
    {

        $result = $this->setupModel->delChildAccount($_REQUEST);

        echo json_encode($result);

    }

    public function addChildAccount()
    {

        $result = $this->setupModel->addChildAccount($_REQUEST);

        echo json_encode($result);

    }

    public function editChildAccount()
    {

        $result = $this->setupModel->editChildAccount($_REQUEST);

        echo json_encode($result);

    }

    public function getExpressTaobao()
    {
        $result = $this->setupModel->getExpressTaobao($_REQUEST);

        echo json_encode($result);

    }
	
	public function getExpressJdwj(){
		$result = $this->setupModel->getExpressJdwj($_REQUEST);

        echo json_encode($result);
	}
	
	public function getExpressPdd(){
		$result = $this->setupModel->getExpressPdd($_REQUEST);

        echo json_encode($result);
	}
	
	public function getExpressJd()
    {
        $result = $this->setupModel->getExpressJd($_REQUEST);

        echo json_encode($result);

    }
	
	public function getExpressJdYth()
    {
        $result = $this->setupModel->getExpressJdYth($_REQUEST);

        echo json_encode($result);

    }
	
	public function getExpressJdYthCod()
    {
        $result = $this->setupModel->getExpressJdYthCod($_REQUEST);

        echo json_encode($result);

    }
	
	public function getExpresszjzto()
    {
        $result = $this->setupModel->getExpresszjzto($_REQUEST);

        echo json_encode($result);

    }
	
	public function getExpresszjsf()
    {
        $result = $this->setupModel->getExpresszjsf($_REQUEST);

        echo json_encode($result);

    }

    public function getExpress()
    {
        $result = $this->setupModel->getExpress($_REQUEST);

        echo json_encode($result);

    }
	
	
    public function setstatus()
	{
        $result = $this->setupModel->setstatus($_REQUEST);

        echo json_encode($result);

    }
    public function deleteExpress()
	{
        $result = $this->setupModel->deleteExpress($_REQUEST);

        echo json_encode($result);

    }
	
	public function recoveryExpress()
	{
        $result = $this->setupModel->recoveryExpress($_REQUEST);

        echo json_encode($result);

    }
	
    public function getshoplist()
	{
        $result = $this->setupModel->getshoplist($_REQUEST);

        echo json_encode($result);

    }
    public function updateExpress()
	{
        $result = $this->setupModel->updateExpress($_REQUEST);

        echo json_encode($result);

    }
    public function getdetail()
	{
        $result = $this->setupModel->getdetail($_REQUEST);

        echo json_encode($result);

    }
    public function updatecity()
	{
        $result = $this->setupModel->updatecity($_REQUEST);
        echo json_encode($result);
    }
	public function updatecityon()
	{
        $result = $this->setupModel->updatecityon($_REQUEST);
        echo json_encode($result);
    }
	
    public function getcitys()
	{
        $result = $this->setupModel->getcitys($_REQUEST);

        echo json_encode($result);

    }
	public function getcityson()
	{
        $result = $this->setupModel->getcityson($_REQUEST);
        echo json_encode($result);
    }
	
	public function getReplaceExpress(){
		$result = $this->setupModel->getReplaceExpress($_REQUEST);

        echo json_encode($result);
	}
	
	//更新排序插入
	public function insertLabel()
	{
        $result = $this->setupModel->insertLabel($_REQUEST['data']);

        echo json_encode($result);

    }
	
	//获取排序插入数据
	public function getLabel()
	{
        $result = $this->setupModel->getLabel();

        echo json_encode($result);

    }
	//获取群单码list
	public function getgroupcode()
	{
        $result = $this->setupModel->getgroupcode();

        echo json_encode($result);

    }
	//获取群单码list
	public function savegroupcode()
	{
        $result = $this->setupModel->savegroupcode($_REQUEST);

        echo json_encode($result);

    }
	
	public function getPhone()
	{
        $result = $this->setupModel->getPhone($_REQUEST);

        echo json_encode($result);

    }
	
	public function resetPhone()
	{
		$result = $this->setupModel->resetPhone($_REQUEST);

        echo json_encode($result);
	}
	
	public function savePrint()
	{
		$result = $this->setupModel->savePrint($_REQUEST);

        echo json_encode($result);
	}
	
	public function getPrint()
	{
		$result = $this->setupModel->getPrint();

        echo json_encode($result);
	}

	public function saveStorageType(){
		$result = $this->setupModel->saveStorageType($_REQUEST);

        echo json_encode($result);
	}
	
	public function getStorageType(){
		$result = $this->setupModel->getStorageType();

        echo json_encode($result);
	}
	
	public function saveApproval(){
		$result = $this->setupModel->saveApproval($_REQUEST);

        echo json_encode($result);
	}
	
	public function getApproval(){
		$result = $this->setupModel->getApproval();

        echo json_encode($result);
	}
	
	public function getBaseConfig(){
		$result = $this->setupModel->getBaseConfig();

        echo json_encode($result);
	}
	
	public function saveBaseConfig(){
		$result = $this->setupModel->saveBaseConfig($_REQUEST);

        echo json_encode($result);
	}
	
	public function getMobileConfig(){
		$result = $this->setupModel->getMobileConfig();

        echo json_encode($result);
	}
	
	public function saveMobileConfig(){
		$result = $this->setupModel->saveMobileConfig($_REQUEST);
        echo json_encode($result);
	}
	
	//权限列表
	public function getPremList(){
		$result = $this->setupModel->getPremList($_REQUEST);
        echo json_encode($result);
	}
	//店铺权限列表
	public function getShopPremList(){
		$result = $this->setupModel->getShopPremList($_REQUEST);
        echo json_encode($result);
	}
	//保存权限
	public function savePremList(){
		$result = $this->setupModel->savePremList($_REQUEST);
        echo json_encode($result);
	}
	
	public function saveShopPremList(){
		$result = $this->setupModel->saveShopPremList($_REQUEST);
        echo json_encode($result);
	}
	
	public function expressMemoMain(){
		$result = $this->setupModel->expressMemoMain($_REQUEST);
        echo json_encode($result);
	}
	
	public function saveWuliuRemark(){
		$result = $this->setupModel->saveWuliuRemark($_REQUEST);
        echo json_encode($result);
	}
	
	public function userOnlineMain(){
		$result = $this->setupModel->userOnlineMain($_REQUEST);
        echo json_encode($result);
	}
	
	public function saveUserOnline(){
		$result = $this->setupModel->saveUserOnline($_REQUEST);
        echo json_encode($result);
	}
	
	public function downloadUserOnline(){
		$result = $this->setupModel->downloadUserOnline($_REQUEST);
        echo json_encode($result);
	}
	
	public function getUser(){
		$result = $this->setupModel->getUser();
        echo json_encode($result);
	}
	
	//刷单处理
	public function among()
	{
		$this->display();
    }

    //获取商品
	public function getLoadTable(){
		$data = $this->setupModel->getLoadTable($_REQUEST);
		echo json_encode($data);
	}
	
	public function getBasicSetUp(){
		$data = $this->setupModel->getBasicSetUp($_REQUEST);
		echo json_encode($data);
	}
	
	public function addAmongList(){
		$data = $this->setupModel->addAmongList($_REQUEST);
		echo json_encode($data);
	}
	
	public function delBasicSetUp(){
		$data = $this->setupModel->delBasicSetUp($_REQUEST);
		echo json_encode($data);
	}
	
	public function selectedTable(){
		$data = $this->setupModel->selectedTable($_REQUEST);
		echo json_encode($data);
	}
	
	public function secretSetup(){
		$data = $this->setupModel->secretSetup($_REQUEST);
		echo json_encode($data);
	}
	
	public function secretSel(){
		$data = $this->setupModel->secretSel($_REQUEST);
		echo json_encode($data);
	}
	
	public function getWuliuList(){
		$data = $this->setupModel->getWuliuList($_REQUEST);
		echo json_encode($data);
	}
	
	public function setAllWuliu(){
		$data = $this->setupModel->setAllWuliu($_REQUEST);
		echo json_encode($data);
	}
	
	//设置打印省份选择
	public function setupPrintList(){
		$data = $this->setupModel->setupPrintList($_REQUEST);
		echo json_encode($data);
	}
	
	//功能权限列表
	public function getFuncPremList(){
		$result = $this->setupModel->getFuncPremList($_REQUEST);
        echo json_encode($result);
	}
	
	public function saveFuncPremList(){
		$result = $this->setupModel->saveFuncPremList($_REQUEST);
        echo json_encode($result);
	}
	
	public function getAGList(){
		$result = $this->setupModel->getAGList($_REQUEST);
        echo json_encode($result);
	}
	
	public function getAGConfig(){
		$result = $this->setupModel->getAGConfig($_REQUEST);
        echo json_encode($result);
	}
	
	public function setAGConfig(){
		$result = $this->setupModel->setAGConfig($_REQUEST);
        echo json_encode($result);
	}
	
	public function alipayCustoms(){
		$this->display();
	}
	
	public function weixinCustoms(){
		$this->display();
	}
	
	public function clearCustoms(){
		$this->display();
	}
	
	public function getAlipayCustoms(){
		$result = $this->setupModel->getAlipayCustoms($_REQUEST);
        echo json_encode($result);
	}
	
	public function saveAlipayCustoms(){
		$result = $this->setupModel->saveAlipayCustoms($_REQUEST);
        echo json_encode($result);
	}
	
	public function getWeixinCustoms(){
		$result = $this->setupModel->getWeixinCustoms($_REQUEST);
        echo json_encode($result);
	}
	
	public function saveWeixinCustoms(){
		$result = $this->setupModel->saveWeixinCustoms($_REQUEST);
        echo json_encode($result);
	}
	
	//删除快递
	public function delTableOnceExpress(){
		$result = $this->setupModel->delTableOnceExpress($_REQUEST);
        echo json_encode($result);
	}
	
	public function expressExpenses()
    {
        $this->display();
    }
	
	public function expressExpensesAreaNew(){
		$this->display();
	}
	
	public function expressExpensesAreaEdit(){
		$id = $_REQUEST['id'];
		
		$this->assign('id', $id);
		$this->display();
	}
	
	public function areaTree(){
		$this->display();
	}
	
	public function areaLoadNodes(){
		$result = $this->setupModel->areaLoadNodes($_REQUEST);
        echo json_encode($result);
	}
	 
	public function areaAdd(){
		$result = $this->setupModel->areaAdd($_REQUEST);
        echo json_encode($result);
	}
	
	public function areaDel(){
		$result = $this->setupModel->areaDel($_REQUEST);
        echo json_encode($result);
	}
	
	public function areaLook(){
		$result = $this->setupModel->areaLook($_REQUEST);
        echo json_encode($result);
	}
	
	public function areaGet(){
		$result = $this->setupModel->areaGet($_REQUEST);
        echo json_encode($result);
	}
	
	public function areaUpdate(){
		$result = $this->setupModel->areaUpdate($_REQUEST);
        echo json_encode($result);
	}
	
	public function expressExpensesAreaMain(){
		$result = $this->setupModel->expressExpensesAreaMain($_REQUEST);
        echo json_encode($result);
	}
	
	public function getWlComp(){
		$result = $this->setupModel->getWlComp($_REQUEST);
        echo json_encode($result);
	}
	
	public function expensesUpdate(){
		$result = $this->setupModel->expensesUpdate($_REQUEST);
        echo json_encode($result);
	}
	
	public function expensesMain(){
		$result = $this->setupModel->expensesMain($_REQUEST);
        echo json_encode($result);
	}
	
	public function getShopJD(){
		$result = $this->setupModel->getShopJD($_REQUEST);
        echo json_encode($result);
	}
	
	/**
	取得默认打印模板
	**/
	public function getPrintTemplate(){
		$result = $this->setupModel->getPrintTemplate($_REQUEST);
        echo json_encode($result);
	}
}
