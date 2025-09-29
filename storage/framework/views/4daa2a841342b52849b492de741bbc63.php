<?php $__env->startSection('title', 'Calendrier des Réunions'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-8">
    <!-- Page Title & Breadcrumb -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent">Calendrier des Réunions</h1>
        <nav class="flex mt-2" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="<?php echo e(route('private.reunions.index')); ?>" class="inline-flex items-center text-sm font-medium text-slate-700 hover:text-blue-600 transition-colors">
                        <i class="fas fa-calendar-check mr-2"></i>
                        Réunions
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <i class="fas fa-chevron-right text-slate-400 mx-2"></i>
                        <span class="text-sm font-medium text-slate-500">Calendrier</span>
                    </div>
                </li>
            </ol>
        </nav>
    </div>

    <!-- Filtres et navigation -->
    <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
        <div class="p-6 border-b border-slate-200">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <h2 class="text-xl font-bold text-slate-800 flex items-center">
                    <i class="fas fa-calendar-alt text-blue-600 mr-2"></i>
                    Navigation du Calendrier
                </h2>
                <div class="flex flex-wrap gap-2">
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('reunions.create')): ?>
                        <a href="<?php echo e(route('private.reunions.create')); ?>" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-600 to-purple-600 text-white text-sm font-medium rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow-md hover:shadow-lg">
                            <i class="fas fa-plus mr-2"></i> Nouvelle Réunion
                        </a>
                    <?php endif; ?>
                    <a href="<?php echo e(route('private.reunions.index')); ?>" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-slate-600 to-gray-600 text-white text-sm font-medium rounded-xl hover:from-slate-700 hover:to-gray-700 transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-list mr-2"></i> Vue Liste
                    </a>
                </div>
            </div>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-6 gap-4">
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-slate-700 mb-2">Navigation rapide</label>
                    <div class="flex items-center gap-2">
                        <button onclick="navigateMonth(-1)" class="inline-flex items-center justify-center w-10 h-10 bg-slate-100 text-slate-600 rounded-xl hover:bg-slate-200 transition-colors">
                            <i class="fas fa-chevron-left"></i>
                        </button>
                        <div class="flex-1 text-center">
                            <h3 id="currentMonthYear" class="text-lg font-semibold text-slate-900"></h3>
                        </div>
                        <button onclick="navigateMonth(1)" class="inline-flex items-center justify-center w-10 h-10 bg-slate-100 text-slate-600 rounded-xl hover:bg-slate-200 transition-colors">
                            <i class="fas fa-chevron-right"></i>
                        </button>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Année</label>
                    <select id="yearSelect" name="annee" onchange="updateCalendar()" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        <?php for($y = now()->year - 2; $y <= now()->year + 2; $y++): ?>
                            <option value="<?php echo e($y); ?>" <?php echo e(request('annee', now()->year) == $y ? 'selected' : ''); ?>><?php echo e($y); ?></option>
                        <?php endfor; ?>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Mois</label>
                    <select id="monthSelect" name="mois" onchange="updateCalendar()" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        <option value="">Toute l'année</option>
                        <?php for($m = 1; $m <= 12; $m++): ?>
                            <option value="<?php echo e($m); ?>" <?php echo e(request('mois') == $m ? 'selected' : ''); ?>>
                                <?php echo e(\Carbon\Carbon::create()->month($m)->translatedFormat('F')); ?>

                            </option>
                        <?php endfor; ?>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Vue</label>
                    <select id="viewSelect" onchange="changeView()" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        <option value="month">Mois</option>
                        <option value="week">Semaine</option>
                        <option value="agenda">Agenda</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Actions</label>
                    <div class="flex gap-2">
                        <button onclick="goToToday()" class="flex-1 px-3 py-2 bg-blue-600 text-white text-sm font-medium rounded-xl hover:bg-blue-700 transition-colors">
                            Aujourd'hui
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Légende -->
    <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20">
        <div class="p-6">
            <div class="flex flex-wrap items-center gap-4">
                <span class="text-sm font-medium text-slate-700">Légende :</span>
                <div class="flex items-center gap-2">
                    <div class="w-4 h-4 bg-blue-500 rounded"></div>
                    <span class="text-xs text-slate-600">Planifiée</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-4 h-4 bg-green-500 rounded"></div>
                    <span class="text-xs text-slate-600">Confirmée</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-4 h-4 bg-orange-500 rounded"></div>
                    <span class="text-xs text-slate-600">En cours</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-4 h-4 bg-emerald-500 rounded"></div>
                    <span class="text-xs text-slate-600">Terminée</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-4 h-4 bg-red-500 rounded"></div>
                    <span class="text-xs text-slate-600">Annulée</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-4 h-4 bg-purple-500 rounded"></div>
                    <span class="text-xs text-slate-600">Reportée</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-4 h-4 bg-cyan-500 rounded border-2 border-cyan-700"></div>
                    <span class="text-xs text-slate-600">Avec diffusion</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-4 h-4 bg-indigo-500 rounded-full"></div>
                    <span class="text-xs text-slate-600">Récurrente</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Calendrier -->
    <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
        <div id="calendar-container">
            <!-- Vue Mois (par défaut) -->
            <div id="monthView" class="calendar-view">
                <div class="p-6">
                    <!-- En-tête des jours -->
                    <div class="grid grid-cols-7 gap-1 mb-4">
                        <div class="p-3 text-center text-sm font-semibold text-slate-600 bg-slate-50 rounded-lg">Lun</div>
                        <div class="p-3 text-center text-sm font-semibold text-slate-600 bg-slate-50 rounded-lg">Mar</div>
                        <div class="p-3 text-center text-sm font-semibold text-slate-600 bg-slate-50 rounded-lg">Mer</div>
                        <div class="p-3 text-center text-sm font-semibold text-slate-600 bg-slate-50 rounded-lg">Jeu</div>
                        <div class="p-3 text-center text-sm font-semibold text-slate-600 bg-slate-50 rounded-lg">Ven</div>
                        <div class="p-3 text-center text-sm font-semibold text-slate-600 bg-slate-50 rounded-lg">Sam</div>
                        <div class="p-3 text-center text-sm font-semibold text-slate-600 bg-slate-50 rounded-lg">Dim</div>
                    </div>
                    <!-- Grille des jours -->
                    <div id="calendar-grid" class="grid grid-cols-7 gap-1">
                        <!-- Généré dynamiquement par JavaScript -->
                    </div>
                </div>
            </div>

            <!-- Vue Semaine -->
            <div id="weekView" class="calendar-view hidden">
                <div class="p-6">
                    <div id="week-container">
                        <!-- Généré dynamiquement par JavaScript -->
                    </div>
                </div>
            </div>

            <!-- Vue Agenda -->
            <div id="agendaView" class="calendar-view hidden">
                <div class="p-6">
                    <div id="agenda-container">
                        <!-- Généré dynamiquement par JavaScript -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistiques rapides du mois -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-cyan-500 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-calendar-check text-white text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p id="total-reunions" class="text-2xl font-bold text-slate-800">0</p>
                    <p class="text-sm text-slate-500">Réunions ce mois</p>
                </div>
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
                    <p id="reunions-confirmees" class="text-2xl font-bold text-slate-800">0</p>
                    <p class="text-sm text-slate-500">Confirmées</p>
                </div>
            </div>
        </div>

        <div class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-gradient-to-r from-purple-500 to-pink-500 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-users text-white text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p id="total-participants" class="text-2xl font-bold text-slate-800">0</p>
                    <p class="text-sm text-slate-500">Participants prévus</p>
                </div>
            </div>
        </div>

        <div class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-gradient-to-r from-yellow-500 to-orange-500 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-clock text-white text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p id="prochaine-reunion" class="text-2xl font-bold text-slate-800">--</p>
                    <p class="text-sm text-slate-500">Prochaine réunion</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal détails réunion -->
<div id="reunionModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <div class="p-6 border-b border-slate-200">
            <div class="flex items-center justify-between">
                <h3 id="modal-titre" class="text-xl font-semibold text-slate-900"></h3>
                <button onclick="closeReunionModal()" class="text-slate-400 hover:text-slate-600 transition-colors">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
        </div>
        <div id="modal-content" class="p-6">
            <!-- Contenu généré dynamiquement -->
        </div>
        <div class="flex items-center justify-end space-x-3 p-6 border-t border-slate-200">
            <button onclick="closeReunionModal()" class="px-4 py-2 text-slate-700 bg-slate-100 rounded-xl hover:bg-slate-200 transition-colors">
                Fermer
            </button>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('reunions.read')): ?>
            <a id="modal-voir-plus" href="#" class="px-4 py-2 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition-colors">
                Voir plus
            </a>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
// Variables globales
let currentDate = new Date();
let currentView = 'month';
let reunions = <?php echo json_encode($reunions ?? [], 15, 512) ?>;

// Couleurs des statuts
const statutColors = {
    'planifiee': 'bg-blue-500',
    'confirmee': 'bg-green-500',
    'planifie': 'bg-yellow-500',
    'en_cours': 'bg-orange-500',
    'terminee': 'bg-emerald-500',
    'annulee': 'bg-red-500',
    'reportee': 'bg-purple-500',
    'suspendue': 'bg-gray-500'
};

// Initialisation
document.addEventListener('DOMContentLoaded', function() {
    updateCurrentMonthYear();
    updateCalendar();
    updateStatistics();
});

// Navigation des mois
function navigateMonth(direction) {
    currentDate.setMonth(currentDate.getMonth() + direction);
    updateCurrentMonthYear();
    updateCalendar();
    updateStatistics();

    // Mettre à jour les selects
    document.getElementById('yearSelect').value = currentDate.getFullYear();
    document.getElementById('monthSelect').value = currentDate.getMonth() + 1;
}

// Aller à aujourd'hui
function goToToday() {
    currentDate = new Date();
    updateCurrentMonthYear();
    updateCalendar();
    updateStatistics();

    document.getElementById('yearSelect').value = currentDate.getFullYear();
    document.getElementById('monthSelect').value = currentDate.getMonth() + 1;
}

// Mettre à jour le titre du mois/année
function updateCurrentMonthYear() {
    const options = { year: 'numeric', month: 'long' };
    document.getElementById('currentMonthYear').textContent = currentDate.toLocaleDateString('fr-FR', options);
}

// Mettre à jour le calendrier
function updateCalendar() {
    const year = document.getElementById('yearSelect').value;
    const month = document.getElementById('monthSelect').value;

    // Mettre à jour currentDate si nécessaire
    if (year) currentDate.setFullYear(parseInt(year));
    if (month) currentDate.setMonth(parseInt(month) - 1);

    updateCurrentMonthYear();

    switch(currentView) {
        case 'month':
            generateMonthView();
            break;
        case 'week':
            generateWeekView();
            break;
        case 'agenda':
            generateAgendaView();
            break;
    }
}

// Changer de vue
function changeView() {
    const newView = document.getElementById('viewSelect').value;

    // Cacher toutes les vues
    document.querySelectorAll('.calendar-view').forEach(view => {
        view.classList.add('hidden');
    });

    // Afficher la vue sélectionnée
    document.getElementById(newView + 'View').classList.remove('hidden');

    currentView = newView;
    updateCalendar();
}

// Générer la vue mensuelle
function generateMonthView() {
    const grid = document.getElementById('calendar-grid');
    grid.innerHTML = '';

    const year = currentDate.getFullYear();
    const month = currentDate.getMonth();

    // Premier jour du mois
    const firstDay = new Date(year, month, 1);
    // Dernier jour du mois
    const lastDay = new Date(year, month + 1, 0);

    // Premier jour de la grille (peut être du mois précédent)
    const startDate = new Date(firstDay);
    startDate.setDate(startDate.getDate() - (firstDay.getDay() === 0 ? 6 : firstDay.getDay() - 1));

    // Générer 42 cases (6 semaines × 7 jours)
    for (let i = 0; i < 42; i++) {
        const cellDate = new Date(startDate);
        cellDate.setDate(startDate.getDate() + i);

        const cell = createCalendarCell(cellDate, month);
        grid.appendChild(cell);
    }
}

// Créer une cellule du calendrier
function createCalendarCell(date, currentMonth) {
    const cell = document.createElement('div');
    const isCurrentMonth = date.getMonth() === currentMonth;
    const isToday = isDateToday(date);

    cell.className = `min-h-[120px] p-2 border border-slate-200 rounded-lg hover:bg-slate-50 transition-colors cursor-pointer ${
        !isCurrentMonth ? 'text-slate-400 bg-slate-50' : 'bg-white'
    } ${isToday ? 'ring-2 ring-blue-500' : ''}`;

    // Numéro du jour
    const dayNumber = document.createElement('div');
    dayNumber.className = `text-sm font-semibold mb-2 ${isToday ? 'text-blue-600' : 'text-slate-900'}`;
    dayNumber.textContent = date.getDate();
    cell.appendChild(dayNumber);

    // Réunions du jour
    const dayReunions = getReunionsForDate(date);
    const container = document.createElement('div');
    container.className = 'space-y-1';

    dayReunions.slice(0, 3).forEach(reunion => {
        const reunionEl = createReunionElement(reunion, 'calendar');
        container.appendChild(reunionEl);
    });

    if (dayReunions.length > 3) {
        const moreEl = document.createElement('div');
        moreEl.className = 'text-xs text-slate-500 font-medium';
        moreEl.textContent = `+${dayReunions.length - 3} autres`;
        container.appendChild(moreEl);
    }

    cell.appendChild(container);

    // Événement click pour ouvrir modal ou créer réunion
    cell.addEventListener('click', () => {
        if (dayReunions.length > 0) {
            if (dayReunions.length === 1) {
                showReunionModal(dayReunions[0]);
            } else {
                showDayReunionsModal(date, dayReunions);
            }
        } else {
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('reunions.create')): ?>
            // Rediriger vers création de réunion avec la date pré-remplie
            window.location.href = `<?php echo e(route('private.reunions.create')); ?>?date=${date.toISOString().split('T')[0]}`;

            <?php endif; ?>
        }
    });

    return cell;
}

// Générer la vue semaine
function generateWeekView() {
    const container = document.getElementById('week-container');
    container.innerHTML = '';

    // Obtenir le début de la semaine (lundi)
    const startOfWeek = new Date(currentDate);
    const day = startOfWeek.getDay();
    const diff = startOfWeek.getDate() - day + (day === 0 ? -6 : 1);
    startOfWeek.setDate(diff);

    const weekEl = document.createElement('div');
    weekEl.className = 'grid grid-cols-8 gap-4';

    // Colonne des heures
    const timeColumn = document.createElement('div');
    timeColumn.className = 'space-y-1';
    timeColumn.innerHTML = '<div class="h-12 text-sm font-medium text-slate-600 flex items-center">Heure</div>';

    for (let hour = 6; hour < 23; hour++) {
        const timeSlot = document.createElement('div');
        timeSlot.className = 'h-12 text-xs text-slate-500 border-t border-slate-200 flex items-center';
        timeSlot.textContent = `${hour.toString().padStart(2, '0')}:00`;
        timeColumn.appendChild(timeSlot);
    }
    weekEl.appendChild(timeColumn);

    // Colonnes des jours
    for (let i = 0; i < 7; i++) {
        const dayDate = new Date(startOfWeek);
        dayDate.setDate(startOfWeek.getDate() + i);

        const dayColumn = createWeekDayColumn(dayDate);
        weekEl.appendChild(dayColumn);
    }

    container.appendChild(weekEl);
}

// Créer une colonne jour pour la vue semaine
function createWeekDayColumn(date) {
    const column = document.createElement('div');
    column.className = 'space-y-1';

    // En-tête du jour
    const header = document.createElement('div');
    header.className = `h-12 text-center text-sm font-medium p-2 rounded-lg ${
        isDateToday(date) ? 'bg-blue-100 text-blue-800' : 'bg-slate-100 text-slate-700'
    }`;
    header.innerHTML = `
        <div>${date.toLocaleDateString('fr-FR', { weekday: 'short' })}</div>
        <div class="font-bold">${date.getDate()}</div>
    `;
    column.appendChild(header);

    // Créneaux horaires
    const dayReunions = getReunionsForDate(date);

    for (let hour = 6; hour < 23; hour++) {
        const timeSlot = document.createElement('div');
        timeSlot.className = 'h-12 border-t border-slate-200 p-1 relative';

        // Chercher les réunions pour cette heure
        const hourReunions = dayReunions.filter(reunion => {
            const startHour = new Date(reunion.heure_debut_prevue).getHours();
            return startHour === hour;
        });

        hourReunions.forEach(reunion => {
            const reunionEl = createReunionElement(reunion, 'week');
            reunionEl.addEventListener('click', () => showReunionModal(reunion));
            timeSlot.appendChild(reunionEl);
        });

        column.appendChild(timeSlot);
    }

    return column;
}

// Générer la vue agenda
function generateAgendaView() {
    const container = document.getElementById('agenda-container');
    container.innerHTML = '';

    const year = currentDate.getFullYear();
    const month = currentDate.getMonth();

    // Filtrer les réunions du mois
    const monthReunions = reunions.filter(reunion => {
        const reunionDate = new Date(reunion.date_reunion);
        return reunionDate.getFullYear() === year && reunionDate.getMonth() === month;
    }).sort((a, b) => new Date(a.date_reunion) - new Date(b.date_reunion));

    if (monthReunions.length === 0) {
        container.innerHTML = `
            <div class="text-center py-12">
                <div class="w-24 h-24 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-calendar-times text-3xl text-slate-400"></i>
                </div>
                <h3 class="text-lg font-semibold text-slate-900 mb-2">Aucune réunion ce mois</h3>
                <p class="text-slate-500 mb-6">Commencez par planifier votre première réunion.</p>
                <a href="<?php echo e(route('private.reunions.create')); ?>" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-medium rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow-md hover:shadow-lg">
                    <i class="fas fa-plus mr-2"></i> Planifier une réunion
                </a>
            </div>
        `;
        return;
    }

    // Grouper par date
    const groupedReunions = {};
    monthReunions.forEach(reunion => {
        const dateKey = reunion.date_reunion;
        if (!groupedReunions[dateKey]) {
            groupedReunions[dateKey] = [];
        }
        groupedReunions[dateKey].push(reunion);
    });

    // Afficher chaque jour
    Object.keys(groupedReunions).sort().forEach(dateKey => {
        const dateReunions = groupedReunions[dateKey];
        const date = new Date(dateKey);

        const daySection = document.createElement('div');
        daySection.className = 'mb-8';

        const dayHeader = document.createElement('div');
        dayHeader.className = `flex items-center mb-4 pb-2 border-b-2 ${
            isDateToday(date) ? 'border-blue-500' : 'border-slate-200'
        }`;
        dayHeader.innerHTML = `
            <div class="text-lg font-bold ${isDateToday(date) ? 'text-blue-600' : 'text-slate-900'}">
                ${date.toLocaleDateString('fr-FR', {
                    weekday: 'long',
                    day: 'numeric',
                    month: 'long',
                    year: 'numeric'
                })}
            </div>
            <span class="ml-2 text-sm text-slate-500">${dateReunions.length} réunion(s)</span>
        `;
        daySection.appendChild(dayHeader);

        const reunionsList = document.createElement('div');
        reunionsList.className = 'space-y-3';

        dateReunions.forEach(reunion => {
            const reunionCard = createAgendaReunionCard(reunion);
            reunionsList.appendChild(reunionCard);
        });

        daySection.appendChild(reunionsList);
        container.appendChild(daySection);
    });
}

// Créer une carte réunion pour l'agenda
function createAgendaReunionCard(reunion) {
    const card = document.createElement('div');
    card.className = 'bg-white border border-slate-200 rounded-xl p-4 hover:shadow-md transition-all duration-200 cursor-pointer';

    const statutColor = statutColors[reunion.statut] || 'bg-gray-500';

    card.innerHTML = `
        <div class="flex items-start space-x-4">
            <div class="flex-shrink-0">
                <div class="w-3 h-3 ${statutColor} rounded-full mt-2"></div>
            </div>
            <div class="flex-1 min-w-0">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <h4 class="text-lg font-semibold text-slate-900 mb-1">${reunion.titre}</h4>
                        <p class="text-sm text-slate-600 mb-2">${reunion.type_reunion?.nom || 'Type non défini'}</p>
                        <div class="flex items-center space-x-4 text-sm text-slate-500">
                            <div class="flex items-center">
                                <i class="fas fa-clock w-4 mr-1"></i>
                                ${new Date(reunion.heure_debut_prevue).toLocaleTimeString('fr-FR', { hour: '2-digit', minute: '2-digit' })}
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-map-marker-alt w-4 mr-1"></i>
                                ${reunion.lieu}
                            </div>
                            ${reunion.nombre_inscrits ? `
                                <div class="flex items-center">
                                    <i class="fas fa-users w-4 mr-1"></i>
                                    ${reunion.nombre_inscrits} inscrit(s)
                                </div>
                            ` : ''}
                        </div>
                    </div>
                    <div class="flex items-center space-x-2">
                        ${reunion.diffusion_en_ligne ? '<i class="fas fa-video text-cyan-600" title="Diffusion en ligne"></i>' : ''}
                        ${reunion.est_recurrente ? '<i class="fas fa-repeat text-indigo-600" title="Récurrente"></i>' : ''}
                        ${reunion.niveau_priorite !== 'normale' ? `<i class="fas fa-exclamation-triangle text-orange-600" title="Priorité ${reunion.niveau_priorite}"></i>` : ''}
                    </div>
                </div>
            </div>
        </div>
    `;

    card.addEventListener('click', () => showReunionModal(reunion));

    return card;
}

// Créer un élément réunion
function createReunionElement(reunion, context = 'calendar') {
    const el = document.createElement('div');
    const statutColor = statutColors[reunion.statut] || 'bg-gray-500';

    if (context === 'calendar') {
        el.className = `p-1 rounded text-xs ${statutColor} text-white truncate cursor-pointer hover:opacity-80 transition-opacity`;

        const borderClass = reunion.diffusion_en_ligne ? 'border-2 border-white' : '';
        const shapeClass = reunion.est_recurrente ? 'rounded-full' : 'rounded';
        el.className += ` ${borderClass} ${shapeClass}`;

        const time = new Date(reunion.heure_debut_prevue).toLocaleTimeString('fr-FR', {
            hour: '2-digit',
            minute: '2-digit'
        });
        el.textContent = `${time} ${reunion.titre}`;
    } else if (context === 'week') {
        el.className = `absolute inset-x-0 top-0 p-1 rounded text-xs ${statutColor} text-white truncate cursor-pointer hover:opacity-80 transition-opacity z-10`;
        el.textContent = reunion.titre;
    }

    return el;
}

// Obtenir les réunions pour une date donnée
function getReunionsForDate(date) {
    const dateStr = date.toISOString().split('T')[0];
    return reunions.filter(reunion => {
        const reunionDateStr = reunion.date_reunion.split('T')[0];
        return reunionDateStr === dateStr;
    });
}

// Vérifier si c'est aujourd'hui
function isDateToday(date) {
    const today = new Date();
    return date.toDateString() === today.toDateString();
}

// Afficher le modal de réunion
function showReunionModal(reunion) {
    const modal = document.getElementById('reunionModal');
    const title = document.getElementById('modal-titre');
    const content = document.getElementById('modal-content');
    const voirPlus = document.getElementById('modal-voir-plus');

    title.textContent = reunion.titre;
    voirPlus.href = `<?php echo e(route('private.reunions.show', ':reunion')); ?>`.replace(':reunion', reunion.id);

    const statutColor = statutColors[reunion.statut] || 'bg-gray-500';
    const statutText = reunion.statut.charAt(0).toUpperCase() + reunion.statut.slice(1);

    content.innerHTML = `
        <div class="space-y-4">
            <div class="flex items-center space-x-4">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium ${statutColor} text-white">
                    ${statutText}
                </span>
                ${reunion.niveau_priorite !== 'normale' ? `
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-orange-100 text-orange-800">
                        Priorité ${reunion.niveau_priorite}
                    </span>
                ` : ''}
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-600">Type</label>
                    <p class="text-slate-900">${reunion.type_reunion?.nom || 'Non défini'}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-600">Date et heure</label>
                    <p class="text-slate-900">
                        ${new Date(reunion.date_reunion).toLocaleDateString('fr-FR')} à
                        ${new Date(reunion.heure_debut_prevue).toLocaleTimeString('fr-FR', { hour: '2-digit', minute: '2-digit' })}
                    </p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-600">Lieu</label>
                    <p class="text-slate-900">${reunion.lieu}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-600">Organisateur</label>
                    <p class="text-slate-900">${reunion.organisateur_principal?.nom || ''} ${reunion.organisateur_principal?.prenom || 'Non défini'}</p>
                </div>
            </div>

            ${reunion.description ? `
                <div>
                    <label class="block text-sm font-medium text-slate-600 mb-2">Description</label>
                    <div class="prose prose-sm text-slate-700 bg-slate-50 p-3 rounded-lg">
                        ${reunion.description}
                    </div>
                </div>
            ` : ''}

            <div class="flex items-center space-x-4 text-sm">
                ${reunion.diffusion_en_ligne ? '<div class="flex items-center text-cyan-600"><i class="fas fa-video mr-2"></i>Diffusion en ligne</div>' : ''}
                ${reunion.est_recurrente ? '<div class="flex items-center text-indigo-600"><i class="fas fa-repeat mr-2"></i>Récurrente</div>' : ''}
                ${reunion.nombre_inscrits ? `<div class="flex items-center text-slate-600"><i class="fas fa-users mr-2"></i>${reunion.nombre_inscrits} inscrit(s)</div>` : ''}
            </div>
        </div>
    `;

    modal.classList.remove('hidden');
}

// Afficher le modal avec plusieurs réunions du jour
function showDayReunionsModal(date, dayReunions) {
    const modal = document.getElementById('reunionModal');
    const title = document.getElementById('modal-titre');
    const content = document.getElementById('modal-content');

    title.textContent = `Réunions du ${date.toLocaleDateString('fr-FR')}`;

    content.innerHTML = `
        <div class="space-y-4">
            ${dayReunions.map(reunion => `
                <div class="border border-slate-200 rounded-lg p-4 hover:bg-slate-50 transition-colors cursor-pointer" onclick="window.location.href='/reunions/${reunion.id}'">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <h4 class="font-semibold text-slate-900">${reunion.titre}</h4>
                            <p class="text-sm text-slate-600">${reunion.type_reunion?.nom || 'Type non défini'}</p>
                            <div class="flex items-center space-x-3 mt-2 text-sm text-slate-500">
                                <span><i class="fas fa-clock mr-1"></i>${new Date(reunion.heure_debut_prevue).toLocaleTimeString('fr-FR', { hour: '2-digit', minute: '2-digit' })}</span>
                                <span><i class="fas fa-map-marker-alt mr-1"></i>${reunion.lieu}</span>
                            </div>
                        </div>
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium ${statutColors[reunion.statut]} text-white">
                            ${reunion.statut}
                        </span>
                    </div>
                </div>
            `).join('')}
        </div>
    `;

    modal.classList.remove('hidden');
}

// Fermer le modal
function closeReunionModal() {
    document.getElementById('reunionModal').classList.add('hidden');
}

// Mettre à jour les statistiques
function updateStatistics() {
    const year = currentDate.getFullYear();
    const month = currentDate.getMonth();

    const monthReunions = reunions.filter(reunion => {
        const reunionDate = new Date(reunion.date_reunion);
        return reunionDate.getFullYear() === year && reunionDate.getMonth() === month;
    });

    const confirmees = monthReunions.filter(r => r.statut === 'confirmee').length;
    const totalParticipants = monthReunions.reduce((sum, r) => sum + (r.nombre_inscrits || 0), 0);

    // Prochaine réunion
    const now = new Date();
    const prochainesReunions = reunions
        .filter(r => new Date(r.date_reunion) >= now && r.statut !== 'annulee')
        .sort((a, b) => new Date(a.date_reunion) - new Date(b.date_reunion));

    const prochaineText = prochainesReunions.length > 0
        ? new Date(prochainesReunions[0].date_reunion).toLocaleDateString('fr-FR', { day: 'numeric', month: 'short' })
        : '--';

    // Mettre à jour l'affichage
    document.getElementById('total-reunions').textContent = monthReunions.length;
    document.getElementById('reunions-confirmees').textContent = confirmees;
    document.getElementById('total-participants').textContent = totalParticipants.toLocaleString();
    document.getElementById('prochaine-reunion').textContent = prochaineText;
}

// Fermer le modal en cliquant à l'extérieur
document.getElementById('reunionModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeReunionModal();
    }
});

// Raccourcis clavier
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeReunionModal();
    }
    if (e.ctrlKey || e.metaKey) {
        switch(e.key) {
            case 'ArrowLeft':
                e.preventDefault();
                navigateMonth(-1);
                break;
            case 'ArrowRight':
                e.preventDefault();
                navigateMonth(1);
                break;
            case 't':
                e.preventDefault();
                goToToday();
                break;
        }
    }
});

// Export du calendrier
function exportCalendar(format) {
    const year = document.getElementById('yearSelect').value;
    const month = document.getElementById('monthSelect').value;

    const params = new URLSearchParams();
    if (year) params.append('annee', year);
    if (month) params.append('mois', month);
    params.append('export', format);

    window.open(`<?php echo e(route('private.reunions.calendrier')); ?>?${params.toString()}`, '_blank');
}

// Imprimer le calendrier
function printCalendar() {
    window.print();
}

// Gestion responsive
function handleResize() {
    const width = window.innerWidth;

    if (width < 768 && currentView === 'week') {
        // Basculer en vue agenda sur mobile
        document.getElementById('viewSelect').value = 'agenda';
        changeView();
    }
}

window.addEventListener('resize', handleResize);

// Synchronisation avec les événements du serveur (optionnel)
function syncWithServer() {
    fetch(`<?php echo e(route('private.reunions.calendrier')); ?>?api=1&annee=${currentDate.getFullYear()}&mois=${currentDate.getMonth() + 1}`)
        .then(response => response.json())
        .then(data => {
            reunions = data.reunions || [];
            updateCalendar();
            updateStatistics();
        })
        .catch(error => {
            console.error('Erreur de synchronisation:', error);
        });
}

// Synchroniser toutes les 5 minutes
setInterval(syncWithServer, 300000);
</script>

<style>
@media print {
    .no-print {
        display: none !important;
    }

    .calendar-view {
        break-inside: avoid;
    }

    .calendar-cell {
        break-inside: avoid;
        page-break-inside: avoid;
    }

    body {
        print-color-adjust: exact;
        -webkit-print-color-adjust: exact;
    }
}

/* Animations */
.calendar-view {
    transition: opacity 0.3s ease-in-out;
}

.calendar-view.hidden {
    opacity: 0;
    pointer-events: none;
}

/* Responsive */
@media (max-width: 768px) {
    #calendar-grid {
        gap: 1px;
    }

    .min-h-[120px] {
        min-height: 80px;
    }

    .grid-cols-7 {
        gap: 1px;
    }
}

@media (max-width: 640px) {
    .min-h-[120px] {
        min-height: 60px;
    }

    .calendar-cell {
        font-size: 0.75rem;
    }
}

/* Amélioration de l'accessibilité */
.calendar-cell:focus {
    outline: 2px solid #3B82F6;
    outline-offset: 2px;
}

.reunion-element:focus {
    outline: 2px solid #FFFFFF;
    outline-offset: 1px;
}

/* Styles pour l'impression */
@page {
    margin: 2cm;
    size: A4 landscape;
}

@media print {
    .bg-gradient-to-r {
        background: #374151 !important;
        -webkit-print-color-adjust: exact;
    }

    .shadow-lg,
    .shadow-xl,
    .hover\:shadow-xl {
        box-shadow: none !important;
    }

    .hover\:-translate-y-1 {
        transform: none !important;
    }
}

/* Animation des transitions */
.transition-all {
    transition-property: all;
    transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
    transition-duration: 300ms;
}

/* Styles pour les éléments interactifs */
.calendar-cell:hover .reunion-element {
    transform: scale(1.05);
    z-index: 10;
}

/* Indicateur de chargement */
.loading {
    position: relative;
}

.loading::after {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 20px;
    height: 20px;
    margin: -10px 0 0 -10px;
    border: 2px solid #E5E7EB;
    border-top: 2px solid #3B82F6;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

/* Styles pour les tooltips */
.tooltip {
    position: relative;
}

.tooltip::before {
    content: attr(data-tooltip);
    position: absolute;
    bottom: 100%;
    left: 50%;
    transform: translateX(-50%);
    background: rgba(0, 0, 0, 0.8);
    color: white;
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 12px;
    white-space: nowrap;
    opacity: 0;
    pointer-events: none;
    transition: opacity 0.3s;
    z-index: 1000;
}

.tooltip:hover::before {
    opacity: 1;
}
</style>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.private.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Dell\Desktop\MICRISERVICES\methodiste_belle_ville\resources\views/components/private/reunions/calendrier.blade.php ENDPATH**/ ?>