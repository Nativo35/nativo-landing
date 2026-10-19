<?php
/**
 * Pacchetti: prezzi letti da Impostazioni landing, "Più richiesto" in evidenza, sconto unico.
 * Ogni CTA indica il pacchetto (data-pacchetto), così il form potrà preselezionarlo.
 */

defined( 'ABSPATH' ) || exit;

$nl_pacchetti = array(
	array(
		'chiave'   => 'vetrina',
		'nome'     => 'Sito Vetrina',
		'per'      => 'Per presentare la tua attività e ricevere richieste.',
		'tempi'    => '3–4 settimane',
		'voci'     => array( 'Design su misura', 'Perfetto su smartphone', 'SEO di base per Google', 'Form e pulsanti per farti scrivere', 'Lo aggiorni da solo' ),
		'cta'      => 'Voglio il Sito Vetrina',
		'evidenza' => false,
	),
	array(
		'chiave'   => 'blog',
		'nome'     => 'Sito + Blog',
		'per'      => 'Per farti trovare su Google, articolo dopo articolo.',
		'tempi'    => '4–6 settimane',
		'voci'     => array( 'Tutto del Sito Vetrina', 'Blog che porta visite da Google', 'Parole chiave studiate per te', 'Newsletter integrata', 'Articoli pronti per Google e per le risposte AI' ),
		'cta'      => 'Voglio Sito + Blog',
		'evidenza' => true,
	),
	array(
		'chiave'   => 'ecommerce',
		'nome'     => 'E-commerce',
		'per'      => 'Per vendere online, 24 ore su 24.',
		'tempi'    => '5–7 settimane',
		'voci'     => array( 'Prodotti illimitati', 'Pagamenti con carta e PayPal', 'Ordini e spedizioni da un pannello semplice', 'Coupon e promozioni', 'SEO per e-commerce' ),
		'cta'      => 'Voglio l\'E-commerce',
		'evidenza' => false,
	),
);
?>
<section id="pacchetti" class="sezione sezione--alt pacchetti" aria-labelledby="pacchetti-titolo">
	<div class="contenitore">
		<header class="sezione__intestazione">
			<h2 id="pacchetti-titolo" class="sezione__titolo">Prezzi chiari. <mark>Nessuna sorpresa.</mark></h2>
			<p class="sezione__sottotitolo">Scegli il punto di partenza. Il preventivo finale dipende da quello che ti serve davvero.</p>
		</header>

		<!-- DA VERIFICARE: tempi di consegna (3–4 / 4–6 / 5–7 settimane oppure 4–8 settimane). -->
		<!-- DA VERIFICARE: etichetta "Più richiesto" su Sito + Blog, da mostrare solo se corrisponde ai dati reali. -->
		<ul class="pacchetti__griglia" role="list">
			<?php foreach ( $nl_pacchetti as $p ) : ?>
				<?php
				if ( ! nl_prezzo( $p['chiave'] ) ) {
					continue;
				}
				?>
				<li class="pacchetto<?php echo $p['evidenza'] ? ' pacchetto--evidenza' : ''; ?>">
					<?php if ( $p['evidenza'] ) : ?>
						<span class="etichetta pacchetto__etichetta">Più richiesto</span>
					<?php endif; ?>
					<h3 class="pacchetto__nome"><?php echo esc_html( $p['nome'] ); ?></h3>
					<p class="pacchetto__per"><?php echo esc_html( $p['per'] ); ?></p>

					<p class="pacchetto__prezzo"><small>da</small>&nbsp;<?php echo esc_html( nl_prezzo( $p['chiave'] ) ); ?></p>
					<p class="pacchetto__tempi">Online in <?php echo esc_html( $p['tempi'] ); ?></p>

					<ul class="spunte pacchetto__voci" role="list">
						<?php foreach ( $p['voci'] as $voce ) : ?>
							<li><?php nl_icona( 'spunta' ); ?><span><?php echo esc_html( $voce ); ?></span></li>
						<?php endforeach; ?>
					</ul>

					<div class="pacchetto__azione" data-pacchetto="<?php echo esc_attr( $p['chiave'] ); ?>">
						<?php nl_cta( $p['cta'], 'pacchetto-' . $p['chiave'] ); ?>
					</div>
				</li>
			<?php endforeach; ?>
		</ul>

		<?php nl_sconto_banda( 'sconto--grande' ); ?>

		<!-- DA VERIFICARE: prezzi IVA esclusa o inclusa; hosting e dominio esclusi dal prezzo. -->
		<p class="pacchetti__nota">Prezzi IVA esclusa. Hosting e dominio a parte: possiamo gestirli noi o usare quelli che hai già.</p>
	</div>
</section>
