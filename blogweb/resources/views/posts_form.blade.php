@php($post ??= null)
<div class="form-group">
    <label for="title">Tiêu đề</label>
    <input type="text" name="title" id="title" class="form-control" required
           value="{{ old('title', $post?->title) }}">
</div>

<div class="form-row">
    <div class="form-group col-md-6">
        <label for="category_id">Danh mục</label>
        @php($cat = old('category_id', $post?->category_id))
        <select name="category_id" id="category_id" required
                class="form-control">
            <option value="">-- Chọn danh mục --</option>
            @foreach($categories as $item)
                <option value="{{ $item->id }}" @selected($cat == $item->id)>
                    {{ $item->name }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="form-group col-md-6">
        <label for="status">Trạng thái</label>
        @php($st = old('status', $post?->status ?? 'published'))
        <select name="status" id="status" class="form-control" required>
            @foreach(\App\Models\Post::STATUSES as $value => $label)
                <option value="{{ $value }}" @selected($st === $value)>
                    {{ $label }}
                </option>
            @endforeach
        </select>
    </div>
</div>

@php($at = old('published_at', $post?->published_at?->format('Y-m-d\TH:i')))
<div class="form-group" id="schedule-box">
    <label for="published_at">
        Thời gian hẹn đăng (khi chọn "Hẹn giờ đăng")
    </label>
    <input type="datetime-local" name="published_at" id="published_at"
           class="form-control" value="{{ $at }}">
</div>

<div class="form-group">
    <label>Tag</label><br>
    @php($checked = old('tags', $post?->tags->pluck('id')->all() ?? []))
    @foreach($tags as $tag)
        <label class="mr-3 font-weight-normal">
            <input type="checkbox" name="tags[]" value="{{ $tag->id }}"
                   @checked(in_array($tag->id, $checked))>
            {{ $tag->name }}
        </label>
    @endforeach
</div>

<div class="form-group">
    <label for="content">Nội dung</label>
    <textarea name="content" id="content" rows="8" class="form-control"
              required>{{ old('content', $post?->content) }}</textarea>
</div>

@if($post?->imageUrl())
    <div class="form-group">
        <label>Ảnh hiện tại</label>
        <div><img src="{{ $post->imageUrl() }}" style="max-width: 320px;"></div>
    </div>
@endif

<div class="form-group">
    <label for="image_file">
        Ảnh bài viết (jpeg, jpg, png, gif, webp - tối đa 2MB)
        {{ $post ? '(không bắt buộc)' : '' }}
    </label>
    <input type="file" name="image_file" id="image_file"
           class="form-control-file" @required(! $post)
           accept="image/jpeg,image/png,image/gif,image/webp">
</div>
