<?php
/**
 * @var \Cake\View\View $this
 * @var string $pageTitle
 */

use Cake\Core\Configure;

$googleAnalyticsId = Configure::read('DataCenter.googleAnalyticsId');
if (!$googleAnalyticsId || Configure::read('debug')) {
    return;
}
$gaConfig = [
    'page_location' => $this->request->getUri()->__toString(),
    'page_path' => $this->request->getUri()->getPath(),
    'page_title' => $pageTitle ?? null,
];
?>

<?php if ($this->request->is('ajax')): ?>
    <?php $this->append('buffered'); ?>
        gtag('config', <?= json_encode($googleAnalyticsId) ?>, <?= json_encode($gaConfig) ?>);
    <?php $this->end(); ?>
<?php else: ?>
    <!-- Global Site Tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=<?= $googleAnalyticsId ?>"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', '<?= $googleAnalyticsId ?>', <?= json_encode($gaConfig) ?>);
    </script>
<?php endif; ?>
