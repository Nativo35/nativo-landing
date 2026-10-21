<?php
/**
 * Ultima sezione, dopo il form: per chi non è ancora pronto. Tono calmo, pulsanti meno forti delle CTA.
 * "Salva il contatto" scarica la vCard generata da Impostazioni landing (inc/vcard.php).
 * Il QR (solo da computer) è un SVG statico: rigenerarlo con strumenti/genera-qr.py se cambia il dominio.
 */

defined( 'ABSPATH' ) || exit;

$nl_foto = (int) nl_opt( 'foto_id' ) && wp_attachment_is_image( (int) nl_opt( 'foto_id' ) );
?>
<section id="contatto" class="sezione contatto" aria-labelledby="contatto-titolo">
	<div class="contenitore contatto__griglia">
		<div class="contatto__testo">
			<div class="contatto__intestazione">
				<?php if ( $nl_foto ) : ?>
					<?php echo nl_ritratto( 'contatto__foto', 'nl-ritratto', '64px', 'lazy' ); // phpcs:ignore WordPress.Security.EscapeOutput -- HTML generato da WordPress. ?>
				<?php endif; ?>
				<p class="contatto__etichetta">Non è ancora il momento?</p>
			</div>

			<h2 id="contatto-titolo" class="contatto__titolo">Nessuna fretta. Tieni il mio numero a portata di mano.</h2>
			<p class="contatto__descrizione">Magari il sito ti servirà tra qualche mese, o hai ancora bisogno di pensarci. Va benissimo. Salva i miei contatti sul telefono: quando sarai pronto, li ritroverai in un attimo.</p>

			<div class="contatto__azioni">
				<a class="pulsante-calmo pulsante-calmo--pieno" href="<?php echo esc_url( nl_vcard_url() ); ?>" data-evento="salva_contatto" type="text/vcard">
					<?php nl_icona( 'contatto' ); ?>
					<span>Salva il contatto</span>
				</a>
				<a class="pulsante-calmo" href="<?php echo esc_url( nl_whatsapp_href( 'Ciao Matteo, ho visto la tua pagina e vorrei salvare il tuo contatto per un progetto futuro.' ) ); ?>" data-evento="whatsapp" target="_blank" rel="noopener">
					<?php nl_icona( 'whatsapp' ); ?>
					<span>Scrivimi su WhatsApp</span>
				</a>
			</div>

			<p class="contatto__nota">Nessuna newsletter, nessuna chiamata a sorpresa. Mi senti solo se mi scrivi tu.</p>
		</div>

		<?php // DA VERIFICARE: il QR punta a https://promo.nativo.studio/?nl_vcard=1; se la landing va su un altro dominio, rigeneralo. ?>
		<figure class="contatto__qr">
			<img src="<?php echo esc_url( NL_URI . '/assets/img/qr-contatto.svg' ); ?>" width="222" height="222" alt="Codice QR per salvare il contatto di <?php echo esc_attr( nl_opt( 'referente_nome' ) ); ?>" loading="lazy" decoding="async">
			<figcaption><strong>Sei al computer?</strong> Inquadra il codice con il telefono e salva il contatto</figcaption>
		</figure>
	</div>
</section>
