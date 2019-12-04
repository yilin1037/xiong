<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<?
		include_once("../html/js/boot.php");
	?>
	
	<script src="js/distpicker.js" type="text/javascript" ></script>
	<script src="js/distpicker.data.js" type="text/javascript"></script>
	
	<title>新增出库单</title>
	<style>

		body{padding:15px}
		legend{font-size:12px}
		.head-box table{width:100%;}
		.head-box table tr td{padding:5px 0;}
		.head-box table tr td select{height:24px;}
		
		.New_Button, .Edit_Button, .Delete_Button, .Update_Button, .Cancel_Button {
			font-size: 11px;
			color: #1B3F91;
			font-family: Verdana;
			margin-right: 5px;
		}
	</style>
</head>


<body> 
	
	<form id="form1" method="post">
		<input class="mini-hidden" name="id"/>
		<div class="head-box" style="padding-left:11px;padding-bottom:5px;">
			<div class="divcss" style="margin-top: 10px;"><div class="spancss">订单基本信息</div></div>	
			<table style="table-layout:fixed;">
				<tr>
					<td style="width:80px;">经销商：</td>
					<td style="width:150px;">
						<input class="mini-buttonedit" onbuttonclick="onSelectDealer" id="dealer_no" name="dealer_no" allowInput="false"/>
					</td>
					<td style="width:80px;">所属客户：</td>
					<td style="width:150px;">
						<input class="mini-buttonedit" onbuttonclick="onSelectCust" id="cust_no" name="cust_no" allowInput="false"/>
					</td>
					<td style="width:80px;">来源单号：</td>
                    <td style="width:150px;">    
                        <input name="bill_no" id="bill_no" class="mini-textbox" readonly="readonly" />
                    </td>
				</tr>
				<tr>
					<td style="width:80px;">装卸队：</td>
					<td style="width:150px;">
						<input class="mini-buttonedit" onbuttonclick="onHandingEdit" name="handing_no" allowInput="false"/>
					</td>
					<td style="width:80px;">出库类型：</td>
					<td style="width:150px;">
						<select id="out_type" name="out_type" class="mini-combobox" style="width:150px;">
							<option value="0">正常出库</option>

							<option value="2">出库退回(客户原因)</option>
                            <option value="1">出库退回(我司原因)</option>
						</select>
					</td>
					<td style="width:80px;">配送方式：</td>
					<td style="width:150px;">
						<select id="delivery_type" name="delivery_type" class="mini-combobox" style="width:150px;">
							<option value="0">配送</option>
							<option value="1">自提</option>
						</select>
					</td>
				</tr>
				<tr>
					<td style="width:80px;">出库仓库：</td>
					<td style="width:150px;">
						<input name="wh_no" class="mini-combobox" url="index.php?m=system&c=wh&a=getList" textField="wh_name" valueField="wh_no" emptyText="请选择..."/>
					</td>
					<td style="width:80px;">订单日期：</td>
					<td style="width:150px;">
						<input id="order_time" name="order_time" class="mini-datepicker" value="new Date()"/>
					</td>
					<td style="width:90px;">到达日期：</td>
					<td style="width:150px;">
						<input id="arrive_time" name="arrive_time" class="mini-datepicker" value="new Date()"/>
					</td>
				</tr>
				<tr>
					<td style="width:80px;">付款方式：</td>
					<td style="width:150px;">
						<select id="payment_type" name="payment_type" class="mini-combobox" style="width:150px;">
							<option value="0">现金</option>
							<option value="1">月结</option>
						</select>
					</td>
					<td style="width:80px;">备注：</td>
					<td colspan="3">
						<input name="remark" class="mini-textbox" style="width:85%;" />
					</td>
				</tr>
			</table>
			<div class="divcss" style="margin-top: 10px;"><div class="spancss">收货信息</div></div>	
			<table>
				<tr>
					<td>省市区：</td>
					<td colspan="3">
						<div data-toggle="distpicker">
							<select name="province" id="province" data-province="—— 收货省 ——" required="true"></select>
							<select name="city" id="city" data-city="—— 收货市 ——" required="true"></select>
							<select name="district" id="district" data-district="—— 收货区 ——" required="true"></select>
						</div>
					</td>
				</tr>
				<tr>
					<td>详细地址：</td>
					<td colspan="5">
						<input name="receiver_address" id="receiver_address" class="mini-textbox" style="width:90%" />
					</td>
				</tr>
				<tr>
                    <td style="width:80px;">收货人：</td>
                    <td style="width:150px;">    
                        <input name="receiver_name" id="receiver_name" class="mini-textbox"/>
                    </td>
					<td style="width:80px;">收货联系电话：</td>
                    <td style="width:150px;">    
                        <input name="receiver_mobile" id="receiver_mobile" class="mini-textbox"/>
                    </td>
					<td style="width:80px;">是否加急：</td>
                    <td style="width:150px;">    
                        <select id="is_urgent" name="is_urgent" class="mini-combobox" style="width:150px;" onvaluechanged="onUrgent">
							<option value="0">否</option>
							<option value="1">是</option>
						</select>
                    </td>
                </tr>
				
				<tr id="tr_urgent" style="display:none">
                    <td style="width:80px;">加急收货时间：</td>
                    <td style="width:150px;">    
                        <select id="urgent_time_type" name="urgent_time_type" class="mini-combobox" style="width:150px;" onvaluechanged="onUrgentTimeType">
							<option value="0">指定时间段</option>
							<option value="1">上午</option>
							<option value="2">下午</option>
						</select>
                    </td>
					<td class="urgent_time_set" style="width:80px;">加急收货开始时间：</td>
                    <td class="urgent_time_set" style="width:150px;">    
                        <input id="urgent_begin_time" name="urgent_begin_time" class="mini-timespinner"  format="H:mm" />
                    </td>
					<td class="urgent_time_set" style="width:80px;">加急收货截止时间：</td>
                    <td class="urgent_time_set" style="width:150px;">
						<input id="urgent_end_time" name="urgent_end_time" class="mini-timespinner"  format="H:mm" />
                    </td>
                </tr>
			</table>
		</div>
		<div class="divcss"><div class="spancss">订单商品信息</div></div>
		<div class="mini-fit" style="background:white;height:300px;">
			<div id="datagrid1" class="mini-datagrid" style="width:100%;height:300px" url="" multiSelect="true" allowCellEdit="true" 
			allowAlternating="true" allowCellSelect="true" idField="id" showPager="false"
			editNextOnEnterKey="true"
			editNextRowCell="true">
				<div property="columns">
					<div name="action" width="120" headerAlign="center" align="center" renderer="onActionRenderer" cellStyle="padding:0;">操作</div>
					
					<div field="prd_no" name="prd_no" displayField="prd_no" width="120" headerAlign="center" align="center" allowSort="">商品编号
						<input property="editor" class="mini-buttonedit" allowInput="false" onbuttonclick="onSelectPrdt"/>
					</div>
					
					<div field="prd_name" width="120" headerAlign="center" align="center" allowSort="">商品名称</div>
					
					<div field="spec" width="60" headerAlign="center" align="center" allowSort="">规格
						<input class="mini-textbox" style="width:100%;" readonly="readonly"/>
					</div>
					<div field="unit_name" width="60" headerAlign="center" align="center" allowSort="">单位</div>
					
					<div field="add_unit_num" width="60" headerAlign="center" align="center" allowSort="">数量
						<input property="editor" class="mini-spinner"  minValue="0" maxValue="20000" style="width:100%;"/>
					</div>
					<div field="unit_name1" width="60" headerAlign="center" align="center" allowSort="">副单位</div>
					
					<div field="add_unit_num1" width="60" headerAlign="center" align="center" allowSort="">副数量
						<input property="editor" class="mini-textbox" style="width:100%;"/>
					</div>
					<div field="add_box_num" width="120" headerAlign="center" align="center" allowSort="">件数量
						<input property="editor" class="mini-textbox" style="width:100%;"/>
					</div>
					<div type="checkboxcolumn" trueValue="1" falseValue="0" field="is_bat" width="70" headerAlign="center" align="center" allowSort="">指定批号</div>
					
					<div field="bat_no" width="90" headerAlign="center" align="center" allowSort="">批号</div>
					
					<div type="checkboxcolumn" trueValue="1" falseValue="0" field="is_gift" width="70" headerAlign="center" align="center" allowSort="">是否赠品</div>
					
					<div field="remark" width="80" headerAlign="center" align="center" allowSort="">备注
						<input property="editor" vtype="float" allowInput="true" class="mini-textBox" />
					</div>
				</div>
			</div>
		</div>
		<div style="text-align:center;padding:10px;">
			<a class="mini-button" onclick="onOk" style="width:60px;margin-right:20px;" id='save2'>确定</a>
			<a class="mini-button" onclick="onCancel" style="width:60px;">取消</a>
		</div>
	</form>
	
	 
	<script>
		mini.parse();
		
		var form = new mini.Form("form1");
        function SaveData() {
            var o = form.getData();
			
			o.receiver_province = $("#province option:selected").val();
			o.receiver_city = $("#city option:selected").val();
			o.receiver_district = $("#district option:selected").val();
			
			//收货加急开始、截止时间
			o.urgent_begin_time = mini.get("urgent_begin_time").getFormValue().trim();
			o.urgent_end_time = mini.get("urgent_end_time").getFormValue().trim();
			
			form.validate();
            if (form.isValid() == false) return;
            var json = mini.encode(o);
			//alert(json);exit;
			var rowData = grid.getChanges();
			var json2 = mini.encode(rowData);
            $.ajax({
                url: "index.php?m=system&c=outhouseOrderNum&a=add",
		        type: 'post',
                data: { data: json,data2:json2 },
				dataType:'json',
                cache: false,
                success: function (data) {
					if(data.code == 'ok'){
						CloseWindow("save");
					}else{
						alert(data.msg);
					}
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    alert(jqXHR.responseText);
                    CloseWindow();
                }
            });
        }
        
		var grid = mini.get("datagrid1");
        grid.load({},function(e){
			if(1){
				var newRow = [];
				var rows = 9 - e['result']['total'];
				rows = 0;
				if(rows > 0){
					for(var i = 0; i < rows; i++){
						newRow[i] = {option:"<a onClick='newRow()' style='color: #0088FF; text-decoration:underline'>插入</a> <a onClick='delRow()' style='color: #0088FF; text-decoration:underline'>删除</a>"};
					}
					grid.addRows(newRow);
				}else{
					grid.addRow({option:"<a onClick='newRow()' style='color: #0088FF; text-decoration:underline'>插入</a> <a onClick='delRow()' style='color: #0088FF; text-decoration:underline'>删除</a>"});
				}
			}
		});

		grid.on("drawcell", function (e) {
			var record = e.record,
			column = e.column,
			field = e.field,
			row = e.row,
			value = e.value;
			e.rowStyle = "background:#fff;";
			//alert(record.tid.substring(0,2));
			if(field == "prd_no" || field == "add_unit_num" || field == "add_unit_num1" || field == "add_box_num" || field == "remark"){
				e.cellStyle = "background:#FFFFD0;";
			}
		})

		grid.on("cellcommitedit", function (e){
			var record = e.record;
			if(e.field == "add_unit_num"){
				if(e.value > parseInt(e.row['unit_num'])){
					mini.showTips({
						content: "下单数量不能大于库存",
						state: 'danger',
						x: 'center',
						y: 'top',
						timeout: 3000
					});
					e.cancel = true;
					return;	
				}
				if(e.row['unit_rate1'] > 0){
					e.row['add_unit_num1'] = e.value/e.row['unit_rate1'];
				}else{
					e.row['add_unit_num1'] = "";
				}
				if(e.row['box_rate'] > 0){
					e.row['add_box_num'] = e.value/e.row['box_rate'];	
				}else{
					e.row['add_box_num'] = "";
				}
				grid.updateRow(e.row);
			}
			else if(e.field == "add_unit_num1"){
				if(e.row['unit_rate1'] > 0){
					e.row['add_unit_num'] = Math.ceil(e.value*e.row['unit_rate1']);
				}else{
					e.row['add_unit_num'] = "";
				}
				if(e.row['box_rate']){
					e.row['add_box_num'] = e.row['add_unit_num']/e.row['box_rate'];	
				}else{
					e.row['add_box_num'] = '';	
				}
				grid.updateRow(e.row);
			}
			else if(e.field == "add_box_num"){
				if(e.row['box_rate'] > 0){
					e.row['add_unit_num'] = Math.ceil(e.value*e.row['box_rate']);
				}else{
					e.row['add_unit_num'] = "";
				}
				if(e.row['unit_rate1'] > 0){
					e.row['add_unit_num1'] = e.row['add_unit_num']/e.row['unit_rate1'];
				}
				grid.updateRow(e.row);
			}
			
		})
		
		function onSelectPrdt(e)
		{
			var dealer_no = mini.get('dealer_no').getValue();
			if(dealer_no == ''){
				mini.alert('请选择经销商');
				return false;
			}
			selectProduct({displayStock:1, multiSelect:1, dealer_no:dealer_no},function(data){
				for(var i = 0; i < data.length; i++)
				{
					//console.log((data));
					var row = grid.findRow(function(row){
						var row_PRD_NO = row.prd_no;
						if(typeof(row_PRD_NO) == "undefined"){
							row_PRD_NO = "";
						}
						if(data[i].bat_no == "" || typeof(data[i].bat_no) == "undefined"){ // 不指定批号时验证唯一
							if($.trim(row_PRD_NO) == $.trim(data[i].prd_no)){
								return true;
							}
						}
					});
					var arr = new Object();
					if(!row)//货品不存在
					{
						arr = mini.clone(data[i]);
						arr['option'] = "<a onClick='onAddadd()' style='color: #0088FF; text-decoration:underline'>插入</a> <a onClick='delRow()' style='color: #0088FF; text-decoration:underline'>删除</a>";
						grid.addRow(arr,0);
					}
				}
				grid.cancelEdit();
			});
		}
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
		setTimeout(function() {
				btn.enable();
			}, 500);
        SaveData();
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
		//选择装卸队
		function onHandingEdit(e) {
			var btnEdit = this;
			mini.open({
				url: "index.php?m=system&c=handingTeam&a=handingList",
				title: "选择装卸队",
				width: 650,
				height: 380,
				ondestroy: function (action) {
					//if (action == "close") return false;
					if (action == "ok") {
						var iframe = this.getIFrameEl();
						var data = iframe.contentWindow.GetData();
						data = mini.clone(data);    //必须
						if (data) {
							btnEdit.setValue(data.handing_no);
							btnEdit.setText(data.handing_name);
						}
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
