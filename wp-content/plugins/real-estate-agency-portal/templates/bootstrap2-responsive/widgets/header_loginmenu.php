<div class="top-wrapper">
      <div class="container">
        <div class="masthead">
        {not_logged}
        <ul class="nav pull-left top-small">
          <li><span><i class="icon-phone"></i> {settings_phone}</span></li>
          <li><a href="mailto:{settings_email}"><i class="icon-envelope"></i> {settings_email}</a></li>
          <?php if(config_db_item('property_subm_disabled')==FALSE):  ?>
          <li><a href="{front_login_url}#content"><i class="icon-user"></i> {lang_Login}</a></li>
          <?php endif;?>
        </ul>
        {/not_logged}
        <?php if(config_db_item('property_subm_disabled')==FALSE):  ?>
        {is_logged_user}
        <ul class="nav pull-left top-small">
        <?php if(file_exists(APPPATH.'controllers/admin/booking.php')):?>
          <li><a href="{myreservations_url}#content"><i class="icon-shopping-cart"></i> {lang_Myreservations}</a></li>
        <?php endif; ?>
          <li><a href="{myproperties_url}#content"><i class="icon-list"></i> {lang_Myproperties}</a></li>
        <?php if(file_exists(APPPATH.'controllers/admin/savesearch.php')): ?>
          <li><a href="{myresearch_url}#content"><i class="icon-filter"></i> {lang_Myresearch}</a></li>  
        <?php endif; ?>
        <?php if(file_exists(APPPATH.'controllers/admin/favorites.php')):?>
          <li><a href="{myfavorites_url}#content"><i class="icon-star"></i> {lang_Myfavorites}</a></li>
        <?php endif; ?>
          <li><a href="<?php _che($mymessages_url); ?>#content"><i class="icon-envelope"></i> <?php _l('My messages'); ?></a></li>
          <li><a href="{myprofile_url}#content"><i class="icon-user"></i> {lang_Myprofile}</a></li>
          <li><a href="{logout_url}"><i class="icon-off"></i> {lang_Logout}</a></li>
        </ul>
        {/is_logged_user}
        {is_logged_other}
        <ul class="nav pull-left top-small">
          <li><a href="{login_url}"><i class="icon-wrench"></i> {lang_Admininterface}</a></li>
          <li><a href="{logout_url}"><i class="icon-off"></i> {lang_Logout}</a></li>
        </ul>
        {/is_logged_other}
        <?php endif;?>
        <?php echo print_breadcrump(null, ' > ', 'class="breadcrumb nav pull-right"');?>
        </div>
      </div> <!-- /.container -->
</div>