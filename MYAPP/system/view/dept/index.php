<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<?
		include_once("../html/js/boot.php");
	?>
	<link rel="stylesheet" href="js/plug-in/bootstrap/css/bootstrap.css" />
	<link rel="stylesheet" href="js/plug-in/layui-2.0/css/layui.css" />
	
	<title>部门管理</title>
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
					<a class="mini-button" iconCls="icon-remove" onclick="remove()">删除</a>       
				</td>
			</tr>
		</table>           
	</div>
</div>


<div class="mini-fit">
	
	<div id="treegrid1" class="mini-treegrid" style="width:100%;height:100%;"     
    url="index.php?m=system&c=dept&a=getList" showTreeIcon="true" 
    treeColumn="taskname" idField="dept_no" parentField="parent_dept_no" resultAsTree="false" expandOnLoad="false" multiSelect="false" pageSize="20" >
		<div property="columns">
			<div type="checkcolumn" ></div>
			<div name="taskname" field="dept_name" width="50%">部门名称</div>
			<div field="dept_no" width="50%">部门编号</div>
		</div>
	</div>


<?
	include_once("../html/js/myAppBoot.php");
?>
<style>
	.layui-table-page select{height:25px}
</style>
<script type="text/javascript">
	mini.parse();
	var tree = mini.get("treegrid1");

	function search() {
		var key = mini.get("key").getValue();
		if (key == "") {
			
		} else {
			key = key.toLowerCase();

			//查找到节点
			var nodes = tree.findNodes(function (node) {
				var text = node.Name ? node.Name.toLowerCase() : "";
				if (text.indexOf(key) != -1) {
					return true;
				}
			});

			//展开所有找到的节点
			for (var i = 0, l = nodes.length; i < l; i++) {
				var node = nodes[i];
				tree.expandPath(node);
			}
			
			//第一个节点选中并滚动到视图
			var firstNode = nodes[0];
			if (firstNode) {
				tree.selectNode(firstNode);
				tree.scrollIntoView(firstNode);
			}
		}
	}
	function onKeyEnter(e) {
		search();
	}
	
	function add() {
		mini.open({
			url: "index.php?m=system&c=dept&a=add",
			title: "新增", width: 400, height: 260,
			onload: function () {
				
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
				tree.reload();
			}
		});
	}
	function edit() {
		var rows = tree.getSelecteds();
		if (rows.length == 1) {
			var row = tree.getSelected();
			mini.open({
				url: "index.php?m=system&c=dept&a=edit&id="+row.id,
				title: "编辑", width: 400, height: 260,
				onload: function () {
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
					tree.reload();
				}
			});
			
		} else {
			alert("请选中一条记录");
		}
		
	}
	function remove() {
		var rows = tree.getSelecteds();
		if (rows.length > 0) {
			if (confirm("确定删除选中记录？")) {
				var ids = [];
				for (var i = 0, l = rows.length; i < l; i++) {
					var r = rows[i];
					ids.push(r.id);
				}
				var id = ids.join(',');
				tree.loading("操作中，请稍后......");
				$.ajax({
					url: "index.php?m=system&c=dept&a=del&id=" + id,
					dataType:"json",
					success: function (text) {
                        if(text.code=="error") {
                            mini.alert(text.msg);
                        }
						tree.reload();
					},
					error: function () {
					}
				});
			}
		} else {
			alert("请选中一条记录");
		}
	}
	
	function uncheckAll() {
            var tree = mini.get("treegrid1");
            var nodes = tree.getAllChildNodes(tree.getRootNode());
            tree.uncheckNodes(nodes);
    }
	
</script>
</div>

<script src="js/plug-in/bootstrap/js/bootstrap.js"></script>
<script src="js/plug-in/layui-2.0/layui.js"></script>

<style>
.layui-table-page select{height:25px}
</style>

</body>	
</html>
