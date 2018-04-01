<div class="page-head">
    <!-- Page heading -->
      <h2 class="pull-left"><?php _l('Dependent field')?>
          <!-- page meta -->
          <span class="page-meta"><?php echo empty($item->id_dependent_field) ? lang_check('Add dependent field') : lang('Edit dependent field').' #' . $item->id_dependent_field.''?></span>
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


              <div class="widget wlightblue">
                
                <div class="widget-head">
                  <div class="pull-left"><?php _l('Dependent field data')?></div>
                  <div class="widget-icons pull-right">
                    <a class="wminimize" href="#"><i class="icon-chevron-up"></i></a> 
                  </div>
                  <div class="clearfix"></div>
                </div>

                <div class="widget-content">
                  <div class="padd">
                    <?php echo validation_errors()?>
                    <?php if($this->session->flashdata('message')):?>
                    <?php echo $this->session->flashdata('message')?>
                    <?php endif;?>
                    <?php if($this->session->flashdata('error')):?>
                    <p class="label label-important validation"><?php echo $this->session->flashdata('error')?></p>
                    <?php endif;?>
                    <hr />
                    <!-- Form starts.  -->
                    <?php echo form_open(NULL, array('class' => 'form-horizontal', 'role'=>'form'))?>                              


                                
                                <div class="form-group">
                                  <label class="col-lg-3 control-label"><?php _l('Dependent field')?></label>
                                  <div class="col-lg-9">
                                  <?php if( empty($item->id_dependent_field) ): ?>
                                    <?php echo form_dropdown('field_id', $available_fields, $this->input->post('field_id') ? $this->input->post('field_id') : $item->field_id, 'class="form-control"')?>
                                  <?php else: ?>
                                    <?php echo form_dropdown('field_id_x', $available_fields, $this->input->post('field_id') ? $this->input->post('field_id') : $item->field_id, 'class="form-control" disabled')?>
                                    <?php echo form_input('field_id', $this->input->post('field_id') ? $this->input->post('field_id') : $item->field_id, 'class="hidden"')?>
                                  <?php endif; ?>
                                  </div>
                                </div>
                                
                                <?php if( empty($item->id_dependent_field) ): ?>
                                <div class="form-group">
                                  <label class="col-lg-3 control-label"></label>
                                  <div class="col-lg-9">
                                    <span class="label label-danger"><?php _l('After saving, you can define other parameters');?></span>
                                  </div>
                                </div>
                                <?php else: ?>

                                <div class="form-group">
                                  <label class="col-lg-3 control-label"><?php _l('Selected index')?></label>
                                  <div class="col-lg-9">
                                    <?php echo form_dropdown('selected_index', $available_indexes, $this->input->post('selected_index') ? $this->input->post('selected_index') : $item->selected_index, 'class="form-control"')?>
                                  </div>
                                </div>
                                
                                <hr />
                                <h5><?php _l('Hidden fields under selected')?></h5>
                                <hr />
                                
                                <?php foreach($fields_under_selected as $key=>$field): ?>
                                
                                <?php if($field->type == 'CATEGORY'): ?>
                                <hr />
                                <?php endif; ?>
                                
                                <div class="form-group">
                                  <label class="col-lg-3 control-label"><?php echo $field->option; ?></label>
                                  <div class="col-lg-9">
                                    <?php 
                                    
                                    $val = $this->input->post('field_'.$field->id);
                                    
                                    if(empty($val))
                                    {
                                        if(isset($item->{'field_'.$field->id}))
                                        $val = $item->{'field_'.$field->id};
                                    }
                                    
                                    echo form_checkbox('field_'.$field->id, '1', $val)?>
                                  </div>
                                </div>
                                
                                <?php if($field->type == 'CATEGORY'): ?>
                                <hr />
                                <?php endif; ?>
                                
                                <?php endforeach; ?>
                                
                                <?php endif; ?>

                                <div class="form-group">
                                  <div class="col-lg-offset-3 col-lg-9">
                                    <?php echo form_submit('submit', lang('Save'), 'class="btn btn-primary"')?>
                                    <a href="<?php echo site_url('admin/estate/dependent_fields')?>" class="btn btn-default" type="button"><?php echo lang('Cancel')?></a>
                                  </div>
                                </div>
                       <?php echo form_close()?>
                  </div>
                </div>
                  <div class="widget-foot">
                    <!-- Footer goes here -->
                  </div>
              </div>  

            </div>

          </div>
        </div>
    </div>

<script language="javascript">

$(document).ready(function() {

});


</script>