# Analisi e proposta: nuova landing Nativo Studio

Data: 16 ottobre 2026
Fonti lette per intero: https://promo.nativo.studio/ (landing attuale), https://nativo.studio/ (home), /servizi/, /servizi/realizzazione-siti-web-toscana/, /chi-siamo/, /contatti/.

Obiettivo unico della nuova landing: **più richieste di preventivo qualificate**.

Nel documento i commenti `<!-- DA VERIFICARE: ... -->` segnano ciò che non è verificabile nelle fonti. L'elenco completo è in fondo, nella sezione 7.

---

## 1. Prestazioni attuali (Lighthouse, mobile)

Misura fatta con Lighthouse 13.5.0 da riga di comando, emulazione mobile predefinita (Moto G Power, rete 4G lenta simulata), 3 esecuzioni il 16/10/2026. La cookie bar non è stata accettata, come per un visitatore nuovo. Non ho potuto usare PageSpeed Insights (quota giornaliera dell'API esaurita), quindi mancano i dati reali degli utenti (CrUX).

| Metrica | Run 1 | Run 2 | Run 3 | Mediana | Obiettivo |
|---|---|---|---|---|---|
| Performance | 55 | 49 | 58 | **55** | ≥ 90 |
| LCP | 8,8 s | 7,0 s | 6,7 s | **7,0 s** | < 2,5 s |
| FCP | 2,9 s | 3,8 s | 2,9 s | 2,9 s | |
| TBT | 350 ms | 440 ms | 320 ms | 350 ms | |
| CLS | 0 | 0 | 0 | 0 | < 0,1 |
| Speed Index | 7,2 s | 10,8 s | 7,1 s | 7,2 s | |
| Time to Interactive | 14,9 s | 15,7 s | 12,3 s | 14,9 s | |
| Peso pagina | 2,48 MB | 2,17 MB | 2,12 MB | ~2,2 MB | |
| Accessibilità | 90 | 90 | 90 | 90 | |
| Best practice | 73 | 73 | 73 | 73 | |
| SEO | 92 | 92 | 92 | 92 | |

Cause principali (run 3):

- **93 richieste**: circa 970 KB di JavaScript, di cui circa 495 KB inutilizzati. Da soli, Google Tag Manager e i tag Google (GTM-WHFCL32F, GT-WFFZ6R7C, AW-18190401487, AW-2520295174) pesano circa 660 KB.
- **Video MP4 da 769 KB** (`Nativo_Studio_Siti_Web_04.mp4`) caricato all'apertura.
- **Font Gilroy in TTF**, 4 pesi da circa 57 KB ciascuno, senza `font-display` adeguato: circa 1,3 s di ritardo stimato sul testo.
- **CSS e JS che bloccano il rendering** (Elementor, Elementor Pro, plugin): circa 1,6 s di risparmio stimato.
- **L'elemento LCP è il banner dei cookie** (Complianz), che su mobile copre metà della prima schermata.
- Script `accounts.google.com/gsi/client` (accesso con Google, circa 100 KB) caricato senza un motivo evidente.
- Errori in console (risorse in 401 e 404), contrasto insufficiente su più elementi (numero di telefono, banner sconto, referral, "Salva contatto"), link senza nome accessibile, touch target piccoli, nessuna meta description.

---

## 2. Criticità della landing attuale

### 2.1 Credibilità (dal brief, tutte confermate)

1. **Sconto incoerente**: 15% nella barra in alto, nelle card e nel box "Sconto speciale"; 10% nel banner a metà pagina.
2. **Contatori a zero e refuso**: a schermo si legge "0 %", "0 +", "0 +", "0". I valori impostati sono 100% (progetto su misura), 10+ (anni di esperienza), 50+ (progetti realizzati) e 5 (servizi integrati). C'è anche il refuso "Progetti reralizzati".
3. **WhatsApp sbagliato**: `wa.me/33347035703` (manca il prefisso 39). Il sito ufficiale usa invece correttamente `wa.me/393347035703`.
4. **Email nel footer** `info@promo.nativo.studio`, mentre altrove è `info@nativo.studio`.
5. **REA**: "PI 2788398" sulla landing, "MI2788398" sul sito ufficiale.
6. **Processo**: annunciate 5 fasi, ma c'è una sesta card "06" che ripete il titolo della sezione.
7. **Statistiche senza fonte**: 53% (visitatori persi per lentezza) e 70% (traffico da mobile), citate due volte.
8. **"Velocità certificata"** promessa da una pagina che su mobile ha performance 55 e LCP di 7 s.

### 2.2 Credibilità (criticità aggiunte)

9. **Sede incoerente**: la landing indica via Chiesa Vecchia 35, Terricciola (Pisa); la pagina Contatti del sito ufficiale indica Viale Certosa 89, 20151 Milano. Il REA "MI" fa pensare a una sede legale a Milano.
10. **Tempi di consegna incoerenti**: la landing dice 3–4, 4–6 e 5–7 settimane; la FAQ del sito ufficiale dice "in media dalle 4 alle 8 settimane".
11. **Messaggio incoerente sui pacchetti**: la landing vende tre pacchetti con prezzo, mentre la FAQ del sito ufficiale dice "Non proponiamo soluzioni standard o pacchetti preconfezionati".
12. **Tempi di risposta**: "entro 24 ore" in più punti, "entro un giorno lavorativo" in un altro.
13. **Telefono scritto in tre modi**: "+39 334 7035 703", "+39 334 703 5 703" e "+39 334 7035703".
14. **"⭐ Più richiesto"** sul pacchetto Sito + Blog: nessun dato a supporto.
15. **Portfolio non verificabile**: sei nomi in maiuscolo senza immagine visibile né link funzionante ("Clicca per visualizzare la grafica").
16. **Due sistemi privacy diversi**: la landing usa Complianz con pagine proprie, il sito ufficiale usa iubenda.
17. **Tag Google caricati prima del consenso**: GTM e tre tag Google partono all'apertura della pagina, prima che il visitatore accetti i cookie. Va verificata la conformità della configurazione (Consent Mode o meno).
18. **Due ID Google Ads diversi** (AW-18190401487 e AW-2520295174): forse uno è vecchio, e le conversioni potrebbero essere contate in modo errato.

### 2.3 Messaggio e struttura (dal brief, tutte confermate)

19. **Titolo generico** ("Un sito web di cui andare fieri"): non dice per chi, cosa si ottiene, né perché Nativo.
20. **Troppe offerte e azioni**: sconto 15%, sconto 10%, referral 150 €, salva contatto (.vcf), WhatsApp, telefono, preventivo. Nella prima schermata mobile ci sono già due pulsanti in competizione (preventivo e telefono), più la barra sconto.
21. **"Chi siamo" in fondo**, dopo il form: il punto di forza più distintivo (Matteo referente unico) arriva quando il visitatore ha già deciso.
22. **Portfolio senza contesto né risultati.**
23. **Nessuna testimonianza.**
24. **Lista tecnologie** (WordPress, WooCommerce, Shopify, Elementor Pro, Stripe…) che non parla al cliente PMI. Cita anche Shopify, mai menzionato altrove.
25. **Form poco qualificante**: nome e cognome separati, campo libero generico, nessuna domanda sul tipo di progetto.

### 2.4 Messaggio e struttura (criticità aggiunte)

26. **Fiducia nella prima schermata**: vicino alla CTA non c'è nessun elemento di fiducia (referente, tempi di risposta, prezzi chiari).
27. **Benefici annegati nelle caratteristiche**: "Google Analytics 4 integrato", "Schema markup articoli", "Ottimizzazione AI Overview" sono tecnicismi. Il cliente vuole sapere cosa cambia per lui (più contatti, meno dipendenza dal passaparola, autonomia).
28. **Elenco delle 10 province** come striscia di testo: occupa spazio senza dire nulla di nuovo.
29. **Sezione "Perché i nostri clienti tornano sempre"**: affermazione non dimostrata, e ripete i contenuti di "Ogni sito include".
30. **Due sezioni sul processo** (sei vantaggi e cinque fasi) con contenuti sovrapposti (mobile first, SEO, supporto ripetuti 2–3 volte).
31. **Banner cookie invasivo** su mobile: copre metà della prima schermata, quindi titolo e CTA non si vedono.
32. **Tema scuro con turchese e rosa saturi**: lontano dalla richiesta "prevalentemente chiaro" e meno leggibile; diversi contrasti non superano WCAG AA.
33. **Mancano le basi SEO e di misura**: nessuna meta description, nessuna pagina di ringraziamento per tracciare le conversioni, nessun salvataggio degli UTM.
34. **Video in apertura** pesante (769 KB) per un contenuto decorativo.

---

## 3. Strategia

**Pubblico**: PMI e professionisti in Toscana con un sito vecchio, lento o che non porta contatti, oppure senza sito.

**Proposta di valore** (una frase): *un sito su misura che porta contatti, seguito da un unico referente, a prezzi chiari fin da subito.*

**Prove da mettere in fila**, in ordine di forza:
1. Prezzi "a partire da" e tempi dichiarati (rari tra gli studi, riducono l'ansia da preventivo).
2. Un unico referente, Matteo Pugi, dal brief alla consegna, con una rete di professionisti quando serve.
3. Progetti reali, con tipo di sito e contesto.
4. Cosa è incluso: SEO di base, mobile, formazione al CMS, supporto dopo il lancio.
5. Presenza sul territorio toscano.
6. La landing stessa: veloce, chiara, curata. È la prima prova del lavoro.

**Una sola offerta**: sconto del [X]% se il preventivo viene accettato entro 10 giorni dal ricevimento. Compare sempre con la stessa percentuale e la stessa formula: nei pacchetti, nel form e nella CTA finale. Il referral e il "salva contatto" vengono tolti.
<!-- DA VERIFICARE: percentuale dello sconto (oggi 15% o 10%) e se la scadenza resta "10 giorni dal ricevimento del preventivo". -->

**Una sola azione principale**: "Richiedi il preventivo gratuito", che porta sempre al form. Telefono e WhatsApp restano un'alternativa secondaria, visivamente meno forte (link testuale, non pulsante pieno).

**Tono**: diretto, concreto, senza superlativi. Si usa "noi" per lo studio, ma si nomina Matteo come persona che risponde e segue il progetto. Nessuna statistica senza fonte.

---

## 4. Nuova struttura sezione per sezione, con testi completi

Prima di tutto, le scelte trasversali:

- **Niente menu di navigazione**: su una landing per campagne ogni link in uscita è una conversione persa. L'header contiene solo logo, CTA e telefono.
- **CTA ripetute** dopo ogni blocco persuasivo, con testo legato alla sezione; portano tutte al form.
- **Barra CTA fissa in basso su mobile**, che compare dopo la hero e sparisce quando il form è visibile: su 390 px la CTA resta sempre a portata di pollice senza coprire contenuti.
- **Test A/B** solo su titolo hero e testo della CTA principale (`?v=b`).

### 4.0 Header

**Perché**: riconoscibilità del brand e accesso immediato al contatto, senza vie di fuga.

- Logo Nativo Studio (SVG, non cliccabile verso altri siti)
- Pulsante: **Preventivo gratuito**
- Su mobile: icona telefono con etichetta accessibile "Chiama Nativo Studio"

### 4.1 Hero

**Perché**: in 3–5 secondi chi arriva da un annuncio deve capire cosa ottiene, per chi è e cosa fare. Titolo orientato al risultato, sottotitolo con i tre differenziatori, CTA visibile nella prima schermata a 390 px e un elemento di fiducia subito sotto. Niente video né foto pesanti: la composizione è tipografica, con un piccolo ritratto di Matteo come segnale umano.

Occhiello: **Siti web per PMI e professionisti in Toscana**

Titolo, variante A (beneficio):
> **Un sito su misura che porta richieste, non solo visite.**

Titolo, variante B (problema):
> **Il tuo sito è vecchio, lento o non porta contatti? Rifacciamolo insieme.**

Sottotitolo:
> Progettiamo il tuo sito da zero, ottimizzato per Google e per lo smartphone. Ti segue un unico referente dal primo incontro alla messa online, con prezzi chiari fin da subito: da 1.100 €.

CTA principale, variante A: **Richiedi il preventivo gratuito**
CTA principale, variante B: **Ricevi una valutazione gratuita**

Link secondario: **oppure chiama il 334 703 5703**

Elemento di fiducia sotto la CTA (ritratto di Matteo + tre punti):
> Ti risponde Matteo Pugi, entro un giorno lavorativo · Preventivo gratuito e senza impegno · Prezzi e tempi dichiarati
<!-- DA VERIFICARE: foto di Matteo da usare (ritratto quadrato, almeno 200×200 px). -->
<!-- DA VERIFICARE: tempo di risposta da garantire ("entro un giorno lavorativo" o "entro 24 ore"). -->

### 4.2 I problemi del cliente

**Perché**: chi si riconosce in un problema è più disposto ad ascoltare la soluzione. Quattro segnali concreti, scritti con le parole del cliente e senza statistiche non verificabili.

Titolo: **Il tuo sito ti sta facendo perdere clienti?**
Sottotitolo: Se ti riconosci in almeno uno di questi segnali, è il momento di parlarne.

1. **È lento ad aprirsi.** Chi non aspetta torna su Google e sceglie un altro. E Google tende a premiare i siti veloci.
2. **Sullo smartphone si vede male.** Testi minuscoli, pulsanti difficili da toccare: proprio lì dove molti tuoi clienti ti cercano.
3. **Su Google non ti trova nessuno.** Se compari solo cercando il tuo nome, esisti solo per chi ti conosce già.
4. **Riceve visite, ma nessuno scrive.** Senza un percorso chiaro verso il contatto, i visitatori se ne vanno senza lasciare traccia.

Variante per chi non ha un sito (riga sotto le card):
> **Non hai ancora un sito?** Partiamo da zero, con le idee chiare su cosa deve fare per te.

### 4.3 La soluzione: cosa ottieni

**Perché**: dopo il problema serve subito la risposta, tradotta in benefici e non in tecnicismi. Qui si raccoglie tutto ciò che è incluso in ogni sito, così i pacchetti possono restare snelli.

Titolo: **Un sito costruito per farti contattare**
Sottotitolo: Ogni sito parte da una domanda: cosa deve portare alla tua attività? La risposta guida ogni scelta di struttura, design e tecnologia.

Incluso in ogni sito:
1. **Progettato su misura.** Nessun template preconfezionato: struttura e grafica nascono dalla tua attività e dal tuo pubblico.
2. **Pensato prima per lo smartphone.** Progettiamo da mobile a desktop, perché è lì che ti cercano più spesso.
3. **Pronto per Google.** L'ottimizzazione SEO di base è inclusa fin dall'inizio, non è un extra.
4. **Fatto per generare contatti.** Form, pulsanti e percorsi pensati per trasformare le visite in richieste.
5. **Lo aggiorni da solo.** Ti consegniamo il sito su WordPress con una sessione di formazione: testi, immagini e pagine li modifichi tu.
6. **Non ti lasciamo dopo il lancio.** Restiamo disponibili per aggiornamenti, correzioni e miglioramenti.
7. **Misuri i risultati.** Statistiche delle visite configurate, così sai quante persone arrivano e quante ti contattano.

CTA: **Scopri quanto costa il tuo sito** (scorre ai pacchetti)

### 4.4 Pacchetti e prezzi

**Perché**: prezzo e tempi sono la prima domanda di chi valuta un preventivo. Dichiararli filtra i contatti non in target e qualifica gli altri. L'offerta compare qui, una sola volta per card, con la stessa formula. La CTA di ogni card preseleziona il tipo di progetto nel form.

Titolo: **Tre punti di partenza, un progetto su misura**
Sottotitolo: Prezzi e tempi chiari fin da subito. Il preventivo finale dipende da contenuti e funzioni che servono davvero a te.

**Sito Vetrina**: ideale per presentare l'attività
- Presenta azienda, servizi e team in modo professionale, con un form per ricevere richieste.
- Incluso: design su misura · mobile first · SEO di base · form di contatto · statistiche visite · formazione per gestirlo in autonomia
- **A partire da 1.100 €** · Consegna in 3–4 settimane
- CTA: **Preventivo per un sito vetrina**

**Sito + Blog**: ideale per farsi trovare su Google nel tempo
- Tutto il Sito Vetrina, più un blog per pubblicare articoli che portano visite dai motori di ricerca.
- Incluso: blog con categorie e tag · strategia delle parole chiave · newsletter integrata · articoli ottimizzati per Google e per le risposte dell'AI
- **A partire da 1.600 €** · Consegna in 4–6 settimane
- CTA: **Preventivo per sito + blog**
<!-- DA VERIFICARE: l'etichetta "Più richiesto" sul pacchetto Sito + Blog si può tenere solo se è vera. -->

**E-commerce**: ideale per vendere online
- Negozio online completo, per vendere in Italia e all'estero 24 ore su 24.
- Incluso: catalogo prodotti illimitato · pagamenti con carta e PayPal · gestione ordini e spedizioni · coupon e promozioni · SEO per e-commerce · pannello di gestione semplice
- **A partire da 2.700 €** · Consegna in 5–7 settimane
- CTA: **Preventivo per un e-commerce**

Riga sotto le card (offerta unica):
> **Sconto del [X]%** se accetti il preventivo entro 10 giorni dal ricevimento.

Nota sotto l'offerta:
> Prezzi IVA esclusa. Hosting e dominio a parte: possiamo gestirli noi o usare quelli che hai già.
<!-- DA VERIFICARE: prezzi IVA esclusa o inclusa? -->
<!-- DA VERIFICARE: tempi di consegna (la landing dice 3–4 / 4–6 / 5–7 settimane, il sito ufficiale "4–8 settimane"). -->
<!-- DA VERIFICARE: hosting e dominio sono esclusi dal prezzo? -->

### 4.5 Progetti realizzati

**Perché**: dopo il prezzo serve la prova che il lavoro vale quella cifra. Ogni progetto mostra un'immagine leggera (WebP/AVIF, lazy load), il tipo di sito e il contesto in una riga. Niente "clicca per visualizzare".

Titolo: **Progetti reali, per attività reali**
Sottotitolo: Alcuni dei siti che abbiamo progettato e realizzato.

| Progetto | Tipo di sito (dalle fonti) | Contesto |
|---|---|---|
| Addon | E-commerce | <!-- DA VERIFICARE: settore, obiettivo, link, immagine --> |
| B&GB Bergamo | Sito vetrina + area riservata | <!-- DA VERIFICARE --> |
| Jambo Tour | Sito vetrina + e-commerce | <!-- DA VERIFICARE --> |
| Igor Meloni | Sito vetrina | <!-- DA VERIFICARE --> |
| Zaini SpA | Sito vetrina | <!-- DA VERIFICARE --> |
| Ambrosiano | Sito vetrina | <!-- DA VERIFICARE --> |

Schema della card: **[Nome]** · [Tipo di sito] · "[Settore], [città]. [Obiettivo del progetto in una frase]."
<!-- DA VERIFICARE: quali progetti sono di Nativo Studio e quali del periodo ADP Agency, e se si possono mostrare con il nome del cliente. -->
<!-- DA VERIFICARE: eventuali risultati misurabili (solo se documentati). Altrimenti niente numeri. -->

CTA: **Voglio un sito così per la mia attività**

### 4.6 Referente unico e metodo

**Perché**: è il differenziatore più forte rispetto alle agenzie (un interlocutore, nessun rimbalzo) e rispetto ai freelance (una rete di professionisti quando serve). Spostarlo dal fondo a qui, subito dopo le prove, risponde alla domanda "con chi lavorerò?". Il metodo in 5 fasi (solo 5, senza la card ripetuta) riduce l'incertezza.

Titolo: **Un solo referente, dall'inizio alla fine**

Testo:
> Ogni progetto è seguito direttamente da **Matteo Pugi**, dal primo incontro alla consegna. Niente passaggi di mano, niente account che cambiano: parli sempre con chi progetta il tuo sito.
>
> Quando il progetto lo richiede, Matteo coordina una rete selezionata di professionisti: sviluppatori, copywriter e specialisti SEO. Una sola regia, le competenze giuste.
>
> Prima di fondare Nativo Studio in Toscana, Matteo ha lavorato per anni a Milano nella comunicazione, nel marketing e nei progetti digitali, affiancando aziende di settori molto diversi: dal vino al food, dal turismo rurale ai servizi professionali.

<!-- DA VERIFICARE: numero di anni di esperienza (il contatore della landing dice 10+) e numero di progetti (50+). Si mostrano solo se confermati. -->
<!-- DA VERIFICARE: foto di Matteo al lavoro o ritratto per questa sezione. -->

Sottotitolo del metodo: **Come lavoriamo: sai sempre a che punto siamo**

1. **Chiamata conoscitiva.** Capiamo obiettivi, clienti e concorrenti. È gratuita e senza impegno.
2. **Struttura.** Progettiamo le pagine e il percorso dei visitatori prima di pensare alla grafica.
3. **Design e sviluppo.** Realizziamo il sito con il tuo brand e i tuoi contenuti. Approvi ogni fase prima di passare alla successiva.
4. **Test.** Controlliamo il sito su smartphone, tablet e computer, insieme a velocità e SEO tecnica.
5. **Lancio e formazione.** Mettiamo il sito online, ti insegniamo a gestirlo e restiamo a disposizione.

CTA: **Fissa la chiamata conoscitiva gratuita** (porta al form)

### 4.7 Testimonianze

**Perché**: la prova sociale è la leva di fiducia più forte, ma solo se reale. Nelle fonti non ci sono testimonianze, quindi la sezione viene predisposta nel tema e **non si mostra finché è vuota**: una sezione vuota o inventata farebbe più danni dell'assenza.

Titolo (quando ci saranno contenuti): **Cosa dicono i clienti**
Schema: citazione breve · nome e cognome · ruolo e attività · tipo di sito realizzato
<!-- DA VERIFICARE: testimonianze reali con nome, attività e autorizzazione alla pubblicazione; in alternativa recensioni del profilo Google di Nativo Studio, se presenti. -->

### 4.8 Domande frequenti

**Perché**: ogni obiezione non risolta è un motivo per rimandare. Le risposte riprendono quelle delle fonti, rese coerenti tra loro. Accordion nativo `<details>`, senza JavaScript.

Titolo: **Le domande che ci fanno più spesso**

**Quanto costa un sito?**
Dipende da cosa deve fare. I prezzi di partenza sono 1.100 € per un sito vetrina, 1.600 € per un sito con blog e 2.700 € per un e-commerce. Dopo la chiamata conoscitiva ricevi un preventivo dettagliato, gratuito e senza impegno.

**Quanto tempo ci vuole?**
Sito vetrina 3–4 settimane, sito con blog 4–6 settimane, e-commerce 5–7 settimane. I tempi dipendono anche da quanto velocemente arrivano testi, immagini e riscontri: li concordiamo all'inizio e li rispettiamo.

**Potrò modificare il sito da solo?**
Sì. Il sito è realizzato su WordPress e la consegna include una sessione di formazione: testi, immagini, pagine e articoli li aggiorni in autonomia. Per gli aggiornamenti tecnici e la sicurezza puoi affidarti al nostro servizio di assistenza.

**Hosting e dominio sono inclusi?**
Possiamo gestirli noi oppure lavorare su quelli che hai già. Durante la chiamata conoscitiva ti consigliamo la soluzione più adatta.

**E se il risultato non mi piace?**
Non ti presentiamo il sito finito a sorpresa: lavoriamo per fasi e approvi ogni passaggio. Sono inclusi giri di revisione per arrivare al risultato che hai in mente prima della consegna.
<!-- DA VERIFICARE: numero di giri di revisione inclusi, se si vuole indicarlo. -->

**Il sito sarà visibile su Google?**
Sì. Ogni sito include l'ottimizzazione SEO di base: struttura delle pagine, testi e metadati, velocità e versione mobile. Se vuoi scalare le posizioni in un mercato competitivo, possiamo affiancarti con un percorso SEO continuativo.

**Ho già un sito: perderò il posizionamento su Google?**
No, se il rifacimento segue la procedura corretta. Analizziamo le pagine indicizzate e il traffico esistente e impostiamo i reindirizzamenti, per non perdere visibilità e, dove possibile, migliorarla.

**Come funziona il preventivo?**
Compili il form: Matteo ti risponde entro un giorno lavorativo con una prima valutazione. Se il progetto è adatto, fissiamo una breve chiamata gratuita e poi ti mandiamo un preventivo dettagliato, senza impegno.

### 4.9 Form e CTA finale

**Perché**: è il punto di conversione. Pochi campi, un campo qualificante (tipo di progetto), rassicurazioni vicino al pulsante, offerta ripetuta. Telefono e WhatsApp come alternativa per chi non vuole scrivere.

Titolo: **Raccontaci il tuo progetto**
Sottotitolo: Ricevi una prima valutazione entro un giorno lavorativo. È gratuita e senza impegno.

Campi:
- **Nome e cognome** *
- **Email** *
- **Telefono** * <!-- DA VERIFICARE: telefono obbligatorio o facoltativo? Obbligatorio qualifica di più, facoltativo converte di più. -->
- **Che sito ti serve?** * (Sito vetrina / Sito + Blog / E-commerce / Non lo so ancora)
- **Vuoi aggiungere qualcosa?** (facoltativo). Placeholder: "Es. che lavoro fai, se hai già un sito, entro quando ti serve"
- ☐ **Ho letto l'informativa privacy e acconsento al trattamento dei dati per ricevere una risposta.** *
- Campi nascosti: utm_source, utm_medium, utm_campaign, utm_term, utm_content, variante A/B

Pulsante: **Invia la richiesta**

Sotto il pulsante:
> Preventivo gratuito e senza impegno · **Sconto del [X]%** se accetti entro 10 giorni

Riquadro alternativo accanto al form (sotto, su mobile):
> **Preferisci parlarne a voce?**
> Chiama Matteo al **334 703 5703** o scrivigli su **WhatsApp**.
> Email: info@nativo.studio

### 4.10 Footer

**Perché**: dati societari corretti e coerenti sono un segnale di serietà, e un obbligo.

> **Nativo Studio**, studio creativo indipendente per il web e la comunicazione
> [Indirizzo] · P. IVA 14506620963 · REA [numero] · SDI W7YVJK9
> info@nativo.studio · +39 334 703 5703
> Privacy policy · Cookie policy · Gestisci consenso
<!-- DA VERIFICARE: indirizzo da mostrare (via Chiesa Vecchia 35, Terricciola, Pisa, oppure Viale Certosa 89, Milano) e REA corretto (PI 2788398 o MI2788398). -->
<!-- DA VERIFICARE: privacy e cookie policy: Complianz (landing attuale) o iubenda (sito ufficiale)? -->

### 4.11 Pagina /grazie/

**Perché**: conferma la richiesta, riduce l'ansia post-invio e serve come pagina di conversione per le campagne.

Titolo: **Grazie, la tua richiesta è arrivata!**
Testo:
> Matteo ti risponderà entro un giorno lavorativo all'indirizzo email che hai indicato. Ti abbiamo appena inviato una conferma: se non la trovi, controlla nella posta indesiderata.
>
> Hai fretta? Chiama il **334 703 5703** o scrivi su **WhatsApp**.

Link: **Torna alla pagina**

### 4.12 Email di conferma automatica

Oggetto: **Abbiamo ricevuto la tua richiesta. Nativo Studio**
> Ciao [nome],
> grazie per averci scritto. Ho ricevuto la tua richiesta per [tipo di progetto] e ti risponderò entro un giorno lavorativo con una prima valutazione.
> Se nel frattempo vuoi aggiungere dettagli, ti basta rispondere a questa email.
>
> A presto,
> Matteo Pugi, Nativo Studio
> info@nativo.studio · +39 334 703 5703

### Elementi tolti, e perché

| Elemento | Motivo |
|---|---|
| Barra sconto in alto | Un'offerta sola, mostrata dove serve (pacchetti, form). |
| Sconto 10% / referral 150 € | Offerte in competizione che disperdono l'attenzione. |
| Striscia delle 10 province | Spazio senza informazione; la Toscana è già nell'occhiello. |
| Lista tecnologie | Non parla al cliente PMI. |
| Statistiche 53% e 70% | Senza fonte. |
| Contatori | Valori non verificati, e senza JS a volte mostrano "0". |
| "Perché i nostri clienti tornano sempre" | Affermazione non dimostrata, contenuti già presenti in "Cosa ottieni". |
| Salva contatto (.vcf) | Azione a basso valore che compete con il preventivo. |
| Video in apertura | Pesante e decorativo. |

---

## 5. Palette proposta

Colori ricavati dal CSS di nativo.studio (colori globali di Elementor) e dal logo (monocromatico, grigio antracite #30302E): antracite **#30302E**, grigio chiaro **#F5F5F5**, turchese **#00FFD1** (accento), rosa **#F2295B** e arancio **#FF5300 / #FF6F31** (secondari). La proposta li riporta su fondo chiaro, con accenti dosati.

| Ruolo | Colore | Uso | Contrasto |
|---|---|---|---|
| Testo e titoli | `#30302E` antracite | Testo principale, titoli, fondo della sezione CTA finale | 13,2:1 su bianco ✅ |
| Testo secondario | `#5C5C57` | Sottotitoli, note | 6,7:1 su bianco ✅ (il #7A7A7A attuale è 4,3:1 ❌) |
| Sfondo | `#FFFFFF` | Base | |
| Sfondo alternato | `#F6F6F3` | Sezioni alterne, card | testo 12,2:1 ✅ |
| Bordi | `#E3E3DE` | Card, separatori, campi del form | (decorativo) |
| Accento brand | `#00FFD1` turchese | Riempimento del pulsante principale con testo #30302E, evidenziazioni, icone | testo antracite su turchese 10,2:1 ✅ |
| Accento scuro | `#00735F` verde petrolio | Link e testo d'accento su fondo chiaro | 5,8:1 su bianco ✅ |
| Offerta | `#C4183F` rosa scuro | Solo per lo sconto (testo o etichetta) | 5,9:1 su bianco ✅ (il #F2295B con testo bianco è 4,0:1 ❌) |

Regole d'uso:
- Il turchese su bianco ha contrasto 1,3:1, quindi non va mai usato per il testo e non può essere l'unico confine del pulsante: il pulsante principale avrà un bordo antracite da 2 px, così è riconoscibile anche su fondo bianco.
- Il rosa compare solo per l'offerta, così diventa un segnale riconoscibile.
- L'arancio non si usa, per restare entro due accenti.
<!-- DA VERIFICARE: approvazione della palette e conferma che turchese e rosa sono i colori di brand da tenere. -->

## 6. Font proposti

Il brand usa oggi **Gilroy** (commerciale, file TTF caricati sul sito), con Roboto e Roboto Slab come font di Elementor di fallback.

**Proposta A (consigliata): Plus Jakarta Sans + Inter**
- **Plus Jakarta Sans** (titoli, 600 e 700): geometrico come Gilroy e come il logo, ma con più carattere. Licenza libera OFL.
- **Inter** (testo, 400 e 600): molto leggibile anche a 16 px su mobile. Licenza libera OFL.
- Entrambi self-hosted in woff2, solo i sottoinsiemi latini. In preload solo il titolo 700 e il testo 400, cioè quelli della prima schermata (circa 50–60 KB in tutto).

**Proposta B: Gilroy + Inter**, solo se la licenza di Gilroy copre l'uso web (webfont). Massima continuità con il brand, ma è da convertire in woff2 e il peso è simile.
<!-- DA VERIFICARE: licenza webfont di Gilroy (proposta B) oppure via libera a Plus Jakarta Sans (proposta A). -->

---

## 7. Cosa devi verificare

**Offerta e prezzi**
- [ ] Percentuale dello sconto (15% o 10%) e scadenza "10 giorni dal ricevimento del preventivo".
- [ ] Prezzi "a partire da" (1.100 / 1.600 / 2.700 €) ancora validi; IVA inclusa o esclusa.
- [ ] Hosting e dominio esclusi dal prezzo?
- [ ] Tempi di consegna: 3–4 / 4–6 / 5–7 settimane (landing) o 4–8 settimane (sito ufficiale).
- [ ] Etichetta "Più richiesto" sul pacchetto Sito + Blog: è vera?
- [ ] Numero di giri di revisione inclusi (se vuoi indicarlo).
- [ ] Coerenza con la FAQ del sito ufficiale ("non proponiamo pacchetti preconfezionati").

**Dati societari e contatti**
- [ ] Indirizzo da mostrare: Terricciola (Pisa) o Milano, Viale Certosa 89.
- [ ] REA corretto: PI 2788398 o MI2788398.
- [ ] Tempo di risposta da promettere: "entro un giorno lavorativo" o "entro 24 ore".
- [ ] Numero WhatsApp: +39 334 703 5703 (stesso del telefono)?
- [ ] Il telefono nel form è obbligatorio o facoltativo?

**Prove e contenuti**
- [ ] Per ognuno dei 6 progetti (Addon, B&GB Bergamo, Jambo Tour, Igor Meloni, Zaini SpA, Ambrosiano): settore, città, obiettivo, link, immagine, autorizzazione a pubblicarlo, e se è un lavoro Nativo Studio o del periodo ADP Agency.
- [ ] Risultati misurabili dei progetti (solo se documentati).
- [ ] Testimonianze reali, con nome, attività e consenso; oppure recensioni Google.
- [ ] Anni di esperienza (10+?) e numero di progetti (50+?): da mostrare solo se confermati.
- [ ] Foto di Matteo per hero e sezione referente.
- [ ] Logo in SVG (oggi c'è solo il PNG).

**Design**
- [ ] Palette proposta (sezione 5).
- [ ] Font: proposta A (Plus Jakarta Sans + Inter) o B (Gilroy, se la licenza lo consente).

**Tracciamento e privacy**
- [ ] Quale ID Google Ads è attivo: AW-18190401487 o AW-2520295174? E il tag GT-WFFZ6R7C?
- [ ] GTM-WHFCL32F resta il contenitore da usare?
- [ ] Gestione dei cookie: Complianz (landing attuale) o iubenda (sito ufficiale), e quali testi di privacy e cookie policy usare.
- [ ] Testo del consenso privacy nel form.

**Da sistemare fuori dalla landing (sito ufficiale)**
- [ ] Il link "Realizzazione Siti Web" punta a `/servizi/realizzazione-siti-web-toscana/%20` (spazio finale, passa da un redirect 301).
- [ ] REA e indirizzo da rendere coerenti con la landing.

---

## 8. Revisione: stile più marketing e orientato alla CTA (proposta del 19/10/2026)

Obiettivo: una pagina più energica, con le CTA che si trovano subito, frasi brevi alla seconda persona e lo stesso fondo chiaro. Nessuna urgenza finta: niente countdown, "posti limitati" o numeri non verificabili.

### 8.1 Grafica

**Colori: ogni colore ha un solo compito**

| Ruolo | Colore | Uso | Contrasto |
|---|---|---|---|
| CTA | `#FF5300` arancio (dal CSS di nativo.studio) | **Solo** pulsanti di azione | testo `#1A1A19` su arancio 5,4:1 ✅; pulsante su bianco 3,2:1 ✅ (soglia 3:1 per i componenti) |
| Parole chiave e bande | `#00FFD1` turchese | Evidenziatore pieno nei titoli, spunte, bande colorate | testo antracite su turchese 10,2:1 ✅ |
| Testo e bande scure | `#30302E` antracite | Testo, banda dell'offerta, banda del form | 13,2:1 su bianco ✅ |
| Sfondi | `#FFFFFF` / `#F6F6F3` | Alternanza delle sezioni | |

Il rosa dell'offerta viene tolto: lo sconto vive in una banda antracite con la percentuale in turchese grande. Così l'arancio resta l'unico colore "da cliccare".

**Tipografia**
- Titoli in Gilroy **Bold (700)** al posto del SemiBold (600), più grandi: H1 fino a 80 px su desktop e 43 px a 390 px. Restano due file di font (400 e 700), circa 40 KB in tutto.
- Una o due parole chiave per titolo con evidenziatore turchese pieno.

**CTA**
- Pulsante arancio più grande (altezza 60 px), testo orientato al beneficio, freccia.
- Hover evidente: fondo scuro, testo bianco, leggero sollevamento e freccia che avanza (200 ms; senza movimento con `prefers-reduced-motion`).
- Sotto ogni CTA principale la riga anti-rischio: **Gratis · Senza impegno · Risposta entro 24 ore**.
- Su mobile, una barra fissa in basso con CTA e telefono: compare dopo la hero e sparisce quando il form è a schermo.

**Ritmo delle sezioni**

| # | Sezione | Sfondo | CTA di fine sezione |
|---|---|---|---|
| 1 | Hero | bianco | Ricevi il preventivo gratis |
| 2 | Problemi | grigio chiaro, card bianche | Voglio un sito che mi porta clienti |
| 3 | Cosa ottieni | bianco, elenco breve | Scopri quanto costa il tuo sito |
| 4 | Pacchetti | grigio chiaro, prezzi in grande, "Più richiesto" in evidenza, banda antracite con lo sconto | Voglio [pacchetto] |
| 5 | Progetti | bianco | Voglio un sito così |
| 6 | Referente, metodo e numeri | banda turchese per i numeri in grande | Parla con Matteo, è gratis |
| 7 | Testimonianze (nascosta se vuota) | bianco | — |
| 8 | FAQ | grigio chiaro | Hai altre domande? Chiama Matteo |
| 9 | Form | banda antracite, form bianco | Ricevi il preventivo gratis |

### 8.2 Testi

Regole: frasi brevi, "tu", il risultato prima della caratteristica (più contatti, più clienti, più tempo), elenchi al posto dei paragrafi.

**Hero**
- Titolo A: **Il sito che ti porta [più clienti].**
- Titolo B: **Il tuo sito non porta contatti? [Rifacciamolo.]**
- Sottotitolo: Un sito su misura che ti fa trovare su Google e trasforma le visite in richieste.
- Elenco: Più richieste di preventivo, non solo visite · Un solo referente, Matteo, dall'inizio alla fine · Prezzi chiari: da [prezzo minimo dal pannello]
- CTA A: **Ricevi il preventivo gratis** · CTA B: **Ricevi una valutazione gratis**
- Riga anti-rischio: Gratis · Senza impegno · Risposta entro 24 ore
- Fiducia: foto di Matteo + "Ti risponde Matteo Pugi, fondatore di Nativo Studio" · "Preferisci chiamare? [telefono]"
- Riquadro prezzi: tre pacchetti con prezzo grande, "Più richiesto" su Sito + Blog, sconto in banda scura con percentuale e giorni letti dal pannello.

**Pacchetti** (sezione 4.4, da applicare): per ogni pacchetto prezzo grande, tempi, 4–5 voci con spunta, CTA "Preventivo gratis per …". Card "Più richiesto" in evidenza (bordo antracite, etichetta turchese). Sotto, la banda dello sconto con la frase unica: "Sconto del [X]% se accetti il preventivo entro [N] giorni dal ricevimento."

**Problemi** (approvato lo stile il 19/10/2026)
- Titolo: Il tuo sito ti sta facendo **[perdere clienti?]**
- Sottotitolo: Se ti riconosci in uno di questi segnali, è il momento di cambiare.
- È lento ad aprirsi. → Chi aspetta se ne va. E sceglie un concorrente.
- Sullo smartphone si vede male. → Proprio lì dove i tuoi clienti ti cercano di più.
- Su Google non ti trova nessuno. → Ti trova solo chi ti conosce già.
- Riceve visite, ma nessuno scrive. → Le persone arrivano e se ne vanno senza contattarti.
- Riga: **Non hai ancora un sito?** Partiamo da zero, con le idee chiare.
- CTA (dal 19/10/2026): blocco "**Hai già un sito?** Lascia l'indirizzo: Matteo lo analizza gratis e ti dice cosa lo frena." con **Analizza gratis il mio sito** + riga anti-rischio. Porta all'inizio del modulo, evidenzia il campo del sito e segna la richiesta come "Analisi gratuita del sito".
- Riga "Non hai ancora un sito?" con link testuale **Richiedi il preventivo gratis**.

**Cosa ottieni**
- Titolo: Un sito costruito per **[farti contattare]**
- Sottotitolo: Tutto quello che serve per trasformare le visite in clienti. Già incluso.
- Su misura. → Niente template: il sito nasce dalla tua attività.
- Perfetto su smartphone. → Dove i tuoi clienti ti cercano più spesso.
- Pronto per Google. → SEO di base incluso, non è un extra.
- Fatto per farti contattare. → Pulsanti e percorsi che portano dritti alla richiesta.
- Lo aggiorni da solo. → WordPress e formazione inclusi: testi e foto li cambi tu.
- Non resti solo dopo il lancio. → Aggiornamenti, correzioni e miglioramenti.
- Vedi i risultati. → Sai quante persone arrivano e quante ti scrivono.
- Prezzo in grande: "a partire da [prezzo minimo dal pannello]"
- CTA: **Scopri quanto costa il tuo sito** (scorre ai pacchetti) + riga anti-rischio

**Pacchetti**
- Titolo: Prezzi chiari. **[Nessuna sorpresa.]**
- Sottotitolo: Scegli il punto di partenza. Il preventivo finale dipende da quello che ti serve davvero.
- Sito Vetrina: "Per presentare la tua attività e ricevere richieste." · da [prezzo] · online in 3–4 settimane · Design su misura · Perfetto su smartphone · SEO di base per Google · Form e pulsanti per farti scrivere · Lo aggiorni da solo · CTA: **Voglio il Sito Vetrina**
- Sito + Blog (Più richiesto, card scura): "Per farti trovare su Google, articolo dopo articolo." · da [prezzo] · online in 4–6 settimane · Tutto del Sito Vetrina · Blog che porta visite da Google · Parole chiave studiate per te · Newsletter integrata · Articoli pronti per Google e per le risposte AI · CTA: **Voglio Sito + Blog**
- E-commerce: "Per vendere online, 24 ore su 24." · da [prezzo] · online in 5–7 settimane · Prodotti illimitati · Pagamenti con carta e PayPal · Ordini e spedizioni da un pannello semplice · Coupon e promozioni · SEO per e-commerce · CTA: **Voglio l'E-commerce**
- Banda scura dello sconto: −[X]% grande + la frase unica dal pannello
- Nota: Prezzi IVA esclusa. Hosting e dominio a parte: possiamo gestirli noi o usare quelli che hai già.

**Progetti**
- Titolo: Progetti veri, per attività **[come la tua]**
- Sottotitolo: Alcuni dei siti che abbiamo progettato e realizzato.
- Card: immagine, tipo di sito (etichetta), nome, contesto in una frase. Tutto gestito da Impostazioni landing → Progetti realizzati.
- Ai visitatori compaiono solo i progetti con immagine; senza immagini la sezione è nascosta.
- CTA: **Voglio un sito così** + riga anti-rischio

**Referente, numeri e metodo**
- Titolo: Un solo referente. **[Dall'inizio alla fine.]** · Sottotitolo: Parli sempre con chi progetta il tuo sito: Matteo.
- Punti: Nessun passaggio di mano · Una rete quando serve · Esperienza vera (Milano, poi Toscana) · Settori diversi (vino, food, turismo rurale, servizi professionali)
- Banda turchese con i 4 numeri dal pannello; un numero vuoto non compare ai visitatori.
- Metodo: Come lavoriamo: **[5 passi]**, zero sorprese (chiamata, struttura, design e sviluppo, test, lancio e formazione)
- CTA: **Parla con Matteo, è gratis** + riga anti-rischio

**Territorio (cartina della Toscana)**
- Titolo: Lavoriamo in **[tutta la Toscana]** · Sottotitolo: Lo studio è a Terricciola, in provincia di Pisa. Seguiamo attività in tutta la regione, e non solo.
- Punti: Conosciamo il territorio (SEO locale) · Un solo referente, in qualunque provincia · Anche fuori regione
- Cartina SVG in linea (confini openpolis/ISTAT) con i 10 capoluoghi e la sede a Terricciola, segnata da un'onda leggera (ferma con movimento ridotto).
- CTA: **Voglio farmi trovare nella mia zona** + riga anti-rischio. Interruttore in Impostazioni landing.

**Testimonianze**
- Titolo: Cosa dicono **[i clienti]** · card con virgolette turchesi, testo, nome in evidenza, ruolo e azienda più piccoli, foto o logo (oppure cerchio con le iniziali nei colori della palette) · CTA: **Inizia anche tu: preventivo gratis**
- Desktop: griglia a 3 colonne. Mobile: scorrimento orizzontale in CSS (scroll-snap), senza librerie.
- Ai visitatori solo le testimonianze con "Pubblicata" attivo; nessuna pubblicata = sezione nascosta. Da loggato anche le altre, con l'etichetta "In attesa di approvazione".
- Inserite il 20/10/2026, tutte non pubblicate: Alessandro Luraghi (Unique Srl), Alessandro Fleri (Jambo Tour), Sergio Pinto (Geofelix), Emilio Picasso (Rappresentanze Industriali), Laura Viglietta (Cantine Cenéra).

**FAQ**
- Titolo: Hai dei dubbi? **[Ecco le risposte.]** · otto domande dell'analisi con risposte accorciate; prezzi e sconto letti dal pannello.
- CTA: **Chiama Matteo: [telefono]** (senza riga anti-rischio, perché è una chiamata)

**Form e CTA finale**
- Banda scura. Titolo: Ricevi il tuo **[preventivo gratis]** · Sottotitolo: Bastano 30 secondi. Matteo ti risponde entro 24 ore con una prima valutazione.
- Punti: Gratis e senza impegno · Risposta entro 24 ore · frase dello sconto dal pannello · alternativa telefono/WhatsApp
- Campi: nome e cognome, email, telefono, **il tuo sito attuale (facoltativo, "Se ce l'hai, Matteo lo analizza gratis prima di risponderti")**, che sito ti serve (4 scelte), messaggio facoltativo, consenso privacy; nascosti UTM, variante, pacchetto cliccato, tipo di richiesta.
- Barra fissa su mobile: Preventivo gratis + WhatsApp (messaggio già scritto) + telefono.
- Pulsante: **Ricevi il preventivo gratis** + riga anti-rischio. Dopo l'invio: /grazie/.

### 8.3 Punti da confermare

<!-- DA VERIFICARE: "Risposta entro 24 ore" è una promessa anche nel weekend. Le fonti dicono sia "entro 24 ore" sia "entro un giorno lavorativo". -->
<!-- DA VERIFICARE: la CTA "Ricevi il preventivo gratis in 24 ore" non è stata usata: secondo le fonti in 24 ore arriva una prima valutazione, il preventivo dettagliato arriva dopo la chiamata. Il "24 ore" resta nella riga anti-rischio, riferito alla risposta. -->
<!-- DA VERIFICARE: l'etichetta "Più richiesto" su Sito + Blog viene mostrata su tua indicazione: va bene solo se corrisponde ai dati reali. -->
