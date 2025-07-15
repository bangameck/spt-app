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
        if (window.performance) {
            let loadTime = (window.performance.timing.loadEventEnd - window.performance.timing
                .navigationStart) / 1000;
            let loadTimeElement = document.getElementById('page-load-time');
            if (loadTimeElement) {
                loadTimeElement.textContent = loadTime.toFixed(3) + 's';
            }
        }
    });
</script>
