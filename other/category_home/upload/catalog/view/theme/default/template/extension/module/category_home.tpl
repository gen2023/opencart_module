<div class="row" style="padding-bottom:20px;">
	<div class="list-group">
		<?php foreach ($categories as $category) { ?>
			<div class="col-sm-3" style="text-align: center;">
				<?php if ($category['category_id'] == $category_id) { ?>
					<a href="<?php echo $category['href']; ?>" class="list-group-item active">
						<?php if ($category['thumb']) { ?>
							<img style="width:100px; height:100px;" src="image/<?php echo $category['thumb']; ?>" alt="<?php echo $heading_title; ?>" title="<?php echo $heading_title; ?>" class="img-thumbnail" />
							<span class="title_cat_home"><?php echo $category['name']; ?></span>
						<?php } else { ?>
							<img style="width:100px; height:100px;" src="image/no_image.png" alt="<?php echo $heading_title; ?>" title="<?php echo $heading_title; ?>" class="img-thumbnail" />
							<span class="title_cat_home"><?php echo $category['name']; ?></span>		  
						<?php }?>
					</a>
				<?php } else { ?>
					<a href="<?php echo $category['href']; ?>" class="list-group-item">
						<?php if ($category['thumb']) { ?>
							<img style="width:100px; height:100px;" src="image/<?php echo $category['thumb']; ?>" alt="<?php echo $heading_title; ?>" title="<?php echo $heading_title; ?>" class="img-thumbnail" />
							<span class="title_cat_home"><?php echo $category['name']; ?></span>
						<?php } else { ?>
							<img style="width:100px; height:100px;" src="image/no_image.png" alt="<?php echo $heading_title; ?>" title="<?php echo $heading_title; ?>" class="img-thumbnail" />
							<span class="title_cat_home"><?php echo $category['name']; ?></span>
						<?php }?>
					</a>
				<?php } ?>  
			</div>
		<?php } ?>
	</div>
</div>
