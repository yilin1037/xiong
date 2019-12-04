<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<?
		include_once("../html/js/boot.php");
	?>
	
	<script src="js/distpicker.js" type="text/javascript" ></script>
	<script src="js/distpicker.data.js" type="text/javascript"></script>
	
	<title>新增组合满赠设置</title>
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
			<div class="divcss" style="margin-top: 10px;"><div class="spancss">基本信息</div></div>	
			<table style="table-layout:fixed;width:830px">
				<tr>
					<td style="width:50px">策略名称：</td>
					<td style="width:120px">
						<input name="sales_name" class="mini-textbox" required="true" />
					</td>
					<td style="width:50px">制单日期：</td>
					<td style="width:120px">
						<input id="addtime" name="addtime" class="mini-datepicker" value="new Date()"/>
					</td>
					<td style="width:10px"></td>
					<td style="width:120px">    
						<input name="is_multiple" class="mini-checkbox" text="是否倍数赠送" trueValue="1" falseValue="0" />
					</td>
				</tr>
			</table>
			<div class="divcss" style="margin-top: 10px;"><div class="spancss">促销时间</div></div>	
			<table style="width:600px">
				<tr>
                    <td style="width:65px;">开始日期：</td>
                    <td style="width:120px;">    
                        <input id="begin_date" name="begin_date" class="mini-datepicker" value="new Date()" required="true" />
                    </td>
					<td style="width:65px;">结束日期：</td>
                    <td style="width:120px;">
						<input id="end_date" name="end_date" class="mini-datepicker" value="new Date()" required="true" />
                    </td>
                </tr>
			</table>
			<div class="divcss" style="margin-top: 10px;"><div class="spancss">备注</div></div>	
			<table>
				<tr>
                    <td></td>
					<td colspan="5">
						<textarea name="remark" class="mini-textarea" emptyText="请输入备注" value="" style="width: 100%;height: 50px;"></textarea>
					</td>
				</tr>
			</table>
		</div>
		<div class="divcss" style="margin-top: 10px;border-bottom:0">
			<a class="mini-button" iconCls="icon-add" onClick="onAdd('grid')">增加</a>
			<a class="mini-button" iconCls="icon-remove" onClick="onRemove('grid')">删除</a> 
		</div>
		<div class="mini-fit" style="background:white;height:300px;">
			<div id="grid" class="mini-datagrid" style="width:99%;height:100%;" allowCellEdit="true" allowCellSelect="true" showPager="false" idField="id">
				<div property="columns">
					<div field="gender" name="gender" width="120" headerAlign="center" align="center" renderer="onActionRenderer" cellStyle="padding:0;">操作</div>
					<div field="groupId" name="groupId" headerAlign="center"><span style='color:red;'>*</span>组合名称</div>
					<div field="orderId" width="60" name="orderId" headerAlign="center">优先级</div>
					<div name="title1" header="组合满赠货品" headerAlign="center">
						<div property="columns">
							<div field="ZH_PRD_NO" name="ZH_PRD_NO" width="80" headerAlign="center" ><span style='color:red;'>*</span>商品编号 </div>
							<div field="ZH_PRD_NAME" name="ZH_PRD_NAME" width="140" headerAlign="center" >商品名称</div>
							<div field="ZH_UNIT" width="60" name="ZH_UNIT" headerAlign="center" align="center">单位</div>
							<div field="ZH_QTY" width="60" name="ZH_QTY" headerAlign="center" align="center"><span style='color:red;'>*</span>数量</div>
						</div>
					</div>
					<div header="赠品列表" headerAlign="center">
						<div property="columns">
							<div field="ZP_PRD_NO" width="80" headerAlign="center" ><span style='color:red;'>*</span>赠品代号</div>
							<div field="ZP_PRD_NAME" width="140" headerAlign="center" >赠品名称</div>
							<div field="ZP_UNIT" width="60" headerAlign="center" align="center">单位</div>
							<div field="ZP_QTY" width="60" headerAlign="center" align="center"><span style='color:red;'>*</span>数量</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div style="text-align:center;padding:10px;">
			<a class="mini-button" onclick="onOk" style="width:60px;margin-right:20px;" id='save2'>确定</a>
			<a class="mini-button" onclick="onCancel" style="width:60px;">取消</a>
		</div>
	</form>
	<!--组合满赠-->
	<div id="editWindow" class="mini-window" title="组合满赠" style="width:800px;height:530px;" showModal="true" allowResize="false" allowDrag="true">
		<div class="mini-fit" style="background:white;height:100%;">
            <div id="panel1" class="mini-panel" style="width:100%;height:100%;" showFooter="true" showToolbar="false" showHeader="false">
                <table style="display:block;margin-bottom:5px">
                    <tr>
                    	<td align="right"><span style="color:red;">*</span>组合名称</td>
                    	<td style="display:block;margin-left:5px" align="left"><input id="groupId" class="mini-textbox" width="300px"/>&nbsp;</td>
                        <td align="right">优先级</td>
                    	<td style="display:block;margin-left:5px" align="left"><input id="orderId" class="mini-textbox" width="100px"/>&nbsp;</td>
                    </tr>
                </table>
                <div style="width:100%;">
                    <div class="mini-toolbar" style="border-bottom:0;padding:0px;">
                        <table style="width:100%;">
                            <tr>
                                <td style="width:100%;">
                                    <a class="mini-button" iconCls="icon-add" onClick="onAdd('grid1')" style="border:0">增加</a>
                                    <a class="mini-button" iconCls="icon-remove" onClick="onRemove('grid1')" style="border:0">删除</a>       
                                </td>
                            </tr>
                        </table>           
                    </div>
                </div>
                <div id="grid1" class="mini-datagrid" style="width:100%;height:180px;" url="" multiSelect="true" allowCellEdit="true" allowAlternating="true" allowCellSelect="true" idField="id" showPager="false" onCellcommitedit="onCellcommitedit">
					<div property="columns">
                        <div type="indexcolumn" ></div>
                        <div field="prd_no" name="prd_no" displayField="prd_no" headerAlign="left" >
							<span style='color:red;'>*</span>商品编号
							<input property="editor" class="mini-buttonedit" allowInput="false" onbuttonclick="onSelectPrdt"/>
                        </div>
						<div field="prd_name" name="prd_name" headerAlign="left" >商品名称</div>
						<div field="unit_name" name="unit_name" headerAlign="left" >单位</div>
                        <div field="unit_qty" name="unit_qty" headerAlign="left" >
							<span style='color:red;'>*</span>数量                            
							<input id="unit_qty" name="unit_qty" property="editor" class="mini-spinner" minValue="0" maxValue="99999999999" decimalPlaces="2" />
                        </div>
                    </div>
                </div>
                <div style="width:100%;">
                    <div class="mini-toolbar" style="border-bottom:0;padding:0px;">
                        <table style="width:100%;">
                            <tr>
                                <td style="width:100%;">
                                    <a class="mini-button" iconCls="icon-add" onClick="onAdd('grid2')" style="border:0">增加</a>
                                    <a class="mini-button" iconCls="icon-remove" onClick="onRemove('grid2')" style="border:0">删除</a>       
                                </td>
                            </tr>
                        </table>           
                    </div>
                </div>
                <div id="grid2" class="mini-datagrid" style="width:100%;height:180px;" allowCellEdit="true" allowCellSelect="true" showPager="false" idField="id" contextMenu="#grid2Menu">
                    <div property="columns">
                        <div type="indexcolumn"></div>
                        <div field="prd_no" name="prd_no" displayField="prd_no" headerAlign="left" >
							<span style='color:red;'>*</span>赠品代号                            <input property="editor" class="mini-buttonedit" allowInput="false" onbuttonclick="onSelectPrdz"/>
                        </div>
                        <div field="prd_name" name="prd_name" headerAlign="left" >赠品名称</div>
                        <div field="unit_name" name="unit_name" headerAlign="left" >单位</div>
                        <div field="unit_qty" headerAlign="left" >
							<span style='color:red;'>*</span>数量                            
							<input id="unit_qty" name="unit_qty" property="editor" class="mini-spinner" minValue="0" maxValue="99999999999" decimalPlaces="6" />
                        </div>
                    </div>
                </div>
                <div property="footer" style="text-align:right;">
                    <a class="mini-button" iconCls="icon-save" onClick="saveObj" style="border:0">保存</a>
                    <a class="mini-button" iconCls="icon-cancel" onClick="onCancel1" style="border:0">取消</a>
                </div>
            </div>
        </div>
	</div>

</div>
	 
	<script>
		mini.parse();
		//表单提交
		var form = new mini.Form("form1");
        function SaveData() {
            var o = form.getData();
			
			o.begin_date = mini.get("begin_date").getFormValue().trim();
			o.end_date = mini.get("end_date").getFormValue().trim();
			o.addtime = mini.get("addtime").getFormValue().trim();
			
			form.validate();
            if (form.isValid() == false) return;
            var json = mini.encode(o);
			//alert(json);exit;
			var rowData = grid.getChanges();
			
			var isSet = false;
			
			$.each(rowData,function(k,v){
				
				if(v['groupId']){
					
					isSet = true;
					return false;
				}
			})
			
			if(!isSet){
				
				alert('组合信息不可为空');
				return;
			}
			
			var json2 = mini.encode(rowData);
			//console.log(json2);return;
            $.ajax({
                url: "index.php?m=system&c=promotion&a=add",
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
                }
            });
        }
        
		
		function onAdd(grid){
			var grid = mini.get(grid);
			var row = grid.getSelected();
			var index = grid.indexOf(row);
			grid.addRow({},index);
		}

		function onRemove(grid){
			var grid = mini.get(grid);
			var rows = grid.getSelecteds();
			if (rows.length > 0) {
				grid.removeRows(rows, true);
			}
		}

		
		var grid = mini.get("grid");
		onprobject();
		
        grid.on("drawcell", function (e) {
			var record = e.record,
			column = e.column,
			field = e.field,
			row = e.row,
			value = e.value;
			if(field == "ZH_PRD_NO")
			{
				e.cellHtml = "";
				e.cellStyle  = "background:#FFB;";   

				var detail = row.ZH_detail;
				if(detail)
				{
					for(var i=0;i<detail.length;i++)
					{
						e.cellHtml += '<p>'+detail[i].prd_no+'</p>';	
					}
				}
			}
			else if(field == "ZH_PRD_NAME")
			{
				e.cellHtml = "";
				e.cellStyle  = "background:#FFB;";   
				var detail = row.ZH_detail;
				if(detail)
				{
					for(var i=0;i<detail.length;i++)
					{
						e.cellHtml += '<p>'+detail[i].prd_name+'</p>';	
					}
				}
			}
			else if(field == "ZH_PRD_MARK")
			{
				e.cellHtml = "";
				e.cellStyle  = "background:#FFB;";   
				var detail = row.ZH_detail;
				if(detail)
				{
					for(var i=0;i<detail.length;i++)
					{
						e.cellHtml += '<p>'+(detail[i].RE_PRD_MARK ? detail[i].RE_PRD_MARK : '')+'&nbsp;</p>';	
					}
				}
			}
			else if(field == "ZH_UNIT")
			{
				e.cellHtml = "";
				e.cellStyle  = "background:#FFB;";   
				var detail = row.ZH_detail;
				if(detail)
				{
					for(var i=0;i<detail.length;i++)
					{
						e.cellHtml += '<p>'+detail[i].unit_name+'</p>';	
					}
				}
			}
			else if(field == "ZH_QTY")
			{
				e.cellHtml = "";
				e.cellStyle  = "background:#FFB;";   
				var detail = row.ZH_detail;
				if(detail)
				{
					for(var i=0;i<detail.length;i++)
					{
						e.cellHtml += '<p>'+detail[i].unit_qty+'</p>';	
					}
				}
			}
			else if(field == "ZP_PRD_NO")
			{
				e.cellHtml = "";
				e.cellStyle  = "background:#B0DAFF;";  
				var detail = row.ZP_detail;
				if(detail)
				{
					for(var i=0;i<detail.length;i++)
					{
						e.cellHtml += '<p>'+detail[i].prd_no+'</p>';	
					}
				}
			}
			else if(field == "ZP_PRD_NAME")
			{
				e.cellHtml = "";
				e.cellStyle  = "background:#B0DAFF;";  
				var detail = row.ZP_detail;
				if(detail)
				{
					for(var i=0;i<detail.length;i++)
					{
						e.cellHtml += '<p>'+detail[i].prd_name+'</p>';	
					}
				}
			}
			else if(field == "ZP_UNIT")
			{
				e.cellHtml = "";
				e.cellStyle  = "background:#B0DAFF;";  
				var detail = row.ZP_detail;
				if(detail)
				{
					for(var i=0;i<detail.length;i++)
					{
						e.cellHtml += '<p>'+detail[i].unit_name+'</p>';	
					}
				}
			}
			else if(field == "ZP_QTY")
			{
				e.cellHtml = "";
				e.cellStyle  = "background:#B0DAFF;";  
				var detail = row.ZP_detail;
				if(detail)
				{
					for(var i=0;i<detail.length;i++)
					{
						e.cellHtml += '<p>'+detail[i].unit_qty+'</p>';	
					}
				}
			}
			else if(field == "ZP_PRD_MARK")
			{
				e.cellHtml = "";
				e.cellStyle  = "background:#B0DAFF;";   
				var detail = row.ZP_detail;
				if(detail)
				{
					for(var i=0;i<detail.length;i++)
					{
						e.cellHtml += '<p>'+(detail[i].RE_PRD_MARK ? detail[i].RE_PRD_MARK : '')+'&nbsp;</p>';	
					}
				}
			}
			else if(field == "gender")
			{
				e.cellHtml = '<a class="mini-button" style="width:50px;border:0" onmousedown="editGift(\'' + record._uid + '\')">编辑</a> ';
			}
		})
		var uid = '';
		function addGift()
		{
			onAdd('grid');
		}

		function editGift(row_uid)
		{
			uid = row_uid;
			var row = grid.getRowByUID(row_uid);
			var groupId = row.groupId;
			var orderId = row.orderId;
			mini.get('groupId').setValue(groupId);
			mini.get('orderId').setValue(orderId);
			var grid1 = mini.get('grid1');	
			var grid2 = mini.get('grid2');
			grid1.setData({});
			grid2.setData({});
			
			grid.findRow(function(row){
				if(row.groupId == groupId)
				{
					var data = mini.clone(row);
					grid1.setData(data.ZH_detail);
					grid2.setData(data.ZP_detail);
				}
			});
			var editWindow = mini.get("editWindow");
			editWindow.show();
		}
		
		function onSelectPrdt(e)
		{
			selectProduct({displayStock:1, multiSelect:1},function(data){
				for(var i = 0; i < data.length; i++)
				{
					//console.log(data);
					var grid1 = mini.get('grid1');
					var row = grid1.findRow(function(row){
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
						
						grid1.addRow(arr,0);
						if(grid1.addRow() == null){
							onRemove(grid1)
						}
					}
				}
				grid1.cancelEdit();
			});
		}
		
		function onSelectPrdz(e)
		{
			selectProduct({displayStock:1, multiSelect:1},function(data){
				for(var i = 0; i < data.length; i++)
				{
					//console.log(data);
					var grid2 = mini.get('grid2');
					var row = grid2.findRow(function(row){
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
						
						grid2.addRow(arr,0);
						if(grid2.addRow() == null){
							onRemove(grid2)
						}
					}
				}
				grid2.cancelEdit();
			});
		}
		
		function onprobject(){
			var grid1 = mini.get("grid1");
			var grid2 = mini.get("grid2");
			
			grid.clearRows();
			var newRow = [];
			var rows = 5;
			if(rows > 0){
				for(var i=0; i<rows; i++){
					newRow[i]={};
				}
				grid.addRows(newRow);
			}
			grid1.clearRows();
			var newRow = [];
			var rows = 5;
			if(rows > 0){
				for(var i=0; i<rows; i++){
					newRow[i]={};
				}
				grid1.addRows(newRow);
			}
			
			grid2.clearRows();
			var newRow = [];
			var rows = 5;
			if(rows > 0){
				for(var i=0; i<rows; i++){
					newRow[i]={};
				}
				grid2.addRows(newRow);
			}
		}

		function onCancel1()
		{
			var accInfoWindow = mini.get("editWindow");
			accInfoWindow.hide();	
		}
		function saveObj()
		{
			var groupId = mini.get('groupId').getValue();
			var orderId = mini.get('orderId').getValue();
			var grid1 = mini.get("grid1");
			var grid2 = mini.get("grid2");
			if(groupId == '')
			{
				mini.alert("组合名称不能为空");
				return;	
			}
			var row = grid.findRow(function(row){
			   if(row.groupId == groupId && row._uid != uid)
			   {
				   return true;
			   }
			});
			if(row)
			{
				mini.alert("组合名称已经存在，不允许重复");
				return;	
			}
		   
			var row_ckeck1 = grid1.findRows(function(row){
				if(typeof(row.prd_no) != "undefined" && row.prd_no != "") return true;
			});
			
			if(row_ckeck1.length == 0)
			{
				mini.alert("表身必填项不能为空!");
				return false;
			}
			
			for ( var i = 0; i < row_ckeck1.length; i++ ){
				if(typeof(row_ckeck1[i].unit_qty)=="undefined" || row_ckeck1[i].unit_qty == ""){
					mini.alert("表身必填项不能为空!");
					return false;
				}
			}
			
			
			var row_ckeck2 = grid2.findRows(function(row){
			   if(typeof(row.prd_no) != "undefined" && row.prd_no != "") return true;  
			});
			if(row_ckeck2.length == 0)
			{
				mini.alert("表身必填项不能为空!");
				return false;
			}
			for ( var i = 0; i < row_ckeck2.length; i++ ){
				if(typeof(row_ckeck2[i].unit_qty) == "undefined" || row_ckeck2[i].unit_qty == ""){
					mini.alert("表身必填项不能为空!");
					return false;
				}
			}
			var row = grid.getRowByUID(uid);
			row.groupId = groupId;
			row.orderId = orderId;
			row.ZH_detail = row_ckeck1;
			console.log(row.ZH_detail);
			row.ZP_detail = row_ckeck2;
			grid.updateRow(row, row);
			var editWindow = mini.get("editWindow");
			editWindow.hide();
		}

		function CloseWindow(action) {            
			if (window.CloseOwnerWindow) return window.CloseOwnerWindow(action);
			else window.close();            
		}

		function onCellcommitedit(e) {
			var sender = e.sender;
			var record = e.record;
			var field = e.field;
			var value = e.value;
			sender.updateRow(record);
		}
		
		/* grid.on("cellcommitedit", function (e){
			var sender = e.sender;
			var record = e.record;
			var field = e.field;
			var value = e.value;
			
			sender.updateRow(record, {prd_no: value});
		}) */
		

		function onOk(e) {
            SaveData();
        }
        function onCancel(e) {
            CloseWindow("cancel");
        }
		
		
	</script>
	<?
		include_once("../html/js/myAppBoot.php");
	?>
	
	<script src="js/plug-in/bootstrap/js/bootstrap.js"></script>
	<script src="js/plug-in/layui-2.0/layui.js"></script>
</body>

</html>
