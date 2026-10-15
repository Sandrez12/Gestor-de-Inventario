@props(['label', 'name', 'type' => 'text', 'value' => null, 'required' => false, 'hint' => null])

<div>
    <label for="f-{{ $name }}" class="block text-sm font-medium text-gray-700">
        {{ $label }} @if ($required)<span class="text-red-600" aria-hidden="true">*</span>@endif
    </label>

    @if ($type === 'textarea')
        <textarea id="f-{{ $name }}" name="{{ $name }}" rows="3" @required($required)
            @error($name) aria-invalid="true" aria-describedby="e-{{ $name }}" @enderror
            {{ $attributes->merge(['class' => 'mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500']) }}>{{ old($name, $value) }}</textarea>
    @elseif ($type === 'select')
        <select id="f-{{ $name }}" name="{{ $name }}" @required($required)
            @error($name) aria-invalid="true" aria-describedby="e-{{ $name }}" @enderror
            {{ $attributes->merge(['class' => 'mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500']) }}>
            {{ $slot }}
        </select>
    @else
        <input id="f-{{ $name }}" name="{{ $name }}" type="{{ $type }}" value="{{ old($name, $value) }}" @required($required)
            @error($name) aria-invalid="true" aria-describedby="e-{{ $name }}" @enderror
            {{ $attributes->merge(['class' => 'mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500']) }}>
    @endif

    @if ($hint)
        <p class="mt-1 text-xs text-gray-500">{{ $hint }}</p>
    @endif
    @error($name)
        <p id="e-{{ $name }}" class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>
