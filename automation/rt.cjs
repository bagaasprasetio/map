const loginAction = require('./login.cjs');

const rtNik = async (email, pin) => {
    const session = await loginAction(email, pin);

    if (!session){
        console.error("Login gagal, tidak bisa lanjut input data.");
        return;
    }

    const { browser, page } = session;

    try {
        await page.waitForSelector('#mantine-r5');
        await page.type('#mantine-r5', '3201132312710002', { delay: 50 });
    
        const buttons = await page.$$('button'); // Ambil semua tombol
        for (let button of buttons) {
            let text = await page.evaluate(el => el.innerText, button);
            if (text.includes('Cek')) {
                await button.click();
                //console.log("Login button clicked!");
                break;
            }
        }

        await page.waitForSelector('.mantine-UnstyledButton-root.mantine-ActionIcon-root.styles_control__emoIZ', { visible: true });
        await page.evaluate(() => {
            document.querySelector('.mantine-UnstyledButton-root.mantine-ActionIcon-root.styles_control__emoIZ').click();
        });

        const buttons2 = await page.$$('button'); // Ambil semua tombol
        for (let button of buttons2) {
            let text = await page.evaluate(el => el.innerText, button);
            if (text.includes('Cek Pesanan')) {
                await button.click();
                //console.log("Login button clicked!");
                break;
            }
        }

        const buttons3 = await page.$$('button'); // Ambil semua tombol
        for (let button of buttons3) {
            let text = await page.evaluate(el => el.innerText, button);
            if (text.includes('Proses Transaksi')) {
                await button.click();
                //console.log("Login button clicked!");
                break;
            }
        }

        console.log('rt done');

    } catch (error) {
        console.error('Gagal:', error);
    } finally {
        //await Browser.close();
    }
};

const args = process.argv.slice(2);
if (args.length < 2) {
    console.error("Masukkan email dan pin sebagai argumen!");
    process.exit(1);
}

rtNik(args[0], args[1]);


