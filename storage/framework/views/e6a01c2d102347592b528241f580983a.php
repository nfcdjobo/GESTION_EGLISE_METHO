<?php $__env->startSection('title', 'Détails du Passage - ' . $passageMoisson->categorie_libelle); ?>

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
                <a href="<?php echo e(route('private.moissons.passages.index', $moisson)); ?>" class="hover:text-blue-600 transition-colors">
                    Passages
                </a>
                <i class="fas fa-chevron-right text-xs"></i>
                <span class="text-slate-800 font-medium"><?php echo e($passageMoisson->categorie_libelle); ?></span>
            </div>

            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent">
                        <?php echo e($passageMoisson->categorie_libelle); ?>

                    </h1>
                    <p class="text-slate-500 mt-1">
                        Détails et suivi du passage pour la moisson "<?php echo e($moisson->theme); ?>"
                    </p>
                </div>

                <div class="flex gap-2">
                    <button onclick="ajouterMontant()"
                        class="inline-flex items-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-xl hover:bg-green-700 transition-colors">
                        <i class="fas fa-plus mr-2"></i> Ajouter montant
                    </button>
                    <a href="<?php echo e(route('private.moissons.passages.edit', [$moisson, $passageMoisson])); ?>"
                        class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-xl hover:bg-blue-700 transition-colors">
                        <i class="fas fa-edit mr-2"></i> Modifier
                    </a>
                    <button onclick="toggleStatus()"
                        class="inline-flex items-center px-4 py-2 <?php echo e($passageMoisson->status ? 'bg-red-600 hover:bg-red-700' : 'bg-green-600 hover:bg-green-700'); ?> text-white text-sm font-medium rounded-xl transition-colors">
                        <i class="fas fa-toggle-<?php echo e($passageMoisson->status ? 'off' : 'on'); ?> mr-2"></i>
                        <?php echo e($passageMoisson->status ? 'Désactiver' : 'Activer'); ?>

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

        <!-- Indicateurs de performance du passage -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-600">Objectif</p>
                        <p class="text-2xl font-bold text-slate-900"><?php echo e(number_format($passageMoisson->cible, 0, ',', ' ')); ?></p>
                        <p class="text-xs text-slate-500">FCFA</p>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-bullseye text-blue-600"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-600">Collecté</p>
                        <p class="text-2xl font-bold text-green-600"><?php echo e(number_format($passageMoisson->montant_solde, 0, ',', ' ')); ?></p>
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
                            <?php echo e($passageMoisson->reste > 0 ? 'Reste' : 'Supplément'); ?>

                        </p>
                        <p class="text-2xl font-bold <?php echo e($passageMoisson->reste > 0 ? 'text-red-600' : 'text-purple-600'); ?>">
                            <?php echo e(number_format($passageMoisson->reste > 0 ? $passageMoisson->reste : $passageMoisson->montant_supplementaire, 0, ',', ' ')); ?>

                        </p>
                        <p class="text-xs text-slate-500">FCFA</p>
                    </div>
                    <div class="w-12 h-12 <?php echo e($passageMoisson->reste > 0 ? 'bg-red-100' : 'bg-purple-100'); ?> rounded-xl flex items-center justify-center">
                        <i class="fas fa-<?php echo e($passageMoisson->reste > 0 ? 'exclamation-triangle' : 'trophy'); ?> <?php echo e($passageMoisson->reste > 0 ? 'text-red-600' : 'text-purple-600'); ?>"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-600">Progression</p>
                        <p class="text-2xl font-bold
                            <?php if($passageMoisson->pourcentage_realise >= 100): ?> text-green-600
                            <?php elseif($passageMoisson->pourcentage_realise >= 70): ?> text-blue-600
                            <?php elseif($passageMoisson->pourcentage_realise >= 50): ?> text-yellow-600
                            <?php else: ?> text-red-600
                            <?php endif; ?>"><?php echo e($passageMoisson->pourcentage_realise); ?>%</p>
                        <p class="text-xs text-slate-500">
                            <?php
                                $pourcentage = $passageMoisson->pourcentage_realise;
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
                    <span class="text-sm font-medium text-slate-700">Progression de l'objectif</span>
                    <span class="text-sm font-medium text-slate-900"><?php echo e($passageMoisson->pourcentage_realise); ?>%</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-4">
                    <div class="h-4 rounded-full transition-all duration-300
                        <?php if($passageMoisson->pourcentage_realise >= 100): ?> bg-green-500
                        <?php elseif($passageMoisson->pourcentage_realise >= 70): ?> bg-blue-500
                        <?php elseif($passageMoisson->pourcentage_realise >= 50): ?> bg-yellow-500
                        <?php else: ?> bg-red-500
                        <?php endif; ?>"
                        style="width: <?php echo e(min($passageMoisson->pourcentage_realise, 100)); ?>%">
                    </div>
                </div>
                <div class="flex justify-between text-xs text-slate-500">
                    <span>0 FCFA</span>
                    <span><?php echo e(number_format($passageMoisson->cible, 0, ',', ' ')); ?> FCFA</span>
                </div>
            </div>
        </div>

        <!-- Détails du passage -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Informations principales -->
            

    <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20">
                <div class="p-6 border-b border-slate-200">
                    <h3 class="text-lg font-bold text-slate-800 flex items-center">
                        <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                        Informations du passage
                    </h3>
                </div>

    <!-- Content -->
    <div class="p-6">
        <dl class="flex flex-wrap gap-y-6">
            <div class="w-full sm:w-1/2 pr-4">
                <dt class="text-sm font-medium text-slate-500">Catégorie</dt>
                <dd class="mt-1 text-base text-slate-900 font-semibold">
                    <?php echo e($passageMoisson->categorie_libelle); ?>

                </dd>
            </div>

            <?php if($passageMoisson->est_classe_communautaire && $passageMoisson->classe): ?>
                <div class="w-full sm:w-1/2 pr-4">
                    <dt class="text-sm font-medium text-slate-500">Classe</dt>
                    <dd class="mt-1 text-base text-slate-900 font-semibold">
                        <?php echo e($passageMoisson->classe->nom); ?>

                    </dd>
                </div>
            <?php endif; ?>

            <div class="w-full sm:w-1/2 pr-4">
                <dt class="text-sm font-medium text-slate-500">Statut</dt>
                <dd class="mt-1">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold
                        <?php echo e($passageMoisson->status ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'); ?>">
                        <?php echo e($passageMoisson->status ? 'Actif' : 'Inactif'); ?>

                    </span>
                </dd>
            </div>

            <div class="w-full sm:w-1/2 pr-4">
                <dt class="text-sm font-medium text-slate-500">Collecteur responsable</dt>
                <dd class="mt-1 text-base text-slate-900 font-semibold">
                    <?php echo e($passageMoisson->collecteur?->nom_complet ?? 'Non défini'); ?>

                </dd>
            </div>

            <div class="w-full sm:w-1/2 pr-4">
                <dt class="text-sm font-medium text-slate-500">Date de collecte</dt>
                <dd class="mt-1 text-base text-slate-900">
                    <?php echo e($passageMoisson->collecte_le ? $passageMoisson->collecte_le->format('d/m/Y à H:i') : 'Non définie'); ?>

                </dd>
            </div>

            <div class="w-full sm:w-1/2 pr-4">
                <dt class="text-sm font-medium text-slate-500">Créé par</dt>
                <dd class="mt-1 text-base text-slate-900 font-semibold">
                    <?php echo e($passageMoisson->createur?->nom_complet ?? 'Inconnu'); ?>

                </dd>
            </div>

            <div class="w-full sm:w-1/2 pr-4">
                <dt class="text-sm font-medium text-slate-500">Date de création</dt>
                <dd class="mt-1 text-base text-slate-900">
                    <?php echo e($passageMoisson->created_at->format('d/m/Y à H:i')); ?>

                </dd>
            </div>

            <?php if($passageMoisson->updated_at != $passageMoisson->created_at): ?>
                <div class="w-full sm:w-1/2 pr-4">
                    <dt class="text-sm font-medium text-slate-500">Dernière modification</dt>
                    <dd class="mt-1 text-base text-slate-900">
                        <?php echo e($passageMoisson->updated_at->format('d/m/Y à H:i')); ?>

                    </dd>
                </div>
            <?php endif; ?>
        </dl>
    </div>
</div>


            <!-- Montants et calculs -->
            <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20">
                <div class="p-6 border-b border-slate-200">
                    <h3 class="text-lg font-bold text-slate-800 flex items-center">
                        <i class="fas fa-calculator text-green-600 mr-2"></i>
                        Détail des montants
                    </h3>
                </div>
                <div class="p-6">
                    <div class="space-y-6">
                        <!-- Objectif -->
                        <div class="flex items-center justify-between p-4 bg-blue-50 rounded-xl">
                            <div>
                                <p class="text-sm font-medium text-blue-800">Objectif fixé</p>
                                <p class="text-xs text-blue-600">Montant à atteindre</p>
                            </div>
                            <p class="text-lg font-bold text-blue-900">
                                <?php echo e(number_format($passageMoisson->cible, 0, ',', ' ')); ?> FCFA
                            </p>
                        </div>

                        <!-- Montant collecté -->
                        <div class="flex items-center justify-between p-4 bg-green-50 rounded-xl">
                            <div>
                                <p class="text-sm font-medium text-green-800">Montant collecté</p>
                                <p class="text-xs text-green-600">Fonds déjà rassemblés</p>
                            </div>
                            <p class="text-lg font-bold text-green-900">
                                <?php echo e(number_format($passageMoisson->montant_solde, 0, ',', ' ')); ?> FCFA
                            </p>
                        </div>

                        <?php if($passageMoisson->reste > 0): ?>
                            <!-- Reste à collecter -->
                            <div class="flex items-center justify-between p-4 bg-red-50 rounded-xl">
                                <div>
                                    <p class="text-sm font-medium text-red-800">Reste à collecter</p>
                                    <p class="text-xs text-red-600">Montant manquant</p>
                                </div>
                                <p class="text-lg font-bold text-red-900">
                                    <?php echo e(number_format($passageMoisson->reste, 0, ',', ' ')); ?> FCFA
                                </p>
                            </div>
                        <?php endif; ?>

                        <?php if($passageMoisson->montant_supplementaire > 0): ?>
                            <!-- Montant supplémentaire -->
                            <div class="flex items-center justify-between p-4 bg-purple-50 rounded-xl">
                                <div>
                                    <p class="text-sm font-medium text-purple-800">Montant supplémentaire</p>
                                    <p class="text-xs text-purple-600">Dépassement d'objectif</p>
                                </div>
                                <p class="text-lg font-bold text-purple-900">
                                    +<?php echo e(number_format($passageMoisson->montant_supplementaire, 0, ',', ' ')); ?> FCFA
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
                        Historique des modifications
                    </h3>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <?php $__currentLoopData = array_reverse($historique); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $edit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="flex items-start gap-4 p-4 <?php echo e($index % 2 === 0 ? 'bg-slate-50' : 'bg-white'); ?> rounded-lg">
                                <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
                                    <i class="fas fa-<?php echo e($edit['action'] === 'creation' ? 'plus' : ($edit['action'] === 'modification' ? 'edit' : 'coins')); ?> text-blue-600 text-xs"></i>
                                </div>
                                <div class="flex-1">
                                    <div class="flex items-center justify-between">
                                        <h4 class="text-sm font-medium text-slate-900">
                                            <?php switch($edit['action']):
                                                case ('creation'): ?>
                                                    Création du passage
                                                    <?php break; ?>
                                                <?php case ('modification'): ?>
                                                    Modification
                                                    <?php break; ?>
                                                <?php case ('ajout_montant'): ?>
                                                    Ajout de montant
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

                                    <?php if(isset($edit['details'])): ?>
                                        <div class="mt-2 text-sm text-slate-600">
                                            <?php if(isset($edit['details']['ancien_montant'])): ?>
                                                <p>Ancien montant: <?php echo e(number_format($edit['details']['ancien_montant'], 0, ',', ' ')); ?> FCFA</p>
                                            <?php endif; ?>
                                            <?php if(isset($edit['details']['nouveau_montant'])): ?>
                                                <p>Nouveau montant: <?php echo e(number_format($edit['details']['nouveau_montant'], 0, ',', ' ')); ?> FCFA</p>
                                            <?php endif; ?>
                                            <?php if(isset($edit['details']['montant_ajoute'])): ?>
                                                <p>Montant ajouté: +<?php echo e(number_format($edit['details']['montant_ajoute'], 0, ',', ' ')); ?> FCFA</p>
                                            <?php endif; ?>
                                            <?php if(isset($edit['details']['notes']) && $edit['details']['notes']): ?>
                                                <p class="italic"><?php echo e($edit['details']['notes']); ?></p>
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

    <!-- Modal pour ajouter un montant -->
    <div id="modal-ajouter-montant" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-2xl shadow-xl max-w-md w-full">
                <div class="p-6 border-b border-slate-200">
                    <h3 class="text-lg font-bold text-slate-800">Ajouter un montant</h3>
                    <p class="text-sm text-slate-600 mt-1">Ajouter un montant collecté pour ce passage</p>
                </div>
                <form id="form-ajouter-montant" class="p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Montant (FCFA) *</label>
                        <input type="number" name="montant" id="montant-input" required min="0.01" step="0.01"
                            class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Ex: 50000">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Notes (optionnel)</label>
                        <textarea name="notes" rows="3"
                            class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Notes sur cette collecte..."></textarea>
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

    <?php $__env->startPush('scripts'); ?>
        <script>
            // Modal pour ajouter un montant
            function ajouterMontant() {
                document.getElementById('modal-ajouter-montant').classList.remove('hidden');
                document.getElementById('montant-input').focus();
            }

            function fermerModal() {
                document.getElementById('modal-ajouter-montant').classList.add('hidden');
                document.getElementById('form-ajouter-montant').reset();
            }

            // Soumission du formulaire d'ajout de montant
            document.getElementById('form-ajouter-montant').addEventListener('submit', function(e) {
                e.preventDefault();

                const formData = new FormData(this);
                const donnees = {
                    montant: parseFloat(formData.get('montant')),
                    notes: formData.get('notes')
                };

                fetch(`<?php echo e(route('private.moissons.passages.ajouter-montant', [$moisson, $passageMoisson])); ?>`, {
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
                        showNotification('Montant ajouté avec succès', 'success');
                        setTimeout(() => location.reload(), 1000);
                    } else {
                        showNotification(data.message || 'Erreur lors de l\'ajout du montant', 'error');
                    }
                    fermerModal();
                })
                .catch(error => {
                    console.error('Erreur:', error);
                    showNotification('Erreur lors de l\'ajout du montant', 'error');
                    fermerModal();
                });
            });

            // Toggle status
            function toggleStatus() {
                const currentStatus = <?php echo e($passageMoisson->status ? 'true' : 'false'); ?>;
                const action = currentStatus ? 'désactiver' : 'activer';

                if (!confirm(`Êtes-vous sûr de vouloir ${action} ce passage ?`)) {
                    return;
                }

                fetch(`<?php echo e(route('private.moissons.passages.toggle-status', [$moisson, $passageMoisson])); ?>`, {
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

            // Fermer modal avec ESC
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    fermerModal();
                }
            });

            // Animation des cartes au chargement
            document.addEventListener('DOMContentLoaded', function() {
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

<?php echo $__env->make('layouts.private.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Dell\Desktop\MICRISERVICES\methodiste_belle_ville\resources\views/components/private/moissons/passages/show.blade.php ENDPATH**/ ?>