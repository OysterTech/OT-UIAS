<?php
/**
 * @name 生蚝科技统一身份认证平台-V-通知详情
 * @author Jerry Cheung <master@smhgzs.com>
 * @since 2019-01-07
 * @version 2019-01-19
 */
?>
<!DOCTYPE html>
<html>
<head>
	<title>通知详情 / <?=$this->setting->get('systemName');?></title>
	<?php $this->load->view('include/header'); ?>
</head>
<body class="hold-transition skin-cyan sidebar-mini">
<div class="wrapper">

<?php $this->load->view('include/navbar'); ?>

<!-- 页面内容 -->
<div id="app" class="content-wrapper">
	<!-- 头部(显示页面名称和路径) -->
	<section class="content-header">
		<h1>{{noticeInfo['title']}}</h1>
		<ol class="breadcrumb">
			<li><a href="<?=base_url('dashborad');?>"><i class="fa fa-dashboard"></i> 生蚝科技用户中心</a></li>
			<li class="active">通知</li>
			<li class="active">通知详情</li>
		</ol>
	</section>

	<!-- 页面主要内容 -->
	<section class="content">
		<div class="box">
			<div class="box-body">
			<h2 style="text-align:center;">{{noticeInfo['title']}}</h2>
			
			<table style="width:100%">
				<tr>
					<td style="width:50%;text-align:left">发布人：{{noticeInfo['publisher']}}</td>
					<td style="width:50%;text-align:right">{{noticeInfo['create_time']}}</td>
				</tr>
			</table>
			
			<hr>
			
			{{noticeInfo['content']}}
			
			<br><br><br>
			
			<table style="width:100%">
				<tr>
					<td style="width:50%;text-align:left">阅读量：{{noticeInfo['read_count']}}</td>
					<td style="width:50%;text-align:right"><a v-on:click="toPraise" v-bind:style="{color:praiseColor}"><i class="fa fa-thumbs-o-up" aria-hidden="true"></i> ({{noticeInfo['praise']}})</a></td>
				</tr>
			</table>
			
			</div>
		</div>
	</section>
	<!-- ./页面主要内容 -->
</div>
<!-- ./页面内容 -->

<?php $this->load->view('include/footer'); ?>

</div>
<!-- ./wrapper -->

<script>
var vm = new Vue({
	el:'#app',
	data:{
		noticeInfo:{},
		praiseColor:""
	},
	created:function(){
		praiseNotices=getCookie("<?=$this->sessPrefix;?>praiseNotices");
		if(praiseNotices==NULL) return;
		praiseNotices=praiseNotices.split(",");
		isPraise=$.inArray(getURLParam("id"),praiseNotices);

		if(isPraise>=0) vm.praiseColor="green";
	},
	methods:{
		getNotice:function(){
			lockScreen();
			$.ajax({
				url:headerVm.apiPath+"notice/get",
				data:{"type":"detail","id":getURLParam("id")},
				dataType:"json",
				error:function(e){
					unlockScreen();
					showModalTips("服务器错误！"+e.status);
					console.log(e);
					return false;
				},
				success:function(ret){
					unlockScreen();
					if(ret.code==200){
						list=ret.data['list'];
						
						if(list[0]!=null){
							vm.noticeInfo=list[0];
						}else{
							alert("通知不存在！");
							history.go(-1);
							return false;	
						}
					}else{
						showModalTips("系统错误！"+ret.code);
						console.log(ret);
						return false;
					}
				}
			});
		},
		toPraise:function(){
			
		}
	}
});

vm.getNotice();

</script>

</body>
</html>
