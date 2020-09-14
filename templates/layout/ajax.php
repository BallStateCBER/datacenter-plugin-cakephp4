<?php
/** @var \Cake\View\View $this */
use Cake\Core\Configure;

$googleAnalyticsId = Configure::read('google_analytics_id');
$gaConfig = ['page_title' => $titleForLayout ?? null];
?>
<?php if ($googleAnalyticsId && !Configure::read('debug')): ?>
    <?php $this->append('buffered'); ?>
    gtag('config', <?= json_encode($googleAnalyticsId) ?>, <?= json_encode($gaConfig) ?>);
    <?php $this->end(); ?>
<?php endif; ?>

<?php if (!empty($flashMessages)): ?>
    <?php foreach ($flashMessages as $msg): ?>
        <?php $this->append('buffered'); ?>
        flashMessage.insert(<?= json_encode($msg['message']) ?>, <?= json_encode($msg['class']) ?>);
        <?php $this->end(); ?>
    <?php endforeach; ?>
<?php endif; ?>

<?= $this->fetch('content') ?>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        <?= $this->fetch('buffered') ?>
    });
</script>
