const puppeteer = require('puppeteer');

(async () => {
  const browser = await puppeteer.launch({ headless: false }); // Set ke `true` jika tidak perlu tampilan browser
  const page = await browser.newPage();
  await page.goto('https://www.google.com');
  await new Promise(resolve => setTimeout(resolve, 5000)); // Tunggu 5 detik
  await browser.close();
})();

