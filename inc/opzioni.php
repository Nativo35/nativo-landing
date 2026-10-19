<?php
/**
 * Valori della pagina "Impostazioni landing" e funzioni che li stampano.
 * Tutti i testi della landing che dipendono da prezzi, sconto, numeri,
 * testimonianze, foto e dati aziendali passano da qui.
 */

defined( 'ABSPATH' ) || exit;

const NL_OPZIONE = 'nl_impostazioni';

function nl_default() {
	return array(
		'prezzo_vetrina'     => 1100,
		'prezzo_blog'        => 1600,
		'prezzo_ecommerce'   => 2700,
		'sconto_attivo'      => 1,
		'sconto_percentuale' => 15,
		'sconto_giorni'      => 10,
		'numeri'             => array(
			array( 'valore' => '', 'suffisso' => '+', 'etichetta' => 'Anni di esperienza' ),
			array( 'valore' => '', 'suffisso' => '+', 'etichetta' => 'Progetti realizzati' ),
			array( 'valore' => '', 'suffisso' => '%', 'etichetta' => 'Progetti su misura' ),
			array( 'valore' => '', 'suffisso' => '', 'etichetta' => 'Servizi integrati' ),
		),
		'testimonianze'      => array(),
		// Nomi e tipi dalla landing attuale; contesto e immagine da completare nel pannello.
		'progetti'           => array(
			array( 'nome' => 'Addon', 'tipo' => 'E-commerce', 'contesto' => '', 'immagine' => 0 ),
			array( 'nome' => 'B&GB Bergamo', 'tipo' => 'Sito vetrina + area riservata', 'contesto' => '', 'immagine' => 0 ),
			array( 'nome' => 'Jambo Tour', 'tipo' => 'Sito vetrina + e-commerce', 'contesto' => '', 'immagine' => 0 ),
			array( 'nome' => 'Igor Meloni', 'tipo' => 'Sito vetrina', 'contesto' => '', 'immagine' => 0 ),
			array( 'nome' => 'Zaini SpA', 'tipo' => 'Sito vetrina', 'contesto' => '', 'immagine' => 0 ),
			array( 'nome' => 'Ambrosiano', 'tipo' => 'Sito vetrina', 'contesto' => '', 'immagine' => 0 ),
		),
		'foto_id'            => 0,
		'telefono'           => '+39 334 703 5703',
		'whatsapp'           => '+39 334 703 5703',
		'email'              => 'info@nativo.studio',
		'indirizzo'          => 'Via Chiesa Vecchia 35, 56030 Terricciola (PI)',
		'piva'               => '14506620963',
		'rea'                => 'MI 2788398',
		'sdi'                => 'W7YVJK9',
	);
}

/**
 * Restituisce un valore delle impostazioni (o tutte, senza chiave).
 */
function nl_opt( $chiave = null ) {
	static $opzioni = null;
	if ( null === $opzioni ) {
		$salvate  = get_option( NL_OPZIONE, array() );
		$opzioni  = array_merge( nl_default(), is_array( $salvate ) ? $salvate : array() );
	}
	if ( null === $chiave ) {
		return $opzioni;
	}
	return $opzioni[ $chiave ] ?? null;
}

/**
 * Prezzo formattato all'italiana: "1.100 €". Stringa vuota se manca.
 */
function nl_prezzo( $pacchetto ) {
	$valore = (int) nl_opt( 'prezzo_' . $pacchetto );
	return $valore > 0 ? number_format( $valore, 0, ',', '.' ) . ' €' : '';
}

/**
 * Prezzo più basso tra i pacchetti, per il "da … €" della hero.
 */
function nl_prezzo_minimo() {
	$prezzi = array_filter( array_map( 'intval', array( nl_opt( 'prezzo_vetrina' ), nl_opt( 'prezzo_blog' ), nl_opt( 'prezzo_ecommerce' ) ) ) );
	return $prezzi ? number_format( min( $prezzi ), 0, ',', '.' ) . ' €' : '';
}

function nl_sconto_attivo() {
	return (bool) nl_opt( 'sconto_attivo' ) && (int) nl_opt( 'sconto_percentuale' ) > 0 && (int) nl_opt( 'sconto_giorni' ) > 0;
}

/**
 * Unica formulazione dello sconto, usata in ogni punto della pagina.
 * Restituisce HTML già sicuro, oppure stringa vuota se lo sconto è spento.
 */
function nl_sconto_testo() {
	if ( ! nl_sconto_attivo() ) {
		return '';
	}
	return sprintf(
		'<strong>Sconto del %1$d%%</strong> se accetti il preventivo entro %2$d giorni dal ricevimento.',
		(int) nl_opt( 'sconto_percentuale' ),
		(int) nl_opt( 'sconto_giorni' )
	);
}

/**
 * Numero di telefono per i link: solo cifre e "+".
 */
function nl_tel_href( $numero = null ) {
	return 'tel:' . preg_replace( '/[^\d+]/', '', $numero ?? nl_opt( 'telefono' ) );
}

/**
 * Numero da mostrare nel testo, senza prefisso internazionale: "334 703 5703".
 */
function nl_tel_breve() {
	return trim( preg_replace( '/^\+39\s*/', '', (string) nl_opt( 'telefono' ) ) );
}

function nl_whatsapp_href() {
	return 'https://wa.me/' . preg_replace( '/\D/', '', nl_opt( 'whatsapp' ) );
}

/**
 * Variante A/B: "b" con ?v=b nell'URL, altrimenti "a".
 */
function nl_variante() {
	return ( isset( $_GET['v'] ) && 'b' === strtolower( sanitize_key( wp_unslash( $_GET['v'] ) ) ) ) ? 'b' : 'a'; // phpcs:ignore WordPress.Security.NonceVerification
}

/**
 * Segnaposto visibile solo a chi può modificare le impostazioni.
 */
function nl_segnaposto_admin( $testo ) {
	if ( ! current_user_can( 'manage_options' ) ) {
		return '';
	}
	return sprintf(
		'<a class="nl-segnaposto" href="%s">%s</a>',
		esc_url( admin_url( 'admin.php?page=nl-impostazioni' ) ),
		esc_html( $testo )
	);
}

/**
 * Ritratto di Matteo, oppure un segnaposto neutro finché non viene caricato.
 */
function nl_ritratto( $classe = 'nl-ritratto', $dimensione = 'nl-ritratto', $sizes = '80px', $loading = 'eager' ) {
	$id = (int) nl_opt( 'foto_id' );
	if ( $id && wp_attachment_is_image( $id ) ) {
		return wp_get_attachment_image(
			$id,
			$dimensione,
			false,
			array(
				'class'         => $classe,
				'alt'           => 'Matteo Pugi, fondatore di Nativo Studio',
				'loading'       => $loading,
				'fetchpriority' => 'low',
				'sizes'         => $sizes,
			)
		);
	}
	return '<span class="' . esc_attr( $classe ) . ' ' . esc_attr( $classe ) . '--vuoto" role="img" aria-label="Matteo Pugi"><span aria-hidden="true">MP</span></span>';
}

/**
 * Riga anti-rischio, identica sotto ogni CTA principale.
 */
function nl_rischio_testo() {
	return 'Gratis · Senza impegno · Risposta entro 24 ore';
}

/**
 * CTA principale: pulsante arancio con freccia e riga anti-rischio.
 * $posizione finisce in data-cta, per distinguere le CTA nel tracciamento.
 */
function nl_cta( $testo, $posizione, $href = '#preventivo', $rischio = true ) {
	?>
	<div class="cta">
		<a class="pulsante pulsante--grande" href="<?php echo esc_url( $href ); ?>" data-cta="<?php echo esc_attr( $posizione ); ?>">
			<span><?php echo esc_html( $testo ); ?></span>
			<?php nl_icona( 'freccia' ); ?>
		</a>
		<?php if ( $rischio ) : ?>
			<!-- DA VERIFICARE: "Risposta entro 24 ore" vale anche nel weekend? Le fonti dicono anche "entro un giorno lavorativo". -->
			<p class="cta__rischio"><?php echo esc_html( nl_rischio_testo() ); ?></p>
		<?php endif; ?>
	</div>
	<?php
}

/**
 * Banda scura dello sconto: percentuale in grande e la frase unica di nl_sconto_testo().
 */
function nl_sconto_banda( $classe = '' ) {
	if ( ! nl_sconto_attivo() ) {
		return;
	}
	?>
	<div class="sconto <?php echo esc_attr( $classe ); ?>">
		<p class="sconto__percentuale" aria-hidden="true">−<?php echo (int) nl_opt( 'sconto_percentuale' ); ?>%</p>
		<p class="sconto__testo"><?php echo wp_kses_post( nl_sconto_testo() ); ?></p>
	</div>
	<?php
}
