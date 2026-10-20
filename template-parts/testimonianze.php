<?php
/**
 * Testimonianze da Impostazioni landing.
 * Visitatori: solo quelle pubblicate; se non ce n'è nessuna la sezione è nascosta.
 * Da loggato: anche quelle non pubblicate, con l'etichetta "In attesa di approvazione".
 * Desktop: griglia. Mobile: scorrimento orizzontale in CSS (scroll-snap), senza librerie.
 */

defined( 'ABSPATH' ) || exit;

$nl_admin         = current_user_can( 'manage_options' );
$nl_testimonianze = array_values(
	array_filter(
		(array) nl_opt( 'testimonianze' ),
		fn( $t ) => $nl_admin || ! empty( $t['pubblicata'] )
	)
);

if ( ! $nl_testimonianze ) {
	if ( $nl_admin ) {
		echo '<div class="contenitore sezione-spenta">' . nl_segnaposto_admin( 'Testimonianze nascoste: aggiungine una e attiva "Pubblicata" in Impostazioni landing' ) . '</div>'; // phpcs:ignore WordPress.Security.EscapeOutput -- HTML già sanificato.
	}
	return;
}
?>
<section id="testimonianze" class="sezione testimonianze" aria-labelledby="testimonianze-titolo">
	<div class="contenitore">
		<header class="sezione__intestazione">
			<h2 id="testimonianze-titolo" class="sezione__titolo">Cosa dicono <mark>i clienti</mark></h2>
		</header>
	</div>

	<div class="contenitore testimonianze__contenitore">
		<ul class="testimonianze__elenco" role="list" tabindex="0" aria-label="Testimonianze dei clienti: scorri per leggerle tutte">
			<?php foreach ( $nl_testimonianze as $i => $t ) : ?>
				<?php
				$nl_firma = implode( ', ', array_filter( array( $t['ruolo'] ?? '', $t['azienda'] ?? '' ) ) );
				$nl_img   = ! empty( $t['immagine'] ) && wp_attachment_is_image( $t['immagine'] ) ? (int) $t['immagine'] : 0;
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
							<?php if ( $nl_img ) : ?>
								<?php echo wp_get_attachment_image( $nl_img, 'nl-ritratto', false, array( 'class' => 'testimonianza__foto', 'alt' => '', 'loading' => 'lazy', 'sizes' => '56px' ) ); ?>
							<?php else : ?>
								<span class="testimonianza__iniziali testimonianza__iniziali--<?php echo (int) ( $i % 3 ); ?>" aria-hidden="true"><?php echo esc_html( nl_iniziali( $t['nome'] ) ); ?></span>
							<?php endif; ?>
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
	</div>

	<div class="contenitore">
		<div class="sezione__cta">
			<?php nl_cta( 'Inizia anche tu: preventivo gratis', 'testimonianze' ); ?>
		</div>
	</div>
</section>
