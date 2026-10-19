<?php
/**
 * Testimonianze da Impostazioni landing. Nascosta ai visitatori finché non ce n'è almeno una.
 */

defined( 'ABSPATH' ) || exit;

$nl_testimonianze = (array) nl_opt( 'testimonianze' );

if ( ! $nl_testimonianze && ! current_user_can( 'manage_options' ) ) {
	return;
}
?>
<section id="testimonianze" class="sezione testimonianze" aria-labelledby="testimonianze-titolo">
	<div class="contenitore">
		<header class="sezione__intestazione">
			<h2 id="testimonianze-titolo" class="sezione__titolo">Cosa dicono <mark>i clienti</mark></h2>
		</header>

		<?php if ( ! $nl_testimonianze ) : ?>
			<!-- DA VERIFICARE: testimonianze reali con nome, attività e consenso alla pubblicazione. -->
			<?php echo nl_segnaposto_admin( 'Sezione nascosta ai visitatori: aggiungi la prima testimonianza da Impostazioni landing' ); // phpcs:ignore WordPress.Security.EscapeOutput -- HTML già sanificato. ?>
		<?php else : ?>
			<ul class="testimonianze__griglia" role="list">
				<?php foreach ( $nl_testimonianze as $t ) : ?>
					<li>
						<figure class="testimonianza">
							<blockquote class="testimonianza__testo">
								<p><?php echo esc_html( $t['testo'] ); ?></p>
							</blockquote>
							<figcaption class="testimonianza__autore">
								<strong><?php echo esc_html( $t['nome'] ); ?></strong>
								<?php if ( ! empty( $t['attivita'] ) ) : ?>
									<span><?php echo esc_html( $t['attivita'] ); ?></span>
								<?php endif; ?>
							</figcaption>
						</figure>
					</li>
				<?php endforeach; ?>
			</ul>

			<div class="sezione__cta">
				<?php nl_cta( 'Inizia anche tu: preventivo gratis', 'testimonianze' ); ?>
			</div>
		<?php endif; ?>
	</div>
</section>
