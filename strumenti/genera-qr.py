"""
Genera il codice QR della sezione "Non è ancora il momento?" come SVG statico.
Il QR contiene l'indirizzo della vCard sul sito online (?nl_vcard=1), che legge i dati da Impostazioni landing:
se cambi un recapito non serve rigenerarlo. Va rigenerato solo se cambia il dominio.

Uso:  python3 strumenti/genera-qr.py https://promo.nativo.studio
Serve la libreria qrcode (pip install qrcode).
"""
import sys
import qrcode

dominio = (sys.argv[1] if len(sys.argv) > 1 else 'https://promo.nativo.studio').rstrip('/')
url = dominio + '/?nl_vcard=1'

qr = qrcode.QRCode(error_correction=qrcode.constants.ERROR_CORRECT_M, border=4)
qr.add_data(url)
qr.make(fit=True)
matrice = qr.get_matrix()  # bordo bianco incluso
lato = len(matrice)

# Un solo tracciato: un rettangolo per ogni sequenza di moduli scuri sulla stessa riga.
tratti = []
for y, riga in enumerate(matrice):
    x = 0
    while x < lato:
        if riga[x]:
            inizio = x
            while x < lato and riga[x]:
                x += 1
            tratti.append(f'M{inizio} {y}h{x - inizio}v1h-{x - inizio}z')
        else:
            x += 1

svg = (
    f'<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 {lato} {lato}" width="{lato * 6}" height="{lato * 6}" shape-rendering="crispEdges">'
    f'<title>Codice QR: salva il contatto di Nativo Studio ({url})</title>'
    f'<rect width="{lato}" height="{lato}" fill="#fff"/>'
    f'<path fill="#1a1a19" d="{"".join(tratti)}"/>'
    '</svg>\n'
)
open('assets/img/qr-contatto.svg', 'w').write(svg)
print(f'QR generato: {url} | versione {qr.version} | {lato}×{lato} moduli | {len(svg)} byte')
