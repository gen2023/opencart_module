<div class="<?php echo $css_class; ?>">
	<div class="opgtm-testimonial-inner <?php if (empty($author_image)) { echo 'opgtm-no-author-image'; } ?>">
	<?php if ( isset( $author_image ) && ($display_avatar)) { ?>
			<div class="opgtm-testimonial_avatar">			
					<div class="opgtm-avtar-image"><?php echo $author_image;?></div>			
			</div>
		<?php }?>
		<div class="opgtm-testimonial-content">
			<div class="opgtm-testimonials-text">
				<p>
				
					<?php  echo get_the_content(); ?> 
				
				</p>
			</div>
		</div>
		<div class="opgtm-testimonial-author">
			<?php if($display_job && !empty($job_title) || $display_company && !empty($company)) { ?>
				<div class="opgtm-testimonial-cdec">
					<?php  if($display_company && !empty($company)) {
							echo '<a href="'.esc_url($url).'" > '.esc_html($company).' </a>';
						}?>
				</div>
				<div class="opgtm-testimonial-client">
					<?php 
						if($display_client && !empty($author)){
							?> <span class="nameAutor"><?php echo esc_html($author);?></span><?php
						} 
						if($display_job && !empty($job_title)) {
							?> <span class="positionAutor"><em><?php echo esc_html($job_title); ?></em></span><?php
						}						
					?>
				</div>
			<?php } ?>
		</div>
	</div>
</div>