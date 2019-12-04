<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<?
		include_once("../html/js/boot.php");
	?>
	<title>选择保管员</title>
	<style type="text/css">
	html, body{
		margin:0;padding:0;border:0;width:100%;height:100%;overflow:hidden;
	}

	</style>
</head>
<body>
    <div class="mini-toolbar" style="text-align:center;line-height:30px;" borderStyle="border:0;">
        <input name="user_no" id="user_no" class="mini-textbox" emptyText="保管员编号" style="width:150px;" onenter="onKeyEnter"/>
		<input name="user_name" id="user_name" class="mini-textbox" emptyText="保管员名称" style="width:150px;" onenter="onKeyEnter"/>
		
		<a class="mini-button" style="width:60px;" onclick="search()">查询</a>
    </div>
    <div class="mini-fit">

        <div id="grid1" class="mini-datagrid" style="width:100%;height:100%;" 
            idField="id" allowResize="true"
            borderStyle="border-left:0;border-right:0;" onrowdblclick="onRowDblClick"
        >
            <div property="columns">
				<div type="indexcolumn"></div>
                <div field="user_no">编号</div>
                <div field="user_name" width="60" allowSort="true">保管员名称</div>
				<div field="dept_no" width="60">所属部门</div>
			</div>
		</div>
    </div>
    <div class="mini-toolbar" style="text-align:center;padding-top:8px;padding-bottom:8px;" borderStyle="border:0;">
        <a class="mini-button" style="width:60px;" onclick="onOk()">确定</a>
        <span style="display:inline-block;width:25px;"></span>
        <a class="mini-button" style="width:60px;" onclick="onCancel()">取消</a>
    </div>
	
	<script>
		mini.parse();

		var grid = mini.get("grid1");

		//动态设置URL
		grid.setUrl("index.php?m=system&c=whLoc&a=getUserList");
		//也可以动态设置列 grid.setColumns([]);

		grid.load();

		function GetData() {
			var row = grid.getSelected();
			return row;
		}
		function search() {
			var user_no = mini.get("user_no").getValue();
			var user_name = mini.get("user_name").getValue();
			grid.load({ user_no: user_no , user_name: user_name});
		}
		function onKeyEnter(e) {
			search();
		}
		function onRowDblClick(e) {
			onOk();
		}
		//////////////////////////////////
		function CloseWindow(action) {
			if (window.CloseOwnerWindow) return window.CloseOwnerWindow(action);
			else window.close();
		}

		function onOk() {
			CloseWindow("ok");
		}
		function onCancel() {
			CloseWindow("cancel");
		}
	</script>
</body>
</html>
