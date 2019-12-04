<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<?
		include_once("../html/js/boot.php");
	?>
	<title>账户管理</title>
</head>
<body>
	<style type="text/css">
	html, body{
		margin:0;padding:0;border:0;width:100%;height:100%;overflow:hidden;
	}    
	.mini-grid-rows-view{
        overflow-x: hidden;
	}
	</style>
	<div class="mini-toolbar" style="padding:2px;border-bottom:0;">
		<table style="width:100%;">
			<tr style="display:inline-block;margin-bottom:2px">
				<td style="white-space:nowrap;">
					<input name="username" id="username" class="mini-textbox" emptyText="名称" style="width:150px;" onenter="onKeyEnter"/>
					<input name="mobile" id="mobile" class="mini-textbox" emptyText="电话" style="width:150px;" onenter="onKeyEnter"/>
					<input name="status" id="status" class="mini-combobox" url="index.php?m=system&c=userTable&a=status_arr" textField="text" valueField="id" emptyText="账号状态" />
					<a class="mini-button" onclick="search()">查询</a>
				</td>
			</tr>
			<tr>
				<td style="width:100%;">
					<a class="mini-button" onclick="add()">增加</a>
					<a class="mini-button" onclick="edit()">编辑</a>
					<a class="mini-button" onclick="remove()">删除</a>
				</td>
			</tr>
		</table>
	</div>
	<div class="mini-fit">
		<div id="datagrid1" class="mini-datagrid" style="width:100%;height:100%;" url="index.php?m=system&c=userTable&a=getList" idField="id" multiSelect="true" pageSize="20">
			<div property="columns">
				<!--<div type="indexcolumn"></div> -->
				<div type="checkcolumn"></div>
				<div field="userid" width="120" headerAlign="center">登录帐号</div>
				<div field="username" width="120" headerAlign="center">名称</div>
				<div field="mobile" width="120" headerAlign="center">电话</div>
				<div field="status" width="120" headerAlign="center">帐号状态</div>
				<div field="last_login_time" width="120" headerAlign="center">上次登录日期</div>
				<div field="create_login_time" width="120" headerAlign="center">创建时间</div>
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
