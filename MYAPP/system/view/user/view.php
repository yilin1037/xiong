<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<?
		include_once("../html/js/boot.php");
	?>
	
	<title>查看员工资料</title>
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
                    <td style="width:80px;">员工姓名：</td>
                    <td style="width:150px;">    
                        <input name="user_name" id="user_name" class="mini-textbox" required="true" emptyText="" value="<?=$show['user_name']?>" />
                    </td>
                    <td style="width:80px;">所属部门：</td>
                    <td style="width:150px;">
                        <input name="dept_no" class="mini-combobox" valueField="dept_no" textField="dept_name" url="index.php?m=system&c=dept&a=getDeptTopList" emptyText="请选择..." value="<?=$show['dept_no']?>" />
                    </td>
                </tr>
				 <tr>
                    <td>是否操作员：</td>
                    <td>    
                        <input name="is_operator" class="mini-combobox" data="[{'id':'0','text':'否'},{'id':'1','text':'是'}]" valueField="id" textField="text" emptyText="请选择" value="<?=$show['is_operator']?>" />
                    </td>
                    <td>登录密码：</td>
                    <td>    
                        <input name="password" class="mini-password" value="<?=$show['password']?>" />
                    </td>
                </tr>
                <tr>
                    <td >电话：</td>
                    <td>    
                        <input name="mobile" class="mini-textbox" required="true" value="<?=$show['mobile']?>" />
                    </td>
                    <td>所属角色：</td>
                    <td>
						<input name="user_type" class="mini-combobox" valueField="configKey" textField="configValue" url="index.php?m=system&c=user&a=baseConfigList" emptyText="请选择..." value="<?=$show['user_type']?>" />
                    </td>
                </tr>
               
                <tr>
                    <td>性别：</td>
                    <td>    
                        <input name="sex" class="mini-combobox" data="[{'id':'0','text':'男'},{'id':'1','text':'女'}]" valueField="id" textField="text" emptyText="请选择" value="<?=$show['sex']?>" />
                    </td>
                    <td>职务名称：</td>
                    <td>    
                        <input name="job" class="mini-textbox" required="true" value="<?=$show['job']?>" />
                    </td>
                </tr>
				
				<tr>
					<td>生日日期：</td>
					<td>
						<input name="birthday" id="birthday" class="mini-datepicker" viewDate="1990-01-01" value="<?=$show['birthday']?>" />
					</td>
					 <td>学历：</td>
                    <td>    
                        <input name="education" class="mini-combobox" data="[{'text':'初中'},{'text':'高中'},{'text':'中专'},{'text':'大专'},{'text':'本科'},{'text':'研究生'},{'text':'硕士'}]" valueField="text" textField="text" emptyText="请选择" value="<?=$show['education']?>" />
                    </td>
                </tr>
				
				<tr>
					<td>身份证号：</td>
					<td>
						<input name="id_card" id="id_card" class="mini-textbox" value="<?=$show['id_card']?>" />
					</td>
					<td>入职日期：</td>
					<td>
						<input name="entry_day" id="entry_day" class="mini-datepicker" value="<?=$show['entry_day']?>" />
					</td>
				</tr>
				<tr>
					<td>状态：</td>
					<td>
						<input name="status" class="mini-combobox" data="[{'id':'0','text':'离职'},{'id':'1','text':'在职'}]" valueField="id" textField="text" emptyText="请选择" value="<?=$show['status']?>" />
					</td>
					<td>离职日期：</td>
					<td>
						<input name="quit_day" id="quit_day" class="mini-datepicker" value="<?=$show['quit_day']?>" />
					</td>
				</tr>
				<tr>
                    <td>员工类型：</td>
                    <td>    
                        <input name="user_from" class="mini-combobox" data="[{'id':'0','text':'内聘'},{'id':'1','text':'外聘'}]" value="<?=$show['user_from']?>" valueField="id" textField="text"   emptyText="请选择" />
                    </td>
					<td>经销商：</td>
					<td>
						<input class="mini-buttonedit" onbuttonclick="onSelectDealer" id="dealer_no" name="dealer_no"  value="<?php echo $show['dealer_no'];?>" text="<?=$show['dealer_name']?>"/>
					</td>
                </tr>
				
				<tr>
					<td >备注：</td>
					<td colspan="3">    
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
                url: "index.php?m=system&c=user&a=edit",
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
		function onCloseClick(e){
			var obj = e.sender;
			obj.setText("");
			obj.setValue("");
		}
		
		//选择经销商
		function onSelectDealer(e)
		{
			selectDealer(0,function(data){
				if (data) {
					mini.get('dealer_no').setValue(data[0].dealer_no);
					mini.get('dealer_no').setText(data[0].dealer_name);
				}
			});
		}
		
		
    </script>
</body>

</html>
