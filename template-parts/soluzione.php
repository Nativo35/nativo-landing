<?php
/**
 * La soluzione: cosa è incluso in ogni sito, in voci brevi orientate al risultato.
 */

defined( 'ABSPATH' ) || exit;

$nl_inclusi = array(
	array( 'Su misura.', 'Niente template: il sito nasce dalla tua attività.' ),
	array( 'Perfetto su smartphone.', 'Dove i tuoi clienti ti cercano più spesso.' ),
	array( 'Pronto per Google.', 'SEO di base incluso, non è un extra.' ),
	array( 'Fatto per farti contattare.', 'Pulsanti e percorsi che portano dritti alla richiesta.' ),
	array( 'Lo aggiorni da solo.', 'WordPress e formazione inclusi: testi e foto li cambi tu.' ),
	array( 'Non resti solo dopo il lancio.', 'Aggiornamenti, correzioni e miglioramenti.' ),
	array( 'Vedi i risultati.', 'Sai quante persone arrivano e quante ti scrivono.' ),
);
?>
<section id="soluzione" class="sezione soluzione" aria-labelledby="soluzione-titolo">
	<div class="contenitore soluzione__griglia">
		<header class="soluzione__intestazione">
			<h2 id="soluzione-titolo" class="sezione__titolo">Un sito costruito per <mark>farti contattare</mark></h2>
			<p class="sezione__sottotitolo">Tutto quello che serve per trasformare le visite in clienti. Già incluso.</p>

			<div class="soluzione__azione">
				<?php if ( nl_prezzo_minimo() ) : ?>
					<p class="prezzo-grande"><small>a partire da</small> <?php echo esc_html( nl_prezzo_minimo() ); ?><?php echo nl_iva_html(); // phpcs:ignore WordPress.Security.EscapeOutput -- HTML già sanificato. ?></p>
				<?php endif; ?>
				<?php nl_cta( 'Scopri quanto costa il tuo sito', 'soluzione', '#pacchetti' ); ?>
			</div>
		</header>

		<ul class="inclusi" role="list">
			<?php foreach ( $nl_inclusi as $voce ) : ?>
				<li class="incluso">
					<span class="incluso__icona"><?php nl_icona( 'spunta' ); ?></span>
					<h3 class="incluso__titolo"><?php echo esc_html( $voce[0] ); ?></h3>
					<p class="incluso__testo"><?php echo esc_html( $voce[1] ); ?></p>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
</section>
