@props([
    'content' => '',
    'field' => '',
    'model' => null,
    'showMeta' => false,
    'emptyMessage' => 'Aucun contenu disponible.',
    'class' => '',
    'showReadingTime' => false
])

@php
    // Si un modèle est fourni, utiliser ses méthodes
    if ($model && $field) {
        $displayContent = $model->getFormattedContent($field);
        $plainText = $model->getPlainTextContent($field);
        $wordCount = $model->getWordCount($field);
        $readingTime = $model->getReadingTime($field);
        $links = $model->getContentLinks($field);
    } else {
        // Utiliser directement le helper
        $displayContent = \App\Helpers\CKEditorHelper::formatForDisplay($content);
        $plainText = \App\Helpers\CKEditorHelper::toPlainText($content);
        $wordCount = \App\Helpers\CKEditorHelper::wordCount($content);
        $readingTime = \App\Helpers\CKEditorHelper::estimateReadingTime($content);
        $links = \App\Helpers\CKEditorHelper::extractLinks($content);
    }

    $isEmpty = empty(trim($plainText));
    $isShort = strlen($plainText) < 50;
@endphp

<div {{ $attributes->merge(['class' => 'ckeditor-display-wrapper ' . $class]) }}>
    @if($isEmpty)
        <div class="ckeditor-content-empty">
            {{ $emptyMessage }}
        </div>
    @else
        {{-- Métadonnées du contenu --}}
        @if($showMeta && !$isEmpty)
            <div class="flex items-center justify-between text-sm text-slate-500 mb-3 pb-3 border-b border-slate-200">
                <div class="flex items-center space-x-4">
                    <span class="flex items-center">
                        <i class="fas fa-font mr-1"></i>
                        {{ number_format($wordCount) }} mot{{ $wordCount > 1 ? 's' : '' }}
                    </span>
                    @if($showReadingTime && $readingTime > 0)
                        <span class="flex items-center">
                            <i class="fas fa-clock mr-1"></i>
                            {{ $readingTime }} min de lecture
                        </span>
                    @endif
                    @if(count($links) > 0)
                        <span class="flex items-center">
                            <i class="fas fa-link mr-1"></i>
                            {{ count($links) }} lien{{ count($links) > 1 ? 's' : '' }}
                        </span>
                    @endif
                </div>
            </div>
        @endif

        {{-- Contenu formaté --}}
        <div class="ckeditor-content {{ $isShort ? 'ckeditor-content-short' : '' }}">
            {!! $displayContent !!}
        </div>

        {{-- Liens externes --}}
        @if(count($links) > 0 && $showMeta)
            <div class="mt-4 pt-4 border-t border-slate-200">
                <h4 class="text-sm font-medium text-slate-700 mb-2">Liens référencés :</h4>
                <ul class="text-sm space-y-1">
                    @foreach($links as $link)
                        <li class="flex items-center">
                            @if($link['is_external'])
                                <i class="fas fa-external-link-alt text-slate-400 mr-2 text-xs"></i>
                            @else
                                <i class="fas fa-link text-slate-400 mr-2 text-xs"></i>
                            @endif
                            <a href="{{ $link['url'] }}"
                               @if($link['is_external']) target="_blank" rel="noopener" @endif
                               class="text-blue-600 hover:text-blue-800">
                                {{ $link['text'] ?: $link['url'] }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif
    @endif
</div>
