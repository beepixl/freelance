<div class="page-head">
    <!-- Page heading -->
      <h2 class="pull-left"><?php echo lang('Estates')?>
      <!-- page meta -->
      <span class="page-meta"><?php echo lang('View all estates')?></span>
    </h2>
    
    
    <!-- Breadcrumb -->
    <div class="bread-crumb pull-right">
      <a href="<?php echo site_url('admin')?>"><i class="icon-home"></i> <?php echo lang('Home')?></a> 
      <!-- Divider -->
      <span class="divider">/</span> 
      <a class="bread-current" href="<?php echo site_url('admin/estate')?>"><?php echo lang('Estates')?></a>
    </div>
    
    <div class="clearfix"></div>
</div>

<div class="matter">
        <div class="container">
        
        <div class="row">
            <div class="col-md-12"> 
                <?php echo anchor('admin/estate/edit', '<i class="icon-plus"></i>&nbsp;&nbsp;'.lang('Add a estate'), 'class="btn btn-primary"')?>
                <?php if(config_db_item('csv_import_enabled') == TRUE): ?>
                <?php endif;?>
                <?php if(config_db_item('csv_import_enabled') == TRUE && ($this->session->userdata('type') == 'ADMIN' || $this->session->userdata('type') == 'AGENT_ADMIN')): ?>
                    <?php echo anchor('admin/estate/export_csv/', '<i class="icon-arrow-up"></i>&nbsp;&nbsp;'.lang_check('Export CSV'), 'class="btn btn-success pull-right" style=""')?>
                    <?php echo anchor('admin/estate/import_csv/', '<i class="icon-arrow-down"></i>&nbsp;&nbsp;'.lang_check('Import from CSV'), 'class="btn btn-success pull-right"  style="margin-right:10px;"')?>
                <?php endif;?>
            </div>
        </div>

          <div class="row">

            <div class="col-md-12">

                <div class="widget wlightblue">

                <div class="widget-head">
                  <div class="pull-left"><?php echo lang('Estates')?></div>
                  <div class="widget-icons pull-right">
                    <a class="wminimize" href="#"><i class="icon-chevron-up"></i></a> 
                  </div>
                  <div class="clearfix"></div>
                </div>

                  <div class="widget-content">
                  
                    <form class="search-admin form-inline" action="<?php echo site_url($this->uri->uri_string()); ?>" method="GET" autocomplete="off">

                      <div class="form-group">
                        <input class="form-control" name="smart_search" id="smart_search" value="<?php echo set_value_GET('smart_search', '', true); ?>" placeholder="<?php echo lang_check('Smart item search'); ?>" type="text" />
                      </div>
                      <div class="form-group">
                        <?php echo form_dropdown('field_4', $this->option_m->get_field_values($content_language_id, 4, lang_check('Purpose')), set_value_GET('field_4', '', true), 'class="form-control"'); ?>
                      </div>
                      <div class="form-group">
                        <?php echo form_dropdown('field_2', $this->option_m->get_field_values($content_language_id, 2, lang_check('Type')), set_value_GET('field_2', '', true), 'class="form-control"'); ?>
                      </div>
                      <button type="submit" class="btn btn-default"><i class="icon icon-search"></i>&nbsp;&nbsp;<?php echo lang_check('Search'); ?></button>

                    </form>
                  
                    <?php if($this->session->flashdata('error')):?>
                    <p class="label label-important validation"><?php echo $this->session->flashdata('error')?></p>
                    <?php endif;?>
                    
                    <?php echo form_open('admin/estate/delete_multiple', array('class' => '', 'style'=> 'padding:0px;margin:0px;', 'role'=>'form'))?> 
                    <table class="table table-bordered footable">
                      <thead>
                        <tr>
                        	<th>#</th>
                            <th><?php echo lang('Address');?></th>
                            <th data-hide="phone"><?php echo lang('Agent');?></th>
                            <!-- Dynamic generated -->
                            <?php foreach($this->option_m->get_visible($content_language_id) as $row):?>
                            <th data-hide="phone,tablet"><?php echo $row->option?></th>
                            <?php endforeach;?>
                            <!-- End dynamic generated -->
                            <th data-hide="phone"><?php echo lang_check('Views');?></th>
                            <th data-hide="phone"><?php echo lang_check('Preview');?></th>
                        	<th class="control"><?php echo lang('Edit');?></th>

                            <?php if(config_item('status_enabled') === TRUE && 
                                        ($this->session->userdata('type') == 'AGENT_COUNTY_AFFILIATE' || 
                                         $this->session->userdata('type') == 'ADMIN'
                                        )):?>
                            <th class="control"><?php _l('Status');?></th>
                            <?php elseif(check_acl('estate/delete')):?><th class="control"><?php echo lang('Delete');?></th>
                            <?php endif;?>
                            
                            <?php if(check_acl('estate/delete_multiple')):?>
                            <th data-hide="phone" class="control">
                            <button type="submit" onclick="return confirm('<?php _l('Are you sure?'); ?>');" class="btn btn-xs btn-warning"><i class="icon-remove"></i></button>
                            </th>
                            <?php endif;?>
                        </tr>
                      </thead>
                      <tbody>
                        <?php if(count($estates)): foreach($estates as $estate):?>
                                    <tr>
                                    	<td><?php echo $estate->id?></td>
                                        <td>
                                        <?php echo anchor('admin/estate/edit/'.$estate->id, $estate->address)?>
                                        <?php if($estate->is_activated == 0):?>
                                        &nbsp;&nbsp;&nbsp;&nbsp;<span class="label label-danger"><?php echo lang_check('Not Activated')?></span>
                                        <?php endif;?>
                                        <?php if(isset($settings['listing_expiry_days']) && $settings['listing_expiry_days'] > 0 && strtotime($estate->date_modified) <= time()-$settings['listing_expiry_days']*86400): ?>
                                        &nbsp;&nbsp;&nbsp;&nbsp;<span class="label label-warning"><?php echo lang_check('Expired'); ?></span>
                                        <?php endif; ?>
                                        <?php if(!empty($estate->activation_paid_date)):?>
                                        &nbsp;&nbsp;&nbsp;&nbsp;<span class="label label-success"><?php echo lang_check('Paid'); ?></span>
                                        <?php endif; ?>
                                        <?php if(!empty($estate->status)):?>
                                        &nbsp;&nbsp;&nbsp;&nbsp;<span class="label label-info"><?php echo $estate->status; ?></span>
                                        <?php endif; ?>
                                        </td>
                                        <td><?php echo check_set($available_agent[$this->estate_m->get_user_id($estate->id)], '')?></td>
                                        <!-- Dynamic generated -->
                                        <?php foreach($this->option_m->get_visible($content_language_id) as $row):?>
                                        <td>
                                        <?php
                                            echo $this->estate_m->get_field_from_listing($estate, $row->option_id);
                                        ?>
                                        </td>
                                        <?php endforeach;?>
                                        <!-- End dynamic generated -->
                                        <td><?php echo $estate->counter_views; ?></td>
                                        <td><a class="btn btn-info" target="_blank" href="<?php echo site_url((config_item('listing_uri')===false?'property':config_item('listing_uri')).'/'.$estate->id);?>"><i class="icon-search"></i></a></td>
                                    	<td><?php echo btn_edit('admin/estate/edit/'.$estate->id)?></td>
                                    	
                            <?php if(config_item('status_enabled') === TRUE && 
                                        ($this->session->userdata('type') == 'AGENT_COUNTY_AFFILIATE' || 
                                         $this->session->userdata('type') == 'ADMIN'
                                        )):?>
                                     
                            
                                <td>
<?php if(empty($estate->status)): ?>
<div class="btn-group">
<a class="btn btn-warning dropdown-toggle" data-toggle="dropdown" href="#">
<?php echo lang_check('Status'); ?>
<span class="caret"></span>
</a>
<ul class="dropdown-menu">
    <li>
        <a href="<?php echo site_url('admin/estate/status/'.$estate->id.'/APPROVE'); ?>">
        <i class="icon-ok"></i> <?php echo lang_check('Approve'); ?></a>
    </li>
    <li>
        <a href="<?php echo site_url('admin/estate/status/'.$estate->id.'/HOLD'); ?>">
        <i class="icon-pause"></i> <?php echo lang_check('On hold'); ?></a>
    </li>
    <li>
        <a href="<?php echo site_url('admin/estate/status/'.$estate->id.'/DECLINE'); ?>">
        <i class="icon-remove-sign"></i> <?php echo lang_check('Decline'); ?></a>
    </li>
</ul>
<?php elseif($estate->status == 'HOLD'): ?>
<div class="btn-group">
<a class="btn btn-warning dropdown-toggle" data-toggle="dropdown" href="#">
<?php echo lang_check('Status'); ?>
<span class="caret"></span>
</a>
<ul class="dropdown-menu">
    <li>
        <a href="<?php echo site_url('admin/estate/status/'.$estate->id.'/APPROVE'); ?>">
        <i class="icon-ok"></i> <?php echo lang_check('Approve'); ?></a>
    </li>
    <li>
        <a href="<?php echo site_url('admin/estate/status/'.$estate->id.'/CONTRACT'); ?>">
        <i class="icon-briefcase"></i> <?php echo lang_check('Contract property'); ?></a>
    </li>
</ul>
<?php elseif($estate->status == 'HOLD_ADMIN'): ?>
<div class="btn-group">
<a class="btn btn-warning dropdown-toggle" data-toggle="dropdown" href="#">
<?php echo lang_check('Status'); ?>
<span class="caret"></span>
</a>
<ul class="dropdown-menu">
    <li>
        <a href="<?php echo site_url('admin/estate/status/'.$estate->id.'/APPROVE'); ?>">
        <i class="icon-ok"></i> <?php echo lang_check('Approve'); ?></a>
    </li>
    <li>
        <a href="<?php echo site_url('admin/estate/status/'.$estate->id.'/DECLINE'); ?>">
        <i class="icon-remove-sign"></i> <?php echo lang_check('Decline'); ?></a>
    </li>
</ul>
<?php endif;?> 
                                </td>
                             
                            
                            <?php elseif(check_acl('estate/delete')):?><td><?php echo btn_delete('admin/estate/delete/'.$estate->id); ?></td>
                            <?php endif;?>
                                        

                                        
                                        <?php if(check_acl('estate/delete_multiple')):?>
                                            <td>
                                            <?php echo form_checkbox('delete_multiple[]', $estate->id, FALSE); ?>
                                            </td>
                                        <?php endif;?>
                                    
                                    </tr>
                        <?php endforeach;?>
                        <?php else:?>
                                    <tr>
                                    	<td colspan="20"><?php echo lang('We could not find any');?></td>
                                    </tr>
                        <?php endif;?>           
                      </tbody>
                    </table>
                    <?php echo form_close()?>
                    <div style="text-align: center;"><?php echo $pagination; ?></div>

                  </div>
                </div>
            </div>
          </div>
          <?php if(config_db_item('disable_faq_for_agent') == TRUE && $this->session->userdata('type') != 'ADMIN'): ?>
          <div class="row">

            <div class="col-md-12">


              <div class="widget worange">
                
                <div class="widget-head">
                  <div class="pull-left"><?php echo lang_check('Frequently asked questions')?></div>
                  <div class="widget-icons pull-right">
                    <a class="wminimize" href="#"><i class="icon-chevron-up"></i></a> 
                  </div>
                  <div class="clearfix"></div>
                </div>

                  <div class="widget-content" style="padding: 10px;">
                        <div class="list-group">
                            <a href="http://iwinter.com.hr/support/?p=162" target="_blank" class="list-group-item"><?php echo lang_check('How to add / edit or delete the menu tabs on map ? i.e Sale , Rent and both ?')?></a>
                        </div>
                  </div>

              </div>  

            </div>
          </div>
          <?php endif; ?>
        </div>
</div>
    
    
    
    
    
</section>