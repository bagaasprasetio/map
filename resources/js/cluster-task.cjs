const { Cluster } = require('puppeteer-cluster');
const os = require('os');
const fs = require('fs');
const path = require('path');

(async () => {
  const url = process.argv[2];

  if (!url) {
    console.error('❌ URL tidak diberikan.');
    process.exit(1);
  }

  // Buat folder sementara unik untuk userDataDir
  const tempDir = fs.mkdtempSync(path.join(os.tmpdir(), 'puppeteer-user-'));

  const cluster = await Cluster.launch({
    concurrency: Cluster.CONCURRENCY_PAGE,
    maxConcurrency: 3,
    puppeteerOptions: {
      headless: true,  // Jangan tampilkan browser
      args: ['--no-sandbox', '--disable-setuid-sandbox'],
      userDataDir: tempDir, // Set unique userDataDir untuk setiap task
    },
  });

  await cluster.task(async ({ page, data }) => {
    await page.goto(data, { waitUntil: 'domcontentloaded' });
    const title = await page.title();
    console.log(`✅ ${data} => ${title}`);
    await new Promise(r => setTimeout(r, 5000)); // Simulasi waktu berjalan
  });

  await cluster.queue(url);

  await cluster.idle();
  await cluster.close();

  // Hapus temp dir setelah selesai
  fs.rmSync(tempDir, { recursive: true, force: true });
})();
