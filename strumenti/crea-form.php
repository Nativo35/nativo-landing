<?php
/**
 * Crea (o aggiorna) il form "Preventivo landing" di Contact Form 7, con email e messaggi.
 * Uso: wp eval-file wp-content/themes/nativo-landing/strumenti/crea-form.php
 */
$titolo = 'Preventivo landing';
$form   = wpcf7_get_contact_form_by_title( $titolo ) ?: WPCF7_ContactForm::get_template( array( 'title' => $titolo ) );

$markup = <<<'FORM'
<div class="campo">
	<label for="nl-nome">Nome e cognome</label>
	[text* nome id:nl-nome autocomplete:name]
</div>
<div class="campo">
	<label for="nl-email">Email</label>
	[email* email id:nl-email autocomplete:email]
</div>
<div class="campo">
	<label for="nl-telefono">Telefono</label>
	[tel* telefono id:nl-telefono autocomplete:tel]
</div>
<div class="campo campo--sito" id="campo-sito">
	<label for="nl-sito">Il tuo sito attuale <span>(facoltativo)</span></label>
	[text sito_attuale id:nl-sito autocomplete:url placeholder "Es. www.tuosito.it"]
	<p class="campo__aiuto">Se ce l'hai, Matteo lo analizza gratis prima di risponderti.</p>
</div>
<fieldset class="campo campo--scelta">
	<legend>Che sito ti serve?</legend>
	[radio tipo use_label_element "Sito vetrina" "Sito + Blog" "E-commerce" "Non lo so ancora"]
</fieldset>
<fieldset class="campo campo--scelta campo--interessi">
	<legend>Ti interessano anche: <span>(facoltativo)</span></legend>
	[checkbox interessi use_label_element "Hosting e manutenzione" "Pacchetto blog" "Lingue aggiuntive"]
</fieldset>
<div class="campo">
	<label for="nl-messaggio">Vuoi aggiungere qualcosa? <span>(facoltativo)</span></label>
	[textarea messaggio id:nl-messaggio x3 placeholder "Es. che lavoro fai, se hai già un sito, entro quando ti serve"]
</div>
<div class="campo campo--consenso">
	[acceptance privacy] Ho letto l'<a href="/privacy-policy/" target="_blank" rel="noopener">informativa privacy</a> e acconsento al trattamento dei dati per ricevere una risposta. [/acceptance]
</div>
[hidden utm_source] [hidden utm_medium] [hidden utm_campaign] [hidden utm_term] [hidden utm_content] [hidden variante] [hidden pacchetto_cliccato] [hidden richiesta] [hidden event_id] [hidden oppref] [hidden consenso_marketing]
<div class="campo campo--invio">
	[submit class:pulsante class:pulsante--grande "Ricevi il preventivo gratis"]
</div>
FORM;

$mail = array(
	'active'             => true,
	'subject'            => 'Nuova richiesta di preventivo: [tipo] – [nome]',
	'sender'             => 'Landing Nativo Studio <wordpress@nativo.studio>',
	'recipient'          => 'info@nativo.studio',
	'body'               => "Nuova richiesta dalla landing.\n\nNome: [nome]\nEmail: [email]\nTelefono: [telefono]\nTipo di sito: [tipo]\nTi interessano anche: [interessi]\nSito attuale: [sito_attuale]\nRichiesta: [richiesta]\n\nMessaggio:\n[messaggio]\n\n--\nCampagna\nutm_source: [utm_source]\nutm_medium: [utm_medium]\nutm_campaign: [utm_campaign]\nutm_term: [utm_term]\nutm_content: [utm_content]\nVariante A/B: [variante]\nPacchetto cliccato: [pacchetto_cliccato]\nOpenAI Ads: event_id [event_id], oppref [oppref], consenso marketing [consenso_marketing]\n\nInviato il [_date] alle [_time] da [_url]",
	'additional_headers' => 'Reply-To: [nome] <[email]>',
	'attachments'        => '',
	'use_html'           => false,
	'exclude_blank'      => false,
);

$mail_2 = array(
	'active'             => true,
	'subject'            => 'Abbiamo ricevuto la tua richiesta – Nativo Studio',
	'sender'             => 'Matteo Pugi – Nativo Studio <wordpress@nativo.studio>',
	'recipient'          => '[email]',
	'body'               => "Ciao [nome],\n\ngrazie per averci scritto. Ho ricevuto la tua richiesta per: [tipo].\nTi risponderò entro 24 ore con una prima valutazione, gratuita e senza impegno.\n\nSe nel frattempo vuoi aggiungere dettagli, rispondi pure a questa email.\n\nA presto,\nMatteo Pugi\nNativo Studio\ninfo@nativo.studio · +39 334 703 5703",
	'additional_headers' => 'Reply-To: info@nativo.studio',
	'attachments'        => '',
	'use_html'           => false,
	'exclude_blank'      => false,
);

$messaggi = array_merge(
	wpcf7_messages() ? array_combine( array_keys( wpcf7_messages() ), array_column( wpcf7_messages(), 'default' ) ) : array(),
	array(
		'mail_sent_ok'             => 'Grazie! La tua richiesta è arrivata.',
		'mail_sent_ng'             => 'Qualcosa non ha funzionato. Riprova o chiamaci al 334 703 5703.',
		'validation_error'         => 'Controlla i campi evidenziati e riprova.',
		'invalid_required'         => 'Questo campo è obbligatorio.',
		'accept_terms'             => 'Per inviare la richiesta serve il consenso al trattamento dei dati.',
		'invalid_email'            => 'Controlla l\'indirizzo email.',
		'invalid_tel'              => 'Controlla il numero di telefono.',
		'spam'                     => 'Qualcosa non ha funzionato. Riprova o chiamaci al 334 703 5703.',
	)
);

$form->set_properties(
	array(
		'form'                => $markup,
		'mail'                => $mail,
		'mail_2'              => $mail_2,
		'messages'            => $messaggi,
		'additional_settings' => "flamingo_email: \"[email]\"\nflamingo_name: \"[nome]\"\nflamingo_subject: \"Preventivo: [tipo]\"\nacceptance_as_validation: on",
	)
);
$form->set_locale( 'it_IT' );
$id = $form->save();
echo "Form salvato, ID {$id}\n";
