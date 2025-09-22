<?php $__env->startSection('title', 'Détails de la Transaction'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-8">
    <!-- Page Title & Breadcrumb -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent">Détails de la Transaction</h1>
        <nav class="flex mt-2" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="<?php echo e(route('private.fonds.index')); ?>" class="inline-flex items-center text-sm font-medium text-slate-700 hover:text-blue-600 transition-colors">
                        <i class="fas fa-receipt mr-2"></i>
                        Fonds
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <i class="fas fa-chevron-right text-slate-400 mx-2"></i>
                        <span class="text-sm font-medium text-slate-500"><?php echo e($fonds->numero_transaction); ?></span>
                    </div>
                </li>
            </ol>
        </nav>
    </div>

    <!-- Actions rapides -->
    <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
        <div class="p-6 border-b border-slate-200">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div class="flex items-center space-x-4">
                    <div>
                        <h2 class="text-xl font-bold text-slate-800"><?php echo e($fonds->numero_transaction); ?></h2>
                        <p class="text-slate-500"><?php echo e($fonds->type_transaction_libelle ?? ucfirst(str_replace('_', ' ', $fonds->type_transaction))); ?></p>
                    </div>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                        <?php if($fonds->statut == 'validee'): ?> bg-green-100 text-green-800
                        <?php elseif($fonds->statut == 'en_attente'): ?> bg-yellow-100 text-yellow-800
                        <?php elseif($fonds->statut == 'annulee'): ?> bg-red-100 text-red-800
                        <?php elseif($fonds->statut == 'remboursee'): ?> bg-purple-100 text-purple-800
                        <?php endif; ?>">
                        <?php echo e($fonds->statut_libelle ?? ucfirst($fonds->statut)); ?>

                    </span>
                </div>
                <div class="flex flex-wrap gap-2">
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('fonds.create')): ?>
                        <a href="<?php echo e(route('private.fonds.create')); ?>" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-600 to-purple-600 text-white text-sm font-medium rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow-md hover:shadow-lg">
                            <i class="fas fa-plus mr-2"></i> Nouvelle Transaction
                        </a>
                    <?php endif; ?>

                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('fonds.update')): ?>
                        <?php if($fonds->peutEtreModifiee()): ?>
                            <a href="<?php echo e(route('private.fonds.edit', $fonds)); ?>" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-600 to-purple-600 text-white text-sm font-medium rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow-md hover:shadow-lg">
                                <i class="fas fa-edit mr-2"></i> Modifier
                            </a>
                        <?php endif; ?>
                    <?php endif; ?>

                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('fonds.validate')): ?>
                        <?php if($fonds->peutEtreValidee()): ?>
                            <button type="button" onclick="validateTransaction()" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-green-600 to-emerald-600 text-white text-sm font-medium rounded-xl hover:from-green-700 hover:to-emerald-700 transition-all duration-200 shadow-md hover:shadow-lg">
                                <i class="fas fa-check mr-2"></i> Valider
                            </button>
                        <?php endif; ?>
                    <?php endif; ?>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('fonds.cancel')): ?>
                        <?php if($fonds->peutEtreAnnulee()): ?>
                            <button type="button" onclick="openCancelModal()" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-red-600 to-pink-600 text-white text-sm font-medium rounded-xl hover:from-red-700 hover:to-pink-700 transition-all duration-200 shadow-md hover:shadow-lg">
                                <i class="fas fa-times mr-2"></i> Annuler
                            </button>
                        <?php endif; ?>
                    <?php endif; ?>

                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('fonds.refund')): ?>
                        <?php if($fonds->statut == 'validee'): ?>
                            <button type="button" onclick="openRefundModal()" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-purple-600 to-indigo-600 text-white text-sm font-medium rounded-xl hover:from-purple-700 hover:to-indigo-700 transition-all duration-200 shadow-md hover:shadow-lg">
                                <i class="fas fa-undo mr-2"></i> Rembourser
                            </button>
                        <?php endif; ?>
                    <?php endif; ?>

                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('fonds.generate-receipt')): ?>
                        <?php if($fonds->peutGenererRecu()): ?>
                            <a href="<?php echo e(route('private.fonds.receipt.download', $fonds)); ?>"  class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-amber-600 to-orange-600 text-white text-sm font-medium rounded-xl hover:from-amber-700 hover:to-orange-700 transition-all duration-200 shadow-md hover:shadow-lg">
                                <i class="fas fa-receipt mr-2"></i> Générer Reçu
                            </a>
                        <?php endif; ?>
                    <?php endif; ?>

                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('fonds.duplicate')): ?>
                        <button type="button" onclick="duplicateTransaction()" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-cyan-600 to-blue-600 text-white text-sm font-medium rounded-xl hover:from-cyan-700 hover:to-blue-700 transition-all duration-200 shadow-md hover:shadow-lg">
                            <i class="fas fa-copy mr-2"></i> Dupliquer
                        </button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Informations principales -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Informations de base -->
            <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="p-6 border-b border-slate-200">
                    <h2 class="text-xl font-bold text-slate-800 flex items-center">
                        <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                        Informations Générales
                    </h2>
                </div>
                <div class="p-6">
                    <dl class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <dt class="text-sm font-medium text-slate-500 mb-1">Type de transaction</dt>
                            <dd class="text-lg font-semibold text-slate-900">
                                <?php echo e($fonds->type_transaction_libelle ?? ucfirst(str_replace('_', ' ', $fonds->type_transaction))); ?>

                                <?php if($fonds->categorie != 'reguliere'): ?>
                                    <span class="ml-2 text-sm px-2 py-1 bg-orange-100 text-orange-800 rounded-full"><?php echo e(ucfirst($fonds->categorie)); ?></span>
                                <?php endif; ?>
                            </dd>
                        </div>

                        <div>
                            <dt class="text-sm font-medium text-slate-500 mb-1">Montant</dt>
                            <dd class="text-2xl font-bold text-slate-900">
                                <?php echo e(number_format($fonds->montant, 0, ',', ' ')); ?> <?php echo e($fonds->devise); ?>

                                <?php if($fonds->valeur_estimee && $fonds->valeur_estimee != $fonds->montant): ?>
                                    <div class="text-sm text-slate-500 font-normal">Valeur estimée: <?php echo e(number_format($fonds->valeur_estimee, 0, ',', ' ')); ?> <?php echo e($fonds->devise); ?></div>
                                <?php endif; ?>
                            </dd>
                        </div>

                        <div>
                            <dt class="text-sm font-medium text-slate-500 mb-1">Date et heure</dt>
                            <dd class="text-lg text-slate-900">
                                <?php echo e($fonds->date_transaction->format('d/m/Y')); ?>

                                <?php if($fonds->heure_transaction): ?>
                                    à <?php echo e($fonds->heure_transaction->format('H:i')); ?>

                                <?php endif; ?>
                            </dd>
                        </div>

                        <div>
                            <dt class="text-sm font-medium text-slate-500 mb-1">Mode de paiement</dt>
                            <dd class="text-lg text-slate-900">
                                <span class="inline-flex items-center">
                                    <?php if($fonds->mode_paiement == 'especes'): ?>
                                        <i class="fas fa-money-bill text-green-600 mr-2"></i>Espèces
                                    <?php elseif($fonds->mode_paiement == 'mobile_money'): ?>
                                        <i class="fas fa-mobile-alt text-blue-600 mr-2"></i>Mobile Money
                                    <?php elseif($fonds->mode_paiement == 'virement'): ?>
                                        <i class="fas fa-university text-purple-600 mr-2"></i>Virement bancaire
                                    <?php elseif($fonds->mode_paiement == 'cheque'): ?>
                                        <i class="fas fa-check text-orange-600 mr-2"></i>Chèque
                                    <?php else: ?>
                                        <i class="fas fa-gift text-pink-600 mr-2"></i>Don en nature
                                    <?php endif; ?>
                                </span>
                                <?php if($fonds->reference_paiement): ?>
                                    <div class="text-sm text-slate-500">Réf: <?php echo e($fonds->reference_paiement); ?></div>
                                <?php endif; ?>
                            </dd>
                        </div>

                        <?php if($fonds->culte): ?>
                            <div class="md:col-span-2">
                                <dt class="text-sm font-medium text-slate-500 mb-1">Culte associé</dt>
                                <dd class="text-lg text-slate-900">
                                    <a href="<?php echo e(route('private.cultes.show', $fonds->culte)); ?>" class="text-blue-600 hover:text-blue-800 transition-colors">
                                        <?php echo e($fonds->culte->titre); ?> - <?php echo e($fonds->culte->date_culte->format('d/m/Y')); ?>

                                    </a>
                                </dd>
                            </div>
                        <?php endif; ?>

                        <?php if($fonds->occasion_speciale): ?>
                            <div>
                                <dt class="text-sm font-medium text-slate-500 mb-1">Occasion spéciale</dt>
                                <dd class="text-lg text-slate-900"><?php echo e($fonds->occasion_speciale); ?></dd>
                            </div>
                        <?php endif; ?>

                        <?php if($fonds->lieu_collecte): ?>
                            <div>
                                <dt class="text-sm font-medium text-slate-500 mb-1">Lieu de collecte</dt>
                                <dd class="text-lg text-slate-900"><?php echo e($fonds->lieu_collecte); ?></dd>
                            </div>
                        <?php endif; ?>
                    </dl>
                </div>
            </div>

            <!-- Informations du donateur -->
            <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="p-6 border-b border-slate-200">
                    <h2 class="text-xl font-bold text-slate-800 flex items-center">
                        <i class="fas fa-user text-green-600 mr-2"></i>
                        Informations du Donateur
                    </h2>
                </div>
                <div class="p-6">
                    <dl class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <dt class="text-sm font-medium text-slate-500 mb-1">Nom du donateur</dt>
                            <dd class="text-lg font-semibold text-slate-900">
                                <?php if($fonds->est_anonyme): ?>
                                    <span class="text-slate-500 italic">
                                        <i class="fas fa-user-secret mr-1"></i>Donateur anonyme
                                    </span>
                                <?php elseif($fonds->donateur): ?>
                                    <?php echo e($fonds->donateur->nom); ?> <?php echo e($fonds->donateur->prenom); ?>

                                    <?php if($fonds->est_membre): ?>
                                        <span class="ml-2 text-xs px-2 py-1 bg-blue-100 text-blue-800 rounded-full">Membre</span>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <?php echo e($fonds->nom_donateur_anonyme ?? 'Non spécifié'); ?>

                                    <?php if(!$fonds->est_membre): ?>
                                        <span class="ml-2 text-xs px-2 py-1 bg-gray-100 text-gray-800 rounded-full">Externe</span>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </dd>
                        </div>

                        <div>
                            <dt class="text-sm font-medium text-slate-500 mb-1">Contact</dt>
                            <dd class="text-lg text-slate-900">
                                <?php if($fonds->donateur): ?>
                                    <div><?php echo e($fonds->donateur->email ?? 'N/A'); ?></div>
                                    <div class="text-sm text-slate-500"><?php echo e($fonds->donateur->telephone_1 ?? 'N/A'); ?></div>
                                <?php elseif($fonds->contact_donateur): ?>
                                    <?php echo e($fonds->contact_donateur); ?>

                                <?php else: ?>
                                    <span class="text-slate-400">Non spécifié</span>
                                <?php endif; ?>
                            </dd>
                        </div>

                        <?php if($fonds->collecteur): ?>
                            <div>
                                <dt class="text-sm font-medium text-slate-500 mb-1">Collecté par</dt>
                                <dd class="text-lg text-slate-900"><?php echo e($fonds->collecteur->nom); ?> <?php echo e($fonds->collecteur->prenom); ?></dd>
                            </div>
                        <?php endif; ?>

                        <?php if($fonds->validateur): ?>
                            <div>
                                <dt class="text-sm font-medium text-slate-500 mb-1">Validé par</dt>
                                <dd class="text-lg text-slate-900">
                                    <?php echo e($fonds->validateur->nom); ?> <?php echo e($fonds->validateur->prenom); ?>

                                    <?php if($fonds->validee_le): ?>
                                        <div class="text-sm text-slate-500">le <?php echo e($fonds->validee_le->format('d/m/Y à H:i')); ?></div>
                                    <?php endif; ?>
                                </dd>
                            </div>
                        <?php endif; ?>
                    </dl>
                </div>
            </div>

            <?php if($fonds->type_transaction == 'don_materiel' || $fonds->description_don_nature): ?>
                <!-- Don en nature -->
                <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                    <div class="p-6 border-b border-slate-200">
                        <h2 class="text-xl font-bold text-slate-800 flex items-center">
                            <i class="fas fa-gift text-purple-600 mr-2"></i>
                            Détails du Don en Nature
                        </h2>
                    </div>
                    <div class="p-6">
                        <?php if($fonds->description_don_nature): ?>
                            <div class="mb-4">
                                <dt class="text-sm font-medium text-slate-500 mb-2">Description</dt>
                                <dd class="text-slate-900 bg-slate-50 rounded-lg p-4">
                                    <?php echo nl2br(e($fonds->description_don_nature)); ?>

                                </dd>
                            </div>
                        <?php endif; ?>

                        <?php if($fonds->valeur_estimee): ?>
                            <div>
                                <dt class="text-sm font-medium text-slate-500 mb-1">Valeur estimée</dt>
                                <dd class="text-lg font-semibold text-slate-900"><?php echo e(number_format($fonds->valeur_estimee, 0, ',', ' ')); ?> <?php echo e($fonds->devise); ?></dd>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>

            <?php if($fonds->est_flechee || $fonds->destination || $fonds->projet || $fonds->instructions_donateur): ?>
                <!-- Affectation et instructions -->
                <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                    <div class="p-6 border-b border-slate-200">
                        <h2 class="text-xl font-bold text-slate-800 flex items-center">
                            <i class="fas fa-bullseye text-amber-600 mr-2"></i>
                            Affectation et Instructions
                        </h2>
                    </div>
                    <div class="p-6 space-y-4">
                        <?php if($fonds->est_flechee): ?>
                            <div class="bg-orange-50 border-l-4 border-orange-400 p-4">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-arrow-right text-orange-400"></i>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm text-orange-700">Cette offrande est fléchée pour un usage spécifique.</p>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>

                        <?php if($fonds->destination): ?>
                            <div>
                                <dt class="text-sm font-medium text-slate-500 mb-1">Destination</dt>
                                <dd class="text-lg text-slate-900"><?php echo e($fonds->destination); ?></dd>
                            </div>
                        <?php endif; ?>

                        <?php if($fonds->projet): ?>
                            <div>
                                <dt class="text-sm font-medium text-slate-500 mb-1">Projet bénéficiaire</dt>
                                <dd class="text-lg text-slate-900">
                                    <a href="<?php echo e(route('private.projets.show', $fonds->projet)); ?>" class="text-blue-600 hover:text-blue-800 transition-colors">
                                        <?php echo e($fonds->projet->nom); ?>

                                    </a>
                                </dd>
                            </div>
                        <?php endif; ?>

                        <?php if($fonds->instructions_donateur): ?>
                            <div>
                                <dt class="text-sm font-medium text-slate-500 mb-2">Instructions du donateur</dt>
                                <dd class="text-slate-900 bg-blue-50 border-l-4 border-blue-400 p-4">
                                    <?php echo nl2br(e($fonds->instructions_donateur)); ?>

                                </dd>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>

            <?php if($fonds->notes_validation || $fonds->motif_annulation || $fonds->notes_comptable): ?>
                <!-- Notes et commentaires -->
                <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                    <div class="p-6 border-b border-slate-200">
                        <h2 class="text-xl font-bold text-slate-800 flex items-center">
                            <i class="fas fa-comments text-indigo-600 mr-2"></i>
                            Notes et Commentaires
                        </h2>
                    </div>
                    <div class="p-6 space-y-4">
                        <?php if($fonds->notes_validation): ?>
                            <div>
                                <dt class="text-sm font-medium text-green-600 mb-2">Notes de validation</dt>
                                <dd class="text-slate-900 bg-green-50 border-l-4 border-green-400 p-4">
                                    <?php echo nl2br(e($fonds->notes_validation)); ?>

                                </dd>
                            </div>
                        <?php endif; ?>

                        <?php if($fonds->motif_annulation): ?>
                            <div>
                                <dt class="text-sm font-medium text-red-600 mb-2">Motif d'annulation/remboursement</dt>
                                <dd class="text-slate-900 bg-red-50 border-l-4 border-red-400 p-4">
                                    <?php echo nl2br(e($fonds->motif_annulation)); ?>

                                </dd>
                            </div>
                        <?php endif; ?>

                        <?php if($fonds->notes_comptable): ?>
                            <div>
                                <dt class="text-sm font-medium text-purple-600 mb-2">Notes comptables</dt>
                                <dd class="text-slate-900 bg-purple-50 border-l-4 border-purple-400 p-4">
                                    <?php echo nl2br(e($fonds->notes_comptable)); ?>

                                </dd>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>

            <?php if($fonds->transactionsEnfants && $fonds->transactionsEnfants->count() > 0): ?>
                <!-- Transactions récurrentes -->
                <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                    <div class="p-6 border-b border-slate-200">
                        <h2 class="text-xl font-bold text-slate-800 flex items-center">
                            <i class="fas fa-sync-alt text-blue-600 mr-2"></i>
                            Transactions Récurrentes (<?php echo e($fonds->transactionsEnfants->count()); ?>)
                        </h2>
                    </div>
                    <div class="p-6">
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead>
                                    <tr class="bg-slate-50 border-b border-slate-200">
                                        <th class="text-left py-3 px-4 font-medium text-slate-600">N° Transaction</th>
                                        <th class="text-left py-3 px-4 font-medium text-slate-600">Date</th>
                                        <th class="text-left py-3 px-4 font-medium text-slate-600">Montant</th>
                                        <th class="text-left py-3 px-4 font-medium text-slate-600">Statut</th>
                                        <th class="text-left py-3 px-4 font-medium text-slate-600">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $fonds->transactionsEnfants->sortByDesc('date_transaction'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $enfant): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr class="border-b border-slate-100 hover:bg-slate-50/50 transition-colors">
                                            <td class="py-3 px-4 font-medium text-slate-900"><?php echo e($enfant->numero_transaction); ?></td>
                                            <td class="py-3 px-4 text-slate-600"><?php echo e($enfant->date_transaction->format('d/m/Y')); ?></td>
                                            <td class="py-3 px-4 font-semibold text-slate-900"><?php echo e(number_format($enfant->montant, 0, ',', ' ')); ?> <?php echo e($enfant->devise); ?></td>
                                            <td class="py-3 px-4">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                    <?php if($enfant->statut == 'validee'): ?> bg-green-100 text-green-800
                                                    <?php elseif($enfant->statut == 'en_attente'): ?> bg-yellow-100 text-yellow-800
                                                    <?php else: ?> bg-red-100 text-red-800
                                                    <?php endif; ?>">
                                                    <?php echo e($enfant->statut_libelle ?? ucfirst($enfant->statut)); ?>

                                                </span>
                                            </td>
                                            <td class="py-3 px-4">
                                                <a href="<?php echo e(route('private.fonds.show', $enfant)); ?>" class="inline-flex items-center justify-center w-8 h-8 text-cyan-600 bg-cyan-100 rounded-lg hover:bg-cyan-200 transition-colors" title="Voir">
                                                    <i class="fas fa-eye text-sm"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <!-- Sidebar - Informations complémentaires -->
        <div class="space-y-6">
            <!-- Résumé -->
            <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="p-6 border-b border-slate-200">
                    <h2 class="text-xl font-bold text-slate-800 flex items-center">
                        <i class="fas fa-chart-pie text-purple-600 mr-2"></i>
                        Résumé
                    </h2>
                </div>
                <div class="p-6 space-y-4">
                    <div class="text-center">
                        <div class="text-3xl font-bold text-slate-900"><?php echo e(number_format($fonds->montant, 0, ',', ' ')); ?></div>
                        <div class="text-sm text-slate-500"><?php echo e($fonds->devise); ?></div>
                    </div>

                    <div class="space-y-3 text-sm">
                        <div class="flex items-center justify-between">
                            <span class="text-slate-600">Type:</span>
                            <span class="font-medium text-slate-900"><?php echo e($fonds->type_transaction_libelle ?? ucfirst(str_replace('_', ' ', $fonds->type_transaction))); ?></span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-slate-600">Date:</span>
                            <span class="font-medium text-slate-900"><?php echo e($fonds->date_transaction->format('d/m/Y')); ?></span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-slate-600">Mode:</span>
                            <span class="font-medium text-slate-900"><?php echo e($fonds->mode_paiement_libelle ?? ucfirst($fonds->mode_paiement)); ?></span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-slate-600">Statut:</span>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                <?php if($fonds->statut == 'validee'): ?> bg-green-100 text-green-800
                                <?php elseif($fonds->statut == 'en_attente'): ?> bg-yellow-100 text-yellow-800
                                <?php elseif($fonds->statut == 'annulee'): ?> bg-red-100 text-red-800
                                <?php elseif($fonds->statut == 'remboursee'): ?> bg-purple-100 text-purple-800
                                <?php endif; ?>">
                                <?php echo e($fonds->statut_libelle ?? ucfirst($fonds->statut)); ?>

                            </span>
                        </div>
                    </div>

                    <?php if($fonds->est_recurrente): ?>
                        <div class="border-t border-slate-200 pt-4">
                            <div class="flex items-center text-sm">
                                <i class="fas fa-sync-alt text-purple-600 mr-2"></i>
                                <span class="text-purple-700">
                                    Récurrence <?php echo e($fonds->frequence_recurrence); ?>

                                    <?php if($fonds->prochaine_echeance): ?>
                                        <br><span class="text-xs text-slate-500">Prochaine: <?php echo e($fonds->prochaine_echeance->format('d/m/Y')); ?></span>
                                    <?php endif; ?>
                                </span>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if($fonds->est_flechee): ?>
                        <div class="border-t border-slate-200 pt-4">
                            <div class="flex items-center text-sm">
                                <i class="fas fa-arrow-right text-orange-600 mr-2"></i>
                                <span class="text-orange-700">Offrande fléchée</span>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Reçu fiscal -->
            <?php if($fonds->recu_demande || $fonds->recu_emis): ?>
                <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                    <div class="p-6 border-b border-slate-200">
                        <h2 class="text-xl font-bold text-slate-800 flex items-center">
                            <i class="fas fa-receipt text-green-600 mr-2"></i>
                            Reçu Fiscal
                        </h2>
                    </div>
                    <div class="p-6 space-y-4">
                        <?php if($fonds->recu_emis && $fonds->numero_recu): ?>
                            <div class="text-center">
                                <div class="text-2xl font-bold text-green-600"><?php echo e($fonds->numero_recu); ?></div>
                                <div class="text-sm text-slate-500">
                                    Émis le <?php echo e($fonds->date_emission_recu ? \Carbon\Carbon::parse($fonds->date_emission_recu)->format('d/m/Y') : 'N/A'); ?>

                                </div>
                            </div>

                            <?php if($fonds->deductible_impots): ?>
                                <div class="bg-green-50 border-l-4 border-green-400 p-4">
                                    <div class="flex">
                                        <div class="flex-shrink-0">
                                            <i class="fas fa-check-circle text-green-400"></i>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-sm text-green-700">Don déductible des impôts</p>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <?php if($fonds->fichier_recu): ?>
                                <div class="text-center">
                                    <a href="<?php echo e(Storage::url($fonds->fichier_recu)); ?>" target="_blank" class="inline-flex items-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-xl hover:bg-green-700 transition-colors">
                                        <i class="fas fa-download mr-2"></i> Télécharger le reçu
                                    </a>
                                </div>
                            <?php endif; ?>
                        <?php elseif($fonds->recu_demande && !$fonds->recu_emis): ?>
                            <div class="text-center">
                                <div class="text-lg text-orange-600">
                                    <i class="fas fa-clock mr-2"></i>En attente d'émission
                                </div>
                                <div class="text-sm text-slate-500">Reçu fiscal demandé</div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Suivi et historique -->
            <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="p-6 border-b border-slate-200">
                    <h2 class="text-xl font-bold text-slate-800 flex items-center">
                        <i class="fas fa-history text-cyan-600 mr-2"></i>
                        Suivi et Historique
                    </h2>
                </div>
                <div class="p-6 space-y-4 text-sm">
                    <div class="flex items-center justify-between">
                        <span class="text-slate-600">Créé le:</span>
                        <span class="text-slate-900"><?php echo e($fonds->created_at->format('d/m/Y H:i')); ?></span>
                    </div>

                    <?php if($fonds->updated_at && $fonds->updated_at != $fonds->created_at): ?>
                        <div class="flex items-center justify-between">
                            <span class="text-slate-600">Modifié le:</span>
                            <span class="text-slate-900"><?php echo e($fonds->updated_at->format('d/m/Y H:i')); ?></span>
                        </div>
                    <?php endif; ?>

                    <?php if($fonds->createur): ?>
                        <div class="flex items-center justify-between">
                            <span class="text-slate-600">Créé par:</span>
                            <span class="text-slate-900"><?php echo e($fonds->createur->nom); ?> <?php echo e($fonds->createur->prenom); ?></span>
                        </div>
                    <?php endif; ?>

                    <?php if($fonds->derniere_verification): ?>
                        <div class="flex items-center justify-between">
                            <span class="text-slate-600">Vérifiée le:</span>
                            <span class="text-slate-900"><?php echo e($fonds->derniere_verification->format('d/m/Y')); ?></span>
                        </div>
                        <?php if($fonds->verificateur): ?>
                            <div class="flex items-center justify-between">
                                <span class="text-slate-600">Vérifiée par:</span>
                                <span class="text-slate-900"><?php echo e($fonds->verificateur->nom); ?> <?php echo e($fonds->verificateur->prenom); ?></span>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>

                    <div class="flex items-center justify-between">
                        <span class="text-slate-600">Temps écoulé:</span>
                        <span class="text-slate-900"><?php echo e($fonds->jours_depuis_transaction); ?> jours</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal validation -->
<div id="validateModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl max-w-md w-full">
        <div class="p-6">
            <h3 class="text-lg font-semibold text-slate-900 mb-4">Valider la transaction</h3>
            <div class="mb-4">
                <label class="block text-sm font-medium text-slate-700 mb-2">Notes de validation (optionnel)</label>
                <textarea id="validation_notes" rows="3" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors resize-none" placeholder="Notes de validation..."></textarea>
            </div>
        </div>
        <div class="flex items-center justify-end space-x-3 p-6 border-t border-slate-200">
            <button type="button" onclick="closeValidateModal()" class="px-4 py-2 text-slate-700 bg-slate-100 rounded-xl hover:bg-slate-200 transition-colors">
                Annuler
            </button>
            <button type="button" onclick="confirmValidation()" class="px-4 py-2 bg-green-600 text-white rounded-xl hover:bg-green-700 transition-colors">
                Valider la transaction
            </button>
        </div>
    </div>
</div>

<!-- Modal annulation -->
<div id="cancelModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl max-w-md w-full">
        <div class="p-6">
            <h3 class="text-lg font-semibold text-slate-900 mb-4">Annuler la transaction</h3>
            <div class="mb-4">
                <label class="block text-sm font-medium text-slate-700 mb-2">Motif d'annulation <span class="text-red-500">*</span></label>
                <textarea id="cancel_reason" required rows="3" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors resize-none" placeholder="Motif de l'annulation..."></textarea>
            </div>
        </div>
        <div class="flex items-center justify-end space-x-3 p-6 border-t border-slate-200">
            <button type="button" onclick="closeCancelModal()" class="px-4 py-2 text-slate-700 bg-slate-100 rounded-xl hover:bg-slate-200 transition-colors">
                Annuler
            </button>
            <button type="button" onclick="confirmCancellation()" class="px-4 py-2 bg-red-600 text-white rounded-xl hover:bg-red-700 transition-colors">
                Annuler la transaction
            </button>
        </div>
    </div>
</div>

<!-- Modal remboursement -->
<div id="refundModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl max-w-md w-full">
        <div class="p-6">
            <h3 class="text-lg font-semibold text-slate-900 mb-4">Rembourser la transaction</h3>
            <div class="mb-4">
                <label class="block text-sm font-medium text-slate-700 mb-2">Motif du remboursement <span class="text-red-500">*</span></label>
                <textarea id="refund_reason" required rows="3" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors resize-none" placeholder="Motif du remboursement..."></textarea>
            </div>
        </div>
        <div class="flex items-center justify-end space-x-3 p-6 border-t border-slate-200">
            <button type="button" onclick="closeRefundModal()" class="px-4 py-2 text-slate-700 bg-slate-100 rounded-xl hover:bg-slate-200 transition-colors">
                Annuler
            </button>
            <button type="button" onclick="confirmRefund()" class="px-4 py-2 bg-purple-600 text-white rounded-xl hover:bg-purple-700 transition-colors">
                Rembourser
            </button>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
// Validation de la transaction
function validateTransaction() {
    document.getElementById('validateModal').classList.remove('hidden');
}

function closeValidateModal() {
    document.getElementById('validateModal').classList.add('hidden');
    document.getElementById('validation_notes').value = '';
}

function confirmValidation() {
    const notes = document.getElementById('validation_notes').value;

    fetch(`<?php echo e(route('private.fonds.validate.strict', $fonds)); ?>`, {
        method: 'PATCH',
        headers: {
            'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            notes_validation: notes
        })
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

// Annulation de la transaction
function openCancelModal() {
    document.getElementById('cancelModal').classList.remove('hidden');
}

function closeCancelModal() {
    document.getElementById('cancelModal').classList.add('hidden');
    document.getElementById('cancel_reason').value = '';
}

function confirmCancellation() {
    const reason = document.getElementById('cancel_reason').value;

    if (!reason.trim()) {
        alert('Veuillez saisir un motif d\'annulation');
        return;
    }

    fetch(`<?php echo e(route('private.fonds.cancel.strict', $fonds)); ?>`, {
        method: 'PATCH',
        headers: {
            'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            motif_annulation: reason
        })
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

// Remboursement de la transaction
function openRefundModal() {
    document.getElementById('refundModal').classList.remove('hidden');
}

function closeRefundModal() {
    document.getElementById('refundModal').classList.add('hidden');
    document.getElementById('refund_reason').value = '';
}

function confirmRefund() {
    const reason = document.getElementById('refund_reason').value;

    if (!reason.trim()) {
        alert('Veuillez saisir un motif de remboursement');
        return;
    }

    fetch(`<?php echo e(route('private.fonds.refund', $fonds)); ?>`, {
        method: 'PATCH',
        headers: {
            'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            motif_annulation: reason
        })
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

// Génération de reçu
function generateReceipt() {
    if (confirm('Générer un reçu fiscal pour cette transaction ?')) {
        // 'private.fonds.cancel.strict'
        fetch(`<?php echo e(route('private.fonds.receipt.strict', $fonds)); ?>`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(`Reçu généré avec succès. Numéro: ${data.data.numero_recu}`);
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
}

// Duplication
function duplicateTransaction() {
    if (confirm('Dupliquer cette transaction ?')) {
        fetch(`<?php echo e(route('private.fonds.duplicate', $fonds)); ?>`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.href = `<?php echo e(route('private.fonds.show', '')); ?>/${data.data.id}`;
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

// Fermer les modals en cliquant à l'extérieur
document.getElementById('validateModal').addEventListener('click', function(e) {
    if (e.target === this) closeValidateModal();
});

document.getElementById('cancelModal').addEventListener('click', function(e) {
    if (e.target === this) closeCancelModal();
});

document.getElementById('refundModal').addEventListener('click', function(e) {
    if (e.target === this) closeRefundModal();
});
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.private.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Dell\Desktop\MICRISERVICES\methodiste_belle_ville\resources\views/components/private/fonds/show.blade.php ENDPATH**/ ?>