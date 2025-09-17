<?php $__env->startSection('title', 'Détails de l\'Engagement - ' . $engagementMoisson->nom_donateur); ?>

<?php $__env->startSection('content'); ?>
    <div class="space-y-8">
        <!-- En-tête avec navigation -->
        <div class="mb-8">
            <div class="flex items-center gap-2 text-sm text-slate-600 mb-4">
                <a href="<?php echo e(route('private.moissons.index')); ?>" class="hover:text-blue-600 transition-colors">
                    <i class="fas fa-seedling mr-1"></i> Moissons
                </a>
                <i class="fas fa-chevron-right text-xs"></i>
                <a href="<?php echo e(route('private.moissons.show', $moisson)); ?>" class="hover:text-blue-600 transition-colors">
                    <?php echo e(Str::limit($moisson->theme, 30)); ?>

                </a>
                <i class="fas fa-chevron-right text-xs"></i>
                <a href="<?php echo e(route('private.moissons.engagements.index', $moisson)); ?>" class="hover:text-blue-600 transition-colors">
                    Engagements
                </a>
                <i class="fas fa-chevron-right text-xs"></i>
                <span class="text-slate-800 font-medium"><?php echo e($engagementMoisson->nom_donateur); ?></span>
            </div>

            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent">
                        <?php echo e($engagementMoisson->nom_donateur); ?>

                    </h1>
                    <p class="text-slate-500 mt-1">
                        Détails et suivi de l'engagement pour la moisson "<?php echo e($moisson->theme); ?>"
                    </p>
                </div>

                <div class="flex gap-2">
                    <button onclick="ajouterPaiement()"
                        class="inline-flex items-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-xl hover:bg-green-700 transition-colors">
                        <i class="fas fa-plus mr-2"></i> Ajouter paiement
                    </button>
                    <?php if($engagementMoisson->reste > 0 && $engagementMoisson->date_echeance): ?>
                        <button onclick="planifierRappel()"
                            class="inline-flex items-center px-4 py-2 bg-purple-600 text-white text-sm font-medium rounded-xl hover:bg-purple-700 transition-colors">
                            <i class="fas fa-bell mr-2"></i> Rappel
                        </button>
                        <button onclick="prolongerEcheance()"
                            class="inline-flex items-center px-4 py-2 bg-orange-600 text-white text-sm font-medium rounded-xl hover:bg-orange-700 transition-colors">
                            <i class="fas fa-calendar-plus mr-2"></i> Prolonger
                        </button>
                    <?php endif; ?>
                    <a href="<?php echo e(route('private.moissons.engagements.edit', [$moisson, $engagementMoisson])); ?>"
                        class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-xl hover:bg-blue-700 transition-colors">
                        <i class="fas fa-edit mr-2"></i> Modifier
                    </a>
                    <button onclick="toggleStatus()"
                        class="inline-flex items-center px-4 py-2 <?php echo e($engagementMoisson->status ? 'bg-red-600 hover:bg-red-700' : 'bg-green-600 hover:bg-green-700'); ?> text-white text-sm font-medium rounded-xl transition-colors">
                        <i class="fas fa-toggle-<?php echo e($engagementMoisson->status ? 'off' : 'on'); ?> mr-2"></i>
                        <?php echo e($engagementMoisson->status ? 'Désactiver' : 'Activer'); ?>

                    </button>
                </div>
            </div>
        </div>

        <!-- Informations de la moisson -->
        <div class="bg-gradient-to-r from-blue-50 to-purple-50 rounded-2xl shadow-lg border border-blue-200/50 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-bold text-slate-800 mb-2"><?php echo e($moisson->theme); ?></h3>
                    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 text-sm">
                        <div>
                            <span class="text-slate-600">Date:</span>
                            <span class="font-medium text-slate-800 ml-1"><?php echo e($moisson->date->format('d/m/Y')); ?></span>
                        </div>
                        <div>
                            <span class="text-slate-600">Culte:</span>
                            <span class="font-medium text-slate-800 ml-1"><?php echo e($moisson->culte->titre ?? 'Non défini'); ?></span>
                        </div>
                        <div>
                            <span class="text-slate-600">Objectif global:</span>
                            <span class="font-medium text-slate-800 ml-1"><?php echo e(number_format($moisson->cible, 0, ',', ' ')); ?> FCFA</span>
                        </div>
                        <div>
                            <span class="text-slate-600">Progression globale:</span>
                            <span class="font-medium text-blue-600 ml-1"><?php echo e($moisson->pourcentage_realise); ?>%</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Indicateurs de performance de l'engagement -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-600">Engagement</p>
                        <p class="text-2xl font-bold text-slate-900"><?php echo e(number_format($engagementMoisson->cible, 0, ',', ' ')); ?></p>
                        <p class="text-xs text-slate-500">FCFA</p>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-handshake text-blue-600"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-600">Versé</p>
                        <p class="text-2xl font-bold text-green-600"><?php echo e(number_format($engagementMoisson->montant_solde, 0, ',', ' ')); ?></p>
                        <p class="text-xs text-slate-500">FCFA</p>
                    </div>
                    <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-coins text-green-600"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-600">
                            <?php echo e($engagementMoisson->reste > 0 ? 'Reste' : 'Supplément'); ?>

                        </p>
                        <p class="text-2xl font-bold <?php echo e($engagementMoisson->reste > 0 ? 'text-red-600' : 'text-purple-600'); ?>">
                            <?php echo e(number_format($engagementMoisson->reste > 0 ? $engagementMoisson->reste : $engagementMoisson->montant_supplementaire, 0, ',', ' ')); ?>

                        </p>
                        <p class="text-xs text-slate-500">FCFA</p>
                    </div>
                    <div class="w-12 h-12 <?php echo e($engagementMoisson->reste > 0 ? 'bg-red-100' : 'bg-purple-100'); ?> rounded-xl flex items-center justify-center">
                        <i class="fas fa-<?php echo e($engagementMoisson->reste > 0 ? 'exclamation-triangle' : 'trophy'); ?> <?php echo e($engagementMoisson->reste > 0 ? 'text-red-600' : 'text-purple-600'); ?>"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-600">Progression</p>
                        <p class="text-2xl font-bold
                            <?php if($engagementMoisson->pourcentage_realise >= 100): ?> text-green-600
                            <?php elseif($engagementMoisson->pourcentage_realise >= 70): ?> text-blue-600
                            <?php elseif($engagementMoisson->pourcentage_realise >= 50): ?> text-yellow-600
                            <?php else: ?> text-red-600
                            <?php endif; ?>"><?php echo e($engagementMoisson->pourcentage_realise); ?>%</p>
                        <p class="text-xs text-slate-500">
                            <?php
                                $pourcentage = $engagementMoisson->pourcentage_realise;
                                if ($pourcentage >= 100) $statut = 'Objectif atteint';
                                elseif ($pourcentage >= 90) $statut = 'Presque atteint';
                                elseif ($pourcentage >= 70) $statut = 'Bonne progression';
                                elseif ($pourcentage >= 50) $statut = 'En cours';
                                elseif ($pourcentage >= 30) $statut = 'Début';
                                else $statut = 'Très faible';
                            ?>
                            <?php echo e($statut); ?>

                        </p>
                    </div>
                    <div class="w-12 h-12 bg-slate-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-chart-pie text-slate-600"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Barre de progression visuelle -->
        <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 p-6">
            <h3 class="text-lg font-bold text-slate-800 mb-4">
                <i class="fas fa-chart-bar text-blue-600 mr-2"></i>
                Progression visuelle
            </h3>
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <span class="text-sm font-medium text-slate-700">Progression de l'engagement</span>
                    <span class="text-sm font-medium text-slate-900"><?php echo e($engagementMoisson->pourcentage_realise); ?>%</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-4">
                    <div class="h-4 rounded-full transition-all duration-300
                        <?php if($engagementMoisson->pourcentage_realise >= 100): ?> bg-green-500
                        <?php elseif($engagementMoisson->pourcentage_realise >= 70): ?> bg-blue-500
                        <?php elseif($engagementMoisson->pourcentage_realise >= 50): ?> bg-yellow-500
                        <?php else: ?> bg-red-500
                        <?php endif; ?>"
                        style="width: <?php echo e(min($engagementMoisson->pourcentage_realise, 100)); ?>%">
                    </div>
                </div>
                <div class="flex justify-between text-xs text-slate-500">
                    <span>0 FCFA</span>
                    <span><?php echo e(number_format($engagementMoisson->cible, 0, ',', ' ')); ?> FCFA</span>
                </div>
            </div>

            <?php if($engagementMoisson->est_en_retard): ?>
                <div class="mt-4 p-4 bg-red-50 border border-red-200 rounded-lg">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-triangle text-red-600 mr-2"></i>
                        <div>
                            <p class="text-sm font-medium text-red-800">Engagement en retard</p>
                            <p class="text-xs text-red-600 mt-1">
                                <?php echo e($engagementMoisson->jours_retard); ?> jour(s) de retard
                                <?php if($engagementMoisson->niveau_urgence_libelle): ?>
                                    - <?php echo e($engagementMoisson->niveau_urgence_libelle); ?>

                                <?php endif; ?>
                            </p>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <!-- Détails de l'engagement -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Informations principales -->
            <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20">
                <div class="p-6 border-b border-slate-200">
                    <h3 class="text-lg font-bold text-slate-800 flex items-center">
                        <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                        Informations de l'engagement
                    </h3>
                </div>
                <div class="p-6">
                    <dl class="flex flex-wrap gap-y-6">
                        <div class="w-full sm:w-1/2 pr-4">
                            <dt class="text-sm font-medium text-slate-500">Type d'engagement</dt>
                            <dd class="mt-1 text-base text-slate-900 font-semibold">
                                <?php echo e($engagementMoisson->categorie_libelle); ?>

                            </dd>
                        </div>

                        <div class="w-full sm:w-1/2 pr-4">
                            <dt class="text-sm font-medium text-slate-500">Donateur</dt>
                            <dd class="mt-1 text-base text-slate-900 font-semibold">
                                <?php echo e($engagementMoisson->nom_donateur); ?>

                            </dd>
                        </div>

                        <?php if($engagementMoisson->telephone): ?>
                            <div class="w-full sm:w-1/2 pr-4">
                                <dt class="text-sm font-medium text-slate-500">Téléphone</dt>
                                <dd class="mt-1 text-base text-slate-900">
                                    <a href="tel:<?php echo e($engagementMoisson->telephone); ?>" class="text-blue-600 hover:text-blue-800">
                                        <?php echo e($engagementMoisson->telephone); ?>

                                    </a>
                                </dd>
                            </div>
                        <?php endif; ?>

                        <?php if($engagementMoisson->email): ?>
                            <div class="w-full sm:w-1/2 pr-4">
                                <dt class="text-sm font-medium text-slate-500">Email</dt>
                                <dd class="mt-1 text-base text-slate-900">
                                    <a href="mailto:<?php echo e($engagementMoisson->email); ?>" class="text-blue-600 hover:text-blue-800">
                                        <?php echo e($engagementMoisson->email); ?>

                                    </a>
                                </dd>
                            </div>
                        <?php endif; ?>

                        <?php if($engagementMoisson->adresse): ?>
                            <div class="w-full pr-4">
                                <dt class="text-sm font-medium text-slate-500">Adresse</dt>
                                <dd class="mt-1 text-base text-slate-900">
                                    <?php echo e($engagementMoisson->adresse); ?>

                                </dd>
                            </div>
                        <?php endif; ?>

                        <?php if($engagementMoisson->description): ?>
                            <div class="w-full pr-4">
                                <dt class="text-sm font-medium text-slate-500">Description</dt>
                                <dd class="mt-1 text-base text-slate-900">
                                    <?php echo e($engagementMoisson->description); ?>

                                </dd>
                            </div>
                        <?php endif; ?>

                        <div class="w-full sm:w-1/2 pr-4">
                            <dt class="text-sm font-medium text-slate-500">Date d'échéance</dt>
                            <dd class="mt-1 text-base text-slate-900">
                                <?php echo e($engagementMoisson->date_echeance ? $engagementMoisson->date_echeance->format('d/m/Y') : 'Non définie'); ?>

                            </dd>
                        </div>

                        <div class="w-full sm:w-1/2 pr-4">
                            <dt class="text-sm font-medium text-slate-500">Statut</dt>
                            <dd class="mt-1">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold
                                    <?php echo e($engagementMoisson->status ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'); ?>">
                                    <?php echo e($engagementMoisson->status ? 'Actif' : 'Inactif'); ?>

                                </span>
                            </dd>
                        </div>

                        <div class="w-full sm:w-1/2 pr-4">
                            <dt class="text-sm font-medium text-slate-500">Collecteur responsable</dt>
                            <dd class="mt-1 text-base text-slate-900 font-semibold">
                                <?php echo e($engagementMoisson->collecteur?->nom_complet ?? 'Non défini'); ?>

                            </dd>
                        </div>

                        <div class="w-full sm:w-1/2 pr-4">
                            <dt class="text-sm font-medium text-slate-500">Créé par</dt>
                            <dd class="mt-1 text-base text-slate-900 font-semibold">
                                <?php echo e($engagementMoisson->createur?->nom_complet ?? 'Inconnu'); ?>

                            </dd>
                        </div>

                        <div class="w-full sm:w-1/2 pr-4">
                            <dt class="text-sm font-medium text-slate-500">Date de création</dt>
                            <dd class="mt-1 text-base text-slate-900">
                                <?php echo e($engagementMoisson->created_at->format('d/m/Y à H:i')); ?>

                            </dd>
                        </div>

                        <?php if($engagementMoisson->updated_at != $engagementMoisson->created_at): ?>
                            <div class="w-full sm:w-1/2 pr-4">
                                <dt class="text-sm font-medium text-slate-500">Dernière modification</dt>
                                <dd class="mt-1 text-base text-slate-900">
                                    <?php echo e($engagementMoisson->updated_at->format('d/m/Y à H:i')); ?>

                                </dd>
                            </div>
                        <?php endif; ?>
                    </dl>
                </div>
            </div>

            <!-- Montants et analyses -->
            <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20">
                <div class="p-6 border-b border-slate-200">
                    <h3 class="text-lg font-bold text-slate-800 flex items-center">
                        <i class="fas fa-calculator text-green-600 mr-2"></i>
                        Analyse financière
                    </h3>
                </div>
                <div class="p-6">
                    <div class="space-y-6">
                        <!-- Engagement initial -->
                        <div class="flex items-center justify-between p-4 bg-blue-50 rounded-xl">
                            <div>
                                <p class="text-sm font-medium text-blue-800">Engagement initial</p>
                                <p class="text-xs text-blue-600">Montant promis</p>
                            </div>
                            <p class="text-lg font-bold text-blue-900">
                                <?php echo e(number_format($engagementMoisson->cible, 0, ',', ' ')); ?> FCFA
                            </p>
                        </div>

                        <!-- Montant versé -->
                        <div class="flex items-center justify-between p-4 bg-green-50 rounded-xl">
                            <div>
                                <p class="text-sm font-medium text-green-800">Montant versé</p>
                                <p class="text-xs text-green-600">Paiements reçus</p>
                            </div>
                            <p class="text-lg font-bold text-green-900">
                                <?php echo e(number_format($engagementMoisson->montant_solde, 0, ',', ' ')); ?> FCFA
                            </p>
                        </div>

                        <?php if($engagementMoisson->reste > 0): ?>
                            <!-- Reste à verser -->
                            <div class="flex items-center justify-between p-4 bg-red-50 rounded-xl">
                                <div>
                                    <p class="text-sm font-medium text-red-800">Reste à verser</p>
                                    <p class="text-xs text-red-600">Montant en attente</p>
                                </div>
                                <p class="text-lg font-bold text-red-900">
                                    <?php echo e(number_format($engagementMoisson->reste, 0, ',', ' ')); ?> FCFA
                                </p>
                            </div>
                        <?php endif; ?>

                        <?php if($engagementMoisson->montant_supplementaire > 0): ?>
                            <!-- Dépassement d'engagement -->
                            <div class="flex items-center justify-between p-4 bg-purple-50 rounded-xl">
                                <div>
                                    <p class="text-sm font-medium text-purple-800">Dépassement d'engagement</p>
                                    <p class="text-xs text-purple-600">Versements supplémentaires</p>
                                </div>
                                <p class="text-lg font-bold text-purple-900">
                                    +<?php echo e(number_format($engagementMoisson->montant_supplementaire, 0, ',', ' ')); ?> FCFA
                                </p>
                            </div>
                        <?php endif; ?>

                        <?php if($engagementMoisson->date_rappel && $engagementMoisson->doit_etre_rappele): ?>
                            <!-- Rappel programmé -->
                            <div class="flex items-center justify-between p-4 bg-yellow-50 rounded-xl">
                                <div>
                                    <p class="text-sm font-medium text-yellow-800">Rappel programmé</p>
                                    <p class="text-xs text-yellow-600">Date de rappel</p>
                                </div>
                                <p class="text-lg font-bold text-yellow-900">
                                    <?php echo e($engagementMoisson->date_rappel->format('d/m/Y')); ?>

                                </p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Historique des modifications -->
        <?php if(count($historique) > 0): ?>
            <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20">
                <div class="p-6 border-b border-slate-200">
                    <h3 class="text-lg font-bold text-slate-800 flex items-center">
                        <i class="fas fa-history text-purple-600 mr-2"></i>
                        Historique des paiements et modifications
                    </h3>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <?php $__currentLoopData = array_reverse($historique); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $edit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="flex items-start gap-4 p-4 <?php echo e($index % 2 === 0 ? 'bg-slate-50' : 'bg-white'); ?> rounded-lg">
                                <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
                                    <i class="fas fa-<?php echo e($edit['action'] === 'creation' ? 'plus' : ($edit['action'] === 'modification' ? 'edit' : ($edit['action'] === 'ajout_paiement' ? 'coins' : 'bell'))); ?> text-blue-600 text-xs"></i>
                                </div>
                                <div class="flex-1">
                                    <div class="flex items-center justify-between">
                                        <h4 class="text-sm font-medium text-slate-900">
                                            <?php switch($edit['action']):
                                                case ('creation'): ?>
                                                    Création de l'engagement
                                                    <?php break; ?>
                                                <?php case ('modification'): ?>
                                                    Modification
                                                    <?php break; ?>
                                                <?php case ('ajout_paiement'): ?>
                                                    Paiement reçu
                                                    <?php break; ?>
                                                <?php case ('paiement_partiel'): ?>
                                                    Paiement partiel
                                                    <?php break; ?>
                                                <?php case ('paiement_complet'): ?>
                                                    Paiement complet
                                                    <?php break; ?>
                                                <?php case ('rappel_planifie'): ?>
                                                    Rappel planifié
                                                    <?php break; ?>
                                                <?php case ('rappel_effectue'): ?>
                                                    Rappel effectué
                                                    <?php break; ?>
                                                <?php case ('prolongation_echeance'): ?>
                                                    Échéance prolongée
                                                    <?php break; ?>
                                                <?php case ('activation'): ?>
                                                    Activation
                                                    <?php break; ?>
                                                <?php case ('desactivation'): ?>
                                                    Désactivation
                                                    <?php break; ?>
                                                <?php default: ?>
                                                    <?php echo e(ucfirst($edit['action'])); ?>

                                            <?php endswitch; ?>
                                        </h4>
                                        <span class="text-xs text-slate-500">
                                            <?php echo e(\Carbon\Carbon::parse($edit['date'])->format('d/m/Y H:i')); ?>

                                        </span>
                                    </div>

                                    <?php if(isset($edit['details']) || isset($edit['montant'])): ?>
                                        <div class="mt-2 text-sm text-slate-600">
                                            <?php if(isset($edit['ancien_montant'])): ?>
                                                <p>Ancien montant: <?php echo e(number_format($edit['ancien_montant'], 0, ',', ' ')); ?> FCFA</p>
                                            <?php endif; ?>
                                            <?php if(isset($edit['nouveau_montant'])): ?>
                                                <p>Nouveau montant: <?php echo e(number_format($edit['nouveau_montant'], 0, ',', ' ')); ?> FCFA</p>
                                            <?php endif; ?>
                                            <?php if(isset($edit['montant_ajoute'])): ?>
                                                <p>Montant ajouté: +<?php echo e(number_format($edit['montant_ajoute'], 0, ',', ' ')); ?> FCFA</p>
                                            <?php endif; ?>
                                            <?php if(isset($edit['montant'])): ?>
                                                <p>Montant: <?php echo e(number_format($edit['montant'], 0, ',', ' ')); ?> FCFA</p>
                                            <?php endif; ?>
                                            <?php if(isset($edit['motif']) && $edit['motif']): ?>
                                                <p class="italic">Motif: <?php echo e($edit['motif']); ?></p>
                                            <?php endif; ?>
                                            <?php if(isset($edit['notes']) && $edit['notes']): ?>
                                                <p class="italic"><?php echo e($edit['notes']); ?></p>
                                            <?php endif; ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <!-- Modal pour ajouter un paiement -->
    <div id="modal-ajouter-paiement" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-2xl shadow-xl max-w-lg w-full">
                <div class="p-6 border-b border-slate-200">
                    <h3 class="text-lg font-bold text-slate-800">Ajouter un paiement</h3>
                    <p class="text-sm text-slate-600 mt-1">Enregistrer un paiement pour cet engagement</p>
                </div>
                <form id="form-ajouter-paiement" class="p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Montant du paiement (FCFA) *</label>
                        <input type="number" name="montant" id="montant-input" required min="0.01" step="0.01"
                            class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Ex: 50000">
                        <?php if($engagementMoisson->reste > 0): ?>
                            <p class="text-xs text-slate-500 mt-1">Reste à payer: <?php echo e(number_format($engagementMoisson->reste, 0, ',', ' ')); ?> FCFA</p>
                        <?php endif; ?>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Notes (optionnel)</label>
                        <textarea name="notes" rows="3"
                            class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Notes sur ce paiement..."></textarea>
                    </div>
                    <div class="flex justify-end gap-3 pt-4">
                        <button type="button" onclick="fermerModal()"
                            class="px-4 py-2 border border-slate-300 text-slate-700 rounded-lg hover:bg-slate-50 transition-colors">
                            Annuler
                        </button>
                        <button type="submit"
                            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                            <i class="fas fa-plus mr-1"></i> Ajouter
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal pour planifier un rappel -->
    <div id="modal-planifier-rappel" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-2xl shadow-xl max-w-md w-full">
                <div class="p-6 border-b border-slate-200">
                    <h3 class="text-lg font-bold text-slate-800">Planifier un rappel</h3>
                    <p class="text-sm text-slate-600 mt-1">Définir une date de rappel pour cet engagement</p>
                </div>
                <form id="form-planifier-rappel" class="p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Date du rappel *</label>
                        <input type="date" name="date_rappel" id="date-rappel-input" required
                            class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div class="flex justify-end gap-3 pt-4">
                        <button type="button" onclick="fermerModalRappel()"
                            class="px-4 py-2 border border-slate-300 text-slate-700 rounded-lg hover:bg-slate-50 transition-colors">
                            Annuler
                        </button>
                        <button type="submit"
                            class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors">
                            <i class="fas fa-bell mr-1"></i> Planifier
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal pour prolonger l'échéance -->
    <div id="modal-prolonger-echeance" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-2xl shadow-xl max-w-lg w-full">
                <div class="p-6 border-b border-slate-200">
                    <h3 class="text-lg font-bold text-slate-800">Prolonger l'échéance</h3>
                    <p class="text-sm text-slate-600 mt-1">Modifier la date d'échéance de cet engagement</p>
                </div>
                <form id="form-prolonger-echeance" class="p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Nouvelle échéance *</label>
                        <input type="date" name="nouvelle_echeance" id="nouvelle-echeance-input" required
                            class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            value="<?php echo e($engagementMoisson->date_echeance?->format('Y-m-d')); ?>">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Motif de la prolongation</label>
                        <textarea name="motif" rows="3"
                            class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Raison de la prolongation..."></textarea>
                    </div>
                    <div class="flex justify-end gap-3 pt-4">
                        <button type="button" onclick="fermerModalEcheance()"
                            class="px-4 py-2 border border-slate-300 text-slate-700 rounded-lg hover:bg-slate-50 transition-colors">
                            Annuler
                        </button>
                        <button type="submit"
                            class="px-4 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition-colors">
                            <i class="fas fa-calendar-plus mr-1"></i> Prolonger
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php $__env->startPush('scripts'); ?>
        <script>
            // Modal pour ajouter un paiement
            function ajouterPaiement() {
                document.getElementById('modal-ajouter-paiement').classList.remove('hidden');
                document.getElementById('montant-input').focus();
            }

            function fermerModal() {
                document.getElementById('modal-ajouter-paiement').classList.add('hidden');
                document.getElementById('form-ajouter-paiement').reset();
            }

            // Modal pour planifier un rappel
            function planifierRappel() {
                document.getElementById('modal-planifier-rappel').classList.remove('hidden');
                document.getElementById('date-rappel-input').focus();
            }

            function fermerModalRappel() {
                document.getElementById('modal-planifier-rappel').classList.add('hidden');
                document.getElementById('form-planifier-rappel').reset();
            }

            // Modal pour prolonger l'échéance
            function prolongerEcheance() {
                document.getElementById('modal-prolonger-echeance').classList.remove('hidden');
                document.getElementById('nouvelle-echeance-input').focus();
            }

            function fermerModalEcheance() {
                document.getElementById('modal-prolonger-echeance').classList.add('hidden');
                document.getElementById('form-prolonger-echeance').reset();
            }

            // Soumission du formulaire d'ajout de paiement
            document.getElementById('form-ajouter-paiement').addEventListener('submit', function(e) {
                e.preventDefault();

                const formData = new FormData(this);
                const donnees = {
                    montant: parseFloat(formData.get('montant')),
                    notes: formData.get('notes')
                };

                fetch(`<?php echo e(route('private.moissons.engagements.ajouter-montant', [$moisson, $engagementMoisson])); ?>`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': "<?php echo e(csrf_token()); ?>"
                    },
                    body: JSON.stringify(donnees)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showNotification('Paiement ajouté avec succès', 'success');
                        setTimeout(() => location.reload(), 1000);
                    } else {
                        showNotification(data.message || 'Erreur lors de l\'ajout du paiement', 'error');
                    }
                    fermerModal();
                })
                .catch(error => {
                    console.error('Erreur:', error);
                    showNotification('Erreur lors de l\'ajout du paiement', 'error');
                    fermerModal();
                });
            });

            // Soumission du formulaire de planification de rappel
            document.getElementById('form-planifier-rappel').addEventListener('submit', function(e) {
                e.preventDefault();

                const formData = new FormData(this);
                const donnees = {
                    date_rappel: formData.get('date_rappel')
                };

                fetch(`<?php echo e(route('private.moissons.engagements.planifier-rappel', [$moisson, $engagementMoisson])); ?>`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': "<?php echo e(csrf_token()); ?>"
                    },
                    body: JSON.stringify(donnees)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showNotification('Rappel planifié avec succès', 'success');
                        setTimeout(() => location.reload(), 1000);
                    } else {
                        showNotification(data.message || 'Erreur lors de la planification', 'error');
                    }
                    fermerModalRappel();
                })
                .catch(error => {
                    console.error('Erreur:', error);
                    showNotification('Erreur lors de la planification du rappel', 'error');
                    fermerModalRappel();
                });
            });

            // Soumission du formulaire de prolongation d'échéance
            document.getElementById('form-prolonger-echeance').addEventListener('submit', function(e) {
                e.preventDefault();

                const formData = new FormData(this);
                const donnees = {
                    nouvelle_echeance: formData.get('nouvelle_echeance'),
                    motif: formData.get('motif')
                };

                fetch(`<?php echo e(route('private.moissons.engagements.prolonger-echeance', [$moisson, $engagementMoisson])); ?>`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': "<?php echo e(csrf_token()); ?>"
                    },
                    body: JSON.stringify(donnees)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showNotification('Échéance prolongée avec succès', 'success');
                        setTimeout(() => location.reload(), 1000);
                    } else {
                        showNotification(data.message || 'Erreur lors de la prolongation', 'error');
                    }
                    fermerModalEcheance();
                })
                .catch(error => {
                    console.error('Erreur:', error);
                    showNotification('Erreur lors de la prolongation de l\'échéance', 'error');
                    fermerModalEcheance();
                });
            });

            // Toggle status
            function toggleStatus() {
                const currentStatus = <?php echo e($engagementMoisson->status ? 'true' : 'false'); ?>;
                const action = currentStatus ? 'désactiver' : 'activer';

                if (!confirm(`Êtes-vous sûr de vouloir ${action} cet engagement ?`)) {
                    return;
                }

                fetch(`<?php echo e(route('private.moissons.engagements.toggle-status', [$moisson, $engagementMoisson])); ?>`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': "<?php echo e(csrf_token()); ?>"
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showNotification('Statut mis à jour avec succès', 'success');
                        setTimeout(() => location.reload(), 1000);
                    } else {
                        showNotification(data.message || 'Erreur lors de la mise à jour', 'error');
                    }
                })
                .catch(error => {
                    console.error('Erreur:', error);
                    showNotification('Erreur lors de la mise à jour du statut', 'error');
                });
            }

            // Fonction utilitaire pour les notifications
            function showNotification(message, type = 'info') {
                const notification = document.createElement('div');
                notification.className = `fixed top-4 right-4 z-50 px-6 py-3 rounded-lg text-white font-medium ${
                    type === 'success' ? 'bg-green-500' :
                    type === 'error' ? 'bg-red-500' : 'bg-blue-500'
                }`;
                notification.textContent = message;

                document.body.appendChild(notification);

                setTimeout(() => {
                    notification.remove();
                }, 5000);
            }

            // Fermer modales avec ESC
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    fermerModal();
                    fermerModalRappel();
                    fermerModalEcheance();
                }
            });

            // Définir les dates minimales
            document.addEventListener('DOMContentLoaded', function() {
                const today = new Date();
                const tomorrow = new Date(today);
                tomorrow.setDate(tomorrow.getDate() + 1);

                const dateRappelInput = document.getElementById('date-rappel-input');
                const nouvelleEcheanceInput = document.getElementById('nouvelle-echeance-input');

                if (dateRappelInput) {
                    dateRappelInput.min = tomorrow.toISOString().split('T')[0];
                }

                if (nouvelleEcheanceInput) {
                    nouvelleEcheanceInput.min = tomorrow.toISOString().split('T')[0];
                }

                // Animation des cartes au chargement
                const cards = document.querySelectorAll('.bg-white\\/80');
                cards.forEach((card, index) => {
                    card.style.opacity = '0';
                    card.style.transform = 'translateY(20px)';
                    setTimeout(() => {
                        card.style.transition = 'all 0.5s ease';
                        card.style.opacity = '1';
                        card.style.transform = 'translateY(0)';
                    }, index * 100);
                });
            });
        </script>
    <?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.private.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Dell\Desktop\MICRISERVICES\methodiste_belle_ville\resources\views/components/private/moissons/engagements/show.blade.php ENDPATH**/ ?>