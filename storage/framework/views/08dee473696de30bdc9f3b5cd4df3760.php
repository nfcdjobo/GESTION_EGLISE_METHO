<?php $__env->startSection('title', 'Corbeille des Interventions'); ?>

<?php $__env->startSection('content'); ?>
    <div class="space-y-8">
        <!-- Page Title & Breadcrumb -->
        <div class="mb-8">
            <div class="bg-white rounded-xl shadow-lg p-6 border border-slate-200">
                <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent">
                    Corbeille des Interventions</h1>
                <p class="text-slate-500 mt-1">Interventions supprimées - <?php echo e(\Carbon\Carbon::now()->format('l d F Y')); ?></p>
            </div>
        </div>

        <!-- Actions et navigation -->
        <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
            <div class="p-6 border-b border-slate-200">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <h2 class="text-xl font-bold text-slate-800 flex items-center">
                        <i class="fas fa-trash text-red-600 mr-2"></i>
                        Interventions Supprimées
                    </h2>
                    <div class="flex flex-wrap gap-2">
                        <a href="<?php echo e(route('private.interventions.index')); ?>"
                            class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-600 to-purple-600 text-white text-sm font-medium rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow-md hover:shadow-lg">
                            <i class="fas fa-arrow-left mr-2"></i> Retour aux Interventions
                        </a>
                        <?php if($interventions->count() > 0): ?>
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('interventions.restore')): ?>
                                <button type="button" onclick="restoreAll()"
                                    class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-green-600 to-emerald-600 text-white text-sm font-medium rounded-xl hover:from-green-700 hover:to-emerald-700 transition-all duration-200 shadow-md hover:shadow-lg">
                                    <i class="fas fa-undo mr-2"></i> Tout Restaurer
                                </button>
                            <?php endif; ?>

                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('interventions.delete')): ?>
                                <button type="button" onclick="deleteAllPermanently()"
                                    class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-red-600 to-pink-600 text-white text-sm font-medium rounded-xl hover:from-red-700 hover:to-pink-700 transition-all duration-200 shadow-md hover:shadow-lg">
                                    <i class="fas fa-trash-alt mr-2"></i> Vider la Corbeille
                                </button>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistiques de la corbeille -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <div
                class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-gradient-to-r from-red-500 to-pink-500 rounded-xl flex items-center justify-center shadow-lg">
                            <i class="fas fa-trash text-white text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-2xl font-bold text-slate-800"><?php echo e($interventions->total()); ?></p>
                        <p class="text-sm text-slate-500">Total supprimées</p>
                    </div>
                </div>
            </div>

            <div
                class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div
                            class="w-12 h-12 bg-gradient-to-r from-blue-500 to-cyan-500 rounded-xl flex items-center justify-center shadow-lg">
                            <i class="fas fa-microphone text-white text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-2xl font-bold text-slate-800">
                            <?php echo e($interventions->where('type_intervention', 'predication')->count()); ?></p>
                        <p class="text-sm text-slate-500">Prédications</p>
                    </div>
                </div>
            </div>

            <div
                class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div
                            class="w-12 h-12 bg-gradient-to-r from-yellow-500 to-orange-500 rounded-xl flex items-center justify-center shadow-lg">
                            <i class="fas fa-heart text-white text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-2xl font-bold text-slate-800">
                            <?php echo e($interventions->where('type_intervention', 'temoignage')->count()); ?></p>
                        <p class="text-sm text-slate-500">Témoignages</p>
                    </div>
                </div>
            </div>

            <div
                class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div
                            class="w-12 h-12 bg-gradient-to-r from-purple-500 to-indigo-500 rounded-xl flex items-center justify-center shadow-lg">
                            <i class="fas fa-calendar text-white text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-2xl font-bold text-slate-800">
                            <?php if($interventions->count() > 0): ?>
                                <?php echo e($interventions->min('deleted_at')->diffInDays()); ?>

                            <?php else: ?>
                                0
                            <?php endif; ?>
                        </p>
                        <p class="text-sm text-slate-500">Jours depuis la plus ancienne</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Liste des interventions supprimées -->
        <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
            <div class="p-6 border-b border-slate-200">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <h2 class="text-xl font-bold text-slate-800 flex items-center">
                        <i class="fas fa-list text-purple-600 mr-2"></i>
                        Liste des Interventions Supprimées (<?php echo e($interventions->total()); ?>)
                    </h2>
                    <?php if($interventions->count() > 0): ?>
                        <div class="flex items-center gap-2">
                            <input type="checkbox" id="selectAll"
                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                            <label for="selectAll" class="text-sm text-slate-700">Tout sélectionner</label>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <div class="p-6">
                <?php if($interventions->count() > 0): ?>
                    <div class="overflow-x-auto">
                        <table class="min-w-full">
                            <thead>
                                <tr class="border-b border-slate-200">
                                    <th class="px-4 py-3 text-left">
                                        <span class="sr-only">Sélection</span>
                                    </th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">
                                        Titre</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">
                                        Type</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">
                                        Intervenant</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">
                                        Événement</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">
                                        Supprimée le</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">
                                        Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200">
                                <?php $__currentLoopData = $interventions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $intervention): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr class="hover:bg-slate-50 transition-colors">
                                        <td class="px-4 py-4">
                                            <input type="checkbox" name="selected_interventions[]"
                                                value="<?php echo e($intervention->id); ?>"
                                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 intervention-checkbox">
                                        </td>
                                        <td class="px-4 py-4">
                                            <div>
                                                <div class="font-semibold text-slate-900"><?php echo e($intervention->titre); ?></div>
                                                <?php if($intervention->description): ?>
                                                    <div class="text-sm text-slate-500">
                                                        <?php echo e(Str::limit($intervention->description, 50)); ?></div>
                                                <?php endif; ?>
                                                <?php if($intervention->passage_biblique): ?>
                                                    <div class="text-xs text-blue-600 font-medium">
                                                        <?php echo e($intervention->passage_biblique); ?></div>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                        <td class="px-4 py-4">
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                <?php echo e($intervention->type_intervention_label); ?>

                                            </span>
                                        </td>
                                        <td class="px-4 py-4">
                                            <div class="flex items-center space-x-2">
                                                <div
                                                    class="w-8 h-8 bg-gradient-to-r from-gray-400 to-gray-500 rounded-full flex items-center justify-center text-white text-sm font-bold">
                                                    <?php echo e(strtoupper(substr($intervention->intervenant->nom, 0, 1))); ?>

                                                </div>
                                                <span
                                                    class="text-sm font-medium text-slate-900"><?php echo e($intervention->intervenant->nom); ?></span>
                                            </div>
                                        </td>
                                        <td class="px-4 py-4">
                                            <?php if($intervention->culte): ?>
                                                <div class="flex items-center text-sm text-gray-600">
                                                    <i class="fas fa-church text-gray-400 mr-2"></i>
                                                    <?php echo e($intervention->culte->nom); ?>

                                                </div>
                                            <?php elseif($intervention->reunion): ?>
                                                <div class="flex items-center text-sm text-gray-600">
                                                    <i class="fas fa-users text-gray-400 mr-2"></i>
                                                    <?php echo e($intervention->reunion->nom); ?>

                                                </div>
                                            <?php endif; ?>
                                        </td>
                                        <td class="px-4 py-4">
                                            <div class="text-sm">
                                                <div class="text-slate-900 font-medium">
                                                    <?php echo e($intervention->deleted_at->format('d/m/Y')); ?></div>
                                                <div class="text-slate-500"><?php echo e($intervention->deleted_at->format('H:i')); ?>

                                                </div>
                                                <div class="text-xs text-slate-400">
                                                    <?php echo e($intervention->deleted_at->diffForHumans()); ?></div>
                                            </div>
                                        </td>
                                        <td class="px-4 py-4">
                                            <div class="flex items-center space-x-2">
                                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('interventions.restore')): ?>
                                                <button type="button"
                                                    onclick="restoreIntervention('<?php echo e($intervention->id); ?>')"
                                                    class="inline-flex items-center justify-center w-8 h-8 text-green-600 bg-green-100 rounded-lg hover:bg-green-200 transition-colors"
                                                    title="Restaurer">
                                                    <i class="fas fa-undo text-sm"></i>
                                                </button>
                                                <?php endif; ?>
                                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('interventions.delete')): ?>
                                                <button type="button"
                                                    onclick="deleteInterventionPermanently('<?php echo e($intervention->id); ?>')"
                                                    class="inline-flex items-center justify-center w-8 h-8 text-red-600 bg-red-100 rounded-lg hover:bg-red-200 transition-colors"
                                                    title="Supprimer définitivement">
                                                    <i class="fas fa-trash-alt text-sm"></i>
                                                </button>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Actions en lot -->
                    <?php if($interventions->count() > 0): ?>
                        <div class="mt-6 p-4 bg-slate-50 rounded-xl border border-slate-200">
                            <div class="flex flex-wrap items-center gap-3">
                                <span class="text-sm font-medium text-slate-700">Actions sur la sélection:</span>
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('interventions.restore')): ?>
                                <button type="button" onclick="restoreSelected()"
                                    class="inline-flex items-center px-3 py-1.5 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition-colors">
                                    <i class="fas fa-undo mr-1"></i> Restaurer
                                </button>
                                <?php endif; ?>
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('interventions.delete')): ?>
                                <button type="button" onclick="deleteSelectedPermanently()"
                                    class="inline-flex items-center px-3 py-1.5 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700 transition-colors">
                                    <i class="fas fa-trash-alt mr-1"></i> Supprimer définitivement
                                </button>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Pagination -->
                    <div
                        class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mt-6 pt-6 border-t border-slate-200">
                        <div class="text-sm text-slate-700">
                            Affichage de <span class="font-medium"><?php echo e($interventions->firstItem()); ?></span> à <span
                                class="font-medium"><?php echo e($interventions->lastItem()); ?></span>
                            sur <span class="font-medium"><?php echo e($interventions->total()); ?></span> résultats
                        </div>
                        <div>
                            <?php echo e($interventions->links()); ?>

                        </div>
                    </div>
                <?php else: ?>
                    <div class="text-center py-12">
                        <div class="w-24 h-24 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-trash text-3xl text-slate-400"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-slate-900 mb-2">La corbeille est vide</h3>
                        <p class="text-slate-500 mb-6">
                            Aucune intervention supprimée. Les interventions supprimées apparaîtront ici et pourront être
                            restaurées.
                        </p>
                        <a href="<?php echo e(route('private.interventions.index')); ?>"
                            class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-medium rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow-md hover:shadow-lg">
                            <i class="fas fa-arrow-left mr-2"></i> Retour aux interventions
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Modal de confirmation de restauration -->
    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('interventions.restore')): ?>
    <div id="restoreModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-xl max-w-md w-full">
            <div class="p-6">
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mr-4">
                        <i class="fas fa-undo text-green-600 text-xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-slate-900">Confirmer la restauration</h3>
                </div>
                <p class="text-slate-600 mb-2" id="restoreMessage">Êtes-vous sûr de vouloir restaurer cette intervention ?
                </p>
            </div>
            <div class="flex items-center justify-end space-x-3 p-6 border-t border-slate-200">
                <button type="button" onclick="closeRestoreModal()"
                    class="px-4 py-2 text-slate-700 bg-slate-100 rounded-xl hover:bg-slate-200 transition-colors">
                    Annuler
                </button>
                <button type="button" id="confirmRestore"
                    class="px-4 py-2 bg-green-600 text-white rounded-xl hover:bg-green-700 transition-colors">
                    Restaurer
                </button>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Modal de confirmation de suppression définitive -->
    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('interventions.delete')): ?>
    <div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-xl max-w-md w-full">
            <div class="p-6">
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center mr-4">
                        <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-slate-900">Suppression définitive</h3>
                </div>
                <p class="text-slate-600 mb-2" id="deleteMessage">Êtes-vous sûr de vouloir supprimer définitivement cette
                    intervention ?</p>
                <p class="text-red-600 font-medium">Cette action est irréversible.</p>
            </div>
            <div class="flex items-center justify-end space-x-3 p-6 border-t border-slate-200">
                <button type="button" onclick="closeDeleteModal()"
                    class="px-4 py-2 text-slate-700 bg-slate-100 rounded-xl hover:bg-slate-200 transition-colors">
                    Annuler
                </button>
                <button type="button" id="confirmDelete"
                    class="px-4 py-2 bg-red-600 text-white rounded-xl hover:bg-red-700 transition-colors">
                    Supprimer définitivement
                </button>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <script>
        // Sélection multiple
        document.getElementById('selectAll').addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('.intervention-checkbox');
            checkboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
        });

        // Modal functions
        function showRestoreModal(message) {
            document.getElementById('restoreMessage').textContent = message;
            document.getElementById('restoreModal').classList.remove('hidden');
        }

        function closeRestoreModal() {
            document.getElementById('restoreModal').classList.add('hidden');
        }

        function showDeleteModal(message) {
            document.getElementById('deleteMessage').textContent = message;
            document.getElementById('deleteModal').classList.remove('hidden');
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.add('hidden');
        }

        // Restaurer une intervention
        function restoreIntervention(interventionId) {
            showRestoreModal('Êtes-vous sûr de vouloir restaurer cette intervention ?');
            document.getElementById('confirmRestore').onclick = function() {
                fetch(`<?php echo e(route('private.interventions.index')); ?>/${interventionId}/restore`, {
                        method: 'PATCH',
                        headers: {
                            'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        closeRestoreModal();
                        if (data.success) {
                            location.reload();
                        } else {
                            alert(data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Erreur:', error);
                        alert('Une erreur est survenue');
                        closeRestoreModal();
                    });
            };
        }

        // Supprimer définitivement une intervention
        function deleteInterventionPermanently(interventionId) {
            showDeleteModal('Êtes-vous sûr de vouloir supprimer définitivement cette intervention ?');
            document.getElementById('confirmDelete').onclick = function() {
                // Note: Il faudrait une route spéciale pour la suppression définitive
                fetch(`<?php echo e(route('private.interventions.index')); ?>/${interventionId}/force-delete`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        closeDeleteModal();
                        if (data.success) {
                            location.reload();
                        } else {
                            alert(data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Erreur:', error);
                        alert('Une erreur est survenue');
                        closeDeleteModal();
                    });
            };
        }

        // Actions en lot
        function restoreSelected() {
            const selected = Array.from(document.querySelectorAll('.intervention-checkbox:checked'))
                .map(cb => cb.value);

            if (selected.length === 0) {
                alert('Veuillez sélectionner au moins une intervention à restaurer');
                return;
            }

            showRestoreModal(`Êtes-vous sûr de vouloir restaurer ${selected.length} intervention(s) ?`);
            document.getElementById('confirmRestore').onclick = function() {
                // Logique pour restaurer plusieurs interventions
                Promise.all(selected.map(id =>
                        fetch(`<?php echo e(route('private.interventions.index')); ?>/${id}/restore`, {
                            method: 'PATCH',
                            headers: {
                                'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
                                'Content-Type': 'application/json',
                                'Accept': 'application/json'
                            }
                        })
                    ))
                    .then(responses => Promise.all(responses.map(r => r.json())))
                    .then(results => {
                        closeRestoreModal();
                        const success = results.filter(r => r.success).length;
                        const failed = results.length - success;

                        if (success > 0) {
                            location.reload();
                        } else {
                            alert(`Erreur: ${failed} intervention(s) n'ont pas pu être restaurées`);
                        }
                    })
                    .catch(error => {
                        console.error('Erreur:', error);
                        alert('Une erreur est survenue');
                        closeRestoreModal();
                    });
            };
        }

        function deleteSelectedPermanently() {
            const selected = Array.from(document.querySelectorAll('.intervention-checkbox:checked'))
                .map(cb => cb.value);

            if (selected.length === 0) {
                alert('Veuillez sélectionner au moins une intervention à supprimer');
                return;
            }

            showDeleteModal(`Êtes-vous sûr de vouloir supprimer définitivement ${selected.length} intervention(s) ?`);
            document.getElementById('confirmDelete').onclick = function() {
                // Logique pour supprimer définitivement plusieurs interventions
                Promise.all(selected.map(id =>
                        fetch(`<?php echo e(route('private.interventions.index')); ?>/${id}/force-delete`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
                                'Content-Type': 'application/json',
                                'Accept': 'application/json'
                            }
                        })
                    ))
                    .then(responses => Promise.all(responses.map(r => r.json())))
                    .then(results => {
                        closeDeleteModal();
                        const success = results.filter(r => r.success).length;
                        const failed = results.length - success;

                        if (success > 0) {
                            location.reload();
                        } else {
                            alert(`Erreur: ${failed} intervention(s) n'ont pas pu être supprimées`);
                        }
                    })
                    .catch(error => {
                        console.error('Erreur:', error);
                        alert('Une erreur est survenue');
                        closeDeleteModal();
                    });
            };
        }

        function restoreAll() {
            if (confirm('Êtes-vous sûr de vouloir restaurer toutes les interventions de la corbeille ?')) {
                // Logique pour restaurer toutes les interventions
                window.location.href = '<?php echo e(route('private.interventions.index')); ?>';
            }
        }

        function deleteAllPermanently() {
            if (confirm('Êtes-vous sûr de vouloir vider complètement la corbeille ? Cette action est irréversible.')) {
                // Logique pour vider la corbeille
                window.location.href = '<?php echo e(route('private.interventions.index')); ?>';
            }
        }

        // Close modals when clicking outside
        document.getElementById('restoreModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeRestoreModal();
            }
        });

        document.getElementById('deleteModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeDeleteModal();
            }
        });
    </script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.private.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Dell\Desktop\MICRISERVICES\methodiste_belle_ville\resources\views/components/private/interventions/trash.blade.php ENDPATH**/ ?>