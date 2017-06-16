<?php

namespace App\Repositories;

use App\Event;
use App\Property;

/**
 * Class BiographyEntryRepository
 * @package App\Repositories
 */
class BiographyEntryRepository extends Repository {

    /**
     * BiographyEntryRepository constructor.
     * @param Event $scope
     */
    public function __construct(Event $scope = null)
    {

        $this->scope = $scope ? : new Event();

    }

    /**
     * @return Event
     */
    public function show()
    {
        $biography_entry = $this->scope;

        $files = is_null($biography_entry->files) ? [] : $biography_entry->files;
        foreach ($files as &$file) {
            $file = (new FileRepository($file))->show();
        }
        $biography_entry->files = $files;

        $category = $biography_entry->properties()->where('type', 'BiographyEntryCategory')->get()->first();
        if (!is_null($category)) {
            $type = Property::where('type', 'BiographyEntryCategoryType')->where('name', $category->name)->get()->first();
            if (!is_null($type)) {
                $category->value = $type->value;
            }

            $biography_entry->category = $category;
        }

        return $biography_entry;
    }

}