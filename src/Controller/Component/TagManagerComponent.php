<?php
declare(strict_types=1);

namespace DataCenter\Controller\Component;

use Cake\Controller\Component;
use Cake\Http\Exception\InternalErrorException;

class TagManagerComponent extends Component
{
    /**
     * Processes the 'custom tags' field, finding or creating tags as necessary, and returns an array of Tag entities
     *
     * @param array $requestData The full set of request data including the 'custom_tags' field
     * @param \Cake\ORM\Table $tagsTable The site's Tags table object
     * @throws \Cake\Http\Exception\InternalErrorException
     * @return array
     */
    public function processTagInput(array $requestData, $tagsTable)
    {
        // Split the input string into an array of unique, lowercase, trimmed strings
        $tagNames = explode(',', $requestData['custom_tags']);
        $tagNames = array_map('strtolower', $tagNames);
        $tagNames = array_map('trim', $tagNames);
        $tagNames = array_unique($tagNames);

        $tagEntities = [];
        foreach ($tagNames as $tagName) {
            // Fetch tag if it exists
            if ($tagsTable->exists(['name' => $tagName])) {
                $tagEntities[] = $tagsTable
                    ->find()
                    ->where(['name' => $tagName])
                    ->first();
                continue;
            }

            // Create tag if it does not exist
            $tag = $tagsTable->newEntity(['name' => $tagName]);
            if (!$tagsTable->save($tag)) {
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
}
