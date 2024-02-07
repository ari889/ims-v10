<div class="{{ $col ?? '' }}" {{ $required ?? '' }}>
    <label for="{{ $name }}" class="form-label">{{ $labelName }}</label>
    <textarea name="{{ $name }}" id="{{ $name }}" class="form-control {{ $class ?? '' }}"
        placeholder="{{ $placeholder ?? '' }}" cols="30" rows="10">{{ $value ?? '' }}</textarea>
</div>
