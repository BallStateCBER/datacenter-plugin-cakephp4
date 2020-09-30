<?php
/**
 * @var \DataCenter\View\AppView $this
 */

$totalPages = $this->Paginator->counter('pages');
$currentPage = $this->Paginator->counter('pages');

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
<div class="paginator">
    <ul class="pagination">
        <?= $prev ?>
        <?php if ($hasPrev || $hasNext) : ?>
            <label class="sr-only" for="paginator-page-select">
                Go to page
            </label>
            <select class="custom-select" id="paginator-page-select">
                <?php for ($p = 1; $p <= $totalPages; $p++) : ?>
                    <option
                        <?php if ($p == $currentPage) :
                            ?>selected="selected"<?php
                        endif; ?>
                        data-url="<?= $this->Paginator->generateUrl(['page' => $p]) ?>"
                    >
                        <?= $p ?>
                    </option>
                <?php endfor; ?>
            </select>
        <?php endif; ?>
        <?= $next ?>
    </ul>
</div>
