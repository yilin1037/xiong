<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<?
		include_once("../html/js/boot.php");
	?>
	<link rel="stylesheet" href="js/plug-in/bootstrap/css/bootstrap.css" />
	<link rel="stylesheet" href="js/plug-in/layui-2.0/css/layui.css" />
	
	<title>员工资料</title>
	<style type="text/css">
	html, body{
		margin:0;padding:0;border:0;width:100%;height:100%;overflow:hidden;
	}
	</style>
</head>
<body>

<div style="width:100%;margin:5px 0">
	<div class="mini-toolbar" style="border-bottom:0;padding:0px;border:0;">
		<table style="width:100%;">
			<tr>
				<td style="width:100%;">
					<a class="mini-button" iconCls="icon-add" onclick="add()">新增</a>
					<a class="mini-button" iconCls="icon-edit" onclick="edit()">编辑</a>
                    <a class="mini-button" iconCls="icon-edit" onclick="view()">查看</a>
                    <a class="mini-button" iconCls="icon-edit" onclick="permissionConfig()">权限设置</a>
					<a class="mini-button" iconCls="icon-edit" onclick="permissionMobileConfig()">移动端权限设置</a>
					<a class="mini-button" iconCls="icon-edit" onclick="baseConfig()">角色设置</a>
					
					<a class="mini-button" iconCls="icon-remove" onclick="remove()">删除</a>       
				</td>
				<td style="white-space:nowrap;">
					<input name="user_no" id="user_no" class="mini-textbox" emptyText="编号" style="width:150px;" onenter="onKeyEnter"/>
					<input name="user_name" id="user_name" class="mini-textbox" emptyText="姓名" style="width:150px;" onenter="onKeyEnter"/>
					<input name="mobile" id="mobile" class="mini-textbox" emptyText="电话" style="width:150px;" onenter="onKeyEnter"/>
					
					<a class="mini-button" iconCls="icon-search" onclick="search()">查询</a>
				</td>
			</tr>
		</table>           
	</div>
</div>


<div class="mini-fit">
<div id="datagrid1" class="mini-datagrid" style="width:100%;height:100%;" allowResize="true" url="index.php?m=system&c=user&a=getList" idField="id" multiSelect="true" sizeList="[20,30,50,100]" pageSize="20">
	<div property="columns">
		<!--<div type="indexcolumn"></div> -->
		<div type="checkcolumn" ></div>
		<div field="user_no" width="80" headerAlign="center" align="center" allowSort="">员工编号</div>
		<div field="user_name" width="120" headerAlign="center" align="center" allowSort="">员工名称</div>
		<div field="dept_name" width="120" headerAlign="center" align="center" allowSort="">所属部门</div>
		<div field="user_type" width="120" headerAlign="center" align="center" allowSort="">所属角色</div>
		<div field="job" width="120" headerAlign="center" align="center" allowSort="">职务名称</div>
		<!--<div field="" width="120" headerAlign="center" align="center" allowSort="">上级主管</div>-->
		<div field="user_from" width="100" headerAlign="center" align="center" allowSort="">员工类型</div>
		<div field="dealer_name" width="100" headerAlign="center" align="center" allowSort="">经销商</div>
		<!--<div field="rdc" width="100" headerAlign="center" align="center" allowSort="">配送中心</div>-->
		<div field="mobile" width="100" headerAlign="center" align="center" allowSort="">联系电话</div>
		<div field="sex_cn" width="80" headerAlign="center" align="center" allowSort="">性别</div>
		<div field="education" width="120" headerAlign="center" align="center" allowSort="">学历</div>
		<div field="status_cn" width="100" headerAlign="center" align="center" allowSort="">状态</div>  
	</div>
</div>
</div>


<script type="text/javascript">
	mini.parse();
	
	var grid = mini.get("datagrid1");
	grid.load();
	function add() {
		mini.open({
			url: "index.php?m=system&c=user&a=add",
			title: "新增员工", width: 900, height: 642,
			onload: function () {
				var iframe = this.getIFrameEl();
				var data = { action: "new"};
				iframe.contentWindow.SetData(data);
			},
			ondestroy: function (action) {
				if(action == 'save'){
					mini.showTips({
							content: "数据保存成功",
							state: 'success',
							x: 'center',
							y: 'center',
							timeout: 2000
						});
				}
				grid.reload();
			}
		});
	}
	function permissionConfig() {
		var rows = grid.getSelecteds();
		if (rows.length == 1) {
			var row = grid.getSelected();
			mini.open({
				url: "index.php?m=system&c=user&a=permissionConfig&id="+row.id,
				title: "权限设置", width: 900, height: 642,
				onload: function () {
					var iframe = this.getIFrameEl();
					var data = { action: "edit", id: row.id };
					iframe.contentWindow.SetData(data);
				},
				ondestroy: function (action) {
					if(action == 'save'){
						mini.showTips({
								content: "数据保存成功",
								state: 'success',
								x: 'center',
								y: 'center',
								timeout: 2000
							});
					}
					grid.reload();
					
				}
			});
			
		} else {
			alert("请选中一条记录");
		}
		
	}
	function permissionMobileConfig() {
		var rows = grid.getSelecteds();
		if (rows.length == 1) {
			var row = grid.getSelected();
			mini.open({
				url: "index.php?m=system&c=user&a=permissionMobileConfig&id="+row.id,
				title: "移动端权限设置", width: 900, height: 642,
				onload: function () {
					var iframe = this.getIFrameEl();
					var data = { action: "edit", id: row.id };
					iframe.contentWindow.SetData(data);
				},
				ondestroy: function (action) {
					if(action == 'save'){
						mini.showTips({
							content: "数据保存成功",
							state: 'success',
							x: 'center',
							y: 'center',
							timeout: 2000
						});
					}
					grid.reload();
				}
			});
			
		} else {
			alert("请选中一条记录");
		}
	}
    function edit() {
        var rows = grid.getSelecteds();
        if (rows.length == 1) {
            var row = grid.getSelected();
            mini.open({
                url: "index.php?m=system&c=user&a=edit&id="+row.id,
                title: "编辑员工", width: 900, height: 642,
                onload: function () {
                    var iframe = this.getIFrameEl();
                    var data = { action: "edit", id: row.id };
                    iframe.contentWindow.SetData(data);
                },
                ondestroy: function (action) {
                    if(action == 'save'){
                        mini.showTips({
                            content: "数据保存成功",
                            state: 'success',
                            x: 'center',
                            y: 'center',
                            timeout: 2000
                        });
                    }
                    grid.reload();

                }
            });

        } else {
            alert("请选中一条记录");
        }

    }

    function view() {
        var rows = grid.getSelecteds();
        if (rows.length == 1) {
            var row = grid.getSelected();
            mini.open({
                url: "index.php?m=system&c=user&a=view&id="+row.id,
                title: "查看员工", width: 900, height: 642,
                onload: function () {
                    var iframe = this.getIFrameEl();
                    var data = { action: "edit", id: row.id };
                    iframe.contentWindow.SetData(data);
                }
            });

        } else {
            alert("请选中一条记录");
        }

    }

	function baseConfig(){
		mini.open({
			url: "index.php?m=system&c=user&a=baseConfig",
			title: "角色设置", width: 700, height: 450,
			onload: function () {
				var iframe = this.getIFrameEl();
				var data = { action: "new"};
				iframe.contentWindow.SetData(data);
			},
			ondestroy: function (action) {
				grid.reload();
			}
		});
	}
	function remove() {
		var rows = grid.getSelecteds();
		if (rows.length > 0) {
			if (confirm("确定删除选中记录？")) {
				var ids = [];
				for (var i = 0, l = rows.length; i < l; i++) {
					var r = rows[i];
					ids.push(r.id);
				}
				var id = ids.join(',');
				grid.loading("操作中，请稍后......");
				$.ajax({
					url: "index.php?m=system&c=user&a=del&id=" + id,
					dataType:"json",
					success: function (text) {
                        if(text.code=="error") {
                            mini.alert(text.msg);
                        }
						grid.reload();
					},
					error: function () {
					}
				});
			}
		} else {
			alert("请选中一条记录");
		}
	}
	function search() {
		var user_no 	= mini.get("user_no").getValue();
		var user_name 	= mini.get("user_name").getValue();
		var mobile 		= mini.get("mobile").getValue();
		grid.load({user_no:user_no, user_name:user_name, mobile:mobile});
	}
	function onKeyEnter(e) {
		search();
	}
	/////////////////////////////////////////////////
	function onBirthdayRenderer(e) {
		var value = e.value;
		if (value) return mini.formatDate(value, 'yyyy-MM-dd');
		return "";
	}
	function onMarriedRenderer(e) {
		if (e.value == 1) return "是";
		else return "否";
	}
	var Genders = [{ id: 1, text: '男' }, { id: 2, text: '女'}];        
	function onGenderRenderer(e) {
		for (var i = 0, l = Genders.length; i < l; i++) {
			var g = Genders[i];
			if (g.id == e.value) return g.text;
		}
		return "";
	}

</script>



<?
	include_once("../html/js/myAppBoot.php");
?>
<script src="js/plug-in/bootstrap/js/bootstrap.js"></script>
<script src="js/plug-in/layui-2.0/layui.js"></script>

<style>
.layui-table-page select{height:25px}
</style>

</body>	
</html>
