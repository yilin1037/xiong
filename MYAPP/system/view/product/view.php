<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<?
		include_once("../html/js/boot.php");
	?>
	<script src="js/distpicker.js" type="text/javascript" ></script>
	<script src="js/distpicker.data.js" type="text/javascript"></script>
	<script src="js/pinyin.js" type="text/javascript"></script>
	
	<title>查看商品信息</title>
	<style>
		body{padding:15px}
		legend{font-size:12px}
		table{width:100%;}
		table tr td{padding:5px 5px;}
		table tr td select{height:24px;}
		em{font-style:normal;}
		em.tips{color:#888;}
		
		table tr td.hd{width:60px;text-align:right;}
		.mini-textbox{width:auto;}
		.mini-combobox{width:auto;}
	</style>
</head>


<body>    
     
    <form id="form1" method="post">
        <input name="id" class="mini-hidden" />
        <div style="padding-left:11px;padding-bottom:5px;">
            <table style="table-layout:fixed;">
                <tr>
					<td class="hd">商品编号</td>
                    <td style="width:100px;">    
                        <?=$show['prd_no']?>
                    </td>
                    <td class="hd">商品名称</td>
                    <td style="width:100px;">
                        <input name="prd_name" id="prd_name" onblur="onprdnameblur()" class="mini-textbox" required  value="<?=$show['prd_name']?>" />
                    </td>
					<td class="hd">助记码</td>
                    <td style="width:100px;">
                        <input name="mnem_code" id="mnem_code" class="mini-textbox"  value="<?=$show['mnem_code']?>" />
                    </td>
                </tr>
                <tr>

                    <td class="hd">商品类别</td>
                    <td style="width:100px;">
                        <input name="cat_no" width="181" class="mini-combobox" url="index.php?m=system&c=productCategory&a=getProductList" textField="category_name" valueField="category_no" emptyText="请选择..." value="<?=$show['cat_no']?>" />
                    </td>
                    <td class="hd">主单位名称</td>
                    <td>    
                        <input name="unit_name" class="mini-textbox" required value="<?=$show['unit_name']?>"/>
                    </td>
                    <td class="hd">主单位条码</td>
                    <td>
                        <input name="unit_barcode" class="mini-textbox" value="<?=$show['unit_barcode']?>" />
                    </td>

                </tr>
                <tr>

                    <td class="hd">主单位重量</td>
                    <td>
                        <input name="unit_weight" class="mini-textbox" value="<?=$show['unit_weight']?>" emptyText="KG" />
                    </td>
                    <td class="hd">规格</td>
                    <td style="width:100px;">
                        <input name="spec" class="mini-textbox" value="<?=$show['spec']?>" />
                    </td>
                    <td class="hd">件条码</td>
                    <td>
                        <input name="box_barcode" class="mini-textbox" value="<?=$show['box_barcode']?>" />
                    </td>
                </tr>

                <tr>
                    <td class="hd">重量</td>
                    <td style="width:100px;">
                        <input name="box_weight" class="mini-textbox" style="width:70px;" value="<?=$show['box_weight']?>"/> <em class="tips"> KG </em>
                    </td>
                    <td class="hd">件长</td>
                    <td style="width:100px;">
                        <input name="box_length" class="mini-textbox" style="width:70px;" value="<?=$show['box_length']?>" /> <em class="tips">m</em>
                    </td>
                    <td class="hd">件宽</td>
                    <td style="width:100px;">
                        <input name="box_width" class="mini-textbox" style="width:70px;" value="<?=$show['box_width']?>"/> <em class="tips">m</em>
                    </td>
                </tr>
                <tr>
                    <td class="hd">件高</td>
                    <td style="width:100px;">
                        <input name="box_height" class="mini-textbox" style="width:70px;" value="<?=$show['box_height']?>" /> <em class="tips">m</em>
                    </td>
                    <td class="hd">件体积</td>
                    <td style="width:100px;">
                        <input name="box_volume" class="mini-textbox" style="width:70px;" value="<?=$show['box_volume']?>" /> <em class="tips">m</em>
                    </td>
                    <td class="hd">件换算率</td>
                    <td style="width:100px;">
                        <input name="box_rate" class="mini-textbox" style="width:70px;"  value="<?=$show['box_rate']?>"/> <em class="tips">1件=n主单位</em>
                    </td>
                </tr>

				<tr>
                    <td class="hd">副单位名称</td>
                    <td style="width:100px;">    
                        <input name="unit_name1" class="mini-textbox" value="<?=$show['unit_name1']?>" />
                    </td>
                    <td class="hd">副单位条码</td>
                    <td style="width:100px;">
                        <input name="unit_barcode1" class="mini-textbox" value="<?=$show['unit_barcode1']?>" />
                    </td>
					<td class="hd">副单位重量</td>
                    <td style="width:100px;">    
                        <input name="unit_weight1" class="mini-textbox" value="<?=$show['unit_weight1']?>" />
                    </td>

                </tr>
				
				<tr>
                    <td class="hd">副换算率</td>
                    <td style="width:100px;">
                        <input name="unit_rate1" class="mini-textbox" style="width:70px" value="<?=$show['unit_rate1']?>" /> <em class="tips">1副=n主单位</em>
                    </td>
                    <td class="hd">库存上限</td>
                    <td style="width:100px;">
                        <input name="stock_up" class="mini-textbox" value="<?=$show['stock_up']?>" />
                    </td>
                    <td class="hd">库存下限</td>
                    <td style="width:100px;">
                        <input name="stock_low" class="mini-textbox" value="<?=$show['stock_low']?>" />
                    </td>

                </tr>
				
				<tr>
                    <td class="hd">频次</td>
                    <td style="width:100px;">
                        <input name="frequency" width="181" class="mini-combobox" url="index.php?m=system&c=product&a=frequency" textField="text" valueField="id" emptyText="请选择..." value="<?=$show['frequency']?>" />
                    </td>
                    <td class="hd">货品停用</td>
                    <td style="width:100px;">
                        <input name="disabled" width="181" class="mini-combobox" url="index.php?m=system&c=product&a=productDisabled" textField="text" valueField="id" emptyText="请选择..." value="<?=$show['disabled']?>" />
                    </td>
                    <td class="hd">保质期天数</td>
                    <td style="width:100px;">    
                        <input name="expiry_day" class="mini-textbox" value="<?=$show['expiry_day']?>" />
                    </td>

                </tr>
				
				<tr>

                    <td class="hd">预警天数</td>
                    <td style="width:100px;">
                        <input name="early_day" class="mini-textbox" value="<?=$show['early_day']?>" />
                    </td>
                    <td class="hd">整散管理</td>
                    <td style="width:100px;">
                        <input name="disperse_type" class="mini-checkbox" autocomplate="off" value="<?=$show['disperse_type']?>" trueValue="1" falseValue="0" />
                    </td>
                    <td class="hd">托盘堆叠量</td>
                    <td style="width:100px;">
                        <input name="tray_box" class="mini-textbox" value="<?=$show['tray_box']?>" />
                    </td>


                </tr>

                <tr>
                    <td class="hd">配送计费方式</td>
                    <td>
                        <input width="181" name="deliveFeeType" value="<?=$show['deliveFeeType']?>" class="mini-combobox" url="index.php?m=system&c=product&a=deliveFeeType" textField="text" valueField="id" emptyText="请选择..." />
                    </td>
					<td class="hd">是否有附件</td>
                    <td style="100px">
                        <input name="attachment" class="mini-checkbox" autocomplate="off" value="<?=$show['attachment']?>" trueValue="1" falseValue="0" />
                    </td>
                </tr>

                <tr>
                    <td class="hd">生产厂家</td>
                    <td colspan="5">
                        <input name="producer" class="mini-textbox" style="width:100%;" value="<?=$show['producer']?>" />
                    </td>
                </tr>
				
				<tr>
					<td class="hd">备注</td>
					<td colspan="5">
						<input name="remark" class="mini-textarea" style="width:100%;" value="<?=$show['remark']?>" />
					</td>
				</tr>  
			
            </table>
        </div>
		
		
        <div style="text-align:center;padding:10px;">               

            <a class="mini-button" iconCls="icon-cancel" onclick="onCancel">关闭</a>
        </div>
		
		<input type="mini-hidden" name="id" class="mini-hidden" value="<?=$show['id']?>" />
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
                url: "index.php?m=system&c=product&a=edit",
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
		
		function onprdnameblur(e){
			var prd_name = mini.get("prd_name").value;
			var mnem_code = makePy(prd_name)[0];
			mini.get("mnem_code").setValue(mnem_code);
		}

        ////////////////////
        //标准方法接口定义
        function SetData(data) {
            if (data.action == "edit") {
                //跨页面传递的数据对象，克隆后才可以安全使用
                data = mini.clone(data);

                $.ajax({
                    url: "&id=" + data.id,
                    cache: false,
                    success: function (text) {
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
