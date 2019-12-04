<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<?
		include_once("../html/js/boot.php");
	?>
	<script src="js/distpicker.js" type="text/javascript" ></script>
	<script src="js/distpicker.data.js" type="text/javascript" ></script>
	<script src="js/pinyin.js" type="text/javascript"></script>
	<title>查看经销商</title>
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
        <div style="padding-left:11px;padding-bottom:5px;">
            <table style="table-layout:fixed;">
                <tr>
                    <td style="width:80px;">名称：</td>
                    <td style="width:150px;">
                        <input name="dealer_name" id="dealer_name" onblur="onprdnameblur()" class="mini-textbox" emptyText="经销商名称" value="<? echo $show['dealer_name'];?>"/>
                    </td>
					<td style="width:80px;">助记码：</td>
                    <td style="width:150px;">
                        <input name="mnem_code" id="mnem_code" class="mini-textbox" value="<? echo $show['mnem_code'];?>"/>
                    </td>
                    
                </tr>
                <tr>
					<td>联系人：</td>
                    <td>    
                        <input name="link_man" class="mini-textbox" required="true" value="<?=$show['link_man']?>" />
                    </td>
                    <td >联系人电话：</td>
                    <td >    
                        <input name="link_tel" class="mini-textbox" value="<? echo $show['link_tel'];?>" />
                    </td>
				</tr>
                <tr>
					<td >联系人手机：</td>
                    <td >    
                        <input name="link_mobile" class="mini-textbox" value="<? echo $show['link_mobile'];?>" />
                    </td>
                    <td >银行名称：</td>
                    <td >    
                        <input name="bank_name" class="mini-textbox" value="<? echo $show['bank_name'];?>" />
                    </td>
                </tr>
				<tr>
					<td >银行卡号：</td>
					<td >    
                        <input name="bank_no" class="mini-textbox" valueField="id" textField="name" value="<? echo $show['bank_no'];?>" />
                    </td>
				</tr>
				<tr>
					<td >省市区：</td>
					<td colspan="3">
						<div data-toggle="distpicker">
							<select name="province" id="province" data-province="<?=$show['province'] ?>" required="true"></select>
							<select name="city" id="city" data-city="<?=$show['city'] ?>" required="true"></select>
							<select name="district" id="district" data-district="<?=$show['district'] ?>" required="true"></select>
						</div>
					</td>
				</tr>
				<td >详细地址：</td>
					<td colspan="3">
						<input name="address" class="mini-textbox" value="<? echo $show['address'];?>" />
					</td>
				<tr>
					<td >备注：</td>
					<td colspan="3">    
						<input name="remark" class="mini-textarea" style="width:100%;" value="<? echo $show['remark'];?>" />
					</td>
				</tr>  
			
            </table>
        </div>
		
		
        <div style="text-align:center;padding:10px;">               
            <a class="mini-button" onclick="onCancel" style="width:60px;margin-right:20px;">关闭</a>
        </div>
		
		<input type="mini-hidden" name="id" class="mini-hidden" value="<?=$show['id']?>" />
    </form>
	
	<script>
		mini.parse();
        var form = new mini.Form("form1");
        function SaveData() {
            var o = form.getData();
            CloseWindow("save");
        }
		
		function onprdnameblur(e){
			var dealer_name = mini.get("dealer_name").value;
			var mnem_code = makePy(dealer_name)[0];
			mini.get("mnem_code").setValue(mnem_code);
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
