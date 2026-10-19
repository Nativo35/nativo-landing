<?php
/**
 * Referente unico, numeri (da Impostazioni landing) e metodo in 5 passi.
 */

defined( 'ABSPATH' ) || exit;

$nl_admin  = current_user_can( 'manage_options' );
$nl_numeri = array_filter(
	(array) nl_opt( 'numeri' ),
	function ( $n ) use ( $nl_admin ) {
		return '' !== ( $n['etichetta'] ?? '' ) && ( $nl_admin || '' !== ( $n['valore'] ?? '' ) );
	}
);

$nl_passi = array(
	array( 'Chiamata conoscitiva.', 'Gratis e senza impegno: capiamo obiettivi, clienti e concorrenti.' ),
	array( 'Struttura.', 'Pagine e percorso dei visitatori, prima ancora della grafica.' ),
	array( 'Design e sviluppo.', 'Il tuo brand, i tuoi contenuti. Approvi ogni fase.' ),
	array( 'Test.', 'Smartphone, tablet e computer, velocità e SEO tecnica.' ),
	array( 'Lancio e formazione.', 'Il sito va online, impari a gestirlo e noi restiamo a disposizione.' ),
);
?>
<section id="referente" class="sezione sezione--alt referente" aria-labelledby="referente-titolo">
	<div class="contenitore">
		<div class="referente__griglia">
			<div class="referente__foto">
				<?php echo nl_ritratto( 'referente__ritratto', 'nl-ritratto-grande', '(min-width: 64rem) 320px, 220px', 'lazy' ); // phpcs:ignore WordPress.Security.EscapeOutput -- HTML generato da WordPress o fisso. ?>
				<p class="referente__firma"><strong>Matteo Pugi</strong> Fondatore di Nativo Studio</p>
			</div>

			<div class="referente__testo">
				<h2 id="referente-titolo" class="sezione__titolo">Un solo referente. <mark>Dall'inizio alla fine.</mark></h2>
				<p class="sezione__sottotitolo">Parli sempre con chi progetta il tuo sito: Matteo.</p>

				<ul class="spunte referente__punti" role="list">
					<li><?php nl_icona( 'spunta' ); ?><span><strong>Nessun passaggio di mano.</strong> Niente account che cambiano, niente richieste perse.</span></li>
					<li><?php nl_icona( 'spunta' ); ?><span><strong>Una rete quando serve.</strong> Sviluppatori, copywriter e specialisti SEO, coordinati da Matteo.</span></li>
					<li><?php nl_icona( 'spunta' ); ?><span><strong>Esperienza vera.</strong> Anni di comunicazione e progetti digitali a Milano, poi il ritorno in Toscana per fondare Nativo Studio.</span></li>
					<li><?php nl_icona( 'spunta' ); ?><span><strong>Settori diversi.</strong> Vino, food, turismo rurale, servizi professionali.</span></li>
				</ul>
			</div>
		</div>

		<?php if ( $nl_numeri ) : ?>
			<!-- DA VERIFICARE: i valori dei numeri si inseriscono da Impostazioni landing; senza valore non compaiono ai visitatori. -->
			<ul class="numeri" role="list">
				<?php foreach ( $nl_numeri as $n ) : ?>
					<li class="numero">
						<?php if ( '' !== $n['valore'] ) : ?>
							<span class="numero__valore"><?php echo esc_html( $n['valore'] . $n['suffisso'] ); ?></span>
						<?php else : ?>
							<?php echo nl_segnaposto_admin( 'Inserisci il valore' ); // phpcs:ignore WordPress.Security.EscapeOutput -- HTML già sanificato. ?>
						<?php endif; ?>
						<span class="numero__etichetta"><?php echo esc_html( $n['etichetta'] ); ?></span>
					</li>
				<?php endforeach; ?>
			</ul>
		<?php endif; ?>

		<div class="metodo">
			<h3 class="metodo__titolo">Come lavoriamo: <mark>5 passi</mark>, zero sorprese</h3>
			<ol class="metodo__passi" role="list">
				<?php foreach ( $nl_passi as $i => $passo ) : ?>
					<li class="passo">
						<span class="passo__numero" aria-hidden="true"><?php echo esc_html( sprintf( '%02d', $i + 1 ) ); ?></span>
						<h4 class="passo__titolo"><?php echo esc_html( $passo[0] ); ?></h4>
						<p class="passo__testo"><?php echo esc_html( $passo[1] ); ?></p>
					</li>
				<?php endforeach; ?>
			</ol>
		</div>

		<div class="sezione__cta">
			<?php nl_cta( 'Parla con Matteo, è gratis', 'referente' ); ?>
		</div>
	</div>
</section>
