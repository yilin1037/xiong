<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<?
		include_once("../html/js/boot.php");
	?>
	<title>选择经销商</title>
	<style type="text/css">
	html, body{
		margin:0;padding:0;border:0;width:100%;height:100%;overflow:hidden;
	}

	</style>
</head>
<body>
    <div class="mini-toolbar" style="text-align:center;line-height:30px;" borderStyle="border:0;">
           
		    <input name="key" id="key" class="mini-textbox" emptyText="经销商名称/助记码" style="width:150px;" onenter="onKeyEnter"/>
		    <input name="address" id="address" class="mini-textbox" emptyText="地址" style="width:150px;" onenter="onKeyEnter"/>
			<input name="link_man" id="link_man" class="mini-textbox" emptyText="联系人" style="width:150px;" onenter="onKeyEnter"/>
          <a class="mini-button" style="width:60px;" onclick="search()">查询</a>
    </div>
    <div class="mini-fit">

        <div id="grid1" class="mini-datagrid" style="width:100%;height:100%;" 
            idField="id" allowResize="true"
            borderStyle="border-left:0;border-right:0;" onrowdblclick="onRowDblClick"
        >
            <div property="columns">
                <div field="dealer_no">编号</div>
                <div field="dealer_name" width="60" allowSort="true">经销商名称</div>
				<div field="link_man" width="60">联系人</div>
				<div field="address" width="60">地址</div>
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
		grid.setUrl("index.php?m=system&c=dealer&a=getList");
		//也可以动态设置列 grid.setColumns([]);

		grid.load();

		function GetData() {
			var row = grid.getSelected();
			return row;
		}
		function search() {
			var key = mini.get("key").getValue();
			var address = mini.get("address").getValue();
			var link_man = mini.get("link_man").getValue();
			grid.load({ key: key , address:address, link_man:link_man});
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
