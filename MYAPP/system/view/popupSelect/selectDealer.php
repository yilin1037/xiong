<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<?
		include_once("../html/js/boot.php");
	?>
	<link rel="stylesheet" href="js/plug-in/bootstrap/css/bootstrap.css" />
	<link rel="stylesheet" href="js/plug-in/layui-2.0/css/layui.css" />
	
	<title>选择经销商</title>
	<style type="text/css">
	html, body{
		margin:0;padding:0;border:0;width:100%;height:100%;overflow:hidden;
	}

	</style>
</head>

<body>
    <div class="mini-toolbar" style="text-align:center;line-height:30px;" borderStyle="border:0;">
          <label>经销商编号：</label>
          <input id="dealer_no" class="mini-textbox" style="width:150px;" onenter="onKeyEnter"/>
		   <label>经销商名称：</label>
          <input id="dealer_name" class="mini-textbox" style="width:150px;" onenter="onKeyEnter"/>
          <a class="mini-button" style="width:60px;" iconCls="icon-search" onclick="search()">查询</a>
    </div>
	
    <div class="mini-fit">
        <div id="grid1" class="mini-datagrid" style="width:100%;height:100%;" 
            idField="id" allowResize="true"
            borderStyle="border-left:0;border-right:0;"
            <?php if($multiSelect==1){ ?>multiSelect="true"<?php } ?>>
            <div property="columns">
                <div type="checkcolumn" ></div>
                <div field="dealer_no" width="120" headerAlign="center" allowSort="true">经销商编号</div>
                <div field="dealer_name" width="120" headerAlign="center" allowSort="true">经销商名称</div>
				<div field="link_man" width="120" headerAlign="center" allowSort="true">联系人</div>
				<div field="link_tel" width="120" headerAlign="center" allowSort="true">联系人电话</div>
				<div field="address" width="200" headerAlign="center" allowSort="true">地址</div>
            </div>
        </div>
    
    </div>                
    <div class="mini-toolbar" style="text-align:center;padding-top:8px;padding-bottom:8px;" borderStyle="border:0;">
        <a class="mini-button" iconCls="icon-ok" onclick="onOk()">确定</a>
        <span style="display:inline-block;width:25px;"></span>
        <a class="mini-button" iconCls="icon-cancel" onclick="onCancel()">取消</a>
    </div>

</body>
</html>
<script type="text/javascript">
    mini.parse();

    var grid = mini.get("grid1");

    //动态设置URL
	var displayStock = "<?=$displayStock?>";
    grid.setUrl("index.php?m=system&c=popupSelect&a=getDealerList&displayStock="+displayStock);
    //也可以动态设置列 grid.setColumns([]);

    grid.load();


    ////////////////////////////////////////////////////////////////////////////////

    grid.on("load", function (e) {
        if (firstLoad) {
            firstLoad = false;
            if (initIds) {
                selectRowsByIds(initIds);
            }
        }
    });

    var firstLoad = true;
    var initIds; //存放初始数据id，这个作为选中数据。

    function selectRowsByIds(ids) {
        if (ids) {
            var rows = [];
            for (var i = 0, l = ids.length; i < l; i++) {
                var o = grid.getRow(ids[i]);
                if (o) rows.push(o);
            }
            grid.selects(rows);
        }
    }

    function SetData(ids) {
        if (typeof ids == "string") {
            ids = ids.split(",");     //"1,2" => [1, 2]
        }
        initIds = ids;
    }

    function GetSelecteds() {
        var rows = grid.getSelecteds();
        return rows;
    }
	function GetData() {
        var rows = grid.getSelecteds();
		var data = "";
        var ids = [], texts = [];
        for (var i = 0, l = rows.length; i < l; i++) {
            var row = rows[i];
			//data.i.prd_no 	= row.prd_no;
			//data.i.prd_name = row.prd_name;
        }
        return rows;
    }
    function GetData111() {
        var rows = grid.getSelecteds();
        var ids = [], texts = [];
        for (var i = 0, l = rows.length; i < l; i++) {
            var row = rows[i];
            ids.push(row.id);
            texts.push(row.name);
        }
        var data = {};
        data.id = ids.join(",");
        data.text = texts.join(",");
        return data;
    }

    function search() {
        var dealer_no 	= mini.get("dealer_no").getValue();
        var dealer_name = mini.get("dealer_name").getValue();
        grid.load({ dealer_no: dealer_no, dealer_name: dealer_name });
    }
    function onKeyEnter(e) {
        search();
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