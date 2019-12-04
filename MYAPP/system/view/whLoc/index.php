<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<?
		include_once("../html/js/boot.php");
	?>
    <link rel="stylesheet" href="js/plug-in/bootstrap/css/bootstrap.css" />
    <link rel="stylesheet" href="js/plug-in/layui-2.0/css/layui.css" />

    <script src="js/plug-in/bootstrap/js/bootstrap.js"></script>
    <script src="js/plug-in/layui-2.0/layui.js"></script>

	<title>仓库货位管理</title>
	<style type="text/css">
	html, body{
		margin:0;padding:0;border:0;width:100%;height:100%;overflow:hidden;
	}

	</style>
</head>
<body>
	
	<div id="flow" class="mini-splitter" id="refresh" style="width:100%;height:100%;">
		<div size="240" showCollapseButton="true">
			<!--<p style="padding:10px;font-size:16px;font-weight:bold">仓库列表</p>-->
			<div class="mini-fit" style="margin:20px 0 0 30px">
				<ul id="tree1" class="mini-tree" url="index.php?m=system&c=wh&a=whList" style="width:200px;padding:5px;" showTreeIcon="true" textField="wh_name" idField="wh_no" parentField="pid" resultAsTree="false" contextMenu="#treeMenu" expandOnLoad="true">
				</ul>
    
				<ul id="treeMenu" class="mini-contextmenu" onbeforeopen="onBeforeOpen">
					<li name="add" iconCls="icon-add" onclick="onAdd">新增</li>
					<li name="edit" iconCls="icon-edit" onclick="onEdit">编辑</li>
					<li name="remove" iconCls="icon-remove" onclick="onRemove">删除</li>
                </ul>
			</div>
		</div>
		
		<div  showCollapseButton="true">
			<div class="mini-toolbar" style="padding:2px;border-top:0;border-left:0;border-right:0;">
				<table style="width:100%;">
					<tr style="display:block;margin-bottom:2px">
						<td style="white-space:nowrap;">
							<input name="loc_no" id="loc_no" class="mini-textbox" emptyText="货位编号" style="width:150px;" onenter="onKeyEnter"/>
							<input name="loc_name" id="loc_name" class="mini-textbox" emptyText="货位名称" style="width:150px;" onenter="onKeyEnter"/>
							   
							<a class="mini-button" onclick="search()">查询</a>
						</td>
					</tr>
					<tr >
						<td  style="width:100%;">
							<a class="mini-button"  onclick="add()">新增</a>
							<a class="mini-button"  onclick="edit()">编辑</a>
                            <a class="mini-button"  onclick="view()">查看</a>
							<a class="mini-button"  onclick="remove()">删除</a>
							<a class="mini-button"  onclick="">打印货位条码</a>
                            <a class="mini-button" iconCls="icon-edit" id="upload" onClick="#">导入仓库货位</a>
                            <a class="mini-button" iconCls="icon-edit" onClick="location.href='/excelTemplate/仓库货位导入模板.xls?loginact=file'">导入仓库货位模板（点击下载）</a>
                        </td>
					</tr>
				</table>           
			</div>

            <!-- ======================================================================================更多操作弹窗================================================================================ -->
            <div id="edit-pages2"   style="text-align:left;display: none;padding-top:10px;position:relative;">
                <div class="container" style="width:100%;">
                    <div style="padding:20px 20px;">
                        <table style="width:100%;">
                            <thead>
                            <tr>
                                <th>位置</th>
                                <th>原因</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="item in error">
                                <td v-html="item.index"></td>
                                <td v-html="item.msg"></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- ==================================================================================更多操作弹窗结束============================================================================ -->
			
			<div class="mini-fit">
				<div id="datagrid1" class="mini-datagrid" style="width:100%;height:100%;" borderStyle="border:0;" url="index.php?m=system&c=whLoc&a=getList" idField="id" multiSelect="true" pageSize="20">
					<div property="columns">
						<!--<div type="indexcolumn"></div> -->
						<div type="checkcolumn" ></div>        
						<div field="loc_no" width="120" headerAlign="center" allowSort="true">编号</div>    
						<div field="loc_name" width="120" headerAlign="center" allowSort="true">名称</div>    
						<div field="wh_no" width="120" headerAlign="center" allowSort="">所属仓库</div>    
						<div field="loc_barcode" width="120" headerAlign="center" allowSort="">货位条码</div>  
						<div field="curator" width="120" headerAlign="center" allowSort="">仓管员</div>  
						<div field="dealer_no" width="120" headerAlign="center" allowSort="">所属经销商</div>  
						<div field="max_volume" width="120" headerAlign="center" allowSort="">最大体积</div>
						<div field="max_weight" width="120" headerAlign="center" allowSort="">最大重量</div>
						<div field="sales_man" width="120" headerAlign="center" allowSort="">已用体积</div>
						<div field="sales_man" width="120" headerAlign="center" allowSort="">已用重量</div>
						<div field="frequency" width="120" headerAlign="center" allowSort="">频次</div>
						<!--<div field="remark" width="120" headerAlign="center" allowSort="">备注</div>-->
					</div>
				</div>
			</div>
		</div>
	</div>

	<input name="wh_no" id="wh_no" class="mini-hidden" value="<?=$wh_no?>" />
	
	<?
		include_once("../html/js/myAppBoot.php");
	?>

	<style>
	.layui-table-page select{height:25px}
	</style>

</body>	
</html>
