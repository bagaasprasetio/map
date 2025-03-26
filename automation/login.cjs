const puppeteer = require('puppeteer');

const loginAction = async(email, pin) => {
    const browser = await puppeteer.launch({ headless: false });
    const page = await browser.newPage();

    try {
        await page.goto('https://subsiditepatlpg.mypertamina.id/merchant/auth/login', { waitUntil: 'networkidle2' });

        await page.waitForSelector('#mantine-r0');
        await page.type('#mantine-r0', email, { delay: 50 });

        await page.waitForSelector('#mantine-r1');
        await page.type('#mantine-r1', pin, { delay: 50 });

        await page.waitForSelector('button'); // Tunggu tombol muncul

        const buttons = await page.$$('button'); // Ambil semua tombol
        for (let button of buttons) {
            let text = await page.evaluate(el => el.innerText, button);
            if (text.includes('Masuk')) {
                await button.click();
                //console.log("Login button clicked!");
                break;
            }
        }
        
        await page.waitForSelector('.styles_iconClose__ZjGFM', { visible: true });
        await page.evaluate(() => {
            document.querySelector('.styles_iconClose__ZjGFM').click();
        });

        //await page.waitForNavigation({ waitUntil: 'networkidle2' });

        console.log('Login beress!');
        return { browser, page };

    } catch (error) {
        console.error('Gagal:', error);
        return null;
    } finally {
        //await browser.close();
    }
};

module.exports = loginAction;

/*
// Ambil data email & password dari parameter CLI
const args = process.argv.slice(2);
const email = args[0];
const pin = args[1];

if (!email || !pin) {
    console.error('Masukkan email & pin sebagai argumen!');
    process.exit(1);
}

// Jalankan fungsi login
loginAction(email, pin); */