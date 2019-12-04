<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<?
		include_once("../html/js/boot.php");
	?>
	
	<script src="js/distpicker.js" type="text/javascript" ></script>
	<script src="js/distpicker.data.js" type="text/javascript"></script>
	
	<title>结算单</title>
	<style>
		body{padding:15px}
		legend{font-size:12px}
		.head-box table{width:100%;}
		.head-box table tr td select{height:24px;}
		.New_Button, .Edit_Button, .Delete_Button, .Update_Button, .Cancel_Button {
			font-size: 11px;
			color: #1B3F91;
			font-family: Verdana;
			margin-right: 5px;
		}
	</style>
</head>
<body style="height:100%"> 
<div class="mini-fit" style="background:white;">
			<div class="divcss" style="margin-top: 10px;"><div class="spancss">结算客户信息</div></div>	
			<table style="table-layout:fixed;">
				<tr>
					<td style="width:80px;">经销商：</td>
					<td style="width:200px;">
						<input class="mini-buttonedit" width="200px" onbuttonclick="onSelectDealer" id="dealer_no" name="dealer_no" allowInput="false"/>
					</td>
					<td style="width:80px;">结算日期：</td>
					<td style="width:200px;">
						<input id="end_date" name="end_date" width="200px" class="mini-datepicker" onvaluechanged="clearList"/>
					</td>
					<td style="width:80px;">
                    	<a class="mini-button" onclick="createSettlementList" style="width:60px;margin-right:20px;">计算</a>
                    </td>
					<td style="width:150px;">
						<span id="allMoney"></span>
					</td>
				</tr>
				
			</table>
        <div class="divcss"><div class="spancss">订单商品信息</div></div>
        <div class="mini-fit" style="background:white;">
            <div id="datagrid1" class="mini-datagrid" style="width:100%;height:97%" url="" multiSelect="true" allowCellEdit="true" allowAlternating="true" allowCellSelect="true" idField="id" showPager="false">
                <div property="columns">
                    <!--<div type="indexcolumn"></div> -->
                    <div type="checkcolumn" ></div>         
                    <div field="settlement_date" width="120" headerAlign="center" allowSort="">结算截止日期</div>  
                    <div field="bil_type" width="120" headerAlign="center" allowSort="">单据类型</div> 
                    <div field="bil_no" width="120" headerAlign="center" allowSort="">单据编号</div> 
                    <div field="prd_name" width="120" headerAlign="center" allowSort="">货品名称</div> 
                    <div field="bat_no" width="120" headerAlign="center" allowSort="">批号</div> 
                    <div field="handing_money" width="120" headerAlign="center" allowSort="">装卸费</div>  
                    <div field="storage_money" width="120" headerAlign="center" allowSort="">仓储费</div>  
                    <div field="order_money" width="120" headerAlign="center" allowSort="">单费用</div>
                    <div field="sorting_money" width="120" headerAlign="center" allowSort="">分拣费</div>
                    <div field="box_money" width="120" headerAlign="center" allowSort="">件费用</div>
                    <div field="plastic_film_money" width="120" headerAlign="center" allowSort="">打膜费</div>
                    <div field="points_money" width="120" headerAlign="center" allowSort="">扣点费</div>
                </div>
            </div>
            
        </div>
		<div style="text-align:center;padding:10px;">
			<a class="mini-button" onclick="onOk" style="width:60px;margin-right:20px;" id="save2">确定</a>
			<a class="mini-button" onclick="onCancel" style="width:60px;">取消</a>
		</div>
</div>	
	 
	<script>
		mini.parse();
		var grid = mini.get("datagrid1");
		function clearList()
		{
			mini.get("datagrid1").setData({});	
		}
		
		function createSettlementList()
		{
			var dealer_no = mini.get("dealer_no").getValue();
			var end_date = mini.get("end_date").getFormValue();
			mini.get("datagrid1").setData({});
			if(!dealer_no)
			{
				mini.alert("请选择经销商");
			}
			if(!end_date)
			{
				mini.alert("请选择结算截止日期");
			}
			$.ajax({
                url: "index.php?m=wms&c=settlement&a=createSettlementList",
		        type: 'post',
                data: {dealer_no: dealer_no, end_date: end_date},
				dataType:'json',
                cache: false,
                success: function (data) {
					if(data.code == 'ok'){
						mini.get("datagrid1").setData(data.data);
					}else{
						mini.alert(data.msg);
					}
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    mini.alert(jqXHR.responseText);
                }
            });
		}
        

		grid.on("drawcell", function (e) {
			var record = e.record,
			column = e.column,
			field = e.field,
			row = e.row,
			value = e.value;
			e.rowStyle = "background:#fff;";
			//alert(record.tid.substring(0,2));
			if(field == "bil_type"){
				if(value == 'storage')
				{
					e.cellHtml  = "库存";	
				}
				else if(value == 'in_stock')
				{
					e.cellHtml  = "入库";	
				}
				else if(value == 'out_stock')
				{
					e.cellHtml  = "出库";	
				}
			}
		})

		function onRemove(e){
			var rows = grid.getSelecteds();
			var data = grid.data;
			if (rows.length > 0){
				grid.removeRows(rows, true);
				grid.addRow({option:"<a onClick='onAddadd()' style='color: #0088FF; text-decoration:underline'>插入</a> <a onClick='delRow()' style='color: #0088FF; text-decoration:underline'>删除</a>"});
				total_calculation();
			}
		}

		function onAddadd(e){
			var grid = mini.get("datagrid2");
			var row = grid.getSelected();
			var index = grid.indexOf(row);
			grid.addRow({option:"<a onClick='onAddadd()' style='color: #0088FF; text-decoration:underline'>插入</a> <a onClick='delRow()' style='color: #0088FF; text-decoration:underline'>删除</a>"},index);
		}


		function onActionRenderer(e) {
			var grid = e.sender;
			var record = e.record;
			var uid = record._uid;
			var rowIndex = e.rowIndex;

			var s = '<a class="New_Button" href="javascript:newRow()">插入</a>'
					+ ' &nbsp;&nbsp; <a class="Delete_Button" href="javascript:delRow(\'' + uid + '\')">删除</a>';
			return s;
		}

		function newRow() {
			var row = {option:"<a onClick='newRow()' style='color: #0088FF; text-decoration:underline'>插入</a> <a onClick='delRow()' style='color: #0088FF; text-decoration:underline'>删除</a>"};
			grid.addRow(row, 0);

			grid.cancelEdit();
			grid.beginEditRow(row);
		}
		
		function delRow(row_uid) {
			var row = grid.getSelected();
			if (row) {
				if (confirm("确定删除此记录？")) {
					//grid.loading("删除中，请稍后......");
					grid.removeRow(row);
				}
			}
		}

		function search() {
			var area_no	= mini.get("area_no").getValue();
			var wh_no 	= mini.get("wh_no").getValue();
			var wh_name	= mini.get("wh_name").getValue();
			grid.load({area_no:area_no, wh_no:wh_no, wh_name:wh_name});
		}
		function onKeyEnter(e){
			search();
		}
		
		function CloseWindow(action){
            if (action == "close" && form.isChanged()){
                if (confirm("数据被修改了，是否先保存？")){
                    return false;
                }
            }
            if (window.CloseOwnerWindow) return window.CloseOwnerWindow(action);
            else window.close();
        }
        function onOk(e) {
            var btn = mini.get("save2");
            btn.disable();
			setTimeout(function(){
				btn.enable();
			},500);
            SaveData();
        }
		
		function SaveData()
		{
			var dealer_no = mini.get("dealer_no").getValue();
			var end_date = mini.get("end_date").getFormValue();
			$.ajax({
                url: "index.php?m=wms&c=settlement&a=saveSettlement",
		        type: 'post',
                data: {dealer_no: dealer_no, end_date: end_date},
				dataType:'json',
                cache: false,
                success: function (data) {
					if(data.code == 'ok'){
						mini.alert("生成结算成功");
						CloseWindow("save");
					}else{
						mini.alert(data.msg);
					}
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    mini.alert(jqXHR.responseText);
                }
            });	
		}
		
        function onCancel(e) {
            CloseWindow("cancel");
        }
		
		//选择经销商
		function onSelectDealer(e)
		{
			selectDealer(0,function(data){
				if (data) {
					mini.get('dealer_no').setValue(data[0].dealer_no);
					mini.get('dealer_no').setText(data[0].dealer_name);
				}
				grid.cancelEdit();
				clearList();
			});
		}
		//选择所属客户
		function onSelectCust(e)
		{
			selectCustomer(function(data){
				if (data) {
					var res = data[0];
					mini.get('cust_no').setValue(res.cust_no);
					mini.get('cust_no').setText(res.cust_name);
					mini.get("receiver_name").setValue(res.link_man);
					mini.get("receiver_mobile").setValue(res.link_mobile);
					if(res.province != ""){
						$("#province").val(res.province);
						$("#province option[value='"+res.province+"']").change();
						$("#city").val(res.city);
						$("#city option[value='"+res.city+"']").change();
						$("#district").val(res.district);
						mini.get("receiver_address").setValue(res.address);
					}
				}
			});
		}
		
		////////////////////////////////////////////////////////
		function onUrgent(){
			var is_urgent = mini.get("is_urgent").getValue();
			if(is_urgent == 0){
				$("#tr_urgent").hide();
			}else{ // 加急
				$("#tr_urgent").show();
			}
		}
		function onUrgentTimeType(){
			var urgent_time_type = mini.get("urgent_time_type").getValue();
			if(urgent_time_type == 0){
				$(".urgent_time_set").show();
			}else{ // 加急
				$(".urgent_time_set").hide();
			}
		}
		
	</script>
	<?
		include_once("../html/js/myAppBoot.php");
	?>
	
	<script src="js/plug-in/bootstrap/js/bootstrap.js"></script>
	<script src="js/plug-in/layui-2.0/layui.js"></script>
</body>

</html>
