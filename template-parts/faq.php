<?php
/**
 * Domande frequenti: obiezioni reali, risposte brevi. Accordion nativo <details>, una domanda aperta alla volta, senza JavaScript.
 * Prezzi e sconto sono letti da Impostazioni landing.
 */

defined( 'ABSPATH' ) || exit;

$nl_prezzi = array_filter(
	array(
		nl_prezzo( 'vetrina' ) ? nl_con_iva( nl_prezzo( 'vetrina' ) ) . ' per un sito vetrina' : '',
		nl_prezzo( 'blog' ) ? nl_con_iva( nl_prezzo( 'blog' ) ) . ' con il blog' : '',
		nl_prezzo( 'ecommerce' ) ? nl_con_iva( nl_prezzo( 'ecommerce' ) ) . ' per un e-commerce' : '',
	)
);

// Hosting: cita il servizio "Hosting, manutenzione e backup" con i prezzi delle impostazioni.
$nl_hosting = '';
if ( nl_opt( 'servizi_attivi' ) && nl_euro( nl_opt( 'hosting_mese' ) ) ) {
	$nl_hosting = ' oppure affidarli a noi con il servizio <strong>' . esc_html( nl_opt( 'hosting_nome' ) ) . '</strong>, a '
		. esc_html( nl_con_iva( nl_euro( nl_opt( 'hosting_mese' ) ) . '/mese' ) )
		. ( nl_euro( nl_opt( 'hosting_anno' ) ) ? ' (oppure ' . esc_html( nl_con_iva( nl_euro( nl_opt( 'hosting_anno' ) ) . '/anno' ) ) . ')' : '' )
		. ': sito sempre online, aggiornato e al sicuro';
}

$nl_faq = array(
	array(
		'Quanto costa un sito?',
		'<p>Dipende da cosa ti serve.' . ( $nl_prezzi ? ' Si parte da ' . esc_html( implode( ', ', $nl_prezzi ) ) . '.' : '' ) . ' Dopo la chiamata conoscitiva ricevi un preventivo dettagliato, gratis e senza impegno.</p>'
		. ( nl_sconto_testo() ? '<p>' . nl_sconto_testo() . '</p>' : '' ),
	),
	array(
		'Quanto tempo ci vuole?',
		'<p>Sito vetrina 3–4 settimane, con blog 4–6, e-commerce 5–7. Tempi concordati all\'inizio e rispettati. Conta anche quanto velocemente arrivano testi e foto.</p>',
	),
	array(
		'Potrò modificare il sito da solo?',
		'<p>Sì. WordPress e formazione inclusi: testi, foto, pagine e articoli li aggiorni tu. Per aggiornamenti tecnici e sicurezza puoi contare sul nostro servizio di assistenza.</p>',
	),
	array(
		'Hosting e dominio sono inclusi?',
		'<p>Non sono compresi nel prezzo del sito. Puoi usare quelli che hai già' . ( $nl_hosting ?: ' oppure affidarli a noi' ) . '. Ti consigliamo la soluzione migliore durante la chiamata.</p>',
	),
	array(
		'E se il risultato non mi piace?',
		'<p>Non ti presentiamo il sito finito a sorpresa: approvi ogni fase. Le revisioni sono incluse prima della consegna.</p>',
	),
	array(
		'Il sito sarà visibile su Google?',
		'<p>Sì. La SEO di base è sempre inclusa: struttura, testi e metadati, velocità, versione mobile. Per salire in un mercato competitivo c\'è il percorso SEO continuativo.</p>',
	),
	array(
		'Ho già un sito: perderò il posizionamento?',
		'<p>No, se il rifacimento è fatto bene. Analizziamo le pagine indicizzate e impostiamo i reindirizzamenti, per non perdere visibilità.</p>',
	),
	array(
		'Come funziona il preventivo?',
		'<p>Compili il form e Matteo ti risponde entro 24 ore con una prima valutazione. Se il progetto fa per noi, fissiamo una breve chiamata gratuita e ti mandiamo il preventivo dettagliato.</p>',
	),
);
?>
<section id="faq" class="sezione sezione--alt faq" aria-labelledby="faq-titolo">
	<div class="contenitore faq__griglia">
		<header class="faq__intestazione">
			<h2 id="faq-titolo" class="sezione__titolo">Hai dei dubbi? <mark>Ecco le risposte.</mark></h2>
			<p class="sezione__sottotitolo">Le domande che ci fanno più spesso prima di iniziare.</p>
			<div class="faq__azione">
				<p class="faq__altro">Hai un'altra domanda?</p>
				<?php nl_cta( 'Chiama Matteo: ' . nl_tel_breve(), 'faq', nl_tel_href(), false, 'clic_telefono' ); ?>
			</div>
		</header>

		<!-- DA VERIFICARE: tempi di consegna, revisioni incluse, servizio di assistenza e tempo di risposta ("entro 24 ore"). Dominio compreso o no nel servizio hosting. -->
		<div class="faq__elenco">
			<?php foreach ( $nl_faq as $i => $voce ) : ?>
				<?php // name condiviso: aprendo una domanda il browser chiude le altre (accordion esclusivo nativo). ?>
				<details class="domanda" name="faq"<?php echo 0 === $i ? ' open' : ''; ?>>
					<summary class="domanda__titolo">
						<span><?php echo esc_html( $voce[0] ); ?></span>
						<span class="domanda__segno" aria-hidden="true"></span>
					</summary>
					<div class="domanda__risposta"><?php echo wp_kses_post( $voce[1] ); ?></div>
				</details>
			<?php endforeach; ?>
		</div>
	</div>
</section>
