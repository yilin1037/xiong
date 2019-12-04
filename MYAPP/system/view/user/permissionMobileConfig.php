<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <?
    include_once("../html/js/boot.php");
    ?>
    <title>编辑员工资料</title>
</head>


<body>

<form id="form1" method="post">
    <input type="hidden" name="id" value="<?= $show['id'] ?>"/>
    <div >
        <table class="table table-bordered">
            <thead>
            <tr>
                <th scope="col">一级菜单</th>
                <th scope="col">二级菜单</th>
                <th scope="col">权限设置</th>
            </tr>
            </thead>
            <?php
            foreach ($menu as $k => $v) {
                $i=0;
                foreach ($v["child"] as $k2 => $v2) {
                    if($i==0) {
                        ?>
                        <tr>
                            <td rowspan="<?php echo count($v["child"]);?>"><?php echo $v['name']; ?></td>
                            <td><?php echo $v2['name']; ?></td>
                            <td>
                                <?php
                                foreach ($v2["permission"] as $k3 => $v3) {
                                    ?>
                                    <input type="checkbox" name="mobile_permission[]" value="<?php echo $k3; ?>"  <?php if(in_array($k3,$permission)){echo "checked";} ?>/> <?php echo $v3; ?>
                                    <?php
                                }
                                ?>
                            </td>
                        </tr>
                        <?php

                    } else {
                        ?>
                        <tr>
                            <td><?php echo $v2['name']; ?></td>
                            <td>
                                <?php
                                foreach ($v2["permission"] as $k3 => $v3) {
                                    ?>
                                    <input type="checkbox" name="mobile_permission[]" value="<?php echo $k3; ?>"  <?php if(in_array($k3,$permission)){echo "checked";} ?>/> <?php echo $v3; ?>
                                    <?php
                                }
                                ?>
                            </td>
                        </tr>
                        <?php

                    }
                    ?>

                    <?php
                    $i=$i+1;
                }
            }
            ?>

        </table>
    </div>


    <div style="text-align:center;padding:10px;">
        <a class="mini-button" iconCls="icon-ok" onclick="onOk" style="margin-right:20px;">确定</a>
        <a class="mini-button" iconCls="icon-cancel" onclick="onCancel">取消</a>
    </div>

</form>


<script type="text/javascript">
    mini.parse();
    function CloseWindow(action) {
        if (window.CloseOwnerWindow) return window.CloseOwnerWindow(action);
        else window.close();
    }
    function onOk(e) {
		var id = $("input[name='id']").val();
		var mobile_permission = [];
		$("input[name='mobile_permission[]']").each(function(){
			if ($(this).is(':checked')) {
				mobile_permission.push($(this).val());
			}
		});
		if(mobile_permission.length < 1){
			alert('请设置权限');
			return;
		}
		$.ajax({
            url: "<?=U('system/user/editPermission')?>",
            type: 'post',
            data: {
				id: id,
				mobile_permission: mobile_permission
			},
            dataType:'json',
            cache: false,
            success: function (data) {
                if(data['code'] == 'ok'){
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
    function onCancel(e) {
        CloseWindow("cancel");
    }
</script>

</html>