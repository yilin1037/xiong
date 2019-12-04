<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<?
		include_once("../html/js/boot.php");
	?>
	
	<script src="js/distpicker.js" type="text/javascript" ></script>
	<script src="js/distpicker.data.js" type="text/javascript"></script>
	
	<title>选择仓库货位Tree</title>
	<style>
		html, body{
			margin:0;padding:0;border:0;width:100%;height:100%;overflow:hidden;
		}
	</style>
</head>


<body>

<div class="mini-fit">

<div class="mini-toolbar" style="text-align:center;line-height:30px;" borderStyle="border:0;">
          <label >名称：</label>
          <input id="key" class="mini-textbox" style="width:150px;" onenter="onKeyEnter"/>
          <a class="mini-button" style="width:60px;" onclick="search()">查询</a>
    </div>
    <div class="mini-fit">

        <div id="grid1" class="mini-datagrid" style="width:100%;height:100%;" 
            idField="id" allowResize="true"
            borderStyle="border-left:0;border-right:0;" onrowdblclick="onRowDblClick">
            <div property="columns">
                <div type="checkcolumn"></div>
                <div field="loc_no" width="120" headerAlign="center" align="center" allowSort="true">货位编号</div>    
                <div field="loc_name" width="100%" headerAlign="center" align="center">货位名称</div>
            </div>
        </div>
    
    </div>                
    <div class="mini-toolbar" style="text-align:center;padding-top:8px;padding-bottom:8px;" borderStyle="border:0;">
        <a class="mini-button" style="width:60px;" onclick="onOk()">确定</a>
        <span style="display:inline-block;width:25px;"></span>
        <a class="mini-button" style="width:60px;" onclick="onCancel()">取消</a>
    </div>


<script type="text/javascript">
    mini.parse();

    var grid = mini.get("grid1");

    //动态设置URL
	var wh_no = "<?=$request['wh_no']?>";
    grid.setUrl("index.php?m=system&c=moveOrder&a=getWhLocList&wh_no="+wh_no);
    //也可以动态设置列 grid.setColumns([]);

    grid.load();

    function GetData() {
        var row = grid.getSelected();
        return row;
    }
    function search() {
        var key = mini.get("key").getValue();
        grid.load({ key: key });
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

</div>

<?
	include_once("../html/js/myAppBoot.php");
?>
<script src="js/popupSelect.js"></script>
<script src="js/plug-in/bootstrap/js/bootstrap.js"></script>
<script src="js/plug-in/layui-2.0/layui.js"></script>
</body>

</html>
