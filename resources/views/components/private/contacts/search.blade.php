@extends('layouts.private.main')
@section('title', 'Recherche Avancée de Contacts')

@section('content')
<div class="space-y-8">
    <!-- Page Title -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent">Recherche Avancée</h1>
                <p class="text-slate-500 mt-1">Trouvez précisément les contacts que vous cherchez</p>
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('private.contacts.index') }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-slate-600 to-gray-600 text-white text-sm font-medium rounded-xl hover:from-slate-700 hover:to-gray-700 transition-all duration-200 shadow-md hover:shadow-lg">
                    <i class="fas fa-list mr-2"></i> Liste simple
                </a>
                <button onclick="saveSearch()" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-purple-600 to-pink-600 text-white text-sm font-medium rounded-xl hover:from-purple-700 hover:to-pink-700 transition-all duration-200 shadow-md hover:shadow-lg">
                    <i class="fas fa-save mr-2"></i> Sauvegarder
                </button>
                <button onclick="resetSearch()" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-amber-600 to-orange-600 text-white text-sm font-medium rounded-xl hover:from-amber-700 hover:to-orange-700 transition-all duration-200 shadow-md hover:shadow-lg">
                    <i class="fas fa-refresh mr-2"></i> Réinitialiser
                </button>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        <!-- Formulaire de recherche -->
        <div class="lg:col-span-3 space-y-6">
            <!-- Recherche textuelle -->
            <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="p-6 border-b border-slate-200">
                    <h2 class="text-xl font-bold text-slate-800 flex items-center">
                        <i class="fas fa-search text-blue-600 mr-2"></i>
                        Recherche Textuelle
                    </h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="search_name" class="block text-sm font-medium text-slate-700 mb-2">Nom de l'église</label>
                            <input type="text" id="search_name" name="search_name" placeholder="Ex: Baptiste, Assemblée..."
                                class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        </div>

                        <div>
                            <label for="search_pastor" class="block text-sm font-medium text-slate-700 mb-2">Pasteur</label>
                            <input type="text" id="search_pastor" name="search_pastor" placeholder="Nom du pasteur..."
                                class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        </div>

                        <div>
                            <label for="search_phone" class="block text-sm font-medium text-slate-700 mb-2">Téléphone</label>
                            <input type="text" id="search_phone" name="search_phone" placeholder="+225 XX XX XX XX XX"
                                class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        </div>

                        <div>
                            <label for="search_email" class="block text-sm font-medium text-slate-700 mb-2">Email</label>
                            <input type="email" id="search_email" name="search_email" placeholder="contact@eglise.org"
                                class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        </div>
                    </div>

                    <div class="mt-6">
                        <label for="search_description" class="block text-sm font-medium text-slate-700 mb-2">Description ou mission</label>
                        <textarea id="search_description" name="search_description" rows="3" placeholder="Rechercher dans les descriptions..."
                            class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors resize-none"></textarea>
                    </div>
                </div>
            </div>

            <!-- Filtres par catégorie -->
            <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="p-6 border-b border-slate-200">
                    <h2 class="text-xl font-bold text-slate-800 flex items-center">
                        <i class="fas fa-filter text-green-600 mr-2"></i>
                        Filtres par Catégorie
                    </h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div>
                            <label for="filter_type" class="block text-sm font-medium text-slate-700 mb-2">Type de contact</label>
                            <select id="filter_type" name="filter_type" multiple class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                <option value="principal">Principal</option>
                                <option value="pastoral">Pastoral</option>
                                <option value="administratif">Administratif</option>
                                <option value="urgence">Urgence</option>
                                <option value="jeunesse">Jeunesse</option>
                                <option value="femmes">Femmes</option>
                                <option value="hommes">Hommes</option>
                                <option value="enfants">Enfants</option>
                                <option value="technique">Technique</option>
                                <option value="media">Média</option>
                                <option value="finance">Finance</option>
                                <option value="social">Social</option>
                            </select>
                            <p class="text-xs text-slate-500 mt-1">Maintenez Ctrl pour sélectionner plusieurs</p>
                        </div>

                        <div>
                            <label for="filter_denomination" class="block text-sm font-medium text-slate-700 mb-2">Dénomination</label>
                            <select id="filter_denomination" name="filter_denomination" multiple class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                <option value="Baptist">Baptiste</option>
                                <option value="Methodist">Méthodiste</option>
                                <option value="Pentecostal">Pentecôtiste</option>
                                <option value="Catholic">Catholique</option>
                                <option value="Presbyterian">Presbytérien</option>
                                <option value="Assemblies of God">Assemblée de Dieu</option>
                                <option value="Evangelical">Évangélique</option>
                                <option value="Lutheran">Luthérien</option>
                                <option value="Anglican">Anglican</option>
                                <option value="Orthodox">Orthodoxe</option>
                            </select>
                        </div>

                        <div>
                            <label for="filter_status" class="block text-sm font-medium text-slate-700 mb-2">Statut</label>
                            <select id="filter_status" name="filter_status" multiple class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                <option value="verified">Vérifiés</option>
                                <option value="unverified">Non vérifiés</option>
                                <option value="public">Publics</option>
                                <option value="private">Privés</option>
                                <option value="with_website">Avec site web</option>
                                <option value="with_social">Avec réseaux sociaux</option>
                                <option value="geolocated">Géolocalisés</option>
                                <option value="complete">Profil complet</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recherche géographique -->
            <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="p-6 border-b border-slate-200">
                    <h2 class="text-xl font-bold text-slate-800 flex items-center">
                        <i class="fas fa-map-marker-alt text-red-600 mr-2"></i>
                        Recherche Géographique
                    </h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        <div>
                            <label for="filter_country" class="block text-sm font-medium text-slate-700 mb-2">Pays</label>
                            <select id="filter_country" name="filter_country" class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                <option value="">Tous les pays</option>
                                <option value="Côte d'Ivoire" selected>Côte d'Ivoire</option>
                                <option value="Ghana">Ghana</option>
                                <option value="Burkina Faso">Burkina Faso</option>
                                <option value="Mali">Mali</option>
                                <option value="Sénégal">Sénégal</option>
                            </select>
                        </div>

                        <div>
                            <label for="filter_region" class="block text-sm font-medium text-slate-700 mb-2">Région</label>
                            <select id="filter_region" name="filter_region" class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                <option value="">Toutes les régions</option>
                                <option value="Abidjan">Région d'Abidjan</option>
                                <option value="Yamoussoukro">Région de Yamoussoukro</option>
                                <option value="Bouaké">Région de Bouaké</option>
                                <option value="San Pedro">Région de San Pedro</option>
                            </select>
                        </div>

                        <div>
                            <label for="filter_city" class="block text-sm font-medium text-slate-700 mb-2">Ville</label>
                            <select id="filter_city" name="filter_city" multiple class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                <option value="Abidjan">Abidjan</option>
                                <option value="Yamoussoukro">Yamoussoukro</option>
                                <option value="Bouaké">Bouaké</option>
                                <option value="Daloa">Daloa</option>
                                <option value="San Pedro">San Pedro</option>
                                <option value="Korhogo">Korhogo</option>
                                <option value="Man">Man</option>
                                <option value="Divo">Divo</option>
                            </select>
                        </div>

                        <div>
                            <label for="filter_district" class="block text-sm font-medium text-slate-700 mb-2">Quartier/District</label>
                            <input type="text" id="filter_district" name="filter_district" placeholder="Ex: Cocody, Marcory..."
                                class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        </div>
                    </div>

                    <!-- Recherche par proximité -->
                    <div class="mt-6 p-4 bg-blue-50 border border-blue-200 rounded-xl">
                        <h4 class="font-semibold text-blue-800 mb-3 flex items-center">
                            <i class="fas fa-crosshairs mr-2"></i>
                            Recherche par proximité
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label for="proximity_lat" class="block text-sm font-medium text-blue-700 mb-1">Latitude</label>
                                <input type="number" id="proximity_lat" name="proximity_lat" step="any" placeholder="5.3600"
                                    class="w-full px-3 py-2 border border-blue-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            </div>
                            <div>
                                <label for="proximity_lng" class="block text-sm font-medium text-blue-700 mb-1">Longitude</label>
                                <input type="number" id="proximity_lng" name="proximity_lng" step="any" placeholder="-4.0083"
                                    class="w-full px-3 py-2 border border-blue-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            </div>
                            <div>
                                <label for="proximity_radius" class="block text-sm font-medium text-blue-700 mb-1">Rayon (km)</label>
                                <input type="number" id="proximity_radius" name="proximity_radius" min="1" max="100" value="10" placeholder="10"
                                    class="w-full px-3 py-2 border border-blue-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            </div>
                        </div>
                        <button type="button" onclick="useMyLocation()" class="mt-3 inline-flex items-center px-3 py-1 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                            <i class="fas fa-location-arrow mr-1"></i> Utiliser ma position
                        </button>
                    </div>
                </div>
            </div>

            <!-- Critères avancés -->
            <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="p-6 border-b border-slate-200">
                    <h2 class="text-xl font-bold text-slate-800 flex items-center">
                        <i class="fas fa-cogs text-purple-600 mr-2"></i>
                        Critères Avancés
                    </h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="capacity_min" class="block text-sm font-medium text-slate-700 mb-2">Capacité d'accueil minimum</label>
                            <input type="number" id="capacity_min" name="capacity_min" min="0" placeholder="50"
                                class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        </div>

                        <div>
                            <label for="capacity_max" class="block text-sm font-medium text-slate-700 mb-2">Capacité d'accueil maximum</label>
                            <input type="number" id="capacity_max" name="capacity_max" min="0" placeholder="1000"
                                class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        </div>

                        <div>
                            <label for="members_min" class="block text-sm font-medium text-slate-700 mb-2">Nombre de membres minimum</label>
                            <input type="number" id="members_min" name="members_min" min="0" placeholder="25"
                                class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        </div>

                        <div>
                            <label for="members_max" class="block text-sm font-medium text-slate-700 mb-2">Nombre de membres maximum</label>
                            <input type="number" id="members_max" name="members_max" min="0" placeholder="500"
                                class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        </div>
                    </div>

                    <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="date_created_from" class="block text-sm font-medium text-slate-700 mb-2">Créé à partir du</label>
                            <input type="date" id="date_created_from" name="date_created_from"
                                class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        </div>

                        <div>
                            <label for="date_created_to" class="block text-sm font-medium text-slate-700 mb-2">Créé jusqu'au</label>
                            <input type="date" id="date_created_to" name="date_created_to"
                                class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Boutons d'action -->
            <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg border border-white/20">
                <div class="p-6">
                    <div class="flex flex-col sm:flex-row gap-4 justify-center">
                        <button type="button" onclick="executeSearch()" class="inline-flex items-center justify-center px-8 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-medium rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow-md hover:shadow-lg">
                            <i class="fas fa-search mr-2"></i> Lancer la Recherche
                        </button>
                        <button type="button" onclick="previewSearch()" class="inline-flex items-center justify-center px-8 py-3 bg-gradient-to-r from-green-600 to-emerald-600 text-white font-medium rounded-xl hover:from-green-700 hover:to-emerald-700 transition-all duration-200 shadow-md hover:shadow-lg">
                            <i class="fas fa-eye mr-2"></i> Aperçu
                        </button>
                        <button type="button" onclick="exportResults()" class="inline-flex items-center justify-center px-8 py-3 bg-gradient-to-r from-amber-600 to-orange-600 text-white font-medium rounded-xl hover:from-amber-700 hover:to-orange-700 transition-all duration-200 shadow-md hover:shadow-lg">
                            <i class="fas fa-download mr-2"></i> Exporter
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Colonne de droite -->
        <div class="space-y-6">
            <!-- Recherches sauvegardées -->
            <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="p-6 border-b border-slate-200">
                    <h3 class="text-xl font-bold text-slate-800 flex items-center">
                        <i class="fas fa-bookmark text-amber-600 mr-2"></i>
                        Recherches Sauvegardées
                    </h3>
                </div>
                <div class="p-6">
                    <div class="space-y-3">
                        <div class="p-3 border border-slate-200 rounded-lg hover:bg-slate-50 transition-colors cursor-pointer" onclick="loadSavedSearch('eglises-abidjan')">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h4 class="font-medium text-slate-800">Églises d'Abidjan</h4>
                                    <p class="text-xs text-slate-500">125 résultats</p>
                                </div>
                                <button onclick="event.stopPropagation(); deleteSavedSearch('eglises-abidjan')" class="text-red-500 hover:text-red-700">
                                    <i class="fas fa-trash text-sm"></i>
                                </button>
                            </div>
                        </div>

                        <div class="p-3 border border-slate-200 rounded-lg hover:bg-slate-50 transition-colors cursor-pointer" onclick="loadSavedSearch('pasteurs-baptistes')">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h4 class="font-medium text-slate-800">Pasteurs Baptistes</h4>
                                    <p class="text-xs text-slate-500">43 résultats</p>
                                </div>
                                <button onclick="event.stopPropagation(); deleteSavedSearch('pasteurs-baptistes')" class="text-red-500 hover:text-red-700">
                                    <i class="fas fa-trash text-sm"></i>
                                </button>
                            </div>
                        </div>

                        <div class="p-3 border border-slate-200 rounded-lg hover:bg-slate-50 transition-colors cursor-pointer" onclick="loadSavedSearch('urgences-cocody')">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h4 class="font-medium text-slate-800">Urgences Cocody</h4>
                                    <p class="text-xs text-slate-500">8 résultats</p>
                                </div>
                                <button onclick="event.stopPropagation(); deleteSavedSearch('urgences-cocody')" class="text-red-500 hover:text-red-700">
                                    <i class="fas fa-trash text-sm"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Résultats en temps réel -->
            <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="p-6 border-b border-slate-200">
                    <h3 class="text-xl font-bold text-slate-800 flex items-center">
                        <i class="fas fa-chart-bar text-green-600 mr-2"></i>
                        Aperçu des Résultats
                        <span id="resultCount" class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">0</span>
                    </h3>
                </div>
                <div class="p-6">
                    <div id="resultsPreview" class="space-y-4">
                        <div class="text-center py-8 text-slate-500">
                            <i class="fas fa-search text-3xl mb-2"></i>
                            <p>Lancez une recherche pour voir un aperçu des résultats</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistiques de recherche -->
            <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="p-6 border-b border-slate-200">
                    <h3 class="text-xl font-bold text-slate-800 flex items-center">
                        <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                        Statistiques
                    </h3>
                </div>
                <div class="p-6 space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-slate-700">Total contacts</span>
                        <span class="text-sm font-bold text-slate-900">1,247</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-slate-700">Vérifiés</span>
                        <span class="text-sm font-bold text-green-600">1,089</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-slate-700">Publics</span>
                        <span class="text-sm font-bold text-blue-600">967</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-slate-700">Géolocalisés</span>
                        <span class="text-sm font-bold text-purple-600">756</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-slate-700">Avec site web</span>
                        <span class="text-sm font-bold text-amber-600">423</span>
                    </div>
                </div>
            </div>

            <!-- Aide -->
            <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="p-6 border-b border-slate-200">
                    <h3 class="text-xl font-bold text-slate-800 flex items-center">
                        <i class="fas fa-question-circle text-slate-600 mr-2"></i>
                        Aide
                    </h3>
                </div>
                <div class="p-6 space-y-3">
                    <div class="p-3 bg-blue-50 border border-blue-200 rounded-lg">
                        <h4 class="font-medium text-blue-800 text-sm mb-1">Recherche textuelle</h4>
                        <p class="text-xs text-blue-700">Utilisez * pour les caractères jokers (ex: Bap*)</p>
                    </div>

                    <div class="p-3 bg-green-50 border border-green-200 rounded-lg">
                        <h4 class="font-medium text-green-800 text-sm mb-1">Filtres multiples</h4>
                        <p class="text-xs text-green-700">Maintenez Ctrl pour sélectionner plusieurs options</p>
                    </div>

                    <div class="p-3 bg-amber-50 border border-amber-200 rounded-lg">
                        <h4 class="font-medium text-amber-800 text-sm mb-1">Sauvegarde</h4>
                        <p class="text-xs text-amber-700">Sauvegardez vos recherches fréquentes pour un accès rapide</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Variables globales
let searchResults = [];
let isSearching = false;

// Exécuter la recherche
function executeSearch() {
    if (isSearching) return;

    isSearching = true;
    document.getElementById('resultCount').textContent = '...';

    // Simuler une recherche
    setTimeout(() => {
        // Collecter les critères de recherche
        const searchCriteria = collectSearchCriteria();

        // Simuler les résultats
        const mockResults = generateMockResults(searchCriteria);
        searchResults = mockResults;

        // Mettre à jour l'interface
        updateResultsPreview(mockResults);
        document.getElementById('resultCount').textContent = mockResults.length;

        isSearching = false;

        // Rediriger vers la liste avec les paramètres de recherche
        if (mockResults.length > 0) {
            const params = new URLSearchParams(searchCriteria).toString();
            window.location.href = `{{ route('private.contacts.index') }}?${params}&advanced=1`;
        } else {
            alert('Aucun résultat trouvé pour ces critères de recherche.');
        }
    }, 1500);
}

// Aperçu de la recherche
function previewSearch() {
    const searchCriteria = collectSearchCriteria();
    const mockResults = generateMockResults(searchCriteria);

    updateResultsPreview(mockResults.slice(0, 5)); // Afficher seulement 5 résultats en aperçu
    document.getElementById('resultCount').textContent = mockResults.length;
}

// Collecter les critères de recherche
function collectSearchCriteria() {
    return {
        search_name: document.getElementById('search_name').value,
        search_pastor: document.getElementById('search_pastor').value,
        search_phone: document.getElementById('search_phone').value,
        search_email: document.getElementById('search_email').value,
        search_description: document.getElementById('search_description').value,
        filter_type: Array.from(document.getElementById('filter_type').selectedOptions).map(o => o.value),
        filter_denomination: Array.from(document.getElementById('filter_denomination').selectedOptions).map(o => o.value),
        filter_status: Array.from(document.getElementById('filter_status').selectedOptions).map(o => o.value),
        filter_city: Array.from(document.getElementById('filter_city').selectedOptions).map(o => o.value),
        filter_district: document.getElementById('filter_district').value,
        capacity_min: document.getElementById('capacity_min').value,
        capacity_max: document.getElementById('capacity_max').value,
        members_min: document.getElementById('members_min').value,
        members_max: document.getElementById('members_max').value,
        proximity_lat: document.getElementById('proximity_lat').value,
        proximity_lng: document.getElementById('proximity_lng').value,
        proximity_radius: document.getElementById('proximity_radius').value
    };
}

// Générer des résultats simulés
function generateMockResults(criteria) {
    const mockContacts = [
        { id: 1, nom_eglise: "Église Baptiste de la Paix", type_contact: "principal", ville: "Abidjan", denomination: "Baptist", verified: true },
        { id: 2, nom_eglise: "Assemblée de Dieu Central", type_contact: "pastoral", ville: "Abidjan", denomination: "Assemblies of God", verified: true },
        { id: 3, nom_eglise: "Église Méthodiste Unie", type_contact: "administratif", ville: "Yamoussoukro", denomination: "Methodist", verified: false },
        { id: 4, nom_eglise: "Centre Évangélique", type_contact: "principal", ville: "Bouaké", denomination: "Evangelical", verified: true },
        { id: 5, nom_eglise: "Église Pentecôtiste", type_contact: "jeunesse", ville: "Abidjan", denomination: "Pentecostal", verified: true }
    ];

    // Filtrer selon les critères
    return mockContacts.filter(contact => {
        if (criteria.search_name && !contact.nom_eglise.toLowerCase().includes(criteria.search_name.toLowerCase())) return false;
        if (criteria.filter_type.length > 0 && !criteria.filter_type.includes(contact.type_contact)) return false;
        if (criteria.filter_denomination.length > 0 && !criteria.filter_denomination.includes(contact.denomination)) return false;
        if (criteria.filter_city.length > 0 && !criteria.filter_city.includes(contact.ville)) return false;
        if (criteria.filter_status.includes('verified') && !contact.verified) return false;
        if (criteria.filter_status.includes('unverified') && contact.verified) return false;

        return true;
    });
}

// Mettre à jour l'aperçu des résultats
function updateResultsPreview(results) {
    const container = document.getElementById('resultsPreview');

    if (results.length === 0) {
        container.innerHTML = `
            <div class="text-center py-8 text-slate-500">
                <i class="fas fa-search-minus text-3xl mb-2"></i>
                <p>Aucun résultat trouvé</p>
            </div>
        `;
        return;
    }

    container.innerHTML = results.map(contact => `
        <div class="p-3 border border-slate-200 rounded-lg hover:bg-slate-50 transition-colors">
            <div class="flex items-center justify-between">
                <div>
                    <h4 class="font-medium text-slate-800 text-sm">${contact.nom_eglise}</h4>
                    <div class="flex items-center space-x-2 mt-1">
                        <span class="px-2 py-0.5 bg-blue-100 text-blue-700 rounded text-xs">${contact.type_contact}</span>
                        <span class="text-xs text-slate-500">${contact.ville}</span>
                    </div>
                </div>
                ${contact.verified ? '<i class="fas fa-check-circle text-green-500"></i>' : '<i class="fas fa-clock text-amber-500"></i>'}
            </div>
        </div>
    `).join('');
}

// Utiliser ma position
function useMyLocation() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(position) {
            document.getElementById('proximity_lat').value = position.coords.latitude.toFixed(6);
            document.getElementById('proximity_lng').value = position.coords.longitude.toFixed(6);
        });
    } else {
        alert('Géolocalisation non supportée par ce navigateur');
    }
}

// Sauvegarder la recherche
function saveSearch() {
    const name = prompt('Nom de la recherche sauvegardée:');
    if (name) {
        const criteria = collectSearchCriteria();
        // Ici vous sauvegarderiez en base de données
        alert(`Recherche "${name}" sauvegardée avec succès!`);
    }
}

// Charger une recherche sauvegardée
function loadSavedSearch(searchId) {
    // Simuler le chargement d'une recherche sauvegardée
    switch(searchId) {
        case 'eglises-abidjan':
            document.getElementById('filter_city').value = 'Abidjan';
            break;
        case 'pasteurs-baptistes':
            document.getElementById('filter_denomination').value = 'Baptist';
            document.getElementById('filter_type').value = 'pastoral';
            break;
        case 'urgences-cocody':
            document.getElementById('filter_type').value = 'urgence';
            document.getElementById('filter_district').value = 'Cocody';
            break;
    }
    previewSearch();
}

// Supprimer une recherche sauvegardée
function deleteSavedSearch(searchId) {
    if (confirm('Êtes-vous sûr de vouloir supprimer cette recherche sauvegardée ?')) {
        // Ici vous supprimeriez de la base de données
        alert('Recherche supprimée avec succès!');
        location.reload();
    }
}

// Réinitialiser la recherche
function resetSearch() {
    document.querySelectorAll('input, select, textarea').forEach(element => {
        if (element.type === 'checkbox') {
            element.checked = false;
        } else if (element.tagName === 'SELECT') {
            element.selectedIndex = 0;
        } else {
            element.value = '';
        }
    });

    document.getElementById('resultsPreview').innerHTML = `
        <div class="text-center py-8 text-slate-500">
            <i class="fas fa-search text-3xl mb-2"></i>
            <p>Lancez une recherche pour voir un aperçu des résultats</p>
        </div>
    `;
    document.getElementById('resultCount').textContent = '0';
}

// Exporter les résultats
function exportResults() {
    if (searchResults.length === 0) {
        alert('Aucun résultat à exporter. Lancez d\'abord une recherche.');
        return;
    }

    const format = prompt('Format d\'export:\n1. CSV\n2. Excel\n3. PDF\n\nEntrez le numéro (1, 2 ou 3):');

    let exportFormat = 'csv';
    switch(format) {
        case '1':
            exportFormat = 'csv';
            break;
        case '2':
            exportFormat = 'excel';
            break;
        case '3':
            exportFormat = 'pdf';
            break;
        default:
            alert('Format non valide');
            return;
    }

    const criteria = collectSearchCriteria();
    const params = new URLSearchParams({...criteria, format: exportFormat}).toString();
    window.open(`{{ route('private.contacts.export') }}?${params}`, '_blank');
}

// Aperçu automatique lors de la saisie
document.addEventListener('DOMContentLoaded', function() {
    // Ajouter des écouteurs pour l'aperçu automatique
    const inputs = document.querySelectorAll('input, select, textarea');
    inputs.forEach(input => {
        input.addEventListener('change', previewSearch);
    });
});
</script>
@endpush

@endsection
