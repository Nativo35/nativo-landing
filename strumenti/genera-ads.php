<?php
/**
 * Genera gli HTML degli annunci OpenAI Ads in ads/, con prezzo e foto letti da Impostazioni landing.
 * Uso: wp eval-file wp-content/themes/nativo-landing/strumenti/genera-ads.php
 * Poi: node strumenti/esporta-ads.mjs (screenshot PNG 1080 × 1080 e logo 512 × 512).
 *
 * Gli HTML usano critical.css della landing (font, palette, pulsanti) più ads/ads.css.
 * La cartella ads/ non viene mai caricata dal tema.
 */

$dir = NL_DIR . '/ads';

function nl_ads_icona( $nome ) {
	ob_start();
	nl_icona( $nome );
	return ob_get_clean();
}

function nl_ads_pagina( $titolo, $corpo, $extra_css = '' ) {
	return '<!doctype html>
<html lang="it">
<head>
<meta charset="utf-8">
<meta name="robots" content="noindex, nofollow">
<title>' . esc_html( $titolo ) . '</title>
<link rel="stylesheet" href="../assets/css/critical.css">
<link rel="stylesheet" href="ads.css">' . ( $extra_css ? "\n<style>{$extra_css}</style>" : '' ) . '
</head>
<body>
' . $corpo . '
</body>
</html>
';
}

$logo = '<img class="annuncio__logo" src="logo-nativo-studio.png" alt="Nativo Studio">';

// Foto di Matteo dalle impostazioni, incorporata nell'HTML (nessun percorso esterno).
$foto    = '';
$foto_id = (int) nl_opt( 'foto_id' );
if ( $foto_id && wp_attachment_is_image( $foto_id ) ) {
	$meta = image_get_intermediate_size( $foto_id, 'nl-ritratto-grande' );
	$file = $meta ? path_join( dirname( get_attached_file( $foto_id ) ), $meta['file'] ) : get_attached_file( $foto_id );
	if ( $file && file_exists( $file ) ) {
		$foto = '<img class="ritratto-annuncio" src="data:' . wp_check_filetype( $file )['type'] . ';base64,' . base64_encode( file_get_contents( $file ) ) . '" alt="Matteo Pugi">'; // phpcs:ignore
	}
}

$freccia = nl_ads_icona( 'freccia' );
$spunta  = nl_ads_icona( 'spunta' );

$annunci = array(
	'annuncio-1-prezzo'     => nl_ads_pagina(
		'Annuncio 1: prezzo',
		'<div class="annuncio">
	' . $logo . '
	<div class="annuncio__corpo">
		<h1 class="annuncio__titolo">Sito web su misura</h1>
		<p class="prezzo-annuncio">' . esc_html( nl_prezzo( 'vetrina' ) ) . '</p>
		<p class="prezzo-annuncio__nota">a partire da · ' . esc_html( nl_iva() ) . '</p>
		<ul class="spunte" role="list">
			<li>' . $spunta . '<span>Design su misura</span></li>
			<li>' . $spunta . '<span>SEO e mobile inclusi</span></li>
		</ul>
	</div>
	<span class="pulsante">Preventivo gratis in 24h ' . $freccia . '</span>
</div>'
	),
	'annuncio-2-problema'   => nl_ads_pagina(
		'Annuncio 2: problema',
		'<div class="annuncio annuncio--alt">
	' . $logo . '
	<div class="annuncio__corpo">
		<h1 class="annuncio__titolo">Il tuo sito non porta <mark>clienti?</mark></h1>
		<p class="annuncio__sotto">Rifacciamolo per farti contattare</p>
		<div class="grafico" aria-hidden="true"><span></span><span></span><span></span><span></span><span></span></div>
	</div>
	<span class="pulsante">Preventivo gratis ' . $freccia . '</span>
</div>'
	),
	'annuncio-3-referente'  => nl_ads_pagina(
		'Annuncio 3: referente unico',
		'<div class="annuncio">
	<div class="annuncio__testa">' . $logo . $foto . '</div>
	<div class="annuncio__corpo">
		<h1 class="annuncio__titolo">Un solo <mark>referente.</mark></h1>
		<p class="annuncio__sotto">Dal brief al lancio del sito.</p>
		<ol class="tappe" role="list">
			<li class="tappa">Brief</li>
			<li class="tappa">Design</li>
			<li class="tappa">Online</li>
		</ol>
	</div>
	<span class="pulsante">Siti web per PMI · Toscana ' . $freccia . '</span>
</div>'
	),
	'logo-openai'           => nl_ads_pagina(
		'Logo account OpenAI Ads',
		'<div class="logo-account"><img src="logo-nativo-studio-quadrato.png" alt="Nativo Studio"></div>',
		'html, body { width: 512px; height: 512px; }'
	),
);

foreach ( $annunci as $nome => $html ) {
	file_put_contents( "{$dir}/{$nome}.html", $html ); // phpcs:ignore WordPress.WP.AlternativeFunctions
	echo "{$nome}.html\n";
}
echo 'Prezzo: ' . nl_prezzo( 'vetrina' ) . ' | foto: ' . ( $foto ? 'sì' : 'no' ) . "\n";
