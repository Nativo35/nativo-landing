<?php
/**
 * Progetti realizzati, gestiti da Impostazioni landing.
 * Ai visitatori compaiono solo i progetti con immagine; da loggato si vedono tutti, con i segnaposto.
 */

defined( 'ABSPATH' ) || exit;

$nl_admin = current_user_can( 'manage_options' );

$nl_progetti = array_filter(
	(array) nl_opt( 'progetti' ),
	function ( $pr ) use ( $nl_admin ) {
		return $nl_admin || ( ! empty( $pr['immagine'] ) && wp_attachment_is_image( $pr['immagine'] ) );
	}
);

if ( ! $nl_progetti ) {
	return;
}
?>
<section id="progetti" <?php nl_sezione_attr( 'progetti', 'sezione progetti' ); ?> aria-labelledby="progetti-titolo">
	<div class="contenitore">
		<header class="sezione__intestazione">
			<h2 id="progetti-titolo" class="sezione__titolo">Progetti veri, per attività <mark>come la tua</mark></h2>
			<p class="sezione__sottotitolo">Alcuni dei siti che abbiamo progettato e realizzato.</p>
		</header>

		<!-- DA VERIFICARE: quali progetti sono di Nativo Studio e quali del periodo ADP Agency, e l'autorizzazione a mostrarli. Nessun risultato numerico senza dati documentati. -->
		<ul class="progetti__griglia" role="list">
			<?php foreach ( $nl_progetti as $pr ) : ?>
				<li class="progetto">
					<div class="progetto__immagine">
						<?php if ( ! empty( $pr['immagine'] ) && wp_attachment_is_image( $pr['immagine'] ) ) : ?>
							<?php
							echo wp_get_attachment_image(
								$pr['immagine'],
								'nl-progetto-2x',
								false,
								array(
									'alt'     => 'Il sito di ' . $pr['nome'],
									'loading' => 'lazy',
									'sizes'   => '(min-width: 64rem) 380px, (min-width: 40rem) 45vw, 100vw',
								)
							);
							?>
						<?php else : ?>
							<span class="progetto__vuoto" aria-hidden="true"><?php echo esc_html( $pr['nome'] ); ?></span>
						<?php endif; ?>
					</div>
					<div class="progetto__testo">
						<?php if ( ! empty( $pr['tipo'] ) ) : ?>
							<p class="etichetta progetto__tipo"><?php echo esc_html( $pr['tipo'] ); ?></p>
						<?php endif; ?>
						<h3 class="progetto__nome"><?php echo esc_html( $pr['nome'] ); ?></h3>
						<?php if ( ! empty( $pr['contesto'] ) ) : ?>
							<p class="progetto__contesto"><?php echo esc_html( $pr['contesto'] ); ?></p>
						<?php else : ?>
							<?php echo nl_segnaposto_admin( 'Aggiungi contesto e immagine' ); // phpcs:ignore WordPress.Security.EscapeOutput -- HTML già sanificato. ?>
						<?php endif; ?>
					</div>
				</li>
			<?php endforeach; ?>
		</ul>

		<div class="sezione__cta">
			<?php nl_cta( 'Voglio un sito così', 'progetti' ); ?>
		</div>
	</div>
</section>
