<div class="bg-white rounded-lg shadow-lg border border-gray-200 max-w-md mx-auto">
    <div class="p-6">
        <!-- En-tête d'erreur -->
        <div class="flex items-center mb-4">
            <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center mr-4">
                <i class="fas {{ $icon ?? 'fa-exclamation-triangle' }} text-red-600 text-xl"></i>
            </div>
            <div>
                <h3 class="text-lg font-semibold text-gray-900">{{ $title ?? 'Erreur' }}</h3>
                <p class="text-sm text-gray-500">Code: {{ $statusCode }}</p>
            </div>
        </div>

        <!-- Message d'erreur -->
        <div class="mb-6">
            <p class="text-gray-700 leading-relaxed">
                {{ $message ?? 'Une erreur s\'est produite' }}
            </p>
        </div>

        <!-- Actions -->
        <div class="flex flex-col sm:flex-row gap-3">
            <button onclick="window.location.reload()"
                    class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                <i class="fas fa-sync-alt mr-2"></i>
                Actualiser
            </button>
            <button onclick="history.back()"
                    class="flex-1 px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>
                Retour
            </button>
        </div>

        <!-- Lien de fermeture pour modal -->
        <div class="mt-4 text-center">
            <button onclick="this.closest('.modal, .overlay, [data-modal]')?.remove()"
                    class="text-sm text-gray-500 hover:text-gray-700 transition-colors">
                Fermer
            </button>
        </div>
    </div>
</div>
