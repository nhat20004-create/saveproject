<?php

namespace App\Http\Requests;

use App\Models\Post;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePostRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        $scheduled = $this->input('status') === Post::SCHEDULED;

        return [
            'title' => ['required', 'string', 'max:255'],
            'category_id' => ['required', 'exists:categories,id'],
            'tags' => ['nullable', 'array'],
            'tags.*' => ['integer', 'exists:tags,id'],
            'status' => ['required', Rule::in(array_keys(Post::STATUSES))],
            'published_at' => $scheduled
                ? ['required', 'date', 'after:now']
                : ['nullable'],
            'content' => ['required', 'string', 'min:10'],
            'image_file' => $this->imageRules(),
        ];
    }

    protected function imageRules(): array
    {
        return [
            'required', 'image', 'mimes:jpeg,jpg,png,gif,webp', 'max:2048',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Vui lòng nhập tiêu đề bài viết.',
            'title.max' => 'Tiêu đề không được vượt quá 255 ký tự.',
            'category_id.required' => 'Vui lòng chọn danh mục.',
            'category_id.exists' => 'Danh mục không hợp lệ.',
            'tags.*.exists' => 'Thẻ tag không hợp lệ.',
            'status.required' => 'Vui lòng chọn trạng thái.',
            'status.in' => 'Trạng thái không hợp lệ.',
            'published_at.required' => 'Vui lòng chọn thời gian hẹn đăng.',
            'published_at.after' => 'Thời gian hẹn đăng phải ở tương lai.',
            'content.required' => 'Vui lòng nhập nội dung bài viết.',
            'content.min' => 'Nội dung phải có ít nhất 10 ký tự.',
            'image_file.required' => 'Vui lòng chọn ảnh bài viết.',
            'image_file.uploaded' => 'Tải ảnh thất bại, ảnh tối đa 2MB.',
            'image_file.image' => 'File tải lên phải là hình ảnh.',
            'image_file.mimes' => 'Ảnh chỉ nhận jpeg, jpg, png, gif, webp.',
            'image_file.max' => 'Dung lượng ảnh không được vượt quá 2MB.',
        ];
    }
}
