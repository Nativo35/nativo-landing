/**
 * Esporta gli annunci di ads/ in PNG con Chrome headless (puppeteer-core).
 * Uso, da una cartella con puppeteer-core installato:
 *   node /percorso/del/tema/strumenti/esporta-ads.mjs
 */
import { createRequire } from 'module';
import { fileURLToPath, pathToFileURL } from 'url';
import path from 'path';

const require = createRequire( path.join( process.cwd(), 'x.js' ) );
const puppeteer = require( 'puppeteer-core' );

const ads = path.join( path.dirname( fileURLToPath( import.meta.url ) ), '..', 'ads' );
const file = [
	[ 'annuncio-1-prezzo', 1080 ],
	[ 'annuncio-2-problema', 1080 ],
	[ 'annuncio-3-referente', 1080 ],
	[ 'logo-openai', 512 ],
];

const browser = await puppeteer.launch( {
	executablePath: process.env.CHROME || '/Applications/Google Chrome.app/Contents/MacOS/Google Chrome',
	headless: 'new',
	args: [ '--allow-file-access-from-files' ],
} );
const pagina = await browser.newPage();
for ( const [ nome, lato ] of file ) {
	await pagina.setViewport( { width: lato, height: lato, deviceScaleFactor: 1 } );
	await pagina.goto( pathToFileURL( path.join( ads, nome + '.html' ) ).href, { waitUntil: 'networkidle0' } );
	await pagina.evaluate( () => document.fonts.ready );
	const fuori = await pagina.evaluate( () => document.documentElement.scrollHeight > innerHeight || document.documentElement.scrollWidth > innerWidth );
	await pagina.screenshot( { path: path.join( ads, nome + '.png' ), clip: { x: 0, y: 0, width: lato, height: lato } } );
	console.log( nome + '.png', fuori ? '(ATTENZIONE: contenuto oltre il bordo)' : 'ok' );
}
await browser.close();
