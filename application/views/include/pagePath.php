<?php 
/**
 * @name 生蚝科技RBAC开发框架-V-页面路径导航
 * @author Jerry Cheung <master@xshgzs.com>
 * @since 2019-03-16
 * @version 2019-03-16
 */
?>
<section class="content-header">
	<h1><?=$name;?></h1>
	<ol class="breadcrumb">
		<li><a href="<?=base_url('dashborad');?>"><i class="fa fa-dashboard"></i> <?=$this->setting->get('systemName'); ?></a></li>
		<?php foreach($path as $info){ ?>
			<li <?php if(isset($info[2])&&$info[2]==1){echo 'class="active">';}else{echo '><a href="'.$info[1].'">';} ?><?=$info[0];?><?php if($info[1]){echo '</a>';}?></li>
		<?php } ?>		
	</ol>
</section>