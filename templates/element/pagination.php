<?php
/**
 * @var \Cake\View\View $this
 */

$totalPages = $this->Paginator->counter('{{pages}}');
$currentPage = $this->Paginator->counter('{{page}}');

$hasPrev = $this->Paginator->hasPrev();
$prevButton = $this->Paginator->prev(
    '&lsaquo;&nbsp;Prev',
    [
        'escape' => false,
        'class' => 'page-link',
    ]
);
$prev = $hasPrev ? $prevButton : null;

$hasNext = $this->Paginator->hasNext();
$nextButton = $this->Paginator->next(
    'Next&nbsp;&rsaquo;',
    [
        'class' => 'page-link',
        'escape' => false,
    ]
);
$next = $hasNext ? $nextButton : null;
?>
<div class="paginator form-inline">
    <ul class="list-unstyled form-group">
        <?= $prev ?>
        <?php if ($hasPrev || $hasNext) : ?>
            <li>
                <label class="sr-only" for="paginator-page-select">
                    Go to page
                </label>
                <select class="custom-select" id="paginator-page-select">
                    <?php for ($p = 1; $p <= $totalPages; $p++) : ?>
                        <option data-url="<?= $this->Paginator->generateUrl(['page' => $p]) ?>"
                            <?= $p == $currentPage ? 'selected="selected"' : '' ?>>
                            <?= $p ?>
                        </option>
                    <?php endfor; ?>
                </select>
            </li>
        <?php endif; ?>
        <?= $next ?>
    </ul>
</div>
<?php $this->Html->script('/data_center/js/pagination.js', ['block' => true]); ?>
<?php $this->append('buffered'); ?>
    if (typeof pagination === 'undefined') {
        pagination = new Pagination();
    }
<?php $this->end(); ?>
