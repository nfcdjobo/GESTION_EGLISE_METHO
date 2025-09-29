@extends('layouts.private.main')
@section('title', 'Paramètres Publics')

@section('content')
<div class="space-y-8">
    <!-- Page Title -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent">Paramètres Publics</h1>
        <p class="text-slate-500 mt-1">Paramètres de donation disponibles au public - {{ \Carbon\Carbon::now()->format('l d F Y') }}</p>
    </div>

    <!-- Statistiques -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-gradient-to-r from-green-500 to-emerald-500 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-globe text-white text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-2xl font-bold text-slate-800">{{ $parametres->count() }}</p>
                    <p class="text-sm text-slate-500">Paramètres publics</p>
                </div>
            </div>
        </div>

        <div class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-cyan-500 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-university text-white text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-2xl font-bold text-slate-800">{{ $parametres->where('type', 'virement_bancaire')->count() }}</p>
                    <p class="text-sm text-slate-500">Virements bancaires</p>
                </div>
            </div>
        </div>

        <div class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-gradient-to-r from-orange-500 to-red-500 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-mobile-alt text-white text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-2xl font-bold text-slate-800">{{ $parametres->where('type', 'mobile_money')->count() }}</p>
                    <p class="text-sm text-slate-500">Mobile Money</p>
                </div>
            </div>
        </div>

        <div class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-gradient-to-r from-purple-500 to-pink-500 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-credit-card text-white text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-2xl font-bold text-slate-800">{{ $parametres->where('type', 'carte_bancaire')->count() }}</p>
                    <p class="text-sm text-slate-500">Cartes bancaires</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Actions -->
    <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
        <div class="p-6">
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('private.parametresdons.index') }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-600 to-purple-600 text-white text-sm font-medium rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow-md hover:shadow-lg">
                    <i class="fas fa-arrow-left mr-2"></i> Retour à la liste
                </a>
                <button type="button" onclick="copyPublicUrl()" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-green-600 to-emerald-600 text-white text-sm font-medium rounded-xl hover:from-green-700 hover:to-emerald-700 transition-all duration-200 shadow-md hover:shadow-lg">
                    <i class="fas fa-copy mr-2"></i> Copier l'URL publique
                </button>
                <a href="{{ route('private.parametresdons.publics') }}?format=json" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-amber-600 to-orange-600 text-white text-sm font-medium rounded-xl hover:from-amber-700 hover:to-orange-700 transition-all duration-200 shadow-md hover:shadow-lg">
                    <i class="fas fa-code mr-2"></i> API JSON
                </a>
            </div>
        </div>
    </div>

    <!-- Liste des paramètres publics -->
    <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
        <div class="p-6 border-b border-slate-200">
            <h2 class="text-xl font-bold text-slate-800 flex items-center">
                <i class="fas fa-list text-purple-600 mr-2"></i>
                Paramètres Disponibles ({{ $parametres->count() }})
            </h2>
        </div>
        <div class="p-6">
            @if($parametres->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($parametres as $parametre)
                        <div class="bg-gradient-to-br from-slate-50 to-white rounded-2xl p-6 border border-slate-200 hover:shadow-lg transition-all duration-300 hover:-translate-y-1">
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex items-center space-x-3">
                                    <div class="w-12 h-12 bg-gradient-to-r
                                        @switch($parametre->type)
                                            @case('virement_bancaire') from-blue-500 to-cyan-500 @break
                                            @case('carte_bancaire') from-green-500 to-emerald-500 @break
                                            @case('mobile_money') from-orange-500 to-red-500 @break
                                            @default from-gray-500 to-slate-500 @break
                                        @endswitch
                                        rounded-xl flex items-center justify-center shadow-lg">
                                        @switch($parametre->type)
                                            @case('virement_bancaire')
                                                <i class="fas fa-university text-white text-lg"></i>
                                                @break
                                            @case('carte_bancaire')
                                                <i class="fas fa-credit-card text-white text-lg"></i>
                                                @break
                                            @case('mobile_money')
                                                <i class="fas fa-mobile-alt text-white text-lg"></i>
                                                @break
                                            @default
                                                <i class="fas fa-dollar-sign text-white text-lg"></i>
                                        @endswitch
                                    </div>
                                    <div>
                                        <h3 class="font-bold text-slate-900">{{ $parametre->operateur }}</h3>
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium
                                            @switch($parametre->type)
                                                @case('virement_bancaire') bg-blue-100 text-blue-800 @break
                                                @case('carte_bancaire') bg-green-100 text-green-800 @break
                                                @case('mobile_money') bg-orange-100 text-orange-800 @break
                                                @default bg-gray-100 text-gray-800 @break
                                            @endswitch">
                                            {{ $parametre->type_libelle }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="space-y-3">
                                <div>
                                    <label class="block text-xs font-medium text-slate-500 uppercase tracking-wider mb-1">Numéro de compte</label>
                                    <div class="flex items-center space-x-2">
                                        <code class="flex-1 px-2 py-1 text-sm bg-slate-100 text-slate-800 rounded">{{ $parametre->numero_compte }}</code>
                                        <button type="button" onclick="copyToClipboard('{{ $parametre->numero_compte }}')" class="text-blue-600 hover:text-blue-800" title="Copier">
                                            <i class="fas fa-copy text-sm"></i>
                                        </button>
                                    </div>
                                </div>

                                @if($parametre->qrcode)
                                    <div>
                                        <label class="block text-xs font-medium text-slate-500 uppercase tracking-wider mb-1">QR Code</label>
                                        <div class="flex items-center space-x-2">
                                            <span class="flex-1 text-sm text-emerald-600">Disponible</span>
                                            <button type="button" onclick="showQrCode('{{ $parametre->id }}')" class="text-emerald-600 hover:text-emerald-800" title="Voir QR Code">
                                                <i class="fas fa-qrcode text-sm"></i>
                                            </button>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <div class="flex items-center justify-between mt-4 pt-4 border-t border-slate-200">
                                <div class="flex items-center space-x-2">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <i class="fas fa-check mr-1"></i> Actif
                                    </span>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        <i class="fas fa-globe mr-1"></i> Public
                                    </span>
                                </div>
                                @can('parametresdons.read')
                                    <a href="{{ route('private.parametresdons.show', $parametre) }}" class="text-slate-400 hover:text-slate-600" title="Voir détails">
                                        <i class="fas fa-external-link-alt text-sm"></i>
                                    </a>
                                @endcan
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <div class="w-24 h-24 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-globe text-3xl text-slate-400"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-slate-900 mb-2">Aucun paramètre public</h3>
                    <p class="text-slate-500 mb-6">Aucun paramètre n'est actuellement publié pour les dons publics.</p>
                    @can('parametresdons.create')
                    <a href="{{ route('private.parametresdons.index') }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-medium rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-cog mr-2"></i> Gérer les paramètres
                    </a>
                    @endcan
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal QR Code -->
<div id="qrModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl max-w-md w-full">
        <div class="p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-slate-900">Code QR</h3>
                <button type="button" onclick="closeQrModal()" class="text-slate-400 hover:text-slate-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <div id="qrContent" class="text-center">
                <!-- Le contenu QR sera inséré ici -->
            </div>
        </div>
    </div>
</div>

<script>
// Copier dans le presse-papiers
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        // Animation de succès ou notification
        const notification = document.createElement('div');
        notification.className = 'fixed top-4 right-4 bg-green-500 text-white px-4 py-2 rounded-xl shadow-lg z-50';
        notification.textContent = 'Copié dans le presse-papiers !';
        document.body.appendChild(notification);

        setTimeout(() => {
            notification.remove();
        }, 2000);
    }).catch(function(err) {
        console.error('Erreur lors de la copie:', err);
        alert('Impossible de copier dans le presse-papiers');
    });
}

// Copier l'URL publique
function copyPublicUrl() {
    const url = `${window.location.origin}{{ route('private.parametresdons.publics') }}`;
    copyToClipboard(url);
}

// Afficher le QR Code
function showQrCode(parametreId) {
    const parametre = @json($parametres->keyBy('id'));
    const qrCode = parametre[parametreId].qrcode;

    if (qrCode) {
        document.getElementById('qrContent').innerHTML = `
            <div class="p-4 bg-slate-50 rounded-xl">
                <p class="text-sm text-slate-600 mb-3">Code QR pour ce paramètre:</p>
                <div class="break-all text-sm font-mono text-slate-800 bg-white p-3 rounded border">
                    ${qrCode}
                </div>
                <button type="button" onclick="copyToClipboard('${qrCode}')" class="mt-3 inline-flex items-center px-3 py-1 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700 transition-colors">
                    <i class="fas fa-copy mr-1"></i> Copier
                </button>
            </div>
        `;
    }

    document.getElementById('qrModal').classList.remove('hidden');
}

// Fermer le modal QR
function closeQrModal() {
    document.getElementById('qrModal').classList.add('hidden');
}

// Fermer le modal en cliquant à l'extérieur
document.getElementById('qrModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeQrModal();
    }
});
</script>
@endsection
