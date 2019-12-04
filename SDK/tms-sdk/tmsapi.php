<?php
include_once('model/AddCust.class.php');
include_once('model/AddDealer.class.php');
include_once('model/AddGood.class.php');
include_once('model/AddOrder.class.php');
include_once('model/AddPickup.class.php');
include_once('model/CancelOrder.class.php');
include_once('model/EditCust.class.php');
include_once('model/EditDealer.class.php');
include_once('model/EditGood.class.php');
include_once('model/EditOrder.class.php');
include_once('model/OpenApiTms.class.php');
include_once('model/OutQueryLatestTrack.class.php');
include_once('model/PushConfirm.class.php');
include_once('model/ReceiveShipmen.class.php');
include_once('model/RecvInstruct.class.php');
include_once('model/FinishPickup.class.php');
// 客户添加 编辑 1 2
function addCust($data){
    $openApi = new OpenApiTms();
    $apiRequest = new AddCust();
    $apiRequest->setData($data);
    $openApi->appkey = '555';
    $openApi->secretKey = 'abcde53343';
    $rep = $openApi->execute($apiRequest);
    return $rep;
}

function editCust($data) {
    $openApi = new OpenApiTms();
    $apiRequest = new EditCust();

    $apiRequest->setData($data);
    $openApi->appkey = '555';
    $openApi->secretKey = 'abcde53343';
    $rep = $openApi->execute($apiRequest);
    return $rep;
}

// 添加  编辑商品 3 4
function addGood($data){
    $openApi = new OpenApiTms();
    $apiRequest = new AddGood();

    $apiRequest->setData($data);
    $openApi->appkey = '555';
    $openApi->secretKey = 'abcde53343';
    $rep = $openApi->execute($apiRequest);
    return $rep;
}

function editGood($data){
    $openApi = new OpenApiTms();
    $apiRequest = new EditGood();

    $apiRequest->setData($data);
    $openApi->appkey = '555';
    $openApi->secretKey = 'abcde53343';
    $rep = $openApi->execute($apiRequest);
    return $rep;
}

// 添加 编辑 经销商 5 6
function addDealer($data){
    $openApi = new OpenApiTms();
    $apiRequest = new AddDealer();

    $apiRequest->setData($data);
    $openApi->appkey = '555';
    $openApi->secretKey = 'abcde53343';
    $rep = $openApi->execute($apiRequest);
    return $rep;
}

function editDealer($data){
    $openApi = new OpenApiTms();
    $apiRequest = new EditDealer();

    $apiRequest->setData($data);
    $openApi->appkey = '555';
    $openApi->secretKey = 'abcde53343';
    $rep = $openApi->execute($apiRequest);
    return $rep;
}

// 添加 编辑 取消 订单 7 8 9

function addOrder($data){
    $openApi = new OpenApiTms();
    $apiRequest = new AddOrder();

    $apiRequest->setData($data);
    $openApi->appkey = '555';
    $openApi->secretKey = 'abcde53343';

    $rep = $openApi->execute($apiRequest);
    return $rep;
}

function editOrder($data){
    $openApi = new OpenApiTms();
    $apiRequest = new EditOrder();

    $apiRequest->setData($data);
    $openApi->appkey = '555';
    $openApi->secretKey = 'abcde53343';
    $rep = $openApi->execute($apiRequest);
    return $rep;
}

function cancelOrder($data){
    $openApi = new OpenApiTms();
    $apiRequest = new CancelOrder();

    $apiRequest->setData($data);
    $openApi->appkey = '555';
    $openApi->secretKey = 'abcde53343';
    $rep = $openApi->execute($apiRequest);
    return $rep;
}

// 10 接收发货信息

function receiveShipment($data){
    $openApi = new OpenApiTms();
    $apiRequest = new ReceiveShipmen();

    $apiRequest->setData($data);
    $openApi->appkey = '555';
    $openApi->secretKey = 'abcde53343';
    $rep = $openApi->execute($apiRequest);
    return $rep;
}

// 11 接收仓库退货、拒收、货损确认信息

function pushConfirm($data){
    $openApi = new OpenApiTms();
    $apiRequest = new PushConfirm();

    $apiRequest->setData($data);
    $openApi->appkey = '555';
    $openApi->secretKey = 'abcde53343';
    $rep = $openApi->execute($apiRequest);
    return $rep;
}

// 12 接收入库计划接口
function addPickup($data){
    $openApi = new OpenApiTms();
    $apiRequest = new AddPickup();

    $apiRequest->setData($data);
    $openApi->appkey = '555';
    $openApi->secretKey = 'abcde53343';
    $rep = $openApi->execute($apiRequest);
    return $rep;
}
// 13 入库计划完成
function finishPickup($data){
    $openApi = new OpenApiTms();
    $apiRequest = new FinishPickup();

    $apiRequest->setData($data);
    $openApi->appkey = '555';
    $openApi->secretKey = 'abcde53343';
    $rep = $openApi->execute($apiRequest);
    return $rep;
}



// 14 查询最新位置
function outquerylatesttrack($data){
    $openApi = new OpenApiTms();
    $apiRequest = new OutQueryLatestTrack();

    $apiRequest->setData($data);
    $openApi->appkey = '555';
    $openApi->secretKey = 'abcde53343';
    $rep = $openApi->execute($apiRequest);
    return $rep;
}

// 15 接收配送计划计算指令
function recvInstruct($data){
    $openApi = new OpenApiTms();
    $apiRequest = new RecvInstruct();

    $apiRequest->setData($data);
    $openApi->appkey = '555';
    $openApi->secretKey = 'abcde53343';
    $rep = $openApi->execute($apiRequest);
    return $rep;
}
