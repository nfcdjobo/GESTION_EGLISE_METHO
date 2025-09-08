<?php $__env->startSection('title', 'Détails du Contact - ' . $contact->nom_eglise); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-8">
    <!-- Page Title & Breadcrumb -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent"><?php echo e($contact->nom_eglise); ?></h1>
                <nav class="flex mt-2" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-1 md:space-x-3">
                        <li class="inline-flex items-center">
                            <a href="<?php echo e(route('private.contacts.index')); ?>" class="inline-flex items-center text-sm font-medium text-slate-700 hover:text-blue-600 transition-colors">
                                <i class="fas fa-church mr-2"></i>
                                Contacts
                            </a>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <i class="fas fa-chevron-right text-slate-400 mx-2"></i>
                                <span class="text-sm font-medium text-slate-500"><?php echo e(Str::limit($contact->nom_eglise, 30)); ?></span>
                            </div>
                        </li>
                    </ol>
                </nav>
            </div>

            <!-- Actions rapides -->
            <div class="flex flex-wrap gap-2">
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('contacts.update')): ?>
                    <a href="<?php echo e(route('private.contacts.edit', $contact)); ?>" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-yellow-600 to-amber-600 text-white text-sm font-medium rounded-xl hover:from-yellow-700 hover:to-amber-700 transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-edit mr-2"></i> Modifier
                    </a>
                <?php endif; ?>

                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('contacts.update')): ?>
                    <?php if(!$contact->verifie): ?>
                        <button onclick="verifyContact('<?php echo e($contact->id); ?>')" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-green-600 to-emerald-600 text-white text-sm font-medium rounded-xl hover:from-green-700 hover:to-emerald-700 transition-all duration-200 shadow-md hover:shadow-lg">
                            <i class="fas fa-check mr-2"></i> Vérifier
                        </button>
                    <?php endif; ?>
                <?php endif; ?>

                <button onclick="showQRCode('<?php echo e($contact->id); ?>')" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-purple-600 to-pink-600 text-white text-sm font-medium rounded-xl hover:from-purple-700 hover:to-pink-700 transition-all duration-200 shadow-md hover:shadow-lg">
                    <i class="fas fa-qrcode mr-2"></i> QR Code
                </button>

                <a href="<?php echo e(route('private.contacts.export', $contact)); ?>" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-cyan-600 to-blue-600 text-white text-sm font-medium rounded-xl hover:from-cyan-700 hover:to-blue-700 transition-all duration-200 shadow-md hover:shadow-lg">
                    <i class="fas fa-download mr-2"></i> vCard
                </a>
            </div>
        </div>
    </div>

    <!-- En-tête avec informations principales -->
    <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
        <div class="p-8">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                <!-- Logo et infos principales -->
                <div class="flex items-center space-x-6">
                    <div class="flex-shrink-0">
                        <?php if($contact->logo_url): ?>
                            <img class="h-20 w-20 rounded-2xl object-cover shadow-lg" src="<?php echo e($contact->logo_url); ?>" alt="<?php echo e($contact->nom_eglise); ?>">
                        <?php else: ?>
                            <div class="h-20 w-20 rounded-2xl bg-gradient-to-r from-blue-500 to-purple-500 flex items-center justify-center shadow-lg">
                                <span class="text-white font-bold text-2xl"><?php echo e(substr($contact->nom_eglise, 0, 2)); ?></span>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-slate-900"><?php echo e($contact->nom_eglise); ?></h2>
                        <?php if($contact->denomination): ?>
                            <p class="text-lg text-slate-600 mt-1"><?php echo e($contact->denomination); ?></p>
                        <?php endif; ?>
                        <?php if($contact->description_courte): ?>
                            <p class="text-sm text-slate-500 mt-2 max-w-md"><?php echo e($contact->description_courte); ?></p>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Badges de statut -->
                <div class="flex flex-wrap gap-2 lg:flex-col lg:items-end">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                        <?php switch($contact->type_contact):
                            case ('principal'): ?> bg-blue-100 text-blue-800 <?php break; ?>
                            <?php case ('pastoral'): ?> bg-green-100 text-green-800 <?php break; ?>
                            <?php case ('administratif'): ?> bg-purple-100 text-purple-800 <?php break; ?>
                            <?php case ('urgence'): ?> bg-red-100 text-red-800 <?php break; ?>
                            <?php default: ?> bg-gray-100 text-gray-800
                        <?php endswitch; ?>">
                        <?php echo e(ucfirst($contact->type_contact)); ?>

                    </span>

                    <?php if($contact->verifie): ?>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                            <i class="fas fa-check mr-1"></i> Vérifié
                        </span>
                    <?php else: ?>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                            <i class="fas fa-clock mr-1"></i> En attente
                        </span>
                    <?php endif; ?>

                    <?php if($contact->visible_public): ?>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                            <i class="fas fa-eye mr-1"></i> Public
                        </span>
                    <?php endif; ?>

                    <?php if($contact->latitude && $contact->longitude): ?>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-emerald-100 text-emerald-800">
                            <i class="fas fa-map-marker-alt mr-1"></i> Géolocalisé
                        </span>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistiques rapides -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-cyan-500 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-percentage text-white text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-2xl font-bold text-slate-800"><?php echo e($stats['completude']); ?>%</p>
                    <p class="text-sm text-slate-500">Complétude</p>
                </div>
            </div>
        </div>

        <?php if($contact->capacite_accueil): ?>
        <div class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-gradient-to-r from-green-500 to-emerald-500 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-users text-white text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-2xl font-bold text-slate-800"><?php echo e(number_format($contact->capacite_accueil)); ?></p>
                    <p class="text-sm text-slate-500">Capacité</p>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <?php if($contact->nombre_membres): ?>
        <div class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-gradient-to-r from-purple-500 to-pink-500 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-user-friends text-white text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-2xl font-bold text-slate-800"><?php echo e(number_format($contact->nombre_membres)); ?></p>
                    <p class="text-sm text-slate-500">Membres</p>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <?php if($stats['derniere_verification']): ?>
        <div class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-gradient-to-r from-amber-500 to-orange-500 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-clock text-white text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-bold text-slate-800"><?php echo e($stats['derniere_verification']->diffForHumans()); ?></p>
                    <p class="text-sm text-slate-500">Dernière vérification</p>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <!-- Contenu principal -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Colonne principale -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Coordonnées -->
            <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="p-6 border-b border-slate-200">
                    <h3 class="text-xl font-bold text-slate-800 flex items-center">
                        <i class="fas fa-phone text-green-600 mr-2"></i>
                        Coordonnées
                    </h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <?php if($contact->telephone_principal): ?>
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-phone text-green-600"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-slate-700">Téléphone principal</p>
                                <p class="text-lg font-semibold text-slate-900"><?php echo e($contact->telephone_principal); ?></p>
                            </div>
                        </div>
                        <?php endif; ?>

                        <?php if($contact->telephone_secondaire): ?>
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-phone text-blue-600"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-slate-700">Téléphone secondaire</p>
                                <p class="text-lg font-semibold text-slate-900"><?php echo e($contact->telephone_secondaire); ?></p>
                            </div>
                        </div>
                        <?php endif; ?>

                        <?php if($contact->email_principal): ?>
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-envelope text-purple-600"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-slate-700">Email principal</p>
                                <a href="mailto:<?php echo e($contact->email_principal); ?>" class="text-lg font-semibold text-purple-600 hover:text-purple-700"><?php echo e($contact->email_principal); ?></a>
                            </div>
                        </div>
                        <?php endif; ?>

                        <?php if($contact->whatsapp): ?>
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                                <i class="fab fa-whatsapp text-green-600"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-slate-700">WhatsApp</p>
                                <a href="https://wa.me/<?php echo e(preg_replace('/[^0-9]/', '', $contact->whatsapp)); ?>" target="_blank" class="text-lg font-semibold text-green-600 hover:text-green-700"><?php echo e($contact->whatsapp); ?></a>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Localisation -->
            <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="p-6 border-b border-slate-200">
                    <h3 class="text-xl font-bold text-slate-800 flex items-center">
                        <i class="fas fa-map-marker-alt text-red-600 mr-2"></i>
                        Localisation
                    </h3>
                </div>
                <div class="p-6">
                    <?php if($contact->adresse_complete): ?>
                    <div class="mb-6">
                        <div class="flex items-start space-x-3">
                            <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center mt-1">
                                <i class="fas fa-map-marker-alt text-red-600"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-slate-700 mb-1">Adresse complète</p>
                                <p class="text-slate-900"><?php echo e($contact->adresse_complete); ?></p>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                        <?php if($contact->quartier): ?>
                        <div>
                            <p class="text-sm font-medium text-slate-700">Quartier</p>
                            <p class="text-slate-900"><?php echo e($contact->quartier); ?></p>
                        </div>
                        <?php endif; ?>

                        <?php if($contact->ville): ?>
                        <div>
                            <p class="text-sm font-medium text-slate-700">Ville</p>
                            <p class="text-slate-900"><?php echo e($contact->ville); ?></p>
                        </div>
                        <?php endif; ?>

                        <?php if($contact->pays): ?>
                        <div>
                            <p class="text-sm font-medium text-slate-700">Pays</p>
                            <p class="text-slate-900"><?php echo e($contact->pays); ?></p>
                        </div>
                        <?php endif; ?>
                    </div>

                    <?php if($contact->latitude && $contact->longitude): ?>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm font-medium text-slate-700">Latitude</p>
                            <p class="text-slate-900 font-mono"><?php echo e($contact->latitude); ?></p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-slate-700">Longitude</p>
                            <p class="text-slate-900 font-mono"><?php echo e($contact->longitude); ?></p>
                        </div>
                    </div>

                    <div class="mt-4">
                        <a href="https://www.google.com/maps?q=<?php echo e($contact->latitude); ?>,<?php echo e($contact->longitude); ?>" target="_blank" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                            <i class="fas fa-external-link-alt mr-2"></i> Voir sur Google Maps
                        </a>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Leadership -->
            <?php if($contact->pasteur_principal || $contact->telephone_pasteur || $contact->email_pasteur): ?>
            <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="p-6 border-b border-slate-200">
                    <h3 class="text-xl font-bold text-slate-800 flex items-center">
                        <i class="fas fa-user-tie text-purple-600 mr-2"></i>
                        Leadership
                    </h3>
                </div>
                <div class="p-6">
                    <div class="flex items-start space-x-4">
                        <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-user-tie text-purple-600 text-lg"></i>
                        </div>
                        <div class="flex-1">
                            <?php if($contact->pasteur_principal): ?>
                            <h4 class="text-lg font-semibold text-slate-900"><?php echo e($contact->pasteur_principal); ?></h4>
                            <p class="text-sm text-slate-600 mb-2">Pasteur principal</p>
                            <?php endif; ?>

                            <div class="space-y-2">
                                <?php if($contact->telephone_pasteur): ?>
                                <div class="flex items-center space-x-2">
                                    <i class="fas fa-phone text-green-600 text-sm"></i>
                                    <span class="text-sm text-slate-700"><?php echo e($contact->telephone_pasteur); ?></span>
                                </div>
                                <?php endif; ?>

                                <?php if($contact->email_pasteur): ?>
                                <div class="flex items-center space-x-2">
                                    <i class="fas fa-envelope text-purple-600 text-sm"></i>
                                    <a href="mailto:<?php echo e($contact->email_pasteur); ?>" class="text-sm text-purple-600 hover:text-purple-700"><?php echo e($contact->email_pasteur); ?></a>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <!-- Mission et Vision -->
            <?php if($contact->mission_vision): ?>
            <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="p-6 border-b border-slate-200">
                    <h3 class="text-xl font-bold text-slate-800 flex items-center">
                        <i class="fas fa-bullseye text-orange-600 mr-2"></i>
                        Mission et Vision
                    </h3>
                </div>
                <div class="p-6">
                    <p class="text-slate-700 leading-relaxed"><?php echo e($contact->mission_vision); ?></p>
                </div>
            </div>
            <?php endif; ?>
        </div>

        <!-- Colonne de droite -->
        <div class="space-y-6">
            <!-- Réseaux sociaux et sites web -->
            <?php
                $hasSocialMedia = $contact->site_web_principal || $contact->facebook_url || $contact->instagram_url || $contact->youtube_url;
            ?>

            <?php if($hasSocialMedia): ?>
            <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="p-6 border-b border-slate-200">
                    <h3 class="text-xl font-bold text-slate-800 flex items-center">
                        <i class="fas fa-share-alt text-blue-600 mr-2"></i>
                        Présence en ligne
                    </h3>
                </div>
                <div class="p-6 space-y-4">
                    <?php if($contact->site_web_principal): ?>
                    <a href="<?php echo e($contact->site_web_principal); ?>" target="_blank" class="flex items-center space-x-3 p-3 rounded-lg hover:bg-slate-50 transition-colors">
                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-globe text-blue-600"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-slate-700">Site web</p>
                            <p class="text-blue-600 hover:text-blue-700"><?php echo e($contact->site_web_principal); ?></p>
                        </div>
                    </a>
                    <?php endif; ?>

                    <?php if($contact->facebook_url): ?>
                    <a href="<?php echo e($contact->facebook_url); ?>" target="_blank" class="flex items-center space-x-3 p-3 rounded-lg hover:bg-slate-50 transition-colors">
                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                            <i class="fab fa-facebook text-blue-600"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-slate-700">Facebook</p>
                            <p class="text-blue-600 hover:text-blue-700 text-sm"><?php echo e(Str::limit($contact->facebook_url, 30)); ?></p>
                        </div>
                    </a>
                    <?php endif; ?>

                    <?php if($contact->instagram_url): ?>
                    <a href="<?php echo e($contact->instagram_url); ?>" target="_blank" class="flex items-center space-x-3 p-3 rounded-lg hover:bg-slate-50 transition-colors">
                        <div class="w-10 h-10 bg-pink-100 rounded-lg flex items-center justify-center">
                            <i class="fab fa-instagram text-pink-600"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-slate-700">Instagram</p>
                            <p class="text-pink-600 hover:text-pink-700 text-sm"><?php echo e(Str::limit($contact->instagram_url, 30)); ?></p>
                        </div>
                    </a>
                    <?php endif; ?>

                    <?php if($contact->youtube_url): ?>
                    <a href="<?php echo e($contact->youtube_url); ?>" target="_blank" class="flex items-center space-x-3 p-3 rounded-lg hover:bg-slate-50 transition-colors">
                        <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center">
                            <i class="fab fa-youtube text-red-600"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-slate-700">YouTube</p>
                            <p class="text-red-600 hover:text-red-700 text-sm"><?php echo e(Str::limit($contact->youtube_url, 30)); ?></p>
                        </div>
                    </a>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>

            <!-- Informations supplémentaires -->
            <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="p-6 border-b border-slate-200">
                    <h3 class="text-xl font-bold text-slate-800 flex items-center">
                        <i class="fas fa-info-circle text-slate-600 mr-2"></i>
                        Informations
                    </h3>
                </div>
                <div class="p-6 space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-slate-700">Créé le</span>
                        <span class="text-sm text-slate-600"><?php echo e($contact->created_at->format('d/m/Y')); ?></span>
                    </div>

                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-slate-700">Modifié le</span>
                        <span class="text-sm text-slate-600"><?php echo e($contact->updated_at->format('d/m/Y')); ?></span>
                    </div>

                    <?php if($contact->derniere_verification): ?>
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-slate-700">Vérifié le</span>
                        <span class="text-sm text-slate-600"><?php echo e($contact->derniere_verification->format('d/m/Y')); ?></span>
                    </div>
                    <?php endif; ?>

                    <?php if($contact->createur): ?>
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-slate-700">Créé par</span>
                        <span class="text-sm text-slate-600"><?php echo e($contact->createur->nom); ?> <?php echo e($contact->createur->prenom); ?></span>
                    </div>
                    <?php endif; ?>

                    <?php if($contact->responsableContact): ?>
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-slate-700">Responsable</span>
                        <span class="text-sm text-slate-600"><?php echo e($contact->responsableContact->nom); ?> <?php echo e($contact->responsableContact->prenom); ?></span>
                    </div>
                    <?php endif; ?>

                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-slate-700">Complétude</span>
                        <div class="flex items-center space-x-2">
                            <div class="w-16 bg-gray-200 rounded-full h-2">
                                <div class="bg-blue-600 h-2 rounded-full" style="width: <?php echo e($stats['completude']); ?>%"></div>
                            </div>
                            <span class="text-sm font-medium text-slate-700"><?php echo e($stats['completude']); ?>%</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions rapides -->
            <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="p-6 border-b border-slate-200">
                    <h3 class="text-xl font-bold text-slate-800 flex items-center">
                        <i class="fas fa-bolt text-yellow-600 mr-2"></i>
                        Actions rapides
                    </h3>
                </div>
                <div class="p-6 space-y-3">
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('contacts.update')): ?>
                    <button onclick="toggleVisibility(<?php echo e($contact->id); ?>)" class="w-full flex items-center justify-center px-4 py-2 bg-slate-600 text-white text-sm font-medium rounded-lg hover:bg-slate-700 transition-colors">
                        <i class="fas fa-<?php echo e($contact->visible_public ? 'eye-slash' : 'eye'); ?> mr-2"></i>
                        <?php echo e($contact->visible_public ? 'Rendre privé' : 'Rendre public'); ?>

                    </button>
                    <?php endif; ?>

                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('contacts.create')): ?>
                    <a href="<?php echo e(route('private.contacts.duplicate', $contact)); ?>" class="w-full flex items-center justify-center px-4 py-2 bg-gray-600 text-white text-sm font-medium rounded-lg hover:bg-gray-700 transition-colors">
                        <i class="fas fa-copy mr-2"></i> Dupliquer
                    </a>
                    <?php endif; ?>

                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('contacts.delete')): ?>
                    <button onclick="deleteContact(<?php echo e($contact->id); ?>)" class="w-full flex items-center justify-center px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700 transition-colors">
                        <i class="fas fa-trash mr-2"></i> Supprimer
                    </button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
// Vérification d'un contact
function verifyContact(contactId) {
    if (confirm('Voulez-vous marquer ce contact comme vérifié ?')) {
        fetch(`<?php echo e(route('private.contacts.verify', ':contactid')); ?>`.replace(':contactid', contactId), {
            method: 'PATCH',
            headers: {
                'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert(data.message);
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            alert('Une erreur est survenue');
        });
    }
}

// Basculer la visibilité
function toggleVisibility(contactId) {
    const action = <?php echo e($contact->visible_public ? 'false' : 'true'); ?>;
    const message = "<?php echo e($contact->visible_public ? 'rendre ce contact privé' : 'rendre ce contact public'); ?>";

    if (confirm(`Voulez-vous ${message} ?`)) {
        fetch(`<?php echo e(route('private.contacts.update-visibility', ':contactid')); ?>`.replace('contactid', contactId), {
            method: 'PATCH',
            headers: {
                'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                visible_public: action
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert(data.message);
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            alert('Une erreur est survenue');
        });
    }
}

// Suppression d'un contact
function deleteContact(contactId) {
    if (confirm('Êtes-vous sûr de vouloir supprimer ce contact ? Cette action est irréversible.')) {
        fetch(`<?php echo e(route('private.contacts.destroy', ':contactid')); ?>`.replace(':contactid', contactId), {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.href = '<?php echo e(route("private.contacts.index")); ?>';
            } else {
                alert(data.message);
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            alert('Une erreur est survenue');
        });
    }
}

// Afficher QR Code
function showQRCode(contactId) {
    window.open(`/private/contacts/${contactId}/qr-code`, '_blank', 'width=400,height=400');
}
</script>
<?php $__env->stopPush(); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.private.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Dell\Desktop\MICRISERVICES\methodiste_belle_ville\resources\views/components/private/contacts/show.blade.php ENDPATH**/ ?>