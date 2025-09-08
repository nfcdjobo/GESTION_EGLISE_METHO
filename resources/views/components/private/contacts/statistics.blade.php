@extends('layouts.private.main')
@section('title', 'Statistiques des Contacts')

@section('content')
<div class="space-y-8">
    <!-- Page Title -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent">Statistiques des Contacts</h1>
                <p class="text-slate-500 mt-1">Tableau de bord et analyses détaillées - {{ \Carbon\Carbon::now()->format('l d F Y') }}</p>
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('private.contacts.index') }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-slate-600 to-gray-600 text-white text-sm font-medium rounded-xl hover:from-slate-700 hover:to-gray-700 transition-all duration-200 shadow-md hover:shadow-lg">
                    <i class="fas fa-list mr-2"></i> Retour à la liste
                </a>
                <a href="{{ route('private.contacts.export') }}?format=csv" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-green-600 to-emerald-600 text-white text-sm font-medium rounded-xl hover:from-green-700 hover:to-emerald-700 transition-all duration-200 shadow-md hover:shadow-lg">
                    <i class="fas fa-download mr-2"></i> Exporter
                </a>
                <button onclick="refreshStats()" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-600 to-purple-600 text-white text-sm font-medium rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow-md hover:shadow-lg">
                    <i class="fas fa-sync-alt mr-2"></i> Actualiser
                </button>
            </div>
        </div>
    </div>

    <!-- Statistiques générales -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-cyan-500 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-church text-white text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-2xl font-bold text-slate-800">{{ number_format($general['total_contacts']) }}</p>
                    <p class="text-sm text-slate-500">Total des contacts</p>
                </div>
            </div>
            <div class="mt-4 flex items-center text-sm">
                <span class="text-green-600 font-medium">+{{ $general['total_contacts'] > 0 ? number_format(($general['contacts_verifies'] / $general['total_contacts']) * 100, 1) : 0 }}%</span>
                <span class="text-slate-500 ml-1">vérifiés</span>
            </div>
        </div>

        <div class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-gradient-to-r from-green-500 to-emerald-500 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-check-circle text-white text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-2xl font-bold text-slate-800">{{ number_format($general['contacts_verifies']) }}</p>
                    <p class="text-sm text-slate-500">Contacts vérifiés</p>
                </div>
            </div>
            <div class="mt-4 flex items-center text-sm">
                <span class="text-blue-600 font-medium">{{ $general['total_contacts'] > 0 ? number_format(($general['contacts_verifies'] / $general['total_contacts']) * 100, 1) : 0 }}%</span>
                <span class="text-slate-500 ml-1">du total</span>
            </div>
        </div>

        <div class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-gradient-to-r from-purple-500 to-pink-500 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-eye text-white text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-2xl font-bold text-slate-800">{{ number_format($general['contacts_publics']) }}</p>
                    <p class="text-sm text-slate-500">Contacts publics</p>
                </div>
            </div>
            <div class="mt-4 flex items-center text-sm">
                <span class="text-purple-600 font-medium">{{ $general['total_contacts'] > 0 ? number_format(($general['contacts_publics'] / $general['total_contacts']) * 100, 1) : 0 }}%</span>
                <span class="text-slate-500 ml-1">visibles</span>
            </div>
        </div>

        <div class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-gradient-to-r from-amber-500 to-orange-500 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-map-marker-alt text-white text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-2xl font-bold text-slate-800">{{ number_format($general['contacts_avec_geo']) }}</p>
                    <p class="text-sm text-slate-500">Géolocalisés</p>
                </div>
            </div>
            <div class="mt-4 flex items-center text-sm">
                <span class="text-amber-600 font-medium">{{ $general['total_contacts'] > 0 ? number_format(($general['contacts_avec_geo'] / $general['total_contacts']) * 100, 1) : 0 }}%</span>
                <span class="text-slate-500 ml-1">avec coordonnées</span>
            </div>
        </div>
    </div>

    <!-- Statistiques secondaires -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
        <div class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-600">Avec site web</p>
                    <p class="text-2xl font-bold text-slate-900">{{ number_format($general['contacts_avec_site']) }}</p>
                </div>
                <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-globe text-blue-600"></i>
                </div>
            </div>
        </div>

        <div class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-600">Avec réseaux sociaux</p>
                    <p class="text-2xl font-bold text-slate-900">{{ number_format($general['contacts_avec_reseaux']) }}</p>
                </div>
                <div class="w-8 h-8 bg-pink-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-share-alt text-pink-600"></i>
                </div>
            </div>
        </div>

        <div class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-600">Taux de complétude</p>
                    <p class="text-2xl font-bold text-slate-900">{{ number_format($general['contacts_verifies'] > 0 ? ($general['contacts_verifies'] / $general['total_contacts']) * 100 : 0, 1) }}%</p>
                </div>
                <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-chart-pie text-green-600"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Graphiques et analyses -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Répartition par type -->
        <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
            <div class="p-6 border-b border-slate-200">
                <h3 class="text-xl font-bold text-slate-800 flex items-center">
                    <i class="fas fa-chart-pie text-blue-600 mr-2"></i>
                    Répartition par Type
                </h3>
            </div>
            <div class="p-6">
                @if($by_type->count() > 0)
                    <div class="space-y-4">
                        @foreach($by_type as $type)
                            @php
                                $percentage = $general['total_contacts'] > 0 ? ($type->count / $general['total_contacts']) * 100 : 0;
                                $colorClass = match($type->type_contact) {
                                    'principal' => 'bg-blue-500',
                                    'pastoral' => 'bg-green-500',
                                    'administratif' => 'bg-purple-500',
                                    'urgence' => 'bg-red-500',
                                    'jeunesse' => 'bg-yellow-500',
                                    default => 'bg-gray-500'
                                };
                            @endphp
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <div class="w-4 h-4 rounded-full {{ $colorClass }}"></div>
                                    <span class="text-sm font-medium text-slate-700">{{ ucfirst($type->type_contact) }}</span>
                                </div>
                                <div class="flex items-center space-x-3">
                                    <div class="w-32 bg-gray-200 rounded-full h-2">
                                        <div class="{{ $colorClass }} h-2 rounded-full" style="width: {{ $percentage }}%"></div>
                                    </div>
                                    <span class="text-sm font-bold text-slate-900 w-12 text-right">{{ $type->count }}</span>
                                    <span class="text-xs text-slate-500 w-12 text-right">{{ number_format($percentage, 1) }}%</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-slate-500 text-center py-8">Aucune donnée disponible</p>
                @endif
            </div>
        </div>

        <!-- Répartition par ville -->
        <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
            <div class="p-6 border-b border-slate-200">
                <h3 class="text-xl font-bold text-slate-800 flex items-center">
                    <i class="fas fa-map-marked-alt text-green-600 mr-2"></i>
                    Top 10 des Villes
                </h3>
            </div>
            <div class="p-6">
                @if($by_city->count() > 0)
                    <div class="space-y-4">
                        @foreach($by_city as $index => $city)
                            @php
                                $percentage = $general['total_contacts'] > 0 ? ($city->count / $general['total_contacts']) * 100 : 0;
                                $colorClass = match($index) {
                                    0 => 'bg-yellow-500',
                                    1 => 'bg-gray-400',
                                    2 => 'bg-amber-600',
                                    default => 'bg-blue-500'
                                };
                            @endphp
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <div class="w-6 h-6 rounded-lg {{ $colorClass }} flex items-center justify-center">
                                        <span class="text-white text-xs font-bold">{{ $index + 1 }}</span>
                                    </div>
                                    <span class="text-sm font-medium text-slate-700">{{ $city->ville }}</span>
                                </div>
                                <div class="flex items-center space-x-3">
                                    <div class="w-24 bg-gray-200 rounded-full h-2">
                                        <div class="{{ $colorClass }} h-2 rounded-full" style="width: {{ $percentage }}%"></div>
                                    </div>
                                    <span class="text-sm font-bold text-slate-900 w-8 text-right">{{ $city->count }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-slate-500 text-center py-8">Aucune ville renseignée</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Répartition par dénomination -->
    <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
        <div class="p-6 border-b border-slate-200">
            <h3 class="text-xl font-bold text-slate-800 flex items-center">
                <i class="fas fa-cross text-purple-600 mr-2"></i>
                Répartition par Dénomination (Top 10)
            </h3>
        </div>
        <div class="p-6">
            @if($by_denomination->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @foreach($by_denomination as $index => $denomination)
                        @php
                            $percentage = $general['total_contacts'] > 0 ? ($denomination->count / $general['total_contacts']) * 100 : 0;
                            $colors = ['bg-purple-500', 'bg-blue-500', 'bg-green-500', 'bg-yellow-500', 'bg-red-500', 'bg-indigo-500', 'bg-pink-500', 'bg-cyan-500', 'bg-orange-500', 'bg-teal-500'];
                            $colorClass = $colors[$index % count($colors)];
                        @endphp
                        <div class="p-4 border border-slate-200 rounded-xl hover:bg-slate-50 transition-colors">
                            <div class="flex items-center justify-between mb-2">
                                <h4 class="text-sm font-semibold text-slate-800">{{ $denomination->denomination }}</h4>
                                <span class="text-lg font-bold text-slate-900">{{ $denomination->count }}</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <div class="flex-1 bg-gray-200 rounded-full h-2">
                                    <div class="{{ $colorClass }} h-2 rounded-full" style="width: {{ $percentage }}%"></div>
                                </div>
                                <span class="text-xs text-slate-500">{{ number_format($percentage, 1) }}%</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-slate-500 text-center py-8">Aucune dénomination renseignée</p>
            @endif
        </div>
    </div>

    <!-- Contacts récents et nécessitant une vérification -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Contacts récents -->
        <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
            <div class="p-6 border-b border-slate-200">
                <h3 class="text-xl font-bold text-slate-800 flex items-center">
                    <i class="fas fa-clock text-blue-600 mr-2"></i>
                    Contacts Récents
                </h3>
            </div>
            <div class="p-6">
                @if($recent->count() > 0)
                    <div class="space-y-4">
                        @foreach($recent as $contact)
                            <div class="flex items-center space-x-4 p-3 border border-slate-200 rounded-lg hover:bg-slate-50 transition-colors">
                                <div class="flex-shrink-0">
                                    <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-purple-500 rounded-full flex items-center justify-center">
                                        <span class="text-white font-semibold text-sm">{{ substr($contact->nom_eglise, 0, 2) }}</span>
                                    </div>
                                </div>
                                <div class="flex-1">
                                    <h4 class="text-sm font-semibold text-slate-800">{{ $contact->nom_eglise }}</h4>
                                    <div class="flex items-center space-x-2 mt-1">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                            {{ ucfirst($contact->type_contact) }}
                                        </span>
                                        @if($contact->ville)
                                            <span class="text-xs text-slate-500">{{ $contact->ville }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="text-xs text-slate-500">
                                    {{ $contact->created_at->diffForHumans() }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="mt-4 text-center">
                        <a href="{{ route('private.contacts.index', ['sort' => 'created_at', 'direction' => 'desc']) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                            Voir tous les contacts récents
                        </a>
                    </div>
                @else
                    <p class="text-slate-500 text-center py-8">Aucun contact récent</p>
                @endif
            </div>
        </div>

        <!-- Contacts nécessitant une vérification -->
        <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
            <div class="p-6 border-b border-slate-200">
                <h3 class="text-xl font-bold text-slate-800 flex items-center">
                    <i class="fas fa-exclamation-triangle text-amber-600 mr-2"></i>
                    À Vérifier
                    @if($need_verification->count() > 0)
                        <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800">
                            {{ $need_verification->count() }}
                        </span>
                    @endif
                </h3>
            </div>
            <div class="p-6">
                @if($need_verification->count() > 0)
                    <div class="space-y-4">
                        @foreach($need_verification as $contact)
                            <div class="flex items-center space-x-4 p-3 border border-amber-200 rounded-lg hover:bg-amber-50 transition-colors">
                                <div class="flex-shrink-0">
                                    <div class="w-10 h-10 bg-gradient-to-r from-amber-500 to-orange-500 rounded-full flex items-center justify-center">
                                        <span class="text-white font-semibold text-sm">{{ substr($contact->nom_eglise, 0, 2) }}</span>
                                    </div>
                                </div>
                                <div class="flex-1">
                                    <h4 class="text-sm font-semibold text-slate-800">{{ $contact->nom_eglise }}</h4>
                                    <div class="flex items-center space-x-2 mt-1">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-amber-100 text-amber-800">
                                            {{ ucfirst($contact->type_contact) }}
                                        </span>
                                        @if($contact->ville)
                                            <span class="text-xs text-slate-500">{{ $contact->ville }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <span class="text-xs text-slate-500">{{ $contact->created_at->diffForHumans() }}</span>
                                    @can('contacts.update')
                                        <button onclick="verifyContact({{ $contact->id }})" class="inline-flex items-center px-2 py-1 bg-green-600 text-white text-xs font-medium rounded hover:bg-green-700 transition-colors">
                                            <i class="fas fa-check mr-1"></i> Vérifier
                                        </button>
                                    @endcan
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="mt-4 text-center">
                        <a href="{{ route('private.contacts.index', ['verifie' => 'false']) }}" class="inline-flex items-center px-4 py-2 bg-amber-600 text-white text-sm font-medium rounded-lg hover:bg-amber-700 transition-colors">
                            Voir tous les contacts à vérifier
                        </a>
                    </div>
                @else
                    <div class="text-center py-8">
                        <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-3">
                            <i class="fas fa-check-circle text-green-600 text-2xl"></i>
                        </div>
                        <p class="text-green-600 font-medium">Tous les contacts sont vérifiés !</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Actions rapides -->
    <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
        <div class="p-6 border-b border-slate-200">
            <h3 class="text-xl font-bold text-slate-800 flex items-center">
                <i class="fas fa-bolt text-yellow-600 mr-2"></i>
                Actions Rapides
            </h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                @can('contacts.create')
                <a href="{{ route('private.contacts.create') }}" class="flex items-center justify-center p-4 border-2 border-dashed border-blue-300 rounded-xl hover:border-blue-400 hover:bg-blue-50 transition-colors">
                    <div class="text-center">
                        <i class="fas fa-plus text-blue-600 text-2xl mb-2"></i>
                        <p class="text-sm font-medium text-blue-600">Nouveau Contact</p>
                    </div>
                </a>
                @endcan

                <a href="{{ route('private.contacts.map') }}" class="flex items-center justify-center p-4 border-2 border-dashed border-green-300 rounded-xl hover:border-green-400 hover:bg-green-50 transition-colors">
                    <div class="text-center">
                        <i class="fas fa-map text-green-600 text-2xl mb-2"></i>
                        <p class="text-sm font-medium text-green-600">Carte Interactive</p>
                    </div>
                </a>

                @can('contacts.export')
                <a href="{{ route('private.contacts.export') }}" class="flex items-center justify-center p-4 border-2 border-dashed border-purple-300 rounded-xl hover:border-purple-400 hover:bg-purple-50 transition-colors">
                    <div class="text-center">
                        <i class="fas fa-download text-purple-600 text-2xl mb-2"></i>
                        <p class="text-sm font-medium text-purple-600">Exporter Données</p>
                    </div>
                </a>
                @endcan

                @can('contacts.export')
                <button onclick="generateReport()" class="flex items-center justify-center p-4 border-2 border-dashed border-amber-300 rounded-xl hover:border-amber-400 hover:bg-amber-50 transition-colors">
                    <div class="text-center">
                        <i class="fas fa-file-alt text-amber-600 text-2xl mb-2"></i>
                        <p class="text-sm font-medium text-amber-600">Rapport Détaillé</p>
                    </div>
                </button>
                @endcan
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Actualiser les statistiques
function refreshStats() {
    location.reload();
}

// Vérifier un contact
function verifyContact(contactId) {
    if (confirm('Voulez-vous marquer ce contact comme vérifié ?')) {
        fetch(`{{route('private.contacts.verify', ':contactid')}}`.replace(':contactid', contactId), {
            method: 'PATCH',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
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

// Générer un rapport détaillé
function generateReport() {
    const format = prompt('Format du rapport:\n1. PDF\n2. Excel\n3. CSV\n\nEntrez le numéro (1, 2 ou 3):');

    let exportFormat = 'csv';
    switch(format) {
        case '1':
            exportFormat = 'pdf';
            break;
        case '2':
            exportFormat = 'excel';
            break;
        case '3':
            exportFormat = 'csv';
            break;
        default:
            alert('Format non valide');
            return;
    }

    window.open(`{{ route('private.contacts.export') }}?format=${exportFormat}&type=statistics`, '_blank');
}

// Animation des compteurs au chargement
document.addEventListener('DOMContentLoaded', function() {
    const counters = document.querySelectorAll('.text-2xl.font-bold');

    counters.forEach(counter => {
        const target = parseInt(counter.textContent.replace(/,/g, ''));
        if (isNaN(target)) return;

        const duration = 2000; // 2 secondes
        const increment = target / (duration / 16); // 60 FPS
        let current = 0;

        const updateCounter = () => {
            current += increment;
            if (current >= target) {
                counter.textContent = new Intl.NumberFormat().format(target);
            } else {
                counter.textContent = new Intl.NumberFormat().format(Math.floor(current));
                requestAnimationFrame(updateCounter);
            }
        };

        updateCounter();
    });
});

// Animation des barres de progression
const progressBars = document.querySelectorAll('[style*="width:"]');
progressBars.forEach(bar => {
    const width = bar.style.width;
    bar.style.width = '0%';
    setTimeout(() => {
        bar.style.transition = 'width 1s ease-in-out';
        bar.style.width = width;
    }, 500);
});
</script>
@endpush

@endsection
