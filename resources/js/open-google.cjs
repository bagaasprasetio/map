// const puppeteer = require('puppeteer');

// (async () => {
//   const browser = await puppeteer.launch({ headless: false }); // Set ke `true` jika tidak perlu tampilan browser
//   const page = await browser.newPage();
//   await page.goto('https://www.google.com');
//   await new Promise(resolve => setTimeout(resolve, 5000)); // Tunggu 5 detik
//   await browser.close();
// })();

(async () => {
    // Menggunakan import() dinamis untuk p-limit
    const pLimit = (await import('p-limit')).default;  // Pastikan menggunakan '.default' jika module export default
    const puppeteer = require('puppeteer'); // tetap menggunakan 'require' untuk puppeteer

    const limit = pLimit(3); // Batasi maksimal 3 browser berjalan bersamaan

    async function openGoogle() {
      const browser = await puppeteer.launch({ headless: false });
      const page = await browser.newPage();
      await page.goto('https://www.google.com');
      await new Promise(resolve => setTimeout(resolve, 5000)); // Tunggu 5 detik
      await browser.close();
    }

    const totalTasks = 5; // Misalnya user memasukkan 5 tugas untuk dijalankan
    const tasks = [];

    // Loop untuk membuat totalTasks
    for (let i = 0; i < totalTasks; i++) {
      tasks.push(limit(() => openGoogle())); // Batasi dengan limit, 3 berjalan bersamaan
    }

    // Tunggu hingga semua task selesai
    await Promise.all(tasks);
  })();


