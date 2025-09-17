<div class="bg-white rounded-lg shadow-lg border border-gray-200 max-w-md mx-auto">
    <div class="p-6">
        <!-- En-tête 404 -->
        <div class="text-center mb-6">
            <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-search text-purple-600 text-2xl"></i>
            </div>
            <h3 class="text-xl font-semibold text-gray-900 mb-2">Ressource introuvable</h3>
            <p class="text-gray-600">
                {{ $message ?? 'La ressource demandée n\'a pas été trouvée' }}
            </p>
        </div>

        <!-- Suggestions -->
        @if(isset($suggestions) && count($suggestions) > 0)
            <div class="mb-6">
                <h4 class="text-sm font-medium text-gray-700 mb-3">Suggestions :</h4>
                <div class="space-y-2">
                    @foreach(array_slice($suggestions, 0, 3) as $suggestion)
                        <a href="{{ $suggestion['url'] ?? '#' }}"
                           class="flex items-center p-2 text-sm text-blue-600 hover:bg-blue-50 rounded-lg transition-colors">
                            <i class="fas fa-arrow-right mr-2"></i>
                            {{ $suggestion['path'] ?? $suggestion }}
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Actions -->
        <div class="flex flex-col sm:flex-row gap-3">
            <button onclick="history.back()"
                    class="flex-1 px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>
                Retour
            </button>
            <button onclick="window.location.href='/'"
                    class="flex-1 px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
                <i class="fas fa-home mr-2"></i>
                Accueil
            </button>
        </div>
    </div>
</div>
