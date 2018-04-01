<div class="wrap-map" id="wrap-map-1">
    <div id="myCarousel" class="carousel slide">
    <?php if(false): ?>
    <ol class="carousel-indicators">
    {slideshow_images}
    <li data-target="#myCarousel" data-slide-to="{num}" class="{first_active}"></li>
    {/slideshow_images}
    </ol>
    <?php endif; ?>
    
    <div class="container">
    <ol class="carousel-thumbs carousel-indicators">
    {slideshow_images}
        <li data-target="#myCarousel" data-slide-to="{num}" class="{first_active}"><a href="#"><img src="{url}" /></a></li>
    {/slideshow_images}
    </ol>
    </div>
    
    <!-- Carousel items -->
    <div class="carousel-inner">
    <?php foreach ($slideshow_images as $key => $item):?>
        <div class="item <?php _che($item['first_active']);?>">
        <div class="cont">
            <img alt="" src="<?php _che($item['url']);?>" />
            <?php if(!empty($item['title'])): ?>
            <div class="slider-info">
                <div class='container'>
                <?php if(!empty($item['link'])): ?>
                    <a href='<?php _che($item['link']);?>'><?php _che($item['title']);?></a>
                <?php  else:?>
                    <span><?php _che($item['title']);?></span>
                <?php  endif;?>
                </div>
            </div>
            <?php  endif;?>
        </div>
        </div>
    <?php endforeach;?>
    </div>
    <!-- Carousel nav -->
    <a class="carousel-control left" href="#myCarousel" data-slide="prev">&lsaquo;</a>
    <a class="carousel-control right" href="#myCarousel" data-slide="next">&rsaquo;</a>
    </div>
</div>

{template_search-filter}