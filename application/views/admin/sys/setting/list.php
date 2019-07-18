<?php 
/**
 * @name 生蚝科技RBAC开发框架-V-系统参数列表
 * @author Jerry Cheung <master@xshgzs.com>
 * @since 2018-03-03
 * @version 2019-06-12
 */
?>
<!DOCTYPE html>
<html>

<head>
	<?php $this->load->view('include/header'); ?>
	<title>系统参数列表 / <?=$this->setting->get('systemName');?></title>
</head>

<body class="hold-transition skin-cyan sidebar-mini">
<div class="wrapper">

<?php $this->load->view('include/navbar'); ?>

<!-- 页面内容 -->
<div id="app" class="content-wrapper">
	<?php $this->load->view('include/pagePath',['name'=>'修改系统参数','path'=>[['系统参数列表','',1]]]); ?>

	<!-- 页面主要内容 -->
	<section class="content">
		<div class="panel panel-default">
			<div class="panel-body">
				<table class="table table-striped table-bordered table-hover" style="border-radius: 5px; border-collapse: separate;">
					<thead>
						<tr>
							<th>名称</th>
							<th>内容</th>
							<th>操作</th>
						</tr>
					</thead>

					<tbody>
						<?php foreach($list as $info){ ?>
							<tr>
								<td><?=$info['chinese_name'];?></td>
								<td>
									<!-- 显示 -->
									<p id="<?=$info['name'];?>_show"><?=$info['value'];?></p>

									<!-- 输入框 -->
									<input type="hidden" id="<?=$info['name'];?>_input" class="form-control" value="<?=$info['value'];?>">
								</td>
								<td>
									<button class="btn btn-primary" id="<?=$info['name'];?>_btn1" onclick='edit("<?=$info['name'];?>")'>修改</button>
									<button class="btn btn-success" id="<?=$info['name'];?>_btn2" onclick='save("<?=$info['name'];?>")' style="display:none">保存</button>
								</td>
							</tr>
						<?php } ?>
					</tbody>
				</table>
			</div>
		</div>
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
function edit(name){
	$("#"+name+"_show").attr("style","display:none");
	$("#"+name+"_input").attr("type","");
	$("#"+name+"_btn1").attr("style","display:none");
	$("#"+name+"_btn2").attr("style","");
}

function save(name){
	lockScreen();
	value=$("#"+name+"_input").val();

	$.ajax({
		url:"./toSave",
		type:"post",
		dataType:"json",
		data:{"name":name,"value":value},
		error:function(e){
			console.log(e);
			unlockScreen();
			$("#truncateModal").modal('hide');
			showModalTips("服务器错误！<hr>请联系技术支持并提交以下错误码：<br><font color='blue'>"+e.responseText+"</font>");
			return false;
		},
		success:function(ret){
			unlockScreen();
			
			if(ret.code==200){
				alert("设置成功！");
				$("#"+name+"_show").attr("style","");
				$("#"+name+"_show").html(value);
				$("#"+name+"_input").attr("type","hidden");
				$("#"+name+"_btn1").attr("style","");
				$("#"+name+"_btn2").attr("style","display:none");
				return true;
			}else if(ret.message=="noSetting"){
				showModalTips("无此配置项！");
				return false;
			}else if(ret.code==1){
				showModalTips("保存失败！");
				return false;
			}else{
				$("#truncateModal").modal('hide');
				showModalTips("系统错误！<hr>请联系技术支持并提交以下错误码：<br><font color='blue'>"+ret.code+"</font>");
				return false;
			}
		}
	});
}
</script>

</body>
</html>
