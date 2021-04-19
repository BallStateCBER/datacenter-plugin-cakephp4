<?php
declare(strict_types=1);

namespace DataCenter\Controller\Component;

use Cake\Controller\Component;
use Cake\Http\Exception\InternalErrorException;

/**
 * Class TagManagerComponent
 *
 * @package DataCenter\Controller\Component
 * @property \App\Model\Table\TagsTable $Tags
 */
class TagManagerComponent extends Component
{
    private $Tags;

    /**
     * Processes the custom_tags and tags._ids fields, creating tags if necessary, and returns an array of Tag entities
     *
     * @param array $requestData The full set of request data including the 'custom_tags' field
     * @param \Cake\ORM\Table $tagsTable The site's Tags table object
     * @throws \Cake\Http\Exception\InternalErrorException
     * @throws \Cake\Datasource\Exception\RecordNotFoundException
     * @return array
     */
    public function processTagInput(array $requestData, $tagsTable)
    {
        $this->Tags = $tagsTable;
        $tagNames = explode(',', $requestData['custom_tags'] ?? '');
        $tagIds = $requestData['tags']['_ids'];
        $tags = array_merge(
            $this->getNamedTags($tagNames),
            $this->getTagsById($tagIds)
        );

        // Remove any duplicate tags
        $tagIds = [];
        foreach ($tags as $k => $tag) {
            if (in_array($tag->id, $tagIds)) {
                unset($tags[$k]);
                continue;
            }
            $tagIds[] = $tag->id;
        }

        return $tags;
    }

    /**
     * Returns an array of Tag entities matching the provided names
     *
     * @param array|null|bool $tagNames An array of tag names, or null or FALSE
     * @return array
     */
    public function getNamedTags($tagNames)
    {
        if (!$tagNames) {
            return [];
        }

        // Ensure tag names are unique, lowercase, trimmed strings
        $tagNames = array_map('strtolower', $tagNames);
        $tagNames = array_map('trim', $tagNames);
        $tagNames = array_unique($tagNames);

        $tagEntities = [];
        foreach ($tagNames as $tagName) {
            if ($tagName == '') {
                continue;
            }

            // Fetch tag if it exists
            if ($this->Tags->exists(['name' => $tagName])) {
                $tagEntities[] = $this->Tags
                    ->find()
                    ->where(['name' => $tagName])
                    ->first();
                continue;
            }

            // Create tag if it does not exist
            $tag = $this->Tags->newEntity(['name' => $tagName]);
            if (!$this->Tags->save($tag)) {
                throw new InternalErrorException(sprintf(
                    'Error saving new "%s" tag. Details: %s',
                    $tagName,
                    print_r($tag->getErrors(), true)
                ));
            }
            $tagEntities[] = $tag;
        }

        return $tagEntities;
    }

    /**
     * Returns a
     *
     * @param array $tagIds Array of tag IDs
     * @return array
     * @throws \Cake\Datasource\Exception\RecordNotFoundException
     */
    public function getTagsById($tagIds)
    {
        if (!$tagIds) {
            return [];
        }

        $tagIds = array_unique($tagIds);
        $tags = [];
        foreach ($tagIds as $tagId) {
            $tags[] = $this->Tags->get($tagId);
        }

        return $tags;
    }
}
