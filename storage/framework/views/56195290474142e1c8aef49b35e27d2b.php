<?php $__env->startSection('title', 'Nouvelle Souscription'); ?>

<?php $__env->startSection('content'); ?>
    <style>
        .autocomplete-dropdown {
            display: none;
        }

        .autocomplete-dropdown.show {
            display: block;
        }
    </style>
    <div class="space-y-8">
        <!-- Page Title & Breadcrumb -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent">
                Nouvelle Souscription</h1>
            <nav class="flex mt-2" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="<?php echo e(route('private.subscriptions.index')); ?>"
                            class="inline-flex items-center text-sm font-medium text-slate-700 hover:text-blue-600 transition-colors">
                            <i class="fas fa-hand-holding-usd mr-2"></i>
                            Souscriptions
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

        <form action="<?php echo e(route('private.subscriptions.store')); ?>" method="POST" id="souscriptionForm" class="space-y-8">
            <?php echo csrf_field(); ?>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Formulaire principal -->
                <div class="lg:col-span-2">
                    <div
                        class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                        <div class="p-6 border-b border-slate-200">
                            <h2 class="text-xl font-bold text-slate-800 flex items-center">
                                <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                                Informations de Souscription
                            </h2>
                        </div>
                        <div class="p-6 space-y-6">
                            <!-- Sélection FIMECO -->
                            <div>
                                <label for="fimeco_id" class="block text-sm font-medium text-slate-700 mb-2">
                                    FIMECO <span class="text-red-500">*</span>
                                </label>
                                
                                <input type="hidden" name="fimeco_id" value="<?php echo e($fimecoActive->id); ?>">
                                <div class="p-4 bg-blue-50 border border-blue-200 rounded-xl">
                                    <h3 class="font-semibold text-blue-900"><?php echo e($fimecoActive->nom); ?></h3>
                                    <p class="text-sm text-blue-700 mt-1"><?php echo e($fimecoActive->description); ?></p>
                                    <div class="flex items-center gap-4 mt-3 text-sm text-blue-600">
                                        <span><i
                                                class="fas fa-calendar mr-1"></i><?php echo e(\Carbon\Carbon::parse($fimecoActive->debut)->format('d/m/Y')); ?>

                                            - <?php echo e(\Carbon\Carbon::parse($fimecoActive->fin)->format('d/m/Y')); ?></span>
                                        <span><i
                                                class="fas fa-bullseye mr-1"></i><?php echo e(number_format($fimecoActive->cible, 0, ',', ' ')); ?>

                                            FCFA</span>
                                    </div>
                                </div>
                                
                            </div>

                            

                            <!-- Souscripteur avec recherche -->
                            <div>
                                <label for="souscripteur_search" class="block text-sm font-medium text-slate-700 mb-2">
                                    Souscripteur <span class="text-red-500">*</span>
                                </label>
                                <div class="relative" data-type="souscripteur">
                                    <input type="text" id="souscripteur_search" name="souscripteur_search"
                                        placeholder="Rechercher un souscripteur..."
                                        class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors <?php $__errorArgs = ['souscripteur_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 focus:ring-red-500 focus:border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                        autocomplete="off" value="<?php echo e(old('souscripteur_search')); ?>">

                                    <input type="hidden" id="souscripteur_id" name="souscripteur_id"
                                        value="<?php echo e(old('souscripteur_id')); ?>">

                                    <div class="autocomplete-dropdown absolute top-full left-0 right-0 bg-white border border-slate-200 rounded-lg shadow-xl max-h-72 overflow-y-auto z-50"
                                        id="souscripteur_dropdown">
                                        <div class="loading-item p-3 text-center text-slate-500 hidden">
                                            <i class="fas fa-spinner fa-spin mr-2"></i>Recherche en cours...
                                        </div>
                                        <div class="no-results p-3 text-center text-slate-500 hidden">
                                            Aucun souscripteur trouvé
                                        </div>
                                        <div
                                            class="add-new-item p-3 border-t border-slate-200 cursor-pointer hover:bg-slate-50 transition-colors hidden">
                                            <i class="fas fa-plus text-blue-600 mr-2"></i>
                                            <span class="text-blue-600">Ajouter ce souscripteur</span>
                                        </div>
                                    </div>
                                </div>
                                <?php $__errorArgs = ['souscripteur_id'];
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

                            <!-- Montant de souscription -->
                            <div>
                                <label for="montant_souscrit" class="block text-sm font-medium text-slate-700 mb-2">
                                    Montant de souscription (FCFA) <span class="text-red-500">*</span>
                                </label>
                                <input type="number" id="montant_souscrit" name="montant_souscrit"
                                    value="<?php echo e(old('montant_souscrit')); ?>" required min="10" step="0.01" placeholder="50000"
                                    onchange="updatePreview()"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors <?php $__errorArgs = ['montant_souscrit'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 focus:ring-red-500 focus:border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                <?php $__errorArgs = ['montant_souscrit'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <p class="mt-2 text-sm text-red-600"><?php echo e($message); ?></p>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                <p class="mt-1 text-sm text-slate-500">Montant minimum : 10 FCFA</p>
                            </div>



                            <!-- Commentaire -->
                            <div>
                                <label for="commentaire" class="block text-sm font-medium text-slate-700 mb-2">Commentaire
                                    (optionnel)</label>
                                <textarea id="commentaire" name="commentaire" rows="3"
                                    placeholder="Motivation, objectifs personnels, etc."
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors resize-none <?php $__errorArgs = ['commentaire'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 focus:ring-red-500 focus:border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"><?php echo e(old('commentaire')); ?></textarea>
                                <?php $__errorArgs = ['commentaire'];
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

                <!-- Sidebar avec aperçu et aide -->
                <div class="space-y-6">
                    <!-- Aperçu de la souscription -->
                    <div
                        class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                        <div class="p-6 border-b border-slate-200">
                            <h2 class="text-xl font-bold text-slate-800 flex items-center">
                                <i class="fas fa-eye text-purple-600 mr-2"></i>
                                Aperçu
                            </h2>
                        </div>
                        <div class="p-6 space-y-4">
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-slate-700">FIMECO:</span>
                                <span id="preview-fimeco" class="text-sm text-slate-900 font-semibold">-</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-slate-700">Montant:</span>
                                <span id="preview-montant" class="text-sm text-green-600 font-semibold">-</span>
                            </div>

                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-slate-700">Statut initial:</span>
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    Active
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Information importante -->
                    <div
                        class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                        <div class="p-6 border-b border-slate-200">
                            <h2 class="text-xl font-bold text-slate-800 flex items-center">
                                <i class="fas fa-info text-amber-600 mr-2"></i>
                                Informations Importantes
                            </h2>
                        </div>
                        <div class="p-6 space-y-4">
                            <div class="p-4 bg-blue-50 border border-blue-200 rounded-lg">
                                <div class="flex">
                                    <i class="fas fa-info-circle text-blue-400 mt-0.5 mr-3"></i>
                                    <div class="text-sm text-blue-800">
                                        <p class="font-medium">Engagement</p>
                                        <p class="mt-1">En souscrivant, vous vous engagez à verser le montant indiqué selon
                                            les modalités de la FIMECO.</p>
                                    </div>
                                </div>
                            </div>

                            <div class="p-4 bg-green-50 border border-green-200 rounded-lg">
                                <div class="flex">
                                    <i class="fas fa-check-circle text-green-400 mt-0.5 mr-3"></i>
                                    <div class="text-sm text-green-800">
                                        <p class="font-medium">Flexibilité</p>
                                        <p class="mt-1">Vous pouvez effectuer des paiements partiels et suivre votre
                                            progression.</p>
                                    </div>
                                </div>
                            </div>

                            <div class="p-4 bg-orange-50 border border-orange-200 rounded-lg">
                                <div class="flex">
                                    <i class="fas fa-clock text-orange-400 mt-0.5 mr-3"></i>
                                    <div class="text-sm text-orange-800">
                                        <p class="font-medium">Suivi</p>
                                        <p class="mt-1">Vos paiements doivent être validés par les responsables de la
                                            FIMECO.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Guide de souscription -->
                    <div
                        class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                        <div class="p-6 border-b border-slate-200">
                            <h2 class="text-xl font-bold text-slate-800 flex items-center">
                                <i class="fas fa-map text-green-600 mr-2"></i>
                                Guide de Souscription
                            </h2>
                        </div>
                        <div class="p-6 space-y-4">
                            <div class="flex items-start space-x-3">
                                <div
                                    class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                                    <span class="text-blue-600 font-bold text-sm">1</span>
                                </div>
                                <div>
                                    <h3 class="font-medium text-slate-900">Choisir la FIMECO</h3>
                                    <p class="text-sm text-slate-600">Sélectionnez une FIMECO active correspondant à vos
                                        objectifs</p>
                                </div>
                            </div>

                            <div class="flex items-start space-x-3">
                                <div
                                    class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                                    <span class="text-purple-600 font-bold text-sm">2</span>
                                </div>
                                <div>
                                    <h3 class="font-medium text-slate-900">Fixer le montant</h3>
                                    <p class="text-sm text-slate-600">Déterminez un montant réaliste selon vos capacités</p>
                                </div>
                            </div>

                            <div class="flex items-start space-x-3">
                                <div
                                    class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                                    <span class="text-green-600 font-bold text-sm">3</span>
                                </div>
                                <div>
                                    <h3 class="font-medium text-slate-900">Valider</h3>
                                    <p class="text-sm text-slate-600">Confirmez votre souscription et commencez les
                                        paiements</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20">
                <div class="p-6">
                    <div class="flex flex-col sm:flex-row gap-4 justify-center">
                        <button type="submit"
                            class="inline-flex items-center justify-center px-8 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-medium rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow-md hover:shadow-lg">
                            <i class="fas fa-save mr-2"></i> Créer la Souscription
                        </button>
                        <a href="<?php echo e(route('private.subscriptions.index')); ?>"
                            class="inline-flex items-center justify-center px-8 py-3 bg-slate-600 text-white font-medium rounded-xl hover:bg-slate-700 transition-colors">
                            <i class="fas fa-times mr-2"></i> Annuler
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <?php $__env->startPush('scripts'); ?>
        <script>
            // Mise à jour des informations FIMECO
            function updateFimecoInfo() {
                const select = document.getElementById('fimeco_id');
                const infoDiv = document.getElementById('fimeco-info');

                if (select?.value) {
                    const option = select.options[select.selectedIndex];
                    const nom = option.getAttribute('data-nom');
                    const description = option.getAttribute('data-description');
                    const debut = option.getAttribute('data-debut');
                    const fin = option.getAttribute('data-fin');
                    const cible = option.getAttribute('data-cible');

                    document.getElementById('fimeco-nom').textContent = nom;
                    document.getElementById('fimeco-description').textContent = description || 'Aucune description';
                    document.getElementById('fimeco-periode').innerHTML = `<i class="fas fa-calendar mr-1"></i>${debut} - ${fin}`;
                    document.getElementById('fimeco-cible').innerHTML = `<i class="fas fa-bullseye mr-1"></i>${new Intl.NumberFormat('fr-FR').format(cible)} FCFA`;

                    infoDiv.classList.remove('hidden');
                } else {
                    infoDiv?.classList.add('hidden');
                }

                updatePreview();
            }

            // Mise à jour de l'aperçu
            function updatePreview() {
                const fimecoSelect = document.getElementById('fimeco_id');
                const montant = document.getElementById('montant_souscrit').value;

                // FIMECO
                if (fimecoSelect?.value) {
                    const fimecoNom = fimecoSelect.options[fimecoSelect.selectedIndex].text;
                    document.getElementById('preview-fimeco').textContent = fimecoNom;
                } else {
                    document.getElementById('preview-fimeco').textContent = '-';
                }

                // Montant
                if (montant) {
                    const formatted = new Intl.NumberFormat('fr-FR').format(montant) + ' FCFA';
                    document.getElementById('preview-montant').textContent = formatted;
                } else {
                    document.getElementById('preview-montant').textContent = '-';
                }


            }

            // Validation du formulaire
            document.getElementById('souscriptionForm').addEventListener('submit', function (e) {
                const fimecoId = document.getElementById('fimeco_id').value;
                const montant = parseFloat(document.getElementById('montant_souscrit').value);

                if (!fimecoId) {
                    e.preventDefault();
                    alert('Veuillez sélectionner une FIMECO.');
                    return false;
                }

                if (!montant || montant < 10) {
                    e.preventDefault();
                    alert('Le montant de souscription doit être d\'au moins 10 FCFA.');
                    return false;
                }



                // Confirmation
                const confirmation = confirm(
                    `Confirmez-vous votre souscription de ${new Intl.NumberFormat('fr-FR').format(montant)} FCFA ?`
                );

                if (!confirmation) {
                    e.preventDefault();
                    return false;
                }
            });

            const fetchUsers = () => {
                alert()
                const target = this.target
                alert();
                fetch("<?php echo e(route('private.subscriptions.user-disponibles', ':fimeco')); ?>".replace(':fimeco', target.value), {
                    method: 'GET',
                    headers: {
                        'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
                        'Accept': 'application/json'
                    }
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            target.content = '';
                            target.value = '';
                            data.data.forEach((user, key) => {
                                if ($key == 0) {
                                    target.innerHTML = `<option value="">Sélectionner le souscripteur</option>`;
                                } else {
                                    target.innerHTML += `<option value="${user.id}" data-nom="${user.nom}" data-telephone="${user.telephone_1}" data-prenom="${user.prenom}" data-email="${user.email}">
                                                     ${user.nom}  ${user.prenom} ( ${user.telephone_1} )
                                                </option>`
                                }
                            })

                        } else {
                            alert(data.message || 'Une erreur est survenue');
                        }
                    })
                    .catch(error => {
                        console.error('Erreur:', error);
                        alert('Une erreur est survenue');
                    });
            }

            // Événements
            document.getElementById('montant_souscrit').addEventListener('input', updatePreview);

            // Initialisation
            document.addEventListener('DOMContentLoaded', function () {
                updateFimecoInfo();
                updatePreview();
            });













            class AutoComplete {
                constructor(containerElement, type) {
                    this.container = containerElement;
                    this.type = type;
                    this.input = containerElement.querySelector(`input[name="${type}_search"]`);
                    this.hiddenInput = containerElement.querySelector(`input[name="${type}_id"]`);
                    this.dropdown = containerElement.querySelector(`.autocomplete-dropdown`);
                    this.loadingItem = this.dropdown.querySelector('.loading-item');
                    this.noResultsItem = this.dropdown.querySelector('.no-results');
                    this.addNewItem = this.dropdown.querySelector('.add-new-item');

                    this.debounceTimer = null;
                    this.selectedIndex = -1;
                    this.currentItems = [];
                    this.currentRequest = null; // Initialiser ici
                    this.isSelecting = false; // Pour éviter la fermeture prématurée

                    this.init();
                }


                init() {
                    this.input.addEventListener('input', this.handleInput.bind(this));
                    this.input.addEventListener('focus', this.handleFocus.bind(this));
                    this.input.addEventListener('blur', this.handleBlur.bind(this));
                    this.input.addEventListener('keydown', this.handleKeydown.bind(this));

                    // Retirer le required du champ de recherche
                    this.input.removeAttribute('required');

                    if (this.addNewItem) {
                        this.addNewItem.addEventListener('click', () => {
                            this.isSelecting = true;
                            this.handleAddNew();
                        });
                    }

                    // Fermer le dropdown si on clique ailleurs
                    this.documentClickHandler = (e) => {
                        if (!this.container.contains(e.target) && !this.isSelecting) {
                            this.hideDropdown();
                        }
                        this.isSelecting = false;
                    };
                    document.addEventListener('click', this.documentClickHandler);
                }

                handleInput() {
                    const query = this.input.value.trim();

                    if (query.length === 0) {
                        this.hideDropdown();
                        this.hiddenInput.value = '';
                        this.clearStoredSelection();
                        updatePreview();
                        return;
                    }

                    // Vérifier si la sélection actuelle correspond
                    const stored = this.getStoredSelection();
                    if (stored && stored.display !== query) {
                        this.hiddenInput.value = '';
                        this.clearStoredSelection();
                        updatePreview();
                    }

                    if (query.length < 2) {
                        this.hideDropdown();
                        return;
                    }

                    clearTimeout(this.debounceTimer);
                    this.debounceTimer = setTimeout(() => {
                        this.search(query);
                    }, 300);
                }

                handleFocus() {
                    const query = this.input.value.trim();
                    if (query.length >= 2) {
                        this.search(query);
                    }
                }


                getStoredSelection() {
                    const key = `autocomplete_${this.type}_selection`;
                    const stored = sessionStorage.getItem(key);
                    return stored ? JSON.parse(stored) : null;
                }

                handleBlur() {
                    // Augmenter le délai et vérifier si on est en train de sélectionner
                    setTimeout(() => {
                        if (!this.isSelecting) {
                            this.hideDropdown();
                        }
                    }, 300);
                }

                handleBlur() {
                    // Délai pour permettre le clic sur un élément du dropdown
                    setTimeout(() => {
                        this.hideDropdown();
                    }, 200);
                }

                handleKeydown(e) {
                    if (!this.dropdown.classList.contains('show')) return;

                    switch (e.key) {
                        case 'ArrowDown':
                            e.preventDefault();
                            this.selectedIndex = Math.min(this.selectedIndex + 1, this.currentItems.length - 1);
                            this.updateSelection();
                            break;
                        case 'ArrowUp':
                            e.preventDefault();
                            this.selectedIndex = Math.max(this.selectedIndex - 1, -1);
                            this.updateSelection();
                            break;
                        case 'Enter':
                            e.preventDefault();
                            if (this.selectedIndex >= 0 && this.currentItems[this.selectedIndex]) {
                                const user = this.currentItems[this.selectedIndex];
                                const emailMatch = user.text.match(/^(.+)\s+\([^)]+\)$/);
                                const displayName = emailMatch ? emailMatch[1].trim() : user.text;
                                this.selectItem(user, displayName);
                            } else if (this.addNewItem && !this.addNewItem.classList.contains('hidden')) {
                                this.handleAddNew();
                            }
                            break;
                        case 'Escape':
                            this.hideDropdown();
                            this.input.blur();
                            break;
                    }
                }



                async search(query) {
                    if (this.currentRequest) {
                        this.currentRequest.abort();
                    }

                    this.showLoading();

                    try {
                        this.currentRequest = new AbortController();

                        // CORRECTION : Utiliser la route index avec le paramètre search
                        const response = await fetch(`<?php echo e(route('private.users.index')); ?>?search=${encodeURIComponent(query)}`, {
                            headers: {
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest',
                                'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
                            },
                            signal: this.currentRequest.signal
                        });

                        if (!response.ok) {
                            throw new Error('Erreur lors de la recherche');
                        }

                        const data = await response.json();

                        // if (!this.currentRequest.signal.aborted) {
                            // Adapter la structure de réponse
                            const users = data.success && data.data ? data.data.data : []; // data.data.data car c'est paginé
                            // Transformer les utilisateurs au format attendu
                            const formattedUsers = users.map(user => ({
                                id: user.id,
                                text: user.email ? `${user.nom} ${user.prenom} (${user.email})` : `${user.nom} ${user.prenom}`,
                                email: user.email || null
                            }));
                            this.displayResults(formattedUsers);
                        // }
                    } catch (error) {
                        if (error.name !== 'AbortError') {
                            console.error('Erreur lors de la recherche:', error);
                            this.hideDropdown();
                        }
                    } finally {
                        this.currentRequest = null;
                    }
                }



                displayResults(users) {
                    this.hideLoading();
                    this.clearResults();
                    this.currentItems = users;
                    this.selectedIndex = -1;

                    if (users.length === 0) {
                        this.showNoResults();
                        this.showAddNew();
                    } else {
                        users.forEach((user, index) => {
                            const item = this.createUserItem(user, index);
                            this.dropdown.appendChild(item);
                        });
                        this.showAddNew();
                    }

                    this.showDropdown();
                }

                createUserItem(user, index) {
                    const div = document.createElement('div');
                    div.className = 'p-3 cursor-pointer border-b border-slate-100 hover:bg-slate-50 transition-colors';
                    div.dataset.index = index;

                    const emailMatch = user.text.match(/^(.+)\s+\([^)]+\)$/);
                    let nameOnly = emailMatch ? emailMatch[1].trim() : user.text;

                    div.innerHTML = `
                                    <div class="font-medium text-slate-700">${this.escapeHtml(nameOnly)}</div>
                                    <div class="text-sm text-slate-500">${this.escapeHtml(user.email)}</div>
                                `;

                    div.addEventListener('mousedown', (e) => {
                        e.preventDefault(); // Empêcher le blur
                        this.isSelecting = true;
                    });

                    div.addEventListener('click', () => {
                        this.selectItem(user, nameOnly);
                    });

                    return div;
                }

                escapeHtml(text) {
                    const div = document.createElement('div');
                    div.textContent = text;
                    return div.innerHTML;
                }

                selectItem(user, displayName = null) {
                    let nameToDisplay = displayName;
                    if (!nameToDisplay) {
                        const emailMatch = user.text.match(/^(.+)\s+\([^)]+\)$/);
                        nameToDisplay = emailMatch ? emailMatch[1].trim() : user.text;
                    }

                    this.input.value = nameToDisplay;
                    this.hiddenInput.value = user.id;
                    this.hideDropdown();

                    this.storeSelection(user.id, nameToDisplay);
                    this.isSelecting = false;

                    updatePreview();
                }

                handleAddNew() {
                    const name = this.input.value.trim();
                    if (!name) return;

                    // Ouvrir un modal pour créer un nouvel utilisateur
                    this.showAddUserModal(name);
                }


showAddUserModal(name) {
    const modal = document.createElement('div');
    modal.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50';
    modal.innerHTML = `
        <div class="bg-white rounded-xl p-6 w-full max-w-lg mx-4 shadow-2xl max-h-[90vh] overflow-y-auto">
            <h3 class="text-lg font-semibold text-slate-800 mb-4">Ajouter un nouveau membre</h3>
            <form id="addUserForm">
                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Nom <sup class="text-red-500">*</sup></label>
                            <input type="text" name="nom" required class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Prénom <sup class="text-red-500">*</sup></label>
                            <input type="text" name="prenom" required class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Email <sup class="text-red-500">*</sup></label>
                        <input type="email" name="email" required class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Téléphone <sup class="text-red-500">*</sup></label>
                        <input type="text" name="telephone_1" required class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Genre <sup class="text-red-500">*</sup></label>
                        <select name="sexe" required class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            <option value="">Choisir</option>
                            <option value="masculin">Masculin</option>
                            <option value="feminin">Féminin</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Adresse <sup class="text-red-500">*</sup></label>
                        <input type="text" name="adresse_ligne_1" required placeholder="Adresse principale" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Ville <sup class="text-red-500">*</sup></label>
                        <input type="text" name="ville" required class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Statut membre <sup class="text-red-500">*</sup></label>
                            <select name="statut_membre" required class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                <option value="actif">Actif</option>
                                <option value="visiteur">Visiteur</option>
                                <option value="nouveau_converti">Nouveau converti</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Statut baptême <sup class="text-red-500">*</sup></label>
                            <select name="statut_bapteme" id="statut_bapteme" required class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                <option value="non_baptise">Non baptisé</option>
                                <option value="baptise">Baptisé</option>
                                <option value="confirme">Confirmé</option>
                            </select>
                        </div>
                    </div>

                    <!-- Champ date de baptême conditionnel -->
                    <div id="date_bapteme_container" class="hidden">
                        <label class="block text-sm font-medium text-slate-700 mb-1">Date de baptême <sup class="text-red-500">*</sup></label>
                        <input type="date" name="date_bapteme" id="date_bapteme" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                    </div>

                </div>
                <div class="flex justify-end space-x-3 mt-6">
                    <button type="button" class="cancel-btn px-4 py-2 text-slate-600 hover:text-slate-800 transition-colors rounded-lg">Annuler</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors shadow-md">Ajouter</button>
                </div>
            </form>
        </div>
    `;

    document.body.appendChild(modal);

    // Pré-remplir le nom si possible
    const [nom, ...prenomParts] = name.split(' ');
    const prenom = prenomParts.join(' ');
    modal.querySelector('input[name="nom"]').value = nom || '';
    modal.querySelector('input[name="prenom"]').value = prenom || '';

    // Gestion conditionnelle de la date de baptême
    const statutBapteme = modal.querySelector('#statut_bapteme');
    const dateBaptemeContainer = modal.querySelector('#date_bapteme_container');
    const dateBaptemeInput = modal.querySelector('#date_bapteme');

    function toggleDateBapteme() {
        const value = statutBapteme.value;
        if (value === 'baptise' || value === 'confirme') {
            dateBaptemeContainer.classList.remove('hidden');
            dateBaptemeInput.required = true;
        } else {
            dateBaptemeContainer.classList.add('hidden');
            dateBaptemeInput.required = false;
            dateBaptemeInput.value = '';
        }
    }

    statutBapteme.addEventListener('change', toggleDateBapteme);

    // Appeler une fois pour initialiser l'état
    toggleDateBapteme();

    // Gestionnaires d'événements
    modal.querySelector('.cancel-btn').addEventListener('click', () => {
        document.body.removeChild(modal);
    });

    modal.addEventListener('click', (e) => {
        if (e.target === modal) {
            document.body.removeChild(modal);
        }
    });

    modal.querySelector('#addUserForm').addEventListener('submit', async (e) => {
        e.preventDefault();
        await this.createUser(new FormData(e.target), modal);
    });
}

























                // async createUser(formData, modal) {
                //     try {
                //         const response = await fetch("<?php echo e(route('private.users.ajoutmembre')); ?>", {
                //             method: 'POST',
                //             headers: {
                //                 'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
                //                 'Accept': 'application/json',
                //             },
                //             body: formData
                //         });

                //         const data = await response.json();

                //         // Correction : vérifier response.ok (statut HTTP) ou data.success
                //         if (response.ok && data.success) {
                //             // Sélectionner l'utilisateur créé
                //             this.selectItem(data.data);
                //             document.body.removeChild(modal);

                //             // Afficher un message de succès
                //             this.showSuccessMessage(data.message);
                //         } else {
                //             throw new Error(data.message || 'Erreur lors de la création');
                //         }
                //     } catch (error) {
                //         console.error('Erreur:', error);
                //         alert('Erreur lors de la création du membre: ' + error.message);
                //     }
                // }


                async createUser(formData, modal) {
    try {
        const response = await fetch("<?php echo e(route('private.users.store')); ?>", {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
                'Accept': 'application/json',
            },
            body: formData
        });

        const data = await response.json();

        if (response.ok && data.success) {
            // Fermer le modal
            document.body.removeChild(modal);

            // Transformer l'utilisateur au format attendu par selectItem
            const userForSelection = {
                id: data.data.id,
                text: data.data.email ? `${data.data.nom} ${data.data.prenom} (${data.data.email})` : `${data.data.nom} ${data.data.prenom}`,
                email: data.data.email || null
            };

            // Sélectionner l'utilisateur créé
            this.selectItem(userForSelection, `${data.data.nom} ${data.data.prenom}`.trim());

            // Afficher un message de succès
            this.showSuccessMessage(data.message || 'Membre ajouté avec succès');
        } else {
            // Afficher les erreurs de validation
            if (data.error && typeof data.error === 'object') {
                const errorMessages = Object.values(data.error).flat();
                alert('Erreurs de validation:\n' + errorMessages.join('\n'));
            } else {
                throw new Error(data.message || 'Erreur lors de la création');
            }
        }
    } catch (error) {
        console.error('Erreur:', error);
        alert('Erreur lors de la création du membre: ' + error.message);
    }
}



                showSuccessMessage(message) {
                    const toast = document.createElement('div');
                    toast.className = 'fixed top-4 right-4 bg-green-600 text-white px-6 py-3 rounded-lg shadow-lg z-50';
                    toast.textContent = message;
                    document.body.appendChild(toast);

                    setTimeout(() => {

                        document.body.removeChild(toast);
                    }, 3000);
                }

                storeSelection(userId, displayName) {
                    const key = `autocomplete_${this.type}_selection`;
                    sessionStorage.setItem(key, JSON.stringify({
                        id: userId,
                        display: displayName
                    }));
                }

                clearStoredSelection() {
                    const key = `autocomplete_${this.type}_selection`;
                    sessionStorage.removeItem(key);
                }

                // async restoreSelection() {
                //     const stored = this.getStoredSelection();

                //     if (this.hiddenInput.value) {
                //         if (this.input.value && this.input.value.trim() !== '') {
                //             return;
                //         }

                //         if (stored && stored.id === parseInt(this.hiddenInput.value)) {
                //             this.input.value = stored.display;
                //             return;
                //         }

                //         try {
                //             const response = await fetch(`<?php echo e(route('private.users.search')); ?>?q=${this.hiddenInput.value}`);
                //             const data = await response.json();
                //             const users = Array.isArray(data) ? data : (data.users || []);

                //             const user = users.find(u => u.id === parseInt(this.hiddenInput.value));
                //             if (user && user.email) {
                //                 const emailMatch = user.text.match(/^(.+)\s+\([^)]+\)$/);
                //                 const displayName = emailMatch ? emailMatch[1].trim() : user.text;
                //                 this.input.value = displayName;
                //                 this.storeSelection(user.id, displayName);
                //             }
                //         } catch (error) {
                //             console.error('Erreur lors de la restauration:', error);
                //         }
                //     }
                // }



                async restoreSelection() {
    const stored = this.getStoredSelection();

    if (this.hiddenInput.value) {
        if (this.input.value && this.input.value.trim() !== '') {
            return;
        }

        if (stored && stored.id === this.hiddenInput.value) {
            this.input.value = stored.display;
            return;
        }

        try {
            const response = await fetch(`<?php echo e(route('private.users.index')); ?>?search=${this.hiddenInput.value}`, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
                }
            });
            const data = await response.json();
            const users = data.success && data.data ? data.data.data : [];

            const user = users.find(u => u.id === this.hiddenInput.value);
            if (user) {
                const displayName = `${user.nom} ${user.prenom}`.trim();
                this.input.value = displayName;
                this.storeSelection(user.id, displayName);
            }
        } catch (error) {
            console.error('Erreur lors de la restauration:', error);
        }
    }
}


                clearResults() {
                    // Supprimer tous les éléments avec data-index (les résultats utilisateur)
                    const items = this.dropdown.querySelectorAll('[data-index]');
                    items.forEach(item => item.remove());

                    // Cacher les éléments statiques
                    this.noResultsItem.classList.add('hidden');
                    this.addNewItem.classList.add('hidden');
                }

                updateSelection() {
                    const items = this.dropdown.querySelectorAll('[data-index]');
                    items.forEach((item, index) => {
                        if (index === this.selectedIndex) {
                            item.classList.add('bg-blue-50');
                            item.classList.remove('hover:bg-slate-50');
                        } else {
                            item.classList.remove('bg-blue-50');
                            item.classList.add('hover:bg-slate-50');
                        }
                    });
                }

                showLoading() {
                    this.loadingItem.classList.remove('hidden');
                    this.showDropdown();
                }

                hideLoading() {
                    this.loadingItem.classList.add('hidden');
                }

                showNoResults() {
                    this.noResultsItem.classList.remove('hidden');
                }

                showAddNew() {
                    this.addNewItem.classList.remove('hidden');
                    this.addNewItem.querySelector('span').textContent = `Ajouter "${this.input.value}"`;
                }

                showDropdown() {
                    this.dropdown.classList.add('show');
                }

                hideDropdown() {
                    this.dropdown.classList.remove('show');
                }

                destroy() {
                    if (this.documentClickHandler) {
                        document.removeEventListener('click', this.documentClickHandler);
                    }

                    if (this.currentRequest) {
                        this.currentRequest.abort();
                    }

                    if (this.debounceTimer) {
                        clearTimeout(this.debounceTimer);
                    }
                }
            }


            // Fonction pour mettre à jour l'aperçu
            function updatePreview() {
                const fimecoSelect = document.getElementById('fimeco_id');
                const montant = document.getElementById('montant_souscrit').value;

                // FIMECO
                if (fimecoSelect?.value) {
                    const fimecoNom = fimecoSelect.options[fimecoSelect.selectedIndex].text;
                    document.getElementById('preview-fimeco').textContent = fimecoNom;
                } else {
                    document.getElementById('preview-fimeco').textContent = '-';
                }

                // Montant
                if (montant) {
                    const formatted = new Intl.NumberFormat('fr-FR').format(montant) + ' FCFA';
                    document.getElementById('preview-montant').textContent = formatted;
                } else {
                    document.getElementById('preview-montant').textContent = '-';
                }
            }

            // Validation du formulaire
            document.getElementById('souscriptionForm').addEventListener('submit', function (e) {
                const fimecoId = document.getElementById('fimeco_id').value;
                const souscripteurId = document.getElementById('souscripteur_id').value;
                const montant = parseFloat(document.getElementById('montant_souscrit').value);

                if (!fimecoId) {
                    e.preventDefault();
                    alert('Veuillez sélectionner une FIMECO.');
                    return false;
                }

                if (!souscripteurId) {
                    e.preventDefault();
                    alert('Veuillez sélectionner un souscripteur.');
                    document.getElementById('souscripteur_search').focus();
                    return false;
                }

                if (!montant || montant < 10) {
                    e.preventDefault();
                    alert('Le montant de souscription doit être d\'au moins 10 FCFA.');
                    return false;
                }

                // Confirmation
                const confirmation = confirm(
                    `Confirmez-vous votre souscription de ${new Intl.NumberFormat('fr-FR').format(montant)} FCFA ?`
                );

                if (!confirmation) {
                    e.preventDefault();
                    return false;
                }
            });

            // Événements
            document.getElementById('montant_souscrit').addEventListener('input', updatePreview);

            // Initialisation
            document.addEventListener('DOMContentLoaded', function () {
                // Initialiser l'autocomplete pour le souscripteur
                const souscripteurContainer = document.querySelector('[data-type="souscripteur"]');
                if (souscripteurContainer) {
                    window.souscripteurAutocomplete = new AutoComplete(souscripteurContainer, 'souscripteur');
                    // Restaurer l'affichage si une valeur est déjà sélectionnée
                    window.souscripteurAutocomplete.restoreSelection();
                }

                updateFimecoInfo();
                updatePreview();
            });

            // Cleanup au moment de la soumission
            document.getElementById('souscriptionForm').addEventListener('submit', function () {
                sessionStorage.removeItem('autocomplete_souscripteur_selection');
            });
        </script>
    <?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.private.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Dell\Desktop\MICRISERVICES\methodiste_belle_ville\resources\views/components/private/subscriptions/create.blade.php ENDPATH**/ ?>