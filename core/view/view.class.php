<?
class View 
{
	protected $_values;
	public function __call($method,$args)
	{
		if(method_exists($this,$method)) 
		{
			$this->$method();
        }
	}
	
	public function setCacheHtml($fileName)
	{
		$this->cacheHtml = $fileName;
	}
	
	public function get($name='')
	{
        if($name === '') 
		{
            return $this->_values;
        }
        return isset($this->_values[$name]) ? $this->_values[$name] : false;
    }
	
	public function assign($name,$value='')
	{
        if(is_array($name)) 
		{
            $this->_values = array_merge($this->_values,$name);
        }
		else 
		{
            $this->_values[$name] = $value;
        }
    }
	
	public function display($templateFile='') 
	{
		$templateFile = $this->parseTemplate($templateFile);
		if(!is_file($templateFile))
		{
			exit;	
		}
		if(is_array($this->_values)) 
		{
			foreach($this->_values as $key => $value)
			{
				$$key = $value;
			}
		}
        
        /*echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">';
        echo"<link rel=\"stylesheet\" type=\"text/css\" href=\"/theme/";
        echo $LOGIN_THEME==""?"1" : $LOGIN_THEME;
        echo"/menu.css\">\r\n";*/
		global $LOGIN_USER_ID;
		global $LOGIN_UID;
		global $msconn;
		include $templateFile;
    }
	
	public function parseTemplate($template='') 
	{
		if(is_file($template)) 
		{
            return $template;
        }
		if($template != '')
		{
			$template = trim($template,'/');
			$path = explode('/',$template); 	
		}
		$ACTION_NAME = $template == '' ? ACTION_NAME : array_pop($path);
		$CONTROLLER_NAME = !empty($path) ? array_pop($path) : CONTROLLER_NAME;
		$MODULE_NAME = !empty($path) ? array_pop($path) : MODULE_NAME;
		if(strtoupper(substr(PHP_OS,0,3)) === 'WIN'){}else{
//			if(!file_exists(($GLOBALS['JQ_SYSTEM']['APP_ROOT'] ? $GLOBALS['JQ_SYSTEM']['APP_ROOT'] : $_SERVER['DOCUMENT_ROOT'])."/".APP_PATH."/".MODULE_NAME."/view/".$CONTROLLER_NAME.'/'.$ACTION_NAME.".php")){
//				$CONTROLLER_NAME = getOldFileName($_SERVER['DOCUMENT_ROOT']."/".APP_PATH."/".MODULE_NAME."/view/",$CONTROLLER_NAME);
//			}
		}
		return  APP_PATH."/".MODULE_NAME."/view/".$CONTROLLER_NAME.'/'.$ACTION_NAME.".php";
	}
}
?>