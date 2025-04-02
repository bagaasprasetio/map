// Fungsi reusable untuk klik dengan delay
async function clickWithDelay(page, selector, description, delay = 500) {
    try {
        await page.waitForSelector(selector, { visible: true, timeout: 2000 });
        console.log(`✅ Mengklik tombol: ${description}`);
        await new Promise(resolve => setTimeout(resolve, delay));
        await page.click(selector);
    } catch (error) {
        console.error(`⚠️ Gagal mengklik tombol: ${description}`, error);
    }
}


async function cekNik(browser, nikList, redirectBackURL) {
    const pages = await browser.pages();
    const page = pages.length > 1 ? pages[1] : await browser.newPage();
    let validNikList = [];

    for (let nik of nikList) {

        if(validNikList.length >= inputTrx) break; // Stop jika jumlah NIK valid sudah cukup

        try {
            nik = nik?.toString().trim(); // Pastikan `nik` string dan trim whitespace

            if (!nik) {
                console.error("❌ Error: NIK kosong setelah trim!");
                continue;
            }
            // Pastikan input sudah ada
            await page.waitForSelector('.mantine-Input-input.mantine-Autocomplete-input.mantine-f5preq', { visible: true, timeout: 1000 });

            // Cek apakah elemen ada
            const isInputExist = await page.evaluate(() => !!document.querySelector('.mantine-Input-input.mantine-Autocomplete-input.mantine-f5preq'));
            if (!isInputExist) throw new Error('Input NIK tidak ditemukan!');

            // Isi input dengan NIK
            await page.type('.mantine-Input-input.mantine-Autocomplete-input.mantine-f5preq', String(nik), { delay: 100 });

            await new Promise(resolve => setTimeout(resolve, 500));
            await page.click('[data-testid="btnCheckNik"]');

            // Tunggu modal muncul atau timeout setelah 3 detik
            let isModalFound = false;
            try {
                await page.waitForSelector('.mantine-Modal-body.mantine-1q36a81', { timeout: 3000 });

                // Ambil semua modal yang muncul
                const modals = await page.$$('.mantine-Modal-body.mantine-1q36a81');

                for (let modal of modals) {
                    const text = await page.evaluate(el => el.innerText, modal);

                    if (text.includes("NIK belum terdaftar")) {
                        console.log(`🔴 NIK ${nik}: Belum Terdaftar`);
                        isModalFound = true;
                    } else if (text.includes("NIK Terdaftar")) {
                        console.log(`🟢 NIK ${nik}: Terdaftar`);
                        isModalFound = true;
                    }
                }
            } catch (e) {
                console.log(`⚠️ NIK ${nik}: Tidak ada modal terdeteksi.`);
            }

            // Jika modal tidak ditemukan, lanjut ke halaman selanjutnya
            if (!isModalFound) {
                console.log(`➡️ NIK ${nik}: Tidak ada modal, lanjut ke halaman berikutnya.`);
                // await page.goto(nextPageURL, { waitUntil: "domcontentloaded" });

                // Tunggu hingga halaman baru termuat
                await new Promise(resolve => setTimeout(resolve, 2000));

                // Cek apakah ada salah satu dari string pembatas LPG
                const isRestricted = await page.evaluate(() => {
                    const pageText = document.body.innerText;
                    return pageText.includes("Tidak dapat transaksi karena telah melebihi batas kewajaran pembelian LPG 3 kg bulan ini.") ||
                           pageText.includes("Tidak dapat transaksi karena telah melebihi batas kewajaran pembelian LPG 3 kg bulan ini untuk NIK yang terdaftar pada nomor KK yang sama.") ||
                           pageText.includes("Tidak dapat transaksi karena telah melebihi batas kewajaran pembelian LPG 3 kg hari ini. (10 tabung).");
                });

                if (!isRestricted) {
                    try {
                        console.log(`✅ NIK ${nik}: Tidak ada batasan LPG, cek jenis transaksi.`);

                        // Ambil teks seluruh halaman
                        const pageText = await page.evaluate(() => document.body.innerText);

                        // Cek apakah termasuk kategori Rumah Tangga atau Usaha Mikro
                        const isRumahTangga = pageText.includes("Rumah Tangga");
                        const isUsahaMikro  = pageText.includes("Usaha Mikro");

                        if (isRumahTangga || isUsahaMikro) {
                            console.log(`🟢 NIK ${nik}: Kategori ditemukan: ${isRumahTangga ? "Rumah Tangga" : "Usaha Mikro"}`);

                            // Tunggu hingga tombol muncul
                            await page.waitForSelector('[data-testid="actionIcon2"]', { visible: true, timeout: 2000 });

                            // Klik tombol sesuai kategori
                            const buttonSelector = '[data-testid="actionIcon2"]';
                            if (isRumahTangga) {
                                console.log(`👉 Klik tombol 1x untuk Rumah Tangga`);
                                await page.click(buttonSelector);
                            } else if (isUsahaMikro) {
                                console.log(`👉 Klik tombol 2x untuk Usaha Mikro`);
                                await page.click(buttonSelector);
                                await new Promise(resolve => setTimeout(resolve, 500)); // Delay kecil antara klik
                                await page.click(buttonSelector);
                            }

                            await clickWithDelay(page, '[data-testid="btnCheckOrder"]', '🛒 Cek Pesanan');
                            await clickWithDelay(page, '[data-testid="btnPay"]', '💳 Proses Transaksi');
                            await clickWithDelay(page, 'a[href="/merchant/app/verification-nik"]', '🏠 Ke Beranda');
                            validNikList.push(nik);
                        } else {
                            console.log(`❌ NIK ${nik}: Transaksi tidak dapat dilakukan karena batas LPG tercapai.`);
                            await page.goto(redirectBackURL, { waitUntil: "domcontentloaded" }); // Kembali ke halaman sebelumnya
                            continue;
                        }

                    } catch (error) {
                        console.error(`⚠️ Error saat memproses transaksi untuk NIK ${nik}:`, error);
                    }
                }
            }

            // Tunggu sampai kembali ke target URL setelah submit
            await new Promise(resolve => setTimeout(resolve, 3000));

            await page.goto(redirectBackURL, { waitUntil: "domcontentloaded" });

        } catch (error) {
            console.error(`Error saat memeriksa NIK ${nik}:`, error);
        }
    }

    await page.close();
    return validNikList;
}

module.exports = { cekNik };
