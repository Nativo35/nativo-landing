<?php
/**
 * Problemi del cliente: quattro segnali in cui riconoscersi, senza statistiche non verificabili.
 */

defined( 'ABSPATH' ) || exit;

$nl_problemi = array(
	array(
		'icona'  => 'lento',
		'titolo' => 'È lento ad aprirsi.',
		'testo'  => 'Chi aspetta se ne va. E sceglie un concorrente.',
	),
	array(
		'icona'  => 'smartphone',
		'titolo' => 'Sullo smartphone si vede male.',
		'testo'  => 'Proprio lì dove i tuoi clienti ti cercano di più.',
	),
	array(
		'icona'  => 'ricerca',
		'titolo' => 'Su Google non ti trova nessuno.',
		'testo'  => 'Ti trova solo chi ti conosce già.',
	),
	array(
		'icona'  => 'posta',
		'titolo' => 'Riceve visite, ma nessuno scrive.',
		'testo'  => 'Le persone arrivano e se ne vanno senza contattarti.',
	),
);
?>
<section id="problemi" <?php nl_sezione_attr( 'problemi', 'sezione sezione--alt problemi' ); ?> aria-labelledby="problemi-titolo">
	<div class="contenitore">
		<header class="sezione__intestazione">
			<h2 id="problemi-titolo" class="sezione__titolo">Il tuo sito ti sta facendo <mark>perdere clienti?</mark></h2>
			<p class="sezione__sottotitolo">Se ti riconosci in uno di questi segnali, è il momento di cambiare.</p>
		</header>

		<ul class="problemi__griglia" role="list">
			<?php foreach ( $nl_problemi as $p ) : ?>
				<li class="problema">
					<span class="problema__icona"><?php nl_icona( $p['icona'] ); ?></span>
					<h3 class="problema__titolo"><?php echo esc_html( $p['titolo'] ); ?></h3>
					<p class="problema__testo"><?php echo esc_html( $p['testo'] ); ?></p>
				</li>
			<?php endforeach; ?>
		</ul>

		<!-- DA VERIFICARE: cosa include l'analisi gratuita del sito e in quanto tempo arriva. -->
		<div class="<?php echo esc_attr( trim( 'sezione__cta analisi ' . nl_classi_link( '#modulo' ) ) ); ?>" data-richiesta="analisi">
			<p class="analisi__testo"><strong>Hai già un sito?</strong><br>Lascia l'indirizzo: Matteo lo analizza gratis e ti dice cosa lo frena.</p>
			<?php nl_cta( 'Analizza gratis il mio sito', 'problemi-analisi', '#modulo' ); ?>
		</div>

		<p class="<?php echo esc_attr( trim( 'problemi__nuovo ' . nl_classi_link( '#preventivo' ) ) ); ?>">
			<strong>Non hai ancora un sito?</strong>
			Partiamo da zero, con le idee chiare.
			<a href="#preventivo" data-cta="problemi-nuovo">Richiedi il preventivo gratis</a>
		</p>
	</div>
</section>
