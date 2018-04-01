<?php get_header(); ?>

<?php $blog_sidebar = of_get_option('blog_sidebar', 'right'); ?>
  
<!-- Content -->
<div id="content" class="grid_8 <?php echo $blog_sidebar; ?>">

  <?php
    if(isset($_GET['author_name'])) :
      $curauth = get_userdatabylogin($author_name);
      else :
      $curauth = get_userdata(intval($author));
    endif;
  ?>
  <div class="author-info clearfix">
    <h2><?php _e('About:', 'babysitter'); ?> <?php echo $curauth->display_name; ?></h2>
    <figure class="thumb">
      <?php if(function_exists('get_avatar')) { echo get_avatar( $curauth->user_email, $size = '120' ); } /* Displays the Gravatar based on the author's email address. Visit Gravatar.com for info on Gravatars */ ?>
    </figure>
    
    <?php if($curauth->description !="") { /* Displays the author's description from their Wordpress profile */ ?>
      <p><?php echo $curauth->description; ?></p>
    <?php } ?>
  </div><!--.author-->
  <div class="hr"></div>
  <div id="recent-author-posts">
    <h4 class="bordered"><?php _e('Recent Posts by', 'babysitter'); ?> <?php echo $curauth->display_name; ?></h4>
    
    <?php get_template_part('loop'); ?>
  
    <!-- Pagination -->
    <?php babysitter_pagination(); ?>
    <!-- /Pagination -->
    
    
  </div><!--#recentPosts-->

  <div id="recent-author-comments">
    <h4 class="bordered"><?php _e('Recent Comments by', 'babysitter'); ?> <?php echo $curauth->display_name; ?></h4>
      <?php
        $number=5; // number of recent comments to display
        $comments = $wpdb->get_results("SELECT * FROM $wpdb->comments WHERE comment_approved = '1' and comment_author_email='$curauth->user_email' ORDER BY comment_date_gmt DESC LIMIT $number");
      ?>
      <div class="list list-style__check">
        <ul>
          <?php
            if ( $comments ) : foreach ( (array) $comments as $comment) :
            echo  '<li class="recentcomments">' . sprintf(__('%1$s on %2$s', 'babysitter'), get_comment_date(), '<a href="'. get_comment_link($comment->comment_ID) . '">' . get_the_title($comment->comment_post_ID) . '</a>') . '</li>';
          endforeach; else: ?>
                    <p>
                      <?php _e('No comments by', 'babysitter'); ?> <?php echo $curauth->display_name; ?> <?php _e('yet.', 'babysitter'); ?>
                    </p>
          <?php endif; ?>
        </ul>
      </div>
  </div><!--#recentAuthorComments-->

</div>

<?php if($blog_sidebar != 'fullblog') { ?>
<!-- Sidebar -->
<aside id="sidebar" class="grid_4 <?php echo $blog_sidebar; ?>">
  <?php get_sidebar(); ?>
</aside>
<!-- /Sidebar -->
<?php } ?>

<?php get_footer(); ?>