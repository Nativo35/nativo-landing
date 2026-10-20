<?php
/**
 * Testimonianze, versione B: citazione in evidenza su banda scura.
 * Una testimonianza grande alla volta; l'elenco delle persone funziona da schede (tab).
 * Senza JavaScript le citazioni compaiono tutte, una sotto l'altra.
 * Il JavaScript (landing.js) attiva le schede, l'avanzamento automatico ogni 8 secondi e la pausa.
 * Riceve l'elenco già filtrato in $args['testimonianze'] (vedi testimonianze.php).
 */

defined( 'ABSPATH' ) || exit;

$nl_testimonianze = $args['testimonianze'];
?>
<section id="testimonianze" class="sezione testimonianze-evidenza" aria-labelledby="testimonianze-titolo">
	<div class="contenitore evidenza__griglia">
		<div class="evidenza__lato">
			<h2 id="testimonianze-titolo" class="sezione__titolo">Cosa dicono <mark>i clienti</mark></h2>

			<div class="evidenza__comandi">
				<div class="evidenza__persone" role="tablist" aria-label="Scegli la testimonianza" hidden>
					<?php foreach ( $nl_testimonianze as $i => $t ) : ?>
						<button type="button" class="evidenza__persona" role="tab" id="testimonianza-scheda-<?php echo (int) $i; ?>" aria-controls="testimonianza-<?php echo (int) $i; ?>" aria-selected="<?php echo 0 === $i ? 'true' : 'false'; ?>" tabindex="<?php echo 0 === $i ? '0' : '-1'; ?>">
							<?php echo nl_testimonianza_avatar( $t, $i, 'evidenza' ); // phpcs:ignore WordPress.Security.EscapeOutput -- HTML generato da WordPress o fisso. ?>
							<span class="evidenza__persona-testo">
								<strong><?php echo esc_html( $t['nome'] ); ?></strong>
								<?php if ( ! empty( $t['azienda'] ) ) : ?>
									<span><?php echo esc_html( $t['azienda'] ); ?></span>
								<?php endif; ?>
							</span>
							<?php if ( empty( $t['pubblicata'] ) ) : ?>
								<span class="evidenza__attesa-punto" title="In attesa di approvazione"></span>
							<?php endif; ?>
							<span class="evidenza__avanzamento" aria-hidden="true"></span>
						</button>
					<?php endforeach; ?>
				</div>

				<button type="button" class="carosello__pulsante carosello__pausa evidenza__pausa" aria-label="Metti in pausa lo scorrimento automatico" hidden>
					<span class="carosello__icona-pausa"><?php nl_icona( 'pausa' ); ?></span>
					<span class="carosello__icona-play"><?php nl_icona( 'play' ); ?></span>
				</button>
			</div>
		</div>

		<div class="evidenza__citazioni">
			<?php foreach ( $nl_testimonianze as $i => $t ) : ?>
				<?php $nl_firma = nl_testimonianza_firma( $t ); ?>
				<figure class="evidenza__citazione" role="tabpanel" id="testimonianza-<?php echo (int) $i; ?>" aria-labelledby="testimonianza-scheda-<?php echo (int) $i; ?>" tabindex="0">
					<?php if ( empty( $t['pubblicata'] ) ) : ?>
						<span class="evidenza__attesa">In attesa di approvazione</span>
					<?php endif; ?>
					<svg class="evidenza__virgolette" viewBox="0 0 50 38" width="50" height="38" aria-hidden="true" focusable="false"><path d="M1 37V23C1 11 7 3 19 1l2 5c-7 3-10 8-10 13h8v18zm28 0V23c0-12 6-20 18-22l2 5c-7 3-10 8-10 13h8v18z"/></svg>
					<blockquote class="evidenza__testo">
						<p><?php echo esc_html( $t['testo'] ); ?></p>
					</blockquote>
					<figcaption class="evidenza__autore">
						<?php echo nl_testimonianza_avatar( $t, $i, 'evidenza' ); // phpcs:ignore WordPress.Security.EscapeOutput -- HTML generato da WordPress o fisso. ?>
						<span>
							<strong><?php echo esc_html( $t['nome'] ); ?></strong>
							<?php if ( $nl_firma ) : ?>
								<span><?php echo esc_html( $nl_firma ); ?></span>
							<?php endif; ?>
						</span>
					</figcaption>
				</figure>
			<?php endforeach; ?>
		</div>
	</div>

	<div class="contenitore">
		<div class="sezione__cta">
			<?php nl_cta( 'Inizia anche tu: preventivo gratis', 'testimonianze' ); ?>
		</div>
	</div>
</section>
