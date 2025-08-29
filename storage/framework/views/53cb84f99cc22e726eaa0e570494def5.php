<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag; ?>
<?php foreach($attributes->onlyProps([
    'content' => '',
    'field' => '',
    'model' => null,
    'showMeta' => false,
    'emptyMessage' => 'Aucun contenu disponible.',
    'class' => '',
    'showReadingTime' => false
]) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
} ?>
<?php $attributes = $attributes->exceptProps([
    'content' => '',
    'field' => '',
    'model' => null,
    'showMeta' => false,
    'emptyMessage' => 'Aucun contenu disponible.',
    'class' => '',
    'showReadingTime' => false
]); ?>
<?php foreach (array_filter(([
    'content' => '',
    'field' => '',
    'model' => null,
    'showMeta' => false,
    'emptyMessage' => 'Aucun contenu disponible.',
    'class' => '',
    'showReadingTime' => false
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
} ?>
<?php $__defined_vars = get_defined_vars(); ?>
<?php foreach ($attributes as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
} ?>
<?php unset($__defined_vars); ?>

<?php
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
?>

<div <?php echo e($attributes->merge(['class' => 'ckeditor-display-wrapper ' . $class])); ?>>
    <?php if($isEmpty): ?>
        <div class="ckeditor-content-empty">
            <?php echo e($emptyMessage); ?>

        </div>
    <?php else: ?>
        
        <?php if($showMeta && !$isEmpty): ?>
            <div class="flex items-center justify-between text-sm text-slate-500 mb-3 pb-3 border-b border-slate-200">
                <div class="flex items-center space-x-4">
                    <span class="flex items-center">
                        <i class="fas fa-font mr-1"></i>
                        <?php echo e(number_format($wordCount)); ?> mot<?php echo e($wordCount > 1 ? 's' : ''); ?>

                    </span>
                    <?php if($showReadingTime && $readingTime > 0): ?>
                        <span class="flex items-center">
                            <i class="fas fa-clock mr-1"></i>
                            <?php echo e($readingTime); ?> min de lecture
                        </span>
                    <?php endif; ?>
                    <?php if(count($links) > 0): ?>
                        <span class="flex items-center">
                            <i class="fas fa-link mr-1"></i>
                            <?php echo e(count($links)); ?> lien<?php echo e(count($links) > 1 ? 's' : ''); ?>

                        </span>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>

        
        <div class="ckeditor-content <?php echo e($isShort ? 'ckeditor-content-short' : ''); ?>">
            <?php echo $displayContent; ?>

        </div>

        
        <?php if(count($links) > 0 && $showMeta): ?>
            <div class="mt-4 pt-4 border-t border-slate-200">
                <h4 class="text-sm font-medium text-slate-700 mb-2">Liens référencés :</h4>
                <ul class="text-sm space-y-1">
                    <?php $__currentLoopData = $links; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $link): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li class="flex items-center">
                            <?php if($link['is_external']): ?>
                                <i class="fas fa-external-link-alt text-slate-400 mr-2 text-xs"></i>
                            <?php else: ?>
                                <i class="fas fa-link text-slate-400 mr-2 text-xs"></i>
                            <?php endif; ?>
                            <a href="<?php echo e($link['url']); ?>"
                               <?php if($link['is_external']): ?> target="_blank" rel="noopener" <?php endif; ?>
                               class="text-blue-600 hover:text-blue-800">
                                <?php echo e($link['text'] ?: $link['url']); ?>

                            </a>
                        </li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</div>
<?php /**PATH C:\Users\Dell\Desktop\MICRISERVICES\methodiste_belle_ville\resources\views/components/ckeditor-display.blade.php ENDPATH**/ ?>