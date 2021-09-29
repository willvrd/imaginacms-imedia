<?php

namespace Modules\Media\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Media\Validators\MaxFolderSizeRule;

class UploadMediaRequest extends FormRequest
{
    public function rules()
    {
        $extensions = 'mimes:' . join(',', setting('media::allowedTypes'));
        $maxFileSize = $this->getMaxFileSizeInKilobytes();

        return [
            'file' => [
                'required',
                new MaxFolderSizeRule(),
                $extensions,
                "max:$maxFileSize",
            ],
        ];
    }

    public function messages()
    {
        $size = $this->getMaxFileSize();

        return [
            'file.max' => trans('media::media.file too large', ['size' => $size]),
        ];
    }

    public function authorize()
    {
        return true;
    }

    private function getMaxFileSizeInKilobytes()
    {
        return $this->getMaxFileSize() * 1000;
    }

    private function getMaxFileSize()
    {
        return setting('media::maxFileSize');
    }
}
