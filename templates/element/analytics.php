<?php
    /** @var \Cake\View\View $this */
    use Cake\Core\Configure;

    $googleAnalyticsId = Configure::read('google_analytics_id');
    $gaConfig = ['page_title' => $titleForLayout ?? null];
?>
<?php if ($googleAnalyticsId && !Configure::read('debug')): ?>
    <!-- Global Site Tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=<?= $googleAnalyticsId ?>"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', <?= json_encode($googleAnalyticsId) ?>, <?= json_encode($gaConfig) ?>);
    </script>
<?php endif; ?>
