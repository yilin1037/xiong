<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<?
		include_once("../html/js/boot.php");
	?>
	
	<title>新增客户</title>
	<style>
		body{padding:15px}
		legend{font-size:12px}
		table{width:100%;}
		table tr td{padding:5px 0;}
		table tr td select{height:24px;}
	</style>
</head>


<body>
	
<form id="form1" method="post">
	<input name="id" class="mini-hidden" />
	<div style="padding-left:11px;padding-bottom:5px;">
		<table style="table-layout:fixed;">
			<tr>
				<td style="width:80px;">部门名称：</td>
				<td>
					<input name="dept_name" id="dept_name" class="mini-textbox" required="true"  emptyText=""/>
				</td>
			</tr>
			</tr>
				<td style="width:80px;">所属部门：</td>
				<td>
					<input name="parent_dept_no" class="mini-combobox" valueField="dept_no" textField="dept_name" url="index.php?m=system&c=dept&a=getDeptTopList" emptyText="请选择..." />
				</td>
			</tr>
			
			</tr><td colspan="2" style="height:15px"></td></tr>
			</tr>
				<td></td>
				<td>
					<a class="mini-button" iconCls="icon-ok" onclick="onOk" style="margin-right:20px;">确定</a>       
					<a class="mini-button" iconCls="icon-cancel" onclick="onCancel">取消</a>
				</td>
			</tr>
		</table>
	</div>
	
	
</form>


<script type="text/javascript">
	mini.parse();
	var form = new mini.Form("form1");
	function SaveData() {
		var o = form.getData();
		
		form.validate();
		if (form.isValid() == false) return;
		
		var json = mini.encode(o);
		$.ajax({
			url: "index.php?m=system&c=dept&a=add",
			type: 'post',
			data: { data: json },
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
		SaveData();
	}
	function onCancel(e) {
		CloseWindow("cancel");
	}
	
	function onCloseClick(e){
		var obj = e.sender;
		obj.setText("");
		obj.setValue("");
	}

</script>
</body>

</html>
