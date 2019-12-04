<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<?
		include_once("../html/js/boot.php");
	?>
	
	<title>出库订单</title>
	<style type="text/css">
	html, body{
		margin:0;padding:0;border:0;width:100%;height:100%;overflow:hidden;
	}

	</style>
</head>
<body>
<div class="mini-fit">
	<div class="mini-toolbar" style="padding:2px;border-bottom:0;">
		<table style="width:100%;">
			<tr style="display:block;margin-bottom:2px">
				<td style="white-space:nowrap;">
					<input id="start_time" name="start_time" class="mini-datepicker" format="yyyy-MM-dd"  onenter="onKeyEnter" emptyText="开始时间"/>
					<input id="end_time" name="end_time" class="mini-datepicker" onenter="onKeyEnter" emptyText="结束时间"/>
					<input name="settle_no" id="settle_no" class="mini-textbox" emptyText="结算单号" style="width:150px;" onenter="onKeyEnter"/>
					<input name="dealer_no" id="dealer_no" class="mini-textbox" emptyText="经销商" style="width:150px;" onenter="onKeyEnter"/>
					<select id="status" name="status" class="mini-combobox" style="width:150px;">
                    	<option value="all">全部</option>
                        <option value="0">未审核</option>
                        <option value="1">已审核</option>
                    </select>

						
					<a class="mini-button" onclick="search()">查询</a>
				</td>
			</tr>
			<tr>
				<td style="width:100%;">
					<a class="mini-button" onclick="add()">新增结算</a>
					<a class="mini-button" onclick="remove()">删除</a>
					<a class="mini-button" onclick="examine()">审核结算</a>
                    <a class="mini-button" onclick="pay()">收款录入</a>
				</td>
			</tr>
		</table>           
	</div>

	<div class="mini-fit">
		<div id="datagrid1" class="mini-datagrid" style="width:100%;height:100%;"
			url="index.php?m=wms&c=settlement&a=getList"  idField="id" multiSelect="true" pageSize="20"
		>
			<div property="columns">
				<!--<div type="indexcolumn"></div> -->
				<div type="checkcolumn" ></div>        
				<div field="settle_no" width="120" headerAlign="center" allowSort="">结算单号</div>    
				<div field="status" width="120" headerAlign="center" allowSort="">状态</div>    
				<div field="dealer_name" width="120" headerAlign="center" allowSort="">经销商名称</div>
                <div field="begin_date" width="120" headerAlign="center" allowSort="">结算开始日期</div>  
				<div field="end_date" width="120" headerAlign="center" allowSort="">结算截止日期</div>  
                <div field="create_time" width="120" headerAlign="center" allowSort="" >单据日期</div> 
                <div field="all_money" width="120" headerAlign="center" allowSort="">合计费用</div>
                <div field="payment" width="120" headerAlign="center" allowSort="">已付费用</div>
				<div field="handing_money" width="120" headerAlign="center" allowSort="">装卸费</div>  
				<div field="storage_money" width="120" headerAlign="center" allowSort="">仓储费</div>  
				<div field="order_money" width="120" headerAlign="center" allowSort="">单费用</div>
				<div field="sorting_money" width="120" headerAlign="center" allowSort="">分拣费</div>
                <div field="box_money" width="120" headerAlign="center" allowSort="">件费用</div>
                <div field="plastic_film_money" width="120" headerAlign="center" allowSort="">打膜费</div>
                <div field="points_money" width="120" headerAlign="center" allowSort="">扣点费</div>
                <div field="remark" width="120" headerAlign="center" allowSort="">备注</div>
                
			</div>
		</div>
	</div>
</div>	
	<?
		include_once("../html/js/myAppBoot.php");
	?>
	
	<style>
	.layui-table-page select{height:25px}
	</style>

</body>
</html>
