<?php
/**
 * @var string $pageTitle
 */
?>

<?php if ($pageTitle ?? false && !$hidePageTitle ?? false): ?>
    <h1 class="page_title">
        <?= $pageTitle ?>
    </h1>
<?php endif; ?>
