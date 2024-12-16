const fs = require('fs');
const puppeteer = require('puppeteer');
const [, , ...args] = process.argv;

const run = async function () {
  try {
    const post = JSON.parse(args[0]);
    const html = fs.readFileSync(post.sourceFile);

    const pdf = await generatePDF(html.toString(), {
      ignoreHttpsErrors: post.ignoreHttpsErrors,
      chromeExecutable: post.chromeExecutable,
      format: post.format ?? 'A4',
      margin: { top: '20mm', bottom: '20mm', left: '10mm', right: '10mm' },
      landscape: !!post.landscape,
      displayHeaderFooter: true,
      headerTemplate: post.headerTemplate ?? '',
      footerTemplate: post.footerTemplate ?? ''
    });

    fs.writeFileSync(post.targetFile, pdf);
    process.exit(0);
  }
  catch (exception) {
    console.error(exception.status);
    console.log(exception.toString());
    process.exit(1);
  }
}

/**
 * Generate PDF with puppeteer
 * @param {string} html
 * @param {object} options
 * @returns {Promise<*>}
 */
async function generatePDF(html, options) {
  const browser = await puppeteer.launch({
    headless: true,
    ignoreHTTPSErrors: options.ignoreHttpsErrors,
    executablePath: options.chromeExecutable,
    args: getPuppeteerArgs()
  });
  const page = await browser.newPage();
  await page.setContent(html, { waitUntil: 'networkidle0' });
  const pdf = await page.pdf(options);
  await browser.close();
  return pdf;
}

/**
 * Get arguments for launching puppeteer
 * @see https://apitemplate.io/blog/tips-for-generating-pdfs-with-puppeteer/
 * @returns {string[]}
 */
function getPuppeteerArgs() {
  return [
    '--disable-features=IsolateOrigins',
    '--disable-site-isolation-trials',
    '--autoplay-policy=user-gesture-required',
    '--disable-background-networking',
    '--disable-background-timer-throttling',
    '--disable-backgrounding-occluded-windows',
    '--disable-breakpad',
    '--disable-client-side-phishing-detection',
    '--disable-component-update',
    '--disable-default-apps',
    '--disable-dev-shm-usage',
    '--disable-domain-reliability',
    '--disable-extensions',
    '--disable-features=AudioServiceOutOfProcess',
    '--disable-hang-monitor',
    '--disable-ipc-flooding-protection',
    '--disable-notifications',
    '--disable-offer-store-unmasked-wallet-cards',
    '--disable-popup-blocking',
    '--disable-print-preview',
    '--disable-prompt-on-repost',
    '--disable-renderer-backgrounding',
    '--disable-setuid-sandbox',
    '--disable-speech-api',
    '--disable-sync',
    '--hide-scrollbars',
    '--ignore-gpu-blacklist',
    '--metrics-recording-only',
    '--mute-audio',
    '--no-default-browser-check',
    '--no-first-run',
    '--no-pings',
    '--no-sandbox',
    '--no-zygote',
    '--password-store=basic',
    '--use-gl=swiftshader',
    '--use-mock-keychain'
  ];
}


if (require.main === module) {
  run();
}

exports.run = run;