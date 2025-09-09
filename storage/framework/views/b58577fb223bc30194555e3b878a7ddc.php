<?php $__env->startSection('title', 'Gestion des Fonds'); ?>

<?php $__env->startSection('content'); ?>
    <div class="space-y-8">
        <!-- Page Title -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent">Gestion
                des Fonds</h1>
            <p class="text-slate-500 mt-1">Suivi des transactions financières -
                <?php echo e(\Carbon\Carbon::now()->format('l d F Y')); ?></p>
        </div>

        <!-- Filtres et actions -->
        <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
            <div class="p-6 border-b border-slate-200">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <h2 class="text-xl font-bold text-slate-800 flex items-center">
                        <i class="fas fa-filter text-blue-600 mr-2"></i>
                        Filtres et Actions
                    </h2>
                    <div class="flex flex-wrap gap-2">
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('fonds.create')): ?>
                            <a href="<?php echo e(route('private.fonds.create')); ?>"
                                class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-600 to-purple-600 text-white text-sm font-medium rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow-md hover:shadow-lg">
                                <i class="fas fa-plus mr-2"></i> Nouvelle Transaction
                            </a>
                        <?php endif; ?>
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('fonds.dashboard')): ?>
                            <a href="<?php echo e(route('private.fonds.dashboard')); ?>"
                                class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-green-600 to-emerald-600 text-white text-sm font-medium rounded-xl hover:from-green-700 hover:to-emerald-700 transition-all duration-200 shadow-md hover:shadow-lg">
                                <i class="fas fa-tachometer-alt mr-2"></i> Tableau de Bord
                            </a>
                        <?php endif; ?>
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('fonds.statistics')): ?>
                            <a href="<?php echo e(route('private.fonds.statistics')); ?>"
                                class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-cyan-600 to-blue-600 text-white text-sm font-medium rounded-xl hover:from-cyan-700 hover:to-blue-700 transition-all duration-200 shadow-md hover:shadow-lg">
                                <i class="fas fa-chart-bar mr-2"></i> Statistiques
                            </a>
                        <?php endif; ?>
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('fonds.analytics')): ?>
                            <a href="<?php echo e(route('private.fonds.analytics')); ?>"
                                class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-amber-600 to-orange-600 text-white text-sm font-medium rounded-xl hover:from-amber-700 hover:to-orange-700 transition-all duration-200 shadow-md hover:shadow-lg">
                                <i class="fas fa-chart-line mr-2"></i> Analytics
                            </a>
                        <?php endif; ?>
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('fonds.export')): ?>
                            <a href="<?php echo e(route('private.fonds.export')); ?>"
                                class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-purple-600 to-pink-600 text-white text-sm font-medium rounded-xl hover:from-purple-700 hover:to-pink-700 transition-all duration-200 shadow-md hover:shadow-lg">
                                <i class="fas fa-download mr-2"></i> Exporter
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="p-6">
                <form method="GET" action="<?php echo e(route('private.fonds.index')); ?>"
                    class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-4">
                    <div class="lg:col-span-2">
                        <label class="block text-sm font-medium text-slate-700 mb-2">Recherche</label>
                        <div class="relative">
                            <input type="text" name="search" value="<?php echo e(request('search')); ?>"
                                placeholder="Numéro, donateur, référence..."
                                class="w-full pl-10 pr-4 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-slate-400"></i>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Statut</label>
                        <select name="statut"
                            class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            <option value="">Tous les statuts</option>
                            <option value="en_attente" <?php echo e(request('statut') == 'en_attente' ? 'selected' : ''); ?>>En attente
                            </option>
                            <option value="validee" <?php echo e(request('statut') == 'validee' ? 'selected' : ''); ?>>Validée</option>
                            <option value="annulee" <?php echo e(request('statut') == 'annulee' ? 'selected' : ''); ?>>Annulée</option>
                            <option value="remboursee" <?php echo e(request('statut') == 'remboursee' ? 'selected' : ''); ?>>Remboursée
                            </option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Type</label>
                        <select name="type_transaction"
                            class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            <option value="">Tous les types</option>
                            <option value="dime" <?php echo e(request('type_transaction') == 'dime' ? 'selected' : ''); ?>>Dîme
                            </option>
                            <option value="offrande_libre"
                                <?php echo e(request('type_transaction') == 'offrande_libre' ? 'selected' : ''); ?>>Offrande libre
                            </option>
                            <option value="offrande_ordinaire"
                                <?php echo e(request('type_transaction') == 'offrande_ordinaire' ? 'selected' : ''); ?>>Offrande
                                ordinaire</option>
                            <option value="offrande_speciale"
                                <?php echo e(request('type_transaction') == 'offrande_speciale' ? 'selected' : ''); ?>>Offrande
                                spéciale</option>
                            <option value="don_special"
                                <?php echo e(request('type_transaction') == 'don_special' ? 'selected' : ''); ?>>Don spécial</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Mode paiement</label>
                        <select name="mode_paiement"
                            class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            <option value="">Tous les modes</option>
                            <option value="especes" <?php echo e(request('mode_paiement') == 'especes' ? 'selected' : ''); ?>>Espèces
                            </option>
                            <option value="mobile_money"
                                <?php echo e(request('mode_paiement') == 'mobile_money' ? 'selected' : ''); ?>>Mobile Money</option>
                            <option value="virement" <?php echo e(request('mode_paiement') == 'virement' ? 'selected' : ''); ?>>
                                Virement</option>
                            <option value="cheque" <?php echo e(request('mode_paiement') == 'cheque' ? 'selected' : ''); ?>>Chèque
                            </option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Donateur</label>
                        <select name="donateur_id"
                            class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            <option value="">Tous les donateurs</option>
                            <?php $__currentLoopData = $filterData['donateurs']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $donateur): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($donateur->id); ?>"
                                    <?php echo e(request('donateur_id') == $donateur->id ? 'selected' : ''); ?>>
                                    <?php echo e($donateur->nom); ?> <?php echo e($donateur->prenom); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="lg:col-span-6 grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Date début</label>
                            <input type="date" name="date_debut" value="<?php echo e(request('date_debut')); ?>"
                                class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Date fin</label>
                            <input type="date" name="date_fin" value="<?php echo e(request('date_fin')); ?>"
                                class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Montant min</label>
                            <input type="number" name="montant_min" value="<?php echo e(request('montant_min')); ?>"
                                class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Montant max</label>
                            <input type="number" name="montant_max" value="<?php echo e(request('montant_max')); ?>"
                                class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        </div>
                    </div>
                    <div class="lg:col-span-6 flex gap-2 pt-4">
                        <button type="submit"
                            class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-xl hover:bg-blue-700 transition-colors">
                            <i class="fas fa-search mr-2"></i> Rechercher
                        </button>
                        <a href="<?php echo e(route('private.fonds.index')); ?>"
                            class="inline-flex items-center px-4 py-2 bg-slate-600 text-white text-sm font-medium rounded-xl hover:bg-slate-700 transition-colors">
                            <i class="fas fa-refresh mr-2"></i> Réinitialiser
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Statistiques rapides -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <div
                class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div
                            class="w-12 h-12 bg-gradient-to-r from-blue-500 to-cyan-500 rounded-xl flex items-center justify-center shadow-lg">
                            <i class="fas fa-receipt text-white text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-2xl font-bold text-slate-800"><?php echo e($fonds->total()); ?></p>
                        <p class="text-sm text-slate-500">Total transactions</p>
                    </div>
                </div>
            </div>

            <div
                class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div
                            class="w-12 h-12 bg-gradient-to-r from-green-500 to-emerald-500 rounded-xl flex items-center justify-center shadow-lg">
                            <i class="fas fa-check-circle text-white text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-2xl font-bold text-slate-800"><?php echo e($fonds->where('statut', 'validee')->count()); ?></p>
                        <p class="text-sm text-slate-500">Validées</p>
                    </div>
                </div>
            </div>

            <div
                class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div
                            class="w-12 h-12 bg-gradient-to-r from-yellow-500 to-orange-500 rounded-xl flex items-center justify-center shadow-lg">
                            <i class="fas fa-clock text-white text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-2xl font-bold text-slate-800"><?php echo e($fonds->where('statut', 'en_attente')->count()); ?>

                        </p>
                        <p class="text-sm text-slate-500">En attente</p>
                    </div>
                </div>
            </div>

            <div
                class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div
                            class="w-12 h-12 bg-gradient-to-r from-purple-500 to-pink-500 rounded-xl flex items-center justify-center shadow-lg">
                            <i class="fas fa-money-bill text-white text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-2xl font-bold text-slate-800">
                            <?php echo e(number_format($fonds->where('statut', 'validee')->sum('montant'))); ?></p>
                        <p class="text-sm text-slate-500">Total validé (XOF)</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Liste des transactions -->
        <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
            <div class="p-6 border-b border-slate-200">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <h2 class="text-xl font-bold text-slate-800 flex items-center">
                        <i class="fas fa-list text-purple-600 mr-2"></i>
                        Liste des Transactions (<?php echo e($fonds->total()); ?>)
                    </h2>
                    <div class="flex items-center space-x-2">
                        <select id="perPage"
                            class="px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors text-sm">
                            <option value="20" <?php echo e(request('per_page') == 20 ? 'selected' : ''); ?>>20 par page</option>
                            <option value="50" <?php echo e(request('per_page') == 50 ? 'selected' : ''); ?>>50 par page</option>
                            <option value="100" <?php echo e(request('per_page') == 100 ? 'selected' : ''); ?>>100 par page
                            </option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="p-6">
                <?php if($fonds->count() > 0): ?>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="bg-slate-50 border-b border-slate-200">
                                    <th class="text-left py-3 px-4 font-medium text-slate-600">N° Transaction</th>
                                    <th class="text-left py-3 px-4 font-medium text-slate-600">Date</th>
                                    <th class="text-left py-3 px-4 font-medium text-slate-600">Donateur</th>
                                    <th class="text-left py-3 px-4 font-medium text-slate-600">Type</th>
                                    <th class="text-left py-3 px-4 font-medium text-slate-600">Montant</th>
                                    <th class="text-left py-3 px-4 font-medium text-slate-600">Mode</th>
                                    <th class="text-left py-3 px-4 font-medium text-slate-600">Statut</th>
                                    <th class="text-left py-3 px-4 font-medium text-slate-600">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $fonds; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $fond): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr class="border-b border-slate-100 hover:bg-slate-50/50 transition-colors">
                                        <td class="py-4 px-4">
                                            <div class="font-medium text-slate-900"><?php echo e($fond->numero_transaction); ?></div>
                                            <?php if($fond->est_recurrente): ?>
                                                <div class="text-xs text-purple-600"><i
                                                        class="fas fa-sync-alt mr-1"></i>Récurrente</div>
                                            <?php endif; ?>
                                        </td>
                                        <td class="py-4 px-4">
                                            <div class="text-slate-900"><?php echo e($fond->date_transaction->format('d/m/Y')); ?>

                                            </div>
                                            <?php if($fond->heure_transaction): ?>
                                                <div class="text-xs text-slate-500">
                                                    <?php echo e($fond->heure_transaction->format('H:i')); ?></div>
                                            <?php endif; ?>
                                        </td>
                                        <td class="py-4 px-4">
                                            <?php if($fond->est_anonyme): ?>
                                                <span class="text-slate-500 italic">Donateur anonyme</span>
                                            <?php elseif($fond->donateur): ?>
                                                <div class="font-medium text-slate-900"><?php echo e($fond->donateur->nom); ?>

                                                    <?php echo e($fond->donateur->prenom); ?></div>
                                                <div class="text-xs text-slate-500"><?php echo e($fond->donateur->email); ?></div>
                                            <?php else: ?>
                                                <span class="text-slate-700"><?php echo e($fond->nom_donateur_anonyme); ?></span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="py-4 px-4">
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            <?php if($fond->type_transaction == 'dime'): ?> bg-blue-100 text-blue-800
                                            <?php elseif(str_contains($fond->type_transaction, 'offrande')): ?> bg-green-100 text-green-800
                                            <?php elseif(str_contains($fond->type_transaction, 'don')): ?> bg-purple-100 text-purple-800
                                            <?php else: ?> bg-gray-100 text-gray-800 <?php endif; ?>">
                                                <?php echo e($fond->type_transaction_libelle ?? ucfirst(str_replace('_', ' ', $fond->type_transaction))); ?>

                                            </span>
                                            <?php if($fond->est_flechee): ?>
                                                <div class="text-xs text-orange-600 mt-1"><i
                                                        class="fas fa-arrow-right mr-1"></i>Fléchée</div>
                                            <?php endif; ?>
                                        </td>
                                        <td class="py-4 px-4">
                                            <div class="font-bold text-slate-900">
                                                <?php echo e(number_format($fond->montant, 0, ',', ' ')); ?> <?php echo e($fond->devise); ?></div>
                                            <?php if($fond->type_transaction == 'don_materiel' && $fond->valeur_estimee): ?>
                                                <div class="text-xs text-slate-500">Valeur estimée</div>
                                            <?php endif; ?>
                                        </td>
                                        <td class="py-4 px-4">
                                            <span class="text-slate-600">
                                                <?php if($fond->mode_paiement == 'especes'): ?>
                                                    <i class="fas fa-money-bill text-green-600 mr-1"></i>Espèces
                                                <?php elseif($fond->mode_paiement == 'mobile_money'): ?>
                                                    <i class="fas fa-mobile-alt text-blue-600 mr-1"></i>Mobile Money
                                                <?php elseif($fond->mode_paiement == 'virement'): ?>
                                                    <i class="fas fa-university text-purple-600 mr-1"></i>Virement
                                                <?php elseif($fond->mode_paiement == 'cheque'): ?>
                                                    <i class="fas fa-check text-orange-600 mr-1"></i>Chèque
                                                <?php else: ?>
                                                    <i class="fas fa-gift text-pink-600 mr-1"></i>Nature
                                                <?php endif; ?>
                                            </span>
                                        </td>
                                        <td class="py-4 px-4">
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            <?php if($fond->statut == 'validee'): ?> bg-green-100 text-green-800
                                            <?php elseif($fond->statut == 'en_attente'): ?> bg-yellow-100 text-yellow-800
                                            <?php elseif($fond->statut == 'annulee'): ?> bg-red-100 text-red-800
                                            <?php elseif($fond->statut == 'remboursee'): ?> bg-purple-100 text-purple-800 <?php endif; ?>">
                                                <?php echo e($fond->statut_libelle ?? ucfirst($fond->statut)); ?>

                                            </span>
                                            <?php if($fond->recu_emis): ?>
                                                <div class="text-xs text-green-600 mt-1"><i
                                                        class="fas fa-receipt mr-1"></i>Reçu émis</div>
                                            <?php endif; ?>
                                        </td>
                                        <td class="py-4 px-4">
                                            <div class="flex items-center space-x-2">
                                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('fonds.read')): ?>
                                                    <a href="<?php echo e(route('private.fonds.show', $fond)); ?>"
                                                        class="inline-flex items-center justify-center w-8 h-8 text-cyan-600 bg-cyan-100 rounded-lg hover:bg-cyan-200 transition-colors"
                                                        title="Voir">
                                                        <i class="fas fa-eye text-sm"></i>
                                                    </a>
                                                <?php endif; ?>

                                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('fonds.update')): ?>
                                                    <?php if($fond->peutEtreModifiee()): ?>
                                                        <a href="<?php echo e(route('private.fonds.edit', $fond)); ?>"
                                                            class="inline-flex items-center justify-center w-8 h-8 text-yellow-600 bg-yellow-100 rounded-lg hover:bg-yellow-200 transition-colors"
                                                            title="Modifier">
                                                            <i class="fas fa-edit text-sm"></i>
                                                        </a>
                                                    <?php endif; ?>
                                                <?php endif; ?>

                                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('fonds.validate')): ?>
                                                    <?php if($fond->peutEtreValidee()): ?>
                                                        <button type="button"
                                                            onclick="validateTransaction('<?php echo e($fond->id); ?>')"
                                                            class="inline-flex items-center justify-center w-8 h-8 text-green-600 bg-green-100 rounded-lg hover:bg-green-200 transition-colors"
                                                            title="Valider">
                                                            <i class="fas fa-check text-sm"></i>
                                                        </button>
                                                    <?php endif; ?>
                                                <?php endif; ?>

                                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('fonds.cancel')): ?>
                                                    <?php if($fond->peutEtreAnnulee()): ?>
                                                        <button type="button"
                                                            onclick="openCancelModal('<?php echo e($fond->id); ?>')"
                                                            class="inline-flex items-center justify-center w-8 h-8 text-red-600 bg-red-100 rounded-lg hover:bg-red-200 transition-colors"
                                                            title="Annuler">
                                                            <i class="fas fa-times text-sm"></i>
                                                        </button>
                                                    <?php endif; ?>
                                                <?php endif; ?>

                                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('fonds.generate-receipt')): ?>
                                                    <?php if($fond->peutGenererRecu()): ?>
                                                        <button type="button"
                                                            onclick="generateReceipt('<?php echo e($fond->id); ?>')"
                                                            class="inline-flex items-center justify-center w-8 h-8 text-purple-600 bg-purple-100 rounded-lg hover:bg-purple-200 transition-colors"
                                                            title="Générer reçu">
                                                            <i class="fas fa-receipt text-sm"></i>
                                                        </button>
                                                    <?php endif; ?>
                                                <?php endif; ?>

                                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('fonds.delete')): ?>
                                                    <?php if($fond->peutEtreModifiee()): ?>
                                                        <button type="button"
                                                            onclick="deleteTransaction('<?php echo e($fond->id); ?>')"
                                                            class="inline-flex items-center justify-center w-8 h-8 text-red-600 bg-red-100 rounded-lg hover:bg-red-200 transition-colors"
                                                            title="Supprimer">
                                                            <i class="fas fa-trash text-sm"></i>
                                                        </button>
                                                    <?php endif; ?>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div
                        class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mt-6 pt-6 border-t border-slate-200">
                        <div class="text-sm text-slate-700">
                            Affichage de <span class="font-medium"><?php echo e($fonds->firstItem()); ?></span> à <span
                                class="font-medium"><?php echo e($fonds->lastItem()); ?></span>
                            sur <span class="font-medium"><?php echo e($fonds->total()); ?></span> résultats
                        </div>
                        <div>
                            <?php echo e($fonds->appends(request()->query())->links()); ?>

                        </div>
                    </div>
                <?php else: ?>
                    <div class="text-center py-12">
                        <div class="w-24 h-24 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-receipt text-3xl text-slate-400"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-slate-900 mb-2">Aucune transaction trouvée</h3>
                        <p class="text-slate-500 mb-6">
                            <?php if(request()->hasAny(['search', 'statut', 'type_transaction'])): ?>
                                Aucune transaction ne correspond à vos critères de recherche.
                            <?php else: ?>
                                Commencez par créer votre première transaction.
                            <?php endif; ?>
                        </p>
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('fonds.create')): ?>
                            <a href="<?php echo e(route('private.fonds.create')); ?>"
                                class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-medium rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow-md hover:shadow-lg">
                                <i class="fas fa-plus mr-2"></i> Créer une transaction
                            </a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Modal validation -->
    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('fonds.validate')): ?>
        <div id="validateModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-2xl shadow-xl max-w-md w-full">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-slate-900 mb-4">Valider la transaction</h3>
                    <form id="validateForm">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" id="validate_transaction_id">
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-slate-700 mb-2">Notes de validation
                                (optionnel)</label>
                            <textarea id="validation_notes" rows="3"
                                class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors resize-none"
                                placeholder="Notes de validation..."></textarea>
                        </div>
                    </form>
                </div>
                <div class="flex items-center justify-end space-x-3 p-6 border-t border-slate-200">
                    <button type="button" onclick="closeValidateModal()"
                        class="px-4 py-2 text-slate-700 bg-slate-100 rounded-xl hover:bg-slate-200 transition-colors">
                        Annuler
                    </button>
                    <button type="button" onclick="confirmValidation()"
                        class="px-4 py-2 bg-green-600 text-white rounded-xl hover:bg-green-700 transition-colors">
                        Valider la transaction
                    </button>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Modal annulation -->
    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('fonds.cancel')): ?>
        <div id="cancelModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-2xl shadow-xl max-w-md w-full">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-slate-900 mb-4">Annuler la transaction</h3>
                    <form id="cancelForm">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" id="cancel_transaction_id">
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-slate-700 mb-2">Motif d'annulation <span
                                    class="text-red-500">*</span></label>
                            <textarea id="cancel_reason" required rows="3"
                                class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors resize-none"
                                placeholder="Motif de l'annulation..."></textarea>
                        </div>
                    </form>
                </div>
                <div class="flex items-center justify-end space-x-3 p-6 border-t border-slate-200">
                    <button type="button" onclick="closeCancelModal()"
                        class="px-4 py-2 text-slate-700 bg-slate-100 rounded-xl hover:bg-slate-200 transition-colors">
                        Annuler
                    </button>
                    <button type="button" onclick="confirmCancellation()"
                        class="px-4 py-2 bg-red-600 text-white rounded-xl hover:bg-red-700 transition-colors">
                        Annuler la transaction
                    </button>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <?php $__env->startPush('scripts'); ?>
        <script>
            // Gestion du nombre d'éléments par page
            document.getElementById('perPage').addEventListener('change', function() {
                const url = new URL(window.location.href);
                url.searchParams.set('per_page', this.value);
                window.location.href = url.toString();
            });

            // Modal validation
            function validateTransaction(transactionId) {
                document.getElementById('validate_transaction_id').value = transactionId;
                document.getElementById('validateModal').classList.remove('hidden');
            }

            function closeValidateModal() {
                document.getElementById('validateModal').classList.add('hidden');
                document.getElementById('validateForm').reset();
            }

            function confirmValidation() {
                const transactionId = document.getElementById('validate_transaction_id').value;
                const notes = document.getElementById('validation_notes').value;

                fetch(`<?php echo e(route('private.fonds.validate.strict', ':fond')); ?>`.replace(':fond', transactionId), {
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

            // Modal annulation
            function openCancelModal(transactionId) {
                document.getElementById('cancel_transaction_id').value = transactionId;
                document.getElementById('cancelModal').classList.remove('hidden');
            }

            function closeCancelModal() {
                document.getElementById('cancelModal').classList.add('hidden');
                document.getElementById('cancelForm').reset();
            }

            function confirmCancellation() {
                const transactionId = document.getElementById('cancel_transaction_id').value;
                const reason = document.getElementById('cancel_reason').value;

                if (!reason.trim()) {
                    alert('Veuillez saisir un motif d\'annulation');
                    return;
                }

                fetch(`<?php echo e(route('private.fonds.cancel.strict', ':fond')); ?>`.replace(':fond', transactionId), {
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
            function generateReceipt(transactionId) {
                if (confirm('Générer un reçu fiscal pour cette transaction ?')) {
                    fetch(`<?php echo e(route('private.fonds.receipt.strict', ':fond')); ?>`.replace(':fond', transactionId), {
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

            // Suppression
            function deleteTransaction(transactionId) {
                if (confirm('Êtes-vous sûr de vouloir supprimer cette transaction ?')) {
                    fetch(`<?php echo e(route('private.fonds.destroy', ':fonds')); ?>`.replace(':fonds', transactionId), {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
                                'Accept': 'application/json'
                            }
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
            }

            // Fermer les modals en cliquant à l'extérieur
            document.getElementById('validateModal').addEventListener('click', function(e) {
                if (e.target === this) closeValidateModal();
            });

            document.getElementById('cancelModal').addEventListener('click', function(e) {
                if (e.target === this) closeCancelModal();
            });
        </script>
    <?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.private.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Dell\Desktop\MICRISERVICES\methodiste_belle_ville\resources\views/components/private/fonds/index.blade.php ENDPATH**/ ?>