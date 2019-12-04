<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<?
		include_once("../html/js/boot.php");
	?>
	<link rel="stylesheet" href="js/plug-in/bootstrap/css/bootstrap.css" />
	<link rel="stylesheet" href="js/plug-in/layui-2.0/css/layui.css" />
	<script src="js/popupSelect.js" type="text/javascript"></script>
	
	<title>弹窗选择商品</title>
	<style type="text/css">
	html, body{
		margin:0;padding:0;border:0;width:100%;height:100%;overflow:hidden;
	}

	</style>
</head>
<body>

<!--
<div style="width:100%;margin:5px 0">
	<div class="mini-toolbar" style="border-bottom:0;padding:0px;border:0;">
		<table style="width:100%;">
			<tr>
				<td style="white-space:nowrap;">
					<input name="key" id="key" class="mini-textbox" emptyText="快速查询" style="width:150px;" onenter="onKeyEnter" />
					
					<a class="mini-button" onclick="search()">查询</a>
				</td>
			</tr>
		</table>           
	</div>
</div>
-->


<div class="mini-toolbar" style="text-align:center;padding-top:8px;padding-bottom:8px;" borderStyle="border:0;">
	<a class="mini-button" onclick="onOk()">追加</a>
	<span style="display:inline-block;width:25px;"></span>
	
	<a class="mini-button" iconCls="icon-addnew" onclick="selectProduct(1, 1)">选择商品 - 显示库存</a>
	
	<a class="mini-button" iconCls="icon-addnew" onclick="selectProduct()">选择商品 - 不显示库存</a>
	
	<a class="mini-button" iconCls="icon-addnew" onclick="selectDealer(1)">选择经销商</a>
</div>


<style>
#productStock ul li.last{margin-bottom:15px}
#productNoStock ul li.last{margin-bottom:15px}
#selectedDealer ul li.last{margin-bottom:15px}
</style>
<div id="productStock" style="padding:15px"></div>
<div id="productNoStock" style="padding:15px"></div>
<div id="selectedDealer" style="padding:15px"></div>



<?
	include_once("../html/js/myAppBoot.php");
?>
<script src="js/plug-in/bootstrap/js/bootstrap.js"></script>
<script src="js/plug-in/layui-2.0/layui.js"></script>

<style>
.layui-table-page select{height:25px}
</style>

</body>	
</html>
