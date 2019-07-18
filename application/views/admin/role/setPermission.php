<?php 
/**
 * @name 生蚝科技RBAC开发框架-V-设置角色权限
 * @author Jerry Cheung <master@xshgzs.com>
 * @since 2018-02-17
 * @version 2019-06-12
 */
?>
<!DOCTYPE html>
<html>

<head>
	<?php $this->load->view('include/header'); ?>
	<title>设置角色权限 / <?=$this->setting->get('systemName');?></title>
	<style>
		.ztree li span.button.add {margin-left:2px; margin-right: -1px; background-position:-144px 0; vertical-align:top; *vertical-align:middle}
		ul.ztree {margin-top: 10px;border: 1px solid #617775;background: #f0f6e4;height:360px;overflow-y:scroll;overflow-x:auto;}
	</style>
</head>

<body class="hold-transition skin-cyan sidebar-mini">
<div class="wrapper">

<?php $this->load->view('include/navbar'); ?>

<!-- 页面内容 -->
<div id="app" class="content-wrapper">
	<?php $this->load->view('include/pagePath',['name'=>'设置角色权限<font style="font-size: 22px;">（角色名：<font color="blue"><b>'.urldecode($roleName).'</b></font>）</font>','path'=>[['角色列表',base_url('admin/role/list')],['设置角色权限','',1]]]); ?>

	<!-- 页面主要内容 -->
	<section class="content">

		<input type="hidden" id="roleId" value="<?=$roleId;?>">
		<input type="hidden" id="menuIds">

		<ul id="treeDemo" class="ztree"></ul>

		<hr>

		<button onclick='getCheckedNodes();toSetPermission();' class="btn btn-success btn-block">确 认 分 配 权 限</button>
	</section>
	<!-- ./页面主要内容 -->
</div>
<!-- ./页面内容 -->

<?php $this->load->view('include/footer'); ?>

<!-- ./Page Main Content -->
</div>
<!-- ./Page -->
</div>

<script>
var setting = {
	view: {
		selectedMulti: false
	},
	check: {
		enable: true
	},
	data: {
		simpleData: {
			enable: true
		}
	}
};

var zNodes=getAllMenu();

function getCheckedNodes(){
	ids="";
	var zTree = $.fn.zTree.getZTreeObj("treeDemo"),
	nodes = zTree.getCheckedNodes();
	for (i=0,l=nodes.length;i<l;i++){
		ids+=nodes[i].id+",";
	}
	ids=ids.substr(0,ids.length-1);
	console.log(ids);
	$("#menuIds").val(ids);
}

$(document).ready(function(){
	$.fn.zTree.init($("#treeDemo"),setting,zNodes);
});

function getAllMenu(){
	roleId=$("#roleId").val();
	
	$.ajax({
		url:headerVm.apiPath+"role/getRoleMenuForZtree",
		data:{'roleId':roleId},
		dataType:"json",
		async:false,
		error:function(e){
			console.log(e);
			unlockScreen();
			showModalTips("服务器错误！<hr>请联系技术支持并提交以下错误码：<br><font color='blue'>"+e.status+"</font>");

			$("#tipsModal").modal('show');
			return false;
		},
		success:function(got){
			ret=got;
		}
	});
	return ret;
}

function toSetPermission(){
	lockScreen();
	roleId=$("#roleId").val();
	menuIds=$("#menuIds").val();

	$.ajax({
		url:"<?=base_url('admin/role/toSetPermission'); ?>",
		type:"post",
		dataType:"json",
		data:{'roleId':roleId,'menuIds':menuIds},
		error:function(e){
			console.log(e);
			unlockScreen();
			showModalTips("服务器错误！<hr>请联系技术支持并提交以下错误码：<br><font color='blue'>"+e.status+"</font>");
			return false;
		},
		success:function(ret){
			unlockScreen();
			
			if(ret.code==200){
				alert("权限分配成功！");
				history.go(-1);
				return true;
			}else if(ret.code==500){
				showModalTips("权限分配数量不匹配！！<br>请联系管理员！");
				return false;
			}else if(ret.code==1){
				showModalTips("权限清空失败！！<br>请联系管理员！");
				return false;
			}else if(ret.code==0){
				showModalTips("参数缺失！请联系技术支持！");
				return false;
			}else{
				showModalTips("系统错误！<hr>请联系技术支持并提交以下错误码：<br><font color='blue'>"+ret.code+"</font>");
				return false;
			}
		}
	});
}
</script>

<div class="modal fade" id="ztreeModal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
        <h3 class="modal-title" id="ModalTitle">温馨提示</h3>
      </div>
      <div class="modal-body">
        <div style="overflow:hidden;">
        </div>
        <form method="post" name="OprNode">
        <input type="hidden" id="OprType" name="OprType">
        <p id="msg"></p>       
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">&lt; 取消</button>
        <button type="button" class="btn btn-success" id='okbtn' onclick="submitOpr()">确定 &gt;</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

</body>
</html>
