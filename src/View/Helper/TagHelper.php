<?php
declare(strict_types=1);

namespace DataCenter\View\Helper;

use Cake\ORM\TableRegistry;
use Cake\View\Helper;

class TagHelper extends Helper
{
    /**
     * Echoes a <script> tag that initializes the TagHelper
     *
     * Options:
     * - tags: (Required) A tree-shaped object containing nested tag groups
     * - selectedTags: (Optional) An array of objects with 'id' and 'name' properties
     * - showTree: (default: true)
     * - showList: (default: false)
     * - useAutocomplete: (default: false)
     * - container: (default: '#available_tags')
     *
     * @param array $availableTags Array of available tag entities
     * @param array $selectedTags Array of selected tag entities
     * @param array $options Array of options
     * @return string
     */
    public function setup(array $availableTags, $selectedTags = [], $options = [])
    {
        $params = $options;
        $params['tags'] = $this->availableTagsForJs($availableTags);
        if ($selectedTags) {
            $selectedTags = $this->formatSelectedTags($selectedTags);
            $params['selectedTags'] = $this->selectedTagsForJs($selectedTags);
        }

        return '<script>const tagManager = new TagManager(' . json_encode($params) . ');</script>';
    }

    /**
     * Returns an array of available tags, formatted for JSON output
     *
     * @param array $availableTags Array of available tags
     * @return array
     */
    private function availableTagsForJs(array $availableTags)
    {
        $arrayForJson = [];
        foreach ($availableTags as $tag) {
            $arrayForJson[] = [
                'id' => $tag->id,
                'name' => $tag->name,
                'selectable' => $tag->selectable,
                'children' => $this->availableTagsForJs($tag->children),
            ];
        }

        return $arrayForJson;
    }

    /**
     * Returns an array of selected tags, formatted for JSON output
     *
     * @param array $selectedTags Array of selected tags
     * @return array
     */
    private function selectedTagsForJs(array $selectedTags)
    {
        $arrayForJson = [];
        foreach ($selectedTags as $tag) {
            $arrayForJson[] = [
                'id' => $tag->id,
                'name' => $tag->name,
            ];
        }

        return $arrayForJson;
    }

    /**
     * Converts $selectedTags from an array of IDs to a full array of tag entities
     *
     * @param array $selectedTags Array of selected tags
     * @return array
     */
    private function formatSelectedTags(array $selectedTags)
    {
        if (empty($selectedTags)) {
            return [];
        }

        if (is_array($selectedTags[0])) {
            return $selectedTags;
        }

        $tagsTable = TableRegistry::getTableLocator()->get('Tags');
        $retval = [];
        foreach ($selectedTags as $tag) {
            $retval[] = $tagsTable->get($tag->id);
        }

        return $retval;
    }
}
