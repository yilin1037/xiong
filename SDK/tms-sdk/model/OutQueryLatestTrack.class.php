<?php
class OutQueryLatestTrack
{	
	private $url = "/query/outquerylatesttrack";//接口类型，固定值
	
	private $apiParas = array();//API参数数组
	
	public function __construct()
	{
		
	}

	public function getUrl()
	{
		return $this->url;	
	}
	
	public function setData($data)//客户编号
	{
		$this->apiParas = $data;
	}
	
	public function getApiParas()
	{
		return $this->apiParas;
	}
	
	public function check()
	{
		
		/*RequestCheckUtil::checkMaxLength($this->buyerNick,64,"buyerNick");
		RequestCheckUtil::checkNotNull($this->isFinished,"isFinished");
		RequestCheckUtil::checkNotNull($this->orderItemList,"orderItemList");
		RequestCheckUtil::checkNotNull($this->orderSubType,"orderSubType");
		RequestCheckUtil::checkNotNull($this->orderType,"orderType");
		RequestCheckUtil::checkNotNull($this->outBizCode,"outBizCode");
		RequestCheckUtil::checkMaxLength($this->outBizCode,128,"outBizCode");
		RequestCheckUtil::checkMaxLength($this->remark,4000,"remark");
		RequestCheckUtil::checkNotNull($this->storeCode,"storeCode");
		RequestCheckUtil::checkMaxLength($this->storeCode,64,"storeCode");
		RequestCheckUtil::checkMaxLength($this->tmsServiceCode,64,"tmsServiceCode");*/
	}
}
