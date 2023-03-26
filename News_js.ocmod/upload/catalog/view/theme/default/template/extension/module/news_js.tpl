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
		<div id="content" class="<?php echo $class; ?>"><?php echo $content_top; ?>
			<?php if ($newsJs_list) {?>			
			<div class="row">
				<div class="newsJs_listName">
					<ul class="news_name">
						<?php $countT=0; ?>
						<?php foreach ($newsJs_list as $news_item) { ?>								
							<li class="newsJs_title"><div><?php echo $news_item['title']; ?></div>
								<span class="none js_newsJs_content"><img src="<?php echo $news_item['thumb']?>">
								<p><?php echo $news_item['description']; ?></p></span>
							</li>
							
						<?php } ?>
					</ul>					
				</div>
			</div>
			<?php } else { ?>
			<p><?php echo $text_empty; ?></p>
			<div class="buttons">
				<div class="pull-right"><a href="<?php echo $continue; ?>" class="btn btn-primary"><?php echo $button_continue; ?></a></div>
			</div>
			<?php } ?>
			<?php echo $content_bottom; ?>
		</div>
		<?php echo $column_right; ?>
	</div>
</div>
<?php echo $footer; ?>

  <script src="catalog/view/javascript/newsJs/newsJs.js"></script>
  <link rel="stylesheet" href="catalog/view/theme/default/stylesheet/newsJs/newsJs.css">

