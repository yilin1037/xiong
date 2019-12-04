<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<?
		include_once("../html/js/boot.php");
	?>
	<script src="js/distpicker.js" type="text/javascript" ></script>
	<script src="js/distpicker.data.js" type="text/javascript"></script>
	
	<title>查看报损</title>
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
			<table style="table-layout:fixed;">
				<tr>
					<td style="width:80px;">经销商：</td>
					<td style="width:150px;">
						<input class="mini-buttonedit" onbuttonclick="onSelectDealer" id="dealer_no" name="dealer_no"  value="<?php echo $show['dealer_no'];?>" text="<?=$dealer['dealer_name']?>" allowInput="false"/>
					</td>
					<td style="width:80px;">来源单号：</td>
                    <td style="width:150px;">
                        <input name="bill_no" id="bill_no" class="mini-textbox" readonly="readonly" value="<?php echo $show['bill_no'];?>" />
                    </td>
					<td style="width:80px;">入到仓库：</td>
					<td style="width:150px;">
						<input name="wh_no" id="wh_no" class="mini-combobox" url="index.php?m=system&c=wh&a=getwhList" textField="wh_name" valueField="wh_no" emptyText="请选择..." value="<?php echo $show['wh_no'];?>" />
					</td>
				</tr>
				<tr>
					<td style="width:80px;">订单日期：</td>
					<td style="width:150px;">
						<input id="order_time" name="order_time" class="mini-datepicker" value="<?php echo $show['order_time'];?>"/>
					</td>
					<td style="width:80px;">备注：</td>
					<td colspan="3">
						<input name="remark" class="mini-textbox" style="width:85%;" value="<?php echo $show['remark'];?>" />
					</td>
				</tr>
			</table>
		</div>
		<input name="id" class="mini-hidden" value="<?php echo $show['id']?>" />
		<div class="mini-fit" style="background:white;height:300px;">
			<div id="datagrid1" class="mini-datagrid" style="width:100%;height:300px" url="index.php?m=system&c=damageOrders&a=getItemList&order_no=<?=$show['order_no']?>" multiSelect="true" allowCellEdit="true" allowAlternating="true" allowCellSelect="true" idField="id" showPager="false">
				<div property="columns">
					
					<div field="prd_no" name="prd_no" displayField="prd_no" width="120" headerAlign="center" allowSort="">商品编号
						<input property="editor" class="mini-buttonedit" allowInput="false" onbuttonclick="onSelectPrdt"/>
					</div>
					<div field="prd_name" width="120" headerAlign="center" allowSort="">商品名称</div>
					<div field="loc_name" width="120" headerAlign="center" allowSort="">货位名称</div>
					<div field="spec" width="60" headerAlign="center" allowSort="">规格
						<input class="mini-textbox" style="width:100%;" readonly="readonly"/>
					</div>
					<div field="unit_name" width="60" headerAlign="center" allowSort="">单位</div>
					
					<div field="add_unit_num" width="60" headerAlign="center" allowSort="">数量
						<input property="editor" class="mini-textbox" style="width:100%;"/>
					</div>
					<div field="unit_name1" width="60" headerAlign="center" allowSort="">副单位</div>
					
					<div field="add_unit_num1" width="60" headerAlign="center" allowSort="">副数量
						<input property="editor" class="mini-textbox" style="width:100%;"/>
					</div>
					<div field="add_box_num" width="120" headerAlign="center" allowSort="">箱数量
						<input property="editor" class="mini-textbox" style="width:100%;"/>
					</div>
					
					<div field="bat_no" width="90" headerAlign="center" allowSort="">批号</div>
					
					<div field="remark" width="80" headerAlign="center" allowSort="">备注
						<input property="editor" vtype="float" allowInput="true" class="mini-textBox" />
					</div>
				</div>
			</div>
		</div>
		<div style="text-align:center;padding:10px;">
			<a class="mini-button" onclick="onOk" style="width:60px;margin-right:20px;">关闭</a>
		</div>
	</form>
	
	<script>
		mini.parse();
		
        var form = new mini.Form("form1");
        function SaveData() {
            var o = form.getData();
			
			form.validate();
            if (form.isValid() == false) return;
			
            var json = mini.encode(o);
			var dataItem = grid.findRows();
			//var rowData = grid.getChanges();
			//var json2 = mini.encode(rowData);
            $.ajax({
                url: "index.php?m=system&c=damageOrders&a=edit",
		        type: 'post',
                data: {data: json, data2: dataItem },
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
			if(e['result']['total'] == 0){
				grid.addRow({option:"<a onClick='newRow()' style='color: #0088FF; text-decoration:underline'>插入</a> <a onClick='delRow()' style='color: #0088FF; text-decoration:underline'>删除</a>"});
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
				/* if(e.value > parseInt(e.row['unit_num'])){
					mini.showTips({
						content: "下单数量不能大于库存",
						state: 'danger',
						x: 'center',
						y: 'top',
						timeout: 3000
					});
					e.cancel = true;
					return;	
				} */
				e.row['add_unit_num1'] = e.value/e.row['unit_rate1'];
				if(e.row['box_rate']){
					e.row['add_box_num'] = e.value/e.row['box_rate'];	
				}
				grid.updateRow(e.row);
			}
			else if(e.field == "add_unit_num1")
			{
				e.row['add_unit_num'] = Math.ceil(e.value/(e.row['unit_rate1']) ? e.row['unit_rate1'] : 1);
				if(e.row['box_rate'])
				{
					e.row['add_box_num'] = e.row['add_unit_num']/e.row['box_rate'];	
				}
				else
				{
					e.row['add_box_num'] = '';	
				}
				grid.updateRow(e.row);
			}
			else if(e.field == "add_box_num")
			{
				e.row['add_unit_num'] = Math.ceil(e.value*(e.row['box_rate']) ? Math.ceil(e.row['box_rate']) : 1);
				e.row['add_unit_num1'] = e.row['add_unit_num']/e.row['unit_rate1'];
				grid.updateRow(e.row);
			}
			
		})
		

		function onSelectPrdt(e)
		{
			var dealer_no = mini.get('dealer_no').getValue();
			selectProduct({displayStock:1, multiSelect:1, dealer_no:dealer_no},function(data){
				for(var i = 0; i < data.length; i++)
				{
					console.log((data));
					var row = grid.findRow(function(row){
						var row_PRD_NO = row.prd_no;
						if(typeof(row_PRD_NO) == "undefined"){
							row_PRD_NO = "";
						}
						if($.trim(row_PRD_NO) == $.trim(data[i].prd_no))
						{
							return true;
						}
					});
					var arr = new Object();
					if(!row)//货品不存在
					{
						arr = mini.clone(data[i]);
						arr['option'] = "<a onClick='onAddadd()' style='color: #0088FF; text-decoration:underline'>插入</a> <a onClick='delRow()' style='color: #0088FF; text-decoration:underline'>删除</a>";
						arr['option']
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
			var row = grid.getRowByUID(row_uid);
			if (row.id) {
				if (confirm("确定删除此记录？")) {
					grid.loading("删除中，请稍后......");
					$.ajax({
						url: "index.php?m=system&c=damageOrders&a=delItem&id=" + row.id,
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
			}else{
				grid.load();
			}
		}
		
		//搜索
		function search() {
			var area_no	= mini.get("area_no").getValue();
			var wh_no 	= mini.get("wh_no").getValue();
			var wh_name	= mini.get("wh_name").getValue();
			grid.load({area_no:area_no, wh_no:wh_no, wh_name:wh_name});
		}
		function onKeyEnter(e) {
			search();
		}
		
		function GetData() {
            var o = form.getData();
            return o;
        }
		function CloseWindow(action) {            
            if (action == "close" && form.isChanged()) {
                if (confirm("数据被修改了，是否先保存？")) {
                    return false;
                }
            }
            if (window.CloseOwnerWindow) return window.CloseOwnerWindow(action);
            else window.close();            
        }
function onOk(e) {
        var btn = mini.get("save2");
        btn.disable();
        SaveData();
    }
        function onCancel(e) {
            CloseWindow("cancel");
        }
		
		//选择经销商
		function onSelectDealer(e)
		{
			selectDealer(0,function(data){
				if (data){
					mini.get('dealer_no').setValue(data[0].dealer_no);
					mini.get('dealer_no').setText(data[0].dealer_name);
				}
				grid.cancelEdit();
			});
		}
		
		
		//设置仓库名称
		mini.get("wh_no").setValue('<?=$show['wh_no']?>');
		mini.get("wh_no").setText('<?=$show['wh_name']?>');
		
		//设置订单时间
		mini.get("order_time").setValue('<?=date('Y-m-d',$show['order_time'])?>');
		mini.get("order_time").setText('<?=date('Y-m-d',$show['order_time'])?>');
		
	</script>
	
	<?
		include_once("../html/js/myAppBoot.php");
	?>
	
	<script src="js/plug-in/bootstrap/js/bootstrap.js"></script>
	<script src="js/plug-in/layui-2.0/layui.js"></script>
</body>

</html>
