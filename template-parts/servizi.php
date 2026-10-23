<?php
/**
 * Servizi aggiuntivi, subito dopo i pacchetti: completano l'offerta senza fare concorrenza alle tre card.
 * Grafica più leggera dei pacchetti: bordo sottile, prezzi più piccoli, nessuna card scura.
 * Prezzi e testi letti da Impostazioni landing; un servizio senza prezzo non compare.
 * Si accende e spegne da Impostazioni landing › Sezioni.
 */

defined( 'ABSPATH' ) || exit;

$nl_mese = nl_euro( nl_opt( 'hosting_mese' ) );
$nl_anno = nl_euro( nl_opt( 'hosting_anno' ) );
$nl_blog = nl_euro( nl_opt( 'blog_mese' ) );

// Riga compatta in fondo: voci senza valore saltate.
$nl_altre = array_filter(
	array(
		nl_euro( nl_opt( 'pagina_extra_prezzo' ) ) ? '<strong>' . esc_html( nl_opt( 'pagina_extra_nome' ) ) . '</strong> ' . esc_html( nl_euro( nl_opt( 'pagina_extra_prezzo' ) ) ) . nl_iva_html() : '',
		(int) nl_opt( 'lingua_percentuale' ) > 0 ? '<strong>' . esc_html( nl_opt( 'lingua_nome' ) ) . '</strong> +' . (int) nl_opt( 'lingua_percentuale' ) . '% sul prezzo del sito' : '',
		nl_opt( 'prodotti_extra_testo' ) ? esc_html( nl_opt( 'prodotti_extra_testo' ) ) : '',
	)
);
?>
<section id="servizi" <?php nl_sezione_attr( 'servizi', 'sezione servizi' ); ?> aria-labelledby="servizi-titolo">
	<div class="contenitore">
		<header class="sezione__intestazione">
			<h2 id="servizi-titolo" class="sezione__titolo"><?php echo esc_html( nl_opt( 'servizi_titolo' ) ); ?></h2>
			<?php if ( nl_opt( 'servizi_sottotitolo' ) ) : ?>
				<p class="sezione__sottotitolo"><?php echo esc_html( nl_opt( 'servizi_sottotitolo' ) ); ?></p>
			<?php endif; ?>
		</header>

		<ul class="servizi__griglia" role="list">
			<?php if ( $nl_mese || $nl_anno ) : ?>
				<li class="servizio servizio--principale">
					<span class="servizio__icona"><?php nl_icona( 'scudo' ); ?></span>
					<h3 class="servizio__nome"><?php echo esc_html( nl_opt( 'hosting_nome' ) ); ?></h3>
					<p class="servizio__testo"><?php echo esc_html( nl_opt( 'hosting_descrizione' ) ); ?></p>
					<ul class="servizio__opzioni" role="list">
						<?php if ( $nl_mese ) : ?>
							<li class="opzione">
								<span class="opzione__nome">Mensile</span>
								<span class="servizio__prezzo"><?php echo esc_html( $nl_mese ); ?><small>/mese</small><?php echo nl_iva_html(); // phpcs:ignore WordPress.Security.EscapeOutput -- HTML già sanificato. ?></span>
							</li>
						<?php endif; ?>
						<?php if ( $nl_anno ) : ?>
							<li class="opzione opzione--anno">
								<span class="opzione__nome">
									Annuale
									<?php if ( nl_opt( 'hosting_anno_etichetta' ) ) : ?>
										<span class="etichetta"><?php echo esc_html( nl_opt( 'hosting_anno_etichetta' ) ); ?></span>
									<?php endif; ?>
								</span>
								<span class="servizio__prezzo"><?php echo esc_html( $nl_anno ); ?><small>/anno</small><?php echo nl_iva_html(); // phpcs:ignore WordPress.Security.EscapeOutput -- HTML già sanificato. ?></span>
							</li>
						<?php endif; ?>
					</ul>
				</li>
			<?php endif; ?>

			<?php if ( $nl_blog ) : ?>
				<li class="servizio">
					<span class="servizio__icona"><?php nl_icona( 'matita' ); ?></span>
					<h3 class="servizio__nome"><?php echo esc_html( nl_opt( 'blog_nome' ) ); ?></h3>
					<p class="servizio__testo"><?php echo esc_html( nl_opt( 'blog_descrizione' ) ); ?></p>
					<p class="servizio__prezzo servizio__prezzo--solo"><?php echo esc_html( $nl_blog ); ?><small>/mese</small><?php echo nl_iva_html(); // phpcs:ignore WordPress.Security.EscapeOutput -- HTML già sanificato. ?></p>
				</li>
			<?php endif; ?>
		</ul>

		<?php if ( $nl_altre ) : ?>
			<ul class="servizi__altre" role="list" aria-label="Altri servizi">
				<?php foreach ( $nl_altre as $voce ) : ?>
					<li><?php echo wp_kses_post( $voce ); ?></li>
				<?php endforeach; ?>
			</ul>
		<?php endif; ?>

		<div class="sezione__cta servizi__cta">
			<?php nl_cta( nl_opt( 'servizi_cta' ), 'servizi' ); ?>
		</div>
	</div>
</section>
