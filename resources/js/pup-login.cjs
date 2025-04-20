// login.js
const puppeteer = require('puppeteer');

async function login(email, password) {
    const browser = await puppeteer.launch({
        headless: false,
        args: [
            '--disable-notifications',
            '--no-sandbox',
            '--disable-setuid-sandbox'
        ]
    });
    const page = await browser.newPage();
    const context = browser.defaultBrowserContext();
    await context.overridePermissions('https://subsiditepatlpg.mypertamina.id', ['notifications']);


    await page.goto('https://subsiditepatlpg.mypertamina.id/merchant/auth/login', { waitUntil: 'networkidle2' });
    await page.type('#mantine-r0', email,{ delay: 200 });
    await page.type('#mantine-r1', password,{ delay: 200 });
    await new Promise(resolve => setTimeout(resolve, 500));
    await page.click('button[type="submit"]');

    await new Promise(resolve => setTimeout(resolve, 2000));
    // Cek apakah field password dianggap invalid (salah)
    const isPasswordInvalid = await page.$('#mantine-r1-error');

    if (isPasswordInvalid) {
        const errorText = await page.evaluate(el => el.textContent, isPasswordInvalid);
        if (errorText.toLowerCase().includes('salah')) {
            await browser.close();

            console.log(JSON.stringify({
                success: false
            }));
    
            return;
        }
    }

    try {
        await page.waitForSelector('span[data-testid="btnClose793f59dc-3bb0-4cae-8099-ffbe06336f5e"]', { timeout: 3000 });
        await page.evaluate(() => {
            let closeButton = document.querySelector('span[data-testid="btnClose793f59dc-3bb0-4cae-8099-ffbe06336f5e"] svg path');
            if (closeButton) {
                closeButton.closest('span').click();
            }
        });
    } catch (error) {
        await page.evaluate(() => {
            let banner = document.querySelector('.banner');
            if (banner) {
                banner.style.display = 'none';
            }
        });
    }
    await new Promise(resolve => setTimeout(resolve, 10000)); // Tunggu 5 detik

    // console.log('Login berhasil');
    return browser;
}

module.exports = { login };

