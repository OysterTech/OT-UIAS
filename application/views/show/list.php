<?php 
/**
 * @name V-示例表格页
 * @author Jerry Cheung <master@xshgzs.com>
 * @since 2018-08-16
 * @version 2018-08-16
 */ 
?>

<!DOCTYPE html>
<html>

<head>
	<?php $this->load->view('include/header'); ?>
	<title>列表页 / <?php echo $this->Setting_model->get('systemName'); ?></title>
</head>

<body>
<div id="wrapper">

<?php $this->load->view('include/navbar'); ?>

<div id="page-wrapper">
<!-- Page Main Content -->

<!-- Page Name-->
<div class="row">
	<div class="col-lg-12">
		<h1 class="page-header">列表</h1>
	</div>
</div>
<!-- ./Page Name-->

<table id="table" class="table table-striped table-bordered table-hover" style="border-radius: 5px; border-collapse: separate;">
	<thead>
		<tr>
			<th>标题</th>
			<th>时间</th>
		</tr>
	</thead>
	
	<tbody>
	<?php foreach($list as $info){ ?>
		<tr>
			<td><?php echo $info['title']; ?></td>
			<td><?php echo $info['create_time']; ?></td>
		</tr>
	<?php } ?>
</tbody>
</table>

<?php $this->load->view('include/footer'); ?>

<!-- ./Page Main Content -->
</div>
<!-- ./Page -->
</div>

<script>
window.onload=function(){
	$('#table').DataTable({
		responsive: true,
		"order":[[1,'desc']],
		"columnDefs":[{
			"targets":[0],
			"orderable": false
		}]
	});
};
</script>

<?php $this->load->view('include/tipsModal'); ?>

</body>
</html>
