<?php
/**
 * Pagina di ringraziamento dopo l'invio del form: serve a misurare le conversioni delle campagne.
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>
<section class="sezione grazie" aria-labelledby="grazie-titolo">
	<div class="contenitore grazie__contenuto">
		<span class="grazie__icona"><?php nl_icona( 'spunta' ); ?></span>
		<h1 id="grazie-titolo" class="grazie__titolo">Grazie, la tua richiesta <mark>è arrivata!</mark></h1>
		<p class="sezione__sottotitolo">Matteo ti risponderà entro 24 ore all'indirizzo email che hai indicato. Ti abbiamo appena inviato una conferma: se non la trovi, controlla nella posta indesiderata.</p>
		<!-- DA VERIFICARE: tempo di risposta promesso ("entro 24 ore"). -->

		<div class="grazie__azioni">
			<p><strong>Hai fretta?</strong></p>
			<div class="grazie__pulsanti">
				<a class="pulsante" href="<?php echo esc_attr( nl_tel_href() ); ?>" data-evento="clic_telefono"><?php nl_icona( 'telefono' ); ?><span>Chiama il <?php echo esc_html( nl_tel_breve() ); ?></span></a>
				<a class="grazie__link" href="<?php echo esc_url( nl_whatsapp_href() ); ?>" data-evento="clic_whatsapp" rel="noopener" target="_blank">Scrivi su WhatsApp</a>
			</div>
		</div>

		<p class="grazie__torna"><a href="<?php echo esc_url( home_url( '/' ) ); ?>">Torna alla pagina</a></p>
	</div>
</section>
<?php
get_footer();
