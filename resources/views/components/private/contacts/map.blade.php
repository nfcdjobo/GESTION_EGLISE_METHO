@extends('layouts.private.main')
@section('title', 'Carte des Contacts')

@section('content')
<div class="space-y-6">
    <!-- Page Title -->
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent">Carte Interactive des Contacts</h1>
                <p class="text-slate-500 mt-1">Visualisation géographique des églises et contacts</p>
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('private.contacts.index') }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-slate-600 to-gray-600 text-white text-sm font-medium rounded-xl hover:from-slate-700 hover:to-gray-700 transition-all duration-200 shadow-md hover:shadow-lg">
                    <i class="fas fa-list mr-2"></i> Liste
                </a>
                <a href="{{ route('private.contacts.statistics') }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-purple-600 to-pink-600 text-white text-sm font-medium rounded-xl hover:from-purple-700 hover:to-pink-700 transition-all duration-200 shadow-md hover:shadow-lg">
                    <i class="fas fa-chart-bar mr-2"></i> Statistiques
                </a>
                <button onclick="toggleFullscreen()" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-600 to-cyan-600 text-white text-sm font-medium rounded-xl hover:from-blue-700 hover:to-cyan-700 transition-all duration-200 shadow-md hover:shadow-lg">
                    <i class="fas fa-expand mr-2"></i> Plein écran
                </button>
            </div>
        </div>
    </div>

    <!-- Filtres et contrôles -->
    <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
        <div class="p-6 border-b border-slate-200">
            <h2 class="text-xl font-bold text-slate-800 flex items-center">
                <i class="fas fa-filter text-blue-600 mr-2"></i>
                Filtres et Contrôles
            </h2>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Type de contact</label>
                    <select id="filterType" onchange="filterContacts()" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        <option value="">Tous les types</option>
                        <option value="principal">Principal</option>
                        <option value="pastoral">Pastoral</option>
                        <option value="administratif">Administratif</option>
                        <option value="urgence">Urgence</option>
                        <option value="jeunesse">Jeunesse</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Ville</label>
                    <select id="filterCity" onchange="filterContacts()" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        <option value="">Toutes les villes</option>
                        <option value="Abidjan">Abidjan</option>
                        <option value="Yamoussoukro">Yamoussoukro</option>
                        <option value="Bouaké">Bouaké</option>
                        <option value="San-Pédro">San-Pédro</option>
                        <option value="Daloa">Daloa</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Statut</label>
                    <select id="filterStatus" onchange="filterContacts()" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        <option value="">Tous</option>
                        <option value="verified">Vérifiés</option>
                        <option value="unverified">Non vérifiés</option>
                        <option value="public">Publics</option>
                        <option value="private">Privés</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Rayon (km)</label>
                    <input type="range" id="radiusFilter" min="1" max="100" value="50" oninput="updateRadius(this.value)" class="w-full">
                    <div class="flex justify-between text-xs text-slate-500 mt-1">
                        <span>1km</span>
                        <span id="radiusValue">50km</span>
                        <span>100km</span>
                    </div>
                </div>

                <div class="flex flex-col justify-end space-y-2">
                    <button onclick="centerOnUser()" class="inline-flex items-center justify-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-xl hover:bg-green-700 transition-colors">
                        <i class="fas fa-location-arrow mr-2"></i> Ma position
                    </button>
                    <button onclick="resetFilters()" class="inline-flex items-center justify-center px-4 py-2 bg-slate-600 text-white text-sm font-medium rounded-xl hover:bg-slate-700 transition-colors">
                        <i class="fas fa-refresh mr-2"></i> Reset
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistiques rapides de la carte -->
    <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
        <div class="bg-white/80 rounded-xl shadow-lg p-4 border border-white/20">
            <div class="flex items-center">
                <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                    <i class="fas fa-map-marker-alt text-blue-600"></i>
                </div>
                <div>
                    <p id="totalVisible" class="text-lg font-bold text-slate-900">-</p>
                    <p class="text-xs text-slate-500">Visibles</p>
                </div>
            </div>
        </div>

        <div class="bg-white/80 rounded-xl shadow-lg p-4 border border-white/20">
            <div class="flex items-center">
                <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                    <i class="fas fa-check-circle text-green-600"></i>
                </div>
                <div>
                    <p id="totalVerified" class="text-lg font-bold text-slate-900">-</p>
                    <p class="text-xs text-slate-500">Vérifiés</p>
                </div>
            </div>
        </div>

        <div class="bg-white/80 rounded-xl shadow-lg p-4 border border-white/20">
            <div class="flex items-center">
                <div class="w-8 h-8 bg-amber-100 rounded-lg flex items-center justify-center mr-3">
                    <i class="fas fa-clock text-amber-600"></i>
                </div>
                <div>
                    <p id="totalPending" class="text-lg font-bold text-slate-900">-</p>
                    <p class="text-xs text-slate-500">En attente</p>
                </div>
            </div>
        </div>

        <div class="bg-white/80 rounded-xl shadow-lg p-4 border border-white/20">
            <div class="flex items-center">
                <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center mr-3">
                    <i class="fas fa-eye text-purple-600"></i>
                </div>
                <div>
                    <p id="totalPublic" class="text-lg font-bold text-slate-900">-</p>
                    <p class="text-xs text-slate-500">Publics</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Carte -->
    <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
        <div class="p-6 border-b border-slate-200">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-bold text-slate-800 flex items-center">
                    <i class="fas fa-map text-green-600 mr-2"></i>
                    Carte Interactive
                </h2>
                <div class="flex items-center space-x-4">
                    <div class="flex items-center space-x-2 text-sm">
                        <div class="flex items-center space-x-1">
                            <div class="w-3 h-3 bg-blue-500 rounded-full"></div>
                            <span class="text-slate-600">Principal</span>
                        </div>
                        <div class="flex items-center space-x-1">
                            <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                            <span class="text-slate-600">Pastoral</span>
                        </div>
                        <div class="flex items-center space-x-1">
                            <div class="w-3 h-3 bg-purple-500 rounded-full"></div>
                            <span class="text-slate-600">Administratif</span>
                        </div>
                        <div class="flex items-center space-x-1">
                            <div class="w-3 h-3 bg-red-500 rounded-full"></div>
                            <span class="text-slate-600">Urgence</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="p-6">
            <!-- Carte placeholder - À remplacer par une vraie carte (Google Maps, OpenStreetMap, etc.) -->
            <div id="map" class="w-full h-96 lg:h-[600px] bg-gradient-to-br from-blue-100 to-green-100 rounded-xl relative overflow-hidden">
                <!-- Interface de la carte simulée -->
                <div class="absolute inset-0 flex items-center justify-center">
                    <div class="text-center">
                        <div class="w-16 h-16 bg-blue-500 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-map text-white text-2xl"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-slate-800 mb-2">Carte Interactive</h3>
                        <p class="text-slate-600 mb-4">Intégrez ici votre service de cartographie préféré</p>
                        <div class="text-sm text-slate-500">
                            <p>• Google Maps API</p>
                            <p>• OpenStreetMap avec Leaflet</p>
                            <p>• Mapbox</p>
                        </div>
                    </div>
                </div>

                <!-- Marqueurs simulés -->
                <div class="absolute top-20 left-20 w-4 h-4 bg-blue-500 rounded-full border-2 border-white shadow-lg cursor-pointer hover:scale-110 transition-transform" title="Église Baptiste - Cocody"></div>
                <div class="absolute top-32 left-40 w-4 h-4 bg-green-500 rounded-full border-2 border-white shadow-lg cursor-pointer hover:scale-110 transition-transform" title="Assemblée de Dieu - Marcory"></div>
                <div class="absolute top-28 left-60 w-4 h-4 bg-purple-500 rounded-full border-2 border-white shadow-lg cursor-pointer hover:scale-110 transition-transform" title="Église Méthodiste - Plateau"></div>
                <div class="absolute bottom-40 right-32 w-4 h-4 bg-red-500 rounded-full border-2 border-white shadow-lg cursor-pointer hover:scale-110 transition-transform" title="Contact Urgence - Adjamé"></div>
                <div class="absolute bottom-20 right-20 w-4 h-4 bg-yellow-500 rounded-full border-2 border-white shadow-lg cursor-pointer hover:scale-110 transition-transform" title="Ministère Jeunesse - Yopougon"></div>

                <!-- Contrôles de zoom simulés -->
                <div class="absolute top-4 right-4 flex flex-col space-y-2">
                    <button onclick="zoomIn()" class="w-10 h-10 bg-white border border-slate-300 rounded-lg flex items-center justify-center shadow-lg hover:bg-slate-50 transition-colors">
                        <i class="fas fa-plus text-slate-600"></i>
                    </button>
                    <button onclick="zoomOut()" class="w-10 h-10 bg-white border border-slate-300 rounded-lg flex items-center justify-center shadow-lg hover:bg-slate-50 transition-colors">
                        <i class="fas fa-minus text-slate-600"></i>
                    </button>
                </div>

                <!-- Panneau d'information -->
                <div id="infoPanel" class="absolute bottom-4 left-4 bg-white/95 backdrop-blur-sm border border-white/20 rounded-xl p-4 shadow-lg max-w-sm hidden">
                    <div class="flex items-center justify-between mb-2">
                        <h4 id="infoTitle" class="font-semibold text-slate-800">Nom de l'église</h4>
                        <button onclick="closeInfoPanel()" class="text-slate-400 hover:text-slate-600">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <div id="infoContent" class="text-sm text-slate-600">
                        <p><strong>Type:</strong> <span id="infoType">-</span></p>
                        <p><strong>Adresse:</strong> <span id="infoAddress">-</span></p>
                        <p><strong>Téléphone:</strong> <span id="infoPhone">-</span></p>
                        <p><strong>Email:</strong> <span id="infoEmail">-</span></p>
                    </div>
                    <div class="mt-3 flex space-x-2">
                        <button onclick="viewContact()" class="px-3 py-1 bg-blue-600 text-white text-xs rounded-lg hover:bg-blue-700 transition-colors">
                            <i class="fas fa-eye mr-1"></i> Voir
                        </button>
                        <button onclick="getDirections()" class="px-3 py-1 bg-green-600 text-white text-xs rounded-lg hover:bg-green-700 transition-colors">
                            <i class="fas fa-directions mr-1"></i> Itinéraire
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Liste des contacts visibles -->
    <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
        <div class="p-6 border-b border-slate-200">
            <h2 class="text-xl font-bold text-slate-800 flex items-center">
                <i class="fas fa-list text-purple-600 mr-2"></i>
                Contacts Visibles sur la Carte
                <span id="visibleCount" class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">0</span>
            </h2>
        </div>
        <div class="p-6">
            <div id="contactsList" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <!-- Les contacts seront insérés ici dynamiquement -->
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Données simulées des contacts (remplacer par de vraies données depuis le backend)
const contacts = [
    {
        id: 1,
        nom_eglise: "Église Baptiste de la Paix",
        type_contact: "principal",
        ville: "Abidjan",
        quartier: "Cocody",
        latitude: 5.3600,
        longitude: -4.0083,
        telephone_principal: "+225 27 22 44 55 66",
        email_principal: "contact@baptistepaix.org",
        verifie: true,
        visible_public: true
    },
    {
        id: 2,
        nom_eglise: "Assemblée de Dieu Central",
        type_contact: "pastoral",
        ville: "Abidjan",
        quartier: "Marcory",
        latitude: 5.3100,
        longitude: -4.0200,
        telephone_principal: "+225 27 21 33 44 55",
        email_principal: "pasteur@add-central.ci",
        verifie: true,
        visible_public: true
    },
    {
        id: 3,
        nom_eglise: "Église Méthodiste Unie",
        type_contact: "administratif",
        ville: "Abidjan",
        quartier: "Plateau",
        latitude: 5.3200,
        longitude: -4.0300,
        telephone_principal: "+225 27 20 22 33 44",
        email_principal: "admin@methodiste.ci",
        verifie: false,
        visible_public: true
    },
    {
        id: 4,
        nom_eglise: "Contact Urgence Spirituelle",
        type_contact: "urgence",
        ville: "Abidjan",
        quartier: "Adjamé",
        latitude: 5.3400,
        longitude: -4.0100,
        telephone_principal: "+225 27 20 11 22 33",
        email_principal: "urgence@spiritual.ci",
        verifie: true,
        visible_public: false
    },
    {
        id: 5,
        nom_eglise: "Ministère Jeunesse Active",
        type_contact: "jeunesse",
        ville: "Abidjan",
        quartier: "Yopougon",
        latitude: 5.3500,
        longitude: -4.0400,
        telephone_principal: "+225 27 23 44 55 66",
        email_principal: "jeunes@active.ci",
        verifie: true,
        visible_public: true
    }
];

let filteredContacts = [...contacts];
let currentContact = null;

// Initialiser la carte
document.addEventListener('DOMContentLoaded', function() {
    updateContactsList();
    updateStatistics();
});

// Filtrer les contacts
function filterContacts() {
    const typeFilter = document.getElementById('filterType').value;
    const cityFilter = document.getElementById('filterCity').value;
    const statusFilter = document.getElementById('filterStatus').value;

    filteredContacts = contacts.filter(contact => {
        if (typeFilter && contact.type_contact !== typeFilter) return false;
        if (cityFilter && contact.ville !== cityFilter) return false;

        if (statusFilter) {
            switch(statusFilter) {
                case 'verified':
                    if (!contact.verifie) return false;
                    break;
                case 'unverified':
                    if (contact.verifie) return false;
                    break;
                case 'public':
                    if (!contact.visible_public) return false;
                    break;
                case 'private':
                    if (contact.visible_public) return false;
                    break;
            }
        }

        return true;
    });

    updateContactsList();
    updateStatistics();
}

// Mettre à jour la liste des contacts
function updateContactsList() {
    const container = document.getElementById('contactsList');

    if (filteredContacts.length === 0) {
        container.innerHTML = `
            <div class="col-span-full text-center py-8">
                <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-search text-slate-400 text-2xl"></i>
                </div>
                <p class="text-slate-500">Aucun contact ne correspond aux filtres sélectionnés</p>
            </div>
        `;
        return;
    }

    container.innerHTML = filteredContacts.map(contact => `
        <div class="p-4 border border-slate-200 rounded-xl hover:shadow-md transition-all duration-200 cursor-pointer" onclick="selectContact(${contact.id})">
            <div class="flex items-center space-x-3 mb-3">
                <div class="w-10 h-10 bg-gradient-to-r ${getContactColors(contact.type_contact)} rounded-full flex items-center justify-center">
                    <span class="text-white font-semibold text-sm">${contact.nom_eglise.substring(0, 2)}</span>
                </div>
                <div class="flex-1">
                    <h4 class="font-semibold text-slate-800 text-sm">${contact.nom_eglise}</h4>
                    <p class="text-xs text-slate-500">${contact.quartier}, ${contact.ville}</p>
                </div>
            </div>

            <div class="flex items-center justify-between mb-2">
                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium ${getTypeBadgeClass(contact.type_contact)}">
                    ${contact.type_contact.charAt(0).toUpperCase() + contact.type_contact.slice(1)}
                </span>
                <div class="flex space-x-1">
                    ${contact.verifie ? '<i class="fas fa-check-circle text-green-500 text-sm" title="Vérifié"></i>' : '<i class="fas fa-clock text-amber-500 text-sm" title="En attente"></i>'}
                    ${contact.visible_public ? '<i class="fas fa-eye text-blue-500 text-sm" title="Public"></i>' : '<i class="fas fa-eye-slash text-slate-400 text-sm" title="Privé"></i>'}
                </div>
            </div>

            <div class="text-xs text-slate-600 space-y-1">
                ${contact.telephone_principal ? `<div><i class="fas fa-phone w-3"></i> ${contact.telephone_principal}</div>` : ''}
                ${contact.email_principal ? `<div><i class="fas fa-envelope w-3"></i> ${contact.email_principal}</div>` : ''}
            </div>

            <div class="mt-3 flex space-x-2">
                <button onclick="event.stopPropagation(); viewContact(${contact.id})" class="flex-1 px-3 py-1 bg-blue-600 text-white text-xs rounded-lg hover:bg-blue-700 transition-colors">
                    <i class="fas fa-eye mr-1"></i> Voir
                </button>
                <button onclick="event.stopPropagation(); focusOnMap(${contact.id})" class="flex-1 px-3 py-1 bg-green-600 text-white text-xs rounded-lg hover:bg-green-700 transition-colors">
                    <i class="fas fa-map-marker-alt mr-1"></i> Localiser
                </button>
            </div>
        </div>
    `).join('');
}

// Mettre à jour les statistiques
function updateStatistics() {
    document.getElementById('totalVisible').textContent = filteredContacts.length;
    document.getElementById('totalVerified').textContent = filteredContacts.filter(c => c.verifie).length;
    document.getElementById('totalPending').textContent = filteredContacts.filter(c => !c.verifie).length;
    document.getElementById('totalPublic').textContent = filteredContacts.filter(c => c.visible_public).length;
    document.getElementById('visibleCount').textContent = filteredContacts.length;
}

// Obtenir les couleurs selon le type de contact
function getContactColors(type) {
    switch(type) {
        case 'principal': return 'from-blue-500 to-blue-600';
        case 'pastoral': return 'from-green-500 to-green-600';
        case 'administratif': return 'from-purple-500 to-purple-600';
        case 'urgence': return 'from-red-500 to-red-600';
        case 'jeunesse': return 'from-yellow-500 to-yellow-600';
        default: return 'from-gray-500 to-gray-600';
    }
}

// Obtenir la classe de badge selon le type
function getTypeBadgeClass(type) {
    switch(type) {
        case 'principal': return 'bg-blue-100 text-blue-800';
        case 'pastoral': return 'bg-green-100 text-green-800';
        case 'administratif': return 'bg-purple-100 text-purple-800';
        case 'urgence': return 'bg-red-100 text-red-800';
        case 'jeunesse': return 'bg-yellow-100 text-yellow-800';
        default: return 'bg-gray-100 text-gray-800';
    }
}

// Sélectionner un contact
function selectContact(contactId) {
    currentContact = contacts.find(c => c.id === contactId);
    showInfoPanel(currentContact);
}

// Afficher le panneau d'informations
function showInfoPanel(contact) {
    document.getElementById('infoTitle').textContent = contact.nom_eglise;
    document.getElementById('infoType').textContent = contact.type_contact.charAt(0).toUpperCase() + contact.type_contact.slice(1);
    document.getElementById('infoAddress').textContent = `${contact.quartier}, ${contact.ville}`;
    document.getElementById('infoPhone').textContent = contact.telephone_principal || '-';
    document.getElementById('infoEmail').textContent = contact.email_principal || '-';
    document.getElementById('infoPanel').classList.remove('hidden');
}

// Fermer le panneau d'informations
function closeInfoPanel() {
    document.getElementById('infoPanel').classList.add('hidden');
    currentContact = null;
}

// Centrer sur l'membres
function centerOnUser() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(position) {
            alert(`Position actuelle: ${position.coords.latitude.toFixed(6)}, ${position.coords.longitude.toFixed(6)}`);
        });
    } else {
        alert('Géolocalisation non supportée par ce navigateur');
    }
}

// Réinitialiser les filtres
function resetFilters() {
    document.getElementById('filterType').value = '';
    document.getElementById('filterCity').value = '';
    document.getElementById('filterStatus').value = '';
    document.getElementById('radiusFilter').value = 50;
    document.getElementById('radiusValue').textContent = '50km';
    filterContacts();
}

// Mettre à jour le rayon
function updateRadius(value) {
    document.getElementById('radiusValue').textContent = value + 'km';
}

// Contrôles de zoom
function zoomIn() {
    console.log('Zoom in');
}

function zoomOut() {
    console.log('Zoom out');
}

// Voir un contact
function viewContact(contactId = null) {
    const id = contactId || (currentContact ? currentContact.id : null);
    if (id) {
        window.open(`{{route('private.contacts.show', ':contact')}}`.replace(':contact', id), '_blank');
    }
}

// Obtenir des directions
function getDirections() {
    if (currentContact) {
        const url = `https://www.google.com/maps/dir/?api=1&destination=${currentContact.latitude},${currentContact.longitude}`;
        window.open(url, '_blank');
    }
}

// Focaliser sur la carte
function focusOnMap(contactId) {
    const contact = contacts.find(c => c.id === contactId);
    if (contact) {
        selectContact(contactId);
        // Ici vous pourriez centrer la vraie carte sur les coordonnées
        console.log(`Focusing on: ${contact.latitude}, ${contact.longitude}`);
    }
}

// Mode plein écran
function toggleFullscreen() {
    const mapContainer = document.getElementById('map');
    if (!document.fullscreenElement) {
        mapContainer.requestFullscreen();
    } else {
        document.exitFullscreen();
    }
}
</script>
@endpush

@endsection
