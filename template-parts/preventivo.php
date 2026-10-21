<?php
/**
 * Form di preventivo (Contact Form 7 "Preventivo landing") su banda scura, con CTA finale.
 * Gli script di CF7 vengono caricati solo qui (vedi inc/form.php).
 */

defined( 'ABSPATH' ) || exit;

$nl_form = function_exists( 'wpcf7_get_contact_form_by_title' ) ? wpcf7_get_contact_form_by_title( 'Preventivo landing' ) : null;
if ( $nl_form && function_exists( 'wpcf7_enqueue_scripts' ) ) {
	wpcf7_enqueue_scripts();
	foreach ( array( 'swv', 'contact-form-7' ) as $nl_script ) {
		wp_script_add_data( $nl_script, 'strategy', 'defer' );
	}
}
?>
<section id="preventivo" class="sezione preventivo" aria-labelledby="preventivo-titolo" data-grazie="<?php echo esc_url( home_url( "/grazie/" ) ); ?>">
	<div class="contenitore preventivo__griglia">
		<div class="preventivo__testo">
			<h2 id="preventivo-titolo" class="sezione__titolo">Ricevi il tuo <mark>preventivo gratis</mark></h2>
			<p class="sezione__sottotitolo">Bastano 30 secondi. Matteo ti risponde entro 24 ore con una prima valutazione.</p>

			<ul class="spunte preventivo__punti" role="list">
				<li><?php nl_icona( 'spunta' ); ?><span><strong>Gratis e senza impegno</strong></span></li>
				<li><?php nl_icona( 'spunta' ); ?><span><strong>Risposta entro 24 ore</strong>, da chi seguirà il tuo progetto</span></li>
				<?php if ( nl_sconto_testo() ) : ?>
					<li><?php nl_icona( 'spunta' ); ?><span><?php echo wp_kses_post( nl_sconto_testo() ); ?></span></li>
				<?php endif; ?>
			</ul>

			<div class="preventivo__alternativa">
				<p><strong>Preferisci parlarne a voce?</strong></p>
				<p>
					Chiama il <a href="<?php echo esc_attr( nl_tel_href() ); ?>" data-evento="clic_telefono"><?php echo esc_html( nl_tel_breve() ); ?></a>
					o scrivi su <a href="<?php echo esc_url( nl_whatsapp_href() ); ?>" data-evento="whatsapp" rel="noopener" target="_blank">WhatsApp</a>.
				</p>
			</div>
		</div>

		<div class="preventivo__modulo" id="modulo">
			<?php if ( $nl_form ) : ?>
				<?php echo $nl_form->form_html( array( 'html_class' => 'modulo' ) ); // phpcs:ignore WordPress.Security.EscapeOutput -- HTML generato da Contact Form 7. ?>
				<p class="cta__rischio"><?php echo esc_html( nl_rischio_testo() ); ?></p>
			<?php else : ?>
				<?php echo nl_segnaposto_admin( 'Manca il form "Preventivo landing" di Contact Form 7' ); // phpcs:ignore WordPress.Security.EscapeOutput -- HTML già sanificato. ?>
			<?php endif; ?>
			<!-- DA VERIFICARE: testo del consenso privacy e pagina dell'informativa (ora /privacy-policy/ è in bozza). -->
			<!-- DA VERIFICARE: telefono obbligatorio o facoltativo. -->
		</div>
	</div>
</section>
