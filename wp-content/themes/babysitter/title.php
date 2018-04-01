<div id="page-title" class="page-title">
	<div class="container clearfix">
		<div class="grid_12">
			<div class="page-title-holder">
				
				<?php if(is_home()){ ?>
					<h1><?php echo of_get_option('blog_text'); ?></h1>
				<?php } elseif(is_search()) { ?>
					<h1><?php echo sprintf( __( '<span>%s</span> Search Results for ', 'babysitter' ), $wp_query->found_posts ); echo '<span>' . get_search_query() . '</span>'; ?></h1>
				
				<?php } elseif ( is_author() ) { ?>
					<?php 
						global $author;
						$userdata = get_userdata($author);
					?>
						<h1><?php echo $userdata->display_name; ?></h1>
						
				<?php } elseif ( is_404() ) { ?>
					<h1><?php printf( __( 'Page not found', 'babysitter' )); ?></h1>
					
				<?php } elseif ( is_page() ) { ?>
					<h1><?php the_title(); ?></h1>
				
				<?php } elseif ( is_category() ) { ?>
					<h1><?php printf( __( 'Category Archives', 'babysitter' )); ?></h1>
					
				<?php } elseif ( is_tax('portfolio_category') ) { ?>
					<h1><?php $terms_as_text = get_the_term_list( $post->ID, 'portfolio_category', '', ', ', '' ) ;
					echo strip_tags($terms_as_text); ?></h1>
				
				<?php } elseif ( is_day() ) { ?>
					<h1><?php printf( __( 'Daily Archives', 'babysitter' )); ?></h1>
					
				<?php } elseif ( is_month() ) { ?>	
					<h1><?php printf( __( 'Monthly Archives', 'babysitter' )); ?></h1>
					
				<?php } elseif ( is_year() ) { ?>	
					<h1><?php printf( __( 'Yearly Archives', 'babysitter' )); ?></h1>
						
				<?php } elseif ( is_tag() ) { ?>
					<h1><?php printf( __( 'Tag Archives', 'babysitter' )); ?></h1>
				
				<?php } else { ?>

					<?php if(!is_singular('post') && !is_singular('team')) { ?>
					<h1><?php the_title(); ?></h1>
					<?php } ?>
					
				<?php } ?>
				
			</div>
		
		</div>
	</div>
</div>