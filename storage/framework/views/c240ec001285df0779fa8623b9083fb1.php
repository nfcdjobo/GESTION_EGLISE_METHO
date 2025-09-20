<?php $__env->startSection('title', 'Modifier la classe - ' . $classe->nom); ?>

<?php $__env->startSection('content'); ?>
    <div class="space-y-8">
        <!-- En-tête de page -->
        <div class="mb-8">
            <div class="flex items-center space-x-4 mb-4">
                <a href="<?php echo e(route('private.classes.show', $classe)); ?>"
                   class="inline-flex items-center text-slate-600 hover:text-slate-900 transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Retour à la classe
                </a>
            </div>
            <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent">
                Modifier la classe
            </h1>
            <p class="text-slate-500 mt-1">
                Modification de "<?php echo e($classe->nom); ?>" - <?php echo e($classe->nombre_inscrits); ?> membre(s) inscrit(s)
            </p>
        </div>

        <!-- Formulaire de modification -->
        <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
            <div class="p-6 border-b border-slate-200">
                <h2 class="text-xl font-bold text-slate-800 flex items-center">
                    <i class="fas fa-edit text-yellow-600 mr-2"></i>
                    Modifier les informations
                </h2>
            </div>

            <form action="<?php echo e(route('private.classes.update', $classe)); ?>" method="POST" enctype="multipart/form-data" class="p-6">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Colonne gauche - Informations de base -->
                    <div class="space-y-6">
                        <!-- Nom de la classe -->
                        <div>
                            <label for="nom" class="block text-sm font-medium text-slate-700 mb-2">
                                Nom de la classe <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="nom" name="nom" value="<?php echo e(old('nom', $classe->nom)); ?>" required
                                class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors <?php $__errorArgs = ['nom'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                placeholder="Ex: Classe préparatoire A">
                            <?php $__errorArgs = ['nom'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <!-- Description -->
                        <div>
                            <label for="description" class="block text-sm font-medium text-slate-700 mb-2">
                                Description
                            </label>
                            <textarea id="description" name="description" rows="4"
                                class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                placeholder="Description détaillée de la classe..."><?php echo e(old('description', $classe->description)); ?></textarea>
                            <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <!-- Tranche d'âge -->
                        <div>
                            <label for="tranche_age" class="block text-sm font-medium text-slate-700 mb-2">
                                Tranche d'âge
                            </label>
                            <select id="tranche_age" name="tranche_age"
                                class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors <?php $__errorArgs = ['tranche_age'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                <option value="">Sélectionner une tranche d'âge</option>
                                <?php $__currentLoopData = $tranches_age; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tranche): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($tranche); ?>" <?php echo e(old('tranche_age', $classe->tranche_age) == $tranche ? 'selected' : ''); ?>>
                                        <?php echo e($tranche); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <?php $__errorArgs = ['tranche_age'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <!-- Âges spécifiques -->
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="age_minimum" class="block text-sm font-medium text-slate-700 mb-2">
                                    Âge minimum
                                </label>
                                <input type="number" id="age_minimum" name="age_minimum" value="<?php echo e(old('age_minimum', $classe->age_minimum)); ?>"
                                    min="0" max="120"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors <?php $__errorArgs = ['age_minimum'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                    placeholder="Ex: 6">
                                <?php $__errorArgs = ['age_minimum'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            <div>
                                <label for="age_maximum" class="block text-sm font-medium text-slate-700 mb-2">
                                    Âge maximum
                                </label>
                                <input type="number" id="age_maximum" name="age_maximum" value="<?php echo e(old('age_maximum', $classe->age_maximum)); ?>"
                                    min="0" max="120"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors <?php $__errorArgs = ['age_maximum'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                    placeholder="Ex: 12">
                                <?php $__errorArgs = ['age_maximum'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </div>

                        <!-- Image de la classe -->
                        <div>
                            <label for="image_classe" class="block text-sm font-medium text-slate-700 mb-2">
                                Image de la classe
                            </label>
                            <div class="border-2 border-dashed border-slate-300 rounded-xl p-6 text-center hover:border-slate-400 transition-colors">
                                <input type="file" id="image_classe" name="image_classe" accept="image/*"
                                    class="hidden" onchange="previewImage(this)">

                                <?php if($classe->image_classe): ?>
                                    <div id="current-image" class="mb-4">
                                        <img src="<?php echo e(asset('storage/' . $classe->image_classe)); ?>" alt="Image actuelle"
                                            class="mx-auto max-h-32 rounded-lg mb-3">
                                        <p class="text-sm text-slate-600 mb-2">Image actuelle</p>
                                        <button type="button" onclick="showUploadNew()"
                                            class="px-4 py-2 bg-blue-100 text-blue-600 rounded-lg hover:bg-blue-200 transition-colors">
                                            Changer l'image
                                        </button>
                                    </div>
                                <?php endif; ?>

                                <div id="image-preview" class="hidden">
                                    <img id="preview-img" src="" alt="Aperçu" class="mx-auto max-h-32 rounded-lg mb-3">
                                    <button type="button" onclick="removeImage()"
                                        class="text-red-600 hover:text-red-800 text-sm">
                                        <i class="fas fa-trash mr-1"></i> Supprimer
                                    </button>
                                </div>

                                <div id="upload-placeholder" class="<?php echo e($classe->image_classe ? 'hidden' : ''); ?>">
                                    <i class="fas fa-cloud-upload-alt text-3xl text-slate-400 mb-3"></i>
                                    <p class="text-slate-600 mb-2">Cliquez pour ajouter une image</p>
                                    <button type="button" onclick="document.getElementById('image_classe').click()"
                                        class="px-4 py-2 bg-blue-100 text-blue-600 rounded-lg hover:bg-blue-200 transition-colors">
                                        Choisir un fichier
                                    </button>
                                </div>
                            </div>
                            <?php $__errorArgs = ['image_classe'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>

                    <!-- Colonne droite - Responsables et programme -->
                    <div class="space-y-6">
                        <!-- Responsables -->
                        <!-- Responsables -->
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-4">
                                Responsables de la classe
                            </label>
                            <div id="responsables-container" class="space-y-4">
                                <!-- Les responsables existants seront ajoutés dynamiquement -->
                            </div>

                            <button type="button" onclick="addResponsable()"
                                class="mt-3 inline-flex items-center px-4 py-2 bg-green-100 text-green-700 rounded-lg hover:bg-green-200 transition-colors">
                                <i class="fas fa-plus mr-2"></i> Ajouter un responsable
                            </button>

                            <?php if($utilisateurs->isEmpty()): ?>
                                <div class="mt-3 p-3 bg-amber-50 border border-amber-200 rounded-lg">
                                    <div class="flex items-center">
                                        <i class="fas fa-exclamation-triangle text-amber-600 mr-2"></i>
                                        <span class="text-sm text-amber-700">
                                            Aucun utilisateur disponible pour être responsable. Tous les utilisateurs sont déjà
                                            inscrits dans des classes.
                                        </span>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Programme -->
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-4">
                                Programme de la classe
                            </label>
                            <div id="programme-container" class="space-y-3">
                                <?php if($classe->programme && count($classe->programme) > 0): ?>
                                    <?php $__currentLoopData = $classe->programme; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $element): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="programme-item flex gap-3">
                                            <input type="text" name="programme[]" value="<?php echo e(old("programme.{$index}", $element)); ?>"
                                                class="flex-1 px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                                placeholder="Ex: Mathématiques de base">
                                            <button type="button" onclick="removeProgrammeItem(this)"
                                                class="w-10 h-10 text-red-600 bg-red-100 rounded-lg hover:bg-red-200 transition-colors flex items-center justify-center">
                                                <i class="fas fa-trash text-sm"></i>
                                            </button>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php else: ?>
                                    <!-- Template par défaut si aucun programme -->
                                    <div class="programme-item flex gap-3">
                                        <input type="text" name="programme[]"
                                            class="flex-1 px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                            placeholder="Ex: Mathématiques de base">
                                        <button type="button" onclick="removeProgrammeItem(this)"
                                            class="w-10 h-10 text-red-600 bg-red-100 rounded-lg hover:bg-red-200 transition-colors flex items-center justify-center">
                                            <i class="fas fa-trash text-sm"></i>
                                        </button>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <button type="button" onclick="addProgrammeItem()"
                                class="mt-3 inline-flex items-center px-4 py-2 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition-colors">
                                <i class="fas fa-plus mr-2"></i> Ajouter un élément
                            </button>
                        </div>

                        <!-- Statistiques actuelles (lecture seule) -->
                        <div class="bg-blue-50 p-4 rounded-xl border border-blue-200">
                            <h3 class="text-sm font-medium text-blue-900 mb-3">Statistiques actuelles</h3>
                            <div class="grid grid-cols-2 gap-4 text-sm">
                                <div>
                                    <span class="text-blue-700">Membres inscrits:</span>
                                    <span class="font-medium text-blue-900"><?php echo e($classe->nombre_inscrits); ?></span>
                                </div>
                                <div>
                                    <span class="text-blue-700">Responsables:</span>
                                    <span class="font-medium text-blue-900"><?php echo e(count($classe->responsables ?? [])); ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Boutons d'action -->
                <div class="flex items-center justify-between mt-8 pt-6 border-t border-slate-200">
                    <div class="flex items-center space-x-3">
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('classes.duplicate')): ?>
                            <form action="<?php echo e(route('private.classes.duplicate', $classe)); ?>" method="POST" class="inline">
                                <?php echo csrf_field(); ?>
                                <button type="submit"
                                    class="inline-flex items-center px-4 py-2 bg-cyan-100 text-cyan-700 rounded-xl hover:bg-cyan-200 transition-colors">
                                    <i class="fas fa-copy mr-2"></i> Dupliquer
                                </button>
                            </form>
                        <?php endif; ?>

                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('classes.archive')): ?>
                            <form action="<?php echo e(route('private.classes.archive', $classe)); ?>" method="POST" class="inline">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('PATCH'); ?>
                                <button type="submit" onclick="return confirm('Êtes-vous sûr de vouloir archiver cette classe ?')"
                                    class="inline-flex items-center px-4 py-2 bg-amber-100 text-amber-700 rounded-xl hover:bg-amber-200 transition-colors">
                                    <i class="fas fa-archive mr-2"></i> Archiver
                                </button>
                            </form>
                        <?php endif; ?>
                    </div>

                    <div class="flex items-center space-x-4">
                        <a href="<?php echo e(route('private.classes.show', $classe)); ?>"
                            class="px-6 py-3 text-slate-700 bg-slate-100 rounded-xl hover:bg-slate-200 transition-colors">
                            Annuler
                        </a>
                        <button type="submit"
                            class="px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow-md hover:shadow-lg">
                            <i class="fas fa-save mr-2"></i> Enregistrer les modifications
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Scripts JavaScript -->
    

    <script>
let responsableIndex = <?php echo e(count($classe->responsables ?? []) + 1); ?>;
let programmeIndex = <?php echo e(count($classe->programme ?? [])); ?>;
let selectedUsers = new Set(); // Pour suivre les utilisateurs sélectionnés
let allUsers = <?php echo json_encode($utilisateurs, 15, 512) ?>; // Tous les utilisateurs disponibles
let existingResponsables = <?php echo json_encode($classe->responsables ?? [], 15, 512) ?>; // Responsables existants

// Initialiser les utilisateurs déjà sélectionnés
existingResponsables.forEach(responsable => {
    selectedUsers.add(responsable.id);
});

// Gestion de l'aperçu d'image
function previewImage(input) {
    const file = input.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('preview-img').src = e.target.result;
            document.getElementById('image-preview').classList.remove('hidden');
            document.getElementById('upload-placeholder').classList.add('hidden');
            document.getElementById('current-image')?.classList.add('hidden');
        };
        reader.readAsDataURL(file);
    }
}

function showUploadNew() {
    document.getElementById('current-image').classList.add('hidden');
    document.getElementById('upload-placeholder').classList.remove('hidden');
}

function removeImage() {
    document.getElementById('image_classe').value = '';
    document.getElementById('image-preview').classList.add('hidden');
    document.getElementById('upload-placeholder').classList.remove('hidden');
    document.getElementById('current-image')?.classList.remove('hidden');
}

// Vérifier et mettre à jour l'état du bouton "Ajouter un responsable"
function updateAddResponsableButton() {
    setTimeout(() => {
        const addButton = document.querySelector('button[onclick="addResponsable()"]');
        if (!addButton) {
            console.error('Bouton "Ajouter un responsable" non trouvé');
            return;
        }

        const availableUsers = allUsers.filter(user => !selectedUsers.has(user.id));

        if (availableUsers.length === 0) {
            addButton.disabled = true;
            addButton.style.opacity = '0.5';
            addButton.style.cursor = 'not-allowed';
            addButton.title = 'Tous les utilisateurs sont déjà sélectionnés';
            addButton.onmouseenter = null;
            addButton.onmouseleave = null;
        } else {
            addButton.disabled = false;
            addButton.style.opacity = '1';
            addButton.style.cursor = 'pointer';
            addButton.title = '';
            addButton.onmouseenter = function() {
                this.style.backgroundColor = 'rgb(187 247 208)';
            };
            addButton.onmouseleave = function() {
                this.style.backgroundColor = 'rgb(220 252 231)';
            };
        }
    }, 10);
}

// Créer un responsable existant
function createExistingResponsable(responsable, index) {
    const container = document.getElementById('responsables-container');

    const responsableDiv = document.createElement('div');
    responsableDiv.className = 'responsable-item bg-slate-50 p-4 rounded-xl border border-slate-200';

    // Trouver l'utilisateur correspondant
    const user = allUsers.find(u => u.id === responsable.id);
    const userName = user ? `${user.prenom} ${user.nom}` : 'Utilisateur non trouvé';

    responsableDiv.innerHTML = `
        <div class="grid grid-cols-12 gap-3 items-end">
            <div class="col-span-5">
                <label class="block text-xs font-medium text-slate-600 mb-1">Utilisateur</label>
                <div class="relative">
                    <input type="text"
                        class="user-search w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        placeholder="Rechercher un utilisateur..."
                        value="${userName}"
                        autocomplete="off"
                        onkeyup="searchUsers(this)"
                        onfocus="showUserDropdown(this)"
                        onblur="hideUserDropdown(this)">
                    <select name="responsables[${index}][id]" class="hidden user-select">
                        <option value="">Sélectionner un utilisateur</option>
                        <option value="${responsable.id}" selected>${userName}</option>
                    </select>
                    <button type="button" class="clear-user-btn absolute right-2 top-1/2 transform -translate-y-1/2 text-slate-400 hover:text-red-500" onclick="clearSelectedUser(this)" title="Désélectionner">
                        <i class="fas fa-times text-sm"></i>
                    </button>
                    <div class="user-dropdown absolute z-10 w-full bg-white border border-slate-300 rounded-lg shadow-lg mt-1 max-h-40 overflow-y-auto hidden">
                    </div>
                </div>
            </div>
            <div class="col-span-4">
                <label class="block text-xs font-medium text-slate-600 mb-1">Responsabilité</label>
                <select name="responsables[${index}][responsabilite]" class="w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Type de responsabilité</option>
                    <?php $__currentLoopData = $types_responsabilite; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($type); ?>" ${responsable.responsabilite === '<?php echo e($type); ?>' ? 'selected' : ''}><?php echo e(ucfirst($type)); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="col-span-2">
                <label class="block text-xs font-medium text-slate-600 mb-1">Supérieur</label>
                <div class="flex items-center justify-center">
                    <input type="checkbox" name="responsables[${index}][superieur]" value="1"
                        ${responsable.superieur ? 'checked' : ''}
                        class="w-4 h-4 text-blue-600 bg-white border-gray-300 rounded focus:ring-blue-500"
                        onchange="handleSuperieurChange(this)">
                </div>
            </div>
            <div class="col-span-1">
                <button type="button" onclick="removeResponsable(this)"
                    class="w-8 h-8 text-red-600 bg-red-100 rounded-lg hover:bg-red-200 transition-colors flex items-center justify-center">
                    <i class="fas fa-trash text-xs"></i>
                </button>
            </div>
        </div>
    `;

    container.appendChild(responsableDiv);
    initializeUserSearch(responsableDiv);
}

// Gestion des responsables avec recherche
function addResponsable() {
    const availableUsers = allUsers.filter(user => !selectedUsers.has(user.id));
    if (availableUsers.length === 0) {
        alert('Tous les utilisateurs disponibles sont déjà sélectionnés');
        return;
    }

    const container = document.getElementById('responsables-container');
    const responsableDiv = document.createElement('div');
    responsableDiv.className = 'responsable-item bg-slate-50 p-4 rounded-xl border border-slate-200';

    responsableDiv.innerHTML = `
        <div class="grid grid-cols-12 gap-3 items-end">
            <div class="col-span-5">
                <label class="block text-xs font-medium text-slate-600 mb-1">Utilisateur</label>
                <div class="relative">
                    <input type="text"
                        class="user-search w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        placeholder="Rechercher un utilisateur..."
                        autocomplete="off"
                        onkeyup="searchUsers(this)"
                        onfocus="showUserDropdown(this)"
                        onblur="hideUserDropdown(this)">
                    <select name="responsables[${responsableIndex}][id]" class="hidden user-select">
                        <option value="">Sélectionner un utilisateur</option>
                    </select>
                    <button type="button" class="clear-user-btn absolute right-2 top-1/2 transform -translate-y-1/2 text-slate-400 hover:text-red-500 hidden" onclick="clearSelectedUser(this)" title="Désélectionner">
                        <i class="fas fa-times text-sm"></i>
                    </button>
                    <div class="user-dropdown absolute z-10 w-full bg-white border border-slate-300 rounded-lg shadow-lg mt-1 max-h-40 overflow-y-auto hidden">
                    </div>
                </div>
            </div>
            <div class="col-span-4">
                <label class="block text-xs font-medium text-slate-600 mb-1">Responsabilité</label>
                <select name="responsables[${responsableIndex}][responsabilite]" class="w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Type de responsabilité</option>
                    <?php $__currentLoopData = $types_responsabilite; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($type); ?>"><?php echo e(ucfirst($type)); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="col-span-2">
                <label class="block text-xs font-medium text-slate-600 mb-1">Supérieur</label>
                <div class="flex items-center justify-center">
                    <input type="checkbox" name="responsables[${responsableIndex}][superieur]" value="1"
                        class="w-4 h-4 text-blue-600 bg-white border-gray-300 rounded focus:ring-blue-500"
                        onchange="handleSuperieurChange(this)">
                </div>
            </div>
            <div class="col-span-1">
                <button type="button" onclick="removeResponsable(this)"
                    class="w-8 h-8 text-red-600 bg-red-100 rounded-lg hover:bg-red-200 transition-colors flex items-center justify-center">
                    <i class="fas fa-trash text-xs"></i>
                </button>
            </div>
        </div>
    `;

    container.appendChild(responsableDiv);
    initializeUserSearch(responsableDiv);
    responsableIndex++;
    updateAddResponsableButton();
}

function removeResponsable(button) {
    const responsableItem = button.closest('.responsable-item');
    const userSelect = responsableItem.querySelector('.user-select');

    if (userSelect.value) {
        selectedUsers.delete(userSelect.value);
    }

    responsableItem.remove();
    updateAllUserDropdowns();
    updateAddResponsableButton();
}

function clearSelectedUser(button) {
    const responsableItem = button.closest('.responsable-item');
    const searchInput = responsableItem.querySelector('.user-search');
    const userSelect = responsableItem.querySelector('.user-select');
    const clearButton = button;

    if (userSelect.value) {
        selectedUsers.delete(userSelect.value);
    }

    searchInput.value = '';
    userSelect.value = '';

    const options = userSelect.querySelectorAll('option');
    options.forEach((option, index) => {
        if (index > 0) {
            option.remove();
        }
    });

    clearButton.classList.add('hidden');
    updateAllUserDropdowns();
    updateAddResponsableButton();
    showUserDropdown(searchInput);
}

function initializeUserSearch(responsableElement) {
    const searchInput = responsableElement.querySelector('.user-search');
    const dropdown = responsableElement.querySelector('.user-dropdown');
    const select = responsableElement.querySelector('.user-select');
    updateUserDropdown(dropdown, select);
}

function searchUsers(input) {
    const dropdown = input.nextElementSibling.nextElementSibling.nextElementSibling;
    const select = input.nextElementSibling;
    const searchTerm = input.value.toLowerCase();

    if (!searchTerm && select.value) {
        dropdown.classList.add('hidden');
        return;
    }

    const availableUsers = allUsers.filter(user =>
        !selectedUsers.has(user.id) &&
        (user.prenom.toLowerCase().includes(searchTerm) ||
         user.nom.toLowerCase().includes(searchTerm) ||
         user.email?.toLowerCase()?.includes(searchTerm))
    );

    dropdown.innerHTML = '';
    if (availableUsers.length > 0) {
        availableUsers.forEach(user => {
            const option = document.createElement('div');
            option.className = 'px-3 py-2 hover:bg-slate-100 cursor-pointer text-sm';
            option.innerHTML = `
                <div class="font-medium">${user.prenom} ${user.nom}</div>
                <div class="text-xs text-slate-500">${user.email ?? 'Aucun email disponible'}</div>
            `;
            option.onclick = () => selectUser(input, user, select, dropdown);
            dropdown.appendChild(option);
        });
        dropdown.classList.remove('hidden');
    } else {
        dropdown.innerHTML = '<div class="px-3 py-2 text-sm text-slate-500">Aucun utilisateur trouvé</div>';
        dropdown.classList.remove('hidden');
    }
}

function selectUser(input, user, select, dropdown) {
    const clearButton = input.parentElement.querySelector('.clear-user-btn');

    if (select.value) {
        selectedUsers.delete(select.value);
    }

    selectedUsers.add(user.id);
    input.value = `${user.prenom} ${user.nom}`;
    select.value = user.id;
    dropdown.classList.add('hidden');
    clearButton.classList.remove('hidden');

    let existingOption = select.querySelector(`option[value="${user.id}"]`);
    if (!existingOption) {
        const option = document.createElement('option');
        option.value = user.id;
        option.textContent = `${user.prenom} ${user.nom}`;
        option.selected = true;
        select.appendChild(option);
    } else {
        existingOption.selected = true;
    }

    updateAllUserDropdowns();
    updateAddResponsableButton();
}

function showUserDropdown(input) {
    const dropdown = input.nextElementSibling.nextElementSibling.nextElementSibling;
    const select = input.nextElementSibling;

    if (select.value && !input.value) {
        return;
    }

    if (!input.value) {
        updateUserDropdown(dropdown, select);
    }
    dropdown.classList.remove('hidden');
}

function hideUserDropdown(input) {
    setTimeout(() => {
        const dropdown = input.nextElementSibling.nextElementSibling.nextElementSibling;
        dropdown.classList.add('hidden');
    }, 200);
}

function updateUserDropdown(dropdown, select) {
    const availableUsers = allUsers.filter(user => !selectedUsers.has(user.id));

    dropdown.innerHTML = '';
    if (availableUsers.length > 0) {
        availableUsers.forEach(user => {
            const option = document.createElement('div');
            option.className = 'px-3 py-2 hover:bg-slate-100 cursor-pointer text-sm';
            option.innerHTML = `
                <div class="font-medium">${user.prenom} ${user.nom}</div>
                <div class="text-xs text-slate-500">${user.email ?? 'Aucun email disponible'}</div>
            `;
            option.onclick = () => {
                const input = dropdown.previousElementSibling.previousElementSibling.previousElementSibling;
                selectUser(input, user, select, dropdown);
            };
            dropdown.appendChild(option);
        });
    } else {
        dropdown.innerHTML = '<div class="px-3 py-2 text-sm text-slate-500">Tous les utilisateurs sont sélectionnés</div>';
    }
}

function updateAllUserDropdowns() {
    document.querySelectorAll('.user-dropdown').forEach(dropdown => {
        const select = dropdown.previousElementSibling.previousElementSibling;
        updateUserDropdown(dropdown, select);
    });
}

function handleSuperieurChange(checkbox) {
    if (checkbox.checked) {
        document.querySelectorAll('input[name*="[superieur]"]').forEach(cb => {
            if (cb !== checkbox) {
                cb.checked = false;
            }
        });
    }
}

// Gestion du programme
function addProgrammeItem() {
    const container = document.getElementById('programme-container');
    const template = `
        <div class="programme-item flex gap-3">
            <input type="text" name="programme[]"
                class="flex-1 px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                placeholder="Ex: Mathématiques de base">
            <button type="button" onclick="removeProgrammeItem(this)"
                class="w-10 h-10 text-red-600 bg-red-100 rounded-lg hover:bg-red-200 transition-colors flex items-center justify-center">
                <i class="fas fa-trash text-sm"></i>
            </button>
        </div>
    `;
    container.insertAdjacentHTML('beforeend', template);
}

function removeProgrammeItem(button) {
    const container = document.getElementById('programme-container');
    if (container.children.length > 1) {
        button.closest('.programme-item').remove();
    }
}

// Validation côté client
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    const ageMin = document.getElementById('age_minimum');
    const ageMax = document.getElementById('age_maximum');

    // Créer les responsables existants
    existingResponsables.forEach((responsable, index) => {
        createExistingResponsable(responsable, index);
    });

    // Initialiser l'état du bouton
    updateAddResponsableButton();

    function validateAges() {
        const min = parseInt(ageMin.value);
        const max = parseInt(ageMax.value);

        if (min && max && min > max) {
            ageMax.setCustomValidity('L\'âge maximum doit être supérieur à l\'âge minimum');
        } else {
            ageMax.setCustomValidity('');
        }
    }

    ageMin.addEventListener('input', validateAges);
    ageMax.addEventListener('input', validateAges);

    form.addEventListener('submit', function(e) {
        const superieurs = document.querySelectorAll('input[name*="[superieur]"]:checked');
        if (superieurs.length > 1) {
            e.preventDefault();
            alert('Une seule personne peut être désignée comme responsable supérieur');
        }
    });

    document.addEventListener('click', function(e) {
        if (!e.target.closest('.user-search') &&
            !e.target.closest('.user-dropdown') &&
            !e.target.closest('.clear-user-btn')) {
            document.querySelectorAll('.user-dropdown').forEach(dropdown => {
                dropdown.classList.add('hidden');
            });
        }
    });
});
</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.private.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Dell\Desktop\MICRISERVICES\methodiste_belle_ville\resources\views/components/private/classes/edit.blade.php ENDPATH**/ ?>