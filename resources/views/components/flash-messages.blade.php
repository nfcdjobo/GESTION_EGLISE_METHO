{{-- Messages Flash --}}
@if (session('success') || session('error') || session('warning') || session('info') || $errors->any())
<div class="fixed top-4 right-4 z-50 space-y-2" id="flash-messages">
    {{-- Message de succès --}}
    @if (session('success'))
    <div class="alert alert-success shadow-lg max-w-md animate-in slide-in-from-right duration-300" role="alert" x-data="{ show: true }" x-show="show" x-transition>
        <div class="flex items-start">
            <div class="flex-shrink-0">
                <i class="fas fa-check-circle text-green-600 text-lg"></i>
            </div>
            <div class="ml-3 flex-1">
                <h3 class="text-sm font-medium text-green-800">Succès</h3>
                <p class="text-sm text-green-700 mt-1">{{ session('success') }}</p>
            </div>
            <div class="ml-auto pl-3">
                <button @click="show = false" class="inline-flex rounded-md text-green-400 hover:text-green-500 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                    <i class="fas fa-times text-sm"></i>
                </button>
            </div>
        </div>
    </div>
    @endif

    {{-- Message d'erreur --}}
    @if (session('error'))
    <div class="alert alert-error shadow-lg max-w-md animate-in slide-in-from-right duration-300" role="alert" x-data="{ show: true }" x-show="show" x-transition>
        <div class="flex items-start">
            <div class="flex-shrink-0">
                <i class="fas fa-exclamation-circle text-red-600 text-lg"></i>
            </div>
            <div class="ml-3 flex-1">
                <h3 class="text-sm font-medium text-red-800">Erreur</h3>
                <p class="text-sm text-red-700 mt-1">{{ session('error') }}</p>
            </div>
            <div class="ml-auto pl-3">
                <button @click="show = false" class="inline-flex rounded-md text-red-400 hover:text-red-500 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                    <i class="fas fa-times text-sm"></i>
                </button>
            </div>
        </div>
    </div>
    @endif

    {{-- Message d'avertissement --}}
    @if (session('warning'))
    <div class="alert alert-warning shadow-lg max-w-md animate-in slide-in-from-right duration-300" role="alert" x-data="{ show: true }" x-show="show" x-transition>
        <div class="flex items-start">
            <div class="flex-shrink-0">
                <i class="fas fa-exclamation-triangle text-yellow-600 text-lg"></i>
            </div>
            <div class="ml-3 flex-1">
                <h3 class="text-sm font-medium text-yellow-800">Attention</h3>
                <p class="text-sm text-yellow-700 mt-1">{{ session('warning') }}</p>
            </div>
            <div class="ml-auto pl-3">
                <button @click="show = false" class="inline-flex rounded-md text-yellow-400 hover:text-yellow-500 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2">
                    <i class="fas fa-times text-sm"></i>
                </button>
            </div>
        </div>
    </div>
    @endif

    {{-- Message d'information --}}
    @if (session('info'))
    <div class="alert alert-info shadow-lg max-w-md animate-in slide-in-from-right duration-300" role="alert" x-data="{ show: true }" x-show="show" x-transition>
        <div class="flex items-start">
            <div class="flex-shrink-0">
                <i class="fas fa-info-circle text-blue-600 text-lg"></i>
            </div>
            <div class="ml-3 flex-1">
                <h3 class="text-sm font-medium text-blue-800">Information</h3>
                <p class="text-sm text-blue-700 mt-1">{{ session('info') }}</p>
            </div>
            <div class="ml-auto pl-3">
                <button @click="show = false" class="inline-flex rounded-md text-blue-400 hover:text-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                    <i class="fas fa-times text-sm"></i>
                </button>
            </div>
        </div>
    </div>
    @endif

    {{-- Erreurs de validation --}}
    @if ($errors->any())
    <div class="alert alert-error shadow-lg max-w-md animate-in slide-in-from-right duration-300" role="alert" x-data="{ show: true }" x-show="show" x-transition>
        <div class="flex items-start">
            <div class="flex-shrink-0">
                <i class="fas fa-exclamation-circle text-red-600 text-lg"></i>
            </div>
            <div class="ml-3 flex-1">
                <h3 class="text-sm font-medium text-red-800">Erreurs de validation</h3>
                <div class="text-sm text-red-700 mt-1">
                    @if ($errors->count() === 1)
                        <p>{{ $errors->first() }}</p>
                    @else
                        <p class="mb-2">{{ $errors->count() }} erreurs ont été trouvées :</p>
                        <ul class="list-disc list-inside space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
            <div class="ml-auto pl-3">
                <button @click="show = false" class="inline-flex rounded-md text-red-400 hover:text-red-500 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                    <i class="fas fa-times text-sm"></i>
                </button>
            </div>
        </div>
    </div>
    @endif
</div>

{{-- Auto-masquer les messages après 5 secondes --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-masquer les messages de succès après 5 secondes
    const successMessages = document.querySelectorAll('.alert-success[x-data]');
    successMessages.forEach(function(message) {
        setTimeout(function() {
            // Trigger Alpine.js close
            message.__x.$data.show = false;
        }, 5000);
    });

    // Auto-masquer les messages d'info après 7 secondes
    const infoMessages = document.querySelectorAll('.alert-info[x-data]');
    infoMessages.forEach(function(message) {
        setTimeout(function() {
            message.__x.$data.show = false;
        }, 7000);
    });

    // Garder les messages d'erreur et d'avertissement jusqu'à fermeture manuelle
});
</script>

{{-- Version alternative sans Alpine.js --}}
{{--
@if (session('success') || session('error') || session('warning') || session('info') || $errors->any())
<div class="fixed top-4 right-4 z-50 space-y-2" id="flash-messages">
    @if (session('success'))
    <div class="alert alert-success shadow-lg max-w-md" role="alert" id="success-alert">
        <div class="flex items-start">
            <div class="flex-shrink-0">
                <i class="fas fa-check-circle text-green-600 text-lg"></i>
            </div>
            <div class="ml-3 flex-1">
                <h3 class="text-sm font-medium text-green-800">Succès</h3>
                <p class="text-sm text-green-700 mt-1">{{ session('success') }}</p>
            </div>
            <div class="ml-auto pl-3">
                <button onclick="closeAlert('success-alert')" class="inline-flex rounded-md text-green-400 hover:text-green-500 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                    <i class="fas fa-times text-sm"></i>
                </button>
            </div>
        </div>
    </div>
    @endif

    @if (session('error'))
    <div class="alert alert-error shadow-lg max-w-md" role="alert" id="error-alert">
        <div class="flex items-start">
            <div class="flex-shrink-0">
                <i class="fas fa-exclamation-circle text-red-600 text-lg"></i>
            </div>
            <div class="ml-3 flex-1">
                <h3 class="text-sm font-medium text-red-800">Erreur</h3>
                <p class="text-sm text-red-700 mt-1">{{ session('error') }}</p>
            </div>
            <div class="ml-auto pl-3">
                <button onclick="closeAlert('error-alert')" class="inline-flex rounded-md text-red-400 hover:text-red-500 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                    <i class="fas fa-times text-sm"></i>
                </button>
            </div>
        </div>
    </div>
    @endif

    @if (session('warning'))
    <div class="alert alert-warning shadow-lg max-w-md" role="alert" id="warning-alert">
        <div class="flex items-start">
            <div class="flex-shrink-0">
                <i class="fas fa-exclamation-triangle text-yellow-600 text-lg"></i>
            </div>
            <div class="ml-3 flex-1">
                <h3 class="text-sm font-medium text-yellow-800">Attention</h3>
                <p class="text-sm text-yellow-700 mt-1">{{ session('warning') }}</p>
            </div>
            <div class="ml-auto pl-3">
                <button onclick="closeAlert('warning-alert')" class="inline-flex rounded-md text-yellow-400 hover:text-yellow-500 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2">
                    <i class="fas fa-times text-sm"></i>
                </button>
            </div>
        </div>
    </div>
    @endif

    @if (session('info'))
    <div class="alert alert-info shadow-lg max-w-md" role="alert" id="info-alert">
        <div class="flex items-start">
            <div class="flex-shrink-0">
                <i class="fas fa-info-circle text-blue-600 text-lg"></i>
            </div>
            <div class="ml-3 flex-1">
                <h3 class="text-sm font-medium text-blue-800">Information</h3>
                <p class="text-sm text-blue-700 mt-1">{{ session('info') }}</p>
            </div>
            <div class="ml-auto pl-3">
                <button onclick="closeAlert('info-alert')" class="inline-flex rounded-md text-blue-400 hover:text-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                    <i class="fas fa-times text-sm"></i>
                </button>
            </div>
        </div>
    </div>
    @endif

    @if ($errors->any())
    <div class="alert alert-error shadow-lg max-w-md" role="alert" id="validation-errors">
        <div class="flex items-start">
            <div class="flex-shrink-0">
                <i class="fas fa-exclamation-circle text-red-600 text-lg"></i>
            </div>
            <div class="ml-3 flex-1">
                <h3 class="text-sm font-medium text-red-800">Erreurs de validation</h3>
                <div class="text-sm text-red-700 mt-1">
                    @if ($errors->count() === 1)
                        <p>{{ $errors->first() }}</p>
                    @else
                        <p class="mb-2">{{ $errors->count() }} erreurs ont été trouvées :</p>
                        <ul class="list-disc list-inside space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
            <div class="ml-auto pl-3">
                <button onclick="closeAlert('validation-errors')" class="inline-flex rounded-md text-red-400 hover:text-red-500 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                    <i class="fas fa-times text-sm"></i>
                </button>
            </div>
        </div>
    </div>
    @endif
</div>

<script>
function closeAlert(alertId) {
    const alert = document.getElementById(alertId);
    if (alert) {
        alert.style.transform = 'translateX(100%)';
        alert.style.opacity = '0';
        setTimeout(() => {
            alert.remove();
        }, 300);
    }
}

// Auto-masquer les messages après un délai
document.addEventListener('DOMContentLoaded', function() {
    // Succès : 5 secondes
    setTimeout(() => closeAlert('success-alert'), 5000);

    // Info : 7 secondes
    setTimeout(() => closeAlert('info-alert'), 7000);

    // Erreurs et avertissements restent jusqu'à fermeture manuelle
});
</script>
--}}
