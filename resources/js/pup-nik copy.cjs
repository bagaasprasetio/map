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

async function cekNik(browser, nikList, redirectBackURL, inputTrx, type) {
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
            // Definisikan pesan yang ingin dicocokkan
            const messages = {
                notRegistered: "NIK belum terdaftar",
                registered: "NIK Terdaftar",
                updateRequired: "Pelanggan Usaha Mikro yang sudah terdaftar perlu memperbarui informasi jenis usaha."
            };
            try {
                await page.waitForSelector('.mantine-Modal-body.mantine-1q36a81', { timeout: 3000 });

                // Ambil semua modal yang muncul
                const modals = await page.$$('.mantine-Modal-body.mantine-1q36a81');

                for (let modal of modals) {
                    const text = await page.evaluate(el => el.innerText, modal);

                    if (text.includes(messages.notRegistered)) {
                        console.log(`🔴 NIK ${nik}: Belum Terdaftar`);
                        isModalFound = true;
                    } else if (text.includes(messages.registered)) {
                        console.log(`🟢 NIK ${nik}: Terdaftar`);

                        let radioButton = await modal.$('[data-testid="radio-Usaha Mikro"]');
                        if (!radioButton) {
                            radioButton = await modal.$('[data-testid="radio-Rumah Tangga"]');
                        }

                        if (radioButton) {
                            console.log(`✅ Radio button ditemukan, mencoba klik...`);
                            await new Promise(resolve => setTimeout(resolve, 500));
                            await page.evaluate(radio => radio.click(), radioButton);
                            await new Promise(resolve => setTimeout(resolve, 500));

                            const continueButton = await modal.$('[data-testid="btnContinueTrx"]');
                            await page.evaluate(button => button.click(), continueButton);
                            console.log(`✅ Radio button "Usaha Mikro" berhasil dipilih.`);

                            console.log(`🟠 type adalah ${type}`);
                            if (type == 'UM') {
                                console.log(`🟠 type adalah ${type}: Exec if`);
                                // Tunggu modal baru muncul setelah klik continueButton
                                await page.waitForSelector('.mantine-Modal-body.mantine-1q36a81', { timeout: 3000 });
                                // Ambil kembali modal yang baru muncul
                                let newModals = await page.$$('.mantine-Modal-body.mantine-1q36a81');

                                for (let newModal of newModals) {
                                    let newText = await page.evaluate(el => el.innerText, newModal);

                                    if (newText.includes(messages.updateRequired)) {
                                        console.log(`🟠 Dari NIK Terdaftar ${nik}: Pelanggan perlu memperbarui informasi jenis usaha.`);

                                        // Ambil semua tombol dalam modal ini
                                        const buttons = await newModal.$$('button');
                                        const buttonCount = buttons.length;

                                        if (buttonCount === 3) {
                                            console.log(`🟠 Dari NIK Terdaftar ${nik}: (3 tombol: "Perbarui Data Pelanggan", "Lewati, Lanjut Transaksi", "Kembali").`);

                                            // Tunggu selector tombol "Lewati, Lanjut Transaksi"
                                            await page.waitForSelector('.styles_root__6_rRr.styles_medium__7QTIz.styles_outlined__khSXF.styles_primary__pVpF_', { visible: true, timeout: 3000 });

                                            // Pilih tombol berdasarkan kelasnya dan klik
                                            const skipButton = await newModal.$('.styles_root__6_rRr.styles_medium__7QTIz.styles_outlined__khSXF.styles_primary__pVpF_');

                                            if (skipButton) {
                                                console.log(`✅ Tombol "Lewati, Lanjut Transaksi" ditemukan, mencoba klik...`);
                                                await new Promise(resolve => setTimeout(resolve, 500));  // Delay sebelum klik
                                                await skipButton.click();
                                                console.log(`✅ Tombol "Lewati, Lanjut Transaksi" berhasil dipilih.`);
                                            } else {
                                                console.log(`⚠️ Tombol "Lewati, Lanjut Transaksi" tidak ditemukan.`);
                                            }
                                        }
                                    }
                                }
                            }

                        } else {
                            console.log(`⚠️ Radio button "Usaha Mikro" tidak ditemukan.`);
                        }

                        // isModalFound = true; // Diletakkan setelah semua proses dalam kondisi selesai
                    } else if (text.includes(messages.updateRequired)) {
                        console.log(`🟢 NIK ${nik}: perlu memperbarui informasi`);
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
                           pageText.includes("Tidak dapat transaksi karena telah melebihi batas kewajaran pembelian LPG 3 kg hari ini. (10 tabung).") ||
                           pageText.includes("Tidak dapat transaksi, stok tabung yang dapat dijual kosong. Silakan lakukan penebusan.");
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
                            await page.waitForSelector('[data-testid="actionIcon2"]', { visible: true, timeout: 5000 });

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

                            // await clickWithDelay(page, '[data-testid="btnCheckOrder"]', '🛒 Cek Pesanan');
                            // await clickWithDelay(page, '[data-testid="btnPay"]', '💳 Proses Transaksi');
                            // await clickWithDelay(page, 'a[href="/merchant/app/verification-nik"]', '🏠 Ke Beranda');
                            // validNikList.push(nik);
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
