<div class="form-group">
    <label for="{{ $name }}">{{ $label }}</label>
    <input type="{{ $type ?? 'text' }}" name="{{ $name }}" id="{{ $name }}"
           class="form-control" required
           @if(($type ?? 'text') !== 'password')
               value="{{ old($name, $value ?? '') }}"
           @endif>
</div>
