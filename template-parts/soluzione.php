<?php
/**
 * La soluzione: cosa è incluso in ogni sito, scritto come benefici.
 */

defined( 'ABSPATH' ) || exit;

$nl_inclusi = array(
	array( 'Progettato su misura.', 'Nessun template preconfezionato: struttura e grafica nascono dalla tua attività e dal tuo pubblico.' ),
	array( 'Pensato prima per lo smartphone.', 'Progettiamo da mobile a desktop, perché è lì che ti cercano più spesso.' ),
	array( 'Pronto per Google.', 'L\'ottimizzazione SEO di base è inclusa fin dall\'inizio, non è un extra.' ),
	array( 'Fatto per generare contatti.', 'Form, pulsanti e percorsi pensati per trasformare le visite in richieste.' ),
	array( 'Lo aggiorni da solo.', 'Ti consegniamo il sito su WordPress con una sessione di formazione: testi, immagini e pagine li modifichi tu.' ),
	array( 'Non ti lasciamo dopo il lancio.', 'Restiamo disponibili per aggiornamenti, correzioni e miglioramenti.' ),
	array( 'Misuri i risultati.', 'Statistiche delle visite configurate, così sai quante persone arrivano e quante ti contattano.' ),
);
?>
<section id="soluzione" class="sezione soluzione" aria-labelledby="soluzione-titolo">
	<div class="contenitore soluzione__griglia">
		<header class="sezione__intestazione soluzione__intestazione">
			<h2 id="soluzione-titolo" class="sezione__titolo">Un sito costruito per farti contattare</h2>
			<p class="sezione__sottotitolo">Ogni sito parte da una domanda: cosa deve portare alla tua attività? La risposta guida ogni scelta di struttura, design e tecnologia.</p>

			<div class="soluzione__azione">
				<a class="pulsante" href="#pacchetti" data-cta="soluzione">
					Scopri quanto costa il tuo sito
					<?php nl_icona( 'freccia' ); ?>
				</a>
				<?php if ( nl_prezzo_minimo() ) : ?>
					<p class="soluzione__prezzo">Prezzi a partire da <strong><?php echo esc_html( nl_prezzo_minimo() ); ?></strong></p>
				<?php endif; ?>
			</div>
		</header>

		<div class="soluzione__elenco">
			<p class="soluzione__etichetta">Incluso in ogni sito</p>
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
	</div>
</section>
