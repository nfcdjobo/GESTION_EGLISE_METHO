<?php $__env->startSection('title', 'Créer une Transaction'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-8">
    <!-- Page Title & Breadcrumb -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent">Créer une Nouvelle Transaction</h1>
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
                        <span class="text-sm font-medium text-slate-500">Créer</span>
                    </div>
                </li>
            </ol>
        </nav>
    </div>

    <form action="<?php echo e(route('private.fonds.store')); ?>" method="POST" id="transactionForm" class="space-y-8">
        <?php echo csrf_field(); ?>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Informations principales -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Informations de base -->
                <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                    <div class="flex flex-col p-6 border-b border-slate-200 sm:flex-row sm:items-center sm:justify-between gap-4">
                        <h2 class="text-xl font-bold text-slate-800 flex items-center">
                            <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                            Informations de Base
                        </h2>
                        <div class="flex flex-wrap gap-2">

                            <a href="<?php echo e(route('private.fonds.dashboard')); ?>" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-green-600 to-emerald-600 text-white text-sm font-medium rounded-xl hover:from-green-700 hover:to-emerald-700 transition-all duration-200 shadow-md hover:shadow-lg">
                                <i class="fas fa-tachometer-alt mr-2"></i> Tableau de Bord
                            </a>
                            <a href="<?php echo e(route('private.fonds.statistics')); ?>" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-cyan-600 to-blue-600 text-white text-sm font-medium rounded-xl hover:from-cyan-700 hover:to-blue-700 transition-all duration-200 shadow-md hover:shadow-lg">
                                <i class="fas fa-chart-bar mr-2"></i> Statistiques
                            </a>
                            <a href="<?php echo e(route('private.fonds.analytics')); ?>" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-amber-600 to-orange-600 text-white text-sm font-medium rounded-xl hover:from-amber-700 hover:to-orange-700 transition-all duration-200 shadow-md hover:shadow-lg">
                                <i class="fas fa-chart-line mr-2"></i> Analytics
                            </a>

                        </div>
                    </div>
                    <div class="p-6 space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="type_transaction" class="block text-sm font-medium text-slate-700 mb-2">
                                    Type de transaction <span class="text-red-500">*</span>
                                </label>
                                <select id="type_transaction" name="type_transaction" required
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors <?php $__errorArgs = ['type_transaction'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 focus:ring-red-500 focus:border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                    <option value="">Sélectionner le type</option>
                                    <option value="dime" <?php echo e(old('type_transaction') == 'dime' ? 'selected' : ''); ?>>Dîme</option>
                                    <option value="offrande_libre" <?php echo e(old('type_transaction') == 'offrande_libre' ? 'selected' : ''); ?>>Offrande libre</option>
                                    <option value="offrande_ordinaire" <?php echo e(old('type_transaction') == 'offrande_ordinaire' ? 'selected' : ''); ?>>Offrande ordinaire</option>
                                    <option value="offrande_speciale" <?php echo e(old('type_transaction') == 'offrande_speciale' ? 'selected' : ''); ?>>Offrande spéciale</option>
                                    <option value="offrande_mission" <?php echo e(old('type_transaction') == 'offrande_mission' ? 'selected' : ''); ?>>Offrande mission</option>
                                    <option value="offrande_construction" <?php echo e(old('type_transaction') == 'offrande_construction' ? 'selected' : ''); ?>>Offrande construction</option>
                                    <option value="don_special" <?php echo e(old('type_transaction') == 'don_special' ? 'selected' : ''); ?>>Don spécial</option>
                                    <option value="soutien_pasteur" <?php echo e(old('type_transaction') == 'soutien_pasteur' ? 'selected' : ''); ?>>Soutien pasteur</option>
                                    <option value="frais_ceremonie" <?php echo e(old('type_transaction') == 'frais_ceremonie' ? 'selected' : ''); ?>>Frais cérémonie</option>
                                    <option value="don_materiel" <?php echo e(old('type_transaction') == 'don_materiel' ? 'selected' : ''); ?>>Don matériel</option>
                                    <option value="autres" <?php echo e(old('type_transaction') == 'autres' ? 'selected' : ''); ?>>Autres</option>
                                </select>
                                <?php $__errorArgs = ['type_transaction'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <p class="mt-2 text-sm text-red-600"><?php echo e($message); ?></p>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <div>
                                <label for="categorie" class="block text-sm font-medium text-slate-700 mb-2">
                                    Catégorie <span class="text-red-500">*</span>
                                </label>
                                <select id="categorie" name="categorie" required
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors <?php $__errorArgs = ['categorie'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 focus:ring-red-500 focus:border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                    <option value="reguliere" <?php echo e(old('categorie', 'reguliere') == 'reguliere' ? 'selected' : ''); ?>>Régulière</option>
                                    <option value="exceptionnelle" <?php echo e(old('categorie') == 'exceptionnelle' ? 'selected' : ''); ?>>Exceptionnelle</option>
                                    <option value="urgente" <?php echo e(old('categorie') == 'urgente' ? 'selected' : ''); ?>>Urgente</option>
                                </select>
                                <?php $__errorArgs = ['categorie'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <p class="mt-2 text-sm text-red-600"><?php echo e($message); ?></p>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label for="date_transaction" class="block text-sm font-medium text-slate-700 mb-2">
                                    Date de transaction <span class="text-red-500">*</span>
                                </label>
                                <input type="date" id="date_transaction" name="date_transaction" value="<?php echo e(old('date_transaction', now()->format('Y-m-d'))); ?>" required
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors <?php $__errorArgs = ['date_transaction'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 focus:ring-red-500 focus:border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                <?php $__errorArgs = ['date_transaction'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <p class="mt-2 text-sm text-red-600"><?php echo e($message); ?></p>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <div>
                                <label for="heure_transaction" class="block text-sm font-medium text-slate-700 mb-2">Heure</label>
                                <input type="time" id="heure_transaction" name="heure_transaction" value="<?php echo e(old('heure_transaction')); ?>"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors <?php $__errorArgs = ['heure_transaction'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 focus:ring-red-500 focus:border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                <?php $__errorArgs = ['heure_transaction'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <p class="mt-2 text-sm text-red-600"><?php echo e($message); ?></p>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <div>
                                <label for="culte_id" class="block text-sm font-medium text-slate-700 mb-2">Culte associé</label>
                                <select id="culte_id" name="culte_id"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors <?php $__errorArgs = ['culte_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 focus:ring-red-500 focus:border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                    <option value="">Aucun culte</option>
                                    <?php $__currentLoopData = $formData['cultes']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $culte): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($culte->id); ?>" <?php echo e(old('culte_id') == $culte->id ? 'selected' : ''); ?>>
                                            <?php echo e($culte->titre); ?> - <?php echo e($culte->date_culte->format('d/m/Y')); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <?php $__errorArgs = ['culte_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <p class="mt-2 text-sm text-red-600"><?php echo e($message); ?></p>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="montant" class="block text-sm font-medium text-slate-700 mb-2">
                                    Montant <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <input type="number" id="montant" name="montant" value="<?php echo e(old('montant')); ?>" required min="0.01" step="0.01"
                                        class="w-full pl-4 pr-20 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors <?php $__errorArgs = ['montant'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 focus:ring-red-500 focus:border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                        <select name="devise" class="border-none bg-transparent text-sm text-slate-600 focus:outline-none">
                                            <option value="XOF" <?php echo e(old('devise', 'XOF') == 'XOF' ? 'selected' : ''); ?>>XOF</option>
                                            <option value="EUR" <?php echo e(old('devise') == 'EUR' ? 'selected' : ''); ?>>EUR</option>
                                            <option value="USD" <?php echo e(old('devise') == 'USD' ? 'selected' : ''); ?>>USD</option>
                                        </select>
                                    </div>
                                </div>
                                <?php $__errorArgs = ['montant'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <p class="mt-2 text-sm text-red-600"><?php echo e($message); ?></p>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <div>
                                <label for="mode_paiement" class="block text-sm font-medium text-slate-700 mb-2">
                                    Mode de paiement <span class="text-red-500">*</span>
                                </label>
                                <select id="mode_paiement" name="mode_paiement" required
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors <?php $__errorArgs = ['mode_paiement'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 focus:ring-red-500 focus:border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                    <option value="">Sélectionner le mode</option>
                                    <option value="especes" <?php echo e(old('mode_paiement', 'especes') == 'especes' ? 'selected' : ''); ?>>Espèces</option>
                                    <option value="mobile_money" <?php echo e(old('mode_paiement') == 'mobile_money' ? 'selected' : ''); ?>>Mobile Money</option>
                                    <option value="virement" <?php echo e(old('mode_paiement') == 'virement' ? 'selected' : ''); ?>>Virement bancaire</option>
                                    <option value="cheque" <?php echo e(old('mode_paiement') == 'cheque' ? 'selected' : ''); ?>>Chèque</option>
                                    <option value="nature" <?php echo e(old('mode_paiement') == 'nature' ? 'selected' : ''); ?>>Don en nature</option>
                                </select>
                                <?php $__errorArgs = ['mode_paiement'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <p class="mt-2 text-sm text-red-600"><?php echo e($message); ?></p>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </div>

                        <div>
                            <label for="reference_paiement" class="block text-sm font-medium text-slate-700 mb-2">Référence de paiement</label>
                            <input type="text" id="reference_paiement" name="reference_paiement" value="<?php echo e(old('reference_paiement')); ?>" placeholder="Numéro de transaction, chèque, etc."
                                class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors <?php $__errorArgs = ['reference_paiement'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 focus:ring-red-500 focus:border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                            <?php $__errorArgs = ['reference_paiement'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="mt-2 text-sm text-red-600"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
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
                    <div class="p-6 space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-4">
                                <div class="flex items-center">
                                    <input type="checkbox" id="est_anonyme" name="est_anonyme" value="1" <?php echo e(old('est_anonyme') ? 'checked' : ''); ?>

                                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                    <label for="est_anonyme" class="ml-2 text-sm font-medium text-slate-700">
                                        Don anonyme
                                    </label>
                                </div>

                                <div class="flex items-center">
                                    <input type="checkbox" id="est_membre" name="est_membre" value="1" <?php echo e(old('est_membre', true) ? 'checked' : ''); ?>

                                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                    <label for="est_membre" class="ml-2 text-sm font-medium text-slate-700">
                                        Donateur membre de l'église
                                    </label>
                                </div>
                            </div>

                            <div>
                                <label for="collecteur_id" class="block text-sm font-medium text-slate-700 mb-2">Collecteur</label>
                                <select id="collecteur_id" name="collecteur_id"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors <?php $__errorArgs = ['collecteur_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 focus:ring-red-500 focus:border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                    <option value="">Sélectionner un collecteur</option>
                                    <?php $__currentLoopData = $formData['collecteurs']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $collecteur): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($collecteur->id); ?>" <?php echo e(old('collecteur_id', auth()->id()) == $collecteur->id ? 'selected' : ''); ?>>
                                            <?php echo e($collecteur->nom); ?> <?php echo e($collecteur->prenom); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <?php $__errorArgs = ['collecteur_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <p class="mt-2 text-sm text-red-600"><?php echo e($message); ?></p>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </div>

                        <div id="donateur_section" class="space-y-4">
                            <div>
                                <label for="donateur_id" class="block text-sm font-medium text-slate-700 mb-2">Donateur (membre)</label>
                                <select id="donateur_id" name="donateur_id"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors <?php $__errorArgs = ['donateur_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 focus:ring-red-500 focus:border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                    <option value="">Sélectionner un donateur</option>
                                    <?php $__currentLoopData = $formData['donateurs']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $donateur): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($donateur->id); ?>" <?php echo e(old('donateur_id') == $donateur->id ? 'selected' : ''); ?>>
                                            <?php echo e($donateur->nom); ?> <?php echo e($donateur->prenom); ?> - <?php echo e($donateur->email); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <?php $__errorArgs = ['donateur_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <p class="mt-2 text-sm text-red-600"><?php echo e($message); ?></p>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <div id="donateur_externe_section" class="grid grid-cols-1 md:grid-cols-2 gap-4 hidden">
                                <div>
                                    <label for="nom_donateur_anonyme" class="block text-sm font-medium text-slate-700 mb-2">Nom du donateur</label>
                                    <input type="text" id="nom_donateur_anonyme" name="nom_donateur_anonyme" value="<?php echo e(old('nom_donateur_anonyme')); ?>" placeholder="Nom et prénom"
                                        class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors <?php $__errorArgs = ['nom_donateur_anonyme'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 focus:ring-red-500 focus:border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                    <?php $__errorArgs = ['nom_donateur_anonyme'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <p class="mt-2 text-sm text-red-600"><?php echo e($message); ?></p>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>

                                <div>
                                    <label for="contact_donateur" class="block text-sm font-medium text-slate-700 mb-2">Contact</label>
                                    <input type="text" id="contact_donateur" name="contact_donateur" value="<?php echo e(old('contact_donateur')); ?>" placeholder="Téléphone ou email"
                                        class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors <?php $__errorArgs = ['contact_donateur'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 focus:ring-red-500 focus:border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                    <?php $__errorArgs = ['contact_donateur'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <p class="mt-2 text-sm text-red-600"><?php echo e($message); ?></p>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Don en nature -->
                <div id="don_nature_section" class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300 hidden">
                    <div class="p-6 border-b border-slate-200">
                        <h2 class="text-xl font-bold text-slate-800 flex items-center">
                            <i class="fas fa-gift text-purple-600 mr-2"></i>
                            Détails du Don en Nature
                        </h2>
                    </div>
                    <div class="p-6 space-y-6">
                        <div>
                            <label for="description_don_nature" class="block text-sm font-medium text-slate-700 mb-2">
                                Description du don <span class="text-red-500">*</span>
                            </label>
                            <div class="<?php $__errorArgs = ['description_don_nature'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> has-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                <textarea id="description_don_nature" name="description_don_nature" rows="3" placeholder="Description détaillée du don en nature"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors resize-none"><?php echo e(old('description_don_nature')); ?></textarea>
                            </div>
                            <?php $__errorArgs = ['description_don_nature'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="mt-2 text-sm text-red-600"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div>
                            <label for="valeur_estimee" class="block text-sm font-medium text-slate-700 mb-2">Valeur estimée</label>
                            <input type="number" id="valeur_estimee" name="valeur_estimee" value="<?php echo e(old('valeur_estimee')); ?>" min="0" step="0.01" placeholder="Valeur en XOF"
                                class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors <?php $__errorArgs = ['valeur_estimee'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 focus:ring-red-500 focus:border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                            <?php $__errorArgs = ['valeur_estimee'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="mt-2 text-sm text-red-600"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>
                </div>

                <!-- Affectation et destination -->
                <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                    <div class="p-6 border-b border-slate-200">
                        <h2 class="text-xl font-bold text-slate-800 flex items-center">
                            <i class="fas fa-target text-amber-600 mr-2"></i>
                            Affectation et Destination
                        </h2>
                    </div>
                    <div class="p-6 space-y-6">
                        <div class="flex items-center">
                            <input type="checkbox" id="est_flechee" name="est_flechee" value="1" <?php echo e(old('est_flechee') ? 'checked' : ''); ?>

                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                            <label for="est_flechee" class="ml-2 text-sm font-medium text-slate-700">
                                Offrande fléchée pour un usage spécifique
                            </label>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="destination" class="block text-sm font-medium text-slate-700 mb-2">Destination</label>
                                <input type="text" id="destination" name="destination" value="<?php echo e(old('destination')); ?>" placeholder="Projet ou usage spécifique"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors <?php $__errorArgs = ['destination'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 focus:ring-red-500 focus:border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                <?php $__errorArgs = ['destination'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <p class="mt-2 text-sm text-red-600"><?php echo e($message); ?></p>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <div>
                                <label for="projet_id" class="block text-sm font-medium text-slate-700 mb-2">Projet bénéficiaire</label>
                                <select id="projet_id" name="projet_id"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors <?php $__errorArgs = ['projet_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 focus:ring-red-500 focus:border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                    <option value="">Aucun projet spécifique</option>
                                    <?php $__currentLoopData = $formData['projets']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $projet): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($projet->id); ?>" <?php echo e(old('projet_id') == $projet->id ? 'selected' : ''); ?>>
                                            <?php echo e($projet->nom_projet); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <?php $__errorArgs = ['projet_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <p class="mt-2 text-sm text-red-600"><?php echo e($message); ?></p>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </div>

                        <div>
                            <label for="instructions_donateur" class="block text-sm font-medium text-slate-700 mb-2">Instructions particulières du donateur</label>
                            <div class="<?php $__errorArgs = ['instructions_donateur'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> has-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                <textarea id="instructions_donateur" name="instructions_donateur" rows="3" placeholder="Instructions ou souhaits particuliers du donateur"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors resize-none"><?php echo e(old('instructions_donateur')); ?></textarea>
                            </div>
                            <?php $__errorArgs = ['instructions_donateur'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="mt-2 text-sm text-red-600"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>
                </div>

                <!-- Options avancées -->
                <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                    <div class="p-6 border-b border-slate-200">
                        <h2 class="text-xl font-bold text-slate-800 flex items-center">
                            <i class="fas fa-cogs text-cyan-600 mr-2"></i>
                            Options Avancées
                        </h2>
                    </div>
                    <div class="p-6 space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-4">
                                <div class="flex items-center">
                                    <input type="checkbox" id="recu_demande" name="recu_demande" value="1" <?php echo e(old('recu_demande') ? 'checked' : ''); ?>

                                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                    <label for="recu_demande" class="ml-2 text-sm font-medium text-slate-700">
                                        Reçu fiscal demandé
                                    </label>
                                </div>

                                <div class="flex items-center">
                                    <input type="checkbox" id="deductible_impots" name="deductible_impots" value="1" <?php echo e(old('deductible_impots', true) ? 'checked' : ''); ?>

                                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                    <label for="deductible_impots" class="ml-2 text-sm font-medium text-slate-700">
                                        Don déductible des impôts
                                    </label>
                                </div>

                                <div class="flex items-center">
                                    <input type="checkbox" id="est_recurrente" name="est_recurrente" value="1" <?php echo e(old('est_recurrente') ? 'checked' : ''); ?>

                                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                    <label for="est_recurrente" class="ml-2 text-sm font-medium text-slate-700">
                                        Transaction récurrente
                                    </label>
                                </div>
                            </div>

                            <div id="recurrence_section" class="space-y-4 hidden">
                                <div>
                                    <label for="frequence_recurrence" class="block text-sm font-medium text-slate-700 mb-2">Fréquence</label>
                                    <select id="frequence_recurrence" name="frequence_recurrence"
                                        class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                        <option value="">Sélectionner la fréquence</option>
                                        <option value="hebdomadaire" <?php echo e(old('frequence_recurrence') == 'hebdomadaire' ? 'selected' : ''); ?>>Hebdomadaire</option>
                                        <option value="mensuelle" <?php echo e(old('frequence_recurrence') == 'mensuelle' ? 'selected' : ''); ?>>Mensuelle</option>
                                        <option value="trimestrielle" <?php echo e(old('frequence_recurrence') == 'trimestrielle' ? 'selected' : ''); ?>>Trimestrielle</option>
                                        <option value="annuelle" <?php echo e(old('frequence_recurrence') == 'annuelle' ? 'selected' : ''); ?>>Annuelle</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="occasion_speciale" class="block text-sm font-medium text-slate-700 mb-2">Occasion spéciale</label>
                                <input type="text" id="occasion_speciale" name="occasion_speciale" value="<?php echo e(old('occasion_speciale')); ?>" placeholder="Noël, Pâques, anniversaire..."
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            </div>

                            <div>
                                <label for="lieu_collecte" class="block text-sm font-medium text-slate-700 mb-2">Lieu de collecte</label>
                                <input type="text" id="lieu_collecte" name="lieu_collecte" value="<?php echo e(old('lieu_collecte', 'Église principale')); ?>" placeholder="Lieu où la collecte a été effectuée"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar - Aperçu et aide -->
            <div class="space-y-6">
                <!-- Aperçu -->
                <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                    <div class="p-6 border-b border-slate-200">
                        <h2 class="text-xl font-bold text-slate-800 flex items-center">
                            <i class="fas fa-eye text-purple-600 mr-2"></i>
                            Aperçu
                        </h2>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-slate-700">Type:</span>
                            <span id="preview-type" class="text-sm text-slate-900 font-semibold">-</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-slate-700">Montant:</span>
                            <span id="preview-montant" class="text-sm text-slate-600">-</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-slate-700">Date:</span>
                            <span id="preview-date" class="text-sm text-slate-600">-</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-slate-700">Mode:</span>
                            <span id="preview-mode" class="text-sm text-slate-600">-</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-slate-700">Donateur:</span>
                            <span id="preview-donateur" class="text-sm text-slate-600">-</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-slate-700">Statut:</span>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">En attente</span>
                        </div>
                    </div>
                </div>

                <!-- Guide des types -->
                <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                    <div class="p-6 border-b border-slate-200">
                        <h2 class="text-xl font-bold text-slate-800 flex items-center">
                            <i class="fas fa-info text-green-600 mr-2"></i>
                            Guide des Types
                        </h2>
                    </div>
                    <div class="p-6 space-y-3 text-sm">
                        <div><strong>Dîme:</strong> 10% des revenus</div>
                        <div><strong>Offrande libre:</strong> Don volontaire</div>
                        <div><strong>Offrande spéciale:</strong> Événements particuliers</div>
                        <div><strong>Don matériel:</strong> Bien en nature</div>
                        <div><strong>Soutien pasteur:</strong> Aide au ministère</div>
                    </div>
                </div>

                <!-- Informations importantes -->
                <div class="bg-gradient-to-br from-blue-50 to-cyan-50 rounded-2xl shadow-lg border border-blue-200 p-6">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <i class="fas fa-info-circle text-blue-600 text-lg"></i>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-semibold text-blue-900">Informations importantes</h3>
                            <ul class="mt-2 text-xs text-blue-800 space-y-1">
                                <li>• Les transactions sont en attente par défaut</li>
                                <li>• Un reçu peut être généré après validation</li>
                                <li>• Les dons récurrents créent des échéances automatiques</li>
                                <li>• Les dons matériels nécessitent une description</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20">
            <div class="p-6">
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <button type="submit" class="inline-flex items-center justify-center px-8 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-medium rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-save mr-2"></i> Créer la Transaction
                    </button>
                    <a href="<?php echo e(route('private.fonds.index')); ?>" class="inline-flex items-center justify-center px-8 py-3 bg-slate-600 text-white font-medium rounded-xl hover:bg-slate-700 transition-colors">
                        <i class="fas fa-times mr-2"></i> Annuler
                    </a>
                </div>
            </div>
        </div>
    </form>
</div>


<?php echo $__env->make('partials.ckeditor-resources', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
// Mise à jour de l'aperçu en temps réel
function updatePreview() {
    const typeSelect = document.getElementById('type_transaction');
    const type = typeSelect.options[typeSelect.selectedIndex]?.text || '-';
    const montant = document.getElementById('montant').value || '0';
    const devise = document.querySelector('select[name="devise"]').value || 'XOF';
    const date = document.getElementById('date_transaction').value || '-';
    const modeSelect = document.getElementById('mode_paiement');
    const mode = modeSelect.options[modeSelect.selectedIndex]?.text || '-';

    let donateur = '-';
    if (document.getElementById('est_anonyme').checked) {
        donateur = 'Anonyme';
    } else {
        const donateurSelect = document.getElementById('donateur_id');
        const nomAnonyme = document.getElementById('nom_donateur_anonyme').value;
        if (donateurSelect.value) {
            donateur = donateurSelect.options[donateurSelect.selectedIndex]?.text.split(' - ')[0] || '-';
        } else if (nomAnonyme) {
            donateur = nomAnonyme;
        }
    }

    document.getElementById('preview-type').textContent = type;
    document.getElementById('preview-montant').textContent = montant ? number_format(montant) + ' ' + devise : '-';
    document.getElementById('preview-date').textContent = date !== '-' ? new Date(date).toLocaleDateString('fr-FR') : '-';
    document.getElementById('preview-mode').textContent = mode;
    document.getElementById('preview-donateur').textContent = donateur;
}

// Gestion des sections conditionnelles
function toggleDonNatureSection() {
    const typeTransaction = document.getElementById('type_transaction').value;
    const modePaiement = document.getElementById('mode_paiement').value;
    const section = document.getElementById('don_nature_section');

    if (typeTransaction === 'don_materiel' || modePaiement === 'nature') {
        section.classList.remove('hidden');
        document.getElementById('description_don_nature').required = true;
    } else {
        section.classList.add('hidden');
        document.getElementById('description_don_nature').required = false;
    }
}

function toggleDonateurSection() {
    const estAnonyme = document.getElementById('est_anonyme').checked;
    const estMembre = document.getElementById('est_membre').checked;
    const donateurSection = document.getElementById('donateur_section');
    const externeSection = document.getElementById('donateur_externe_section');
    const donateurSelect = document.getElementById('donateur_id');

    if (estAnonyme) {
        donateurSelect.style.display = 'none';
        externeSection.classList.add('hidden');
        donateurSelect.value = '';
    } else if (estMembre) {
        donateurSelect.style.display = 'block';
        externeSection.classList.add('hidden');
    } else {
        donateurSelect.style.display = 'none';
        externeSection.classList.remove('hidden');
        donateurSelect.value = '';
    }
}

function toggleRecurrenceSection() {
    const estRecurrente = document.getElementById('est_recurrente').checked;
    const section = document.getElementById('recurrence_section');
    const frequenceSelect = document.getElementById('frequence_recurrence');

    if (estRecurrente) {
        section.classList.remove('hidden');
        frequenceSelect.required = true;
    } else {
        section.classList.add('hidden');
        frequenceSelect.required = false;
        frequenceSelect.value = '';
    }
}

// Fonction de formatage des nombres
function number_format(number) {
    return new Intl.NumberFormat('fr-FR').format(number);
}

// Événements pour la mise à jour de l'aperçu
['type_transaction', 'montant', 'date_transaction', 'mode_paiement', 'donateur_id'].forEach(id => {
    const element = document.getElementById(id);
    if (element) {
        element.addEventListener('input', updatePreview);
        element.addEventListener('change', updatePreview);
    }
});

document.querySelector('select[name="devise"]').addEventListener('change', updatePreview);
document.getElementById('nom_donateur_anonyme').addEventListener('input', updatePreview);

// Événements pour les sections conditionnelles
document.getElementById('type_transaction').addEventListener('change', function() {
    toggleDonNatureSection();
    updatePreview();
});

document.getElementById('mode_paiement').addEventListener('change', function() {
    toggleDonNatureSection();
    updatePreview();
});

document.getElementById('est_anonyme').addEventListener('change', function() {
    toggleDonateurSection();
    updatePreview();
});

document.getElementById('est_membre').addEventListener('change', function() {
    toggleDonateurSection();
    updatePreview();
});

document.getElementById('est_recurrente').addEventListener('change', toggleRecurrenceSection);

// Validation du formulaire
document.getElementById('transactionForm').addEventListener('submit', function(e) {
    // Synchroniser tous les éditeurs CKEditor avant validation
    if (window.CKEditorInstances) {
        Object.values(window.CKEditorInstances).forEach(editor => {
            const element = editor.sourceElement;
            if (element) {
                element.value = editor.getData();
            }
        });
    }

    const typeTransaction = document.getElementById('type_transaction').value;
    const montant = document.getElementById('montant').value;
    const modePaiement = document.getElementById('mode_paiement').value;

    if (!typeTransaction || !montant || !modePaiement) {
        e.preventDefault();
        alert('Veuillez remplir tous les champs obligatoires.');
        return false;
    }

    if (parseFloat(montant) <= 0) {
        e.preventDefault();
        alert('Le montant doit être positif.');
        return false;
    }

    // Validation spécifique don matériel
    if ((typeTransaction === 'don_materiel' || modePaiement === 'nature') &&
        !document.getElementById('description_don_nature').value.trim()) {
        e.preventDefault();
        alert('La description est obligatoire pour un don matériel.');
        return false;
    }

    // Validation récurrence
    if (document.getElementById('est_recurrente').checked &&
        !document.getElementById('frequence_recurrence').value) {
        e.preventDefault();
        alert('Veuillez sélectionner une fréquence de récurrence.');
        return false;
    }

    // Validation donateur
    const estAnonyme = document.getElementById('est_anonyme').checked;
    const estMembre = document.getElementById('est_membre').checked;
    const donateurId = document.getElementById('donateur_id').value;
    const nomAnonyme = document.getElementById('nom_donateur_anonyme').value;

    if (!estAnonyme && estMembre && !donateurId) {
        e.preventDefault();
        alert('Veuillez sélectionner un donateur membre.');
        return false;
    }

    if (!estAnonyme && !estMembre && !nomAnonyme.trim()) {
        e.preventDefault();
        alert('Veuillez saisir le nom du donateur externe.');
        return false;
    }
});

// Initialisation
document.addEventListener('DOMContentLoaded', function() {
    toggleDonNatureSection();
    toggleDonateurSection();
    toggleRecurrenceSection();
    updatePreview();
});
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.private.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Dell\Desktop\MICRISERVICES\methodiste_belle_ville\resources\views/components/private/fonds/create.blade.php ENDPATH**/ ?>