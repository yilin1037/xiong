<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<?
		include_once("../html/js/boot.php");
	?>
	<title>打印模板管理</title>
	<style type="text/css">
	html, body{
		margin:0;padding:0;border:0;width:100%;height:100%;overflow:hidden;
	}

	</style>
</head>
<body>
	
	<div class="mini-splitter" id="refresh" style="width:100%;height:100%;">
		<div size="240" showCollapseButton="true">
			<!--<p style="padding:10px;font-size:16px;font-weight:bold">打印模板类型</p>-->
			<div class="mini-fit" style="margin:20px 0 0 30px">
				<ul id="tree1" class="mini-tree" url="index.php?m=system&c=print&a=printModuleList" style="width:200px;padding:5px;" showTreeIcon="true" textField="module_name" idField="module_type" parentField="pid" resultAsTree="false" contextMenu="#treeMenu" expandOnLoad="true">
				</ul>
			</div>
		</div>
		
		<div showCollapseButton="true">
			<div class="mini-toolbar" style="padding:2px;border-top:0;border-left:0;border-right:0;">
				<table style="width:100%;">
					<tr>
						<td style="width:100%;">
							<a class="mini-button"  onclick="add()">导入模板</a>
							<a class="mini-button"  onclick="downloadModule()">获取系统模板</a>
						</td>
					</tr>
				</table>           
			</div>
			
			<div class="mini-fit">
				<div id="datagrid1" class="mini-datagrid" style="width:100%;height:100%;" borderStyle="border:0;" url="index.php?m=system&c=print&a=getList" idField="id" multiSelect="true" pageSize="20">
					<div property="columns">
						<div type="indexcolumn" width="10%"></div>
						<div field="module_name" width="40%" headerAlign="center" align = "center" >模板名称</div>
						<div field="is_default" width="10%" headerAlign="center" align = "center" >默认模板</div>
						<div field="action" width="40%" headerAlign="center" align = "center" >操作</div>
					</div>
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
