<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8" />
	<?
	include_once("../html/js/boot.php");
	?>
	<script src="js/distpicker.js" type="text/javascript"></script>
	<script src="js/distpicker.data.js" type="text/javascript"></script>
	<script src="js/pinyin.js" type="text/javascript"></script>
	<title>基础设置</title>
	<style>
		body {
			padding: 15px
		}

		legend {
			font-size: 12px
		}

		table {
			width: 100%;
		}

		table tr td {
			padding: 5px 0;
		}

		table tr td select {
			height: 24px;
		}
	</style>
</head>

<body>
	<form id="form1" method="post">
		<div style="padding-left:11px;padding-bottom:5px;">
			<table style="table-layout:fixed;">

				<?php
				if ($show['data']) {
					foreach ($show['data'] as $k => $v) {
						?>
						<tr>
							<td style="width:80px;"><?= $v['show'] ?>：</td>
							<td style="width:150px;">
								<input name="configValue[<?= $k ?>]" id="configValue" class="mini-textbox" value="<?= $v['configValue'] ?>" />
								<input type="mini-hidden" name="configKey[<?= $k ?>]" class="mini-hidden" value="<?= $v['configKey'] ?>" />
								<input type="mini-hidden" name="check[<?= $k ?>]" class="mini-hidden" value="<?= $v['configValue'] ?>" />
							</td>
						</tr>
					<?php
						}
					} else {
						?>
					<tr>
						<td style="width:80px;">当日订单截止时间：</td>
						<td style="width:150px;">
							<input name="configValue[0]" id="configValue" class="mini-textbox" value="" />
							<input type="mini-hidden" name="configKey[0]" class="mini-hidden" value="tidEndHour" />
							<input type="mini-hidden" name="check[0]" class="mini-hidden" value="" />
						</td>
					</tr>
				<?php } ?>
			</table>
			</table>
		</div>

		<div style="text-align:center;padding:10px;">
			<a class="mini-button" onclick="onOk" style="width:60px;margin-right:20px;" id='save2'>确定</a>
		</div>

	</form>

	<script>
		mini.parse();
		//var grid = mini.get("datagrid1");
		//grid.load();
		var form = new mini.Form("form1");
		//console.log(form);

		//alert(1);
		function SaveData() {

			var o = form.getData();
			console.log(o);
			form.validate();
			if (form.isValid() == false) return;
			var i = 0;
			//var a = [];
			$.each(o['check'], function(k, v) {

				if (v == o['configValue'][i]) {

					//alert(1);
					o['configValue'].splice(i, 1);
					o['configKey'].splice(i, 1);
				} else {
					i++;
				}
			})

			delete o['check'];
			if (o['configKey'].length == 0) {
				alert('没有修改');
				return;
			}

			var json = mini.encode(o);
			$.ajax({
				url: "index.php?m=system&c=baseConfig&a=edit",
				type: 'post',
				data: {
					data: json
				},
				dataType: 'json',
				cache: false,
				success: function(text) {
					if (text.code == 'ok') {
						window.location.reload();
						alert(text.msg);
					} else {
						alert(text.msg);
					}
				},
				error: function(jqXHR, textStatus, errorThrown) {
					alert(jqXHR.responseText);
					CloseWindow();
				}
			});
		}


		////////////////////
		//标准方法接口定义
		function SetData(data) {
			if (data.action == "edit") {
				//跨页面传递的数据对象，克隆后才可以安全使用
				data = mini.clone(data);

				$.ajax({
					url: "../data/AjaxService.php?method=GetEmployee&id=" + data.id,
					cache: false,
					success: function(text) {
						var o = mini.decode(text);
						form.setData(o);
						form.setChanged(false);

						onDeptChanged();
						mini.getbyName("position").setValue(o.position);
					}
				});
			}
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
			setTimeout(function() {
				btn.enable();
			}, 500);
			SaveData();
		}

		function onCancel(e) {
			CloseWindow("cancel");
		}
		//////////////////////////////////
		function onDeptChanged(e) {
			var deptCombo = mini.getbyName("dept_id");
			var positionCombo = mini.getbyName("position");
			var dept_id = deptCombo.getValue();

			positionCombo.load("../data/AjaxService.php?method=GetPositionsByDepartmenId&id=" + dept_id);
			positionCombo.setValue("");
		}
	</script>
</body>

</html>