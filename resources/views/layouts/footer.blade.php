<footer class="footer bg-white flex items-center py-5">
    <div class="px-6 flex md:justify-between justify-center w-full gap-4 text-sm">
        <div>
            <script>
                document.write(new Date().getFullYear())
            </script> © Opatix.

            {{-- ✅ PERUBAHAN DI SINI: Elemen untuk menampilkan waktu muat --}}
            <span class="ms-1 hidden md:inline-block">
                Page Loaded in: <span id="page-load-time" class="font-semibold text-primary"></span>
            </span>
        </div>
        <div class="md:flex hidden gap-2 item-center md:justify-end">
            Design &amp; Develop by<a href="#" class="text-primary ms-1">MyraStudio</a>
        </div>
    </div>
</footer>

{{-- ✅ PERUBAHAN DI SINI: Script untuk menghitung waktu muat --}}
{{-- Script ini bisa ditempatkan di sini atau di layout utama Anda sebelum </body> --}}
<script>
    window.addEventListener('load', function() {
        // Cek jika Performance API didukung oleh browser
        if (window.performance) {
            // Waktu dihitung dalam milidetik, kita bagi 1000 untuk dapat detik
            let loadTime = (window.performance.timing.loadEventEnd - window.performance.timing
                .navigationStart) / 1000;

            // Ambil elemen span
            let loadTimeElement = document.getElementById('page-load-time');
            if (loadTimeElement) {
                // Tampilkan dengan 2-3 angka desimal dan tambahkan "s" untuk detik
                loadTimeElement.textContent = loadTime.toFixed(3) + 's';
            }
        }
    });
</script>
