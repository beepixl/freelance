<?php
	$author =  get_post_meta(get_the_ID(), 'babysitter_author_quote', true);
	$authorpos =  get_post_meta(get_the_ID(), 'babysitter_author_position', true);
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('entry entry__quote'); ?>>

	<!-- begin post heading -->
	<header class="entry-header">
		<div class="format-icon">
			<div class="format-icon-inner">
				<i class="icon-quote-left"></i>
			</div>
		</div>
	</header>
	<!-- end post heading -->
	
	<!-- begin post content -->
	<div class="entry-content">
		<blockquote>
		<?php the_content(''); ?>
		<?php if($author || $authorpos) {
			echo '<cite>';
				if($author) {
					echo '<strong>' . $author . '</strong>';
				}
				if($authorpos) {
					echo ', ' . $authorpos . '';
				}			
			echo '</cite>';
		} ?>
		</blockquote>
	</div>
	<!-- end post content -->
 
</article>