<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<?
		include_once("../html/js/boot.php");
	?>
	<!--上传控件-->
	<script type="text/javascript" src="/js/plug-in/miniui/swfupload/swfupload.js?v=1431676594"></script>
	<title>打印模板管理</title>
	<style type="text/css">
	html, body{
		margin:0;padding:0;border:0;width:100%;height:100%;overflow:hidden;
	}

	</style>
</head>
<body>
	<form id="saveForm" name="form1" method="post" style='width:100%;height:100%' enctype="multipart/form-data">
		<div class="mini-fit" title="center" region="center" style='padding:8px;'>
			<table style="width:100%;">
				<tbody>
					<tr>
						<td width="80">
							模版名称:							</td>
						<td>
							<input name="module_type" class='mini-hidden' id="module_type" >
							<input required="true" name="module_name" class='mini-textbox' id="module_name"/>
						</td>
					</tr>
												<tr>
							<td width="80">
								模版文件:								</td>
							<td>
								<input type="file" name='GRF_FIELD' id='GRF_FIELD' 
								class='mini-fileupload' limitType="*.grf" 
								uploadUrl="/index.php?m=system&c=print&a=fileUpload"
								flashUrl="/prints/swfupload/swfupload.swf"
								onuploadsuccess="onUploadSuccess" 
								onuploaderror="onUploadError"
								style='width:100%;'/>
							</td>
						</tr>
				</tbody>
			</table>
		</div>
		<div class="mini-toolbar" style="padding:2px;border-left:0;border-right:0;border-bottom:0;">
			<table style="width:100%;">
				<tr>
					<td style="width:100%;">
						<a class="mini-button" iconCls="icon-save" plain="true" onClick="printSave">保存</a>
					</td>
				</tr>
			</table>
		</div>
	</form>
	<?
		include_once("../html/js/myAppBoot.php");
	?>

	<style>
	.layui-table-page select{height:25px}
	</style>

</body>	
</html>
