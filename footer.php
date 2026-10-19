<?php
/**
 * Footer con i dati aziendali, letti dalla pagina "Impostazioni landing".
 */

defined( 'ABSPATH' ) || exit;

$nl_privacy = get_privacy_policy_url();
?>
</main>

<footer class="piede">
	<div class="contenitore piede__griglia">
		<div>
			<img class="piede__logo" src="<?php echo esc_url( NL_URI . '/assets/img/logo-nativo-studio.webp' ); ?>" width="148" height="30" alt="Nativo Studio" loading="lazy">
			<p>Studio creativo indipendente per il web e la comunicazione.</p>
		</div>

		<ul class="piede__contatti" role="list">
			<li><a href="mailto:<?php echo esc_attr( nl_opt( 'email' ) ); ?>"><?php echo esc_html( nl_opt( 'email' ) ); ?></a></li>
			<li><a href="<?php echo esc_attr( nl_tel_href() ); ?>" data-evento="clic_telefono"><?php echo esc_html( nl_opt( 'telefono' ) ); ?></a></li>
			<li><a href="<?php echo esc_url( nl_whatsapp_href() ); ?>" data-evento="clic_whatsapp" rel="noopener">WhatsApp</a></li>
		</ul>

		<!-- DA VERIFICARE: indirizzo (Terricciola o Milano) e REA (PI o MI), modificabili da Impostazioni landing. -->
		<p class="piede__legale">
			<strong>Nativo Studio</strong>
			· <?php echo esc_html( nl_opt( 'indirizzo' ) ); ?>
			· P. IVA <?php echo esc_html( nl_opt( 'piva' ) ); ?>
			· REA <?php echo esc_html( nl_opt( 'rea' ) ); ?>
			· SDI <?php echo esc_html( nl_opt( 'sdi' ) ); ?>
		</p>

		<p class="piede__link">
			<span>© <?php echo esc_html( gmdate( 'Y' ) ); ?> Nativo Studio</span>
			<?php if ( $nl_privacy ) : ?>
				<a href="<?php echo esc_url( $nl_privacy ); ?>">Privacy policy</a>
			<?php endif; ?>
			<!-- DA VERIFICARE: cookie policy e gestione del consenso (Complianz o iubenda). -->
		</p>
	</div>
</footer>

<div class="barra-cta" id="barra-cta" aria-hidden="true" inert>
	<a class="pulsante barra-cta__pulsante" href="<?php echo esc_url( is_front_page() ? "#preventivo" : home_url( "/#preventivo" ) ); ?>" data-cta="barra-mobile">
		<span>Preventivo gratis</span>
		<?php nl_icona( 'freccia' ); ?>
	</a>
	<a class="barra-cta__icona barra-cta__icona--whatsapp" href="<?php echo esc_url( nl_whatsapp_href() ); ?>" data-evento="clic_whatsapp" target="_blank" rel="noopener" aria-label="Scrivi a Nativo Studio su WhatsApp">
		<?php nl_icona( 'whatsapp' ); ?>
	</a>
	<a class="barra-cta__icona" href="<?php echo esc_attr( nl_tel_href() ); ?>" data-evento="clic_telefono" aria-label="Chiama Nativo Studio">
		<?php nl_icona( 'telefono' ); ?>
	</a>
</div>

<?php wp_footer(); ?>
</body>
</html>
