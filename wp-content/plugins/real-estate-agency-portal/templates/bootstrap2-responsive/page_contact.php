<!DOCTYPE html>
<html lang="{lang_code}">
  <head>
    <?php _widget('head');?>    
    <script language="javascript">
    $(document).ready(function(){

        $("#contactMap").gmap3({
         map:{
            options:{
             center: [{settings_gps}],
             zoom: 12,
             scrollwheel: scrollWheelEnabled
            }
         },
         marker:{
            values:[
              {latLng:[{settings_gps}], options:{icon: "assets/img/marker_blue.png"}, data:"{settings_address},<br />{lang_GPS}: {settings_gps}"}
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
              infowindow.setContent('<div style="width:400px;display:inline;">'+context.data+'</div>');
            } else {
              $(this).gmap3({
                infowindow:{
                  anchor:marker,
                  options:{disableAutoPan: mapDisableAutoPan, content: '<div style="width:400px;display:inline;">'+context.data+'</div>'}
                }
              });
            }
          },
          mouseout: function(){
            //var infowindow = $(this).gmap3({get:{name:"infowindow"}});
            //if (infowindow){
            //  infowindow.close();
            //}
          }
        }}});
    });
    
    </script>
  </head>

  <body>
  
{template_header}

<?php _subtemplate('headers', _ch($subtemplate_header, 'empty')); ?>

<a name="content" id="content"></a>
<div class="wrap-content">
    <div class="container">
        <h2>{page_title}</h2>
        <div class="property_content">
        {page_body}
        
        {has_settings_gps}
        <h2>{lang_Locationonmap}</h2>
        <div id="contactMap">
        </div>
        {/has_settings_gps}
        
        {has_settings_email}
        <h2 id="form">{lang_Contactform}</h2>
        <div id="contactForm"  class="contact-form">
        {validation_errors}
        {form_sent_message}
        <form method="post" action="{page_current_url}#form">
            
            <!-- The form name must be set so the tags identify it -->
            <input type="hidden" name="form" value="contact" />

                    <div class="row-fluid">
                    <div class="span5">
                        <div class="control-group {form_error_firstname}">
                            <div class="controls">
                                <div class="input-prepend input-block-level">
                                    <span class="add-on"><i class="icon-user"></i></span>
                                    <input class="input-block-level" id="firstname" name="firstname" type="text" placeholder="{lang_FirstLast}" value="{form_value_firstname}" />
                                </div>
                            </div>
                        </div>
                        <div class="control-group {form_error_email}">
                            <div class="controls">
                                <div class="input-prepend input-block-level">
                                    <span class="add-on"><i class="icon-envelope"></i></span>
                                    <input class="input-block-level" id="email" name="email" type="text" placeholder="{lang_Email}" value="{form_value_email}" />
                                </div>
                            </div>
                        </div>
                        <div class="control-group {form_error_phone}">
                            <div class="controls">
                                <div class="input-prepend input-block-level">
                                    <span class="add-on"><i class="icon-phone"></i></span>
                                    <input class="input-block-level" id="phone" name="phone" type="text" placeholder="{lang_Phone}" value="{form_value_phone}" />
                                </div>
                            </div>
                        </div>
                        <?php if(config_item('captcha_disabled') === FALSE): ?>
                        <div class="control-group" >
                            <?php echo $captcha['image']; ?>
                            <input class="captcha" name="captcha" type="text" placeholder="{lang_Captcha}" value="" />
                            <input class="hidden" name="captcha_hash" type="text" value="<?php echo $captcha_hash; ?>" />
                        </div>
                        <?php endif; ?>
                    </div>
                    <div class="span-mini"></div>
                    <div class="span6">
                        <div class="control-group {form_error_message}">
                            <div class="controls">
                                <textarea id="message" name="message" rows="4" class="input-block-level" type="text" placeholder="{lang_Message}">{form_value_message}</textarea>
                            </div>
                        </div>
                        <button class="btn btn-info pull-right" type="submit">{lang_Send}</button>
                    </div>
                    </div>
		</form>
        </div>
        {/has_settings_email}
        
       <?php _widget('center_imagegallery');?>
        
        {has_page_documents}
        <h2>{lang_Filerepository}</h2>
        <ul>
        {page_documents}
        <li>
            <a href="{url}">{filename}</a>
        </li>
        {/page_documents}
        </ul>
        {/has_page_documents}
        </div>
    </div>
</div>
    
<?php _subtemplate('footers', _ch($subtemplate_footer, 'standard')); ?>

<?php _widget('custom_javascript');?> 

  </body>
</html>