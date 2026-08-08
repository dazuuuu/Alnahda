<?php
/** Requires $active ('home' | 'apply' | 'testimonials') in scope. */
?>
    <a href="https://wa.me/254793700592" class="whatsapp-float" target="_blank" rel="noopener" aria-label="Chat with Al NAHDA Agency on WhatsApp">
        <svg viewBox="0 0 32 32" aria-hidden="true">
            <path d="M16 3C9.373 3 4 8.373 4 15c0 2.43.7 4.727 1.917 6.66L4 28l6.51-1.888A11.94 11.94 0 0 0 16 27c6.627 0 12-5.373 12-12S22.627 3 16 3Zm0 2c5.523 0 10 4.477 10 10s-4.477 10-10 10a9.92 9.92 0 0 1-5.14-1.43l-.36-.21-3.86 1.12 1.1-3.73-.24-.38A9.92 9.92 0 0 1 6 15c0-5.523 4.477-10 10-10Zm4.676 14.087c-.256-.13-1.51-.743-1.744-.828-.234-.087-.405-.13-.575.13-.17.26-.662.828-.811.998-.149.17-.298.195-.554.065-.256-.13-1.083-.4-2.063-1.275-.762-.677-1.276-1.515-1.425-1.77-.149-.26-.016-.4.112-.53.115-.114.256-.296.384-.443.128-.148.17-.255.256-.425.085-.17.043-.318-.022-.448-.065-.13-.575-1.39-.788-1.902-.207-.497-.418-.43-.575-.438-.149-.007-.32-.009-.49-.009-.17 0-.448.064-.683.321-.234.26-.894.872-.894 2.127 0 1.255.917 2.466 1.046 2.637.128.17 1.805 2.757 4.377 3.865.611.264 1.087.422 1.457.54.611.194 1.167.167 1.606.101.49-.073 1.51-.616 1.723-1.211.213-.596.213-1.108.149-1.21-.064-.102-.234-.165-.49-.295Z"/>
        </svg>
    </a>

    <?php if ($active === 'home'): ?>
    <footer class="site-footer">
        <div class="container footer-content">
            <div class="footer-links">
                <a href="#about">About</a>
                <a href="#services">Services</a>
                <a href="#countries">Countries</a>
                <a href="<?= url('/testimonials') ?>">Testimonials</a>
                <a href="<?= url('/apply') ?>">Apply</a>
                <a href="<?= url('/portal/login') ?>">Track Application</a>
            </div>
        </div>
    </footer>
    <?php else: ?>
    <footer class="site-footer">
        <div class="container footer-content">
            <div class="footer-links">
                <a href="<?= url('/') ?>">Home</a>
                <a href="<?= url('/testimonials') ?>">Testimonials</a>
                <a href="<?= url('/portal/login') ?>">Track Application</a>
            </div>
        </div>
    </footer>
    <?php endif; ?>

    <script src="<?= versionedAsset('assets/site/js/script.js') ?>"></script>
</body>
</html>
