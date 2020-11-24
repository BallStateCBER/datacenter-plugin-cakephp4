<?php
/**
 * @var \DataCenter\View\DataCenterView $this
 * @var array $availableTags
 * @var array $options
 * @var array $selectedTags (optional)
 */

echo $this->Html->script('/data_center/js/tag_manager.js', ['block' => true]);
echo $this->Html->css('/data_center/css/tag_editor.css');
echo $this->Html->css('https://cdn.jsdelivr.net/npm/@tarekraafat/autocomplete.js@7.2.0/dist/css/autoComplete.min.css');
echo $this->Html->script(
    'https://cdn.jsdelivr.net/npm/@tarekraafat/autocomplete.js@7.2.0/dist/js/autoComplete.min.js',
    ['block' => true]
);
?>

<div class="input" id="tag-editing">
    <div id="available-tags-container">
        <div id="available-tags"></div>
    </div>
    <div class="text-muted">
        Click <i class="fas fa-caret-right"></i> to expand groups.
        Click
        <a href="#" title="Selectable tags will appear in blue" id="example-selectable-tag">selectable tags</a>
        to select them.
    </div>

    <div id="selected-tags-container" style="display: none;">
        <span class="label">
            Selected tags:
        </span>
        <span id="selected-tags"></span>
        <div class="text-muted">
            Click on a tag to unselect it.
        </div>
    </div>

    <div id="custom-tag-input-wrapper">
        <label for="custom-tag-input">
            Additional Tags
            <span id="tag-autosuggest-loading" style="display: none;">
              <i class="fas fa-spinner fa-spin" title="Working..." style="vertical-align:top;"></i>
            </span>
        </label>
        <?= $this->Form->control(
            'custom_tags',
            [
                'label' => false,
                'class' => 'form-control',
                'id' => 'custom-tag-input',
            ]
        ) ?>
        <div class="text-muted">
            Write out tags, separated by commas.
        </div>
    </div>
</div>

<?= $this->Tag->setup($availableTags, $selectedTags, $options ?? []) ?>
