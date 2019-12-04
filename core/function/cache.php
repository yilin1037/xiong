<?
if(!function_exists('GVAL')){
	function GVAL($valName)
	{
		global $$valName;
		return $$valName;
	}
}
if(!function_exists('S'))
{

	function S($name,$value='',$cacheTime=3600)
	{
		$LOGIN_SYSTEM_ID = GVAL('LOGIN_SYSTEM_ID');
		if(empty($LOGIN_SYSTEM_ID))
		{
			$LOGIN_SYSTEM_ID = $_REQUEST['LOGIN_SYSTEM_ID'];
		}
		include_once($GLOBALS['JQ_SYSTEM']['APP_ROOT']."/core/model/CacheModel.class.php");//缓存模型
		static $cache = '';
		$confarr = parse_ini_file('/inc/mssql/mssql.inc');
		if(empty($cache)) { // 自动初始化
			$cache_set = array(
				//缓存路径 , 最后要加"/"
				'cacheRoot'=>$GLOBALS['JQ_SYSTEM']['APP_ROOT'].'/cache/'.$LOGIN_SYSTEM_ID.'/',
				//缓存时间
				'cacheTime'=>$cacheTime,
				//cache type
				'cacheType'=>$confarr['CACHE_TYPE'] == 'redis' ? 'redis' : 'file',
				//缓存类型 file redis
				'cacheRedisIp'=>$confarr['CACHE_REDIS_IP'],
				//redis IP
				'cacheRedisPort'=>$confarr['CACHE_REDIS_PORT'],
				//redis port
				'cacheFileType'=>1,
				//扩展名
				'cacheExe'=>'.php'
			);
			$cache = new Cache($cache_set);
		}
		if(strpos($name, $LOGIN_SYSTEM_ID) === false)
		{
			if($value === ''){ // 获取缓存
				return $cache->cache_read($LOGIN_SYSTEM_ID.$name);
			}elseif(is_null($value)) { // 删除缓存
				return $cache->clear($LOGIN_DBNAME.$name);
			}else { // 缓存数据
				return $cache->cache_data($LOGIN_SYSTEM_ID.$name, $value, $cacheTime);
			}
		}
		else
		{
			if($value === ''){ // 获取缓存
				return $cache->cache_read($name);
			}elseif(is_null($value)) { // 删除缓存
				return $cache->clear($name);
			}else { // 缓存数据
				return $cache->cache_data($name, $value, $cacheTime);
			}
		}
	}
}
?>