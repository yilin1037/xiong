<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <?
    include_once("../html/js/boot.php");
    ?>
    <link rel="stylesheet" href="js/plug-in/bootstrap/css/bootstrap.css" />
    <link rel="stylesheet" href="js/plug-in/layui-2.0/css/layui.css" />

    <title>选择客户</title>
    <style type="text/css">
        html, body{
            margin:0;padding:0;border:0;width:100%;height:100%;overflow:hidden;
        }
    </style>
</head>

<body>

<div class="mini-fit">

    <div class="mini-toolbar" style="text-align:center;line-height:30px;" borderStyle="border:0;">
        <tr>
            <td style="padding:0 0 5px 0;">
                下单时间:
                <input name="date_begin" id="date_begin" class="mini-datepicker" format="yyyy-MM-dd" emptyText="开始时间" style="width:150px;" onenter="onKeyEnter" />
                <input name="date_end" id="date_end" class="mini-datepicker" format="yyyy-MM-dd" emptyText="结束时间" style="width:150px;" onenter="onKeyEnter" />
                <input name="tid" id="tid" class="mini-textbox" emptyText="订单编号" style="width:190px;" onenter="onKeyEnter" />
                <input name="cust_name" id="cust_name" class="mini-textbox" emptyText="客户名称" style="width:150px;" onenter="onKeyEnter" />
                <a class="mini-button" iconCls="icon-search" onclick="search()">查询</a>
            </td>
        </tr>
    </div>

    <div id="grid1" class="mini-datagrid" style="width:100%;height:80%;"
         idField="id" allowResize="true"
         borderStyle="border-left:0;border-right:0;">
        <div property="columns">
            <div type="checkcolumn" ></div>

            <div field="tid" width="120" headerAlign="center" allowSort="" align="center">订单号</div>
            <div field="send_status" width="120" headerAlign="center" align="center">订单状态</div>
            <div field="cust_name" width="120" headerAlign="center" align="center">客户名称</div>
            <div field="addtime" width="120" headerAlign="center" align="center">添加时间</div>
            <div field="address" width="120" headerAlign="center">收货地址</div>
            <div field="express_type" width="120" headerAlign="center" align="center">配送方式</div>
            <div field="express_status" width="120" headerAlign="center" align="center">配送状态</div>
            <div field="send_time" width="120" headerAlign="center" align="center">收货日期</div>
            <div field="three_pl_esDate" width="120" headerAlign="center" align="center">承诺送达时间</div>
            <div field="remark" width="120" headerAlign="center" allowSort="">备注</div>
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
    grid.setUrl("index.php?m=system&c=order&a=getList&send_status=2");
    //也可以动态设置列 grid.setColumns([]);
    grid.load();
    ///////////////////////////////////////////////////////

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

    function SetData(dataSet) {

    }

    function GetSelecteds() {
        var rows = grid.getSelecteds();
        return rows;
    }
    function GetData() {
        var rows = grid.getSelecteds();
        for (var i = 0, l = rows.length; i < l; i++) {
            var row = rows[i];
        }
        return rows;
    }

    function search() {
        var date_begin  = mini.get("date_begin").getFormValue().trim();
        var date_end	= mini.get("date_end").getFormValue().trim();
        var tid 		= mini.get("tid").getValue();
        var cust_name	= mini.get("cust_name").getValue();

        grid.load({date_begin:date_begin, date_end:date_end, tid:tid, cust_name:cust_name });
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