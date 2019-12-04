<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<?
			include_once("../html/js/boot.php");
		?>
		<link rel="stylesheet" href="js/plug-in/bootstrap/css/bootstrap.css" />
		<link rel="stylesheet" href="js/plug-in/layui-2.0/css/layui.css" />
		
		<title>自定义类型编号管理</title>
		<style type="text/css">
		html, body{
			margin:0;padding:0;border:0;width:100%;height:100%;overflow:hidden;
		}

		
		.New_Button, .Edit_Button, .Delete_Button, .Update_Button, .Cancel_Button {
			font-size: 11px;
			color: #1B3F91;
			font-family: Verdana;
			margin-right: 5px;
		}
		</style>
	</head>
	<body>
	
	<div class="mini-toolbar" style="margin:2px 0;border:0;">
		<table style="width:100%;border:0;">
			<tr>
				<td style="width:100%;">
					<a class="mini-button" iconCls="icon-add" onclick="newRow()">新增</a>
				</td>
				<?php if(0){ ?>
				<td style="white-space:nowrap;"> 
					<input name="configKey" id="configKey" class="mini-textbox" emptyText="编号" style="width:150px;" onenter="onKeyEnter"/>
					
					<a class="mini-button" iconCls="icon-search" onclick="search()">查询</a>
				</td>
				<?php } ?>
			</tr>
		</table>           
	</div>
	
	<div class="mini-fit">
		<div id="datagrid1" class="mini-datagrid" style="width:100%;height:100%;"  allowResize="true"
        url="index.php?m=system&c=productInfo&a=getProTypeList" idField="id" emptyText="数据为空，<a href='javascript:newRow()'>增加一条</a>" showEmptyText="true"
    >
			<div property="columns">
				<div name="action" width="100" headerAlign="center" align="center" renderer="onActionRenderer" cellStyle="padding:0;">#</div>
				
				<div field="configKey" width="120" headerAlign="center" allowSort="">名称
					<input property="editor" class="mini-textbox" style="width:100%;"/>
				</div>
				<div field="configValue" width="120" headerAlign="center" allowSort="">值
					<input property="editor" class="mini-textbox" style="width:100%;"/>
				</div>
			</div>
		</div>    
		
		<script type="text/javascript">
			mini.parse();
			
			var grid = mini.get("datagrid1");
			grid.load();
			grid.sortBy("id", "asc");

			///////////////////////////////////////////////////////
			function onActionRenderer(e) {
				var grid = e.sender;
				var record = e.record;
				var uid = record._uid;
				var rowIndex = e.rowIndex;

				//var s = '<a class="New_Button" href="javascript:newRow()">添加</a>'
				var s = ''
						+ '  &nbsp;&nbsp; <a class="Edit_Button" href="javascript:editRow(\'' + uid + '\')" >编辑</a>'
						+ ' &nbsp;&nbsp; <a class="Delete_Button" href="javascript:delRow(\'' + uid + '\')">删除</a>';

				if (grid.isEditingRow(record)) {
					s = ' &nbsp;&nbsp; <a class="Update_Button" href="javascript:updateRow(\'' + uid + '\')" style="color:green">保存</a>'
						+ '  &nbsp;&nbsp; <a class="Cancel_Button" href="javascript:cancelRow(\'' + uid + '\')">取消</a>'
				}
				return s;
			}

			function newRow() {
				var row = {};
				grid.addRow(row, 0);

				grid.cancelEdit();
				grid.beginEditRow(row);
			}
			function editRow(row_uid) {
				var row = grid.getRowByUID(row_uid);
				if (row) {
					grid.cancelEdit();
					grid.beginEditRow(row);
				}
			}
			function cancelRow(row_uid) {
				grid.reload();
			}
			function delRow(row_uid) {
				var row = grid.getRowByUID(row_uid);
				if (row) {
					if (confirm("确定删除此记录？")) {
						grid.loading("删除中，请稍后......");
						$.ajax({
							url: "index.php?m=system&c=productInfo&a=delProType&id=" + row.id,
							success: function (text) {
                                if(text.code=="error") {
                                    mini.alert(text.msg);
                                }
								grid.reload();
							},
							error: function () {
							}
						});
					}
				}
			}

			function updateRow(row_uid) {
				var row = grid.getRowByUID(row_uid);
				
				grid.commitEdit();
				var rowData = grid.getChanges();
				
				grid.loading("保存中，请稍后......");
				var json = mini.encode(rowData);
				
				$.ajax({
					url: "index.php?m=system&c=productInfo&a=saveProType",
					type: 'post',
					data: { data: json },
					success: function (text) {
						grid.reload();
					},
					error: function (jqXHR, textStatus, errorThrown) {
						alert(jqXHR.responseText);
					}
				});

			}
		</script>
	
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
