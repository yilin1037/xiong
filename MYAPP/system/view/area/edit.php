<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<?
		include_once("../html/js/boot.php");
	?>
	<script src="js/pinyin.js" type="text/javascript"></script>
	
	<title>编辑区域名称</title>
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
                    <td style="width:80px;">区域名称：</td>
                    <td style="width:150px;">
						<input name="area_name" class="mini-textbox" value="<?=$area['area_name']?>" required="true" />
						<input name="id" class="mini-hidden" value="<?=$area['id']?>" />
                    </td>
                </tr>
				
            </table>
        </div>
		
		
        <div style="text-align:center;padding:10px;">               
            <a class="mini-button" onclick="onOk" style="width:60px;margin-right:20px;" id="save2">确定</a>
            <a class="mini-button" onclick="onCancel" style="width:60px;">取消</a>
        </div>
		
		
    </form>
	
	
    <script type="text/javascript">
        mini.parse();
        var form = new mini.Form("form1");
        function SaveData() {
            var o = form.getData();
			
			form.validate();
            if (form.isValid() == false) return;
			
            var json = mini.encode([o]);
			//alert(json);eixt;
            $.ajax({
                url: "index.php?m=system&c=area&a=edit",
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
    </script>
</body>

</html>
