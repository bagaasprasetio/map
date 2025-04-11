const { login } = require('./pup-login.cjs');
const { cekNik } = require('./pup-nik.cjs');

(async () => {
    const email     = process.argv[2];
    const password  = process.argv[3];
    const nikList   = JSON.parse(process.argv[4]); // JSON berisi array NIK
    const nikType   = process.argv[5];
    const URL       = process.argv[6];
    const inputTrx  = process.argv[7];


    const browser = await login(email, password);
    await cekNik(browser, nikList, URL, inputTrx, nikType);

    await browser.close();
})();


// (async () => {
//     const email     = process.argv[2];
//     const password  = process.argv[3];
//     const nikList   = JSON.parse(process.argv[4]); // JSON berisi array NIK
//     const type      = process.argv[5];
//     const URL       = process.argv[6];
//     const inputTrx  = process.argv[7];

//     console.log("✅ Script dijalankan dengan parameter berikut:");
//     console.log(`📌 Email: ${email}`);
//     console.log(`📌 Password: ${password}`);
//     console.log(`📌 NIK List:`, nikList);
//     console.log(`📌 Type: ${type}`);
//     console.log(`📌 URL: ${URL}`);
//     console.log(`📌 Input Transaksi: ${inputTrx}`);

//     console.log("⏳ Proses login dan cek NIK dilewati (tidak dijalankan).");

//     // Tidak menjalankan login atau cekNik
// })();
