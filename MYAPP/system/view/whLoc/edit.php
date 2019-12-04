<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<?
		include_once("../html/js/boot.php");
	?>
	<script src="js/pinyin.js" type="text/javascript"></script>
	
	<title>编辑货位信息</title>
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
                    <td style="width:80px;">所属仓库：</td>
                    <td style="width:150px;">
                        <input name="wh_no" id="wh_no" class="mini-textbox" emptyText="所属仓库" readonly="readonly" value="<? echo $show['wh_name'];?>" />
                    </td>
                    <td style="width:80px;">所属保管员：</td>
                    <td style="width:150px;">
						<input class="mini-buttonedit" onbuttonclick="onButtonEdit" name="curator" textName="user_name" id="curator" value="<? echo $show['curator'];?>" allowInput="false"/>
                    </td>
                </tr>
                <tr>
                   <td >货位编号：</td>
                    <td >    
                        <input name="loc_no" class="mini-textbox" required="true" value="<? echo $show['loc_no'];?>" readonly="readonly"/>
                    </td>
                    <td >货位名称：</td>
                    <td >    
                        <input name="loc_name" class="mini-textbox" textField="name" value="<? echo $show['loc_name'];?>" />
                    </td>
				</tr>
               
                <tr>
					<td >货位条码：</td>
                    <td >    
                        <input name="loc_barcode" class="mini-textbox" textField="name" value="<? echo $show['loc_barcode'];?>"/>
                    </td>
                    <td >绑定经销商：</td>
                    <td >
						<input class="mini-buttonedit" onbuttonclick="onDealerEdit" name="dealer_no" id="dealer_no" value="<? echo $show['dealer_no'];?>" allowInput="false"/>
                    </td>
				</tr>
				
				<tr>
					<td >货位属性：</td>
					<td >
						<select id="loc_attr" name="loc_attr" class="mini-combobox" style="width:150px;">
							<option value="0" <?php if($show['loc_attr'] == 0){echo 'selected';} ?>>常规货位</option>
							<option value="1" <?php if($show['loc_attr'] == 1){echo 'selected';} ?>>非常规货位</option>
						</select>
						
					</td>
					<td >级别：</td>
                    <td >    
                        <input name="level" class="mini-textbox" valueField="id" textField="name" value="<? echo $show['level'];?>" />
                    </td>
                </tr> 
				
				<tr>
					<td >最大体积：</td>
					<td >    
						<input name="max_volume" class="mini-textbox" textField="name" value="<? echo $show['max_volume'];?>"/>
					</td>
					 <td >最大重量：</td>
                    <td >    
                        <input name="max_weight" class="mini-textbox" textField="name" value="<? echo $show['max_weight'];?>"/>
                    </td>
                </tr> 
				<tr>
					<td style="width:80px;">仓库类型：</td>
					<td colspan="3">
						<select name="loc_type" class="mini-radiobuttonlist">
							<option value="0" <?php if($show['loc_type'] == 0){echo 'selected';} ?>>存货区</option>
							<option value="1" <?php if($show['loc_type'] == 1){echo 'selected';} ?>>不区分检货区</option>
							<option value="2" <?php if($show['loc_type'] == 2){echo 'selected';} ?>>整货位检货区</option>
							<option value="3" <?php if($show['loc_type'] == 3){echo 'selected';} ?>>散货位检货区</option>
							<option value="4" <?php if($show['loc_type'] == 4){echo 'selected';} ?>>集货区</option>
						</select>
					</td>
				</tr>
				
				<tr>
					<td >频次：</td>
                    <td>    
                        <select id="frequency" name="frequency" class="mini-combobox" style="width:150px;">
							<option value="0" <?php if($show['frequency'] == 0){echo 'selected';} ?>>无</option>
							<option value="1" <?php if($show['frequency'] == 1){echo 'selected';} ?>>低频</option>
							<option value="2" <?php if($show['frequency'] == 2){echo 'selected';} ?>>中频</option>
							<option value="3" <?php if($show['frequency'] == 3){echo 'selected';} ?>>高频</option>
						</select>
					</td>
					<td ></td>
					<td >
						<input type="checkbox" name="status" id="status" <?php if($show['status'] == 0){echo checked;} ?>>锁定
						<input type="checkbox" name="disabled" id="disabled" <?php if($show['disabled'] == 1){echo checked;} ?>>禁用
					</td>
				</tr>  
				
            </table>
        </div>
		
		
        <div style="text-align:center;padding:10px;">               
            <a class="mini-button" onclick="onOk" style="width:60px;margin-right:20px;" id='save2'>确定</a>       
            <a class="mini-button" onclick="onCancel" style="width:60px;">取消</a>
        </div>
		
		<input type="mini-hidden" name="id" class="mini-hidden" value="<?=$show['id']?>" />
    </form>
	
	<script>
		mini.parse();
        var form = new mini.Form("form1");
        function SaveData() {
            var o = form.getData();
			
			form.validate();
            if (form.isValid() == false) return;
			//alert(1);
			if(document.getElementById("status").checked){
				o.status = 0;
			}else{
				o.status = 1;
			}
			
			if(document.getElementById("disabled").checked){
				o.disabled = 1;
			}else{
				o.disabled = 0;
			}
            var json = mini.encode([o]);
            $.ajax({
                url: "index.php?m=system&c=whLoc&a=edit",
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

        ////////////////////
        //标准方法接口定义
        function SetData(data) {
            if (data.action == "edit") {
                //跨页面传递的数据对象，克隆后才可以安全使用
                data = mini.clone(data);

                $.ajax({
                    url: "../data/AjaxService.php?method=GetEmployee&id=" + data.id,
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

		
		
		//选择保管员
		function onButtonEdit(e) {
			var btnEdit = this;
			
            mini.open({
                url: "index.php?m=system&c=whLoc&a=user",
                title: "选择保管员",
                width: 650,
                height: 380,
                ondestroy: function (action) {
                    //if (action == "close") return false;
                    if(action == "ok") {
                        var iframe = this.getIFrameEl();
                        var data = iframe.contentWindow.GetData();
                        data = mini.clone(data);    //必须
                        if (data) {
                            btnEdit.setValue(data.user_no);
                            btnEdit.setText(data.user_name);
                        }
                    }

                }
            });
		}
		
		mini.get("curator").setValue('<?=$show['curator']?>');
		mini.get("curator").setText('<?=$show['user_name']?>');
		
		//选择经销商
		function onDealerEdit(e) {
            var btnEdit = this;
			
            mini.open({
                url: "index.php?m=system&c=whLoc&a=dealer",
                title: "选择经销商",
                width: 800,
                height: 500,
                ondestroy: function (action) {
					//if (action == "close") return false;
                    if(action == "ok") {
                        var iframe = this.getIFrameEl();
                        var data = iframe.contentWindow.GetData();
						data = mini.clone(data);    //必须
						
						if (data) {
                            btnEdit.setValue(data.dealer_no);
                            btnEdit.setText(data.dealer_name);
                        }
                    }
				}
            });
		}
		
		
		
		mini.get("dealer_no").setValue('<?=$show['dealer_no']?>');
		mini.get("dealer_no").setText('<?=$show['dealer_name']?>');
	</script>
</body>

</html>
