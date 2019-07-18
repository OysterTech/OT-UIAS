<?php 
/**
 * @name 生蚝科技RBAC开发框架-V-菜单管理
 * @author Jerry Cheung <master@xshgzs.com>
 * @since 2018-02-17
 * @version 2019-07-17
 */
?>
<!DOCTYPE html>
<html>

<head>
	<?php $this->load->view('include/header'); ?>
	<title>菜单管理 / <?=$this->setting->get('systemName');?></title>
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
	<?php $this->load->view('include/pagePath',['name'=>'菜单管理','path'=>[['菜单管理','',1]]]); ?>

	<!-- 页面主要内容 -->
	<section class="content">
		<a @click="operateReady(0,0,'home','系统主菜单')" class="btn btn-primary btn-block">新 增 主 菜 单</a>

		<ul id="treeDemo" class="ztree"></ul>
	</section>
	<!-- ./页面主要内容 -->

	<div class="modal fade" id="operateModal">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
					<h3 class="modal-title" id="operateTitle"></h3>
				</div>
				<div class="modal-body">
					<div id="fatherMenuDiv" class="alert" style="font-size:16px;background-color:#caffdf;color:#e029ff;border-color:white;">父菜单：<i id='menuIcon' aria-hidden="true" style="font-weight:bold;"></i></div>
					<div class="panel-body">
						<div class="form-group">
							<label for="name">菜单类型</label>
							<select class="form-control" v-model="menuType" onkeyup='if(event.keyCode==13)$("#name").focus()'>
								<option value="1">菜单</option>
								<option value="2">按钮</option>
								<option value="3">接口</option>
							</select>
							<p class="help-block">Tips</p>
						</div>
						<br>
						<div class="form-group">
							<label for="name">菜单名称</label>
							<input class="form-control" v-model="name" onkeyup='if(event.keyCode==13)$("#icon").focus()'>
							<p class="help-block">请输入<font color="green">1</font>-<font color="green">20</font>字的菜单名称</p>
						</div>
						<br>
						<div class="form-group">
							<label for="icon">菜单图标 ( 预览: <i id="iconPreviewEle" aria-hidden="true"></i> ) &nbsp;&nbsp;&nbsp;&nbsp;<button class="btn btn-primary" onclick="$('#icon').val('circle-o');vm.icon='circle-o';vm.iconPreview();">使用默认图标</button></label>
							<input class="form-control" id="icon" v-model="icon" onkeyup='if(event.keyCode==13)$("#uri").focus()' @input='iconPreview'>
							<p class="help-block">请输入Font-Awesome图标名称，无需输入前缀“fa-”，输入后可在上方预览</p>
						</div>
						<br>
						<div class="form-group">
							<label for="uri">链接URL</label>
							<input class="form-control" v-model="uri" @keyup.enter='add'>
							<p class="help-block">
								若此菜单为父菜单，请留空此项<br>
								如此菜单需跳出站外，请<a @click="inputJumpOutURI">点此输入</a>
							</p>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button class="btn btn-warning" onclick="vm.icon='';vm.uri='';vm.name='';vm.operateType=-1;vm.operateMenuId=0;$('#operateModal').modal('hide');">&lt; 返回</button> <button class="btn btn-success" @click='operateSure' id="operateBtn"></button>
				</div>
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->

</div>
<!-- ./页面内容 -->

<?php $this->load->view('include/footer'); ?>

<!-- ./Page Main Content -->
</div>
<!-- ./Page -->
</div>

<script>
var vm = new Vue({
	el:'#app',
	data:{
		deleteId:0,
		menuType:1,
		operateType:0,
		operateMenuId:0,
		name:'',
		icon:'',
		uri:'',
		zNodes:{},
		setting:{
			view: {
				selectedMulti: false
			},
			data: {
				simpleData: {
					enable: true
				}
			},
			view: {
				addHoverDom: addHoverDom,
				removeHoverDom: removeHoverDom,
			}
		}
	},
	methods:{
		getAllMenu:()=>{
			lockScreen();
			$.fn.zTree.destroy();
			
			$.ajax({
				url:headerVm.apiPath+"role/getRoleMenuForZtree",
				dataType:"json",
				async:false,
				error:function(e){
					console.log(e);
					unlockScreen();
					showModalTips("服务器错误！<hr>请联系技术支持并提交以下错误码：<br><font color='blue'>"+e.status+"</font>");
					return false;
				},
				success:function(ret){
					vm.zNodes=ret;
					$.fn.zTree.init($("#treeDemo"),vm.setting,vm.zNodes);
					setTimeout(function(){unlockScreen()},300);
				}
			});
		},
		iconPreview:()=>{
			$("#iconPreviewEle").attr("class","fa fa-"+vm.icon);
		},
		inputJumpOutURI:()=>{
			uri=prompt("请输入需要跳转到的网站的完整URL（包括HTTP/HTTPS头）","http://");

			if(uri=="http://" || uri=="https://" || uri==""){
				alert("请输入需要跳转到的网站的完整URL（包括HTTP/HTTPS头）！");
				return false;
			}else if(uri==null){
				return;
			}else{
				vm.uri="show/jumpout/"+uri;
				$("#uri").val("show/jumpout/"+uri);
			}
		},
		operateReady:(type=0,menuId=0,icon='',name='',uri='')=>{
			if(type==0){
				$("#fatherMenuDiv").show();
				$('#operateTitle').html('新 增 主 菜 单');
				$('#operateBtn').html('确 认 新 增 菜 单 &gt;');
			}else if(type==1){
				$("#fatherMenuDiv").show();
				$('#operateTitle').html('新 增 菜 单');
				$('#operateBtn').html('确 认 新 增 菜 单 &gt;');
			}else if(type==2){
				$("#fatherMenuDiv").hide();
				$('#operateTitle').html('编 辑 菜 单');
				$('#operateBtn').html('确 认 编 辑 菜 单 &gt;');
				vm.name=name;
				vm.uri=uri;
				vm.icon=icon;
				vm.iconPreview();
			}else{
				return false;
			}

			vm.operateMenuId=menuId;
			vm.operateType=type;
			$('#menuIcon').attr('class','fa fa-'+icon);
			$('#menuIcon').html(' '+name);
			$('#operateModal').modal('show');
		},
		operateSure:()=>{
			lockScreen();

			if(vm.name==""){
				unlockScreen();
				showModalTips("请输入菜单名称！");
				return false;
			}
			if(vm.name.length<1 || vm.name.length>20){
				unlockScreen();
				showModalTips("请输入 1-20字 的菜单名称！");
				return false;
			}
			if(vm.icon==""){
				unlockScreen();
				showModalTips("请输入菜单图标名称！");
				return false;
			}

			$.ajax({
				url:"./toOperate",
				type:"post",
				data:{"operateType":vm.operateType,"id":vm.operateMenuId,"menuType":vm.menuType,"name":vm.name,"icon":vm.icon,"uri":vm.uri},
				dataType:"json",
				error:function(e){
					console.log(e);
					unlockScreen();
					showModalTips("服务器错误！<hr>请联系技术支持并提交以下错误码：<br><font color='blue'>"+e.status+"</font>");
					return false;
				},
				success:function(ret){
					unlockScreen();

					if(ret.code==200){
						$("#operateModal").modal('hide');
						alert("操作成功！");
						vm.getAllMenu();
						return true;
					}else if(ret.code==500){
						showModalTips("操作失败！！！");
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
		},
		deleteReady:(id,name)=>{
			vm.deleteId=id;
			$("#delName_show").html(id+". "+name);
			$("#delModal").modal('show');
		},
		deleteSure:()=>{
			lockScreen();

			$.ajax({
				url:"./toDelete",
				type:"post",
				dataType:"json",
				data:{"id":vm.deleteId},
				error:function(e){
					console.log(e);
					unlockScreen();
					$("#delModal").modal('hide');
					showModalTips("服务器错误！<hr>请联系技术支持并提交以下错误码：<br><font color='blue'>"+e.status+"</font>");
					return false;
				},
				success:function(ret){
					unlockScreen();

					if(ret.code==200){
						$("#delModal").modal('hide');
						alert("删除成功！");
						location.reload();
						return true;
					}else if(ret.code==1){
						$("#delModal").modal('hide');
						showModalTips("删除失败！！！");
						return false;
					}else if(ret.code==403001){
						$("#delModal").modal('hide');
						showModalTips("Token无效！<hr>Tips:请勿在提交前打开另一页面哦~");
						return false;
					}else if(ret.code==0){
						showModalTips("参数缺失！请联系技术支持！");
						return false;
					}else{
						$("#delModal").modal('hide');
						showModalTips("系统错误！<hr>请联系技术支持并提交以下错误码：<br><font color='blue'>"+ret.code+"</font>");
						return false;
					}
				}
			});
		}
	}
});

vm.getAllMenu();

function addHoverDom(treeId, treeNode) {
	var aObj=$("#"+treeNode.tId+"_a");
	var editStr=""
		+"<button class='btn btn-info' id='treeBtn_edit_"+treeNode.id+"' onclick="+'"'+"vm.operateReady(2,"+treeNode.id+",'"+treeNode.menuIcon+"','"+treeNode.menuName+"','"+treeNode.uri+"')"+'"'+"'>编辑</button> "
		+"<button class='btn btn-danger' id='treeBtn_delete_"+treeNode.id+"' onclick="+'"'+"vm.deleteReady("+treeNode.id+",'"+treeNode.name+"')"+'"'+"'>删除</button> "
		+"<button class='btn btn-success' id='treeBtn_add_"+treeNode.id+"' onclick="+'"'+"vm.operateReady(1,'"+treeNode.id+"','"+treeNode.menuIcon+"','"+treeNode.menuName+"')"+'"'+"'>新增子菜单</button>";
	
	// 如果已存在button就返回
	if($("#treeBtn_edit_"+treeNode.id).length>0) return;
	if($("#treeBtn_delete_"+treeNode.id).length>0) return;
	if($("#treeBtn_add_"+treeNode.id).length>0) return;
	
	aObj.append(editStr);
	
	// 三级菜单不允许新增子菜单
	if(treeNode.level>=2) $("#treeBtn_add_"+treeNode.id).remove();
}

function removeHoverDom(treeId, treeNode) {
	$("#treeBtn_edit_"+treeNode.id).unbind().remove();
	$("#treeBtn_delete_"+treeNode.id).unbind().remove();
	$("#treeBtn_add_"+treeNode.id).unbind().remove();
}
</script>

<div class="modal fade" id="delModal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
				<h3 class="modal-title">温馨提示</h3>
			</div>
			<div class="modal-body">
				<center>
				<font color="red" style="font-weight:bolder;font-size:23px;">确定要删除下列菜单吗？</font>
				<br><br>
				<font color="blue" style="font-weight:bolder;font-size:23px;"><p id="delName_show"></p></font>
				</center>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-success" data-dismiss="modal">&lt; 返回</button> <button type="button" class="btn btn-danger" onclick="vm.deleteSure()">确定 &gt;</button>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

</body>
</html>
