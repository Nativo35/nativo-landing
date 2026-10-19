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
<section id="problemi" class="sezione sezione--alt problemi" aria-labelledby="problemi-titolo">
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

		<p class="problemi__nuovo">
			<strong>Non hai ancora un sito?</strong>
			Partiamo da zero, con le idee chiare.
		</p>

		<div class="sezione__cta">
			<?php nl_cta( 'Voglio un sito che mi porta clienti', 'problemi' ); ?>
		</div>
	</div>
</section>
