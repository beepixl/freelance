<!DOCTYPE html>
<html lang="{lang_code}">
  <head>
    <?php _widget('head');?>
    <link href="assets/js/footable/css/footable.core.css" rel="stylesheet">  
    <script src="assets/js/footable/js/footable.js"></script>
    <script language="javascript">
    $(document).ready(function(){
        $('.footable').footable();
    });    
    </script>
  </head>

  <body>
  
{template_header}

<a name="content" id="content"></a>
<div class="wrap-content">
    <div class="container">
        <div class="row-fluid">
            <div class="span12">
            <a name="content" id="content"></a>
            <h2>{lang_Myproperties}</h2>
            <div class="property_content">
                <div class="widget-controls"> 
                    <?php echo anchor('frontend/editproperty/'.$lang_code.'#content', '<i class="icon-plus"></i>&nbsp;&nbsp;'.lang_check('Addproperty'), 'class="btn btn-info"')?>
                    
                    <?php if(file_exists(APPPATH.'controllers/admin/booking.php')):?>
                        <?php echo anchor('rates/payments/'.$lang_code.'#content', '<i class="icon-book"></i>&nbsp;&nbsp;'.lang_check('Payments received'), 'class="btn btn-primary pull-right"')?>
                        <?php echo anchor('rates/index/'.$lang_code.'#content', '<i class="icon-calendar"></i>&nbsp;&nbsp;'.lang_check('Edit availability and rates'), 'class="btn pull-right" style="margin-right:5px;"')?>
                    <?php endif; ?>
                    
                    <?php if(config_item('enable_table_calendar') === TRUE): ?>
                    <?php echo anchor('trates/index/'.$lang_code.'#content', '<i class="icon-calendar"></i>&nbsp;&nbsp;'.lang_check('Edit calendar'), 'class="btn pull-right btn-warning" style="margin-right:5px;"')?>
                    <?php endif; ?>
                </div>
                <div class="widget-content">
                    <?php if($this->session->flashdata('error')):?>
                    <p class="alert alert-error"><?php echo $this->session->flashdata('error')?></p>
                    <?php endif;?>
                    <?php if($this->session->flashdata('message')):?>
                    <?php echo $this->session->flashdata('message')?>
                    <?php endif;?>
                    <table class="table table-striped footable">
                      <thead>
                        <tr>
                        	<th><?php _l('#'); ?></th>
                            <th><?php echo lang('Address');?></th>
                            <!-- Dynamic generated -->
                            <?php foreach($this->option_m->get_visible($content_language_id) as $row):?>
                            <th data-hide="phone,tablet"><?php echo $row->option?></th>
                            <?php endforeach;?>
                            <!-- End dynamic generated -->
                            <th></th>
                        	<th data-hide="phone" class="control"><?php echo lang('Edit');?></th>
                            <th data-hide="phone" class="control"><?php _l('Preview');?></th>
                        	<th data-hide="phone" class="control"><?php echo lang('Delete');?></th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php if(count($estates)): foreach($estates as $estate):?>
                                    <tr>
                                    	<td><?php echo $estate->id?></td>
                                        <td>
                                        <?php echo anchor('frontend/editproperty/'.$lang_code.'/'.$estate->id, $estate->address)?>
                                        
                                        <?php if(!empty($estate->status) && $estate->status == 'DECLINE'):?>
                                        &nbsp;&nbsp;&nbsp;&nbsp;<span class="label label-important"><?php echo $estate->status; ?></span>
                                        <?php elseif($estate->is_activated == 0):?>
                                        &nbsp;<span class="label label-important"><?php echo lang_check('Not activated')?></span>
                                        <?php endif;?>
                                        
                                        <?php if( isset($settings_listing_expiry_days) && $settings_listing_expiry_days > 0 && strtotime($estate->date_modified) <= time()-$settings_listing_expiry_days*86400): ?>
                                        &nbsp;<span class="label label-warning"><?php echo lang_check('Expired')?></span>
                                        <?php endif; ?>
                                        

                                        </td>
                                        <!-- Dynamic generated -->
                                        <?php foreach($this->option_m->get_visible($content_language_id) as $row):?>
                                        <td><?php echo isset($options[$estate->id][$row->option_id])?$options[$estate->id][$row->option_id]:''?></td>
                                        <?php endforeach;?>
                                        <!-- End dynamic generated -->
                                        <td>
                                        <?php if($estate->is_activated == 0 && $settings_activation_price > 0  && config_db_item('payments_enabled') == 1):?>
<div class="btn-group">
<a class="btn btn-warning dropdown-toggle" data-toggle="dropdown" href="#">
<?php echo '<i class="icon-shopping-cart"></i> '.lang('Pay for activation'); ?>
<span class="caret"></span>
</a>
<ul class="dropdown-menu">
    <?php if(!_empty(config_db_item('paypal_email'))): ?>
    <li><a href="<?php echo site_url('frontend/do_purchase_activation/'.$lang_code.'/'.$estate->id.'/'.$settings_activation_price); ?>"><?php echo '<i class="icon-shopping-cart"></i> '.lang_check('Pay for activation').' '.lang_check('with PayPal'); ?></a></li>
    <?php endif; ?>
    
    <?php if(file_exists(APPPATH.'controllers/paymentconsole.php') && !_empty(config_db_item('authorize_api_login_id'))): ?>
    <li><a href="<?php echo site_url('paymentconsole/authorize_payment/'.$lang_code.'/'.$settings_activation_price.'/'.$settings_default_currency.'/'.$estate->id.'/ACT'); ?>"><?php echo '<i class="icon-shopping-cart"></i> '.lang_check('Pay for activation').' '.lang_check('with CreditCard'); ?></a></li>
    <?php endif; ?>
    
    <?php if(file_exists(APPPATH.'controllers/paymentconsole.php') && !_empty(config_db_item('payu_api_pos_id'))): ?>
    <li><a href="<?php echo site_url('paymentconsole/payu_payment/'.$lang_code.'/'.$settings_activation_price.'/'.$settings_default_currency.'/'.$estate->id.'/ACT'); ?>"><?php echo '<i class="icon-shopping-cart"></i> '.lang_check('Pay for activation').' '.lang_check('with CreditCard payu'); ?></a></li>
    <?php endif; ?>
    
    <?php if(!empty($settings_withdrawal_details) && file_exists(APPPATH.'controllers/paymentconsole.php')):?>
    <li><a href="<?php echo site_url('paymentconsole/invoice_payment/'.$lang_code.'/'.$settings_activation_price.'/'.$settings_default_currency.'/'.$estate->id.'/ACT'); ?>"><?php echo '<i class="icon-shopping-cart"></i> '.lang_check('Pay for activation').' '.lang_check('with bank payment'); ?></a></li>
    <?php endif; ?>
</ul>
</div>
                                        <?php endif;?>
                                        
                                        <?php if($estate->is_featured == 0 && $estate->is_activated == 1):?>
<?php if($this->packages_m->get_available_featured() > 0): ?>
<a class="btn btn-primary" href="<?php echo site_url('fproperties/make_featured/'.$lang_code.'/'.$estate->id.'/'); ?>">
<?php echo '<i class="icon-circle-arrow-up"></i> '.lang_check('Make featured'); ?>
</a>
<?php elseif($settings_featured_price > 0 && config_db_item('payments_enabled') == 1): ?>                        
    <div class="btn-group">
    <a class="btn btn-primary dropdown-toggle" data-toggle="dropdown" href="#">
    <?php echo '<i class="icon-shopping-cart"></i> '.lang_check('Pay for featured'); ?>
    <span class="caret"></span>
    </a>
    <ul class="dropdown-menu">
        <?php if(!_empty(config_db_item('paypal_email'))): ?>
        <li><a href="<?php echo site_url('frontend/do_purchase_featured/'.$lang_code.'/'.$estate->id.'/'.$settings_featured_price); ?>"><?php echo '<i class="icon-shopping-cart"></i> '.lang_check('Pay for featured').' '.lang_check('with PayPal'); ?></a></li>
        <?php endif; ?>
        
        <?php if(file_exists(APPPATH.'controllers/paymentconsole.php') && !_empty(config_db_item('authorize_api_login_id'))): ?>
        <li><a href="<?php echo site_url('paymentconsole/authorize_payment/'.$lang_code.'/'.$settings_featured_price.'/'.$settings_default_currency.'/'.$estate->id.'/FEA'); ?>"><?php echo '<i class="icon-shopping-cart"></i> '.lang_check('Pay for featured').' '.lang_check('with CreditCard'); ?></a></li>
        <?php endif; ?>
        
        <?php if(file_exists(APPPATH.'controllers/paymentconsole.php') && !_empty(config_db_item('payu_api_pos_id'))): ?>
        <li><a href="<?php echo site_url('paymentconsole/payu_payment/'.$lang_code.'/'.$settings_featured_price.'/'.$settings_default_currency.'/'.$estate->id.'/FEA'); ?>"><?php echo '<i class="icon-shopping-cart"></i> '.lang_check('Pay for featured').' '.lang_check('with CreditCard payu'); ?></a></li>
        <?php endif; ?>
        
        <?php if(!empty($settings_withdrawal_details) && file_exists(APPPATH.'controllers/paymentconsole.php')):?>
        <li><a href="<?php echo site_url('paymentconsole/invoice_payment/'.$lang_code.'/'.$settings_featured_price.'/'.$settings_default_currency.'/'.$estate->id.'/FEA'); ?>"><?php echo '<i class="icon-shopping-cart"></i> '.lang_check('Pay for featured').' '.lang_check('with bank payment'); ?></a></li>
        <?php endif; ?>
    </ul>
    </div>
<?php endif;?>
                                        <?php endif;?>
                                        
                                        </td>
                                    	<td><?php echo anchor('frontend/editproperty/'.$lang_code.'/'.$estate->id, '<i class="icon-edit"></i> '.lang('Edit'), array('class'=>'btn btn-info'))?></td>
                                    	<td><?php echo anchor('property/'.$estate->id.'/'.$lang_code.'?preview=true', '<i class="icon-search"></i> ', array('class'=>'btn btn-success', 'target'=>'_blank'))?></td>
                                        <td><?php echo anchor('frontend/deleteproperty/'.$lang_code.'/'.$estate->id, '<i class="icon-remove"></i> '.lang('Delete'), array('onclick' => 'return confirm(\''.lang_check('Are you sure?').'\')', 'class'=>'btn btn-danger'))?></td>
                                    </tr>
                        <?php endforeach;?>
                        <?php else:?>
                                    <tr>
                                    	<td colspan="20"><?php echo lang_check('You can add your first property');?></td>
                                    </tr>
                        <?php endif;?>           
                      </tbody>
                    </table>

                  </div>
            </div>
            </div>
        </div>
        
        <?php if(file_exists(APPPATH.'controllers/admin/packages.php')): ?>
        
        <div class="row-fluid">
            <div class="span12">
            <h2>{lang_Mypackage}</h2>
            <div class="property_content">
                <div class="widget-content">
                    <?php if($this->session->flashdata('error_package')):?>
                    <p class="alert alert-error"><?php echo $this->session->flashdata('error_package')?></p>
                    <?php endif;?>
                    <table class="table table-striped footable">
                      <thead>
                        <tr>
                        	<th>#</th>
                            <th><?php echo lang_check('Package name');?></th>
                            <th><?php echo lang_check('Price');?></th>
                            <th data-hide="phone"><?php echo lang_check('Free property activation');?></th>
                            <th><?php echo lang_check('Days limit');?></th>
                            <th><?php echo lang_check('Listings limit');?></th>
                            <th data-hide="phone,tablet"><?php echo lang_check('Free featured limit');?></th>
                        	<th class="control" style="width: 120px;"><?php echo lang('Buy/Extend');?></th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php
                        if(count($packages)): foreach($packages as $package):
                        
                        if(!empty($user['package_id']) && 
                           $user['package_id'] != $package->id &&
                           strtotime($user['package_last_payment']) >= time() &&
                           $packages_days[$package->id] > 0 &&
                           $packages_price[$user['package_id']] > 0)
                        {
                            continue;
                        }
                        else if(!empty($package->user_type) && $package->user_type != 'USER' && $user['package_id'] != $package->id)
                        {
                            continue;
                        }
                        
                        ?>
                                    <tr>
                                    	<td><?php echo $package->id; ?></td>
                                        <td>
                                        <?php echo $package->package_name; ?>
                                        <?php echo $package->show_private_listings==1?'&nbsp;<i class="icon-eye-open"></i>':'&nbsp;<i class="icon-eye-close"></i>'; ?>
                                        <?php if($user['package_id'] == $package->id):?>
                                        &nbsp;<span class="label label-success"><?php echo lang_check('Activated'); ?></span>
                                        <?php else: ?>
                                        &nbsp;<span class="label label-important"><?php echo lang_check('Not activated'); ?></span>
                                        <?php endif;?>
                                        
                                        <?php if($package->package_price > 0 && $user['package_id'] == $package->id && strtotime($user['package_last_payment']) < time() && $packages_days[$package->id] > 0): ?>
                                        &nbsp;<span class="label label-warning"><?php echo lang_check('Expired'); ?></span>
                                        <?php endif; ?>
                                        </td>
                                        <td>
                                        <?php echo $package->package_price.' '.$package->currency_code; ?>
                                        </td>
                                        <td><?php echo $package->auto_activation?'<i class="icon-ok"></i>':''; ?></td>
                                        <td>
                                        <?php 
                                            echo $package->package_days;
                                        
                                            if($user['package_id'] == $package->id && $package->package_price > 0 &&
                                               strtotime($user['package_last_payment']) >= time() && $packages_days[$package->id] > 0 )
                                            {
                                                echo ', '.$user['package_last_payment'];
                                            }
                                        
                                        ?>
                                        </td>
                                        <td>
                                        <?php echo $package->num_listing_limit?>
                                        </td>
                                        <td>
                                        <?php echo $package->num_featured_limit?>
                                        </td>
                                    	<td>
<?php if($package->package_price > 0  && config_db_item('payments_enabled') == 1): ?>                     
<div class="btn-group">
<a class="btn btn-info dropdown-toggle" data-toggle="dropdown" href="#">
<?php echo '<i class="icon-shopping-cart"></i> '.lang('Buy/Extend'); ?>
<span class="caret"></span>
</a>
<ul class="dropdown-menu">
    <?php if(!_empty(config_db_item('paypal_email'))): ?>
    <li><a href="<?php echo site_url('frontend/do_purchase_package/'.$lang_code.'/'.$package->id.'/'.$package->package_price); ?>"><?php echo '<i class="icon-shopping-cart"></i> '.lang_check('Buy/Extend').' '.lang_check('with PayPal'); ?></a></li>
    <?php endif; ?>
    <?php if(file_exists(APPPATH.'controllers/paymentconsole.php') && !_empty(config_db_item('authorize_api_login_id'))): ?>
    <li><a href="<?php echo site_url('paymentconsole/authorize_payment/'.$lang_code.'/'.$package->package_price.'/'.$package->currency_code.'/'.$package->id.'/PAC'); ?>"><?php echo '<i class="icon-shopping-cart"></i> '.lang_check('Buy/Extend').' '.lang_check('with CreditCard'); ?></a></li>
    <?php endif; ?>
    
    <?php if(file_exists(APPPATH.'controllers/paymentconsole.php') && !_empty(config_db_item('payu_api_pos_id'))): ?>
    <li><a href="<?php echo site_url('paymentconsole/payu_payment/'.$lang_code.'/'.$package->package_price.'/'.$package->currency_code.'/'.$package->id.'/PAC'); ?>"><?php echo '<i class="icon-shopping-cart"></i> '.lang_check('Buy/Extend').' '.lang_check('with CreditCard payu'); ?></a></li>
    <?php endif; ?>
        
    
    <?php if(!empty($settings_withdrawal_details) && file_exists(APPPATH.'controllers/paymentconsole.php')):?>
    <li><a href="<?php echo site_url('paymentconsole/invoice_payment/'.$lang_code.'/'.$package->package_price.'/'.$package->currency_code.'/'.$package->id.'/PAC'); ?>"><?php echo '<i class="icon-shopping-cart"></i> '.lang_check('Buy/Extend').' '.lang_check('with bank payment'); ?></a></li>
    <?php endif; ?>
</ul>
</div>
<?php endif; ?>                               
</td>
                                    </tr>
                        <?php endforeach;?>
                        <?php else:?>
                                    <tr>
                                    	<td colspan="20"><?php echo lang_check('Not available');?></td>
                                    </tr>
                        <?php endif;?>           
                      </tbody>
                    </table>

                  </div>
            </div>
            </div>
        </div>
        
        <?php endif; ?>
        
        <?php if(!empty($settings_withdrawal_details)):?>
        <div class="row-fluid">
            <div class="span12">
            <h2>{lang_WithdrawalDetails}</h2>
            <div class="property_content">
            <?php echo $settings_withdrawal_details; ?><br />
            {lang_WithdrawalDetailsNotice}
            </div>
            </div>
        </div>
        <?php endif;?>
        
        <?php if(isset($settings_activation_price) && isset($settings_featured_price) &&
                 $settings_activation_price > 0 || $settings_featured_price > 0): ?>
        <div class="row-fluid">
            <div class="span12">
            <div class="property_content">
                <div class="widget-content">
                <?php if($settings_activation_price > 0): ?>
                    <?php echo lang_check('* Property activation price:').' '.$settings_activation_price.' '.$settings_default_currency; ?><br />
                 <?php endif;?>
                 <?php if($settings_featured_price > 0): ?>
                    <?php echo lang_check('* Property featured price:').' '.$settings_featured_price.' '.$settings_default_currency; ?>
                 <?php endif;?>
                 </div>
            </div>
            </div>
        </div>
        <?php endif;?>
        
        <?php if(false):?>
        <br />
        <div class="property_content">
        {page_body}
        </div>
        <?php endif;?>
    </div>
</div>
    
<?php _subtemplate('footers', _ch($subtemplate_footer, 'standard')); ?>

<?php _widget('custom_javascript');?>

  </body>
</html>