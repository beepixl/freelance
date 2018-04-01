
<style>

.wrap-map
{
    position:relative;
}

.scentered.wrap-search
{
    width:50%;
    top:38%;
    left:60px;
    position:absolute;
}

.scentered.wrap-search .container
{
    width:auto;
}

.scentered.wrap-search .search-form form
{
    padding:0px 10px;    
}

.scentered.wrap-search .search-form form input#search_option_smart.span6
{
    width:40%;
}

.scentered.wrap-search .search-form form .span3
{
    width:30%;
}

.scentered.wrap-search .search-form form button#search-start
{
    margin-top:0px;
}

/* Landscape phone to portrait tablet */
@media (max-width: 1050px) {
    
    .scentered.wrap-search
    {
        width:80%;
    }

}

/* Landscape phones and down */
@media (max-width: 767px) {
    
    .scentered.wrap-search
    {
        width:60%;
    }
    
    .scentered.wrap-search
    {
        top:10px;
    }
    
    .scentered.wrap-search .search-form
    {
        margin-top:0px;
    }
    
    .scentered.wrap-search .search-form form input#search_option_smart.span6
    {
        width:100%;
    }
    
    .scentered.wrap-search .search-form form .span3
    {
        width:100%;
    }
    
}

@media (max-width: 480px) {
   
    .wrap-map, #myCarousel.carousel
    {
        height:400px;
    }

}

.center-search {
    position: relative;
}

.center-search #search-save {
    position: absolute;
    right: 0;
    bottom: -35px;
    border-radius:0 !important;
    color: #fff;
    background-color: #5bc0de;
    border-color: #46b8da;
}

.center-search #search-save:active, .center-search #search-save:hover {
    color: #fff;
    background-color: #31b0d5;
    border-color: #269abc;
     border-radius:0 !important;
}

.center-search #search-save:focus,
.center-search #search-save:active {
    outline-offset: 0 !important;
}

.center-search #search-save {
    display: inline-block;
    margin-bottom: 0;
    font-weight: normal;
    text-align: center;
    vertical-align: middle;
    cursor: pointer;
    background-image: none;
    border: 1px solid transparent;
    white-space: nowrap;
    padding: 6px 12px;
    font-size: 14px;
    line-height: 1.42857;
    border-radius: 4px;
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    -o-user-select: none;
    user-select: none;
}

.ajax-indicator {
    float: right;
    margin-top: -3px;
}
</style>

<script type="text/javascript">

$(document).ready(function()
{
    /* Search to center */
    $('div.wrap-search').css('left', (($(window).width()-$('div.wrap-search').width())/2)+'px');
    
    $( window ).resize(function() {
        $('div.wrap-search').css('left', (($(window).width()-$('div.wrap-search').width())/2)+'px');
    });
    /* End search to center */
});

</script>


<div class="wrap-search scentered">
    <div class="container center-search">

        <ul id="search_option_4" class="menu-onmap tabbed-selector">
            <li class="all-button"><a href="#"><?php echo lang_check('All'); ?></a></li>
            {options_values_li_4}
        </ul>
        
        <div class="search-form">
            <form class="form-inline">
            
                <input id="rectangle_ne" type="text" class="hide" />
                <input id="rectangle_sw" type="text" class="hide" />
            
            
                <input id="search_option_smart" type="text" class="span6" value="{search_query}" placeholder="{lang_CityorCounty}" autocomplete="off" />
                <select id="search_option_2" class="span3 selectpicker" placeholder="{options_name_2}">
                    {options_values_2}
                </select>
                
                <button id="search-start" type="submit" class="btn btn-info btn-large">&nbsp;&nbsp;{lang_Search}&nbsp;&nbsp;</button>
                <img id="ajax-indicator-1" class="ajax-indicator" src="assets/img/ajax-loader.gif"/>
                
                <div class="advanced-form-part hidden">
                <div class="form-row-space"></div>
                <input id="search_option_36_from" type="text" class="span3 mPrice" placeholder="{lang_Fromprice} ({options_prefix_36}{options_suffix_36})" value="<?php echo search_value('36_from'); ?>" />
                <input id="search_option_36_to" type="text" class="span3 xPrice" placeholder="{lang_Toprice} ({options_prefix_36}{options_suffix_36})" value="<?php echo search_value('36_to'); ?>" />
                <input id="search_option_19" type="text" class="span3 Bathrooms" placeholder="{options_name_19}" value="<?php echo search_value(19); ?>" />
                <input id="search_option_20" type="text" class="span3" placeholder="{options_name_20}" value="<?php echo search_value(20); ?>" />
                
                <div class="form-row-space"></div>
                <?php if(file_exists(APPPATH.'controllers/admin/booking.php')):?>
                <input id="booking_date_from" type="text" class="span3 mPrice" placeholder="{lang_Fromdate}" value="<?php echo search_value('date_from'); ?>" />
                <input id="booking_date_to" type="text" class="span3 xPrice" placeholder="{lang_Todate}" value="<?php echo search_value('date_to'); ?>" />
                <div class="form-row-space"></div>
                <?php endif; ?>
                <?php if(config_db_item('search_energy_efficient_enabled') === TRUE): ?>
                <select id="search_option_59_to" class="span3 selectpicker nomargin" placeholder="{options_name_59}">
                    <option value="">{options_name_59}</option>
                    <option value="50">A</option>
                    <option value="90">B</option>
                    <option value="150">C</option>
                    <option value="230">D</option>
                    <option value="330">E</option>
                    <option value="450">F</option>
                    <option value="999999">G</option>
                </select>
                <div class="form-row-space"></div>
                <?php endif; ?>
                
                <div class="form-row-space"></div>
                <label class="checkbox">
                <input id="search_option_11" type="checkbox" class="span1" value="true{options_name_11}" <?php echo search_value('11', 'checked'); ?>/>{options_name_11}
                </label>
                <label class="checkbox">
                <input id="search_option_22" type="checkbox" class="span1" value="true{options_name_22}" <?php echo search_value('22', 'checked'); ?>/>{options_name_22}
                </label>
                <label class="checkbox">
                <input id="search_option_25" type="checkbox" class="span1" value="true{options_name_25}" <?php echo search_value('25', 'checked'); ?>/>{options_name_25}
                </label>
                <label class="checkbox">
                <input id="search_option_27" type="checkbox" class="span1" value="true{options_name_27}" <?php echo search_value('27', 'checked'); ?>/>{options_name_27}
                </label>
                <label class="checkbox">
                <input id="search_option_28" type="checkbox" class="span1" value="true{options_name_28}" <?php echo search_value('28', 'checked'); ?>/>{options_name_28}
                </label>
                <label class="checkbox">
                <input id="search_option_29" type="checkbox" class="span1" value="true{options_name_29}" <?php echo search_value('29', 'checked'); ?>/>{options_name_29}
                </label>
                <label class="checkbox">
                <input id="search_option_32" type="checkbox" class="span1" value="true{options_name_32}" <?php echo search_value('32', 'checked'); ?>/>{options_name_32}
                </label>
                <label class="checkbox">
                <input id="search_option_30" type="checkbox" class="span1" value="true{options_name_30}" <?php echo search_value('30', 'checked'); ?>/>{options_name_30}
                </label>
                <label class="checkbox">
                <input id="search_option_33" type="checkbox" class="span1" value="true{options_name_33}" <?php echo search_value('33', 'checked'); ?>/>{options_name_33}
                </label>
                <label class="checkbox">
                <input id="search_option_23" type="checkbox" class="span1" value="true{options_name_23}" <?php echo search_value('23', 'checked'); ?>/>{options_name_23}
                </label>
                </div>
                <br style="clear:both;" />
                <div id="tags-filters-disabled">
                </div>
            </form>
        </div>
    <?php if(file_exists(APPPATH.'controllers/admin/savesearch.php')): ?>
    <button id="search-save" type="button" class="btn btn-info"><i class="icon-bookmark"></i>{lang_SaveResearch}</button>
    <?php endif; ?>
    </div>
    
</div>



