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
    
<input id="pac-input" class="controls" type="text" placeholder="{lang_Search}" />
<div class="wrap-map" id="wrap-map">
</div>
<div class="wrap-search">
    <div class="container">
        <ul id="search_option_4" class="menu-onmap tabbed-selector">
            {options_values_li_4}
            <?php if(config_db_item('property_subm_disabled')==FALSE):  ?>
            <li class="list-property-button"><a href="{myproperties_url}">{lang_Listproperty}</a></li>
            <?php endif;?>
        </ul>
        <div class="search-form">
            <form class="form-inline">
                <input id="search_option_smart" type="text" class="span6" placeholder="{lang_CityorCounty}" />
                <select id="search_option_2" class="span3 selectpicker" placeholder="{options_name_2}">
                    {options_values_2}
                </select>
                <select id="search_option_3" class="span3 selectpicker nomargin" placeholder="{options_name_3}">
                    {options_values_3}
                </select>
                <div class="form-row-space"></div>
                <input id="search_option_36_from" type="text" class="span3 mPrice" placeholder="{lang_Fromprice} ({options_prefix_36}{options_suffix_36})" />
                <input id="search_option_36_to" type="text" class="span3 xPrice" placeholder="{lang_Toprice} ({options_prefix_36}{options_suffix_36})" />
                <input id="search_option_19" type="text" class="span3 Bathrooms" placeholder="{options_name_19}" />
                <input id="search_option_20" type="text" class="span3" placeholder="{options_name_20}" />
                <div class="form-row-space"></div>
                
                <select id="search_category_21" class="span7 selectpicker" title="{options_name_21}" multiple>
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

                <br style="clear:both;" />
                <button id="search-start" type="submit" class="btn btn-info btn-large">&nbsp;&nbsp;{lang_Search}&nbsp;&nbsp;</button>
            </form>
        </div>
    </div>
</div>