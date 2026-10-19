<?php
/**
 * Hero: titolo e CTA in variante A/B (?v=b), prova di fiducia vicino alla CTA, prezzi di partenza.
 */

defined( 'ABSPATH' ) || exit;

$nl_b = 'b' === nl_variante();

$nl_pacchetti = array(
	array( 'nome' => 'Sito Vetrina', 'chiave' => 'vetrina', 'tempi' => '3–4 settimane' ),
	array( 'nome' => 'Sito + Blog', 'chiave' => 'blog', 'tempi' => '4–6 settimane' ),
	array( 'nome' => 'E-commerce', 'chiave' => 'ecommerce', 'tempi' => '5–7 settimane' ),
);
?>
<section class="hero" aria-labelledby="hero-titolo">
	<div class="contenitore hero__griglia">
		<div class="hero__testo">
			<p class="occhiello">Siti web per PMI e professionisti in Toscana</p>

			<?php if ( $nl_b ) : ?>
				<h1 id="hero-titolo" class="hero__titolo">Il tuo sito è vecchio, lento o non porta contatti? <mark>Rifacciamolo insieme.</mark></h1>
			<?php else : ?>
				<h1 id="hero-titolo" class="hero__titolo">Un sito su misura che <mark>porta richieste</mark>, non solo visite.</h1>
			<?php endif; ?>

			<p class="hero__sottotitolo">
				Progettiamo il tuo sito da zero, ottimizzato per Google e per lo smartphone.
				Ti segue un unico referente dal primo incontro alla messa online, con prezzi chiari fin da subito<?php echo nl_prezzo_minimo() ? ': da&nbsp;' . esc_html( nl_prezzo_minimo() ) : ''; ?>.
			</p>

			<div class="hero__azioni">
				<a class="pulsante" href="#preventivo" data-cta="hero">
					<?php echo esc_html( $nl_b ? 'Ricevi una valutazione gratuita' : 'Richiedi il preventivo gratuito' ); ?>
					<?php nl_icona( 'freccia' ); ?>
				</a>
				<a class="hero__tel" href="<?php echo esc_attr( nl_tel_href() ); ?>" data-evento="clic_telefono">
					oppure chiama il <strong><?php echo esc_html( nl_tel_breve() ); ?></strong>
				</a>
			</div>

			<div class="fiducia">
				<?php echo nl_ritratto(); // phpcs:ignore WordPress.Security.EscapeOutput -- HTML generato da WordPress o fisso. ?>
				<!-- DA VERIFICARE: tempo di risposta promesso ("entro un giorno lavorativo"). -->
				<ul class="fiducia__lista" role="list">
					<li><?php nl_icona( 'spunta' ); ?><span>Ti risponde <strong>Matteo Pugi</strong>, entro un giorno lavorativo</span></li>
					<li><?php nl_icona( 'spunta' ); ?><span>Preventivo gratuito e senza impegno</span></li>
					<li><?php nl_icona( 'spunta' ); ?><span>Prezzi e tempi dichiarati</span></li>
				</ul>
			</div>
			<?php
			if ( ! nl_opt( 'foto_id' ) ) {
				echo nl_segnaposto_admin( 'Carica la foto di Matteo da Impostazioni landing' ); // phpcs:ignore WordPress.Security.EscapeOutput -- HTML già sanificato.
			}
			?>
		</div>

		<aside class="prezzi-lampo" aria-labelledby="prezzi-lampo-titolo">
			<p id="prezzi-lampo-titolo" class="prezzi-lampo__titolo">Prezzi chiari fin da subito</p>
			<!-- DA VERIFICARE: tempi di consegna (3–4 / 4–6 / 5–7 settimane oppure 4–8 settimane) e prezzi IVA esclusa o inclusa. -->
			<ul class="prezzi-lampo__lista" role="list">
				<?php foreach ( $nl_pacchetti as $p ) : ?>
					<?php
					if ( ! nl_prezzo( $p['chiave'] ) ) {
						continue;
					}
					?>
					<li>
						<span class="prezzi-lampo__nome"><?php echo esc_html( $p['nome'] ); ?></span>
						<span class="prezzi-lampo__tempi">Consegna in <?php echo esc_html( $p['tempi'] ); ?></span>
						<span class="prezzi-lampo__prezzo"><small>da</small>&nbsp;<?php echo esc_html( nl_prezzo( $p['chiave'] ) ); ?></span>
					</li>
				<?php endforeach; ?>
			</ul>
			<?php if ( nl_sconto_testo() ) : ?>
				<p class="offerta"><?php echo wp_kses_post( nl_sconto_testo() ); ?></p>
			<?php endif; ?>
			<a class="prezzi-lampo__link" href="#pacchetti">Cosa include ogni pacchetto</a>
		</aside>
	</div>
</section>
