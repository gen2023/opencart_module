<?php echo $header; ?>
<div class="container">
	<ul class="breadcrumb">
		<?php foreach ($breadcrumbs as $breadcrumb) { ?>
		<li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
		<?php } ?>
	</ul>
	<div class="row"><?php echo $column_left; ?>
		<?php if ($column_left && $column_right) { ?>
		<?php $class = 'col-sm-6'; ?>
		<?php } elseif ($column_left || $column_right) { ?>
		<?php $class = 'col-sm-9'; ?>
		<?php } else { ?>
		<?php $class = 'col-sm-12'; ?>
		<?php } ?>
		<div id="row">
			<h1 class="heading_title"><?php echo $heading_title; ?></h1>
		</div>
		<div class="uslugi__container">
            <div class="uslugi__container--inside">
                <div class="uslugi__container--left">
                    <ul>
                        <?php foreach ($services as $service) {?>
							<li class="list_services">
								<img src="catalog/view/theme/default/image/services/label.png" alt=""> 
								<span class="number_service"><?php echo $service['service_id']; ?></span>
								<span class="description_service"><?php echo isset($service['service_name'][$language]) ? $service['service_name'][$language]['name'] : ''; ?></span>
							</li>
			            <?php } ?>
                    </ul>
                </div>                        
            </div>
        </div>
		<?php echo $content_bottom; ?></div>
	<?php echo $column_right; ?></div>
</div></div>
<?php echo $footer; ?>

<link rel="stylesheet" href="catalog/view/theme/default/stylesheet/services/services.css">