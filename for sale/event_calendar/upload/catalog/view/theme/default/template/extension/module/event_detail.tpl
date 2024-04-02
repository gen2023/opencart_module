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
      <div class="row">
        <?php if ($column_left && $column_right) { ?>
        <?php $class = 'col-sm-6'; ?>
        <?php } elseif ($column_left || $column_right) { ?>
        <?php $class = 'col-sm-9'; ?>
        <?php } else { ?>
        <?php $class = 'col-sm-12'; ?>
        <?php } ?>
        <div class="<?php echo $class; ?>">
            <div class="eventTitle">
                <h3><?php echo $heading_title; ?></h3>
            </div>
			<div class="contentEvent">
				<div class="imageEvent">
					<img src="<?php echo $image; ?>"/>
					<div class="event-subtitle">               
						<!-- AddThis Button BEGIN -->
							<?php if ($event_setting['event_share']==0) $view = 'display:none;'; else $view = '';?>
							<div style='<?php echo $view;?>' class="addthis_toolbox addthis_default_style"><a class="addthis_button_facebook_like" fb:like:layout="button_count"></a> <a class="addthis_button_tweet"></a> <a class="addthis_button_pinterest_pinit"></a> <a class="addthis_counter addthis_pill_style"></a></div>
							<script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-515eeaf54693130e"></script>
						<!-- AddThis Button END -->
					</div>
				</div>
				<div class="contentDescriptionEvent">
					<div class="dateEvent">
						<b><h3>
							<?php echo $event_date_to; ?><?php echo $date_To; ?>
							<?php echo $event_date_from; ?><?php echo $date_From; ?>
							&nbsp;(<?php echo $time_to; ?> - <?php echo $time_from; ?>)
						</h3></b>
					</div>
					<?php if($product) { ?>
						<div class="productEvent">
							<div class="product-thumb">
								<?php foreach ($product as $product) { ?>								
									<div id="featured-product<?php echo $product['product_id']; ?>">	
										<div class="image"><a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>"/></a></div>''
										<h4 class="nameProductEvent"><?php echo $product['name']; ?></h4>								
									</div>
								<?php } ?>
							</div>
						</div>
					<?php } ?>
					<div class="descriptionEvent">
						<p><?php echo $description; ?></p>
					</div>
				</div>
			</div>
        </div>
      </div>
      <?php echo $content_bottom; ?></div>
    <?php echo $column_right; ?></div>
</div>
<?php echo $footer; ?>
