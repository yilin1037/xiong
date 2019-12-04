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
	
	<title>新增假日</title>
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
					<td style="width:80px;">节日名称：</td>
                    <td style="width:150px;">
                        <input name="name" id="name" class="mini-textbox" required="true" />
                    </td>
					<td style="width:80px;">开始日期：</td>
					<td style="width:150px;">
						<input id="begin_date" name="begin_date" class="mini-datepicker" value="new Date()" required="true" />
					</td>
                </tr>
				<tr>
					<td style="width:80px;">结束日期：</td>
					<td style="width:150px;">
						<input id="end_date" name="end_date" class="mini-datepicker" value="new Date()" required="true" />
					</td>
					<td style="width:80px;">收费倍数：</td>
                    <td style="width:150px;">
                        <input id="multiple" name="multiple" class="mini-textbox" required="true"/>
                    </td>
				</tr>
            </table>
        </div>
		
		
        <div style="text-align:center;padding:10px;">               
            <a class="mini-button" onclick="onOk" style="width:60px;margin-right:20px;" id="save">确定</a>       
            <a class="mini-button" onclick="onCancel" style="width:60px;">取消</a>
        </div>
		
		
    </form>
	
	
	<script>
		mini.parse();
        var form = new mini.Form("form1");
        function SaveData() {
            var o = form.getData();
			
			form.validate();
			//console.log(o);return;
            if (form.isValid() == false) return;
			
            var json = mini.encode(o);
			//alert(json);
            $.ajax({
                url: "index.php?m=system&c=holiday&a=add",
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
			var dealer_name = mini.get("dealer_name").value;
			var mnem_code = makePy(dealer_name)[0];
			mini.get("mnem_code").setValue(mnem_code);
			//$("#mnem_code").val(mnem_code);
		}
		
		function checkon(e){
			var dealer_no = mini.get("dealer_no").value;
			$.ajax({
                url: "index.php?m=system&c=dealer&a=checkno",
		        type: 'post',
                data: { dealer_no: dealer_no },
				dataType:'json',
                cache: false,
                success: function (data) {
					if(data.code == 'error'){
						alert(data.msg);
					}else if(data.code == 'ok'){
					}
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    alert(jqXHR.responseText);
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
        var btn = mini.get("save");
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
	</script>
</body>

</html>
