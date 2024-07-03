<?php

namespace Architecture\Shared;

use Illuminate\Http\UploadedFile;

interface MergeFile{
    public function Merge(UploadedFile $file);
}