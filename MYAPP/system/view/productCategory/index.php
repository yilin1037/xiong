<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<?
			include_once("../html/js/boot.php");
		?>
		<link rel="stylesheet" href="js/plug-in/bootstrap/css/bootstrap.css" />
		<link rel="stylesheet" href="js/plug-in/layui-2.0/css/layui.css" />
		
		<title>产品分类</title>
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
	
		<div style="width:100%;margin:5px 0">
			<div class="mini-toolbar" style="border-bottom:0;padding:0px;border:0;margin:2px 0;">
				<table style="width:100%;">
					<tr>
						<td style="flaot:left;">
							<a class="mini-button" iconCls="icon-add" onclick="newRow()">新增</a>
							
							<input name="category_no" id="category_no" class="mini-textbox" emptyText="分类编号" style="width:150px;" onenter="onKeyEnter"/>   
							<input name="category_name" id="category_name" class="mini-textbox" emptyText="分类名称" style="width:150px;" onenter="onKeyEnter"/>
							
							<a class="mini-button" onclick="search()">查询</a>
						</td>
					</tr>
				</table>           
			</div>
		</div>
		
		<div class="mini-fit">
		<div id="datagrid1" class="mini-datagrid" style="width:100%;height:100%;"  allowResize="true"
        url="index.php?m=system&c=productCategory&a=getList" idField="id" emptyText="数据为空，<a href='javascript:newRow()'>增加一条</a>" field="1" showEmptyText="true" pageSize="20" OnCellBeginEdit="OnCellBeginEdit"
    >
			<div property="columns">
				<div name="action" width="80" headerAlign="center" align="center" renderer="onActionRenderer" cellStyle="padding:0;">#</div>
				
				<div field="category_no" width="120" headerAlign="center" allowSort="">分类编号
					<input property="editor" class="mini-textbox" style="width:100%;"/>
				</div>
				<div field="category_name" width="120" headerAlign="center" allowSort="">分类名称
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
				//console.log(row);return;
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
							url: "index.php?m=system&c=productCategory&a=del&id=" + row.id,
						
							success: function (text) {
                              
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
				//console.log(row);return;
				if(!row.category_name){
					
					alert('分类名称不能为空');
					return;
				}
				
				var rowData = grid.getChanges();
				grid.loading("保存中，请稍后......");
				var json = mini.encode(rowData);
				
				$.ajax({
					url: "index.php?m=system&c=productCategory&a=save",
					type: 'post',
					dataType:'json',
					cache: false,
					data: { data: json },
					success: function (data) {
					
						if(data.code == 'error'){
							alert(data.msg);
						}else{
							mini.showTips({
									content: "数据保存成功",
									state: 'success',
									x: 'center',
									y: 'center',
									timeout: 2000
								});
						}
						grid.reload();
					},
					error: function (jqXHR, textStatus, errorThrown) {
						alert(jqXHR.responseText);
					}
				});

			}
			
			function search() {
				var category_no		= mini.get("category_no").getValue();
				var category_name 	= mini.get("category_name").getValue();
				grid.load({category_no:category_no, category_name:category_name});
			}
			
			function onKeyEnter(e) {
				search();
			}
			
			function OnCellBeginEdit(e) {
				var record = e.record, field = e.field;
				if (field == "category_no" && record.category_name != undefined) {
					e.cancel = true;
				}
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
