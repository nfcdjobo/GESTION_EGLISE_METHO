<?php $__env->startSection('title', 'Participants du Culte'); ?>

<?php $__env->startSection('content'); ?>
    <div class="space-y-8">
        <!-- Page Title & Breadcrumb -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1
                        class="text-3xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent">
                        Participants - <?php echo e($culte->titre); ?>

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
                                    <a href="<?php echo e(route('private.cultes.show', $culte)); ?>"
                                        class="text-sm font-medium text-slate-700 hover:text-blue-600">
                                        <?php echo e($culte->titre); ?>

                                    </a>
                                </div>
                            </li>
                            <li>
                                <div class="flex items-center">
                                    <i class="fas fa-chevron-right text-slate-400 mx-2"></i>
                                    <span class="text-sm font-medium text-slate-500">Participants</span>
                                </div>
                            </li>
                        </ol>
                    </nav>
                </div>

                <!-- Actions rapides -->
                <div class="flex items-center space-x-2">
                    <a href="<?php echo e(route('private.cultes.show', $culte)); ?>"
                        class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-slate-600 to-slate-700 text-white text-sm font-medium rounded-xl hover:from-slate-700 hover:to-slate-800 transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-arrow-left mr-2"></i> Retour au culte
                    </a>
                    <button type="button" onclick="openAddParticipantModal()"
                        class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-green-600 to-emerald-600 text-white text-sm font-medium rounded-xl hover:from-green-700 hover:to-emerald-700 transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-user-plus mr-2"></i> Ajouter un participant
                    </button>
                </div>
            </div>
        </div>

        <!-- Informations du culte et statistiques -->
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
            <!-- Info du culte -->
            <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 p-6">
                <h3 class="text-lg font-semibold text-slate-800 mb-4 flex items-center">
                    <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                    Informations du Culte
                </h3>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-slate-500">Date:</span>
                        <span class="font-medium"><?php echo e(\Carbon\Carbon::parse($culte->date_culte)->format('d/m/Y')); ?></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-500">Heure:</span>
                        <span class="font-medium"><?php echo e(\Carbon\Carbon::parse($culte->heure_debut)->format('H:i')); ?></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-500">Type:</span>
                        <span class="font-medium"><?php echo e($culte->type_culte_libelle); ?></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-500">Statut:</span>
                        <span class="font-medium"><?php echo e($culte->statut_libelle); ?></span>
                    </div>
                </div>
            </div>

            <!-- Statistiques des participants -->
            <div class="lg:col-span-3 grid grid-cols-2 md:grid-cols-5 gap-4">
                <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 p-6 text-center">
                    <div class="text-2xl font-bold text-blue-600"><?php echo e($statistiques['total']); ?></div>
                    <div class="text-sm text-slate-600">Total</div>
                </div>
                <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 p-6 text-center">
                    <div class="text-2xl font-bold text-green-600"><?php echo e($statistiques['presents']); ?></div>
                    <div class="text-sm text-slate-600">Présents</div>
                </div>
                <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 p-6 text-center">
                    <div class="text-2xl font-bold text-purple-600"><?php echo e($statistiques['en_ligne']); ?></div>
                    <div class="text-sm text-slate-600">En ligne</div>
                </div>
                <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 p-6 text-center">
                    <div class="text-2xl font-bold text-amber-600"><?php echo e($statistiques['premieres_visites']); ?></div>
                    <div class="text-sm text-slate-600">1ères visites</div>
                </div>
                <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 p-6 text-center">
                    <div class="text-2xl font-bold text-red-600"><?php echo e($statistiques['necessitant_suivi']); ?></div>
                    <div class="text-sm text-slate-600">Suivi requis</div>
                </div>
            </div>
        </div>

        <!-- Filtres et recherche -->
        <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 p-6">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-6 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Recherche</label>
                    <input type="text" name="search" value="<?php echo e(request('search')); ?>"
                        placeholder="Nom, prénom, email..."
                        class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Statut présence</label>
                    <select name="statut_presence"
                        class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors text-sm">
                        <option value="">Tous</option>
                        <?php $__currentLoopData = App\Models\ParticipantCulte::STATUT_PRESENCE; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($key); ?>"
                                <?php echo e(request('statut_presence') === $key ? 'selected' : ''); ?>><?php echo e($label); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Type participation</label>
                    <select name="type_participation"
                        class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors text-sm">
                        <option value="">Tous</option>
                        <?php $__currentLoopData = App\Models\ParticipantCulte::TYPE_PARTICIPATION; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($key); ?>"
                                <?php echo e(request('type_participation') === $key ? 'selected' : ''); ?>><?php echo e($label); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Rôle</label>
                    <select name="role_culte"
                        class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors text-sm">
                        <option value="">Tous</option>
                        <?php $__currentLoopData = App\Models\ParticipantCulte::ROLE_CULTE; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($key); ?>" <?php echo e(request('role_culte') === $key ? 'selected' : ''); ?>>
                                <?php echo e($label); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="flex items-end space-x-2">
                    <button type="submit"
                        class="px-4 py-2 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition-colors text-sm">
                        <i class="fas fa-search mr-1"></i> Filtrer
                    </button>
                    <a href="<?php echo e(route('private.cultes.participants', $culte)); ?>"
                        class="px-4 py-2 bg-slate-200 text-slate-700 rounded-xl hover:bg-slate-300 transition-colors text-sm">
                        <i class="fas fa-times mr-1"></i> Reset
                    </a>
                </div>
            </form>
        </div>

        <!-- Liste des participants -->
        <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20">
            <div class="p-6 border-b border-slate-200">
                <h2 class="text-xl font-bold text-slate-800 flex items-center">
                    <i class="fas fa-users text-blue-600 mr-2"></i>
                    Liste des Participants (<?php echo e($participants->total()); ?>)
                </h2>
            </div>

            <?php if($participants->count() > 0): ?>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">
                                    Participant</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">
                                    Statut</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">
                                    Type</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">
                                    Rôle</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">
                                    Horaires</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">
                                    Suivi</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">
                                    Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-slate-200">
                            <?php $__currentLoopData = $participants; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $participation): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                <div
                                                    class="h-10 w-10 rounded-full bg-gradient-to-r from-blue-500 to-purple-600 flex items-center justify-center text-white font-semibold text-sm">
                                                    <?php echo e(strtoupper(substr($participation->participant->prenom, 0, 1) . substr($participation->participant->nom, 0, 1))); ?>

                                                </div>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-slate-900">
                                                    <?php echo e($participation->participant->nom); ?>

                                                    <?php echo e($participation->participant->prenom); ?>

                                                </div>
                                                <div class="text-sm text-slate-500">
                                                    <?php echo e($participation->participant->email); ?></div>
                                                <?php if($participation->premiere_visite): ?>
                                                    <span
                                                        class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-amber-100 text-amber-800">
                                                        Première visite
                                                    </span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <?php
                                            $statutColors = [
                                                'present' => 'bg-green-100 text-green-800',
                                                'present_partiel' => 'bg-yellow-100 text-yellow-800',
                                                'en_retard' => 'bg-orange-100 text-orange-800',
                                                'parti_tot' => 'bg-red-100 text-red-800',
                                            ];
                                        ?>
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?php echo e($statutColors[$participation->statut_presence] ?? 'bg-gray-100 text-gray-800'); ?>">
                                            <?php echo e($participation->statut_presence_libelle); ?>

                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-900">
                                        <?php echo e($participation->type_participation_libelle); ?>

                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-900">
                                        <?php echo e($participation->role_culte_libelle); ?>

                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-900">
                                        <?php if($participation->heure_arrivee): ?>
                                            <div>Arrivée:
                                                <?php echo e(\Carbon\Carbon::parse($participation->heure_arrivee)->format('H:i')); ?>

                                            </div>
                                        <?php endif; ?>
                                        <?php if($participation->heure_depart): ?>
                                            <div>Départ:
                                                <?php echo e(\Carbon\Carbon::parse($participation->heure_depart)->format('H:i')); ?>

                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex flex-wrap gap-1">
                                            <?php if($participation->demande_contact_pastoral): ?>
                                                <span
                                                    class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                                    Contact pastoral
                                                </span>
                                            <?php endif; ?>
                                            <?php if($participation->interesse_bapteme): ?>
                                                <span
                                                    class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-purple-100 text-purple-800">
                                                    Baptême
                                                </span>
                                            <?php endif; ?>
                                            <?php if($participation->souhaite_devenir_membre): ?>
                                                <span
                                                    class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-cyan-100 text-cyan-800">
                                                    Membre
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex items-center space-x-2">
                                            <button type="button"
                                                onclick="editParticipant('<?php echo e($participation->participant_id); ?>', '<?php echo e($participation->culte_id); ?>')"
                                                class="text-blue-600 hover:text-blue-900">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button type="button"
                                                onclick="removeParticipant('<?php echo e($participation->participant_id); ?>', '<?php echo e($participation->culte_id); ?>')"
                                                class="text-red-600 hover:text-red-900">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="px-6 py-4 border-t border-slate-200">
                    <?php echo e($participants->appends(request()->query())->links()); ?>

                </div>
            <?php else: ?>
                <div class="p-12 text-center">
                    <div class="mx-auto h-12 w-12 text-slate-400">
                        <i class="fas fa-users text-4xl"></i>
                    </div>
                    <h3 class="mt-2 text-sm font-medium text-slate-900">Aucun participant</h3>
                    <p class="mt-1 text-sm text-slate-500">Commencez par ajouter des participants à ce culte.</p>
                    <div class="mt-6">
                        <button type="button" onclick="openAddParticipantModal()"
                            class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                            <i class="fas fa-user-plus mr-2"></i>
                            Ajouter un participant
                        </button>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Modal ajout participant -->
    <div id="addParticipantModal"
        class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-xl max-w-2xl w-full max-h-screen overflow-y-auto">
            <div class="p-6 border-b border-slate-200">
                <h3 class="text-lg font-semibold text-slate-900">Ajouter un participant</h3>
            </div>
            <form id="addParticipantForm">
                <?php echo csrf_field(); ?>
                <div class="p-6 space-y-6">
                    <!-- Champ caché pour le culte_id -->
                    <input type="hidden" name="culte_id" value="<?php echo e($culte->id); ?>">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Recherche de participant -->


                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-slate-700 mb-2">Rechercher un participant</label>
                            <div class="relative">
                                <input type="text" id="participantSearch"
                                    placeholder="Rechercher par nom, prénom, email ou téléphone..."
                                    class="w-full px-3 py-2 pr-10 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                    <i class="fas fa-search text-slate-400"></i>
                                </div>

                                <!-- Résultats de recherche - maintenant correctement positionnés -->
                                <div id="searchResults"
                                    class="hidden absolute z-50 left-0 right-0 top-full bg-white border border-slate-300 rounded-xl shadow-lg max-h-60 overflow-y-auto mt-1">
                                </div>
                            </div>
                            <input type="hidden" name="participant_id" id="selectedParticipantId">
                        </div>

                        <!-- Divider -->
                        <div class="md:col-span-2">
                            <div class="relative">
                                <div class="absolute inset-0 flex items-center">
                                    <div class="w-full border-t border-slate-300"></div>
                                </div>
                                <div class="relative flex justify-center text-sm">
                                    <span class="px-2 bg-white text-slate-500">OU créer un nouveau participant</span>
                                </div>
                            </div>
                        </div>

                        <!-- Champs pour créer un nouvel membres -->
                        <div id="newUserFields" class="md:col-span-2 hidden">
                            <div class="bg-blue-50 border border-blue-200 rounded-xl p-4">
                                <h4 class="text-sm font-medium text-blue-900 mb-3">Informations du nouveau participant</h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label for="newPrenom" class="block text-sm font-medium text-slate-700 mb-1">Prénom *</label>
                                        <input type="text" name="prenom" id="newPrenom" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors text-sm">
                                    </div>
                                    <div>
                                        <label for="newNom" class="block text-sm font-medium text-slate-700 mb-1">Nom *</label>
                                        <input type="text" name="nom" id="newNom" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors text-sm">
                                    </div>
                                    <div>
                                        <label for="newSexe" class="block text-sm font-medium text-slate-700 mb-1">Sexe *</label>
                                        <select name="sexe" id="newSexe" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors text-sm">
                                            <option value="">Sélectionner</option>
                                            <option value="masculin">Masculin</option>
                                            <option value="feminin">Féminin</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label for="newTelephone" class="block text-sm font-medium text-slate-700 mb-1">Téléphone *</label>
                                        <input type="tel" name="telephone_1" id="newTelephone"
                                            class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors text-sm">
                                    </div>
                                    <div>
                                        <label for="newEmail" class="block text-sm font-medium text-slate-700 mb-1">Email
                                            (optionnel)</label>
                                        <input type="email" name="email" id="newEmail"
                                            class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors text-sm">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Statut présence -->
                        <div>
                            <label for="statutPresence" class="block text-sm font-medium text-slate-700 mb-2">Statut de présence *</label>
                            <select name="statut_presence" id="statutPresence" required class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                <?php $__currentLoopData = App\Models\ParticipantCulte::STATUT_PRESENCE; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($key); ?>" <?php echo e($key === 'present' ? 'selected' : ''); ?>><?php echo e($label); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>

                        <!-- Type participation -->
                        <div>
                            <label for="typeParticipation" class="block text-sm font-medium text-slate-700 mb-2">Type de participation *</label>
                            <select name="type_participation" required id="typeParticipation"
                                class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                <?php $__currentLoopData = App\Models\ParticipantCulte::TYPE_PARTICIPATION; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($key); ?>" <?php echo e($key === 'physique' ? 'selected' : ''); ?>>
                                        <?php echo e($label); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>

                        <!-- Rôle -->
                        <div>
                            <label for="roleCulte" class="block text-sm font-medium text-slate-700 mb-2">Rôle dans le culte *</label>
                            <select name="role_culte" required id="roleCulte"
                                class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                <?php $__currentLoopData = App\Models\ParticipantCulte::ROLE_CULTE; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($key); ?>" <?php echo e($key === 'participant' ? 'selected' : ''); ?>>
                                        <?php echo e($label); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>


                        <div>
                            <label for="heureArrivee" class="block text-sm font-medium text-slate-700 mb-2">Heure d'arrivée</label>
                            <input type="time" name="heure_arrivee" id="heureArrivee"
                                class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        </div>


                    </div>

                    <!-- Options spéciales -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-3">
                            <label for="premiereVisite" class="flex items-center">
                                <input  type="checkbox" name="premiere_visite" value="1" id="premiereVisite"
                                    class="rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                                <span class="ml-2 text-sm text-slate-700">Première visite</span>
                            </label>

                        </div>

                    </div>


                </div>

                <div class="flex items-center justify-end space-x-3 p-6 border-t border-slate-200">
                    <button type="button" onclick="closeAddParticipantModal()"
                        class="px-4 py-2 text-slate-700 bg-slate-100 rounded-xl hover:bg-slate-200 transition-colors">
                        Annuler
                    </button>
                    <button type="submit"
                        class="px-4 py-2 bg-green-600 text-white rounded-xl hover:bg-green-700 transition-colors">
                        <i class="fas fa-user-plus mr-2"></i> Ajouter
                    </button>
                </div>
            </form>
        </div>
    </div>



    <?php $__env->startPush('scripts'); ?>
    <script>
        let searchTimeout;
        let isNewUser = false;

        // Modal ajout participant
        function openAddParticipantModal() {
            document.getElementById('addParticipantModal').classList.remove('hidden');
            document.getElementById('participantSearch').focus();
        }

        function closeAddParticipantModal() {
            document.getElementById('addParticipantModal').classList.add('hidden');
            document.getElementById('addParticipantForm').reset();
            clearSearch();
            hideNewUserFields();
        }

        // Recherche de participants
        document.getElementById('participantSearch').addEventListener('input', function(e) {
            const query = e.target.value.trim();

            clearTimeout(searchTimeout);

            if (query.length < 2) {
                hideSearchResults();
                return;
            }

            searchTimeout = setTimeout(() => {
                searchParticipants(query);
            }, 300);
        });

        async function searchParticipants(query) {
            try {
                const response = await fetch(`<?php echo e(route('private.participantscultes.search', ':culteid')); ?>`.replace(':culteid', '<?php echo e($culte->id); ?>')+`?q=${encodeURIComponent(query)}`, {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
                    }
                });

                const data = await response.json();

                if (data.success) {
                    displaySearchResults(data.data, query);
                }
            } catch (error) {
                console.error('Erreur lors de la recherche:', error);
            }
        }

        function displaySearchResults(users, query) {
            const resultsContainer = document.getElementById('searchResults');
            resultsContainer.innerHTML = '';

            if (users.length === 0) {
                resultsContainer.innerHTML = `
                    <div class="p-4">
                        <div class="text-sm text-slate-500 mb-2">Aucun participant trouvé pour "${query}"</div>
                        <button type="button" onclick="showNewUserFields('${query}')"
                            class="inline-flex items-center px-3 py-1 bg-green-600 text-white text-xs rounded-lg hover:bg-green-700">
                            <i class="fas fa-user-plus mr-1"></i> Créer un nouveau participant
                        </button>
                    </div>
                `;
            } else {
                users.forEach(user => {
                    const userDiv = document.createElement('div');
                    userDiv.className =
                        'p-3 hover:bg-slate-50 cursor-pointer border-b border-slate-100 last:border-b-0';
                    userDiv.onclick = () => selectParticipant(user);

                    userDiv.innerHTML = `
                        <div class="flex items-center justify-between">
                            <div>
                                <div class="font-medium text-slate-900">${user.nom} ${user.prenom}</div>
                                <div class="text-sm text-slate-500">
                                    ${user.email || ''} ${user.email && user.telephone_1 ? '•' : ''} ${user.telephone_1 || ''}
                                </div>
                            </div>
                            <i class="fas fa-chevron-right text-slate-400"></i>
                        </div>
                    `;

                    resultsContainer.appendChild(userDiv);
                });

                // Ajouter option pour créer un nouveau participant
                const createNewDiv = document.createElement('div');
                createNewDiv.className = 'p-3 bg-green-50 hover:bg-green-100 cursor-pointer border-t border-green-200';
                createNewDiv.onclick = () => showNewUserFields(query);
                createNewDiv.innerHTML = `
                    <div class="flex items-center text-green-700">
                        <i class="fas fa-user-plus mr-2"></i>
                        <span class="text-sm font-medium">Créer un nouveau participant</span>
                    </div>
                `;
                resultsContainer.appendChild(createNewDiv);
            }

            resultsContainer.classList.remove('hidden');
        }

        function selectParticipant(user) {
            document.getElementById('participantSearch').value = `${user.nom} ${user.prenom}`;
            document.getElementById('selectedParticipantId').value = user.id;
            hideSearchResults();
            hideNewUserFields();
            isNewUser = false;
        }

        function showNewUserFields(query = '') {
            hideSearchResults();
            document.getElementById('newUserFields').classList.remove('hidden');
            document.getElementById('selectedParticipantId').value = '';

            // Pré-remplir les champs si possible à partir de la recherche
            const names = query.split(' ');
            if (names.length >= 2) {
                document.getElementById('newPrenom').value = names[0];
                document.getElementById('newNom').value = names.slice(1).join(' ');
            } else if (names.length === 1) {
                document.getElementById('newPrenom').value = names[0];
            }

            // Si la recherche ressemble à un email
            if (query.includes('@')) {
                document.getElementById('newEmail').value = query;
            }

            // Si la recherche ressemble à un téléphone
            if (/^\+?[\d\s\-()]+$/.test(query)) {
                document.getElementById('newTelephone').value = query;
            }

            isNewUser = true;
            document.getElementById('newPrenom').focus();
        }

        function hideNewUserFields() {
            document.getElementById('newUserFields').classList.add('hidden');
            // Réinitialiser les champs
            ['newPrenom', 'newNom', 'newSexe', 'newTelephone', 'newEmail'].forEach(id => {
                document.getElementById(id).value = '';
            });
            isNewUser = false;
        }

        function hideSearchResults() {
            document.getElementById('searchResults').classList.add('hidden');
        }

        function clearSearch() {
            document.getElementById('participantSearch').value = '';
            document.getElementById('selectedParticipantId').value = '';
            hideSearchResults();
        }

        // Soumission du formulaire par API
        document.getElementById('addParticipantForm').addEventListener('submit', async function(e) {
            e.preventDefault(); // Empêcher la soumission normale

            let isValid = true;
            const errors = [];

            // Réinitialiser les styles d'erreur
            document.querySelectorAll('.border-red-500').forEach(el => {
                el.classList.remove('border-red-500');
            });

            if (isNewUser) {
                // Validation pour nouveau participant
                const newUserRequired = [
                    {id: 'newPrenom', name: 'Prénom'},
                    {id: 'newNom', name: 'Nom'},
                    {id: 'newSexe', name: 'Sexe'},
                    {id: 'newTelephone', name: 'Téléphone'}
                ];

                newUserRequired.forEach(field => {
                    const element = document.getElementById(field.id);
                    if (!element.value.trim()) {
                        element.classList.add('border-red-500');
                        errors.push(field.name);
                        isValid = false;
                    }
                });

                // Validation email si fourni (format)
                const emailField = document.getElementById('newEmail');
                if (emailField.value.trim()) {
                    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                    if (!emailPattern.test(emailField.value.trim())) {
                        emailField.classList.add('border-red-500');
                        errors.push('Email (format invalide)');
                        isValid = false;
                    }
                }

                // Validation téléphone (format basique)
                const phoneField = document.getElementById('newTelephone');
                if (phoneField.value.trim()) {
                    const phonePattern = /^[\+]?[0-9\s\-\(\)]{8,}$/;
                    if (!phonePattern.test(phoneField.value.trim())) {
                        phoneField.classList.add('border-red-500');
                        errors.push('Téléphone (format invalide)');
                        isValid = false;
                    }
                }
            } else {
                // Validation pour participant existant
                if (!document.getElementById('selectedParticipantId').value) {
                    document.getElementById('participantSearch').classList.add('border-red-500');
                    errors.push('Sélection du participant');
                    isValid = false;
                }
            }

            // Validation des champs communs obligatoires
            const commonRequired = [
                {id: 'statutPresence', label: 'Statut de présence'},
                {id: 'typeParticipation', label: 'Type de participation'},
                {id: 'roleCulte', label: 'Rôle dans le culte'}
            ];

            commonRequired.forEach(field => {
                const element = document.getElementById(`${field.id}`);
                if (!element.value.trim()) {
                    element.classList.add('border-red-500');
                    errors.push(field.label);
                    isValid = false;
                }
            });

            // Validation heure d'arrivée (format si fournie)
            const heureArriveeField = document.querySelector('input[name="heure_arrivee"]');
            if (heureArriveeField.value.trim()) {
                const timePattern = /^([0-1]?[0-9]|2[0-3]):[0-5][0-9]$/;
                if (!timePattern.test(heureArriveeField.value.trim())) {
                    heureArriveeField.classList.add('border-red-500');
                    errors.push('Heure d\'arrivée (format invalide)');
                    isValid = false;
                }
            }

            if (!isValid) {
                // Afficher les erreurs de manière plus user-friendly
                let errorMessage = 'Veuillez corriger les erreurs suivantes :\n\n';
                errors.forEach((error, index) => {
                    errorMessage += `${index + 1}. ${error}\n`;
                });
                alert(errorMessage);

                // Faire défiler vers le premier champ en erreur
                const firstErrorField = document.querySelector('.border-red-500');
                if (firstErrorField) {
                    firstErrorField.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    firstErrorField.focus();
                }
                return false;
            }

            // Si validation OK, faire l'appel API
            await submitParticipantForm();
        });

        async function submitParticipantForm() {
            const submitButton = document.querySelector('#addParticipantForm button[type="submit"]');

            // Indicateur de chargement
            const originalButtonContent = submitButton.innerHTML;
            submitButton.disabled = true;
            submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Ajout en cours...';

            try {
                // Préparer les données
                const requestData = {
                    culte_id: '<?php echo e($culte->id); ?>',
                    statut_presence: document.getElementById('statutPresence').value,
                    type_participation: document.getElementById('typeParticipation').value,
                    role_culte: document.getElementById('roleCulte').value,
                };

                // Ajouter heure d'arrivée si fournie
                const heureArrivee = document.querySelector('input[name="heure_arrivee"]').value;
                if (heureArrivee) requestData.heure_arrivee = heureArrivee;

                // Ajouter première visite si cochée
                if (document.getElementById('premiereVisite').checked) {
                    requestData.premiere_visite = true;
                }

                // Ajouter les données selon le type
                if (isNewUser) {
                    requestData.prenom = document.getElementById('newPrenom').value.trim();
                    requestData.nom = document.getElementById('newNom').value.trim();
                    requestData.sexe = document.getElementById('newSexe').value;
                    requestData.telephone_1 = document.getElementById('newTelephone').value.trim();

                    const email = document.getElementById('newEmail').value.trim();
                    if (email) requestData.email = email;
                } else {
                    requestData.participant_id = document.getElementById('selectedParticipantId').value;
                }

                // Faire l'appel API
                const response = await fetch('<?php echo e(route("private.participantscultes.store-with-user-creation")); ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(requestData)
                });

                const data = await response.json();

                if (data.success) {
                    showSuccessMessage(data.message);
                    closeAddParticipantModal();
                    setTimeout(() => window.location.reload(), 1000);
                } else {
                    handleApiErrors(data);
                }

            } catch (error) {
                console.error('Erreur lors de l\'ajout du participant:', error);
                showErrorMessage('Une erreur inattendue s\'est produite. Veuillez réessayer.');
            } finally {
                submitButton.disabled = false;
                submitButton.innerHTML = originalButtonContent;
            }
        }

        function handleApiErrors(data) {
            if (data.errors) {
                // Erreurs de validation Laravel
                let errorMessage = 'Erreurs de validation :\n\n';
                Object.keys(data.errors).forEach(field => {
                    data.errors[field].forEach(error => {
                        errorMessage += `• ${error}\n`;
                    });
                });
                alert(errorMessage);

                // Surligner les champs en erreur si possible
                Object.keys(data.errors).forEach(fieldName => {
                    const field = document.querySelector(`[name="${fieldName}"]`) ||
                                 document.getElementById(`new${fieldName.charAt(0).toUpperCase() + fieldName.slice(1)}`);
                    if (field) {
                        field.classList.add('border-red-500');
                    }
                });
            } else {
                // Autre type d'erreur
                showErrorMessage(data.message || 'Une erreur s\'est produite lors de l\'ajout du participant.');
            }
        }

        function showSuccessMessage(message) {
            // Créer une notification de succès temporaire
            const notification = document.createElement('div');
            notification.className = 'fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 transition-all duration-300';
            notification.innerHTML = `
                <div class="flex items-center">
                    <i class="fas fa-check-circle mr-2"></i>
                    <span>${message}</span>
                </div>
            `;

            document.body.appendChild(notification);

            // Supprimer après 3 secondes
            setTimeout(() => {
                notification.style.opacity = '0';
                setTimeout(() => {
                    if (notification.parentNode) {
                        notification.parentNode.removeChild(notification);
                    }
                }, 300);
            }, 3000);
        }

        function showErrorMessage(message) {
            // Créer une notification d'erreur temporaire
            const notification = document.createElement('div');
            notification.className = 'fixed top-4 right-4 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 transition-all duration-300';
            notification.innerHTML = `
                <div class="flex items-center">
                    <i class="fas fa-exclamation-circle mr-2"></i>
                    <span>${message}</span>
                </div>
            `;

            document.body.appendChild(notification);

            // Supprimer après 5 secondes pour les erreurs
            setTimeout(() => {
                notification.style.opacity = '0';
                setTimeout(() => {
                    if (notification.parentNode) {
                        notification.parentNode.removeChild(notification);
                    }
                }, 300);
            }, 5000);
        }

        // Fonction utilitaire pour enlever l'erreur quand l'membres commence à taper
        function setupFieldErrorClearance() {
            const fields = [
                'participantSearch', 'newPrenom', 'newNom', 'newSexe',
                'newTelephone', 'newEmail'
            ];

            fields.forEach(fieldId => {
                const field = document.getElementById(fieldId);
                if (field) {
                    field.addEventListener('input', function() {
                        this.classList.remove('border-red-500');
                    });
                }
            });

            // Pour les selects
            document.querySelectorAll('select[name="statut_presence"], select[name="type_participation"], select[name="role_culte"]').forEach(select => {
                select.addEventListener('change', function() {
                    this.classList.remove('border-red-500');
                });
            });

            // Pour l'heure d'arrivée
            const heureField = document.querySelector('input[name="heure_arrivee"]');
            if (heureField) {
                heureField.addEventListener('input', function() {
                    this.classList.remove('border-red-500');
                });
            }
        }

        document.addEventListener('DOMContentLoaded', setupFieldErrorClearance);

        // Fermer les résultats en cliquant ailleurs
        document.addEventListener('click', function(e) {
            if (!e.target.closest('#participantSearch') && !e.target.closest('#searchResults')) {
                hideSearchResults();
            }
        });

        // Fermer les modals en cliquant à l'extérieur
        document.getElementById('addParticipantModal').addEventListener('click', function(e) {
            if (e.target === this) closeAddParticipantModal();
        });

        // Fonctions pour édition et suppression (à implémenter)
        function editParticipant(participantId, culteId) {
            // TODO: Implémenter l'édition
            console.log('Edit participant:', participantId, culteId);
        }

        function removeParticipant(participantId, culteId) {
            if (confirm('Êtes-vous sûr de vouloir retirer ce participant du culte ?')) {
                // TODO: Implémenter la suppression
                console.log('Remove participant:', participantId, culteId);
            }
        }
    </script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.private.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Dell\Desktop\MICRISERVICES\methodiste_belle_ville\resources\views/components/private/cultes/participants.blade.php ENDPATH**/ ?>