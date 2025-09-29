<?php $__env->startSection('title', 'Gestion des Souscriptions'); ?>

<?php $__env->startSection('content'); ?>

    <div class="space-y-8">
        <!-- Page Title -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent">
                Gestion des Souscriptions
            </h1>
            <p class="text-slate-500 mt-1">
                Suivi et gestion des souscriptions FIMECO - <?php echo e(\Carbon\Carbon::now()->locale('fr')->format('l d F Y')); ?>

            </p>
        </div>

        <!-- Statistiques rapides -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-6">
            <div class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-cyan-500 rounded-xl flex items-center justify-center shadow-lg">
                            <i class="fas fa-handshake text-white text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-2xl font-bold text-slate-800"><?php echo e($subscriptions->total()); ?></p>
                        <p class="text-sm text-slate-500">Total Souscriptions</p>
                    </div>
                </div>
            </div>

            <div class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-gradient-to-r from-green-500 to-emerald-500 rounded-xl flex items-center justify-center shadow-lg">
                            <i class="fas fa-check-circle text-white text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-2xl font-bold text-slate-800"><?php echo e($subscriptions->where('statut', 'completement_payee')->count()); ?></p>
                        <p class="text-sm text-slate-500">Complètement payées</p>
                    </div>
                </div>
            </div>

            <div class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-gradient-to-r from-yellow-500 to-orange-500 rounded-xl flex items-center justify-center shadow-lg">
                            <i class="fas fa-hourglass-half text-white text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-2xl font-bold text-slate-800"><?php echo e($subscriptions->where('statut', 'partiellement_payee')->count()); ?></p>
                        <p class="text-sm text-slate-500">Partiellement payées</p>
                    </div>
                </div>
            </div>

            <div class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-gradient-to-r from-red-500 to-pink-500 rounded-xl flex items-center justify-center shadow-lg">
                            <i class="fas fa-exclamation-triangle text-white text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-2xl font-bold text-slate-800"><?php echo e($subscriptions->filter(function($s) { return $s->en_retard; })->count()); ?></p>
                        <p class="text-sm text-slate-500">En retard</p>
                    </div>
                </div>
            </div>

            <div class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-gradient-to-r from-purple-500 to-indigo-500 rounded-xl flex items-center justify-center shadow-lg">
                            <i class="fas fa-coins text-white text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-2xl font-bold text-slate-800"><?php echo e(number_format($subscriptions->sum('montant_paye'), 0, ',', ' ')); ?></p>
                        <p class="text-sm text-slate-500">Total payé (FCFA)</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filtres et Actions -->
        <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
            <div class="p-6 border-b border-slate-200">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <h2 class="text-xl font-bold text-slate-800 flex items-center">
                        <i class="fas fa-filter text-blue-600 mr-2"></i>
                        Filtres et Actions
                    </h2>
                    <div class="flex flex-wrap gap-2">
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('fimecos.read')): ?>
                            <a href="<?php echo e(route('private.fimecos.index')); ?>"
                                class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-600 to-purple-600 text-white text-sm font-medium rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow-md hover:shadow-lg">
                                <i class="fas fa-list mr-2"></i> Liste FIMECOs
                            </a>
                        <?php endif; ?>
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('subscriptions.dashboard')): ?>
                            <a href="<?php echo e(route('private.subscriptions.dashboard')); ?>"
                                class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-green-600 to-emerald-600 text-white text-sm font-medium rounded-xl hover:from-green-700 hover:to-emerald-700 transition-all duration-200 shadow-md hover:shadow-lg">
                                <i class="fas fa-chart-line mr-2"></i> Tableau de bord
                            </a>
                        <?php endif; ?>
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('subscriptions.export')): ?>
                            <button onclick="exporterSouscriptions()"
                                class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-cyan-600 to-blue-600 text-white text-sm font-medium rounded-xl hover:from-cyan-700 hover:to-blue-700 transition-all duration-200 shadow-md hover:shadow-lg">
                                <i class="fas fa-download mr-2"></i> Exporter
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('subscriptions.search')): ?>
                <div class="p-6">
                    <form method="GET" action="<?php echo e(route('private.subscriptions.index')); ?>"
                        class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-4">
                        <div class="lg:col-span-2">
                            <label class="block text-sm font-medium text-slate-700 mb-2">Recherche</label>
                            <div class="relative">
                                <input type="text" name="search" value="<?php echo e(request('search')); ?>"
                                    placeholder="Nom souscripteur, FIMECO..."
                                    class="w-full pl-10 pr-4 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-slate-400"></i>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Statut</label>
                            <select name="statut"
                                class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                <option value="">Tous les statuts</option>
                                <option value="inactive" <?php echo e(request('statut') === 'inactive' ? 'selected' : ''); ?>>Inactive</option>
                                <option value="partiellement_payee" <?php echo e(request('statut') === 'partiellement_payee' ? 'selected' : ''); ?>>Partiellement payée</option>
                                <option value="completement_payee" <?php echo e(request('statut') === 'completement_payee' ? 'selected' : ''); ?>>Complètement payée</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">FIMECO</label>
                            <select name="fimeco_id"
                                class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                <option value="">Tous les FIMECOs</option>
                                <?php $__currentLoopData = \App\Models\Fimeco::orderBy('nom')->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $fimeco): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($fimeco->id); ?>" <?php echo e(request('fimeco_id') === $fimeco->id ? 'selected' : ''); ?>>
                                        <?php echo e(Str::limit($fimeco->nom, 30)); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">En retard</label>
                            <select name="en_retard"
                                class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                <option value="">Toutes</option>
                                <option value="1" <?php echo e(request('en_retard') === '1' ? 'selected' : ''); ?>>En retard uniquement</option>
                                <option value="0" <?php echo e(request('en_retard') === '0' ? 'selected' : ''); ?>>À jour uniquement</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Date souscription</label>
                            <input type="date" name="date_souscription_debut" value="<?php echo e(request('date_souscription_debut')); ?>"
                                class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        </div>

                        <div class="lg:col-span-6 flex gap-2 pt-4">
                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-xl hover:bg-blue-700 transition-colors">
                                <i class="fas fa-search mr-2"></i> Filtrer
                            </button>
                            <a href="<?php echo e(route('private.subscriptions.index')); ?>"
                                class="inline-flex items-center px-4 py-2 bg-slate-600 text-white text-sm font-medium rounded-xl hover:bg-slate-700 transition-colors">
                                <i class="fas fa-refresh mr-2"></i> Réinitialiser
                            </a>
                        </div>
                    </form>
                </div>
            <?php endif; ?>
        </div>

        <!-- Liste des Souscriptions -->
        <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
            <div class="p-6 border-b border-slate-200">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <h2 class="text-xl font-bold text-slate-800 flex items-center">
                        <i class="fas fa-list text-purple-600 mr-2"></i>
                        Liste des Souscriptions (<?php echo e($subscriptions->total()); ?>)
                    </h2>
                    <div class="flex items-center space-x-2">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                            <?php echo e($subscriptions->total()); ?> résultats
                        </span>
                        <?php if($subscriptions->hasPages()): ?>
                            <span class="text-sm text-slate-600">
                                Page <?php echo e($subscriptions->currentPage()); ?> sur <?php echo e($subscriptions->lastPage()); ?>

                            </span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="p-6">
                <?php if($subscriptions->count() > 0): ?>
                    <div class="overflow-x-auto">
                        <table class="min-w-full">
                            <thead>
                                <tr class="border-b border-slate-200">
                                    <th class="px-4 py-3 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">
                                        <a href="<?php echo e(request()->fullUrlWithQuery(['sort_by' => 'created_at', 'sort_direction' => request('sort_direction') === 'asc' ? 'desc' : 'asc'])); ?>"
                                            class="group inline-flex items-center hover:text-blue-600 transition-colors">
                                            Souscripteur
                                            <span class="ml-2 flex-none rounded text-slate-400 group-hover:text-blue-500">
                                                <i class="fas fa-sort"></i>
                                            </span>
                                        </a>
                                    </th>
                                    <th class="px-4 py-3 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">FIMECO</th>
                                    <th class="px-4 py-3 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">
                                        <a href="<?php echo e(request()->fullUrlWithQuery(['sort_by' => 'date_souscription', 'sort_direction' => request('sort_direction') === 'asc' ? 'desc' : 'asc'])); ?>"
                                            class="group inline-flex items-center hover:text-blue-600 transition-colors">
                                            Dates
                                            <span class="ml-2 flex-none rounded text-slate-400 group-hover:text-blue-500">
                                                <i class="fas fa-sort"></i>
                                            </span>
                                        </a>
                                    </th>
                                    <th class="px-4 py-3 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Montants</th>
                                    <th class="px-4 py-3 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Progression</th>
                                    <th class="px-4 py-3 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Paiements</th>
                                    <th class="px-4 py-3 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Statut</th>
                                    <th class="px-4 py-3 text-right text-xs font-bold text-slate-700 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200">
                                <?php $__currentLoopData = $subscriptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subscription): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr class="hover:bg-gradient-to-r hover:from-blue-50 hover:to-indigo-50 transition-all duration-200">
                                        <td class="px-4 py-4">
                                            <div class="flex items-center">
                                                <?php if($subscription->souscripteur?->photo_profil): ?>
                                                    <img class="h-10 w-10 rounded-full object-cover"
                                                         src="<?php echo e(asset('storage/' . $subscription->souscripteur->photo_profil)); ?>"
                                                         alt="<?php echo e($subscription->souscripteur->nom); ?>">
                                                <?php else: ?>
                                                    <div class="h-10 w-10 rounded-full bg-gradient-to-r from-blue-500 to-purple-500 flex items-center justify-center">
                                                        <span class="text-sm font-medium text-white">
                                                            <?php echo e(strtoupper(substr($subscription->souscripteur->nom ?? 'U', 0, 1))); ?>

                                                        </span>
                                                    </div>
                                                <?php endif; ?>
                                                <div class="ml-4">
                                                    <div class="text-sm font-bold text-slate-900">
                                                        <?php echo e($subscription->souscripteur?->nom ?? 'Utilisateur supprimé'); ?>

                                                    </div>
                                                    <?php if($subscription->souscripteur?->email): ?>
                                                        <div class="text-xs text-slate-500">
                                                            <?php echo e($subscription->souscripteur->email); ?>

                                                        </div>
                                                    <?php endif; ?>
                                                    <?php if($subscription->souscripteur?->telephone_1): ?>
                                                        <div class="text-xs text-blue-600">
                                                            <i class="fas fa-phone mr-1"></i>
                                                            <?php echo e($subscription->souscripteur->telephone_1); ?>

                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-4 py-4">
                                            <div>
                                                <div class="text-sm font-medium text-slate-900">
                                                    <?php echo e(Str::limit($subscription->fimeco->nom, 25)); ?>

                                                </div>
                                                <div class="text-xs text-slate-500">
                                                    Progression: <?php echo e(number_format($subscription->fimeco->progression, 1)); ?>%
                                                </div>
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium
                                                    <?php if($subscription->fimeco->statut === 'active'): ?> bg-green-100 text-green-800
                                                    <?php elseif($subscription->fimeco->statut === 'cloturee'): ?> bg-gray-100 text-gray-800
                                                    <?php else: ?> bg-red-100 text-red-800 <?php endif; ?>">
                                                    <?php echo e(ucfirst($subscription->fimeco->statut)); ?>

                                                </span>
                                            </div>
                                        </td>
                                        <td class="px-4 py-4">
                                            <div class="space-y-1">
                                                <div class="text-sm text-slate-900">
                                                    <i class="fas fa-calendar-plus text-green-600 mr-1"></i>
                                                    <?php echo e($subscription->date_souscription->format('d/m/Y')); ?>

                                                </div>
                                                <?php if($subscription->date_echeance): ?>
                                                    <div class="text-sm <?php echo e($subscription->en_retard ? 'text-red-600' : 'text-slate-600'); ?>">
                                                        <i class="fas fa-calendar-times <?php echo e($subscription->en_retard ? 'text-red-600' : 'text-orange-600'); ?> mr-1"></i>
                                                        <?php echo e($subscription->date_echeance->format('d/m/Y')); ?>

                                                    </div>
                                                    <?php if($subscription->en_retard): ?>
                                                        <div class="text-xs text-red-600 font-medium">
                                                            <i class="fas fa-exclamation-triangle mr-1"></i>
                                                            Retard: <?php echo e($subscription->jours_retard); ?> jours
                                                        </div>
                                                    <?php elseif($subscription->jours_restants <= 7 && $subscription->statut !== 'completement_payee'): ?>
                                                        <div class="text-xs text-orange-600 font-medium">
                                                            <i class="fas fa-clock mr-1"></i>
                                                            <?php echo e($subscription->jours_restants); ?> jours restants
                                                        </div>
                                                    <?php endif; ?>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                        <td class="px-4 py-4">
                                            <div class="space-y-1">
                                                <div class="text-sm font-medium text-slate-900">
                                                    Souscrit: <?php echo e(number_format($subscription->montant_souscrit, 0, ',', ' ')); ?> FCFA
                                                </div>
                                                <div class="text-sm text-green-600">
                                                    Payé: <?php echo e(number_format($subscription->montant_paye, 0, ',', ' ')); ?> FCFA
                                                </div>
                                                <?php if($subscription->reste_a_payer > 0): ?>
                                                    <div class="text-sm text-orange-600">
                                                        Reste: <?php echo e(number_format($subscription->reste_a_payer, 0, ',', ' ')); ?> FCFA
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                        <td class="px-4 py-4">
                                            <div class="space-y-2">
                                                <div class="flex items-center space-x-2">
                                                    <div class="flex-1 bg-slate-200 rounded-full h-2">
                                                        <div class="h-2 rounded-full <?php echo e($subscription->progression >= 100 ? 'bg-green-500' : ($subscription->progression >= 75 ? 'bg-blue-500' : ($subscription->progression >= 50 ? 'bg-yellow-500' : 'bg-red-500'))); ?>"
                                                             style="width: <?php echo e(min($subscription->progression, 100)); ?>%"></div>
                                                    </div>
                                                    <span class="text-xs font-medium text-slate-700">
                                                        <?php echo e(number_format($subscription->progression, 1)); ?>%
                                                    </span>
                                                </div>
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                    <?php if($subscription->statut_global === 'objectif_atteint'): ?> bg-green-100 text-green-800
                                                    <?php elseif($subscription->statut_global === 'presque_atteint'): ?> bg-blue-100 text-blue-800
                                                    <?php elseif($subscription->statut_global === 'en_cours'): ?> bg-yellow-100 text-yellow-800
                                                    <?php else: ?> bg-gray-100 text-gray-800 <?php endif; ?>">
                                                    <?php echo e(ucfirst(str_replace('_', ' ', $subscription->statut_global))); ?>

                                                </span>
                                            </div>
                                        </td>
                                        <td class="px-4 py-4">
                                            <div class="space-y-1">
                                                <div class="text-sm font-medium text-slate-900">
                                                    <?php echo e($subscription->payments->count()); ?> paiement(s)
                                                </div>
                                                <?php if($subscription->payments->where('statut', 'en_attente')->count() > 0): ?>
                                                    <div class="text-xs text-orange-600">
                                                        <i class="fas fa-clock mr-1"></i>
                                                        <?php echo e($subscription->payments->where('statut', 'en_attente')->count()); ?> en attente
                                                    </div>
                                                <?php endif; ?>
                                                <?php if($subscription->payments->where('statut', 'valide')->count() > 0): ?>
                                                    <div class="text-xs text-green-600">
                                                        <i class="fas fa-check mr-1"></i>
                                                        <?php echo e($subscription->payments->where('statut', 'valide')->count()); ?> validé(s)
                                                    </div>
                                                <?php endif; ?>
                                                <?php if($subscription->dernierPaiement): ?>
                                                    <div class="text-xs text-slate-500">
                                                        Dernier: <?php echo e($subscription->dernierPaiement->date_paiement->format('d/m/Y')); ?>

                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                        <td class="px-4 py-4">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                <?php if($subscription->statut === 'completement_payee'): ?> bg-green-100 text-green-800
                                                <?php elseif($subscription->statut === 'partiellement_payee'): ?> bg-yellow-100 text-yellow-800
                                                <?php else: ?> bg-gray-100 text-gray-800 <?php endif; ?>">
                                                <?php if($subscription->statut === 'completement_payee'): ?>
                                                    <i class="fas fa-check-circle mr-1"></i> Complète
                                                <?php elseif($subscription->statut === 'partiellement_payee'): ?>
                                                    <i class="fas fa-hourglass-half mr-1"></i> Partielle
                                                <?php else: ?>
                                                    <i class="fas fa-pause-circle mr-1"></i> Inactive
                                                <?php endif; ?>
                                            </span>
                                            <?php if($subscription->necessiteAttention()): ?>
                                                <div class="mt-1">
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                        <i class="fas fa-exclamation-triangle mr-1"></i> Attention
                                                    </span>
                                                </div>
                                            <?php endif; ?>
                                        </td>
                                        <td class="px-4 py-4">
                                            <div class="flex items-center justify-end space-x-2">
                                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('subscriptions.read')): ?>
                                                    <a href="<?php echo e(route('private.subscriptions.show', $subscription)); ?>"
                                                        class="inline-flex items-center justify-center w-8 h-8 text-cyan-600 bg-cyan-100 rounded-lg hover:bg-cyan-200 transition-colors"
                                                        title="Voir détails">
                                                        <i class="fas fa-eye text-sm"></i>
                                                    </a>
                                                <?php endif; ?>
                                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('subscriptions.update')): ?>
                                                    <a href="<?php echo e(route('private.subscriptions.edit', $subscription)); ?>"
                                                        class="inline-flex items-center justify-center w-8 h-8 text-yellow-600 bg-yellow-100 rounded-lg hover:bg-yellow-200 transition-colors"
                                                        title="Modifier">
                                                        <i class="fas fa-edit text-sm"></i>
                                                    </a>
                                                <?php endif; ?>
                                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('subscriptions.payment')): ?>
                                                    <?php if($subscription->statut !== 'completement_payee' && $subscription->reste_a_payer > 0): ?>
                                                        <button onclick="ouvrirModalPaiement('<?php echo e($subscription->id); ?>')"
                                                            class="inline-flex items-center justify-center w-8 h-8 text-green-600 bg-green-100 rounded-lg hover:bg-green-200 transition-colors"
                                                            title="Ajouter paiement">
                                                            <i class="fas fa-credit-card text-sm"></i>
                                                        </button>
                                                    <?php endif; ?>
                                                <?php endif; ?>
                                                <?php if($subscription->statut === 'partiellement_payee'): ?>
                                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('subscriptions.deactivate')): ?>
                                                        <button onclick="desactiverSouscription('<?php echo e($subscription->id); ?>')"
                                                            class="inline-flex items-center justify-center w-8 h-8 text-orange-600 bg-orange-100 rounded-lg hover:bg-orange-200 transition-colors"
                                                            title="Désactiver">
                                                            <i class="fas fa-pause text-sm"></i>
                                                        </button>
                                                    <?php endif; ?>
                                                <?php elseif($subscription->statut === 'inactive'): ?>
                                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('subscriptions.reactivate')): ?>
                                                        <button onclick="reactiverSouscription('<?php echo e($subscription->id); ?>')"
                                                            class="inline-flex items-center justify-center w-8 h-8 text-green-600 bg-green-100 rounded-lg hover:bg-green-200 transition-colors"
                                                            title="Réactiver">
                                                            <i class="fas fa-play text-sm"></i>
                                                        </button>
                                                    <?php endif; ?>
                                                <?php endif; ?>
                                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('subscriptions.delete')): ?>
                                                    <button onclick="deleteSouscription('<?php echo e($subscription->id); ?>')"
                                                        class="inline-flex items-center justify-center w-8 h-8 text-red-600 bg-red-100 rounded-lg hover:bg-red-200 transition-colors"
                                                        title="Supprimer">
                                                        <i class="fas fa-trash text-sm"></i>
                                                    </button>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mt-6 pt-6 border-t border-slate-200">
                        <div class="text-sm text-slate-700">
                            Affichage de <span class="font-medium"><?php echo e($subscriptions->firstItem()); ?></span> à <span
                                class="font-medium"><?php echo e($subscriptions->lastItem()); ?></span> sur <span
                                class="font-medium"><?php echo e($subscriptions->total()); ?></span> résultats
                        </div>
                        <div>
                            <?php echo e($subscriptions->appends(request()->query())->links()); ?>

                        </div>
                    </div>
                <?php else: ?>
                    <div class="text-center py-12">
                        <div class="w-24 h-24 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-handshake text-3xl text-slate-400"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-slate-900 mb-2">Aucune souscription trouvée</h3>
                        <p class="text-slate-500 mb-6">
                            <?php if(request()->hasAny(['search', 'statut', 'fimeco_id', 'en_retard', 'date_souscription_debut'])): ?>
                                Aucune souscription ne correspond à vos critères de recherche.
                            <?php else: ?>
                                Commencez par créer votre première souscription.
                            <?php endif; ?>
                        </p>
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('fimecos.read')): ?>
                            <a href="<?php echo e(route('private.fimecos.index')); ?>"
                                class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-medium rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow-md hover:shadow-lg">
                                <i class="fas fa-list mr-2"></i> Liste FIMECOs
                            </a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Modal d'export -->
    <div id="exportModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-2xl shadow-2xl p-8 max-w-md w-full mx-4 transform transition-all">
            <div class="text-center mb-6">
                <div class="w-16 h-16 bg-gradient-to-r from-blue-500 to-purple-500 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-download text-white text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold text-slate-800">Choisir le format d'export</h3>
                <p class="text-slate-500 text-sm mt-1">Sélectionnez le format qui vous convient</p>
            </div>

            <div class="space-y-3 mb-6">
                <label class="format-option flex items-center p-4 border-2 border-slate-200 rounded-xl cursor-pointer hover:border-blue-300 hover:bg-slate-50 transition-all duration-200" onclick="selectFormat('excel')">
                    <input type="radio" name="format" value="excel" class="sr-only">
                    <div class="flex-shrink-0 w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mr-4">
                        <i class="fas fa-file-excel text-green-600 text-lg"></i>
                    </div>
                    <div class="flex-1">
                        <div class="font-semibold text-slate-800">Excel</div>
                        <div class="text-sm text-slate-500">Tableau avec calculs et graphiques</div>
                    </div>
                    <div class="w-5 h-5 border-2 border-slate-300 rounded-full flex items-center justify-center">
                        <div class="w-2.5 h-2.5 bg-blue-500 rounded-full opacity-0 transition-opacity"></div>
                    </div>
                </label>

                <label class="format-option flex items-center p-4 border-2 border-slate-200 rounded-xl cursor-pointer hover:border-blue-300 hover:bg-slate-50 transition-all duration-200" onclick="selectFormat('csv')">
                    <input type="radio" name="format" value="csv" class="sr-only">
                    <div class="flex-shrink-0 w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-4">
                        <i class="fas fa-file-csv text-blue-600 text-lg"></i>
                    </div>
                    <div class="flex-1">
                        <div class="font-semibold text-slate-800">CSV</div>
                        <div class="text-sm text-slate-500">Données brutes compatibles</div>
                    </div>
                    <div class="w-5 h-5 border-2 border-slate-300 rounded-full flex items-center justify-center">
                        <div class="w-2.5 h-2.5 bg-blue-500 rounded-full opacity-0 transition-opacity"></div>
                    </div>
                </label>

                <label class="format-option flex items-center p-4 border-2 border-slate-200 rounded-xl cursor-pointer hover:border-blue-300 hover:bg-slate-50 transition-all duration-200" onclick="selectFormat('pdf')">
                    <input type="radio" name="format" value="pdf" class="sr-only">
                    <div class="flex-shrink-0 w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center mr-4">
                        <i class="fas fa-file-pdf text-red-600 text-lg"></i>
                    </div>
                    <div class="flex-1">
                        <div class="font-semibold text-slate-800">PDF</div>
                        <div class="text-sm text-slate-500">Rapport détaillé imprimable</div>
                    </div>
                    <div class="w-5 h-5 border-2 border-slate-300 rounded-full flex items-center justify-center">
                        <div class="w-2.5 h-2.5 bg-blue-500 rounded-full opacity-0 transition-opacity"></div>
                    </div>
                </label>
            </div>

            <div class="flex gap-3">
                <button onclick="closeExportModal()" class="flex-1 px-4 py-2 bg-slate-200 text-slate-700 rounded-xl hover:bg-slate-300 transition-colors font-medium">
                    Annuler
                </button>
                <button onclick="confirmExport()" class="flex-1 px-4 py-2 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow-md hover:shadow-lg font-medium">
                    Exporter
                </button>
            </div>
        </div>
    </div>

    <!-- Modal de paiement -->
    <div id="paiementModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-2xl shadow-2xl p-8 max-w-2xl w-full mx-4 transform transition-all max-h-[90vh] overflow-y-auto">
            <div class="text-center mb-6">
                <div class="w-16 h-16 bg-gradient-to-r from-green-500 to-emerald-500 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-credit-card text-white text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold text-slate-800">Enregistrer un paiement</h3>
                <p class="text-slate-500 text-sm mt-1" id="paiementModalSubtitle">Saisissez les détails du paiement</p>
            </div>

            <form id="paiementForm" class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Montant (FCFA) *</label>
                        <input type="number" id="montant" name="montant" step="0.01" min="1" required
                            class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        <div class="text-xs text-slate-500 mt-1" id="montantInfo">Montant maximum: -</div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Type de paiement *</label>
                        <select id="typePaiement" name="type_paiement" required
                            class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            <option value="">Sélectionnez un type</option>
                            <option value="especes">Espèces</option>
                            <option value="cheque">Chèque</option>
                            <option value="virement">Virement bancaire</option>
                            <option value="carte">Carte bancaire</option>
                            <option value="mobile_money">Mobile Money</option>
                        </select>
                    </div>

                    <div id="referenceField" class="hidden">
                        <label class="block text-sm font-medium text-slate-700 mb-2">Référence *</label>
                        <input type="text" id="reference" name="reference_paiement" maxlength="100"
                            class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                            placeholder="Numéro de chèque, référence virement...">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Date de paiement *</label>
                        <input type="datetime-local" id="datePaiement" name="date_paiement" required
                            class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Commentaire</label>
                    <textarea id="commentaire" name="commentaire" rows="3" maxlength="1000"
                        class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                        placeholder="Informations complémentaires sur le paiement..."></textarea>
                </div>

                <div class="flex gap-3">
                    <button type="button" onclick="closePaiementModal()" class="flex-1 px-4 py-3 bg-slate-200 text-slate-700 rounded-xl hover:bg-slate-300 transition-colors font-medium">
                        Annuler
                    </button>
                    <button type="submit" class="flex-1 px-4 py-3 bg-gradient-to-r from-green-600 to-emerald-600 text-white rounded-xl hover:from-green-700 hover:to-emerald-700 transition-all duration-200 shadow-md hover:shadow-lg font-medium">
                        <i class="fas fa-save mr-2"></i> Enregistrer le paiement
                    </button>
                </div>
            </form>
        </div>
    </div>

    <?php $__env->startPush('scripts'); ?>
        <script>
            let selectedFormat = '';
            let currentSubscriptionId = '';

            // Export functions
            function exporterSouscriptions() {
                document.getElementById('exportModal').classList.remove('hidden');
                selectedFormat = '';
                document.querySelectorAll('input[name="format"]').forEach(radio => {
                    radio.checked = false;
                });
                document.querySelectorAll('.format-option').forEach(option => {
                    option.classList.remove('ring-2', 'ring-blue-500', 'bg-blue-50');
                });
            }

            function closeExportModal() {
                document.getElementById('exportModal').classList.add('hidden');
            }

            function selectFormat(format) {
                selectedFormat = format;
                document.querySelectorAll('.format-option').forEach(option => {
                    option.classList.remove('ring-2', 'ring-blue-500', 'bg-blue-50');
                });
                event.currentTarget.classList.add('ring-2', 'ring-blue-500', 'bg-blue-50');
                document.querySelector(`input[value="${format}"]`).checked = true;
            }

            function confirmExport() {
                if (selectedFormat) {
                    window.location.href = `<?php echo e(route('private.subscriptions.export')); ?>?format=${selectedFormat}`;
                    closeExportModal();
                } else {
                    alert('Veuillez sélectionner un format d\'export');
                }
            }

            // Payment modal functions
            function ouvrirModalPaiement(subscriptionId) {
                currentSubscriptionId = subscriptionId;

                // Fetch subscription details
                fetch(`<?php echo e(route('private.subscriptions.show', ':id')); ?>`.replace(':id', subscriptionId), {
                    headers: {
                        'Accept': 'application/json',
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const subscription = data.data.subscription;
                        document.getElementById('paiementModalSubtitle').textContent =
                            `Souscription: ${subscription.fimeco.nom} - ${subscription.souscripteur.nom}`;
                        document.getElementById('montantInfo').textContent =
                            `Montant maximum: ${new Intl.NumberFormat('fr-FR').format(subscription.reste_a_payer)} FCFA`;
                        document.getElementById('montant').max = subscription.reste_a_payer;

                        // Set current date/time
                        const now = new Date();
                        now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
                        document.getElementById('datePaiement').value = now.toISOString().slice(0, 16);

                        document.getElementById('paiementModal').classList.remove('hidden');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Erreur lors du chargement des détails de la souscription');
                });
            }

            function closePaiementModal() {
                document.getElementById('paiementModal').classList.add('hidden');
                document.getElementById('paiementForm').reset();
                document.getElementById('referenceField').classList.add('hidden');
                currentSubscriptionId = '';
            }

            // Handle type paiement change to show/hide reference field
            document.getElementById('typePaiement').addEventListener('change', function() {
                const referenceField = document.getElementById('referenceField');
                const typesWithReference = ['cheque', 'virement', 'carte'];

                if (typesWithReference.includes(this.value)) {
                    referenceField.classList.remove('hidden');
                    document.getElementById('reference').required = true;
                } else {
                    referenceField.classList.add('hidden');
                    document.getElementById('reference').required = false;
                }
            });

            // Handle payment form submission
            document.getElementById('paiementForm').addEventListener('submit', function(e) {
                e.preventDefault();

                if (!currentSubscriptionId) {
                    alert('Erreur: ID de souscription manquant');
                    return;
                }

                const formData = new FormData(this);
                const data = Object.fromEntries(formData);

                fetch(`<?php echo e(route('private.subscriptions.effectuer-paiement', ':id')); ?>`.replace(':id', currentSubscriptionId), {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': "<?php echo e(csrf_token()); ?>",
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(data)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Paiement enregistré avec succès');
                        location.reload();
                    } else {
                        alert(data.message || 'Erreur lors de l\'enregistrement du paiement');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Erreur lors de l\'enregistrement du paiement');
                });
            });

            // Action functions
            function deleteSouscription(subscriptionId) {
                if (confirm('Êtes-vous sûr de vouloir supprimer cette souscription ?')) {
                    const url = "<?php echo e(route('private.subscriptions.destroy', ':id')); ?>".replace(':id', subscriptionId);

                    fetch(url, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': "<?php echo e(csrf_token()); ?>",
                            'Accept': 'application/json',
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            location.reload();
                        } else {
                            alert(data.message || 'Erreur lors de la suppression');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Erreur lors de la suppression');
                    });
                }
            }

            function desactiverSouscription(subscriptionId) {
                if (confirm('Désactiver cette souscription ?')) {
                    const url = "<?php echo e(route('private.subscriptions.desactiver', ':id')); ?>".replace(':id', subscriptionId);

                    fetch(url, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': "<?php echo e(csrf_token()); ?>",
                            'Accept': 'application/json',
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            location.reload();
                        } else {
                            alert(data.message || 'Erreur lors de la désactivation');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Erreur lors de la désactivation');
                    });
                }
            }

            function reactiverSouscription(subscriptionId) {
                if (confirm('Réactiver cette souscription ?')) {
                    const url = "<?php echo e(route('private.subscriptions.reactiver', ':id')); ?>".replace(':id', subscriptionId);

                    fetch(url, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': "<?php echo e(csrf_token()); ?>",
                            'Accept': 'application/json',
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            location.reload();
                        } else {
                            alert(data.message || 'Erreur lors de la réactivation');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Erreur lors de la réactivation');
                    });
                }
            }

            // Close modals on backdrop click
            document.addEventListener('DOMContentLoaded', function() {
                document.getElementById('exportModal')?.addEventListener('click', function(event) {
                    if (event.target === this) {
                        closeExportModal();
                    }
                });

                document.getElementById('paiementModal')?.addEventListener('click', function(event) {
                    if (event.target === this) {
                        closePaiementModal();
                    }
                });

                // Animation des cartes au chargement
                const cards = document.querySelectorAll('.bg-white\\/80');
                cards.forEach((card, index) => {
                    card.style.opacity = '0';
                    // card.style.transform = 'translateY(20px)';
                    setTimeout(() => {
                        card.style.transition = 'all 0.5s ease';
                        card.style.opacity = '1';
                        // card.style.transform = 'translateY(0)';
                    }, index * 100);
                });
            });
        </script>
    <?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.private.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Dell\Desktop\MICRISERVICES\methodiste_belle_ville\resources\views/components/private/subscriptions/index.blade.php ENDPATH**/ ?>