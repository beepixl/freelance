<?php
// Do not delete these lines
	if (!empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
		die ('Please do not load this page directly. Thanks!');

	if ( post_password_required() ) { ?>
  	<?php echo '<p class="nocomments">' . __('This post is password protected. Enter the password to view comments.', 'babysitter') . '</p>'; ?>
	<?php
		return;
	}
?>

<!-- You can start editing here. -->
<?php if ( have_comments() ) : ?>
	<!-- BEGIN COMMENTS -->
	<div class="comments-wrapper">
		<h2><?php comments_number(); ?></h2>
		
		<!-- BEGIN COMMENTS LIST -->
		<ol class="commentlist">
			<?php wp_list_comments('type=comment&callback=babysittercomments'); ?>
		</ol>
		<!-- END COMMENTS LIST -->

	</div>
	<!-- END COMMENTS -->

 <?php else : // this is displayed if there are no comments so far ?>

	<?php if ( comments_open() ) : ?>
		<!-- If comments are open, but there are no comments. -->
    <?php echo '<p class="nocomments">' . __('No Comments Yet.', 'babysitter') . '</p>'; ?>
	<?php else : // comments are closed ?>
		<!-- If comments are closed. -->
    <?php echo '<p class="nocomments">' . __('Comments are closed.', 'babysitter') . '</p>'; ?>

	<?php endif; ?>
<?php endif; ?>


<!-- Comments Form -->
<?php 
	$comments_args = array(
	// remove "Text or HTML to be displayed after the set of comment fields"
	'comment_notes_after' => ''
	);

	comment_form($comments_args);

?>
<!-- /Comments Form -->