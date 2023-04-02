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
		<div id="content" class="<?php echo $class; ?>"><?php echo $content_top; ?>
			<h1><center><?php echo $heading_title; ?></center></h1>
			<?php if ($results) { ?>
			<div class="row">
				<div class="col-md-3">

				</div>
				<div class="col-md-2 text-right">
					<label class="control-label" for="input-sort"><?php echo $text_sort; ?></label>
				</div>
				<div class="col-md-3 text-right">
					<select id="input-sort" class="form-control" onchange="location = this.value;">
						<?php foreach ($sorts as $sorts) { ?>
						<?php if ($sorts['value'] == $sort . '-' . $order) { ?>
						<option value="<?php echo $sorts['href']; ?>" selected="selected"><?php echo $sorts['text']; ?></option>
						<?php } else { ?>
						<option value="<?php echo $sorts['href']; ?>"><?php echo $sorts['text']; ?></option>
						<?php } ?>
						<?php } ?>
					</select>
				</div>
				<div class="col-md-2 text-right">
					<label class="control-label" for="input-limit"><?php echo $text_limit; ?></label>
				</div>
				<div class="col-md-2 text-right">
					<select id="input-limit" class="form-control" onchange="location = this.value;">
						<?php foreach ($limits as $limits) { ?>
						<?php if ($limits['value'] == $limit) { ?>
						<option value="<?php echo $limits['href']; ?>" selected="selected"><?php echo $limits['text']; ?></option>
						<?php } else { ?>
						<option value="<?php echo $limits['href']; ?>"><?php echo $limits['text']; ?></option>
						<?php } ?>
						<?php } ?>
					</select>
				</div>
			</div>
			<br />
			<div class="row">
				<?php foreach ($events_list as $events_item) { ?>
				<div class="product-layout product-list col-xs-12" style="width:100%;">
					<div style="margin-bottom: 20px; overflow: auto;">
						<?php if($events_item['thumb']) { ?>
						<div class="image"><a href="<?php echo $events_item['href']; ?>">
						<img style="margin-right:20px;" align="left" src="<?php echo $events_item['thumb']; ?>" alt="<?php echo $events_item['title']; ?>" title="<?php echo $events_item['title']; ?>" class="img-responsive" /></a></div>
						<?php }?>
						<div>
							<div class="caption" style="margin-left:50px">
								<?php echo $events_item['posted']; ?><br>
								<h4><a href="<?php echo $events_item['href']; ?>"><?php echo $events_item['title']; ?></a></h4>
								<p><?php echo $events_item['description']; ?></p>
							</div>
							<div class="buttons">
								<div class="pull-right"><a href="<?php echo $events_item['href']; ?>" class="btn btn-primary"><?php echo $text_more; ?></a></div>
							</div>							
						</div>
					</div>
					<hr style="color: black; background-color: black; height: 1px;">
				</div>
				<?php } ?>
			</div>
			<div class="row">
				<div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
				<div class="col-sm-6 text-right"><?php echo $results; ?></div>
			</div>
			<?php } else { ?>
			<p><?php echo $text_empty; ?></p>
			<div class="buttons">
				<div class="pull-right"><a href="<?php echo $continue; ?>" class="btn btn-primary"><?php echo $button_continue; ?></a></div>
			</div>
			<?php } ?>
			
			<?php echo $content_bottom; ?>
		</div>
	<?php echo $column_right; ?></div>
</div>
<?php echo $footer; ?>