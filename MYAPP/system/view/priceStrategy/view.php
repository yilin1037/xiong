<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<?
		include_once("../html/js/boot.php");
	?>
	<script src="js/distpicker.js" type="text/javascript" ></script>
	<script src="js/distpicker.data.js" type="text/javascript"></script>
	
	<title>查看 - 价格策略</title>
	<style>
		body{padding:15px}
		legend{font-size:12px}
		.head-box table{width:100%;}
		.head-box table tr td{padding:5px 0;}
		.head-box table tr td select{height:24px;}
		.p_box{height: 28px;line-height: 28px;font-size: 14px;width:150px;height:28px;padding-left:8px;padding-right:8px;display:inline-block; border:1px solid #ccc;}
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
					<td style="width:110px;">价格策略名称：</td>
					<td style="width:250px;">
						<p class="p_box"><?php echo $show['price_name'];?></p>
					</td>
				</tr>
				<tr>
					<td style="width:80px;">备注：</td>
					<td colspan="5">						
						<p class="p_box" style="width:100%;height:50px"><?php echo $show['remark'];?></p>
					</td>
				</tr>
			</table>
		</div>
		
		
		<input name="id" class="mini-hidden" value="<?php echo $show['id']?>" />
		<div class="mini-fit" style="background:white;height:300px;">
			<div id="datagrid1" class="mini-datagrid" style="width:100%;height:300px" url="index.php?m=system&c=priceStrategy&a=getItemList&price_no=<?=$show['price_no']?>" multiSelect="true" allowCellEdit="true" allowAlternating="true" allowCellSelect="true" idField="id" showPager="false">
				<div property="columns">
					
					<div field="prd_no" name="prd_no" displayField="prd_no" width="120" headerAlign="center" allowSort="">商品编号
						<input property="editor" class="mini-buttonedit" allowInput="false"/>
					</div>
					
					<div field="prd_name" width="120" headerAlign="center" allowSort="">商品名称</div>
					
					<div field="spec" width="60" headerAlign="center" allowSort="">规格
						<input property="editor" class="mini-textbox" style="width:100%;"/>
					</div>
					<div field="unit_name" width="60" headerAlign="center" allowSort="">单位</div>
					<div field="unit_price" width="60" headerAlign="center" allowSort="">单价
						<input  style="width:100%;"/>
						<p property="editor" style="width:100%"></p>
					</div>
					
					<? if(0){?>
					<div field="add_unit_num" width="60" headerAlign="center" allowSort="">数量
						<input property="editor" class="mini-textbox" style="width:100%;"/>
					</div>
					<div field="unit_name1" width="60" headerAlign="center" allowSort="">副单位</div>
					
					<div field="add_unit_num1" width="60" headerAlign="center" allowSort="">副数量
						<input property="editor" class="mini-textbox" style="width:100%;"/>
					</div>
					<div field="add_box_num" width="60" headerAlign="center" allowSort="">箱数量
						<input property="editor" class="mini-textbox" style="width:100%;"/>
					</div>
					
					<div type="checkboxcolumn" trueValue="1" falseValue="0" field="is_bat" width="70" headerAlign="center" allowSort="" align="center">指定批号</div>
					
					<div field="bat_no" width="90" headerAlign="center" allowSort="" align="center">批号</div>
					
					<div type="checkboxcolumn" trueValue="1" falseValue="0" field="is_gift" width="70" headerAlign="center" allowSort="" align="center">是否赠品</div>
					
					<div field="remark" width="80" headerAlign="center" allowSort="">备注
						<input property="editor" vtype="float" allowInput="true" class="mini-textBox" />
					</div>
					<? } ?>
					
				</div>
			</div>
		</div>
		<div style="text-align:center;padding:10px;">
			<a class="mini-button" iconCls="icon-ok" onclick="onCancel" style="margin-right:20px;">关闭</a>
		</div>
	</form>
	
	<script>
		mini.parse();
		
        var form = new mini.Form("form1");
        function SaveData() {
            var o = form.getData();
            CloseWindow("save");
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
			if(field == "prd_no" || field == "add_unit_num" || field == "add_unit_num1" || field == "add_box_num" || field == "remark" || field=="unit_price"){
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
				e.row['add_unit_num1'] = e.value*e.row['unit_rate1'];
				if(e.row['box_rate']){
					e.row['add_box_num'] = e.value/e.row['box_rate'];	
				}
				grid.updateRow(e.row);
			}
			else if(e.field == "add_unit_num1")
			{
				e.row['add_unit_num'] = Math.ceil(e.value/(e.row['unit_rate1']) ? Math.ceil(e.row['unit_rate1']) : 1);
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
				e.row['add_unit_num1'] = e.row['add_unit_num']*e.row['unit_rate1'];
				grid.updateRow(e.row);
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
		function delRow111(row_uid) {
			var row = grid.getRowByUID(row_uid);
			if (row.id) {
				if (confirm("确定删除此记录？")) {
					grid.loading("删除中，请稍后......");
					$.ajax({
						url: "index.php?m=system&c=priceStrategy&a=delItem&id=" + row.id,
						success: function (text) {

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
		var btn = mini.get();
        btn.disable();
		console.log(btn.disable)
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
		
	</script>
	
	<?
		include_once("../html/js/myAppBoot.php");
	?>
	<script src="js/popupSelect.js"></script>
	<script src="js/plug-in/bootstrap/js/bootstrap.js"></script>
	<script src="js/plug-in/layui-2.0/layui.js"></script>
</body>

</html>
