<?php
/**
 * Problemi del cliente: quattro segnali in cui riconoscersi, senza statistiche non verificabili.
 */

defined( 'ABSPATH' ) || exit;

$nl_problemi = array(
	array(
		'icona'  => 'lento',
		'titolo' => 'È lento ad aprirsi.',
		'testo'  => 'Chi non aspetta torna su Google e sceglie un altro. E Google tende a premiare i siti veloci.',
	),
	array(
		'icona'  => 'smartphone',
		'titolo' => 'Sullo smartphone si vede male.',
		'testo'  => 'Testi minuscoli, pulsanti difficili da toccare: proprio lì dove molti tuoi clienti ti cercano.',
	),
	array(
		'icona'  => 'ricerca',
		'titolo' => 'Su Google non ti trova nessuno.',
		'testo'  => 'Se compari solo cercando il tuo nome, esisti solo per chi ti conosce già.',
	),
	array(
		'icona'  => 'posta',
		'titolo' => 'Riceve visite, ma nessuno scrive.',
		'testo'  => 'Senza un percorso chiaro verso il contatto, i visitatori se ne vanno senza lasciare traccia.',
	),
);
?>
<section id="problemi" class="sezione sezione--alt problemi" aria-labelledby="problemi-titolo">
	<div class="contenitore">
		<header class="sezione__intestazione">
			<h2 id="problemi-titolo" class="sezione__titolo">Il tuo sito ti sta facendo perdere clienti?</h2>
			<p class="sezione__sottotitolo">Se ti riconosci in almeno uno di questi segnali, è il momento di parlarne.</p>
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
			Partiamo da zero, con le idee chiare su cosa deve fare per te.
		</p>
	</div>
</section>
