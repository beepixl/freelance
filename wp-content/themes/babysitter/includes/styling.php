<?php // Custom Styling
function custom_styles() {
echo '<style>'; ?>


<?php /* Body background */
$background = of_get_option('bg_body');
echo 'body { background-image:url('.$background['image']. '); background-repeat:'.$background['repeat'].'; background-position:'.$background['position'].';  background-attachment:'.$background['attachment'].'; background-color:'.$background['color'].'}';

/* Content background */
$bg_content = of_get_option('bg_content');
echo '.main-box { background:'.$bg_content.'}';
?>

<?php if(of_get_option('color_accent', '#fc8a58')): ?>
/* Primary Accent Color */
a,
.button__senary,
.list-color__primary ul li:before,
.accordion-wrapper .acc-head,
.accordion-wrapper .acc-head a,
.top-bar a,
.logo h1 strong,
.logo h2 strong,
.page-title h1 span,
.carousel-nav a:hover,
.entry-title a:hover,
.entry-source-link a:hover,
.contact-info li a:hover,
.info-box__nobg .info-box-title,
.thumbs-list .item-heading a,
.cta h2 strong,
.ico-box__primary h3,
.job-manager .job-type,
.job-types .job-type,
.job_listing .job-type {
	color: <?php echo of_get_option('color_accent', '#fc8a58'); ?>;
}
.button,
button,
input[type="submit"],
input[type="reset"],
input[type="button"],
.dropcap,
.dropcap__primary,
.pricing-column__featured h1,
.pricing-column__featured h2,
.pricing-column__featured .pricing-column-footer a,
.single-pricing-table.active .pr-head h4,
.single-pricing-table.active .pr-head h3.price,
.single-pricing-table.active .pr-foot a,
.commentlist li .avatar:hover,
.commentlist li .comment-reply-link,
.info-box__primary,
.info-box__nobg .info-box-num,
.ico-holder,
.ico-box__primary .ico-holder,
.job_summary_shortcode .job-type,
.single_job_listing .meta .job-type {
	background: <?php echo of_get_option('color_accent', '#fc8a58'); ?>;
}

.commentlist li .avatar:hover {
	border-color: <?php echo of_get_option('color_accent', '#fc8a58'); ?>;
}
<?php endif; ?>


<?php if(of_get_option('color_secondary', '#c4d208')): ?>
/* Secondary Color */
blockquote:before,
.list-color__secondary ul li:before,
.link,
.schedule .icon-circle,
.table-legend .icon-circle,
.pricing-column .icon-ok,
.info-box__nobg.info-box__secondary .info-box-title,
.ico-box__tertiary h3,
.info-list__checked .icon-ok {
	color: <?php echo of_get_option('color_secondary', '#c4d208'); ?>;
}
.button__secondary,
.dropcap__secondary,
#wp-calendar thead th,
.info-box__secondary,
.info-box__nobg.info-box__secondary .info-box-num,
.ico-box__tertiary .ico-holder {
	background: <?php echo of_get_option('color_secondary', '#c4d208'); ?>;
}
.format-icon {
	background-color: <?php echo of_get_option('color_secondary', '#c4d208'); ?>;
}
<?php endif; ?>


<?php if(of_get_option('color_tertiary', '#7fdbfd')): ?>
/* Tertiary Color */
.list-color__tertiary ul li:before,
dl dt:before,
.carousel-nav a,
.widget_archive ul li:before,
.widget_nav_menu ul li:before,
.widget_meta ul li:before,
.widget_pages ul li:before,
.widget_recent_comments ul li:before,
.widget_recent_entries ul li:before,
.widget_categories ul li:before,
.twitter_update_list li:before,
.info-box__nobg.info-box__tertiary .info-box-title,
.contact-info li a,
.copyright a,
.thumbs-list .item-heading a:hover,
.ico-box__secondary h3,
.flex-direction-nav a {
	color: <?php echo of_get_option('color_tertiary', '#7fdbfd'); ?>;
}
.button__tertiary,
.dropcap__tertiary,
.inline-form .search-submit,
.tagcloud a,
a.tag,
.info-box__tertiary,
.info-box__nobg.info-box__tertiary .info-box-num,
.ico-box__secondary .ico-holder,
.pagination li a:hover,
.pagination li.current span,
.pagination li.first a:hover,
.pagination li.prev a:hover,
.pagination li.next a:hover,
.pagination li.last a:hover,
.job_summary_shortcode:hover,
.inline-form .search-submit,
.sf-menu > li > a:hover,
.sf-menu > li.sfHover > a,
.sf-menu > li.current-menu-item > a,
.sf-menu > li.current-menu-parent > a,
.sf-menu ul li a,
.flex-next:hover, 
.flex-prev:hover,
.flex-control-paging li a:hover,
.flex-control-paging li a.flex-active {
	background: <?php echo of_get_option('color_tertiary', '#7fdbfd'); ?>;
}
<?php endif; ?>


<?php if(of_get_option('color_quaternary', '#528cba')): ?>
/* Quaternary Color */
.list-color__quaternary ul li:before,
.top-bar a:hover,
.ico-box__quaternary h3
 {
	color: <?php echo of_get_option('color_quaternary', '#528cba'); ?>;
}
.button__quaternary,
.dropcap__quaternary,
.info-box__quaternary,
.ico-box__quaternary .ico-holder {
	background: <?php echo of_get_option('color_quaternary', '#528cba'); ?>;
}
<?php endif; ?>


<?php if(of_get_option('color_quinary', '#f0f7fa')): ?>
/* Quinary Color */
.list-color__quinary ul li:before,
.button__quinary:hover {
	color: <?php echo of_get_option('color_quinary', '#f0f7fa'); ?>;
}
blockquote,
.button:hover,
button:hover,
input[type="submit"]:hover,
input[type="reset"]:hover,
input[type="button"]:hover,
.button__quinary,
table th,
table.stripped tbody tr:nth-child(odd) td,
.accordion-wrapper .acc-head a:before,
.tabs .tab-wrapper,
.tabs .tab-menu a:hover,
.tabs .tab-menu a.active,
.pricing-column h2,
.pricing-column ul li,
.single-pricing-table .pr-head h3.price,
.single-pricing-table .pr-features ul li:nth-child(even),
.hr,
nav.primary,
.post-meta span,
.sticky,
.commentlist li .comment-wrapper,
.commentlist li .comment-reply-link:hover,
.widget__sidebar,
.social-links li a:hover,
.info-box__quinary,
.box,
.cta,
.testi-body,
.pagination li.first a,
.pagination li.prev a,
.pagination li.next a,
.pagination li.last a,
.job_filters,
.gallery-caption {
	background: <?php echo of_get_option('color_quinary', '#f0f7fa'); ?>;
}
.accordion-wrapper .acc-head,
.widgets-footer,
.testi-body:before {
	border-top-color: <?php echo of_get_option('color_quinary', '#f0f7fa'); ?>;
}
hgroup,
.phone-num,
.entry,
.commentlist,
.widgets-footer {
	border-bottom-color: <?php echo of_get_option('color_quinary', '#f0f7fa'); ?>;
}
.commentlist li .comment-wrapper:before {
	border-right-color: <?php echo of_get_option('color_quinary', '#f0f7fa'); ?>;
}
<?php endif; ?>


<?php if(of_get_option('color_sextuple', '#70b3d0')): ?>
blockquote,
.accordion-wrapper .acc-head a:before,
.accordion-wrapper .acc-head.active a,
.post-meta i,
.post-meta a,
.pagination li a,
.pagination li.next a,
.pagination li.last a,
.commentlist li .comment-meta a,
ul.job_listings li.job_listing a div.position h3 i,
ul.job_listings li.no_job_listings_found a div.position h3 i,
ul.job_listings li.job_listing a div.hourly-rate i,
ul.job_listings li.no_job_listings_found a div.hourly-rate i,
ul.job_listings li.job_listing a div.location i,
ul.job_listings li.no_job_listings_found a div.location i
 {
	color: <?php echo of_get_option('color_sextuple', '#70b3d0'); ?>;
}
.accordion-wrapper .acc-head.active a:before,
.tabs .tab-menu a,
.single-pricing-table .pr-head h4,
.single-pricing-table .pr-foot a,
.entry__quote blockquote,
.commentlist li.bypostauthor .comment-wrapper {
	background: <?php echo of_get_option('color_sextuple', '#70b3d0'); ?>;
}
.entry__quote blockquote cite:after {
	border-top-color: <?php echo of_get_option('color_sextuple', '#70b3d0'); ?>;
}
.commentlist li.bypostauthor .comment-wrapper:before {
	border-right-color: <?php echo of_get_option('color_sextuple', '#70b3d0'); ?>;
}
<?php endif; ?>


<?php /* Menu Caret Color */
$menu_caret = of_get_option('typography_menu');
echo '.sf-arrows .sf-with-ul:after { border-top-color:'.$menu_caret['color'].';}';
?>

<?php if(of_get_option('typography_menu_text_hover', '#ffffff')): ?>
/* Menu Text Color */
.sf-menu > li > a:hover,
.sf-menu > li.sfHover > a,
.sf-menu > li.current-menu-item > a,
.sf-menu > li.current-menu-parent > a{
	color: <?php echo of_get_option('typography_menu_text_hover', '#ffffff'); ?>;
}
/* Caret Color on Hover */
.sf-arrows > li.current-menu-item > .sf-with-ul:after,
.sf-arrows > li.current-menu-parent > .sf-with-ul:after,
.sf-arrows > li:hover > .sf-with-ul:after,
.sf-arrows > li.sfHover > .sf-with-ul:after{
	border-top-color: <?php echo of_get_option('typography_menu_text_hover', '#ffffff'); ?>;
}
<?php endif; ?>

<?php if(of_get_option('typography_submenu_text', '#ffffff')): ?>
/* SubMenu Text Color */
.sf-menu ul li a{
	color: <?php echo of_get_option('typography_submenu_text', '#ffffff'); ?>;
}
<?php endif; ?>

<?php if(of_get_option('typography_submenu_text_hover', '#ffffff')): ?>
/* SubMenu Text Color on Hover */
.sf-menu ul li a:hover,
.sf-menu ul li.current-menu-item > a,
.sf-menu ul li.sfHover > a{
	color: <?php echo of_get_option('typography_submenu_text_hover', '#ffffff'); ?>;
}
<?php endif; ?>

<?php if(of_get_option('typography_submenu_bg_hover', '#70b3d0')): ?>
/* SubMenu Background Color on Hover */
.sf-menu ul li a:hover,
.sf-menu ul li.current-menu-item > a,
.sf-menu ul li.sfHover > a{
	background: <?php echo of_get_option('typography_submenu_bg_hover', '#70b3d0'); ?>;
}
<?php endif; ?>

<?php echo htmlspecialchars_decode(of_get_option('custom_css')); ?>
<?php echo '</style>';
}?>