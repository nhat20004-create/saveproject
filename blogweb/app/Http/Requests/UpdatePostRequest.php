<?php

namespace App\Http\Requests;

class UpdatePostRequest extends StorePostRequest
{
    public function authorize(): bool
    {
        $post = $this->route('post');

        return $this->user()?->can('update', $post) ?? false;
    }

    protected function imageRules(): array
    {
        return ['nullable', 'image', 'mimes:jpeg,jpg,png,gif,webp', 'max:2048'];
    }
}
