<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<?
		include_once("../html/js/boot.php");
	?>
	
	<title></title>
	<style type="text/css">
	html, body{
		margin:0;padding:0;border:0;width:100%;height:100%;overflow:hidden;
	}

	.filter_on{background:#E4F1FB}
	.filter_type{display:none;}
	#filter_type_1{display:block;}
	.firstdiv {
		width: 33%;
		float: left;
		margin-top: 2px;
		margin-bottom: 2px;
	}
	</style>
</head>
<body>
<div class="mini-fit">
	<div style="z-index: 100;position: absolute;right: 15px;margin-top: 5px;">
		<a class="mini-button mini-button-success" id="newSaveOrder" style="width: 70px;" onClick="doCheck()">
        	保存
      	</a>
    </div>
	<div class="divcss" style="margin-top: 10px;"><div class="spancss">订单基本信息</div></div>	
    <table style="margin-left: 15px;width:100%;">
        <tr>
            <td style="height: 32px;width:100%;"  >
                <div style="height: 28px;width:100%">
					<div class="firstdiv"><span style="color:red;">*</span>订单编号：
                    	<input  name='tid' id='tid' enabled="enabled" class='mini-textbox span_line' value="<?=$data['tid']?>" style="width:210px;" />
                    </div>

                  	<div class="firstdiv"><span style="color:red;">*</span>配送方式：			
                    	<select name='express_type' id='express_type' class="mini-combobox span_line" style="width:210px;" >
                        	<option value="0">配送</option>
                        	<option value="1">自提</option>
                        </select>			
                  	</div>
                    <div class="firstdiv"><span style="color:red;">*</span>付款方式：			
                    	<select name='payment_type' id='payment_type' class="mini-combobox span_line" style="width:210px;" >
                        	<option value="0">现金</option>
                        	<option value="1">账期</option>
                        </select>			
                  	</div>
                    <div class="firstdiv"><span style="color:red;">*</span>是否加急：			
                    	<select name='is_urgent' id='is_urgent' class="mini-combobox span_line" style="width:210px;" >
                        	<option value="0">否</option>
                        	<option value="1">是</option>
                        </select>			
                  	</div>
                    <div class="firstdiv" style="width:500px">&nbsp;&nbsp;订单备注：			
                    	<input  name='remark' id='remark' class='mini-textbox span_line'  style="width:400px;" />
                  	</div>
                </div>
            </td>
        </tr>
    </table>
	<div class="divcss" style="margin-top: 10px;"><div class="spancss">收货信息</div></div>	
    <table style="margin-left: 15px;width:100%;">
        <tr>
            <td style="height: 32px;width:100%;"  >
                <div style="height: 28px;width:100%">
                  	<div class="firstdiv"><span style="color:red;">*</span>所属客户：			
                    	<input id="cust" class="mini-buttonedit span_line" style="width:210px;" showClose="true" onbuttonclick="onSelectCust" oncloseclick="onCloseClick" />		
                  	</div>
                    <div class="firstdiv" style="width:500px">&nbsp;&nbsp;收货地址：			
                    	<input  name='address' id='address' class='mini-textbox span_line'  style="width:400px;" />
                  	</div>
                </div>
            </td>
        </tr>
    </table>
    <div class="divcss"><div class="spancss">订单商品信息</div></div>
	<div class="mini-fit" style="background:white;height:300px;">
    	<div id="datagrid1" class="mini-datagrid" style="width:100%;height:100%;" multiSelect="true"  allowResize="true" allowCellEdit="true" allowAlternating="true" allowCellSelect="true" url="index.php?m=system&c=order&a=getList" idField="id" showPager="false">
        <div property="columns">
            <!--<div type="indexcolumn"></div> --> 
            <div field="option" width="60" headerAlign="center" allowSort="">操作</div>          
            <div field="prd_no" name="prd_no"  displayField="prd_no" width="120" headerAlign="center" allowSort="">商品编号
            	<input property="editor" class="mini-buttonedit" allowInput="false" onbuttonclick="onSelectPrdt"/>
            </div>    
            <div field="prd_name" width="120" headerAlign="center">商品名称</div>    
            <div field="unit_name" width="60" headerAlign="center">单位</div>  
            <div field="add_unit_num" width="60" headerAlign="center">数量
            	<input property="editor" vtype="float" allowInput="true" class="mini-textBox" />
            </div>  
            <div field="unit_name1" width="60" headerAlign="center">副单位</div>  
            <div field="add_unit_num1" width="60" headerAlign="center">副数量
            	<input property="editor" vtype="float" allowInput="true" class="mini-textBox" />
            </div>  
            <div field="add_box_num" width="60" headerAlign="center">箱数
            	<input property="editor" vtype="float" allowInput="true" class="mini-textBox" />
            </div>  
            <div type="checkboxcolumn" trueValue="1" falseValue="0" field="is_bat" width="70" headerAlign="center" allowSort="">指定批号</div>
            <div field="bat_no" width="90" headerAlign="center" allowSort="">批号</div>
            <div field="remark" width="80" headerAlign="center" allowSort="">备注
            	<input property="editor" vtype="float" allowInput="true" class="mini-textBox" />
            </div>
        </div>
    </div>
	</div>
</div>

</body>	
</html>
<script>
mini.parse();
var grid = mini.get("datagrid1");
grid.load({},function(e){
	var newRow = [];
	var rows = 9 - e['result']['total'];
	if(rows > 0){
		for(var i = 0; i < rows; i++){
			newRow[i] = {option:"<a onClick='onAddadd()' style='color: #0088FF; text-decoration:underline'>插入</a> <a onClick='onRemove()' style='color: #0088FF; text-decoration:underline'>删除</a>"};
		}
		grid.addRows(newRow);
	}else{
		grid.addRow({option:"<a onClick='onAddadd()' style='color: #0088FF; text-decoration:underline'>插入</a> <a onClick='onRemove()' style='color: #0088FF; text-decoration:underline'>删除</a>"});
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
		if(e.value > e.row['unit_num'])
		{
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
		if(e.row['box_rate'])
		{
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
function onSelectCust()
{
	
}

function onSelectPrdt(e)
{
	selectProduct(1,1,function(data){
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
				arr['option'] = "<a onClick='onAddadd()' style='color: #0088FF; text-decoration:underline'>插入</a> <a onClick='onRemove()' style='color: #0088FF; text-decoration:underline'>删除</a>";
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
		grid.addRow({option:"<a onClick='onAddadd()' style='color: #0088FF; text-decoration:underline'>插入</a> <a onClick='onRemove()' style='color: #0088FF; text-decoration:underline'>删除</a>"});
		total_calculation();
	}
}

function onAddadd(e){
	var grid = mini.get("datagrid2");
	var row = grid.getSelected();
	var index = grid.indexOf(row);
	grid.addRow({option:"<a onClick='onAddadd()' style='color: #0088FF; text-decoration:underline'>插入</a> <a onClick='onRemove()' style='color: #0088FF; text-decoration:underline'>删除</a>"},index);
}

</script>