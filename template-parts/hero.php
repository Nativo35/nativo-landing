<?php
/**
 * Hero: titolo e CTA in variante A/B (?v=b), benefici in elenco, fiducia vicino alla CTA, prezzi e sconto.
 */

defined( 'ABSPATH' ) || exit;

$nl_b = 'b' === nl_variante();

$nl_pacchetti = array(
	array( 'nome' => 'Sito Vetrina', 'chiave' => 'vetrina', 'tempi' => '3–4 settimane' ),
	array( 'nome' => 'Sito + Blog', 'chiave' => 'blog', 'tempi' => '4–6 settimane', 'evidenza' => true ),
	array( 'nome' => 'E-commerce', 'chiave' => 'ecommerce', 'tempi' => '5–7 settimane' ),
);
?>
<section class="hero" aria-labelledby="hero-titolo">
	<div class="contenitore hero__griglia">
		<div class="hero__testo">
			<p class="occhiello">Siti web per PMI e professionisti in Toscana</p>

			<?php if ( $nl_b ) : ?>
				<h1 id="hero-titolo" class="hero__titolo">Il tuo sito non porta contatti? <mark>Rifacciamolo.</mark></h1>
			<?php else : ?>
				<h1 id="hero-titolo" class="hero__titolo">Il sito che ti porta <mark>più clienti.</mark></h1>
			<?php endif; ?>

			<p class="hero__sottotitolo">Un sito su misura che ti fa trovare su Google e trasforma le visite in richieste.</p>

			<ul class="spunte hero__benefici" role="list">
				<li><?php nl_icona( 'spunta' ); ?><span><strong>Più richieste</strong> di preventivo, non solo visite</span></li>
				<li><?php nl_icona( 'spunta' ); ?><span><strong>Un solo referente</strong>, Matteo, dall'inizio alla fine</span></li>
				<?php if ( nl_prezzo_minimo() ) : ?>
					<li><?php nl_icona( 'spunta' ); ?><span><strong>Prezzi chiari</strong>: da&nbsp;<?php echo esc_html( nl_prezzo_minimo() ); ?></span></li>
				<?php endif; ?>
			</ul>

			<?php nl_cta( $nl_b ? 'Ricevi una valutazione gratis' : 'Ricevi il preventivo gratis', 'hero' ); ?>

			<div class="fiducia">
				<?php echo nl_ritratto(); // phpcs:ignore WordPress.Security.EscapeOutput -- HTML generato da WordPress o fisso. ?>
				<p>
					Ti risponde <strong>Matteo Pugi</strong>, fondatore di Nativo Studio.<br>
					Preferisci chiamare? <a href="<?php echo esc_attr( nl_tel_href() ); ?>" data-evento="clic_telefono"><?php echo esc_html( nl_tel_breve() ); ?></a>
				</p>
			</div>
			<?php
			if ( ! nl_opt( 'foto_id' ) ) {
				echo nl_segnaposto_admin( 'Carica la foto di Matteo da Impostazioni landing' ); // phpcs:ignore WordPress.Security.EscapeOutput -- HTML già sanificato.
			}
			?>
		</div>

		<aside class="prezzi-lampo" aria-labelledby="prezzi-lampo-titolo">
			<p id="prezzi-lampo-titolo" class="prezzi-lampo__titolo">Prezzi chiari, subito</p>
			<!-- DA VERIFICARE: tempi di consegna (3–4 / 4–6 / 5–7 settimane oppure 4–8 settimane) e prezzi IVA esclusa o inclusa. -->
			<!-- DA VERIFICARE: etichetta "Più richiesto" su Sito + Blog, da mostrare solo se corrisponde ai dati reali. -->
			<ul class="prezzi-lampo__lista" role="list">
				<?php foreach ( $nl_pacchetti as $p ) : ?>
					<?php
					if ( ! nl_prezzo( $p['chiave'] ) ) {
						continue;
					}
					?>
					<li class="<?php echo empty( $p['evidenza'] ) ? '' : 'in-evidenza'; ?>">
						<span class="prezzi-lampo__nome">
							<?php echo esc_html( $p['nome'] ); ?>
							<?php if ( ! empty( $p['evidenza'] ) ) : ?>
								<span class="etichetta">Più richiesto</span>
							<?php endif; ?>
						</span>
						<span class="prezzi-lampo__tempi">Online in <?php echo esc_html( $p['tempi'] ); ?></span>
						<span class="prezzi-lampo__prezzo"><small>da</small>&nbsp;<?php echo esc_html( nl_prezzo( $p['chiave'] ) ); ?></span>
					</li>
				<?php endforeach; ?>
			</ul>
			<?php nl_sconto_banda(); ?>
		</aside>
	</div>
</section>
