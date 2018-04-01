<?php
add_action('widgets_init', 'latest_load_widgets');

function latest_load_widgets()
{
  register_widget('Posts_Widget');
}

class Posts_Widget extends WP_Widget {
  
  function Posts_Widget()
  {
    $widget_ops = array('classname' => 'latest-posts', 'description' => 'The most recent posts on your site.');

    $control_ops = array('id_base' => 'latest-posts');

    $this->WP_Widget('latest-posts', 'Babysitter - Latest Posts', $widget_ops, $control_ops);
  }
  
  function widget($args, $instance)
  {
    extract($args);
    $title = apply_filters('widget_title', $instance['title']);
    $postscount = $instance['postscount'];
    $btntxt = $instance['btntxt'];
    $btnurl = $instance['btnurl'];

    echo $before_widget;

    if($title) {
      echo $before_title.$title.$after_title;
    }
    ?>

    <ul class="thumbs-list">
      <?php
      $pp = new WP_Query("orderby=date&posts_per_page=".$postscount); ?>
      <?php while ($pp->have_posts()) : $pp->the_post();
      $format = get_post_format();
      ?>
      <li class="list-item clearfix">
        <?php if(has_post_thumbnail()) { ?>
        <!-- begin post image -->
        <figure class="thumb thumb__hovered">
          <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_post_thumbnail('small'); ?></a>
        </figure>
        <!-- end post image -->
        <?php } else { ?>

          <?php if($format=="link") { ?>
            <!-- link placeholder -->
            <figure class="thumb thumb__hovered">
            <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><img src="<?php echo get_template_directory_uri() ?>/images/empty-link.jpg" alt=""></a>
            </figure>
            <!-- link placeholder -->
          <?php } elseif($format=="gallery") { ?>
            <!-- gallery placeholder -->
            <figure class="thumb thumb__hovered">
            <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><img src="<?php echo get_template_directory_uri() ?>/images/empty-gallery.jpg" alt=""></a>
            </figure>
            <!-- gallery placeholder -->
          <?php } elseif($format=="video") { ?>
            <!-- video placeholder -->
            <figure class="thumb thumb__hovered">
            <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><img src="<?php echo get_template_directory_uri() ?>/images/empty-video.jpg" alt=""></a>
            </figure>
            <!-- video placeholder -->
          <?php } elseif($format=="quote") { ?>
            <!-- quote placeholder -->
            <figure class="thumb thumb__hovered">
            <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><img src="<?php echo get_template_directory_uri() ?>/images/empty-quote.jpg" alt=""></a>
            </figure>
            <!-- quote placeholder -->
          <?php } else { ?>
            <!-- standard placeholder -->
            <figure class="thumb thumb__hovered">
            <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><img src="<?php echo get_template_directory_uri() ?>/images/empty-standard.jpg" alt=""></a>
            </figure>
            <!-- standard placeholder -->
          <?php } ?>

        <?php } ?>

        <span class="date"><?php the_time(get_option('date_format')); ?></span>
        <h5 class="item-heading"><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h5>
      </li>
      <?php endwhile; ?>
    </ul>
    
    <?php if($btntxt && $btnurl) { ?>
    <a href="<?php echo $btnurl; ?>" class="button"><?php echo $btntxt; ?></a>
    <?php } ?>
    
    <?php
    echo $after_widget;
  }
  
  function update($new_instance, $old_instance)
  {
    $instance = $old_instance;

    $instance['title'] = strip_tags($new_instance['title']);
    $instance['postscount'] = $new_instance['postscount'];
    $instance['btntxt'] = $new_instance['btntxt'];
    $instance['btnurl'] = $new_instance['btnurl'];
    
    return $instance;
  }

  function form($instance)
  {
    $defaults = array(
      'title' => 'Latest Posts',
      'postscount' => 4,
      'btntxt' => 'View All',
      'btnurl' => '#'
    );
    $instance = wp_parse_args((array) $instance, $defaults); ?>
    
    <p>
      <label for="<?php echo $this->get_field_id('title'); ?>"><?php echo _e('Title:', 'babysitter') ?></label>
      <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $instance['title']; ?>" />
    </p>
    
    </p>
      <label for="<?php echo $this->get_field_id('postscount'); ?>"><?php echo _e('Number of posts:', 'babysitter') ?></label>
      <input class="widefat" id="<?php echo $this->get_field_id('postscount'); ?>" name="<?php echo $this->get_field_name('postscount'); ?>" value="<?php echo $instance['postscount']; ?>" />
    </p>

    </p>
      <label for="<?php echo $this->get_field_id('btntxt'); ?>"><?php echo _e('Button Text:', 'babysitter') ?></label>
      <input class="widefat" id="<?php echo $this->get_field_id('btntxt'); ?>" name="<?php echo $this->get_field_name('btntxt'); ?>" value="<?php echo $instance['btntxt']; ?>" />
    </p>

    </p>
      <label for="<?php echo $this->get_field_id('btnurl'); ?>"><?php echo _e('Button URL:', 'babysitter') ?></label>
      <input class="widefat" id="<?php echo $this->get_field_id('btnurl'); ?>" name="<?php echo $this->get_field_name('btnurl'); ?>" value="<?php echo $instance['btnurl']; ?>" />
    </p>

  <?php
  }
}
?>