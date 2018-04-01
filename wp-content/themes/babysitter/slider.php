<section class="slider primary__section">

	<?php $type_of_slider = of_get_option('slider_type');

	get_template_part('sliders/'.$type_of_slider);
	
	if($type_of_slider == '') {
		get_template_part('sliders/flexslider');
	}
	?>

</section>