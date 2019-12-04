<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<?
		include_once("../html/js/boot.php");
	?>
	<script src="js/pinyin.js" type="text/javascript"></script>
	
	<title>新增货位信息</title>
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
                        <input class="mini-textbox" emptyText="所属仓库" readonly="readonly" value="<?php echo $res['wh_name']; ?>" />
						<input name="wh_no" id="wh_no" class="mini-hidden" textField="wh_name" valueField="wh_no" value="<?php echo $res['wh_no']; ?>" />
                    </td>
                    <td style="width:80px;">所属保管员：</td>
                    <td style="width:150px;">
						<input class="mini-buttonedit" onbuttonclick="onButtonEdit" name="curator" allowInput="false"/>
                    </td>
                </tr>
                <tr>
                    <td >货位编号：</td>
                    <td >
                        <input name="loc_no" class="mini-textbox" required="true"/>
                    </td>
                    <td >货位名称：</td>
                    <td >
                        <input name="loc_name" class="mini-textbox" textField="name" required="true"/>
                    </td>
					
                </tr>
               
                <tr>
					<td >货位条码：</td>
                    <td >    
                        <input name="loc_barcode" class="mini-textbox" textField="name" />
                    </td>
                    <td >绑定经销商：</td>
                    <td >
                        <input class="mini-buttonedit" onbuttonclick="onDealerEdit" name="dealer_no" allowInput="false"/>
                    </td>
					
                </tr>
				
				<tr>
					<td >货位属性：</td>
					<td >
						<select id="loc_attr" name="loc_attr" class="mini-combobox" style="width:150px;">
							<option value="0">常规货位</option>
							<option value="1">非常规货位</option>
						</select>
					</td>
					<td >级别：</td>
                    <td >    
                        <input name="level" class="mini-textbox" valueField="id" textField="name" url="" />
                    </td>
                </tr> 
				
				<tr>
					<td >最大体积：</td>
					<td >    
						<input name="max_volume" class="mini-textbox" textField="name"/>
					</td>
					 <td >最大重量：</td>
                    <td >    
                        <input name="max_weight" class="mini-textbox" textField="name"/>
                    </td>
                </tr> 
				<tr>
					<td style="width:80px;">仓库类型：</td>
					<td colspan="3">
						<select name="loc_type" class="mini-radiobuttonlist">
							<option value="0">存货区</option>
							<option value="1">不区分检货区</option>
							<option value="2">整货位检货区</option>
							<option value="3">散货位检货区</option>
							<option value="4">集货区</option>
						</select>
					</td>
				</tr>
				
				<tr>
					<td >频次：</td>
                    <td>    
                        <select id="frequency" name="frequency" class="mini-combobox" style="width:150px;">
							<option value="0">无</option>
							<option value="1">低频</option>
							<option value="2">中频</option>
							<option value="3">高频</option>
						</select>
					</td>
					<td ></td>
					<td >    
						<input name="status" class="mini-checkbox" text="锁定" trueValue="0" falseValue="1" />
						<input name="disabled" class="mini-checkbox" text="禁用" trueValue="1" falseValue="0" />
					</td>
				</tr>  
				
            </table>
        </div>
		
		
        <div style="text-align:center;padding:10px;">               
            <a class="mini-button" onclick="onOk" style="width:60px;margin-right:20px;" id='save2'>确定</a>       
            <a class="mini-button" onclick="onCancel" style="width:60px;">取消</a>
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
			//alert(json);eixt;
            $.ajax({
                url: "index.php?m=system&c=whLoc&a=add",
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
		
		
	</script>
	
</body>

</html>
