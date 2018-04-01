<!-- begin job content -->
<div class="single-job_listing-content">

	<?php global $job_manager; ?>

	<?php
	$rate = get_post_custom_values("_company_rate");
	$gender = get_post_custom_values("_company_gender");
	$age = get_post_custom_values("_company_age");
	$experience = get_post_custom_values("_company_exp");
	$smokes = get_post_custom_values("_company_smokes");
	$education = get_post_custom_values("_company_edu");
	$languages = get_post_custom_values("_company_lang");

	$info_fat = get_post_custom_values("_company_info_fat");
	$info_cpr = get_post_custom_values("_company_info_cpr");
	$info_trust = get_post_custom_values("_company_info_trust");
	$info_doula = get_post_custom_values("_company_info_doula");
	$info_trans = get_post_custom_values("_company_info_trans");
	$info_travel = get_post_custom_values("_company_info_travel");
	$info_care = get_post_custom_values("_company_info_care");
	$info_pets = get_post_custom_values("_company_info_pets");
	$info_nurse = get_post_custom_values("_company_info_nurse");
	$info_live = get_post_custom_values("_company_info_live");

	$currency = of_get_option('job_currency');

	if($currency == "eur") {
		$currency = "<i class='icon-eur'></i>";
	}elseif($currency =="gbp") {
		$currency = "<i class='icon-gbp'></i>";
	}elseif($currency =="inr") {
		$currency = "<i class='icon-inr'></i>";
	}elseif($currency =="jpy") {
		$currency = "<i class='icon-jpy'></i>";
	}elseif($currency =="cny") {
		$currency = "<i class='icon-cny'></i>";
	}elseif($currency =="krw") {
		$currency = "<i class='icon-krw'></i>";
	}elseif($currency =="btc") {
		$currency = "<i class='icon-btc'></i>";
	}else{
		$currency = "<i class='icon-usd'></i>";
	}
	?>

	<div class="single_job_listing">
		<?php if ( $post->post_status == 'expired' ) : ?>

			<div class="job-manager-info"><?php _e( 'This job listing has expired', 'babysitter' ); ?></div>

		<?php else : ?>

			<ul class="meta">
				<li class="job-type <?php echo get_the_job_type() ? sanitize_title( get_the_job_type()->slug ) : ''; ?>"><?php the_job_type(); ?></li>

				<li class="date-posted" itemprop="datePosted"><?php _e( 'Posted', 'babysitter' ); ?> <date><?php echo human_time_diff( get_post_time( 'U' ), current_time( 'timestamp' ) ) . ' ' . __( 'ago', 'babysitter' ); ?></date></li>

				<?php if ( is_position_filled() ) : ?>
					<li class="position-filled"><?php _e( 'This position has been filled', 'babysitter' ); ?></li>
				<?php endif; ?>
			</ul>

			<div class="clearfix">
				<div class="grid_4 alpha job-photo">
					<figure class="thumb alignnone">
						<?php the_company_logo(); ?>
					</figure>
				</div>
				<div class="grid_8 omega portfolio-info">
					
					<div class="box">
						
						<div class="clearfix">
							<div class="one_half">
								<ul class="info-list">
									<?php if($rate[0]) { ?>
									<li>
										<span class="name"><?php _e('Hourly Rate', 'babysitter'); ?></span>
										<span class="value"><?php echo $currency; ?> <?php echo $rate[0]; ?></span>
									</li>
									<?php } ?>
									<li>
										<span class="name"><?php _e('Location', 'babysitter'); ?></span>
										<span class="value"><?php the_job_location(); ?></span>
									</li>
									<?php if($gender[0]) { ?>
									<li>
										<span class="name"><?php _e('Gender', 'babysitter'); ?></span>
										<span class="value">
										<?php if($gender[0] == 'female' || $gender[0] == 'Female') { ?>
											<?php _e('Female', 'babysitter'); ?>
										<?php } else { ?>
											<?php _e('Male', 'babysitter'); ?>
										<?php } ?>
										</span>
									</li>
									<?php } ?>
									<?php if($age[0]) { ?>
									<li>
										<span class="name"><?php _e('Age', 'babysitter'); ?></span>
										<span class="value"><?php echo $age[0]; ?></span>
									</li>
									<?php } ?>
									<?php if($experience[0]) { ?>
									<li>
										<span class="name"><?php _e('Experience', 'babysitter'); ?></span>
										<span class="value"><?php echo $experience[0]; ?></span>
									</li>
									<?php } ?>
									<li>
										<span class="name"><?php _e('Smokes', 'babysitter'); ?></span>
										<span class="value">
										<?php if($smokes[0] == 'yes') { ?>
											<?php _e('Yes', 'babysitter'); ?>
										<?php } else { ?>
											<?php _e('No', 'babysitter'); ?>
										<?php } ?>
										</span>
									</li>
									<?php if($education[0]) { ?>
									<li>
										<span class="name"><?php _e('Education', 'babysitter'); ?></span>
										<span class="value"><?php echo $education[0]; ?></span>
									</li>
									<?php } ?>
									<?php if($languages[0]) { ?>
									<li>
										<span class="name"><?php _e('Languages', 'babysitter'); ?></span>
										<span class="value"><?php echo $languages[0]; ?></span>
									</li>
									<?php } ?>
								</ul>
							</div>
							<div class="one_half last">
								<div class="info-list info-list__checked list list__unstyled">
									<ul>
										<li>
											<?php if($info_fat[0] == "yes") { ?>
												<i class="icon-ok"></i>
											<?php } else { ?>
												<i class="icon-remove"></i>
											<?php } ?>
											<?php _e('First Aid Training', 'babysitter'); ?>
										</li>

										<li>
											<?php if($info_cpr[0] == "yes") { ?>
												<i class="icon-ok"></i>
											<?php } else { ?>
												<i class="icon-remove"></i>
											<?php } ?>
											<?php _e('CPR Certified', 'babysitter'); ?>
										</li>

										<li>
											<?php if($info_trust[0] == "yes") { ?>
												<i class="icon-ok"></i>
											<?php } else { ?>
												<i class="icon-remove"></i>
											<?php } ?>
											<?php _e('TrustLine Certified', 'babysitter'); ?>
										</li>

										<li>
											<?php if($info_doula[0] == "yes") { ?>
												<i class="icon-ok"></i>
											<?php } else { ?>
												<i class="icon-remove"></i>
											<?php } ?>
											<?php _e('Doula', 'babysitter'); ?>
										</li>

										<li>
											<?php if($info_trans[0] == "yes") { ?>
												<i class="icon-ok"></i>
											<?php } else { ?>
												<i class="icon-remove"></i>
											<?php } ?>
											<?php _e('Has Transportation', 'babysitter'); ?>
										</li>

										<li>
											<?php if($info_travel[0] == "yes") { ?>
												<i class="icon-ok"></i>
											<?php } else { ?>
												<i class="icon-remove"></i>
											<?php } ?>
											<?php _e('Will travel 50 mi', 'babysitter'); ?>
										</li>

										<li>
											<?php if($info_care[0] == "yes") { ?>
												<i class="icon-ok"></i>
											<?php } else { ?>
												<i class="icon-remove"></i>
											<?php } ?>
											<?php _e('Special Needs Care', 'babysitter'); ?>
										</li>

										<li>
											<?php if($info_pets[0] == "yes") { ?>
												<i class="icon-ok"></i>
											<?php } else { ?>
												<i class="icon-remove"></i>
											<?php } ?>
											<?php _e('Comfortable with pets', 'babysitter'); ?>
										</li>

										<li>
											<?php if($info_nurse[0] == "yes") { ?>
												<i class="icon-ok"></i>
											<?php } else { ?>
												<i class="icon-remove"></i>
											<?php } ?>
											<?php _e('Certified Nursing Assistant', 'babysitter'); ?>
										</li>

										<li>
											<?php if($info_live[0] == "yes") { ?>
												<i class="icon-ok"></i>
											<?php } else { ?>
												<i class="icon-remove"></i>
											<?php } ?>
											<?php _e('Live-in nanny', 'babysitter'); ?>
										</li>
									</ul>
								</div>
							</div>
						</div>

						<div class="hr hr__smallest"></div>

						<div class="company">
							<p class="name">
								<a class="website" href="<?php echo get_the_company_website(); ?>" itemprop="url"><?php _e( 'Website', 'babysitter' ); ?></a>
								<?php the_company_twitter(); ?>
							</p>
							<?php the_company_tagline( '<p class="tagline">', '</p>' ); ?>
						</div>
					</div>

					<div class="spacer spacer__small"></div>
					
					<!-- Description -->
					<?php echo apply_filters( 'the_job_description', get_the_content() ); ?>
					<!-- Description / End -->

					<div class="hr hr__small"></div>

					<!-- Apply for a Job Button -->
					<?php if ( ! is_position_filled() && $post->post_status !== 'preview' ) get_job_manager_template( 'job-application.php' ); ?>
					<!-- Apply for a Job Button / End -->
				</div>
			</div>

		<?php endif; ?>
	</div>
	
</div>
<!-- end job content -->