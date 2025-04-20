const { Cluster } = require('puppeteer-cluster');

(async () => {
  const url = process.argv[2]; // Ambil URL dari CLI argumen

  if (!url) {
    console.error('❌ URL tidak diberikan.');
    process.exit(1);
  }

  const cluster = await Cluster.launch({
    concurrency: Cluster.CONCURRENCY_PAGE,
    maxConcurrency: 3,
    puppeteerOptions: {
      headless: false,
      args: ['--no-sandbox', '--disable-setuid-sandbox'],
    },
  });

  await cluster.task(async ({ page, data }) => {
    await page.goto(data, { waitUntil: 'domcontentloaded' });

    const title = await page.title();
    console.log(`✅ ${data} => ${title}`);

    // Delay 5 detik sebelum close tab
    await new Promise(resolve => setTimeout(resolve, 5000));

    await page.close();
  });

  await cluster.queue(url);

  await cluster.idle();
  await cluster.close();
})();
