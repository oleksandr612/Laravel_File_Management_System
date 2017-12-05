<?php

namespace UniSharp\LaravelFilemanager\controllers;

class FolderController extends LfmController
{
    /**
     * Get list of folders as json to populate treeview.
     *
     * @return mixed
     */
    public function getFolders()
    {
        $folder_types = array_filter(['user', 'share'], function ($type) {
            return $this->helper->allowFolderType($type);
        });

        return view('laravel-filemanager::tree')
            ->with([
                'root_folders' => array_map(function ($type) use ($folder_types) {
                    $path = $this->lfm->dir($this->helper->getRootFolder($type));

                    return (object) [
                        'name' => trans('laravel-filemanager::lfm.title-' . $type),
                        'path' => $path->path('working_dir'),
                        'children' => $path->folders('name'),
                        'has_next' => ! ($type == end($folder_types)),
                    ];
                }, $folder_types),
            ]);
    }

    /**
     * Add a new folder.
     *
     * @return mixed
     */
    public function getAddfolder()
    {
        $folder_name = $this->helper->translateFromUtf8(trim(request('name')));

        if (empty($folder_name)) {
            return parent::error('folder-name');
        } elseif ($this->lfm->setName($folder_name)->exists()) {
            return parent::error('folder-exist');
        } elseif (config('lfm.alphanumeric_directory') && preg_match('/[^\w-]/i', $folder_name)) {
            return parent::error('folder-alnum');
        } else {
            $this->lfm->setName($folder_name)->createFolder();
        }

        if (config('lfm.alphanumeric_directory') && preg_match('/[^\w-]/i', $folder_name)) {
            return parent::error('folder-alnum');
        }

        parent::createFolderByPath($path);
        return parent::$success_response;
    }
}
