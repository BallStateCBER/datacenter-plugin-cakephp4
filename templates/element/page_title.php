<?php
/**
 * @var \DataCenter\View\DataCenterView $this
 * @var bool $hidePageTitle
 * @var string $pageTitle
 */
?>

<?php if (($pageTitle ?? false) && !($hidePageTitle ?? false)): ?>
    <h1 class="page-title">
        <?php if ($linkPageTitle ?? false): ?>
            <?= $this->Html->link(
                $pageTitle,
                $this->request->getUri()
            ) ?>
        <?php else: ?>
            <?= $pageTitle ?>
        <?php endif; ?>
    </h1>
<?php endif; ?>
