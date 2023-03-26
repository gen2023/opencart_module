<div class="box">
	<div class="box-heading"><?php if ($ajaxtestimonial_title=="") echo "<br>"; else echo $ajaxtestimonial_title; ?></div>
	<div class="box-content">
		<?php if (($setting['position'] == 'content_top') || ($setting['position'] == 'content_bottom')) { ?>
			<div class="box-testimonials-module home-testimonials">
				<?php }else{ ?>
			<div class="box-testimonials-module">
			<?php } ?>
				<?php foreach ($ajaxtestimonials as $ajaxtestimonial) { ?>
					<div class="item">
						<div class="name"><i><?php echo $ajaxtestimonial['date_added']; ?></i><span><?php echo $ajaxtestimonial['name']; ?></span></div>
						<div class="text">
							<?php echo $ajaxtestimonial['description'] ; ?>
						</div>
						<?php if ($ajaxtestimonial['rating']) { ?>
							<div class="rating">
								<?php for ($i = 1; $i <= 5; $i++) { ?>
									<?php if ($ajaxtestimonial['rating'] < $i) { ?>
										<img src="<?php echo $template_path.'/image/ajaxtestimonial-small-0.png'; ?>" alt="">
										<?php } else { ?>
										<img src="<?php echo $template_path.'/image/ajaxtestimonial-small-1.png'; ?>" alt="">
									<?php } ?>
								<?php } ?>	
							</div>
						<?php } ?>	
						<a href="<?php echo $ajaxtestimonial['full_review'];?>" class="more"><?php echo $text_more2; ?></a>
					</div>
				<?php } ?>
				<div style="clear:both"></div>
				<a href="<?php echo $showall_url;?>" class="view-all"><?php echo $show_all; ?></a>
			</div>
		</div>
	</div>	