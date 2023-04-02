<div class="subscribe-box" style="margin-bottom: 20px;">

<div class="panel panel-default">
        <div class="panel-body">
          <div class="row">
            <?php if ($image) { ?>
            <div class="col-sm-3"><img src="<?php echo $image; ?>" alt="<?php echo $store; ?>" title="<?php echo $store; ?>" /></div>
            <?php } ?>
            <div class="col-sm-3"><strong><?php echo $store; ?></strong><br />
              <address>
              <?php echo $address; ?>
              </address>
            </div>
            <div class="col-sm-3"><strong><?php echo $text_telephone; ?></strong><br>
              <?php echo $telephone; ?><br />
            </div>
            <div class="col-sm-3">
              <?php if ($open) { ?>
              <strong><?php echo $text_open; ?></strong><br />
              <?php echo $open; ?><br />
              <br />
              <?php } ?>
            </div>
          </div>
        </div>
      </div>
</div>
