<div class="{{ $col ?? '' }} {{ $required ?? '' }}">
    <label for="{{ $name }}" class="form-label">{{ $labelName }}</label>
    <select name="{{ $name }}" id="{{ $name }}" class="form-control {{ $class ?? '' }}"
        @if (!empty($onchange)) onchange="{{ $onchange }}" @endif data-live-search="true"
        data-live-search-placeholder="Search" title="Choose one of the following">
        <option value="">Select Please</option>
        {{ $slot }}
    </select>
</div>
