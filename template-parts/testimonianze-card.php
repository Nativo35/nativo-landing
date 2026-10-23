<?php
/**
 * Testimonianze, versione A: card a carosello.
 * Scorrimento orizzontale in CSS (scroll-snap); il JavaScript aggiunge frecce, pallini, pausa e avanzamento automatico.
 * Riceve l'elenco già filtrato in $args['testimonianze'] (vedi testimonianze.php).
 */

defined( 'ABSPATH' ) || exit;

$nl_testimonianze = $args['testimonianze'];
?>
<section id="testimonianze" <?php nl_sezione_attr( 'testimonianze', 'sezione testimonianze' ); ?> aria-labelledby="testimonianze-titolo">
	<div class="contenitore">
		<header class="sezione__intestazione">
			<h2 id="testimonianze-titolo" class="sezione__titolo">Cosa dicono <mark>i clienti</mark></h2>
		</header>
	</div>

	<div class="contenitore testimonianze__contenitore">
		<ul class="testimonianze__elenco" id="carosello-testimonianze" role="list" tabindex="0" aria-label="Testimonianze dei clienti: scorri per leggerle tutte">
			<?php foreach ( $nl_testimonianze as $i => $t ) : ?>
				<?php
				$nl_firma = nl_testimonianza_firma( $t );
				?>
				<li class="testimonianze__voce">
					<figure class="testimonianza<?php echo empty( $t['pubblicata'] ) ? ' testimonianza--attesa' : ''; ?>">
						<?php if ( empty( $t['pubblicata'] ) ) : ?>
							<span class="testimonianza__attesa">In attesa di approvazione</span>
						<?php endif; ?>
						<svg class="testimonianza__virgolette" viewBox="0 0 50 38" width="50" height="38" aria-hidden="true" focusable="false"><path d="M1 37V23C1 11 7 3 19 1l2 5c-7 3-10 8-10 13h8v18zm28 0V23c0-12 6-20 18-22l2 5c-7 3-10 8-10 13h8v18z"/></svg>
						<blockquote class="testimonianza__testo">
							<p><?php echo esc_html( $t['testo'] ); ?></p>
						</blockquote>
						<figcaption class="testimonianza__autore">
							<?php echo nl_testimonianza_avatar( $t, $i ); // phpcs:ignore WordPress.Security.EscapeOutput -- HTML generato da WordPress o fisso. ?>
							<span class="testimonianza__chi">
								<strong><?php echo esc_html( $t['nome'] ); ?></strong>
								<?php if ( $nl_firma ) : ?>
									<span><?php echo esc_html( $nl_firma ); ?></span>
								<?php endif; ?>
							</span>
						</figcaption>
					</figure>
				</li>
			<?php endforeach; ?>
		</ul>

		<?php // Comandi del carosello: compaiono solo se il JavaScript è attivo (assets/js/landing.js). ?>
		<div class="carosello__comandi" hidden>
			<button type="button" class="carosello__pulsante" data-carosello="indietro" aria-controls="carosello-testimonianze" aria-label="Testimonianza precedente"><?php nl_icona( 'indietro' ); ?></button>
			<div class="carosello__pallini" role="group" aria-label="Scegli la testimonianza"></div>
			<button type="button" class="carosello__pulsante" data-carosello="avanti" aria-controls="carosello-testimonianze" aria-label="Testimonianza successiva"><?php nl_icona( 'freccia' ); ?></button>
			<button type="button" class="carosello__pulsante carosello__pausa" data-carosello="pausa" aria-controls="carosello-testimonianze" aria-label="Metti in pausa lo scorrimento automatico">
				<span class="carosello__icona-pausa"><?php nl_icona( 'pausa' ); ?></span>
				<span class="carosello__icona-play"><?php nl_icona( 'play' ); ?></span>
			</button>
		</div>
	</div>

	<div class="contenitore">
		<div class="sezione__cta">
			<?php nl_cta( 'Inizia anche tu: preventivo gratis', 'testimonianze' ); ?>
		</div>
	</div>
</section>
