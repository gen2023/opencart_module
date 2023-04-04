<?php echo $header; ?>
<div class="container" id="introPanel">
	<div class="row"><?php echo $column_left; ?>
    	<?php if ($column_left && $column_right) { ?>
    		<?php $class = 'col-sm-6'; ?>
    	<?php } elseif ($column_left || $column_right) { ?>
    		<?php $class = 'col-sm-9'; ?>
    	<?php } else { ?>
    		<?php $class = 'col-sm-12'; ?>
    	<?php }?>
    	<div id="content" class="<?php echo $class; ?>"><?php echo $content_top; ?>
    		<h2 style="text-align:center;"><?php echo $heading_title; ?></h2>

      		<div id="calendar" class="calendar_gen"></div>
      			<div class="row">
        			<!--<div class="col-sm-6 text-left"><?php echo $pagination; ?></div>-->
        			<!--<div class="col-sm-6 text-right"><?php echo $results; ?></div>-->
      			</div>				
      		<?php  
			if (!$events) { ?>
      			<p><?php echo $text_empty; ?></p>
      		<?php }	?>
      		<?php echo $content_bottom; ?>
        </div>
   	 	<?php echo $column_right; ?>
    </div>
</div>
<script>

  document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');

    var calendar = new FullCalendar.Calendar(calendarEl, {
      headerToolbar: {
        left: 'prev,next today',
        center: 'title',
        right: 'dayGridMonth,listMonth'
      },
      navLinks: true, 
	  firstDay: <?php echo $firstDay; ?>,
	  locale: '<?php echo $eventLocale; ?>',
	  dayMaxEvents: <?php echo $dayMaxEvents; ?>,
	  eventOrder: 'sort_order',
      events: [
		<?php foreach ($events as $event) {
				
				$new_from=explode('-',date("Y-m-d", strtotime($event['date_from']))); 
							
				$new_to=explode('-',date("Y-m-d", strtotime($event['date_to']))); 
				?>
			{
			title: '<?php print_r( $event["mindescription"]); ?>',
			color: '<?php echo $event["color"]; ?>',
			url: 'index.php?route=extension/module/event_detail&event_id=<?php print_r($event["event_id"]); ?>',
			start: '<?php echo $new_to[0] . "-" . $new_to[1] . "-".$new_to[2]; ?>',
			end: '<?php echo $new_from[0] . "-" . $new_from[1] ."-" . $new_from[2]; ?>',
			sort_order: '<?php echo $event["sort_order"]; ?>'

			},
		<?php } ?>{}] 
    });

    calendar.render();
  });

</script>
	
<?php echo $footer; ?>