<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8" />
	<?
	include_once("../html/js/boot.php");
	?>
	<link rel="stylesheet" href="js/plug-in/bootstrap/css/bootstrap.css" />
	<link rel="stylesheet" href="js/plug-in/layui-2.0/css/layui.css" />

	<title>查看入库单</title>
	<style>
		body {
			padding: 15px
		}

		legend {
			font-size: 12px
		}

		.head-box table {
			width: 100%;
		}

		.head-box table tr td {
			padding: 5px 0;
		}

		.head-box table tr td select {
			height: 24px;
		}

		.New_Button,
		.Edit_Button,
		.Delete_Button,
		.Update_Button,
		.Cancel_Button {
			font-size: 11px;
			color: #1B3F91;
			font-family: Verdana;
			margin-right: 5px;
		}
	</style>
</head>


<body>

	<form id="form1" method="post">
		<div class="head-box" style="padding-left:11px;padding:5px;">
			<input class="mini-hidden" name="id" />
			<table style="width:100%;">
				<tr>
					<td style="width:80px;padding:5px">经销商：</td>
					<td style="width:150px;">
						<input class="mini-buttonedit" onbuttonclick="onSelectDealer" id="dealer_no" name="dealer_no" value="<?php echo $show['dealer_no']; ?>" text="<?php echo $show['dealer_name']; ?>" allowInput="false" />
					</td>
					<td style="width:80px;">来源单号：</td>
					<td style="width:150px;">
						<input name="bill_no" id="bill_no" class="mini-textbox" readonly="readonly" value="<?php echo $show['bill_no']; ?>" />
					</td>
					<td style="width:80px;padding:5px">装卸队：</td>
					<td style="width:150px;">
						<input class="mini-buttonedit" onbuttonclick="onHandingEdit" id="handing_no" name="handing_no" value="<?php echo $show['handing_no']; ?>" text="<?= $handing['handing_name'] ?>" allowInput="false" />
					</td>
				</tr>
				<tr>
					<td style="width:80px;padding:5px">入库类型：</td>
					<td style="width:150px;">
						<select id="in_type" name="in_type" class="mini-combobox" style="width:150px;">
							<option value="0" <?php if ($show['in_type'] == 0) {
													echo 'selected';
												} ?>>正常入库</option>
							<option value="1" <?php if ($show['in_type'] == 1) {
													echo 'selected';
												} ?>>退货入库</option>
						</select>
					</td>
					<td style="width:80px;padding:5px">交货方式：</td>
					<td style="width:150px;">
						<select id="delivery_type" name="delivery_type" class="mini-combobox" style="width:150px;">
							<option value="0" <?php if ($show['delivery_type'] == 0) {
													echo 'selected';
												} ?>>到库</option>
							<option value="1" <?php if ($show['delivery_type'] == 1) {
													echo 'selected';
												} ?>>派车</option>
						</select>
					</td>
					<td style="width:80px;padding:5px">入库仓库：</td>
					<td style="width:150px;">
						<input name="wh_no" id="wh_no" class="mini-combobox" url="index.php?m=system&c=wh&a=getwhList" textField="wh_name" valueField="wh_no" emptyText="请选择..." value="<?php echo $show['wh_no']; ?>" />
					</td>
				</tr>
				<tr>
					<td style="width:80px;padding:5px">订单日期：</td>
					<td style="width:150px;">
						<input id="order_time" name="order_time" class="mini-datepicker" value="<?php echo $show['order_time']; ?>" />
					</td>
					<td style="width:90px;padding:5px">预计到货日期：</td>
					<td style="width:150px;">
						<input id="arrive_time" name="arrive_time" class="mini-datepicker" value="<?php echo $show['arrive_time']; ?>" />
					</td>

					<td style="width:90px;">需要打膜：</td>
					<td style="width:150px;">
						<input name="plastic_film" class="mini-checkbox" text="" value="<?= $show['plastic_film']; ?>" trueValue="1" falseValue="0" />
					</td>
				</tr>
				<tr>
					<td style="width:80px;padding:5px">备注：</td>
					<td colspan="5">
						<input name="remark" class="mini-textbox" style="width:90%;" />
					</td>
				</tr>
			</table>
		</div>
		<input name="id" class="mini-hidden" value="<?php echo $show['id'] ?>" />


		<div id="datagrid1" class="mini-datagrid" style="width:100%;height:220px" url="index.php?m=system&c=warehouseOrderNum&a=getItemList&order_no=<?= $show['order_no'] ?>" multiSelect="true" allowCellEdit="true" allowAlternating="true" allowCellSelect="true" idField="id" showPager="false">
			<div property="columns">

				<div name="action" width="120" headerAlign="center" align="center" renderer="onActionRenderer" cellStyle="padding:0;">操作</div>

				<div field="prd_no" name="prd_no" displayField="prd_no" width="80" headerAlign="center" align="center" allowSort="">商品编号
					<input property="editor" class="mini-buttonedit" allowInput="false" onbuttonclick="onSelectPrdt" />
				</div>

				<div field="prd_name" width="80" headerAlign="center" align="center" allowSort="">商品名称</div>

				<div field="spec" width="80" headerAlign="center" align="center" allowSort="">规格
					<input class="mini-textbox" style="width:100%;" readonly="readonly" />
				</div>

				<div field="unit_name" width="40" headerAlign="center" align="center" allowSort="">单位</div>

				<div field="add_unit_num" width="40" headerAlign="center" align="center" allowSort="">数量
					<input property="editor" class="mini-spinner" minValue="0" maxValue="20000" allowInput="true" class="mini-textBox" />
				</div>

				<div field="unit_name1" width="40" headerAlign="center" align="center" allowSort="">副单位</div>

				<div field="add_unit_num1" width="40" headerAlign="center" align="center" allowSort="">副数量
					<input property="editor" vtype="float" allowInput="true" class="mini-textBox" />
				</div>
				<div field="add_box_num" width="40" headerAlign="center" align="center" allowSort="">件数量
					<input property="editor" vtype="float" allowInput="true" class="mini-textBox" />
				</div>
				<div field="bat_no" width="50" headerAlign="center" align="center" allowSort="">批号
					<input property="editor" id="bot_no" class="mini-textbox" style="width:100%;" />
				</div>
				<div field="field_produce" displayField="field_produce" width="80" headerAlign="center" align="center" allowSort="">生产日期
					<input property="editor" style="width:100%;" id="produce" name="produce" class="mini-datepicker" format="yyyy/MM/dd" />
				</div>
				<div field="field_expiry_end" width="80" headerAlign="center" align="center" allowSort="">有效期至
					<input property="editor" class="mini-textbox" style="width:100%;" />
				</div>
			</div>
		</div>

		<div style="text-align:center;padding:10px;">
			<a class="mini-button" onclick="onCancel" style="width:60px;margin-right:20px;">关闭</a>

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
			//alert(json);eixt;
			$.ajax({
				url: "index.php?m=system&c=warehouseOrderNum&a=edit",
				type: 'post',
				data: {
					data: json,
					data2: dataItem
				},
				dataType: 'json',
				cache: false,
				success: function(data) {
					if (data.code == 'ok') {
						CloseWindow("save");
					} else {
						alert(data.msg);
					}
				},
				error: function(jqXHR, textStatus, errorThrown) {
					alert(jqXHR.responseText);
					CloseWindow();
				}
			});
		}

		var grid = mini.get("datagrid1");
		grid.load({}, function(e) {
			var newRow = [];
			var rows = e['result']['total'];
			if (rows == 0) {
				grid.addRow({
					option: "<a onClick='newRow()' style='color: #0088FF; text-decoration:underline'>插入</a> <a onClick='delRow()' style='color: #0088FF; text-decoration:underline'>删除</a>"
				});
			}
		});

		grid.on("drawcell", function(e) {
			var record = e.record,
				column = e.column,
				field = e.field,
				row = e.row,
				value = e.value;
			e.rowStyle = "background:#fff;";
			//alert(record.tid.substring(0,2));
			if (field == "prd_no" || field == "add_unit_num" || field == "add_unit_num1" || field == "add_box_num" || field == "remark") {
				e.cellStyle = "background:#FFFFD0;";
			}
		})

		grid.on("cellcommitedit", function(e) {
			var record = e.record;
			if (e.field == "add_unit_num") {
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
				if (e.row['unit_rate1'] > 0) {
					e.row['add_unit_num1'] = e.value / e.row['unit_rate1'];
				} else {
					e.row['add_unit_num1'] = "";
				}
				if (e.row['box_rate'] > 0) {
					e.row['add_box_num'] = e.value / e.row['box_rate'];
				} else {
					e.row['add_box_num'] = "";
				}
				grid.updateRow(e.row);
			} else if (e.field == "add_unit_num1") {
				if (e.row['unit_rate1'] > 0) {
					e.row['add_unit_num'] = Math.ceil(e.value * e.row['unit_rate1']);
				} else {
					e.row['add_unit_num'] = "";
				}
				if (e.row['box_rate']) {
					e.row['add_box_num'] = e.row['add_unit_num'] / e.row['box_rate'];
				} else {
					e.row['add_box_num'] = '';
				}
				grid.updateRow(e.row);
			} else if (e.field == "add_box_num") {
				if (e.row['box_rate'] > 0) {
					e.row['add_unit_num'] = Math.ceil(e.value * e.row['box_rate']);
				} else {
					e.row['add_unit_num'] = "";
				}
				if (e.row['unit_rate1'] > 0) {
					e.row['add_unit_num1'] = e.row['add_unit_num'] / e.row['unit_rate1'];
				}
				grid.updateRow(e.row);
			} else if (e.field == "bat_no") {
				e.row['field_produce'] = e.value.substring(0, 4) + "/" + e.value.substring(4, 6) + "/" + e.value.substring(6, 8);
				grid.updateRow(e.row);
			} else if (e.field == "field_produce") {
				var produce = mini.get("produce").getFormValue().trim();
				produce = produce.replace(/\//g, "");
				e.row['bat_no'] = produce;
				grid.updateRow(e.row);
			}
		})

		function onSelectCust() {

		}

		function onSelectPrdt(e) {
			var dealer_no = mini.get('dealer_no').getValue();
			selectProduct({
				displayStock: 0,
				multiSelect: 1
			}, function(data) {
				for (var i = 0; i < data.length; i++) {
					console.log((data));
					var row = grid.findRow(function(row) {
						var row_PRD_NO = row.prd_no;
						if (typeof(row_PRD_NO) == "undefined") {
							row_PRD_NO = "";
						}
						if ($.trim(row_PRD_NO) == $.trim(data[i].prd_no)) {
							return true;
						}
					});
					var arr = new Object();
					if (!row) //货品不存在
					{
						arr = mini.clone(data[i]);
						arr['option'] = "<a onClick='onAddadd()' style='color: #0088FF; text-decoration:underline'>插入</a> <a onClick='delRow()' style='color: #0088FF; text-decoration:underline'>删除</a>";
						arr['option']
						grid.addRow(arr, 0);
					}
				}
				grid.cancelEdit();
			});
		}

		function addDate(date, days) {
			var d = new Date(date);
			d.setDate(d.getDate() + days);
			var m = d.getMonth() + 1;
			if (m < 9) {
				m = "0" + m;
			}
			return d.getFullYear() + '' + m + '' + d.getDate();
		}

		function onRemove(e) {
			var rows = grid.getSelecteds();
			var data = grid.data;
			if (rows.length > 0) {
				grid.removeRows(rows, true);
				grid.addRow({
					option: "<a onClick='onAddadd()' style='color: #0088FF; text-decoration:underline'>插入</a> <a onClick='delRow()' style='color: #0088FF; text-decoration:underline'>删除</a>"
				});
				total_calculation();
			}
		}

		function onAddadd(e) {
			var grid = mini.get("datagrid2");
			var row = grid.getSelected();
			var index = grid.indexOf(row);
			grid.addRow({
				option: "<a onClick='onAddadd()' style='color: #0088FF; text-decoration:underline'>插入</a> <a onClick='delRow()' style='color: #0088FF; text-decoration:underline'>删除</a>"
			}, index);
		}


		function onActionRenderer(e) {
			var grid = e.sender;
			var record = e.record;
			var uid = record._uid;
			var rowIndex = e.rowIndex;

			var s = '<a class="New_Button" href="javascript:newRow()">插入</a>' +
				' &nbsp;&nbsp; <a class="Delete_Button" href="javascript:delRow(\'' + uid + '\')">删除</a>';
			return s;
		}

		function newRow() {
			var row = {
				option: "<a onClick='newRow()' style='color: #0088FF; text-decoration:underline'>插入</a> <a onClick='delRow()' style='color: #0088FF; text-decoration:underline'>删除</a>"
			};
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
						url: "index.php?m=system&c=warehouseOrderNum&a=delItem&id=" + row.id,
						success: function(text) {
							if (text.code == "error") {
								mini.alert(text.msg);
							}
							grid.reload();
						},
						error: function() {}
					});
				}
			} else {
				grid.reload();
			}
		}


		function search() {
			var area_no = mini.get("area_no").getValue();
			var wh_no = mini.get("wh_no").getValue();
			var wh_name = mini.get("wh_name").getValue();
			grid.load({
				area_no: area_no,
				wh_no: wh_no,
				wh_name: wh_name
			});
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
		function onSelectDealer(e) {
			selectDealer(0, function(data) {
				if (data) {
					mini.get('dealer_no').setValue(data[0].dealer_no);
					mini.get('dealer_no').setText(data[0].dealer_name);
				}
				grid.cancelEdit();
			});
		}

		//选择装卸队
		function onHandingEdit(e) {
			var btnEdit = this;

			mini.open({
				url: "index.php?m=system&c=handingTeam&a=handingList",
				title: "选择经销商",
				width: 800,
				height: 500,
				ondestroy: function(action) {
					//if (action == "close") return false;
					if (action == "ok") {
						var iframe = this.getIFrameEl();
						var data = iframe.contentWindow.GetData();
						data = mini.clone(data); //必须

						if (data) {
							btnEdit.setValue(data.handing_no);
							btnEdit.setText(data.handing_name);
						}
					}
				}
			});
		}

		mini.get("wh_no").setValue('<?= $show['wh_no'] ?>');
		mini.get("wh_no").setText('<?= $show['wh_name'] ?>');

		mini.get("order_time").setValue('<?= date('Y-m-d', $show['order_time']) ?>');
		mini.get("order_time").setText('<?= date('Y-m-d', $show['order_time']) ?>');

		mini.get("arrive_time").setValue('<?= date('Y-m-d', $show['arrive_time']) ?>');
		mini.get("arrive_time").setText('<?= date('Y-m-d', $show['arrive_time']) ?>');
	</script>

	<?
	include_once("../html/js/myAppBoot.php");
	?>

	<script src="js/plug-in/bootstrap/js/bootstrap.js"></script>
	<script src="js/plug-in/layui-2.0/layui.js"></script>
</body>

</html>