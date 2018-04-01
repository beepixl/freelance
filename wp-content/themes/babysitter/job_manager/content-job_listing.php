<?php
$rate = get_post_custom_values("_company_rate");
$age = get_post_custom_values("_company_age");
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
<li <?php job_listing_class(); ?>>
	<a href="<?php the_job_permalink(); ?>">
		<?php the_company_logo(); ?>
		<div class="position">
			<h3><i class="icon-user"></i> <?php the_title(); ?></h3>
			<div class="company">
				<?php if($age[0]) { ?>
				<?php echo $age[0]; ?>
				<?php } ?>
			</div>
		</div>
		<div class="mobile-holder">
			<div class="hourly-rate">
				<?php if($rate[0]) { ?>
				<?php echo $currency; ?><?php echo $rate[0]; ?>
				<?php } ?>
			</div>
			<div class="location">
				<i class="icon-map-marker"></i> <?php the_job_location( false ); ?>
			</div>
			<ul class="meta">
				<li class="job-type <?php echo get_the_job_type() ? sanitize_title( get_the_job_type()->slug ) : ''; ?>"><?php the_job_type(); ?></li>
				<li class="date"><date><?php echo human_time_diff( get_post_time( 'U' ), current_time( 'timestamp' ) ) . ' ' . __( 'ago', 'babysitter' ); ?></date></li>
			</ul>
		</div>
	</a>
</li>