<?php if ( $apply = get_the_job_application_method() ) :
	wp_enqueue_script( 'wp-job-manager-job-application' );
	?>
	<div class="application">
		<input class="application_button button__large" type="button" value="<?php _e( 'Hire Now!', 'babysitter' ); ?>" />

		<div class="application_details">
			<?php
				switch ( $apply->type ) {
					case 'email' :

						echo '<p>' . sprintf( __( 'To hire this person <strong>email your details to</strong> <a class="job_application_email" href="mailto:%1$s%2$s">%1$s</a>', 'babysitter' ), $apply->email, '?subject=' . rawurlencode( $apply->subject ) ) . '</p>';

						echo '<p>' . __( 'Hire using webmail: ', 'babysitter' );

						echo '<a href="' . 'https://mail.google.com/mail/?view=cm&fs=1&to=' . $apply->email . '&su=' . urlencode( $apply->subject ) .'" target="_blank" class="job_application_email">Gmail</a> / ';

						echo '<a href="' . 'http://webmail.aol.com/Mail/ComposeMessage.aspx?to=' . $apply->email . '&subject=' . urlencode( $apply->subject ) .'" target="_blank" class="job_application_email">AOL</a> / ';

						echo '<a href="' . 'http://compose.mail.yahoo.com/?to=' . $apply->email . '&subject=' . urlencode( $apply->subject ) .'" target="_blank" class="job_application_email">Yahoo</a> / ';

						echo '<a href="' . 'http://mail.live.com/mail/EditMessageLight.aspx?n=&to=' . $apply->email . '&subject=' . urlencode( $apply->subject ) .'" target="_blank" class="job_application_email">Outlook</a>';

						echo '</p>';

					break;
					case 'url' :
						echo '<p>' . sprintf( __( 'To hire this person please visit the following URL: <a href="%1$s">%1$s &rarr;</a>', 'babysitter' ), $apply->url ) . '</p>';
					break;
				}
			?>
		</div>
	</div>
<?php endif; ?>