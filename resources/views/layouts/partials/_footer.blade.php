<footer class="content-footer footer bg-footer-theme">
    <div class="container-xxl">
        <div class="footer-container d-flex align-items-center justify-content-between py-3 flex-md-row flex-column">
            <div class="text-body mb-2 mb-md-0">
                ©
                <script>
                    document.write(new Date().getFullYear());
                </script>
                , made with ❤️ by <a href="#" target="_blank" class="footer-link">Tim IT UPT Perparkiran</a>
            </div>
            <div class="d-none d-lg-inline-block">
                Page Loaded in: <span id="page-load-time" class="fw-medium"></span>
            </div>
        </div>
    </div>
</footer>

<script>
    window.addEventListener('load', function() {
        const loadTimeElement = document.getElementById('page-load-time');

        // Ambil waktu sekarang relatif terhadap time origin
        const now = performance.now();
        const loadTime = now; // karena now - 0 (start time) = now

        let displayTime;
        if (loadTime >= 1000) {
            displayTime = (loadTime / 1000).toFixed(2) + ' detik';
        } else if (loadTime >= 1) {
            displayTime = loadTime.toFixed(2) + ' milidetik';
        } else {
            displayTime = (loadTime * 1000).toFixed(2) + ' mikrodetik';
        }

        if (loadTimeElement) {
            loadTimeElement.textContent = displayTime;
        }
    });
</script>
