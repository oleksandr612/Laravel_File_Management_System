<?php

namespace Unisharp\Laravelfilemanager\controllers;

use Unisharp\Laravelfilemanager\Events\ImageIsRenaming;
use Unisharp\Laravelfilemanager\Events\ImageWasRenamed;
use Unisharp\Laravelfilemanager\Events\FolderIsRenaming;
use Unisharp\Laravelfilemanager\Events\FolderWasRenamed;

class RenameController extends LfmController
{
    public function getRename()
    {
        $old_name = parent::translateFromUtf8(request('file'));
        $new_name = parent::translateFromUtf8(trim(request('new_name')));

        $old_file = parent::getCurrentPath($old_name);

        if (empty($new_name)) {
            if ($this->disk->isDirectory($old_file)) {
                return parent::error('folder-name');
            } else {
                return parent::error('file-name');
            }
        }

        if (config('lfm.alphanumeric_directory') && preg_match('/[^\w-]/i', $new_name)) {
            return parent::error('folder-alnum');
        } elseif ($this->disk->exists($new_name)) {
            return parent::error('rename');
        }

        if (!$this->isDirectory($old_file)) {
            $extension = $this->disk->extension($old_file);
            $new_name = str_replace('.' . $extension, '', $new_name) . '.' . $extension;
        }

        $new_file = parent::getCurrentPath($new_name);

        if ($this->isDirectory($old_file)) {
            event(new FolderIsRenaming($old_file, $new_file));
        } else {
            event(new ImageIsRenaming($old_file, $new_file));
        }

        if (parent::fileIsImage($old_file)) {
            $this->move(parent::getThumbPath($old_name), parent::getThumbPath($new_name));
        }

        $this->move($old_file, $new_file);

        if ($this->isDirectory($old_file)) {
            event(new FolderWasRenamed($old_file, $new_file));
        } else {
            event(new ImageWasRenamed($old_file, $new_file));
        }

        return parent::$success_response;
    }
}
