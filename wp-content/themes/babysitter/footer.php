				</div>
			</div>
			<!-- Content Wrapper / End -->
			<!-- Footer -->
			<footer id="footer" class="footer" role="contentinfo">
				<?php if(of_get_option('footer_widgets', 'yes') == 'yes'): ?>
				<!-- Footer Widgets -->
				<div class="widgets-footer">
					<div class="container clearfix">
						<div class="grid_3">
							<?php
							if (!function_exists('dynamic_sidebar') || !dynamic_sidebar('footer1')): 
							endif;
							?>
						</div>
						<div class="grid_3">
							<?php
							if (!function_exists('dynamic_sidebar') || !dynamic_sidebar('footer2')): 
							endif;
							?>
						</div>
						<div class="grid_3">
							<?php
							if (!function_exists('dynamic_sidebar') || !dynamic_sidebar('footer3')): 
							endif;
							?>
						</div>
						<div class="grid_3">
							<?php
							if (!function_exists('dynamic_sidebar') || !dynamic_sidebar('footer4')): 
							endif;
							?>
						</div>
					</div>
				</div>
				<!-- /Footer Widgets -->
				<?php endif; ?>

		
				<!-- Copyright -->
				<div class="copyright">
					<div class="container clearfix">
						<div class="grid_12 mobile-nomargin">
							<div class="clearfix">
								<div class="copyright-primary">
									<?php echo of_get_option('copyright', '&copy; 2013 Babysitter Theme | All rights reserved'); ?>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!-- /Copyright -->

			</footer>
			<!-- /Footer -->

		</div>
		<!-- /Main Box -->
		
	</div>
	<!-- /Wrapper-->

	<?php wp_footer(); ?>

	<?php if(of_get_option('ga_code')) { ?>
	<script>
		<?php echo stripslashes(of_get_option('ga_code')); ?>
	</script>
	<!-- Google Analytics Code-->	
	<?php } ?>
	
</body>
</html>