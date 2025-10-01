<?php $__env->startSection('title', 'Mon Profil'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-8">
    <!-- Page Title -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent">Mon Profil</h1>
        <p class="text-slate-500 mt-1">Consultez et gérez vos informations personnelles - <?php echo e(\Carbon\Carbon::now()->format('l d F Y')); ?></p>
    </div>

    <!-- Actions rapides -->
    <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
        <div class="p-6">
            <div class="flex flex-wrap gap-3">
                <a href="<?php echo e(route('private.profil.edit')); ?>" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-600 to-purple-600 text-white text-sm font-medium rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow-md hover:shadow-lg">
                    <i class="fas fa-edit mr-2"></i> Modifier mes informations
                </a>
                <a href="<?php echo e(route('private.profil.edit.password')); ?>" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-amber-600 to-orange-600 text-white text-sm font-medium rounded-xl hover:from-amber-700 hover:to-orange-700 transition-all duration-200 shadow-md hover:shadow-lg">
                    <i class="fas fa-key mr-2"></i> Changer le mot de passe
                </a>
                <a href="<?php echo e(route('private.profil.spirituel')); ?>" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-emerald-600 to-green-600 text-white text-sm font-medium rounded-xl hover:from-emerald-700 hover:to-green-700 transition-all duration-200 shadow-md hover:shadow-lg">
                    <i class="fas fa-praying-hands mr-2"></i> Informations spirituelles
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Informations principales -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Photo et informations de base -->
            <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="p-6">
                    <div class="flex flex-col md:flex-row items-center md:items-start space-y-4 md:space-y-0 md:space-x-6">
                        <!-- Photo de profil -->
                        <div class="flex-shrink-0">
                            <?php if($user->photo_profil): ?>
                                <img src="<?php echo e(Storage::url($user->photo_profil)); ?>" alt="Photo de profil" class="w-32 h-32 rounded-full object-cover border-4 border-blue-100 shadow-lg">
                            <?php else: ?>
                                <div class="w-32 h-32 bg-gradient-to-r from-blue-500 to-purple-500 rounded-full flex items-center justify-center shadow-lg">
                                    <span class="text-white text-4xl font-bold">
                                        <?php echo e(substr($user->prenom, 0, 1)); ?><?php echo e(substr($user->nom, 0, 1)); ?>

                                    </span>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Informations de base -->
                        <div class="flex-1 text-center md:text-left">
                            <h2 class="text-2xl font-bold text-slate-900"><?php echo e($user->nom_complet); ?></h2>
                            <p class="text-slate-500 mt-1"><?php echo e($user->email ?? 'Email non renseigné'); ?></p>

                            <div class="flex flex-wrap gap-2 mt-4 justify-center md:justify-start">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    <i class="fas fa-<?php echo e($user->sexe === 'masculin' ? 'mars' : 'venus'); ?> mr-1"></i>
                                    <?php echo e(ucfirst($user->sexe)); ?>

                                </span>

                                <?php if($user->statut_membre): ?>
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                                        <?php switch($user->statut_membre):
                                            case ('actif'): ?> bg-green-100 text-green-800 <?php break; ?>
                                            <?php case ('inactif'): ?> bg-red-100 text-red-800 <?php break; ?>
                                            <?php case ('visiteur'): ?> bg-yellow-100 text-yellow-800 <?php break; ?>
                                            <?php case ('nouveau_converti'): ?> bg-purple-100 text-purple-800 <?php break; ?>
                                        <?php endswitch; ?>">
                                        <i class="fas fa-user-circle mr-1"></i>
                                        <?php echo e(ucfirst(str_replace('_', ' ', $user->statut_membre))); ?>

                                    </span>
                                <?php endif; ?>

                                <?php if($user->statut_bapteme !== 'non_baptise'): ?>
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-cyan-100 text-cyan-800">
                                        <i class="fas fa-cross mr-1"></i>
                                        <?php echo e(ucfirst(str_replace('_', ' ', $user->statut_bapteme))); ?>

                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Informations personnelles -->
            <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="p-6 border-b border-slate-200">
                    <h2 class="text-xl font-bold text-slate-800 flex items-center">
                        <i class="fas fa-user text-blue-600 mr-2"></i>
                        Informations Personnelles
                    </h2>
                </div>
                <div class="p-6 space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Prénom</label>
                            <div class="p-3 bg-slate-50 rounded-xl">
                                <span class="text-slate-900"><?php echo e($user->prenom); ?></span>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Nom</label>
                            <div class="p-3 bg-slate-50 rounded-xl">
                                <span class="text-slate-900"><?php echo e($user->nom); ?></span>
                            </div>
                        </div>

                        <?php if($user->date_naissance): ?>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-2">Date de naissance</label>
                                <div class="p-3 bg-slate-50 rounded-xl">
                                    <span class="text-slate-900"><?php echo e($user->date_naissance->format('d/m/Y')); ?></span>
                                    <span class="text-slate-500 text-sm ml-2">(<?php echo e($user->date_naissance->age); ?> ans)</span>
                                </div>
                            </div>
                        <?php endif; ?>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Statut matrimonial</label>
                            <div class="p-3 bg-slate-50 rounded-xl">
                                <span class="text-slate-900"><?php echo e(ucfirst($user->statut_matrimonial)); ?></span>
                            </div>
                        </div>

                        <?php if($user->nombre_enfants > 0): ?>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-2">Nombre d'enfants</label>
                                <div class="p-3 bg-slate-50 rounded-xl">
                                    <span class="text-slate-900"><?php echo e($user->nombre_enfants); ?></span>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Informations de contact -->
            <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="p-6 border-b border-slate-200">
                    <h2 class="text-xl font-bold text-slate-800 flex items-center">
                        <i class="fas fa-address-book text-green-600 mr-2"></i>
                        Coordonnées
                    </h2>
                </div>
                <div class="p-6 space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Téléphone principal</label>
                            <div class="p-3 bg-slate-50 rounded-xl flex items-center">
                                <i class="fas fa-phone text-blue-500 mr-2"></i>
                                <span class="text-slate-900"><?php echo e($user->telephone_1); ?></span>
                            </div>
                        </div>

                        <?php if($user->telephone_2): ?>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-2">Téléphone secondaire</label>
                                <div class="p-3 bg-slate-50 rounded-xl flex items-center">
                                    <i class="fas fa-phone text-blue-500 mr-2"></i>
                                    <span class="text-slate-900"><?php echo e($user->telephone_2); ?></span>
                                </div>
                            </div>
                        <?php endif; ?>

                        <?php if($user->email): ?>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-slate-700 mb-2">Email</label>
                                <div class="p-3 bg-slate-50 rounded-xl flex items-center">
                                    <i class="fas fa-envelope text-blue-500 mr-2"></i>
                                    <span class="text-slate-900"><?php echo e($user->email); ?></span>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>

                    <?php if($user->adresse_ligne_1 || $user->ville): ?>
                        <div class="pt-4 border-t border-slate-200">
                            <label class="block text-sm font-medium text-slate-700 mb-2">Adresse</label>
                            <div class="p-4 bg-slate-50 rounded-xl">
                                <?php if($user->adresse_ligne_1): ?>
                                    <p class="text-slate-900"><?php echo e($user->adresse_ligne_1); ?></p>
                                <?php endif; ?>
                                <?php if($user->adresse_ligne_2): ?>
                                    <p class="text-slate-900"><?php echo e($user->adresse_ligne_2); ?></p>
                                <?php endif; ?>
                                <p class="text-slate-900">
                                    <?php if($user->code_postal): ?><?php echo e($user->code_postal); ?><?php endif; ?>
                                    <?php if($user->ville): ?> <?php echo e($user->ville); ?><?php endif; ?>
                                </p>
                                <?php if($user->region): ?>
                                    <p class="text-slate-900"><?php echo e($user->region); ?></p>
                                <?php endif; ?>
                                <?php if($user->pays): ?>
                                    <p class="text-slate-900"><?php echo e($user->pays); ?></p>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Informations professionnelles -->
            <?php if($user->profession || $user->employeur): ?>
                <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                    <div class="p-6 border-b border-slate-200">
                        <h2 class="text-xl font-bold text-slate-800 flex items-center">
                            <i class="fas fa-briefcase text-purple-600 mr-2"></i>
                            Informations Professionnelles
                        </h2>
                    </div>
                    <div class="p-6 space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <?php if($user->profession): ?>
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-2">Profession</label>
                                    <div class="p-3 bg-slate-50 rounded-xl">
                                        <span class="text-slate-900"><?php echo e($user->profession); ?></span>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <?php if($user->employeur): ?>
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-2">Employeur</label>
                                    <div class="p-3 bg-slate-50 rounded-xl">
                                        <span class="text-slate-900"><?php echo e($user->employeur); ?></span>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Informations d'église -->
            <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="p-6 border-b border-slate-200">
                    <h2 class="text-xl font-bold text-slate-800 flex items-center">
                        <i class="fas fa-church text-amber-600 mr-2"></i>
                        Informations d'Église
                    </h2>
                </div>
                <div class="p-6 space-y-4">
                    <?php if($user->date_adhesion): ?>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Date d'adhésion</label>
                            <p class="text-sm text-slate-900"><?php echo e($user->date_adhesion->format('d/m/Y')); ?></p>
                            <p class="text-xs text-slate-500">Il y a <?php echo e($user->date_adhesion->diffForHumans()); ?></p>
                        </div>
                    <?php endif; ?>

                    <?php if($user->date_bapteme): ?>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Date de baptême</label>
                            <p class="text-sm text-slate-900"><?php echo e($user->date_bapteme->format('d/m/Y')); ?></p>
                        </div>
                    <?php endif; ?>

                    <?php if($user->eglise_precedente): ?>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Église précédente</label>
                            <p class="text-sm text-slate-900"><?php echo e($user->eglise_precedente); ?></p>
                        </div>
                    <?php endif; ?>

                    <?php if($user->classe): ?>
                        <div class="pt-4 border-t border-slate-200">
                            <label class="block text-sm font-medium text-slate-700 mb-2">Classe</label>
                            <div class="p-3 bg-gradient-to-r from-blue-50 to-purple-50 rounded-xl">
                                <p class="font-semibold text-slate-900"><?php echo e($user->classe->nom); ?></p>
                                <?php if($user->classe->tranche_age): ?>
                                    <p class="text-xs text-slate-500 mt-1"><?php echo e($user->classe->tranche_age); ?></p>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Rôles -->
            <?php if($user->roles->count() > 0): ?>
                <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                    <div class="p-6 border-b border-slate-200">
                        <h2 class="text-xl font-bold text-slate-800 flex items-center">
                            <i class="fas fa-user-tag text-cyan-600 mr-2"></i>
                            Mes Rôles
                        </h2>
                    </div>
                    <div class="p-6">
                        <div class="space-y-2">
                            <?php $__currentLoopData = $user->roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="flex items-center justify-between p-3 bg-slate-50 rounded-xl">
                                    <div class="flex items-center space-x-2">
                                        <?php if($role->is_system_role): ?>
                                            <i class="fas fa-lock text-yellow-500 text-xs"></i>
                                        <?php endif; ?>
                                        <span class="text-sm font-medium text-slate-900"><?php echo e($role->name); ?></span>
                                    </div>
                                    <span class="text-xs text-slate-500">Niveau <?php echo e($role->level); ?></span>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Contact d'urgence -->
            <?php if($user->contact_urgence_nom): ?>
                <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                    <div class="p-6 border-b border-slate-200">
                        <h2 class="text-xl font-bold text-slate-800 flex items-center">
                            <i class="fas fa-phone-volume text-red-600 mr-2"></i>
                            Contact d'Urgence
                        </h2>
                    </div>
                    <div class="p-6 space-y-3">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Nom</label>
                            <p class="text-sm text-slate-900"><?php echo e($user->contact_urgence_nom); ?></p>
                        </div>
                        <?php if($user->contact_urgence_telephone): ?>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Téléphone</label>
                                <p class="text-sm text-slate-900"><?php echo e($user->contact_urgence_telephone); ?></p>
                            </div>
                        <?php endif; ?>
                        <?php if($user->contact_urgence_relation): ?>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Relation</label>
                                <p class="text-sm text-slate-900"><?php echo e($user->contact_urgence_relation); ?></p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.private.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Dell\Desktop\MICRISERVICES\methodiste_belle_ville\resources\views/components/private/profil/index.blade.php ENDPATH**/ ?>