{{-- resources/views/components/ckeditor.blade.php --}}
@props([
    'id' => '',
    'name' => '',
    'value' => '',
    'placeholder' => '',
    'type' => 'basic', // 'simple', 'basic', 'advanced'
    'rows' => 3,
    'required' => false,
    'label' => '',
    'help' => '',
    'error' => null
])

@php
    $fieldId = $id ?: $name;
    $fieldName = $name ?: $id;
    $hasError = $error || $errors->has($fieldName);
@endphp

<div class="space-y-2">
    {{-- Label --}}
    @if($label)
        <label for="{{ $fieldId }}" class="block text-sm font-medium text-slate-700">
            {{ $label }}
            @if($required)
                <span class="text-red-500">*</span>
            @endif
        </label>
    @endif

    {{-- Help text --}}
    @if($help)
        <p class="text-sm text-slate-500">{{ $help }}</p>
    @endif

    {{-- CKEditor Container --}}
    <div class="@if($hasError) has-error @endif" data-ckeditor-type="{{ $type }}">
        <textarea
            id="{{ $fieldId }}"
            name="{{ $fieldName }}"
            rows="{{ $rows }}"
            @if($placeholder) placeholder="{{ $placeholder }}" @endif
            @if($required) required @endif
            {{ $attributes->merge([
                'class' => 'w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors resize-none'
            ]) }}
            data-ckeditor-config="{{ $type }}"
        >{{ old($fieldName, $value) }}</textarea>
    </div>

    {{-- Error message --}}
    @if($hasError)
        <p class="text-sm text-red-600">
            {{ $error ?: $errors->first($fieldName) }}
        </p>
    @endif
</div>

{{-- Auto-initialize script --}}
@once
    @push('scripts')
    <script>
        // Auto-initialisation des composants CKEditor
        document.addEventListener('DOMContentLoaded', function() {
            // Attendre que CKEditor soit chargé
            function initializeComponentEditors() {
                if (typeof ClassicEditor === 'undefined') {
                    setTimeout(initializeComponentEditors, 100);
                    return;
                }

                // Initialiser tous les textarea avec data-ckeditor-config
                document.querySelectorAll('textarea[data-ckeditor-config]').forEach(textarea => {
                    const configType = textarea.dataset.ckeditorConfig || 'basic';
                    const placeholder = textarea.placeholder || '';

                    initializeCKEditor(`#${textarea.id}`, configType, {
                        placeholder: placeholder
                    });
                });
            }

            setTimeout(initializeComponentEditors, 500);
        });
    </script>
    @endpush
@endonce
