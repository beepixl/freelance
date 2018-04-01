 <script language="javascript">
    $(document).ready(function(){
        
        $('.menu-onmap li a').click(function () {
            var tab_index = $('ul.menu-onmap li').index($(this).parent()[0]);
            
            if(tab_index == 0)
            {
                // fields manipulation for tab 0
                $('#search_option_19').show();
                $('#search_option_20').show();
            }
            else if(tab_index == 1)
            {
                // fields manipulation for tab 1
                $('#search_option_19').show();
                $('#search_option_20').show();
            }
            else if(tab_index == 2)
            {
                // fields manipulation for tab 2
                $('#search_option_19').hide();
                $('#search_option_20').hide();
            }
            
            //Auto search when click on property purpose
            manualSearch(0);
        });
        
        $("#wrap-map").gmap3({
         map:{
            options:{
             <?php if(config_item('custom_map_center') === FALSE): ?>
             center: [{all_estates_center}],
             <?php else: ?>
             center: [<?php echo config_item('custom_map_center'); ?>],
             <?php endif; ?>
             zoom: {settings_zoom},
             scrollwheel: scrollWheelEnabled,
             mapTypeId: c_mapTypeId,
             mapTypeControlOptions: {
               mapTypeIds: c_mapTypeIds
             }
            }
         },
        styledmaptype:{
          id: "style1",
          options:{
            name: "<?php echo lang_check('CustomMap'); ?>"
          },
          styles: mapStyle
        },
         marker:{
            values:[
            {all_estates}
                {latLng:[{gps}], adr:"{address}", options:{icon: "{icon}"}, data:"<img style=\"width: 150px; height: 100px;\" src=\"{thumbnail_url}\" /><br />{address}<br />{option_2}<br /><span class=\"label label-info\">&nbsp;&nbsp;{option_4}&nbsp;&nbsp;</span><br /><a href=\"{url}\">{lang_Details}</a>"},
            {/all_estates}
            ],
            cluster: clusterConfig,
        options:{
          draggable: false
        },
        events:{
          <?php echo map_event(); ?>: function(marker, event, context){
            var map = $(this).gmap3("get"),
              infowindow = $(this).gmap3({get:{name:"infowindow"}});
            if (infowindow){
              infowindow.open(map, marker);
              infowindow.setContent('<div style="width:400px;display:inline;">'+context.data+'</div>');
            } else {
              $(this).gmap3({
                infowindow:{
                  anchor:marker,
                  options:{disableAutoPan: true, content: '<div style="width:400px;display:inline;">'+context.data+'</div>'}
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
        init_gmap_searchbox();
    });    
    </script>
    <style>
        .scale-conteiner .scale-form input {
            font-size: 12px;
        }
        
        
    </style>
    
    
    
<input id="pac-input" class="controls" type="text" placeholder="{lang_Search}" />
<div class="wrap-map" id="wrap-map">
</div>
<div class="wrap-search">
    <div class="container">
        <ul id="search_option_4" class="menu-onmap tabbed-selector">
            {options_values_li_4}
            <li class="list-property-button"><a href="{myproperties_url}">{lang_Listproperty}</a></li>
        </ul>
        <div class="search-form">
            <form class="form-inline">
                <input id="search_option_smart" type="text" class="span6" placeholder="{lang_CityorCounty}" />
                <select id="search_option_2" class="span3 selectpicker" placeholder="{options_name_2}">
                    {options_values_2}
                </select>
                <select id="search_category_21" class="span3 selectpicker custom-select nomargin" title="{options_name_21}" multiple style="margin-top:5px;">
                    <option value="true{options_name_11}">{options_name_11}</option>
                    <option value="true{options_name_22}">{options_name_22}</option>
                    <option value="true{options_name_25}">{options_name_25}</option>
                    <option value="true{options_name_27}">{options_name_27}</option>
                    <option value="true{options_name_28}">{options_name_28}</option>
                    <option value="true{options_name_29}">{options_name_29}</option>
                    <option value="true{options_name_32}">{options_name_32}</option>
                    <option value="true{options_name_30}">{options_name_30}</option>
                    <option value="true{options_name_33}">{options_name_33}</option>
                </select>
                <div class="form-row-space"></div>
                <div class='custom-scale custom-scale-conteiner span6' style="margin:0;">
                <div class="span4 scale-conteiner scale-price custom-span4" id="scale-price" style="">
                    <div class='row-fluid'>
                        <span>{options_name_36} ({options_prefix_36}{options_suffix_36})</span>
                    </div>
                    <div class='row-fluid scale-form'>
                        <input id="search_option_36_from" type="text" class="span5 minScale mPrice scale-input" data-suffix='{options_suffix_36}' data-prefix='{options_prefix_36}'  value="<?php echo search_value('36_from'); ?>"  placeholder="{lang_Fromprice} ({options_prefix_36}{options_suffix_36})"/>
                        <img src="assets/img/glyphicons/glyphicons-434-minus.png" class="scale-icon-minus"/>
                        <input id="search_option_36_to" type="text" class="span5 maxScale xPrice scale-input" data-suffix='{options_suffix_36}' data-prefix='{options_prefix_36}' value="<?php echo search_value('36_to'); ?>"  placeholder="{lang_Toprice} ({options_prefix_36}{options_suffix_36})"/>
                    </div>
                    <div class='row-fluid'>
                        <div class="span12 scale scale-price" id="slide-scale"></div>
                    </div>
                </div>
                </div>
                <select id="search_option_19_from" class="span3 selectpicker  " placeholder="{options_name_19}">
                        <option value="">{options_name_19}</option>
                        <option value="1">1+</option>
                        <option value="2">2+</option>
                        <option value="3">3+</option>
                        <option value="4">4+</option>
                        <option value="5">5+</option>
                        <option value="6">6+</option>
                </select>
                <select id="search_option_20_from" class="span3 selectpicker  " placeholder="{options_name_20}">
                        <option value="">{options_name_20}</option>
                        <option value="1">1+</option>
                        <option value="2">2+</option>
                        <option value="3">3+</option>
                        <option value="4">4+</option>
                        <option value="5">5+</option>
                        <option value="6">6+</option>
                </select>

                <br style="clear:both;" />
                <button id="search-start" type="submit" class="btn btn-info btn-large">&nbsp;&nbsp;{lang_Search}&nbsp;&nbsp;</button>
            </form>
        </div>
    </div>
</div>



<script>
    
   $('document').ready(function(){
    
    scaleFilter('#scale-price',  0, 1000001, <?php echo (!empty(search_value('36_from')))? search_value('36_from') : '0'; ?>, <?php echo (!empty(search_value('36_to')))? search_value('36_to') : '1000001'; ?>,'{options_prefix_36}{options_suffix_36} ','',1)
    
    
<?php if(empty(search_value('36_from')) and empty(search_value('36_to'))): ?> 
    //    $('.scale-price .minScale, .scale-price .maxScale').val('');
<?php endif;?>  
     
})
   
   
   
/*
 * ScaleFilter add scale
 * @param {string} object's selector
 * @param {int} min value
 * @param {int} max value
 * @param {int} minStart value
 * @param {int} maxStart value
 * @param {str} prefix value
 * @param {str} suffix value
 * @param {int} positionInfin '+' after or before  = 1 before
 * 
 */
    function scaleFilter(object, min, max,minStart, maxStart, prefix, suffix, positionInfin){
        var marginSlider = document.querySelector(object+ ' #slide-scale');
        noUiSlider.create(marginSlider, {
                start: [ minStart, maxStart ],
                margin: 1,
                step:1,
                connect: true,
                range: {
                        'min': min,
                        'max': max
                }
        });

        marginSlider.noUiSlider.on('update', function ( values, handle ) {
           // var prefix='R5';
                if ( handle ) {
                         var value=parseInt(values[handle]);
                         var infinity1='';
                         var infinity2='';
                         if(value==max) {
                             if(positionInfin==1) {
                                 infinity1='+'; 
                             } else {
                                  infinity2='+'; 
                             }
                            value--;
                         }
                         $(object+' .maxScale').val(prefix+$.number(value)+infinity1+suffix+infinity2);
                } else {
                         $(object+' .minScale').val(prefix+$.number(parseInt(values[handle]))+suffix);
                }
                if(values[0]!='' && values[1]!=''){
                if( $(object+' .mPrice').val()=='' ||  $(object+' .maxScale').val()=='' ||
                    $('.mPrice').val()=='' ||  $('.xPrice').val()=='') {
                        var value=parseInt(values[1]);
                         var infinity1='';
                         var infinity2='';
                         if(value==max) {
                             if(positionInfin==1) {
                                 infinity1='+'; 
                             } else {
                                  infinity2='+'; 
                             }
                            value--;
                         }
                    $(object+' .maxScale').val(prefix+$.number(value)+infinity1+suffix+infinity2);
                    $(object+' .minScale').val(prefix+$.number(parseInt(values[0]))+suffix);
                    
                }
            }
        });

         
      //  $(object+' .minScale').val('');
      //  $(object+' .maxScale').val('');
      
        $(object+' .maxScale,'+object+' .minScale').change(function(){
            $(document).click(function(){
                var minNew='';
                minNew=parseInt($.number($(object+' .minScale').val()).replace(/,/g, ""));
                var maxNew='';
                maxNew=parseInt($.number($(object+' .maxScale').val()).replace(/,/g, ""));
                var max_t=max-1;
                // console.log(maxNew)
                // filt 1
                if(maxNew =='' || maxNew>max_t) var maxNew=max_t;
                if(minNew =='' || minNew<min) var minNew=min;
                if(maxNew<min) var maxNew=min;
                if(minNew>max_t) var minNew=max_t;

                marginSlider.noUiSlider.set( [minNew,maxNew] );
                $(document).unbind('click');
                return false;
            })
         }); 
    }   
    
</script>