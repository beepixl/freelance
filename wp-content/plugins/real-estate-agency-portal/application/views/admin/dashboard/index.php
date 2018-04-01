
    <!-- Page heading -->
    <div class="page-head">
    <!-- Page heading -->
        <h2 class="pull-left"><?php echo lang('Dashboard')?>
		  <!-- page meta -->
		  <span class="page-meta"><?php echo lang('Short, basic informations')?></span>
		</h2>

		<!-- Breadcrumb -->
		<div class="bread-crumb pull-right">
		  <a href="#"><i class="icon-home"></i> <?php _l('Dashboard')?></a> 
		</div>

		<div class="clearfix"></div>

    </div>
    <!-- Page heading ends -->

    <!-- Matter -->

    <div class="matter">
    <div class="container">
    

    
        <div class="row">
            <div class="col-md-12"> 
                <?php if(check_acl('page') && config_db_item('frontend_disabled') === FALSE):?><?php echo anchor('admin/page/edit', '<i class="icon-sitemap"></i>&nbsp;&nbsp;'.lang('Add a page'), 'class="btn btn-success"')?><?php endif;?>
                <?php echo anchor('admin/estate/edit', '<i class="icon-map-marker"></i>&nbsp;&nbsp;'.lang('Add a estate'), 'class="btn btn-info"')?>
            </div>
        </div>
          <div class="row">
            <div class="col-md-12">
              <div class="widget worange">
                <div class="widget-head">
                  <div class="pull-left"><?php _l('View last added properties')?></div>
                  <div class="widget-icons pull-right">
                    <a class="wminimize" href="#"><i class="icon-chevron-up"></i></a> 
                  </div>
                  <div class="clearfix"></div>
                </div>

                <div class="widget-content">
                  <div class="gmap" id="mapsProperties">

                  </div>
                </div>
              </div> 
            </div>
            </div>
            <div class="row">
            <?php if(check_acl('page') && config_db_item('frontend_disabled') === FALSE):?>
            <div class="col-md-6">
                <div class="widget wgreen">

                <div class="widget-head">
                  <div class="pull-left"><?php echo lang('Pages')?></div>
                  <div class="widget-icons pull-right">
                    <a class="wminimize" href="#"><i class="icon-chevron-up"></i></a> 
                  </div>
                  <div class="clearfix"></div>
                </div>

                  <div class="widget-content">
                    <!-- Nested Sortable -->
                    <div id="orderResult">
                    <?php echo get_ol_pages($pages_nested)?>
                    </div>
                  </div>
                </div>
            </div>
            <?php endif;?>

            <div class="col-md-6">

                <div class="widget wlightblue">

                <div class="widget-head">
                  <div class="pull-left"><?php echo lang('Last added estates')?></div>
                  <div class="widget-icons pull-right">
                    <a class="wminimize" href="#"><i class="icon-chevron-up"></i></a> 
                  </div>
                  <div class="clearfix"></div>
                </div>

                  <div class="widget-content">

                    <table class="table table-bordered footable">
                      <thead>
                        <tr>
                        	<th>#</th>
                            <th><?php echo lang('Address');?></th>
                            <!-- Dynamic generated -->
                            <?php foreach($this->option_m->get_visible($content_language_id) as $row):?>
                            <th data-hide="phone,tablet"><?php echo $row->option?></th>
                            <?php endforeach;?>
                            <!-- End dynamic generated -->
                        	<th class="control"><?php echo lang('Edit');?></th>
                        	<?php if(check_acl('estate/delete')):?><th class="control"><?php echo lang('Delete');?></th><?php endif;?>
                        </tr>
                      </thead>
                      <tbody>
                        <?php if(count($estates)): foreach($estates as $estate):?>
                                    <tr>
                                        <?php if($estate->is_activated == 0):?>
                                        <td><span class="label label-danger"><?php echo $estate->id?></span></td>
                                        <?php else:?>
                                        <td><?php echo $estate->id?></td>
                                        <?php endif;?>
                                        <td><?php echo anchor('admin/estate/edit/'.$estate->id, $estate->address)?>
                                        </td>
                                        <!-- Dynamic generated -->
                                        <?php foreach($this->option_m->get_visible($content_language_id) as $row):?>
                                        <td>
                                        <?php
                                            echo $this->estate_m->get_field_from_listing($estate, $row->option_id);
                                        ?>
                                        </td>
                                        <?php endforeach;?>
                                        <!-- End dynamic generated -->
                                    	<td><?php echo btn_edit('admin/estate/edit/'.$estate->id)?></td>
                                    	<?php if(check_acl('estate/delete')):?><td><?php echo btn_delete('admin/estate/delete/'.$estate->id)?></td><?php endif;?>
                                    </tr>
                        <?php endforeach;?>
                        <?php else:?>
                                    <tr>
                                    	<td colspan="5"><?php echo lang('We could not find any');?></td>
                                    </tr>
                        <?php endif;?>           
                      </tbody>
                    </table>

                  </div>
                </div>
            </div>
            
<?php if(check_acl('page')):?>
            <div class="col-md-6">

                <div class="widget wred">

                <div class="widget-head">
                  <div class="pull-left"><?php _l('Last script news')?></div>
                  <div class="widget-icons pull-right">
                    <a class="wminimize" href="#"><i class="icon-chevron-up"></i></a> 
                  </div>
                  <div class="clearfix"></div>
                </div>

                  <div class="widget-content">

                    <table class="table table-bordered footable">
                      <thead>
                        <tr>
                        	<th data-hide="phone"><?php _l('Date');?></th>
                            <th><?php _l('Title');?></th>
                        </tr>
                      </thead>
                      <tbody id="script_news_table">
                        <tr>
                        	<td colspan="5"><?php _l('Loading in progress');?></td>
                        </tr>      
                      </tbody>
                    </table>
<script type="text/javascript">
$(function () {
    
    $.getJSON("http://ljiljan.com.hr/last_news.php?f=news.json", function( data ) {
      var content = '';
      
      $.each( data, function( key, val ) {
        content+='<tr><td>'+val.date+'</td><td><a href="'+val.link+'" target="_blank">'+val.title+'</a></td></tr>';
      });
        
      $('#script_news_table').html(content);
    });

});
</script>
                  </div>
                </div>
            </div>
<?php endif; ?>
            
          </div>

    </div>
    </div>

	<!-- Matter ends -->

   <!-- Mainbar ends -->	    	
   <div class="clearfix"></div>
   
       <!-- Script for this page -->
    <script type="text/javascript">
    
        $(function () {
            $("#mapsProperties").gmap3({
             map:{
                options:{
                <?php if(config_item('custom_map_center') === FALSE): ?>
                 center: [<?php echo calculateCenter($estates)?>],
                <?php else: ?>
                 center: [<?php echo config_item('custom_map_center'); ?>],
                <?php endif; ?>
                 zoom: 8,
                 scrollwheel: false
                }
             },
             marker:{
                values:[
                <?php if(count($estates_all)): foreach($estates_all as $estate):
                
                    $icon_url = base_url('admin-assets/img/markers/marker_blue.png');
                    $value = $this->estate_m->get_field_from_listing($estate, 6);
                    
                    if(isset($value))
                    {
                        if($value != '' && $value != 'empty')
                        {
                            if(file_exists(FCPATH.'admin-assets/img/markers/'.$value.'.png'))
                            $icon_url = base_url('admin-assets/img/markers/'.$value.'.png');
                        }
                    }
                
                    echo '{latLng:['.$estate->gps.'], options:{ icon: "'.$icon_url.'"}, data:"'.strip_tags($estate->address);
                    foreach($this->option_m->get_visible($content_language_id) as $row):
                        $value = $this->estate_m->get_field_from_listing($estate, $row->option_id);
                        if($row->type == 'DROPDOWN')
                        {
                            echo isset($value)?'<br /><span class=\\"label label-warning\\">'.htmlentities(strip_tags($value), ENT_QUOTES, "UTF-8").'</span>':'';
                        }
                        else
                        {
                            echo isset($value)?'<br />'.htmlentities(strip_tags($value), ENT_QUOTES, "UTF-8"):'';
                        }
                    endforeach;
                    echo '<br /><a style=\\"font-weight:bold;\\" href=\\"'.site_url('admin/estate/edit/'.$estate->id).'\\">'.lang('Edit').'</a>"},';
                endforeach;
                endif;?> 
                ],
                
            options:{
              draggable: false
            },
            events:{
              mouseover: function(marker, event, context){
                var map = $(this).gmap3("get"),
                  infowindow = $(this).gmap3({get:{name:"infowindow"}});
                if (infowindow){
                  infowindow.open(map, marker);
                  infowindow.setContent(context.data);
                } else {
                  $(this).gmap3({
                    infowindow:{
                      anchor:marker,
                      options:{content: context.data}
                    }
                  });
                }
              },
              mouseout: function(){
                var infowindow = $(this).gmap3({get:{name:"infowindow"}});
                if (infowindow){
                  //infowindow.close();
                }
              }
            }
             }
            });
        });
    
    </script>