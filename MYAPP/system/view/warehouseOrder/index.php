<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<?
		include_once("../html/js/boot.php");
	?>

    <link rel="stylesheet" href="js/plug-in/bootstrap/css/bootstrap.css" />
    <link rel="stylesheet" href="js/plug-in/layui-2.0/css/layui.css" />
	
	<title>入库订单</title>
	<style type="text/css">
	html, body{
		margin:0;padding:0;border:0;width:100%;height:100%;overflow:hidden;
	}

	</style>
</head>
<body>
	<div class="mini-toolbar" style="padding:2px;border-bottom:0;">
		<table style="width:100%;">
			<tr style="display:block;margin-bottom:2px">
				<td style="white-space:nowrap;">
					<input id="order_time" name="order_time" class="mini-datepicker" onenter="onKeyEnter" emptyText="选择订单日期"/>
					<input id="start_time" name="start_time" class="mini-datepicker" format="yyyy-MM-dd"  onenter="onKeyEnter" emptyText="开始时间"/>
					<input id="end_time" name="end_time" class="mini-datepicker" onenter="onKeyEnter" emptyText="结束时间"/>
					<input name="order_no" id="order_no" class="mini-textbox" emptyText="订单编号" style="width:150px;" onenter="onKeyEnter"/>
					<input name="dealer_no" id="dealer_no" class="mini-textbox" emptyText="经销商" style="width:150px;" onenter="onKeyEnter"/>
					<input name="status" id="status" class="mini-combobox" url="index.php?m=system&c=warehouseOrderNum&a=status_arr" textField="text" valueField="id" emptyText="审核状态" />
					<input name="cls_id" id="cls_id" class="mini-combobox" data='[{id:"all",text:""},{id:"0",text:"未结案"},{id:"1",text:"已结案"}]' textField="text" valueField="id" emptyText="结案状态" />

					<a class="mini-button" onclick="search()">查询</a>
				</td>
			</tr>
			<tr>
				<td style="width:100%;">
					<a class="mini-button" onclick="add()">新增订单</a>
					<a class="mini-button" onclick="edit()">编辑</a>
                    <a class="mini-button" onclick="view()">查看</a>
					<a class="mini-button" onclick="remove()">删除</a>
					<a class="mini-button" onclick="examine()">审核订单</a>
					<a class="mini-button" onclick="qxea()">取消审核</a>
                    <?php
                    if ($_SESSION["LOGIN_SYSTEM"] == "T") {

                        ?>
                        <a class="mini-button" onclick="lockOrder()">锁定订单</a>
                        <a class="mini-button" onclick="unlockOrder">取消锁定</a>
                        <?php
                    }
                    ?>
					<a class="mini-button" onclick="cls_id1()">结案</a>
					<a class="mini-button" onclick="cls_id0()">反结案</a>
					<a class="mini-button" onclick="printList()">打印清单</a>
					<a class="mini-hidden" onclick="import()">导出订单</a>
				</td>
			</tr>
		</table>           
	</div>

    <!-- ======================================================================================更多操作弹窗================================================================================ -->
    <div id="edit-pages2"   style="text-align:left;display: none;padding-top:10px;position:relative;">
        <div class="container" style="width:100%;">
            <div style="padding:20px 20px;">
                <table style="width:100%;">
                    <thead>
                    <tr>
                        <th>位置</th>
                        <th>原因</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="item in error">
                        <td v-html="item.index"></td>
                        <td v-html="item.msg"></td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- ==================================================================================更多操作弹窗结束============================================================================ -->

	<div class="mini-fit">
		<div id="datagrid1" class="mini-datagrid" style="width:100%;height:100%;"
			url="index.php?m=system&c=warehouseOrder&a=getList"  idField="id" multiSelect="true" pageSize="20"
		>
			<div property="columns">
				<!--<div type="indexcolumn"></div> -->
				<div type="checkcolumn" ></div>        
				<div field="order_no" width="120" headerAlign="center" allowSort="">订单号</div>    
				<div field="status" width="120" headerAlign="center" allowSort="">订单状态</div>
				<div field="cls_id" width="120" headerAlign="center" allowSort="">结案状态</div>
                <?php
                if ($_SESSION["LOGIN_SYSTEM"] == "T") {

                    ?>
                <div field="lock_by" width="120" headerAlign="center" allowSort="">订单锁定人</div>
                    <?php
                }
                ?>
                    <div field="dealer_name" width="120" headerAlign="center" allowSort="">经销商名称</div>

                
				<div field="in_type" width="120" headerAlign="center" allowSort="">入库类型</div>  
				<div field="delivery_type" width="120" headerAlign="center" allowSort="">交货方式</div>  
				<div field="remark" width="120" headerAlign="center" allowSort="">备注</div>
				<div field="maker" width="120" headerAlign="center" allowSort="">制单人</div>
				<div field="auditor" width="120" headerAlign="center" allowSort="">审核人</div>
				<div field="audit_date" width="120" headerAlign="center" allowSort="">审核日期</div>
				<div field="" width="120" headerAlign="center" allowSort="">操作</div>
			</div>
		</div>
	</div>
	
	<?
		include_once("../html/js/myAppBoot.php");
	?>
    <script src="js/plug-in/bootstrap/js/bootstrap.js"></script>
    <script src="js/plug-in/layui-2.0/layui.js"></script>
	
	<style>
	.layui-table-page select{height:25px}
	</style>

</body>
</html>
