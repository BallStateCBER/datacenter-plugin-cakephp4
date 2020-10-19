<?php
/**
 * @var \DataCenter\View\DataCenterView $this
 * @var array $config
 * @var string $selector
 */

/* This element should be included in any view where textarea fields are intended
 * to be turned into rich text editors. When the rich text editor gets updated or
 * replaced, the only changes necessary will be within the DataCenter plugin.
 *
 * Include this element in views like so:
 * <?= $this->element('DataCenter.rich_text_editor_init', ['selector' => '#textarea-id', 'config' => [...]]) ?>
 *
 * To customize: https://ckeditor.com/docs/ckeditor5/latest/builds/guides/integration/configuration.html
 */

use Cake\Http\Exception\InternalErrorException;

$this->Html->script('https://cdn.ckeditor.com/ckeditor5/23.0.0/classic/ckeditor.js', ['block' => true]);
if (!isset($selector)) {
    throw new InternalErrorException('Selector not specified for rich text editor');
}
?>

<?php $this->append('buffered'); ?>
    ClassicEditor.create(document.querySelector(<?= json_encode($selector) ?>), <?= json_encode($config ?? []) ?>);
<?php $this->end(); ?>
