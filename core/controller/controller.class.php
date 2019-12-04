<?
require_once('../core/view/view.class.php');
class Controller
{
	protected $_view;
	protected $_cacheHtml = '';

	public function __construct() 
	{
		$this->_view = new View;
	}
	
	public function __call($method,$args)
	{
		if(method_exists($this,$method)) 
		{
			$this->$method();
        }
	}
	
	public function success($message='',$jumpUrl='')
	{
		$this->_dispatchJump($message,1,$jumpUrl,true);
	}
	public function error($message='',$jumpUrl='')
	{
		$this->_dispatchJump($message,0,$jumpUrl,true);
	}
    public function original($data)
	{
		$this->_ajaxReturn($data);
	}
	protected function _dispatchJump($message,$status=1,$jumpUrl='',$ajax=false)
	{
		$data['info']   =   $message;
        $data['status'] =   $status;
        $data['url']    =   $jumpUrl;
        $this->_ajaxReturn($data);
	}
	
	protected function _ajaxReturn($data)
	{
		exit(json_encode($data));
	}
	
	public function assign($name,$value='') {
        $this->_view->assign($name,$value);
        return $this;
    }
	
	public function display($templateFile='') 
	{
		if($this->_cacheHtml)//写出缓存
		{
			ob_start();
			$this->_view->display($templateFile);
			$content = ob_get_contents();//取得php页面输出的全部内容
			ob_end_flush();
			if (!file_exists($GLOBALS['JQ_SYSTEM']['HTML_CACHE_PATH']))
			{
				mkdir($GLOBALS['JQ_SYSTEM']['HTML_CACHE_PATH']); 
			}
			if (!file_exists($GLOBALS['JQ_SYSTEM']['HTML_CACHE_PATH'].'/'.$_SESSION['system_id']))
			{
				mkdir($GLOBALS['JQ_SYSTEM']['HTML_CACHE_PATH'].'/'.$_SESSION['system_id']); 
			}
			if($fp = fopen($this->_cacheHtml, 'w')) 
			{
				$startTime = microtime();
				do 
				{
					$canWrite = flock($fp, LOCK_EX);
					if(!$canWrite)
					{
						usleep(round(rand(0, 100)*1000));//随机1-100毫秒
					}
				} while ((!$canWrite) && ((microtime()-$startTime) < 2000));//2秒超时
				if ($canWrite) 
				{
					fwrite($fp, $content);
				}
				fclose($fp);
			}
			echo $content;
			exit;
		}
		$this->_view->display($templateFile);
		$this->_cacheHtml = '';
    }
	
	public function cacheHtml($param)
	{
		ksort($param);
		foreach($param as $item)
		{
			$paramStr .= is_array($item) ? json_encode($item) : $item;
		}
		$fileName = $GLOBALS['JQ_SYSTEM']['HTML_CACHE_PATH'].'/'.$_SESSION['system_id'].'/'.md5(APP_PATH.'/'.INDEX_MODULE_NAME.'/controller/'.INDEX_CONTROLLER_NAME.$paramStr).$GLOBALS['JQ_SYSTEM']['HTML_CACHE_SUFFIX'];
		if(file_exists($GLOBALS['JQ_SYSTEM']['HTML_CACHE_PATH']))//文件存在
		{
			if($GLOBALS['JQ_SYSTEM']['HTML_CACHE_TIME']>0 && (time() - @filemtime($fileName)) > $GLOBALS['JQ_SYSTEM']['HTML_CACHE_TIME'])
			{
				$this->_cacheHtml = $fileName;	
			}
			else
			{
				@include_once($fileName);
				exit;
			}
		}
		else//文件不存在
		{
			$this->_cacheHtml = $fileName;
			$this->_view->setCacheHtml($fileName);
		}
	}
	
	public function buildHtml($action, $param, $fileName)
	{
		if(!@include_once(APP_PATH.'/'.INDEX_MODULE_NAME.'/controller/'.INDEX_CONTROLLER_NAME.'Controller.class.php'))
		{
			return false;
		}
		$className = $c.'ControllerClass';
		$controller = new $className;
		ob_start();
		$controller->$action($param);
		$content = ob_get_contents();//取得php页面输出的全部内容
		ob_end_flush();
		if (!file_exists($GLOBALS['JQ_SYSTEM']['HTML_CACHE_PATH']))
		{
            mkdir($GLOBALS['JQ_SYSTEM']['HTML_CACHE_PATH']); 
        }
		if (!file_exists($GLOBALS['JQ_SYSTEM']['HTML_CACHE_PATH'].'/'.$_SESSION['system_id']))
		{
            mkdir($GLOBALS['JQ_SYSTEM']['HTML_CACHE_PATH']); 
        }
		$fileName = $GLOBALS['JQ_SYSTEM']['HTML_CACHE_PATH'].'/'.$_SESSION['system_id'].'/'.$fileName.$GLOBALS['JQ_SYSTEM']['URL_HTML_SUFFIX'];
		if($fp = fopen($fileName, 'w')) 
		{
			$startTime = microtime();
			do 
			{
				$canWrite = flock($fp, LOCK_EX);
				if(!$canWrite)
				{
					usleep(round(rand(0, 100)*1000));//随机1-100毫秒
				}
			} while ((!$canWrite) && ((microtime()-$startTime) < 2000));//2秒超时
			if ($canWrite) 
			{
				fwrite($fp, $content);
			}
			fclose($fp);
		}
		if($canWrite)
		{
			return str_replace($_SERVER['DOCUMENT_ROOT'], '', $fileName);
		}
		else
		{
			return $canWrite;
		}
	}
	
	public function userLimitCheck($menu_id){//检验菜单权限
		require_once('../core/model/CommonModel.class.php');
		$CommonModel = new CommonModelClass();
		$userlimit =  $CommonModel->getUserLimit();
		if(is_array($userlimit) && !$userlimit[$menu_id]){
			echo "权限不足，请先设置权限！！";
			exit;
		}
	}

    public function userActionLimitCheck($action_ids,$json=false) {//检验菜单权限
        require_once('../core/model/CommonModel.class.php');
        $CommonModel = new CommonModelClass();
        $userlimit =  $CommonModel->getUserLimit();
        $has_permission=false;
        if(is_array($userlimit)) {
            foreach ($action_ids as $k=>$v) {
                if(array_key_exists($v,$userlimit)) {
                    $has_permission=true;
                }
            }
        }
        if(empty($userlimit)) {
            $has_permission=true;
        }

        if(!$has_permission){
            if($json) {
               $return=array("code" => "error", "msg" => "权限不足，请先设置权限！");
               echo json_encode($return);
               exit;

            } else {
                echo "权限不足，请先设置权限！！";
                exit;
            }

        }
    }
}
?>