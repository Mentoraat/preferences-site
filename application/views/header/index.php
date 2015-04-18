<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<?php linkCSS('vendor/bootstrap.min'); ?>
		<?php linkCSS('site'); ?>
		<?php linkCSS($this->router->class); ?>

		<?php insertScript('vendor/jquery-2.1.3.min'); ?>
		<?php insertScript('vendor/bootstrap.min'); ?>
		<?php insertScript($this->router->class); ?>
		<title>Mentoraat</title>
	</head>
	<body>
		<script>
			var siteUrl = '<?= site_url(); ?>';
		</script>
		<?php $this->load->import('header/navigation'); ?>
		<div id="content" class="container">
