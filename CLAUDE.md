# Nativo Landing — tema WordPress

## Lingua

- Rispondi sempre in italiano.

## Regole di lavoro

- A ogni sezione completata fai commit e push su GitHub, con un messaggio in italiano che descriva la modifica.
- Lavora per fasi e fermati dove indicato.
- Non inventare dati, numeri, clienti, testimonianze o risultati. Ciò che non è verificabile nelle fonti (https://promo.nativo.studio/, https://nativo.studio/) va segnato con un commento `<!-- DA VERIFICARE: ... -->` in italiano.
- Controlla ogni sezione con screenshot a 390 px e 1440 px.
- Brief completo: `../../../../../materiale-nativo/brief-landing-nativo.md`. Analisi e proposta approvata: `analisi.md`.

## Regole tecniche

- Tema WordPress classico custom: niente page builder, niente jQuery.
- Mobile first: progetta prima a 390 px.
- Font self-hosted in woff2, al massimo due famiglie; preload solo di quelli necessari alla prima schermata.
- Immagini in WebP/AVIF, dimensioni responsive (`srcset`), lazy load sotto la prima schermata, nessuna immagine pesante nella hero.
- CSS critico inline, resto del CSS minimo; JavaScript vanilla, poco e differito.
- Script di Contact Form 7 caricati solo dove serve.
- Google Tag Manager (GTM-WHFCL32F) caricato solo dopo il consenso cookie.
- Contrasto conforme WCAG AA. Icone SVG leggere; niente slider, animazioni pesanti o effetti decorativi superflui.
- Obiettivo prestazioni: PageSpeed mobile ≥ 90, LCP < 2,5 s, CLS < 0,1. Misura e riporta i valori.

## Form e conversioni

- Contact Form 7 + Flamingo; verifica dell'invio con Mailpit in Local.
- Campi: nome, email, telefono, tipo di progetto (Sito vetrina / Sito + Blog / E-commerce / Non lo so ancora), messaggio facoltativo, consenso privacy.
- Notifica interna a info@nativo.studio e conferma automatica a chi scrive.
- Dopo l'invio reindirizza alla pagina `/grazie/` (serve a misurare le conversioni).
- Campi nascosti con i parametri UTM e la variante A/B mostrata.
- Eventi nel `dataLayer` per invio form, clic sul telefono e clic su WhatsApp.
- Test A/B senza plugin: titolo hero e testo della CTA principale; variante scelta con parametro URL (es. `?v=b`) e salvata nel form.
