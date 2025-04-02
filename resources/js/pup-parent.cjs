const { login } = require('./pup-login.cjs');
const { cekNik } = require('./pup-nik.cjs');


(async () => {
    const email     = process.argv[2];
    const password  = process.argv[3];
    const nikList   = JSON.parse(process.argv[4]); // JSON berisi array NIK
    const inputTrx = parseInt(process.argv[5], 10);

    const browser = await login(email, password);
    await cekNik(browser, nikList, 'https://subsiditepatlpg.mypertamina.id/merchant/app/verification-nik', inputTrx);

    await browser.close();
})();
