
@props([
    'position' => 'top-right', // top-right, top-left, bottom-right, bottom-left
    'autoHide' => true,
    'hideDelay' => 8000
])

@php
    // Récupérer les annonces urgentes actives
    $annoncesUrgentes = \App\Models\Annonce::actives()
        ->urgentes()
        ->with(['auteur'])
        ->orderBy('publie_le', 'desc')
        ->limit(5)
        ->get();

    // Récupérer les annonces expirant dans les 24h
    $annoncesExpirantBientot = \App\Models\Annonce::actives()
        ->whereNotNull('expire_le')
        ->where('expire_le', '<=', now()->addDay())
        ->where('expire_le', '>', now())
        ->with(['auteur'])
        ->orderBy('expire_le', 'asc')
        ->limit(3)
        ->get();

    $positionClasses = [
        'top-right' => 'top-4 right-4',
        'top-left' => 'top-4 left-4',
        'bottom-right' => 'bottom-4 right-4',
        'bottom-left' => 'bottom-4 left-4',
    ];
@endphp

<!-- Container des notifications -->
<div class="fixed {{ $positionClasses[$position] ?? $positionClasses['top-right'] }} z-50 w-80 max-w-sm space-y-3" id="notifications-container">

    <!-- Notifications pour annonces urgentes -->
    @foreach($annoncesUrgentes as $annonce)
        <div class="notification-item bg-red-500 text-white rounded-xl shadow-lg border border-red-600 overflow-hidden transform transition-all duration-300 opacity-0 translate-y-2"
             data-notification-id="urgent-{{ $annonce->id }}"
             data-auto-hide="{{ $autoHide ? 'true' : 'false' }}"
             data-hide-delay="{{ $hideDelay }}">

            <!-- Header avec icône -->
            <div class="flex items-start p-4">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center">
                        <i class="fas fa-exclamation-triangle text-white text-lg animate-pulse"></i>
                    </div>
                </div>

                <div class="ml-3 flex-1 min-w-0">
                    <div class="flex items-center justify-between">
                        <h4 class="text-sm font-semibold text-white truncate">
                            🚨 Annonce Urgente
                        </h4>
                        <button onclick="dismissNotification('urgent-{{ $annonce->id }}')"
                                class="text-white/80 hover:text-white transition-colors">
                            <i class="fas fa-times text-sm"></i>
                        </button>
                    </div>

                    <p class="text-sm text-white/90 mt-1 line-clamp-2">
                        {{ $annonce->titre }}
                    </p>

                    <div class="flex items-center justify-between mt-3">
                        <div class="text-xs text-white/80">
                            @if($annonce->auteur)
                                Par {{ $annonce->auteur->prenom }} {{ $annonce->auteur->nom }}
                            @endif
                            • {{ $annonce->publie_le->diffForHumans() }}
                        </div>

                        @can('annonces.read')
                        <div class="flex items-center space-x-2">
                            <a href="{{ route('private.annonces.show', $annonce) }}"
                               class="inline-flex items-center px-2 py-1 bg-white/20 text-white text-xs font-medium rounded-md hover:bg-white/30 transition-colors">
                                <i class="fas fa-eye mr-1"></i>
                                Voir
                            </a>
                        </div>
                        @endcan
                    </div>
                </div>
            </div>

            <!-- Barre de progression pour auto-hide -->
            @if($autoHide)
                <div class="h-1 bg-white/20">
                    <div class="h-full bg-white/60 progress-bar" style="animation: progress {{ $hideDelay }}ms linear"></div>
                </div>
            @endif
        </div>
    @endforeach

    <!-- Notifications pour annonces expirant bientôt -->
    @foreach($annoncesExpirantBientot as $annonce)
        <div class="notification-item bg-orange-500 text-white rounded-xl shadow-lg border border-orange-600 overflow-hidden transform transition-all duration-300 opacity-0 translate-y-2"
             data-notification-id="expiring-{{ $annonce->id }}"
             data-auto-hide="{{ $autoHide ? 'true' : 'false' }}"
             data-hide-delay="{{ $hideDelay + 2000 }}">

            <div class="flex items-start p-4">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center">
                        <i class="fas fa-clock text-white text-lg"></i>
                    </div>
                </div>

                <div class="ml-3 flex-1 min-w-0">
                    <div class="flex items-center justify-between">
                        <h4 class="text-sm font-semibold text-white truncate">
                            ⏰ Expire Bientôt
                        </h4>
                        <button onclick="dismissNotification('expiring-{{ $annonce->id }}')"
                                class="text-white/80 hover:text-white transition-colors">
                            <i class="fas fa-times text-sm"></i>
                        </button>
                    </div>

                    <p class="text-sm text-white/90 mt-1 line-clamp-2">
                        {{ $annonce->titre }}
                    </p>

                    <div class="flex items-center justify-between mt-3">
                        <div class="text-xs text-white/80">
                            @if($annonce->jours_restants > 0)
                                Dans {{ $annonce->jours_restants }} jour{{ $annonce->jours_restants > 1 ? 's' : '' }}
                            @else
                                Expire aujourd'hui
                            @endif
                        </div>

                        @can('annonces.read')
                        <div class="flex items-center space-x-2">
                            <a href="{{ route('private.annonces.edit', $annonce) }}"
                               class="inline-flex items-center px-2 py-1 bg-white/20 text-white text-xs font-medium rounded-md hover:bg-white/30 transition-colors">
                                <i class="fas fa-edit mr-1"></i>
                                Modifier
                            </a>
                        </div>
                        @endcan
                    </div>
                </div>
            </div>

            @if($autoHide)
                <div class="h-1 bg-white/20">
                    <div class="h-full bg-white/60 progress-bar" style="animation: progress {{ $hideDelay + 2000 }}ms linear"></div>
                </div>
            @endif
        </div>
    @endforeach

    <!-- Notification de succès (masquée par défaut, affichée via JS) -->
    <div class="notification-item bg-green-500 text-white rounded-xl shadow-lg border border-green-600 overflow-hidden transform transition-all duration-300 opacity-0 translate-y-2 hidden"
         id="success-notification">
        <div class="flex items-start p-4">
            <div class="flex-shrink-0">
                <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center">
                    <i class="fas fa-check text-white text-lg"></i>
                </div>
            </div>
            <div class="ml-3 flex-1">
                <h4 class="text-sm font-semibold text-white">Succès</h4>
                <p class="text-sm text-white/90 mt-1" id="success-message"></p>
            </div>
            <button onclick="dismissNotification('success-notification')"
                    class="text-white/80 hover:text-white transition-colors">
                <i class="fas fa-times text-sm"></i>
            </button>
        </div>
    </div>

    <!-- Notification d'erreur (masquée par défaut, affichée via JS) -->
    <div class="notification-item bg-red-600 text-white rounded-xl shadow-lg border border-red-700 overflow-hidden transform transition-all duration-300 opacity-0 translate-y-2 hidden"
         id="error-notification">
        <div class="flex items-start p-4">
            <div class="flex-shrink-0">
                <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center">
                    <i class="fas fa-times text-white text-lg"></i>
                </div>
            </div>
            <div class="ml-3 flex-1">
                <h4 class="text-sm font-semibold text-white">Erreur</h4>
                <p class="text-sm text-white/90 mt-1" id="error-message"></p>
            </div>
            <button onclick="dismissNotification('error-notification')"
                    class="text-white/80 hover:text-white transition-colors">
                <i class="fas fa-times text-sm"></i>
            </button>
        </div>
    </div>
</div>

<!-- Contrôles de notification (bouton pour ouvrir/fermer) -->
<div class="fixed {{ str_replace(['top-', 'bottom-'], ['top-20 ', 'bottom-20 '], $positionClasses[$position] ?? $positionClasses['top-right']) }} z-40">
    <button onclick="toggleNotifications()"
            id="notification-toggle"
            class="bg-blue-600 hover:bg-blue-700 text-white rounded-full p-3 shadow-lg transition-all duration-300">
        <i class="fas fa-bell text-lg"></i>
        @if($annoncesUrgentes->count() + $annoncesExpirantBientot->count() > 0)
            <span class="absolute -top-2 -right-2 bg-red-500 text-white text-xs font-bold rounded-full w-6 h-6 flex items-center justify-center animate-pulse">
                {{ $annoncesUrgentes->count() + $annoncesExpirantBientot->count() }}
            </span>
        @endif
    </button>
</div>

@push('styles')
<style>
@keyframes progress {
    from { width: 100%; }
    to { width: 0%; }
}

.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.notification-item.show {
    opacity: 1;
    transform: translateY(0);
}

.notification-item.hide {
    opacity: 0;
    transform: translateX(100%);
}

/* Animation pour les nouvelles notifications */
@keyframes slideInRight {
    from {
        opacity: 0;
        transform: translateX(100%);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

.notification-item.new {
    animation: slideInRight 0.3s ease-out forwards;
}

/* Effet de secousse pour attirer l'attention */
@keyframes shake {
    0%, 100% { transform: translateX(0); }
    25% { transform: translateX(-5px); }
    75% { transform: translateX(5px); }
}

.notification-item.urgent {
    animation: shake 0.5s ease-in-out 3;
}

/* Styles pour mobile */
@media (max-width: 640px) {
    #notifications-container {
        width: calc(100vw - 2rem);
        max-width: none;
        left: 1rem !important;
        right: 1rem !important;
    }
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    initializeNotifications();
});

function initializeNotifications() {
    const notifications = document.querySelectorAll('.notification-item:not(.hidden)');
    let delay = 0;

    notifications.forEach((notification, index) => {
        setTimeout(() => {
            notification.classList.add('show');

            // Auto-hide si configuré
            if (notification.dataset.autoHide === 'true') {
                const hideDelay = parseInt(notification.dataset.hideDelay) || 8000;
                setTimeout(() => {
                    dismissNotification(notification.dataset.notificationId);
                }, hideDelay);
            }
        }, delay);

        delay += 200; // Décalage entre les notifications
    });

    // Marquer les notifications comme vues
    markNotificationsAsSeen();
}

function dismissNotification(notificationId) {
    const notification = document.querySelector(`[data-notification-id="${notificationId}"]`) ||
                        document.getElementById(notificationId);

    if (notification) {
        notification.classList.add('hide');
        setTimeout(() => {
            notification.remove();
            updateNotificationBadge();
        }, 300);
    }
}

function showSuccessNotification(message) {
    const notification = document.getElementById('success-notification');
    const messageElement = document.getElementById('success-message');

    messageElement.textContent = message;
    notification.classList.remove('hidden');

    setTimeout(() => {
        notification.classList.add('show');
    }, 100);

    // Auto-hide après 5 secondes
    setTimeout(() => {
        dismissNotification('success-notification');
    }, 5000);
}

function showErrorNotification(message) {
    const notification = document.getElementById('error-notification');
    const messageElement = document.getElementById('error-message');

    messageElement.textContent = message;
    notification.classList.remove('hidden');

    setTimeout(() => {
        notification.classList.add('show');
    }, 100);

    // Auto-hide après 8 secondes
    setTimeout(() => {
        dismissNotification('error-notification');
    }, 8000);
}

function toggleNotifications() {
    const container = document.getElementById('notifications-container');
    const toggle = document.getElementById('notification-toggle');

    if (container.style.display === 'none') {
        container.style.display = 'block';
        toggle.querySelector('i').className = 'fas fa-bell-slash text-lg';
    } else {
        container.style.display = 'none';
        toggle.querySelector('i').className = 'fas fa-bell text-lg';
    }
}

function updateNotificationBadge() {
    const visibleNotifications = document.querySelectorAll('#notifications-container .notification-item:not(.hidden)').length;
    const badge = document.querySelector('#notification-toggle .absolute');

    if (visibleNotifications > 0) {
        if (badge) {
            badge.textContent = visibleNotifications;
        }
    } else {
        if (badge) {
            badge.remove();
        }
    }
}

function markNotificationsAsSeen() {
    // Marquer les notifications comme vues dans le localStorage
    const seenNotifications = JSON.parse(localStorage.getItem('seen_notifications') || '[]');
    const currentNotifications = document.querySelectorAll('[data-notification-id]');

    currentNotifications.forEach(notification => {
        const id = notification.dataset.notificationId;
        if (!seenNotifications.includes(id)) {
            seenNotifications.push(id);
        }
    });

    localStorage.setItem('seen_notifications', JSON.stringify(seenNotifications));
}

// Fonction utilitaire pour ajouter dynamiquement une nouvelle notification
function addNotification(type, title, message, actions = []) {
    const container = document.getElementById('notifications-container');
    const colors = {
        'success': 'bg-green-500 border-green-600',
        'error': 'bg-red-500 border-red-600',
        'warning': 'bg-orange-500 border-orange-600',
        'info': 'bg-blue-500 border-blue-600'
    };

    const icons = {
        'success': 'fas fa-check',
        'error': 'fas fa-times',
        'warning': 'fas fa-exclamation-triangle',
        'info': 'fas fa-info-circle'
    };

    const notificationId = 'dynamic-' + Date.now();

    const notificationHtml = `
        <div class="notification-item ${colors[type] || colors.info} text-white rounded-xl shadow-lg overflow-hidden transform transition-all duration-300 opacity-0 translate-y-2 new"
             data-notification-id="${notificationId}">
            <div class="flex items-start p-4">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center">
                        <i class="${icons[type] || icons.info} text-white text-lg"></i>
                    </div>
                </div>
                <div class="ml-3 flex-1">
                    <div class="flex items-center justify-between">
                        <h4 class="text-sm font-semibold text-white">${title}</h4>
                        <button onclick="dismissNotification('${notificationId}')"
                                class="text-white/80 hover:text-white transition-colors">
                            <i class="fas fa-times text-sm"></i>
                        </button>
                    </div>
                    <p class="text-sm text-white/90 mt-1">${message}</p>
                    ${actions.length > 0 ? `
                        <div class="flex items-center space-x-2 mt-3">
                            ${actions.map(action => `
                                <button onclick="${action.onclick}"
                                        class="inline-flex items-center px-2 py-1 bg-white/20 text-white text-xs font-medium rounded-md hover:bg-white/30 transition-colors">
                                    <i class="${action.icon} mr-1"></i>
                                    ${action.text}
                                </button>
                            `).join('')}
                        </div>
                    ` : ''}
                </div>
            </div>
        </div>
    `;

    container.insertAdjacentHTML('afterbegin', notificationHtml);

    // Afficher la notification avec animation
    setTimeout(() => {
        const newNotification = document.querySelector(`[data-notification-id="${notificationId}"]`);
        newNotification.classList.add('show');
        newNotification.classList.remove('new');
    }, 100);

    // Auto-hide après 8 secondes
    setTimeout(() => {
        dismissNotification(notificationId);
    }, 8000);

    updateNotificationBadge();
}

// Écouter les événements personnalisés pour les notifications
document.addEventListener('notification', function(e) {
    const { type, title, message, actions } = e.detail;
    addNotification(type, title, message, actions);
});

// Intégration avec les réponses AJAX pour les annonces
document.addEventListener('annonce-published', function(e) {
    showSuccessNotification(`Annonce "${e.detail.titre}" publiée avec succès`);
});

document.addEventListener('annonce-archived', function(e) {
    showSuccessNotification(`Annonce "${e.detail.titre}" archivée`);
});

document.addEventListener('annonce-error', function(e) {
    showErrorNotification(e.detail.message || 'Une erreur est survenue');
});
</script>
@endpush
