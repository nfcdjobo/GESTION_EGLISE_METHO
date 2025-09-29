<?php $__env->startSection('title', 'Détails du Culte'); ?>

<?php $__env->startSection('content'); ?>
    <div class="space-y-8">
        <!-- Page Title & Breadcrumb -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent">
                        <?php echo e($culte->titre); ?>

                    </h1>
                    <nav class="flex mt-2" aria-label="Breadcrumb">
                        <ol class="inline-flex items-center space-x-1 md:space-x-3">
                            <li class="inline-flex items-center">
                                <a href="<?php echo e(route('private.cultes.index')); ?>"
                                    class="inline-flex items-center text-sm font-medium text-slate-700 hover:text-blue-600 transition-colors">
                                    <i class="fas fa-church mr-2"></i>
                                    Cultes
                                </a>
                            </li>
                            <li>
                                <div class="flex items-center">
                                    <i class="fas fa-chevron-right text-slate-400 mx-2"></i>
                                    <span class="text-sm font-medium text-slate-500"><?php echo e($culte->titre); ?></span>
                                </div>
                            </li>
                        </ol>
                    </nav>
                </div>

                <!-- Actions rapides -->
                <div class="flex items-center space-x-2">
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('cultes.update')): ?>
                        <a href="<?php echo e(route('private.cultes.edit', $culte)); ?>"
                            class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-yellow-600 to-orange-600 text-white text-sm font-medium rounded-xl hover:from-yellow-700 hover:to-orange-700 transition-all duration-200 shadow-md hover:shadow-lg">
                            <i class="fas fa-edit mr-2"></i> Modifier
                        </a>
                    <?php endif; ?>

<?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('cultes.read')): ?>
    <button type="button" onclick="openExportModal('<?php echo e($culte->id); ?>')"
        class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-emerald-600 to-teal-600 text-white text-sm font-medium rounded-xl hover:from-emerald-700 hover:to-teal-700 transition-all duration-200 shadow-md hover:shadow-lg">
        <i class="fas fa-download mr-2"></i> Exporter
    </button>
<?php endif; ?>

                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('cultes.create')): ?>
                        <button type="button" onclick="openDuplicateModal('<?php echo e($culte->id); ?>')"
                            class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-purple-600 to-pink-600 text-white text-sm font-medium rounded-xl hover:from-purple-700 hover:to-pink-700 transition-all duration-200 shadow-md hover:shadow-lg">
                            <i class="fas fa-copy mr-2"></i> Dupliquer
                        </button>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Colonne principale -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Informations générales -->
                <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                    <div class="p-6 border-b border-slate-200">
                        <div class="flex items-center justify-between">
                            <h2 class="text-xl font-bold text-slate-800 flex items-center">
                                <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                                Informations Générales
                            </h2>
                            <?php
                                $statutColors = [
                                    'planifie' => 'bg-blue-100 text-blue-800',
                                    'en_preparation' => 'bg-yellow-100 text-yellow-800',
                                    'en_cours' => 'bg-orange-100 text-orange-800',
                                    'termine' => 'bg-green-100 text-green-800',
                                    'annule' => 'bg-red-100 text-red-800',
                                    'reporte' => 'bg-purple-100 text-purple-800',
                                ];
                            ?>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium <?php echo e($statutColors[$culte->statut] ?? 'bg-gray-100 text-gray-800'); ?>">
                                <?php echo e($culte->statut_libelle); ?>

                            </span>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-4">
                                <div>
                                    <span class="text-sm font-medium text-slate-500">Type de culte</span>
                                    <p class="text-lg font-semibold text-slate-900"><?php echo e($culte->type_culte_libelle); ?></p>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-slate-500">Catégorie</span>
                                    <p class="text-lg font-semibold text-slate-900"><?php echo e($culte->categorie_libelle); ?></p>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-slate-500">Programme</span>
                                    <p class="text-lg font-semibold text-slate-900"><?php echo e($culte->programme->nom ?? 'Non défini'); ?></p>
                                </div>
                            </div>
                            <div class="space-y-4">
                                <div>
                                    <span class="text-sm font-medium text-slate-500">Date</span>
                                    <p class="text-lg font-semibold text-slate-900">
                                        <?php echo e(\Carbon\Carbon::parse($culte->date_culte)->format('l d F Y')); ?>

                                    </p>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-slate-500">Horaires</span>
                                    <p class="text-lg font-semibold text-slate-900">
                                        <?php echo e(\Carbon\Carbon::parse($culte->heure_debut)->format('H:i')); ?>

                                        <?php if($culte->heure_fin): ?>
                                            - <?php echo e(\Carbon\Carbon::parse($culte->heure_fin)->format('H:i')); ?>

                                        <?php endif; ?>
                                    </p>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-slate-500">Lieu</span>
                                    <p class="text-lg font-semibold text-slate-900"><?php echo e($culte->lieu); ?></p>
                                </div>
                            </div>
                        </div>

                        <?php if($culte->description): ?>
                            <div class="mt-6 pt-6 border-t border-slate-200">
                                <h3 class="text-lg font-semibold text-slate-800 mb-3 flex items-center">
                                    <i class="fas fa-align-left text-blue-600 mr-2"></i>
                                    Description
                                </h3>
                                <?php if (isset($component)) { $__componentOriginal55db839a53cf43454e10df1b99ef9479 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal55db839a53cf43454e10df1b99ef9479 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ckeditor-display','data' => ['model' => $culte,'field' => 'description','showMeta' => 'true','class' => 'bg-slate-50 p-4 rounded-lg']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('ckeditor-display'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['model' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($culte),'field' => 'description','show-meta' => 'true','class' => 'bg-slate-50 p-4 rounded-lg']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal55db839a53cf43454e10df1b99ef9479)): ?>
<?php $attributes = $__attributesOriginal55db839a53cf43454e10df1b99ef9479; ?>
<?php unset($__attributesOriginal55db839a53cf43454e10df1b99ef9479); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal55db839a53cf43454e10df1b99ef9479)): ?>
<?php $component = $__componentOriginal55db839a53cf43454e10df1b99ef9479; ?>
<?php unset($__componentOriginal55db839a53cf43454e10df1b99ef9479); ?>
<?php endif; ?>
                            </div>
                        <?php endif; ?>

                        <!-- Options -->
                        <div class="mt-6 pt-6 border-t border-slate-200">
                            <div class="flex flex-wrap gap-2">
                                <?php if($culte->est_public): ?>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-cyan-100 text-cyan-800">
                                        <i class="fas fa-globe mr-1"></i> Public
                                    </span>
                                <?php endif; ?>
                                <?php if($culte->necessite_invitation): ?>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                        <i class="fas fa-envelope mr-1"></i> Sur invitation
                                    </span>
                                <?php endif; ?>
                                <?php if($culte->diffusion_en_ligne): ?>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <i class="fas fa-broadcast-tower mr-1"></i> Diffusion en ligne
                                    </span>
                                <?php endif; ?>
                                <?php if($culte->est_enregistre): ?>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        <i class="fas fa-video mr-1"></i> Enregistré
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Responsables -->
                
                <!-- Officiants et Responsables -->
                <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                    <div class="p-6 border-b border-slate-200">
                        <div class="flex items-center justify-between">
                            <h2 class="text-xl font-bold text-slate-800 flex items-center">
                                <i class="fas fa-users text-purple-600 mr-2"></i>
                                Officiants et Responsables
                            </h2>
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('cultes.update')): ?>
                                <button type="button" onclick="openOfficialsModal('<?php echo e($culte->id); ?>')"
                                    class="inline-flex items-center px-3 py-1 bg-indigo-100 text-indigo-700 text-sm font-medium rounded-lg hover:bg-indigo-200 transition-colors">
                                    <i class="fas fa-edit mr-1"></i> Gérer
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="p-6">
                        <?php if($culte->officiants_detail && $culte->officiants_detail->count() > 0): ?>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <?php $__currentLoopData = $culte->officiants_detail; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $officiant): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php
                                        // Icônes et couleurs selon le titre
                                        $iconMap = [
                                            'pasteur' => ['icon' => 'fa-user-tie', 'color' => 'blue'],
                                            'prédicateur' => ['icon' => 'fa-microphone', 'color' => 'green'],
                                            'predicateur' => ['icon' => 'fa-microphone', 'color' => 'green'],
                                            'louange' => ['icon' => 'fa-music', 'color' => 'amber'],
                                            'responsable' => ['icon' => 'fa-user-cog', 'color' => 'purple'],
                                            'organisateur' => ['icon' => 'fa-user-cog', 'color' => 'purple'],
                                            'maître' => ['icon' => 'fa-microphone-alt', 'color' => 'pink'],
                                            'interprète' => ['icon' => 'fa-language', 'color' => 'cyan'],
                                            'interprete' => ['icon' => 'fa-language', 'color' => 'cyan'],
                                        ];

                                        $titreLower = strtolower($officiant['titre']);
                                        $iconData = null;

                                        foreach ($iconMap as $key => $data) {
                                            if (strpos($titreLower, $key) !== false) {
                                                $iconData = $data;
                                                break;
                                            }
                                        }

                                        if (!$iconData) {
                                            $iconData = ['icon' => 'fa-user', 'color' => 'slate'];
                                        }
                                    ?>

                                    <div class="flex items-center space-x-3 p-4 bg-gradient-to-r from-<?php echo e($iconData['color']); ?>-50 to-<?php echo e($iconData['color']); ?>-50 rounded-xl hover:shadow-md transition-all duration-200">
                                        <div class="w-12 h-12 bg-<?php echo e($iconData['color']); ?>-100 rounded-full flex items-center justify-center flex-shrink-0">
                                            <i class="fas <?php echo e($iconData['icon']); ?> text-<?php echo e($iconData['color']); ?>-600 text-lg"></i>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-medium text-slate-500 truncate"><?php echo e($officiant['titre']); ?></p>
                                            <p class="font-semibold text-slate-900 truncate"><?php echo e($officiant['nom_complet']); ?></p>
                                            <?php if($officiant['provenance'] !== 'Église Locale'): ?>
                                                <p class="text-xs text-slate-500 truncate">
                                                    <i class="fas fa-map-marker-alt mr-1"></i><?php echo e($officiant['provenance']); ?>

                                                </p>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        <?php else: ?>
                            <div class="text-center py-8 text-slate-500">
                                <i class="fas fa-user-slash text-3xl mb-2"></i>
                                <p class="italic">Aucun officiant assigné à ce culte</p>
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('cultes.manage-participants')): ?>
                                    <button type="button" onclick="openOfficialsModal('<?php echo e($culte->id); ?>')"
                                        class="mt-3 inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition-colors">
                                        <i class="fas fa-plus mr-2"></i>Ajouter des officiants
                                    </button>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Message et prédication -->
                <?php if($culte->titre_message || $culte->passage_biblique || $culte->resume_message || $culte->plan_message): ?>
                    <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                        <div class="p-6 border-b border-slate-200">
                            <h2 class="text-xl font-bold text-slate-800 flex items-center">
                                <i class="fas fa-bible text-amber-600 mr-2"></i>
                                Message et Prédication
                            </h2>
                        </div>
                        <div class="p-6 space-y-6">
                            <?php if($culte->titre_message): ?>
                                <div>
                                    <h3 class="text-lg font-semibold text-slate-800 mb-2">Titre du message</h3>
                                    <p class="text-xl font-bold text-blue-700"><?php echo e($culte->titre_message); ?></p>
                                </div>
                            <?php endif; ?>

                            <?php if($culte->passage_biblique): ?>
                                <div>
                                    <h3 class="text-lg font-semibold text-slate-800 mb-2">Passage biblique</h3>
                                    <p class="text-lg font-semibold text-blue-700 bg-blue-50 p-3 rounded-lg">
                                        <?php echo e($culte->passage_biblique); ?>

                                    </p>
                                </div>
                            <?php endif; ?>

                            <?php if($culte->resume_message): ?>
                                <div>
                                    <h3 class="text-lg font-semibold text-slate-800 mb-3">Résumé du message</h3>
                                    <?php if (isset($component)) { $__componentOriginal55db839a53cf43454e10df1b99ef9479 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal55db839a53cf43454e10df1b99ef9479 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ckeditor-display','data' => ['model' => $culte,'field' => 'resume_message','showMeta' => 'true','showReadingTime' => 'true']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('ckeditor-display'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['model' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($culte),'field' => 'resume_message','show-meta' => 'true','show-reading-time' => 'true']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal55db839a53cf43454e10df1b99ef9479)): ?>
<?php $attributes = $__attributesOriginal55db839a53cf43454e10df1b99ef9479; ?>
<?php unset($__attributesOriginal55db839a53cf43454e10df1b99ef9479); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal55db839a53cf43454e10df1b99ef9479)): ?>
<?php $component = $__componentOriginal55db839a53cf43454e10df1b99ef9479; ?>
<?php unset($__componentOriginal55db839a53cf43454e10df1b99ef9479); ?>
<?php endif; ?>
                                </div>
                            <?php endif; ?>

                            <?php if($culte->plan_message): ?>
                                <div>
                                    <h3 class="text-lg font-semibold text-slate-800 mb-3">Plan du message</h3>
                                    <?php if (isset($component)) { $__componentOriginal55db839a53cf43454e10df1b99ef9479 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal55db839a53cf43454e10df1b99ef9479 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ckeditor-display','data' => ['model' => $culte,'field' => 'plan_message','showMeta' => 'true','class' => 'bg-amber-50 p-4 rounded-lg border border-amber-200']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('ckeditor-display'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['model' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($culte),'field' => 'plan_message','show-meta' => 'true','class' => 'bg-amber-50 p-4 rounded-lg border border-amber-200']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal55db839a53cf43454e10df1b99ef9479)): ?>
<?php $attributes = $__attributesOriginal55db839a53cf43454e10df1b99ef9479; ?>
<?php unset($__attributesOriginal55db839a53cf43454e10df1b99ef9479); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal55db839a53cf43454e10df1b99ef9479)): ?>
<?php $component = $__componentOriginal55db839a53cf43454e10df1b99ef9479; ?>
<?php unset($__componentOriginal55db839a53cf43454e10df1b99ef9479); ?>
<?php endif; ?>
                                </div>
                            <?php endif; ?>

                            <?php if($culte->resume_message || $culte->plan_message): ?>
                                <div class="bg-gradient-to-r from-blue-50 to-amber-50 p-4 rounded-lg border border-blue-200">
                                    <h4 class="font-semibold text-slate-800 mb-2">Aperçu du message</h4>
                                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4 text-sm">
                                        <div class="text-center">
                                            <div class="text-lg font-bold text-blue-600"><?php echo e($culte->getMessageWordCount()); ?></div>
                                            <div class="text-slate-600">Mots au total</div>
                                        </div>
                                        <div class="text-center">
                                            <div class="text-lg font-bold text-amber-600"><?php echo e($culte->getMessageReadingTime()); ?></div>
                                            <div class="text-slate-600">Min de lecture</div>
                                        </div>
                                        <div class="text-center">
                                            <div class="text-lg font-bold text-purple-600"><?php echo e($culte->hasRichContent() ? 'Oui' : 'Non'); ?></div>
                                            <div class="text-slate-600">Mise en forme</div>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Statistiques et participation -->
                <?php if($culte->statut === 'termine' || $culte->nombre_participants): ?>
                    <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                        <div class="p-6 border-b border-slate-200">
                            <h2 class="text-xl font-bold text-slate-800 flex items-center">
                                <i class="fas fa-chart-bar text-green-600 mr-2"></i>
                                Statistiques et Participation
                            </h2>
                        </div>
                        <div class="p-6">
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                <?php if($culte->nombre_participants): ?>
                                    <div class="text-center p-4 bg-gradient-to-r from-blue-50 to-cyan-50 rounded-xl">
                                        <div class="text-2xl font-bold text-blue-600"><?php echo e($culte->nombre_participants); ?></div>
                                        <div class="text-sm text-slate-600">Participants</div>
                                    </div>
                                <?php endif; ?>

                                <?php if($culte->nombre_adultes): ?>
                                    <div class="text-center p-4 bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl">
                                        <div class="text-2xl font-bold text-green-600"><?php echo e($culte->nombre_adultes); ?></div>
                                        <div class="text-sm text-slate-600">Adultes</div>
                                    </div>
                                <?php endif; ?>

                                <?php if($culte->nombre_jeunes): ?>
                                    <div class="text-center p-4 bg-gradient-to-r from-purple-50 to-pink-50 rounded-xl">
                                        <div class="text-2xl font-bold text-purple-600"><?php echo e($culte->nombre_jeunes); ?></div>
                                        <div class="text-sm text-slate-600">Jeunes</div>
                                    </div>
                                <?php endif; ?>

                                <?php if($culte->nombre_enfants): ?>
                                    <div class="text-center p-4 bg-gradient-to-r from-amber-50 to-orange-50 rounded-xl">
                                        <div class="text-2xl font-bold text-amber-600"><?php echo e($culte->nombre_enfants); ?></div>
                                        <div class="text-sm text-slate-600">Enfants</div>
                                    </div>
                                <?php endif; ?>

                                <?php if($culte->nombre_nouveaux): ?>
                                    <div class="text-center p-4 bg-gradient-to-r from-cyan-50 to-blue-50 rounded-xl">
                                        <div class="text-2xl font-bold text-cyan-600"><?php echo e($culte->nombre_nouveaux); ?></div>
                                        <div class="text-sm text-slate-600">Nouveaux</div>
                                    </div>
                                <?php endif; ?>

                                <?php if($culte->nombre_conversions): ?>
                                    <div class="text-center p-4 bg-gradient-to-r from-yellow-50 to-amber-50 rounded-xl">
                                        <div class="text-2xl font-bold text-yellow-600"><?php echo e($culte->nombre_conversions); ?></div>
                                        <div class="text-sm text-slate-600">Conversions</div>
                                    </div>
                                <?php endif; ?>

                                <?php if($culte->nombre_baptemes): ?>
                                    <div class="text-center p-4 bg-gradient-to-r from-indigo-50 to-purple-50 rounded-xl">
                                        <div class="text-2xl font-bold text-indigo-600"><?php echo e($culte->nombre_baptemes); ?></div>
                                        <div class="text-sm text-slate-600">Baptêmes</div>
                                    </div>
                                <?php endif; ?>

                                <?php if($culte->offrande_totale): ?>
                                    <div class="text-center p-4 bg-gradient-to-r from-emerald-50 to-green-50 rounded-xl">
                                        <div class="text-2xl font-bold text-emerald-600"><?php echo e(number_format($culte->offrande_totale, 0)); ?>FCFA</div>
                                        <div class="text-sm text-slate-600">Offrandes</div>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <?php if($culte->heure_debut_reelle || $culte->heure_fin_reelle): ?>
                                <div class="mt-6 pt-6 border-t border-slate-200">
                                    <h3 class="text-lg font-semibold text-slate-800 mb-4">Horaires réels</h3>
                                    <div class="flex items-center space-x-6">
                                        <?php if($culte->heure_debut_reelle): ?>
                                            <div>
                                                <span class="text-sm font-medium text-slate-500">Début réel</span>
                                                <p class="text-lg font-semibold text-slate-900"><?php echo e(\Carbon\Carbon::parse($culte->heure_debut_reelle)->format('H:i')); ?></p>
                                            </div>
                                        <?php endif; ?>
                                        <?php if($culte->heure_fin_reelle): ?>
                                            <div>
                                                <span class="text-sm font-medium text-slate-500">Fin réelle</span>
                                                <p class="text-lg font-semibold text-slate-900"><?php echo e(\Carbon\Carbon::parse($culte->heure_fin_reelle)->format('H:i')); ?></p>
                                            </div>
                                        <?php endif; ?>
                                        <?php if($culte->duree_totale): ?>
                                            <div>
                                                <span class="text-sm font-medium text-slate-500">Durée totale</span>
                                                <p class="text-lg font-semibold text-slate-900"><?php echo e($culte->duree_totale); ?></p>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Statistiques Financières Détaillées -->
                <?php if(isset($fondsStatistiques) && $fondsStatistiques['total_transactions'] > 0): ?>
                    <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                        <div class="p-6 border-b border-slate-200">
                            <h2 class="text-xl font-bold text-slate-800 flex items-center">
                                <i class="fas fa-coins text-yellow-600 mr-2"></i>
                                Statistiques Financières
                            </h2>
                        </div>
                        <div class="p-6 space-y-6">
                            <!-- Métriques principales -->
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                <div class="text-center p-4 bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl">
                                    <div class="text-2xl font-bold text-green-600"><?php echo e(number_format($fondsStatistiques['montant_total'], 0)); ?></div>
                                    <div class="text-sm text-slate-600">FCFA Total</div>
                                </div>
                                <div class="text-center p-4 bg-gradient-to-r from-blue-50 to-cyan-50 rounded-xl">
                                    <div class="text-2xl font-bold text-blue-600"><?php echo e($fondsStatistiques['total_transactions']); ?></div>
                                    <div class="text-sm text-slate-600">Transactions</div>
                                </div>
                                <div class="text-center p-4 bg-gradient-to-r from-purple-50 to-pink-50 rounded-xl">
                                    <div class="text-2xl font-bold text-purple-600"><?php echo e($fondsStatistiques['donateurs_uniques']); ?></div>
                                    <div class="text-sm text-slate-600">Donateurs</div>
                                </div>
                                <div class="text-center p-4 bg-gradient-to-r from-amber-50 to-orange-50 rounded-xl">
                                    <div class="text-2xl font-bold text-amber-600"><?php echo e(number_format($metriques['transaction_moyenne'], 0)); ?></div>
                                    <div class="text-sm text-slate-600">FCFA/Transaction</div>
                                </div>
                            </div>

                            <!-- Ratios par participant -->
                            <?php if($culte->nombre_participants > 0): ?>
                                <div class="bg-gradient-to-r from-slate-50 to-blue-50 p-4 rounded-xl border border-slate-200">
                                    <h3 class="text-lg font-semibold text-slate-800 mb-4 flex items-center">
                                        <i class="fas fa-calculator text-blue-600 mr-2"></i>
                                        Ratios par Participant
                                    </h3>
                                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                        <div class="text-center">
                                            <div class="text-xl font-bold text-blue-600"><?php echo e(number_format($metriques['offrande_par_participant'], 0)); ?> FCFA</div>
                                            <div class="text-sm text-slate-600">Total/Participant</div>
                                        </div>
                                        <div class="text-center">
                                            <div class="text-xl font-bold text-green-600"><?php echo e(number_format($metriques['dime_par_participant'], 0)); ?> FCFA</div>
                                            <div class="text-sm text-slate-600">Dîme/Participant</div>
                                        </div>
                                        <div class="text-center">
                                            <div class="text-xl font-bold text-purple-600"><?php echo e(number_format($metriques['offrande_pure_par_participant'], 0)); ?> FCFA</div>
                                            <div class="text-sm text-slate-600">Offrande/Participant</div>
                                        </div>
                                        <div class="text-center">
                                            <div class="text-xl font-bold text-orange-600"><?php echo e($metriques['taux_participation_financiere']); ?>%</div>
                                            <div class="text-sm text-slate-600">Taux participation</div>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <!-- Répartition par type -->
                            <?php if(count($fondsStatistiques['par_type']) > 0): ?>
                                <div>
                                    <h3 class="text-lg font-semibold text-slate-800 mb-4 flex items-center">
                                        <i class="fas fa-chart-pie text-green-600 mr-2"></i>
                                        Répartition par Type
                                    </h3>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <?php $__currentLoopData = $fondsStatistiques['par_type']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type => $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <div class="flex items-center justify-between p-3 bg-slate-50 rounded-lg">
                                                <div>
                                                    <span class="font-medium text-slate-700 capitalize"><?php echo e(str_replace('_', ' ', $type)); ?></span>
                                                    <div class="text-sm text-slate-500"><?php echo e($data['nombre']); ?> transaction(s)</div>
                                                </div>
                                                <div class="text-right">
                                                    <div class="font-bold text-slate-900"><?php echo e(number_format($data['montant'], 0)); ?> FCFA</div>
                                                    <div class="text-sm text-slate-500"><?php echo e($data['pourcentage']); ?>%</div>
                                                </div>
                                            </div>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <!-- Comparaison avec moyennes -->
                            <?php if(isset($metriques['comparaison']) && $metriques['comparaison']['moyenne_type_culte'] > 0): ?>
                                <div class="bg-gradient-to-r from-yellow-50 to-amber-50 p-4 rounded-xl border border-yellow-200">
                                    <h3 class="text-lg font-semibold text-slate-800 mb-3 flex items-center">
                                        <i class="fas fa-chart-line text-amber-600 mr-2"></i>
                                        Comparaison avec la Moyenne
                                    </h3>
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                        <div class="text-center">
                                            <div class="text-lg font-bold text-slate-900"><?php echo e(number_format($fondsStatistiques['montant_total'], 0)); ?> FCFA</div>
                                            <div class="text-sm text-slate-600">Ce culte</div>
                                        </div>
                                        <div class="text-center">
                                            <div class="text-lg font-bold text-slate-700"><?php echo e(number_format($metriques['comparaison']['moyenne_type_culte'], 0)); ?> FCFA</div>
                                            <div class="text-sm text-slate-600">Moyenne <?php echo e($culte->type_culte_libelle); ?></div>
                                        </div>
                                        <div class="text-center">
                                            <?php
                                                $ecart = $metriques['comparaison']['ecart_pourcentage'];
                                                $couleur = $ecart > 0 ? 'text-green-600' : 'text-red-600';
                                                $icone = $ecart > 0 ? 'fas fa-arrow-up' : 'fas fa-arrow-down';
                                            ?>
                                            <div class="text-lg font-bold <?php echo e($couleur); ?> flex items-center justify-center">
                                                <i class="<?php echo e($icone); ?> mr-1"></i>
                                                <?php echo e(abs($ecart)); ?>%
                                            </div>
                                            <div class="text-sm text-slate-600">Écart</div>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <!-- Top donateurs -->
                            <?php if(count($fondsStatistiques['top_donateurs']) > 0): ?>
                                <div>
                                    <h3 class="text-lg font-semibold text-slate-800 mb-4 flex items-center">
                                        <i class="fas fa-trophy text-yellow-600 mr-2"></i>
                                        Top Donateurs
                                    </h3>
                                    <div class="space-y-2">
                                        <?php $__currentLoopData = $fondsStatistiques['top_donateurs']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $donateur): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <div class="flex items-center justify-between p-3 bg-slate-50 rounded-lg">
                                                <div class="flex items-center space-x-3">
                                                    <div class="w-8 h-8 bg-gradient-to-r from-yellow-400 to-orange-500 rounded-full flex items-center justify-center text-white font-bold text-sm">
                                                        <?php echo e($index + 1); ?>

                                                    </div>
                                                    <div>
                                                        <span class="font-medium text-slate-700"><?php echo e($donateur['donateur']); ?></span>
                                                        <div class="text-sm text-slate-500"><?php echo e($donateur['nombre_dons']); ?> don(s)</div>
                                                    </div>
                                                </div>
                                                <div class="font-bold text-slate-900"><?php echo e(number_format($donateur['montant_total'], 0)); ?> FCFA</div>
                                            </div>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <!-- Modes de paiement -->
                            <?php if(count($fondsStatistiques['par_mode_paiement']) > 0): ?>
                                <div>
                                    <h3 class="text-lg font-semibold text-slate-800 mb-4 flex items-center">
                                        <i class="fas fa-credit-card text-indigo-600 mr-2"></i>
                                        Modes de Paiement
                                    </h3>
                                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                                        <?php $__currentLoopData = $fondsStatistiques['par_mode_paiement']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $mode => $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <div class="text-center p-3 bg-slate-50 rounded-lg">
                                                <div class="font-medium text-slate-700 capitalize mb-1"><?php echo e(str_replace('_', ' ', $mode)); ?></div>
                                                <div class="text-lg font-bold text-slate-900"><?php echo e(number_format($data['montant'], 0)); ?> FCFA</div>
                                                <div class="text-sm text-slate-500"><?php echo e($data['nombre']); ?> transaction(s) (<?php echo e($data['pourcentage']); ?>%)</div>
                                            </div>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <!-- Informations supplémentaires -->
                            <?php if($fondsStatistiques['transactions_anonymes'] > 0 || $fondsStatistiques['dons_en_nature'] > 0): ?>
                                <div class="bg-gradient-to-r from-gray-50 to-slate-50 p-4 rounded-xl">
                                    <h3 class="text-lg font-semibold text-slate-800 mb-3">Informations Complémentaires</h3>
                                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                                        <?php if($fondsStatistiques['transactions_anonymes'] > 0): ?>
                                            <div class="text-center">
                                                <div class="text-lg font-bold text-slate-600"><?php echo e($fondsStatistiques['transactions_anonymes']); ?></div>
                                                <div class="text-slate-500">Dons anonymes</div>
                                            </div>
                                        <?php endif; ?>
                                        <?php if($fondsStatistiques['dons_en_nature'] > 0): ?>
                                            <div class="text-center">
                                                <div class="text-lg font-bold text-slate-600"><?php echo e($fondsStatistiques['dons_en_nature']); ?></div>
                                                <div class="text-slate-500">Dons en nature</div>
                                            </div>
                                        <?php endif; ?>
                                        <?php if($fondsStatistiques['recus_demandes'] > 0): ?>
                                            <div class="text-center">
                                                <div class="text-lg font-bold text-slate-600"><?php echo e($fondsStatistiques['recus_demandes']); ?></div>
                                                <div class="text-slate-500">Reçus demandés</div>
                                            </div>
                                        <?php endif; ?>
                                        <?php if($fondsStatistiques['recus_emis'] > 0): ?>
                                            <div class="text-center">
                                                <div class="text-lg font-bold text-slate-600"><?php echo e($fondsStatistiques['recus_emis']); ?></div>
                                                <div class="text-slate-500">Reçus émis</div>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Notes et commentaires -->
                <?php if($culte->notes_pasteur || $culte->notes_organisateur || $culte->temoignages || $culte->points_forts || $culte->points_amelioration): ?>
                    <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                        <div class="p-6 border-b border-slate-200">
                            <h2 class="text-xl font-bold text-slate-800 flex items-center">
                                <i class="fas fa-comment-alt text-cyan-600 mr-2"></i>
                                Notes et Commentaires
                            </h2>
                        </div>
                        <div class="p-6 space-y-6">
                            <?php if($culte->notes_pasteur): ?>
                                <div>
                                    <h3 class="text-lg font-semibold text-slate-800 mb-3 flex items-center">
                                        <i class="fas fa-user-tie text-blue-600 mr-2"></i>
                                        Notes du pasteur
                                    </h3>
                                    <?php if (isset($component)) { $__componentOriginal55db839a53cf43454e10df1b99ef9479 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal55db839a53cf43454e10df1b99ef9479 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ckeditor-display','data' => ['model' => $culte,'field' => 'notes_pasteur','class' => 'bg-blue-50 p-4 rounded-lg border border-blue-200','showMeta' => 'true']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('ckeditor-display'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['model' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($culte),'field' => 'notes_pasteur','class' => 'bg-blue-50 p-4 rounded-lg border border-blue-200','show-meta' => 'true']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal55db839a53cf43454e10df1b99ef9479)): ?>
<?php $attributes = $__attributesOriginal55db839a53cf43454e10df1b99ef9479; ?>
<?php unset($__attributesOriginal55db839a53cf43454e10df1b99ef9479); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal55db839a53cf43454e10df1b99ef9479)): ?>
<?php $component = $__componentOriginal55db839a53cf43454e10df1b99ef9479; ?>
<?php unset($__componentOriginal55db839a53cf43454e10df1b99ef9479); ?>
<?php endif; ?>
                                </div>
                            <?php endif; ?>

                            <?php if($culte->notes_organisateur): ?>
                                <div>
                                    <h3 class="text-lg font-semibold text-slate-800 mb-3 flex items-center">
                                        <i class="fas fa-user-cog text-green-600 mr-2"></i>
                                        Notes de l'organisateur
                                    </h3>
                                    <?php if (isset($component)) { $__componentOriginal55db839a53cf43454e10df1b99ef9479 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal55db839a53cf43454e10df1b99ef9479 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ckeditor-display','data' => ['model' => $culte,'field' => 'notes_organisateur','class' => 'bg-green-50 p-4 rounded-lg border border-green-200','showMeta' => 'true']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('ckeditor-display'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['model' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($culte),'field' => 'notes_organisateur','class' => 'bg-green-50 p-4 rounded-lg border border-green-200','show-meta' => 'true']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal55db839a53cf43454e10df1b99ef9479)): ?>
<?php $attributes = $__attributesOriginal55db839a53cf43454e10df1b99ef9479; ?>
<?php unset($__attributesOriginal55db839a53cf43454e10df1b99ef9479); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal55db839a53cf43454e10df1b99ef9479)): ?>
<?php $component = $__componentOriginal55db839a53cf43454e10df1b99ef9479; ?>
<?php unset($__componentOriginal55db839a53cf43454e10df1b99ef9479); ?>
<?php endif; ?>
                                </div>
                            <?php endif; ?>

                            <?php if($culte->points_forts || $culte->points_amelioration): ?>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <?php if($culte->points_forts): ?>
                                        <div>
                                            <h3 class="text-lg font-semibold text-slate-800 mb-3 flex items-center">
                                                <i class="fas fa-thumbs-up text-emerald-600 mr-2"></i>
                                                Points forts
                                            </h3>
                                            <?php if (isset($component)) { $__componentOriginal55db839a53cf43454e10df1b99ef9479 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal55db839a53cf43454e10df1b99ef9479 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ckeditor-display','data' => ['model' => $culte,'field' => 'points_forts','class' => 'bg-emerald-50 p-4 rounded-lg border border-emerald-200']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('ckeditor-display'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['model' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($culte),'field' => 'points_forts','class' => 'bg-emerald-50 p-4 rounded-lg border border-emerald-200']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal55db839a53cf43454e10df1b99ef9479)): ?>
<?php $attributes = $__attributesOriginal55db839a53cf43454e10df1b99ef9479; ?>
<?php unset($__attributesOriginal55db839a53cf43454e10df1b99ef9479); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal55db839a53cf43454e10df1b99ef9479)): ?>
<?php $component = $__componentOriginal55db839a53cf43454e10df1b99ef9479; ?>
<?php unset($__componentOriginal55db839a53cf43454e10df1b99ef9479); ?>
<?php endif; ?>
                                        </div>
                                    <?php endif; ?>

                                    <?php if($culte->points_amelioration): ?>
                                        <div>
                                            <h3 class="text-lg font-semibold text-slate-800 mb-3 flex items-center">
                                                <i class="fas fa-arrow-up text-amber-600 mr-2"></i>
                                                Points d'amélioration
                                            </h3>
                                            <?php if (isset($component)) { $__componentOriginal55db839a53cf43454e10df1b99ef9479 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal55db839a53cf43454e10df1b99ef9479 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ckeditor-display','data' => ['model' => $culte,'field' => 'points_amelioration','class' => 'bg-amber-50 p-4 rounded-lg border border-amber-200']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('ckeditor-display'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['model' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($culte),'field' => 'points_amelioration','class' => 'bg-amber-50 p-4 rounded-lg border border-amber-200']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal55db839a53cf43454e10df1b99ef9479)): ?>
<?php $attributes = $__attributesOriginal55db839a53cf43454e10df1b99ef9479; ?>
<?php unset($__attributesOriginal55db839a53cf43454e10df1b99ef9479); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal55db839a53cf43454e10df1b99ef9479)): ?>
<?php $component = $__componentOriginal55db839a53cf43454e10df1b99ef9479; ?>
<?php unset($__componentOriginal55db839a53cf43454e10df1b99ef9479); ?>
<?php endif; ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>

                            <?php if($culte->temoignages): ?>
                                <div>
                                    <h3 class="text-lg font-semibold text-slate-800 mb-3 flex items-center">
                                        <i class="fas fa-heart text-purple-600 mr-2"></i>
                                        Témoignages
                                    </h3>
                                    <?php if (isset($component)) { $__componentOriginal55db839a53cf43454e10df1b99ef9479 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal55db839a53cf43454e10df1b99ef9479 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ckeditor-display','data' => ['model' => $culte,'field' => 'temoignages','class' => 'bg-purple-50 p-4 rounded-lg border border-purple-200','showMeta' => 'true']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('ckeditor-display'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['model' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($culte),'field' => 'temoignages','class' => 'bg-purple-50 p-4 rounded-lg border border-purple-200','show-meta' => 'true']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal55db839a53cf43454e10df1b99ef9479)): ?>
<?php $attributes = $__attributesOriginal55db839a53cf43454e10df1b99ef9479; ?>
<?php unset($__attributesOriginal55db839a53cf43454e10df1b99ef9479); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal55db839a53cf43454e10df1b99ef9479)): ?>
<?php $component = $__componentOriginal55db839a53cf43454e10df1b99ef9479; ?>
<?php unset($__componentOriginal55db839a53cf43454e10df1b99ef9479); ?>
<?php endif; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Actions rapides -->
                <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                    <div class="p-6 border-b border-slate-200">
                        <h2 class="text-xl font-bold text-slate-800 flex items-center">
                            <i class="fas fa-bolt text-yellow-600 mr-2"></i>
                            Actions Rapides
                        </h2>
                    </div>
                    <div class="p-6 space-y-3">
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('participant_cultes.ajouter-participant')): ?>
                            <a href="<?php echo e(route('private.cultes.participants', $culte->id)); ?>"
                                class="w-full inline-flex items-center justify-center px-4 py-2 bg-gradient-to-r from-green-600 to-emerald-600 text-white text-sm font-medium rounded-xl hover:from-green-700 hover:to-emerald-700 transition-all duration-200">
                                <i class="fas fa-user-plus mr-2"></i> Ajouter des participants
                            </a>
                        <?php endif; ?>

                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('fonds.create')): ?>
                            <a href="<?php echo e(route('private.fonds.create', ['culte_id' => $culte->id])); ?>"
                                class="w-full inline-flex items-center justify-center px-4 py-2 bg-gradient-to-r from-emerald-600 to-teal-600 text-white text-sm font-medium rounded-xl hover:from-emerald-700 hover:to-teal-700 transition-all duration-200">
                                <i class="fas fa-coins mr-2"></i> Ajouter une transaction
                            </a>
                        <?php endif; ?>

                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('cultes.change-status')): ?>
                        <?php if($culte->statut !== 'termine'): ?>
                            <button type="button" onclick="openStatusModal('<?php echo e($culte->id); ?>', '<?php echo e($culte->statut); ?>')"
                                class="w-full inline-flex items-center justify-center px-4 py-2 bg-gradient-to-r from-blue-600 to-cyan-600 text-white text-sm font-medium rounded-xl hover:from-blue-700 hover:to-cyan-700 transition-all duration-200">
                                <i class="fas fa-exchange-alt mr-2"></i> Changer le statut
                            </button>
                        <?php endif; ?>
                        <?php endif; ?>

                        <?php if($culte->lien_diffusion_live): ?>
                            <a href="<?php echo e($culte->lien_diffusion_live); ?>" target="_blank"
                                class="w-full inline-flex items-center justify-center px-4 py-2 bg-gradient-to-r from-red-500 to-pink-500 text-white text-sm font-medium rounded-xl hover:from-red-600 hover:to-pink-600 transition-all duration-200">
                                <i class="fas fa-broadcast-tower mr-2"></i> Diffusion live
                            </a>
                        <?php endif; ?>

                        <?php if($culte->lien_enregistrement_video): ?>
                            <a href="<?php echo e($culte->lien_enregistrement_video); ?>" target="_blank"
                                class="w-full inline-flex items-center justify-center px-4 py-2 bg-gradient-to-r from-purple-600 to-pink-600 text-white text-sm font-medium rounded-xl hover:from-purple-700 hover:to-pink-700 transition-all duration-200">
                                <i class="fas fa-video mr-2"></i> Enregistrement vidéo
                            </a>
                        <?php endif; ?>

                        
                        <?php if($culte->lien_enregistrement_audio): ?>
                            <a href="<?php echo e($culte->lien_enregistrement_audio); ?>" target="_blank"
                                class="w-full inline-flex items-center justify-center px-4 py-2 bg-gradient-to-r from-amber-600 to-orange-600 text-white text-sm font-medium rounded-xl hover:from-amber-700 hover:to-orange-700 transition-all duration-200">
                                <i class="fas fa-volume-up mr-2"></i> Enregistrement audio
                            </a>
                        <?php endif; ?>

                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('cultes.delete')): ?>
                            <?php if($culte->statut !== 'en_cours'): ?>
                                <button type="button" onclick="deleteCulte('<?php echo e($culte->id); ?>')"
                                    class="w-full inline-flex items-center justify-center px-4 py-2 bg-gradient-to-r from-red-600 to-rose-600 text-white text-sm font-medium rounded-xl hover:from-red-700 hover:to-rose-700 transition-all duration-200">
                                    <i class="fas fa-trash mr-2"></i> Supprimer
                                </button>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Résumé Financier Rapide -->
                <?php if(isset($fondsStatistiques) && $fondsStatistiques['total_transactions'] > 0): ?>
                    <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                        <div class="p-6 border-b border-slate-200">
                            <h2 class="text-xl font-bold text-slate-800 flex items-center">
                                <i class="fas fa-wallet text-green-600 mr-2"></i>
                                Résumé Financier
                            </h2>
                        </div>
                        <div class="p-6 space-y-4">
                            <div class="text-center p-4 bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl">
                                <div class="text-2xl font-bold text-green-600"><?php echo e(number_format($fondsStatistiques['montant_total'], 0)); ?></div>
                                <div class="text-sm text-slate-600">FCFA collectés</div>
                            </div>

                            <?php if($culte->nombre_participants > 0): ?>
                                <div class="space-y-2 text-sm">
                                    <div class="flex justify-between">
                                        <span class="text-slate-600">Par participant:</span>
                                        <span class="font-semibold"><?php echo e(number_format($metriques['offrande_par_participant'], 0)); ?> FCFA</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-slate-600">Participation:</span>
                                        <span class="font-semibold"><?php echo e($metriques['taux_participation_financiere']); ?>%</span>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <!-- Répartition Dîmes/Offrandes -->
                            <?php if(isset($metriques['pourcentage_dimes']) && ($metriques['pourcentage_dimes'] > 0 || $metriques['pourcentage_offrandes'] > 0)): ?>
                                <div class="space-y-2">
                                    <div class="text-sm font-medium text-slate-700">Répartition:</div>
                                    <div class="space-y-1">
                                        <div class="flex items-center justify-between text-sm">
                                            <span class="text-slate-600">Dîmes:</span>
                                            <span class="font-semibold text-blue-600"><?php echo e($metriques['pourcentage_dimes']); ?>%</span>
                                        </div>
                                        <div class="w-full bg-slate-200 rounded-full h-2">
                                            <div class="bg-blue-600 h-2 rounded-full" style="width: <?php echo e($metriques['pourcentage_dimes']); ?>%"></div>
                                        </div>
                                        <div class="flex items-center justify-between text-sm">
                                            <span class="text-slate-600">Offrandes:</span>
                                            <span class="font-semibold text-purple-600"><?php echo e($metriques['pourcentage_offrandes']); ?>%</span>
                                        </div>
                                        <div class="w-full bg-slate-200 rounded-full h-2">
                                            <div class="bg-purple-600 h-2 rounded-full" style="width: <?php echo e($metriques['pourcentage_offrandes']); ?>%"></div>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <?php if($fondsStatistiques['total_transactions'] > 0): ?>
                                <div class="text-center pt-2 border-t border-slate-200">
                                    <div class="text-xs text-slate-500">
                                        <?php echo e($fondsStatistiques['total_transactions']); ?> transaction(s) •
                                        <?php echo e($fondsStatistiques['donateurs_uniques']); ?> donateur(s)
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Évaluations -->
                <?php if($culte->note_globale || $culte->note_louange || $culte->note_message || $culte->note_organisation): ?>
                    <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                        <div class="p-6 border-b border-slate-200">
                            <h2 class="text-xl font-bold text-slate-800 flex items-center">
                                <i class="fas fa-star text-amber-600 mr-2"></i>
                                Évaluations
                            </h2>
                        </div>
                        <div class="p-6 space-y-4">
                            <?php if($culte->note_globale): ?>
                                <div class="flex items-center justify-between">
                                    <span class="text-sm font-medium text-slate-700">Note globale</span>
                                    <div class="flex items-center">
                                        <span class="text-lg font-bold text-amber-600 mr-2"><?php echo e($culte->note_globale); ?>/10</span>
                                        <div class="flex">
                                            <?php for($i = 1; $i <= 10; $i++): ?>
                                                <i class="fas fa-star text-xs <?php echo e($i <= $culte->note_globale ? 'text-amber-400' : 'text-slate-300'); ?>"></i>
                                            <?php endfor; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <?php if($culte->note_louange): ?>
                                <div class="flex items-center justify-between">
                                    <span class="text-sm font-medium text-slate-700">Louange</span>
                                    <span class="text-lg font-bold text-purple-600"><?php echo e($culte->note_louange); ?>/10</span>
                                </div>
                            <?php endif; ?>

                            <?php if($culte->note_message): ?>
                                <div class="flex items-center justify-between">
                                    <span class="text-sm font-medium text-slate-700">Message</span>
                                    <span class="text-lg font-bold text-blue-600"><?php echo e($culte->note_message); ?>/10</span>
                                </div>
                            <?php endif; ?>

                            <?php if($culte->note_organisation): ?>
                                <div class="flex items-center justify-between">
                                    <span class="text-sm font-medium text-slate-700">Organisation</span>
                                    <span class="text-lg font-bold text-green-600"><?php echo e($culte->note_organisation); ?>/10</span>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Informations système -->
                <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                    <div class="p-6 border-b border-slate-200">
                        <h2 class="text-xl font-bold text-slate-800 flex items-center">
                            <i class="fas fa-cog text-slate-600 mr-2"></i>
                            Informations Système
                        </h2>
                    </div>
                    <div class="p-6 space-y-3 text-sm">
                        <div class="flex justify-between">
                            <span class="text-slate-500">Créé le:</span>
                            <span class="text-slate-900 font-medium"><?php echo e($culte->created_at->format('d/m/Y H:i')); ?></span>
                        </div>
                        <?php if($culte->createur): ?>
                            <div class="flex justify-between">
                                <span class="text-slate-500">Créé par:</span>
                                <span class="text-slate-900 font-medium"><?php echo e($culte->createur->nom); ?> <?php echo e($culte->createur->prenom); ?></span>
                            </div>
                        <?php endif; ?>
                        <div class="flex justify-between">
                            <span class="text-slate-500">Modifié le:</span>
                            <span class="text-slate-900 font-medium"><?php echo e($culte->updated_at->format('d/m/Y H:i')); ?></span>
                        </div>
                        <?php if($culte->modificateur): ?>
                            <div class="flex justify-between">
                                <span class="text-slate-500">Modifié par:</span>
                                <span class="text-slate-900 font-medium"><?php echo e($culte->modificateur->nom); ?> <?php echo e($culte->modificateur->prenom); ?></span>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal changement de statut -->
    <div id="statusModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-xl max-w-md w-full">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-slate-900 mb-4">Changer le statut du culte</h3>
                <form id="statusForm">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" id="culte_id" name="culte_id" value="<?php echo e($culte->id); ?>">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-slate-700 mb-2">Nouveau statut</label>
                        <select id="nouveau_statut" name="statut"
                            class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            <option value="planifie">Planifié</option>
                            <option value="en_preparation">En Préparation</option>
                            <option value="en_cours">En Cours</option>
                            <option value="termine">Terminé</option>
                            <option value="annule">Annulé</option>
                            <option value="reporte">Reporté</option>
                        </select>
                    </div>
                    <div id="raisonDiv" class="mb-4 hidden">
                        <label class="block text-sm font-medium text-slate-700 mb-2">Raison</label>
                        <textarea name="raison" id="raison" rows="3"
                            class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors resize-none"
                            placeholder="Raison de l'annulation ou du report..."></textarea>
                    </div>
                </form>
            </div>
            <div class="flex items-center justify-end space-x-3 p-6 border-t border-slate-200">
                <button type="button" onclick="closeStatusModal()"
                    class="px-4 py-2 text-slate-700 bg-slate-100 rounded-xl hover:bg-slate-200 transition-colors">
                    Annuler
                </button>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('cultes.update')): ?>
                <button type="button" onclick="updateStatus()"
                    class="px-4 py-2 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition-colors">
                    Changer le statut
                </button>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Modal duplication -->
    <div id="duplicateModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-xl max-w-md w-full">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-slate-900 mb-4">Dupliquer le culte</h3>
                <form id="duplicateForm">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" id="duplicate_culte_id" name="culte_id" value="<?php echo e($culte->id); ?>">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Nouvelle date</label>
                            <input type="date" name="nouvelle_date" id="nouvelle_date" required
                                class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Nouvelle heure</label>
                            <input type="time" name="nouvelle_heure" id="nouvelle_heure" value="<?php echo e($culte->heure_debut); ?>"
                                class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Nouveau titre (optionnel)</label>
                            <input type="text" name="nouveau_titre" id="nouveau_titre"
                                class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                placeholder="Laisser vide pour ajouter (Copie)">
                        </div>
                    </div>
                </form>
            </div>
            <div class="flex items-center justify-end space-x-3 p-6 border-t border-slate-200">
                <button type="button" onclick="closeDuplicateModal()"
                    class="px-4 py-2 text-slate-700 bg-slate-100 rounded-xl hover:bg-slate-200 transition-colors">
                    Annuler
                </button>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('cultes.duplicate')): ?>
                    <button type="button" onclick="duplicateCulte()"
                        class="px-4 py-2 bg-purple-600 text-white rounded-xl hover:bg-purple-700 transition-colors">
                        Dupliquer
                    </button>
                <?php endif; ?>
            </div>
        </div>
    </div>





<!-- Modal d'export à ajouter avant la fermeture du div content principal -->
<!-- Modal d'export -->
<div id="exportModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl max-w-lg w-full max-h-[90vh] overflow-y-auto">
        <!-- En-tête du modal -->
        <div class="p-6 border-b border-slate-200">
            <div class="flex items-center justify-between">
                <h3 class="text-xl font-semibold text-slate-900 flex items-center">
                    <i class="fas fa-download text-emerald-600 mr-3"></i>
                    Exporter le Culte
                </h3>
                <button type="button" onclick="closeExportModal()"
                    class="text-slate-400 hover:text-slate-600 transition-colors">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <p class="text-sm text-slate-600 mt-2">
                Choisissez le format d'export pour le rapport du culte "<?php echo e($culte->titre); ?>"
            </p>
        </div>

        <!-- Corps du modal -->
        <div class="p-6 space-y-6">
            <!-- Sélection du format -->
            <div>
                <h4 class="text-lg font-semibold text-slate-800 mb-4 flex items-center">
                    <i class="fas fa-file-alt text-blue-600 mr-2"></i>
                    Format d'Export
                </h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Option PDF -->
                    <div class="export-option border-2 border-slate-200 rounded-xl p-4 cursor-pointer transition-all duration-200 hover:border-red-300 hover:bg-red-50"
                         data-format="pdf" onclick="selectExportFormat('pdf')">
                        <div class="flex items-center space-x-3">
                            <div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center">
                                <i class="fas fa-file-pdf text-red-600 text-xl"></i>
                            </div>
                            <div class="flex-1">
                                <h5 class="font-semibold text-slate-900">Format PDF</h5>
                                <p class="text-sm text-slate-600">Rapport complet avec mise en page professionnelle</p>
                                <div class="flex items-center mt-2 text-xs text-slate-500">
                                    <i class="fas fa-check-circle text-green-500 mr-1"></i>
                                    <span>Idéal pour impression et archivage</span>
                                </div>
                            </div>
                        </div>
                        <div class="mt-3 flex items-center justify-between text-sm">
                            <span class="text-slate-600">Taille: ~200-500 KB</span>
                            <span class="text-slate-600">Pages: 2-4</span>
                        </div>
                    </div>

                    <!-- Option Excel -->
                    <div class="export-option border-2 border-slate-200 rounded-xl p-4 cursor-pointer transition-all duration-200 hover:border-green-300 hover:bg-green-50"
                         data-format="excel" onclick="selectExportFormat('excel')">
                        <div class="flex items-center space-x-3">
                            <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                                <i class="fas fa-file-excel text-green-600 text-xl"></i>
                            </div>
                            <div class="flex-1">
                                <h5 class="font-semibold text-slate-900">Format Excel</h5>
                                <p class="text-sm text-slate-600">Données structurées sur plusieurs feuilles</p>
                                <div class="flex items-center mt-2 text-xs text-slate-500">
                                    <i class="fas fa-chart-bar text-blue-500 mr-1"></i>
                                    <span>Parfait pour analyses et calculs</span>
                                </div>
                            </div>
                        </div>
                        <div class="mt-3 flex items-center justify-between text-sm">
                            <span class="text-slate-600">Taille: ~50-150 KB</span>
                            <span class="text-slate-600">Feuilles: 2-3</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contenu de l'export -->
            <div>
                <h4 class="text-lg font-semibold text-slate-800 mb-4 flex items-center">
                    <i class="fas fa-list-check text-purple-600 mr-2"></i>
                    Contenu Inclus
                </h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                    <div class="space-y-2">
                        <div class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-2"></i>
                            <span>Informations générales</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-2"></i>
                            <span>Responsables et intervenants</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-2"></i>
                            <span>Message et prédication</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-2"></i>
                            <span>Statistiques de participation</span>
                        </div>
                    </div>
                    <div class="space-y-2">
                        <?php if(isset($fondsStatistiques) && $fondsStatistiques['total_transactions'] > 0): ?>
                        <div class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-2"></i>
                            <span>Données financières détaillées</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-2"></i>
                            <span>Ratios et métriques</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-2"></i>
                            <span>Top donateurs</span>
                        </div>
                        <?php endif; ?>
                        <?php if($culte->note_globale): ?>
                        <div class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-2"></i>
                            <span>Évaluations et notes</span>
                        </div>
                        <?php endif; ?>
                        <?php if($culte->notes_pasteur || $culte->points_forts || $culte->points_amelioration): ?>
                        <div class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-2"></i>
                            <span>Notes et commentaires</span>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Options d'export -->
            <div>
                <h4 class="text-lg font-semibold text-slate-800 mb-4 flex items-center">
                    <i class="fas fa-cog text-slate-600 mr-2"></i>
                    Options d'Export
                </h4>
                <div class="space-y-3">
                    <label class="flex items-center">
                        <input type="checkbox" id="includeFinancial" checked
                            class="w-4 h-4 text-emerald-600 border-slate-300 rounded focus:ring-emerald-500">
                        <span class="ml-2 text-sm text-slate-700">Inclure les données financières</span>
                        <?php if(isset($fondsStatistiques) && $fondsStatistiques['total_transactions'] > 0): ?>
                            <span class="ml-2 text-xs text-green-600">(<?php echo e($fondsStatistiques['total_transactions']); ?> transactions)</span>
                        <?php endif; ?>
                    </label>
                    <label class="flex items-center">
                        <input type="checkbox" id="includeNotes" checked
                            class="w-4 h-4 text-emerald-600 border-slate-300 rounded focus:ring-emerald-500">
                        <span class="ml-2 text-sm text-slate-700">Inclure les notes et commentaires</span>
                    </label>
                    <label class="flex items-center">
                        <input type="checkbox" id="includeMetadata"
                            class="w-4 h-4 text-emerald-600 border-slate-300 rounded focus:ring-emerald-500">
                        <span class="ml-2 text-sm text-slate-700">Inclure les métadonnées système</span>
                    </label>
                </div>
            </div>

            <!-- Aperçu des informations -->
            <div class="bg-slate-50 rounded-xl p-4">
                <h5 class="font-semibold text-slate-800 mb-2 flex items-center">
                    <i class="fas fa-info-circle text-blue-500 mr-2"></i>
                    Aperçu du Rapport
                </h5>
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <span class="text-slate-600">Culte:</span>
                        <span class="font-medium text-slate-900"><?php echo e($culte->titre); ?></span>
                    </div>
                    <div>
                        <span class="text-slate-600">Date:</span>
                        <span class="font-medium text-slate-900"><?php echo e($culte->date_culte->format('d/m/Y')); ?></span>
                    </div>
                    <?php if($culte->nombre_participants): ?>
                    <div>
                        <span class="text-slate-600">Participants:</span>
                        <span class="font-medium text-slate-900"><?php echo e(number_format($culte->nombre_participants)); ?></span>
                    </div>
                    <?php endif; ?>
                    <?php if(isset($fondsStatistiques) && $fondsStatistiques['total_transactions'] > 0): ?>
                    <div>
                        <span class="text-slate-600">Collecte:</span>
                        <span class="font-medium text-slate-900"><?php echo e(number_format($fondsStatistiques['montant_total'], 0)); ?> FCFA</span>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Pied du modal -->
        <div class="flex items-center justify-between p-6 border-t border-slate-200 bg-slate-50 rounded-b-2xl">
            <div class="text-sm text-slate-600">
                <i class="fas fa-clock mr-1"></i>
                Export généré le <?php echo e(now()->format('d/m/Y à H:i')); ?>

            </div>
            <div class="flex items-center space-x-3">
                <button type="button" onclick="closeExportModal()"
                    class="px-4 py-2 text-slate-700 bg-white border border-slate-300 rounded-xl hover:bg-slate-50 transition-colors">
                    Annuler
                </button>
                <button type="button" id="exportBtn" onclick="executeExport()" disabled
                    class="px-6 py-2 bg-gradient-to-r from-emerald-600 to-teal-600 text-white rounded-xl hover:from-emerald-700 hover:to-teal-700 transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed flex items-center">
                    <i class="fas fa-download mr-2"></i>
                    <span id="exportBtnText">Sélectionner un format</span>
                </button>
            </div>
        </div>
    </div>
</div>


<style>
.export-option.selected {
    border-color: #10b981 !important;
    background-color: #ecfdf5 !important;
}

.export-option.selected .fas {
    color: #10b981 !important;
}

.export-option:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}
</style>

    <?php $__env->startPush('scripts'); ?>
        <script>
            let selectedFormat = null;

            function openExportModal(culteId) {
                selectedFormat = null;
                updateExportButton();
                // Reset des sélections
                document.querySelectorAll('.export-option').forEach(option => {
                    option.classList.remove('selected');
                });
                document.getElementById('exportModal').classList.remove('hidden');
            }

            function closeExportModal() {
    document.getElementById('exportModal').classList.add('hidden');
    selectedFormat = null;
}

function selectExportFormat(format) {
    selectedFormat = format;

    // Reset de toutes les options
    document.querySelectorAll('.export-option').forEach(option => {
        option.classList.remove('selected');
    });

    // Sélectionner l'option choisie
    document.querySelector(`[data-format="${format}"]`).classList.add('selected');

    updateExportButton();
}

function updateExportButton() {
    const btn = document.getElementById('exportBtn');
    const btnText = document.getElementById('exportBtnText');

    if (selectedFormat) {
        btn.disabled = false;
        btnText.textContent = `Exporter en ${selectedFormat.toUpperCase()}`;
        btn.classList.remove('opacity-50', 'cursor-not-allowed');
    } else {
        btn.disabled = true;
        btnText.textContent = 'Sélectionner un format';
        btn.classList.add('opacity-50', 'cursor-not-allowed');
    }
}

function executeExport() {
    if (!selectedFormat) {
        alert('Veuillez sélectionner un format d\'export');
        return;
    }

    // Afficher un indicateur de chargement
    const btn = document.getElementById('exportBtn');
    const originalText = btn.innerHTML;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Génération...';
    btn.disabled = true;

    // Construire l'URL d'export
    const culteId = '<?php echo e($culte->id); ?>';
    let exportUrl;

    if (selectedFormat === 'pdf') {
        exportUrl = `<?php echo e(route('private.cultes.export.pdf', ':culte')); ?>`.replace(':culte', culteId);
    } else {
        exportUrl = `<?php echo e(route('private.cultes.export.excel', ':culte')); ?>`.replace(':culte', culteId);
    }

    // Lancer le téléchargement
    window.location.href = exportUrl;

    // Rétablir le bouton après un délai
    setTimeout(() => {
        btn.innerHTML = originalText;
        btn.disabled = false;
        closeExportModal();
    }, 2000);
}

// Fermer le modal en cliquant à l'extérieur
document.getElementById('exportModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeExportModal();
    }
});

// Raccourci clavier Échap pour fermer
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape' && !document.getElementById('exportModal').classList.contains('hidden')) {
        closeExportModal();
    }
});









            // Modal statut
            function openStatusModal(culteId, currentStatus) {
                document.getElementById('culte_id').value = culteId;
                document.getElementById('nouveau_statut').value = currentStatus;
                toggleRaisonField();
                document.getElementById('statusModal').classList.remove('hidden');
            }

            function closeStatusModal() {
                document.getElementById('statusModal').classList.add('hidden');
                document.getElementById('statusForm').reset();
            }

            function toggleRaisonField() {
                const statut = document.getElementById('nouveau_statut').value;
                const raisonDiv = document.getElementById('raisonDiv');
                if (statut === 'annule' || statut === 'reporte') {
                    raisonDiv.classList.remove('hidden');
                    document.getElementById('raison').required = true;
                } else {
                    raisonDiv.classList.add('hidden');
                    document.getElementById('raison').required = false;
                }
            }

            document.getElementById('nouveau_statut').addEventListener('change', toggleRaisonField);

            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('cultes.update')): ?>
            function updateStatus() {
                const form = document.getElementById('statusForm');
                const formData = new FormData(form);
                const culteId = document.getElementById('culte_id').value;

                fetch(`<?php echo e(route('private.cultes.statut', ':culteid')); ?>`.replace(':culteid', culteId), {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
                            'Accept': 'application/json'
                        },
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            location.reload();
                        } else {
                            alert(data.message || 'Une erreur est survenue');
                        }
                    })
                    .catch(error => {
                        console.error('Erreur:', error);
                        alert('Une erreur est survenue');
                    });
            }
            <?php endif; ?>

            // Modal duplication
            function openDuplicateModal(culteId) {
                document.getElementById('duplicate_culte_id').value = culteId;
                // Définir la date de demain par défaut
                const tomorrow = new Date();
                tomorrow.setDate(tomorrow.getDate() + 1);
                document.getElementById('nouvelle_date').value = tomorrow.toISOString().split('T')[0];
                document.getElementById('duplicateModal').classList.remove('hidden');
            }

            function closeDuplicateModal() {
                document.getElementById('duplicateModal').classList.add('hidden');
                document.getElementById('duplicateForm').reset();
            }

            function duplicateCulte() {
                const form = document.getElementById('duplicateForm');
                const formData = new FormData(form);
                const culteId = document.getElementById('duplicate_culte_id').value;

                fetch(`<?php echo e(route('private.cultes.dupliquer', ':culteid')); ?>`.replace(':culteid', culteId), {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
                            'Accept': 'application/json'
                        },
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            window.location.href = `<?php echo e(route('private.cultes.show', ':culteid')); ?>`.replace(':culteid', data.data.id);
                        } else {
                            alert(data.message || 'Une erreur est survenue');
                        }
                    })
                    .catch(error => {
                        console.error('Erreur:', error);
                        alert('Une erreur est survenue');
                    });
            }

            // Suppression
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('cultes.delete')): ?>
            function deleteCulte(culteId) {
                if (confirm('Êtes-vous sûr de vouloir supprimer ce culte ?')) {
                    fetch(`<?php echo e(route('private.cultes.destroy', ':culteid')); ?>`.replace(':culteid', culteId), {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
                                'Accept': 'application/json'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                window.location.href = '<?php echo e(route('private.cultes.index')); ?>';
                            } else {
                                alert(data.message || 'Une erreur est survenue');
                            }
                        })
                        .catch(error => {
                            console.error('Erreur:', error);
                            alert('Une erreur est survenue');
                        });
                }
            }
            <?php endif; ?>

            // Fermer les modals en cliquant à l'extérieur
            document.getElementById('statusModal').addEventListener('click', function(e) {
                if (e.target === this) closeStatusModal();
            });

            document.getElementById('duplicateModal').addEventListener('click', function(e) {
                if (e.target === this) closeDuplicateModal();
            });
        </script>
    <?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.private.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Dell\Desktop\MICRISERVICES\methodiste_belle_ville\resources\views/components/private/cultes/show.blade.php ENDPATH**/ ?>