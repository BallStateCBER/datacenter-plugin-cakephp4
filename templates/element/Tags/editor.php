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

<div class="input" id="tag_editing">
    <div id="available_tags_container">
        <div id="available_tags"></div>
        <div id="popular_tags"></div>
    </div>
    <div class="text-muted">
        Click <img src="/data_center/img/icons/menu-collapsed.png" /> to expand groups.
        Click
        <a href="#" title="Selectable tags will appear in blue" id="example_selectable_tag">selectable tags</a>
        to select them.
    </div>

    <div id="selected_tags_container" style="display: none;">
        <span class="label">
            Selected tags:
        </span>
        <span id="selected_tags"></span>
        <div class="text-muted">
            Click on a tag to unselect it.
        </div>
    </div>

    <div id="custom_tag_input_wrapper">
        <label for="custom_tag_input">
            Additional Tags
            <span id="tag_autosuggest_loading" style="display: none;">
                <img src="/data_center/img/loading_small.gif" alt="Working..." title="Working..." style="vertical-align:top;" />
            </span>
        </label>
        <?= $this->Form->control(
            'customTags',
            [
                'label' => false,
                'class' => 'form-control',
                'id' => 'custom_tag_input',
            ]
        ) ?>
        <div class="text-muted">
            Write out tags, separated by commas.
        </div>
    </div>
</div>

<?= $this->Tag->setup($availableTags, $selectedTags, $options ?? []) ?>
