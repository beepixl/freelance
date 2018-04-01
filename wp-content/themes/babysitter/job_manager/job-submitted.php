<?php
switch ( $job->post_status ) :
	case 'publish' :
		printf( __( '<div class="alert alert-success">Job listed successfully. To view your job listing <a href="%s">click here</a>.</div>', 'babysitter' ), get_permalink( $job->ID ) );
	break;
	case 'pending' :
		printf( __( '<div class="alert alert-success">Job submitted successfully. Your job listing will be visible once approved.</div>', 'babysitter' ), get_permalink( $job->ID ) );
	break;
	default :
		do_action( 'job_manager_job_submitted_content_' . str_replace( '-', '_', sanitize_title( $job->post_status ) ), $job );
	break;
endswitch;