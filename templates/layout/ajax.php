<?php
/**
 * @var \Cake\View\View $this
 * @var array $flashMessages
 * @var string $pageTitle
 */
?>

<?= $this->element('DataCenter.analytics') ?>

<?php if (!empty($flashMessages)): ?>
    <?php foreach ($flashMessages as $msg): ?>
        <?php $this->append('buffered'); ?>
        (new FlashMessage).insert(<?= json_encode($msg['message']) ?>, <?= json_encode($msg['class']) ?>);
        <?php $this->end(); ?>
    <?php endforeach; ?>
<?php endif; ?>

<?= $this->fetch('content') ?>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        <?= $this->fetch('buffered') ?>
    });
</script>
