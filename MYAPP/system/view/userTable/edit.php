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
	<title>编辑账号</title>
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
                    <td style="width:80px;">登录账号：</td>
                    <td style="width:150px;">
                        <input name="userid" id="userid" class="mini-textbox" required="true" emptyText="登录帐号" value="<? echo $show['userid'];?>" />
                    </td>
                    <td style="width:80px;">名称：</td>
                    <td style="width:150px;">    
                        <input name="username" class="mini-textbox" required="true" value="<?=$show['username']?>" />
					</td>
                </tr>
                <tr>
                    <td>密码：</td>
                    <td>
                        <input name="password" class="mini-password" vtype="minLength:6" minLengthErrorText="密码不能少于6个字符" />
					</td>
					<td>电话：</td>
                    <td>
                        <input name="mobile" class="mini-textbox" value="<? echo $show['mobile'];?>" />
                    </td>
                </tr>
                <tr>
                    <td>账号状态：</td>
                    <td>
                        <select id="status" name="status" class="mini-combobox" style="width:150px;">
							<option value="0" <?php if($show['status'] == 0){echo 'selected';} ?>>启用</option>
							<option value="1" <?php if($show['status'] == 1){echo 'selected';} ?>>禁用</option>
						</select>
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
            var json = mini.encode(o);
            $.ajax({
                url: "index.php?m=system&c=userTable&a=edit",
		        type: 'post',
                data: { data: json },
				dataType:'json',
                cache: false,
                success: function (text) {
					if(text.code == 'ok'){
						CloseWindow("save");
					}else{
						alert('保存失败！');
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
