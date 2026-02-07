</main>
<!-- End Main Content -->

<!-- Footer -->
<footer
    class="mt-auto py-6 px-8 border-t border-slate-200 dark:border-slate-800 text-center sm:text-<?php echo ($dir == 'rtl' ? 'right' : 'left'); ?>">
    <p class="text-sm text-slate-500 dark:text-slate-400 font-medium">
        &copy;
        <?php echo date('Y'); ?>
        <?php echo t('أهل السنة', 'ELSONA'); ?>.
        <?php echo t('جميع الحقوق محفوظة.', 'All rights reserved.'); ?>
    </p>
</footer>

</div>
<!-- End Content Wrapper -->

</div>
<!-- End Flex Container -->
<?php ob_end_flush(); ?>
</body>

</html>