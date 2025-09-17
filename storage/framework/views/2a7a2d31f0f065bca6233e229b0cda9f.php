<?php $__env->startSection('title', 'Détails de la Participation'); ?>

<?php $__env->startSection('content'); ?>
    <div class="space-y-8">
        <!-- Page Title & Breadcrumb -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent">
                        Participation de <?php echo e($participation->participant->nom); ?> <?php echo e($participation->participant->prenom); ?>

                    </h1>
                    <nav class="flex mt-2" aria-label="Breadcrumb">
                        <ol class="inline-flex items-center space-x-1 md:space-x-3">
                            <li class="inline-flex items-center">
                                <a href="<?php echo e(route('private.participantscultes.index')); ?>"
                                    class="inline-flex items-center text-sm font-medium text-slate-700 hover:text-blue-600 transition-colors">
                                    <i class="fas fa-users mr-2"></i>
                                    Participations
                                </a>
                            </li>
                            <li>
                                <div class="flex items-center">
                                    <i class="fas fa-chevron-right text-slate-400 mx-2"></i>
                                    <span class="text-sm font-medium text-slate-500"><?php echo e($participation->participant->nom); ?> <?php echo e($participation->participant->prenom); ?></span>
                                </div>
                            </li>
                        </ol>
                    </nav>
                </div>

                <!-- Actions rapides -->
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['participants-cultes.confirm-presence', 'participants-cultes.update'])): ?>
                <div class="flex items-center space-x-2">
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('participants-cultes.update')): ?>
                    <button type="button" onclick="editParticipation()"
                        class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-yellow-600 to-orange-600 text-white text-sm font-medium rounded-xl hover:from-yellow-700 hover:to-orange-700 transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-edit mr-2"></i> Modifier
                    </button>
                    <?php endif; ?>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('participants-cultes.confirm-presence')): ?>
                    <?php if(!$participation->presence_confirmee): ?>
                        <button type="button" onclick="confirmerPresence()"
                            class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-green-600 to-emerald-600 text-white text-sm font-medium rounded-xl hover:from-green-700 hover:to-emerald-700 transition-all duration-200 shadow-md hover:shadow-lg">
                            <i class="fas fa-check mr-2"></i> Confirmer Présence
                        </button>
                    <?php endif; ?>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Informations principales -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Informations du participant -->
                <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                    <div class="p-6 border-b border-slate-200">
                        <div class="flex items-center justify-between">
                            <h2 class="text-xl font-bold text-slate-800 flex items-center">
                                <i class="fas fa-user text-blue-600 mr-2"></i>
                                Informations du Participant
                            </h2>
                            <?php
                                $statutColors = [
                                    'present' => 'bg-green-100 text-green-800',
                                    'present_partiel' => 'bg-yellow-100 text-yellow-800',
                                    'en_retard' => 'bg-orange-100 text-orange-800',
                                    'parti_tot' => 'bg-red-100 text-red-800'
                                ];
                            ?>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium <?php echo e($statutColors[$participation->statut_presence] ?? 'bg-gray-100 text-gray-800'); ?>">
                                <?php echo e($participation->statut_presence_libelle); ?>

                            </span>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-4">
                                <div>
                                    <span class="text-sm font-medium text-slate-500">Nom complet</span>
                                    <p class="text-lg font-semibold text-slate-900"><?php echo e($participation->participant->nom); ?> <?php echo e($participation->participant->prenom); ?></p>
                                </div>
                                <?php if($participation->participant->email): ?>
                                    <div>
                                        <span class="text-sm font-medium text-slate-500">Email</span>
                                        <p class="text-lg font-semibold text-slate-900"><?php echo e($participation->participant->email); ?></p>
                                    </div>
                                <?php endif; ?>
                                <?php if($participation->participant->telephone_1): ?>
                                    <div>
                                        <span class="text-sm font-medium text-slate-500">Téléphone</span>
                                        <p class="text-lg font-semibold text-slate-900"><?php echo e($participation->participant->telephone_1); ?></p>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="space-y-4">
                                <div>
                                    <span class="text-sm font-medium text-slate-500">Statut membre</span>
                                    <p class="text-lg font-semibold text-slate-900"><?php echo e(ucfirst($participation->participant->statut_membre ?? 'Non défini')); ?></p>
                                </div>
                                <?php if($participation->participant->statut_bapteme): ?>
                                    <div>
                                        <span class="text-sm font-medium text-slate-500">Statut baptême</span>
                                        <p class="text-lg font-semibold text-slate-900"><?php echo e(ucfirst($participation->participant->statut_bapteme)); ?></p>
                                    </div>
                                <?php endif; ?>
                                <?php if($participation->participant->classe): ?>
                                    <div>
                                        <span class="text-sm font-medium text-slate-500">Classe</span>
                                        <p class="text-lg font-semibold text-slate-900"><?php echo e($participation->participant->classe->nom ?? 'Non assigné'); ?></p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Informations du culte -->
                <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                    <div class="p-6 border-b border-slate-200">
                        <h2 class="text-xl font-bold text-slate-800 flex items-center">
                            <i class="fas fa-church text-purple-600 mr-2"></i>
                            Informations du Culte
                        </h2>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-4">
                                <div>
                                    <span class="text-sm font-medium text-slate-500">Titre du culte</span>
                                    <p class="text-lg font-semibold text-slate-900"><?php echo e($participation->culte->titre); ?></p>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-slate-500">Type de culte</span>
                                    <p class="text-lg font-semibold text-slate-900"><?php echo e($participation->culte->type_culte_libelle); ?></p>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-slate-500">Lieu</span>
                                    <p class="text-lg font-semibold text-slate-900"><?php echo e($participation->culte->lieu); ?></p>
                                </div>
                            </div>
                            <div class="space-y-4">
                                <div>
                                    <span class="text-sm font-medium text-slate-500">Date du culte</span>
                                    <p class="text-lg font-semibold text-slate-900">
                                        <?php echo e(\Carbon\Carbon::parse($participation->culte->date_culte)->format('l d F Y')); ?>

                                    </p>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-slate-500">Horaires prévus</span>
                                    <p class="text-lg font-semibold text-slate-900">
                                        <?php echo e(\Carbon\Carbon::parse($participation->culte->heure_debut)->format('H:i')); ?>

                                        <?php if($participation->culte->heure_fin): ?>
                                            - <?php echo e(\Carbon\Carbon::parse($participation->culte->heure_fin)->format('H:i')); ?>

                                        <?php endif; ?>
                                    </p>
                                </div>
                                <?php if($participation->culte->pasteurPrincipal): ?>
                                    <div>
                                        <span class="text-sm font-medium text-slate-500">Pasteur principal</span>
                                        <p class="text-lg font-semibold text-slate-900"><?php echo e($participation->culte->pasteurPrincipal->nom); ?> <?php echo e($participation->culte->pasteurPrincipal->prenom); ?></p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="mt-6 pt-6 border-t border-slate-200">
                            <a href="<?php echo e(route('private.cultes.show', $participation->culte)); ?>"
                               class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-purple-600 to-pink-600 text-white text-sm font-medium rounded-xl hover:from-purple-700 hover:to-pink-700 transition-all duration-200">
                                <i class="fas fa-eye mr-2"></i> Voir le culte complet
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Détails de la participation -->
                <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                    <div class="p-6 border-b border-slate-200">
                        <h2 class="text-xl font-bold text-slate-800 flex items-center">
                            <i class="fas fa-clipboard-check text-green-600 mr-2"></i>
                            Détails de la Participation
                        </h2>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-4">
                                <div>
                                    <span class="text-sm font-medium text-slate-500">Type de participation</span>
                                    <p class="text-lg font-semibold text-slate-900"><?php echo e($participation->type_participation_libelle); ?></p>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-slate-500">Rôle dans le culte</span>
                                    <p class="text-lg font-semibold text-slate-900"><?php echo e($participation->role_culte_libelle); ?></p>
                                </div>
                                <?php if($participation->heure_arrivee): ?>
                                    <div>
                                        <span class="text-sm font-medium text-slate-500">Heure d'arrivée</span>
                                        <p class="text-lg font-semibold text-slate-900"><?php echo e(\Carbon\Carbon::parse($participation->heure_arrivee)->format('H:i')); ?></p>
                                    </div>
                                <?php endif; ?>
                                <?php if($participation->heure_depart): ?>
                                    <div>
                                        <span class="text-sm font-medium text-slate-500">Heure de départ</span>
                                        <p class="text-lg font-semibold text-slate-900"><?php echo e(\Carbon\Carbon::parse($participation->heure_depart)->format('H:i')); ?></p>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="space-y-4">
                                <?php if($participation->accompagnateur): ?>
                                    <div>
                                        <span class="text-sm font-medium text-slate-500">Accompagné par</span>
                                        <p class="text-lg font-semibold text-slate-900"><?php echo e($participation->accompagnateur->nom); ?> <?php echo e($participation->accompagnateur->prenom); ?></p>
                                    </div>
                                <?php endif; ?>
                                <div>
                                    <span class="text-sm font-medium text-slate-500">Enregistré par</span>
                                    <p class="text-lg font-semibold text-slate-900"><?php echo e($participation->enregistreur->nom ?? 'Système'); ?> <?php echo e($participation->enregistreur->prenom ?? ''); ?></p>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-slate-500">Enregistré le</span>
                                    <p class="text-lg font-semibold text-slate-900"><?php echo e($participation->created_at->format('d/m/Y H:i')); ?></p>
                                </div>
                                <?php if($participation->duree_participation): ?>
                                    <div>
                                        <span class="text-sm font-medium text-slate-500">Durée de participation</span>
                                        <p class="text-lg font-semibold text-slate-900"><?php echo e($participation->duree_participation); ?></p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Options spéciales -->
                        <div class="mt-6 pt-6 border-t border-slate-200">
                            <div class="flex flex-wrap gap-2">
                                <?php if($participation->premiere_visite): ?>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                        <i class="fas fa-star mr-1"></i> Première visite
                                    </span>
                                <?php endif; ?>
                                <?php if($participation->presence_confirmee): ?>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <i class="fas fa-check-circle mr-1"></i> Présence confirmée
                                    </span>
                                <?php endif; ?>
                                <?php if($participation->demande_contact_pastoral): ?>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        <i class="fas fa-praying-hands mr-1"></i> Contact pastoral demandé
                                    </span>
                                <?php endif; ?>
                                <?php if($participation->interesse_bapteme): ?>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-cyan-100 text-cyan-800">
                                        <i class="fas fa-water mr-1"></i> Intéressé par le baptême
                                    </span>
                                <?php endif; ?>
                                <?php if($participation->souhaite_devenir_membre): ?>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800">
                                        <i class="fas fa-heart mr-1"></i> Souhaite devenir membre
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Notes et commentaires -->
                <?php if($participation->notes_responsable || $participation->commentaires_participant): ?>
                    <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                        <div class="p-6 border-b border-slate-200">
                            <h2 class="text-xl font-bold text-slate-800 flex items-center">
                                <i class="fas fa-comment-alt text-cyan-600 mr-2"></i>
                                Notes et Commentaires
                            </h2>
                        </div>
                        <div class="p-6 space-y-6">
                            <?php if($participation->notes_responsable): ?>
                                <div>
                                    <h3 class="text-lg font-semibold text-slate-800 mb-3 flex items-center">
                                        <i class="fas fa-user-tie text-blue-600 mr-2"></i>
                                        Notes du responsable
                                    </h3>
                                    <div class="bg-blue-50 p-4 rounded-lg border border-blue-200">
                                        <p class="text-slate-700"><?php echo e($participation->notes_responsable); ?></p>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <?php if($participation->commentaires_participant): ?>
                                <div>
                                    <h3 class="text-lg font-semibold text-slate-800 mb-3 flex items-center">
                                        <i class="fas fa-user text-green-600 mr-2"></i>
                                        Commentaires du participant
                                    </h3>
                                    <div class="bg-green-50 p-4 rounded-lg border border-green-200">
                                        <p class="text-slate-700"><?php echo e($participation->commentaires_participant); ?></p>
                                    </div>
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
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('participants-cultes.confirm-presence')): ?>
                        <?php if(!$participation->presence_confirmee): ?>
                            <button type="button" onclick="confirmerPresence()"
                                class="w-full inline-flex items-center justify-center px-4 py-2 bg-gradient-to-r from-green-600 to-emerald-600 text-white text-sm font-medium rounded-xl hover:from-green-700 hover:to-emerald-700 transition-all duration-200">
                                <i class="fas fa-check mr-2"></i> Confirmer la présence
                            </button>
                        <?php endif; ?>
                        <?php endif; ?>
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('participants-cultes.update')): ?>
                        <button type="button" onclick="editParticipation()"
                            class="w-full inline-flex items-center justify-center px-4 py-2 bg-gradient-to-r from-blue-600 to-cyan-600 text-white text-sm font-medium rounded-xl hover:from-blue-700 hover:to-cyan-700 transition-all duration-200">
                            <i class="fas fa-edit mr-2"></i> Modifier la participation
                        </button>
                        <?php endif; ?>

                        <a href="<?php echo e(route('private.cultes.show', $participation->culte)); ?>"
                            class="w-full inline-flex items-center justify-center px-4 py-2 bg-gradient-to-r from-purple-600 to-pink-600 text-white text-sm font-medium rounded-xl hover:from-purple-700 hover:to-pink-700 transition-all duration-200">
                            <i class="fas fa-church mr-2"></i> Voir le culte
                        </a>
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('participants-cultes.delete')): ?>
                        <button type="button" onclick="deleteParticipation()"
                            class="w-full inline-flex items-center justify-center px-4 py-2 bg-gradient-to-r from-red-600 to-rose-600 text-white text-sm font-medium rounded-xl hover:from-red-700 hover:to-rose-700 transition-all duration-200">
                            <i class="fas fa-trash mr-2"></i> Supprimer
                        </button>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Informations de confirmation -->
                <?php if($participation->presence_confirmee && $participation->confirmateur): ?>
                    <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                        <div class="p-6 border-b border-slate-200">
                            <h2 class="text-xl font-bold text-slate-800 flex items-center">
                                <i class="fas fa-check-circle text-green-600 mr-2"></i>
                                Confirmation
                            </h2>
                        </div>
                        <div class="p-6 space-y-3 text-sm">
                            <div class="flex justify-between">
                                <span class="text-slate-500">Confirmée par:</span>
                                <span class="text-slate-900 font-medium"><?php echo e($participation->confirmateur->nom); ?> <?php echo e($participation->confirmateur->prenom); ?></span>
                            </div>
                            <?php if($participation->confirme_le): ?>
                                <div class="flex justify-between">
                                    <span class="text-slate-500">Confirmée le:</span>
                                    <span class="text-slate-900 font-medium"><?php echo e($participation->confirme_le->format('d/m/Y H:i')); ?></span>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Historique des participations -->
                <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                    <div class="p-6 border-b border-slate-200">
                        <h2 class="text-xl font-bold text-slate-800 flex items-center">
                            <i class="fas fa-history text-purple-600 mr-2"></i>
                            Historique
                        </h2>
                    </div>
                    <div class="p-6 space-y-3 text-sm">
                        <div class="flex justify-between">
                            <span class="text-slate-500">Participant depuis:</span>
                            <span class="text-slate-900 font-medium"><?php echo e($participation->participant->created_at->format('d/m/Y')); ?></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-500">Nombre de participations:</span>
                            <span class="text-slate-900 font-medium"><?php echo e($totalParticipations ?? 'N/A'); ?></span>
                        </div>
                        <?php if($premiereParticipation ?? false): ?>
                            <div class="flex justify-between">
                                <span class="text-slate-500">Première participation:</span>
                                <span class="text-slate-900 font-medium"><?php echo e($premiereParticipation->created_at->format('d/m/Y') ?? 'N/A'); ?></span>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

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
                            <span class="text-slate-900 font-medium"><?php echo e($participation->created_at->format('d/m/Y H:i')); ?></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-500">Modifié le:</span>
                            <span class="text-slate-900 font-medium"><?php echo e($participation->updated_at->format('d/m/Y H:i')); ?></span>
                        </div>
                        <?php if($participation->enregistreur): ?>
                            <div class="flex justify-between">
                                <span class="text-slate-500">Enregistré par:</span>
                                <span class="text-slate-900 font-medium"><?php echo e($participation->enregistreur->nom); ?> <?php echo e($participation->enregistreur->prenom); ?></span>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php $__env->startPush('scripts'); ?>
        <script>
            function confirmerPresence() {
                if (confirm('Confirmer la présence de ce participant ?')) {
                    fetch(`<?php echo e(route('private.participantscultes.confirmer-presence', [$participation->participant_id, $participation->culte_id])); ?>`, {
                        method: 'PATCH',
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

            function editParticipation() {
                // Ici vous pouvez implémenter l'ouverture d'un modal d'édition
                // ou rediriger vers une page d'édition
                alert('Fonctionnalité d\'édition à implémenter');
            }

            function deleteParticipation() {
                if (confirm('Êtes-vous sûr de vouloir supprimer cette participation ?')) {
                    fetch(`<?php echo e(route('private.participantscultes.destroy', [$participation->participant_id, $participation->culte_id])); ?>`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            window.location.href = '<?php echo e(route('private.participantscultes.index')); ?>';
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
        </script>
    <?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.private.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Dell\Desktop\MICRISERVICES\methodiste_belle_ville\resources\views/components/private/particitantscultes/show.blade.php ENDPATH**/ ?>