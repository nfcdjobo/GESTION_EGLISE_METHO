@extends('layouts.private.main')
@section('title', 'Statistiques des Types de Réunions')

@section('content')
<div class="space-y-8">
    <!-- Page Title & Breadcrumb -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent">Statistiques des Types de Réunions</h1>
                <nav class="flex mt-2" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-1 md:space-x-3">
                        <li class="inline-flex items-center">
                            <a href="{{ route('private.types-reunions.index') }}" class="inline-flex items-center text-sm font-medium text-slate-700 hover:text-blue-600 transition-colors">
                                <i class="fas fa-calendar-alt mr-2"></i>
                                Types de Réunions
                            </a>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <i class="fas fa-chevron-right text-slate-400 mx-2"></i>
                                <span class="text-sm font-medium text-slate-500">Statistiques</span>
                            </div>
                        </li>
                    </ol>
                </nav>
            </div>

            <!-- Actions -->
            <div class="flex items-center space-x-3">
                <button onclick="window.print()" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-slate-600 to-gray-600 text-white text-sm font-medium rounded-xl hover:from-slate-700 hover:to-gray-700 transition-all duration-200 shadow-md hover:shadow-lg">
                    <i class="fas fa-print mr-2"></i> Imprimer
                </button>
                <button onclick="exportToCsv()" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-green-600 to-emerald-600 text-white text-sm font-medium rounded-xl hover:from-green-700 hover:to-emerald-700 transition-all duration-200 shadow-md hover:shadow-lg">
                    <i class="fas fa-download mr-2"></i> Exporter CSV
                </button>
            </div>
        </div>
        <p class="text-slate-500 mt-1">Analyse détaillée de l'utilisation des types de réunions - {{ \Carbon\Carbon::now()->format('l d F Y') }}</p>
    </div>

    <!-- Statistiques globales -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-cyan-500 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-calendar-alt text-white text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-2xl font-bold text-slate-800">{{ $statistiques['total_types'] }}</p>
                    <p class="text-sm text-slate-500">Types totaux</p>
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
                    <p class="text-2xl font-bold text-slate-800">{{ $statistiques['types_actifs'] }}</p>
                    <p class="text-sm text-slate-500">Types actifs</p>
                </div>
            </div>
        </div>

        <div class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-gradient-to-r from-yellow-500 to-orange-500 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-archive text-white text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-2xl font-bold text-slate-800">{{ $statistiques['types_archives'] }}</p>
                    <p class="text-sm text-slate-500">Types archivés</p>
                </div>
            </div>
        </div>

        <div class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-gradient-to-r from-red-500 to-pink-500 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-times-circle text-white text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-2xl font-bold text-slate-800">{{ $statistiques['non_utilises'] }}</p>
                    <p class="text-sm text-slate-500">Jamais utilisés</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Répartition par catégorie -->
    <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
        <div class="p-6 border-b border-slate-200">
            <h2 class="text-xl font-bold text-slate-800 flex items-center">
                <i class="fas fa-chart-pie text-purple-600 mr-2"></i>
                Répartition par Catégorie
            </h2>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Graphique (placeholder pour un vrai graphique) -->
                <div class="relative">
                    <div class="bg-gradient-to-br from-slate-50 to-slate-100 rounded-xl h-64 flex items-center justify-center">
                        <div class="text-center">
                            <i class="fas fa-chart-pie text-4xl text-slate-400 mb-2"></i>
                            <p class="text-slate-600">Graphique circulaire</p>
                            <p class="text-sm text-slate-500">Répartition par catégorie</p>
                        </div>
                    </div>
                </div>

                <!-- Détails par catégorie -->
                <div class="space-y-4">
                    @foreach($statistiques['par_categorie'] as $categorie)
                        @php
                            $colors = [
                                'spirituel' => 'blue',
                                'administratif' => 'gray',
                                'formation' => 'green',
                                'social' => 'yellow',
                                'ministeriel' => 'purple',
                                'jeunesse' => 'pink',
                                'femmes' => 'rose',
                                'hommes' => 'indigo',
                                'enfants' => 'orange',
                                'special' => 'red'
                            ];
                            $color = $colors[$categorie->categorie] ?? 'slate';
                            $percentage = $statistiques['total_types'] > 0 ? round(($categorie->nombre_types / $statistiques['total_types']) * 100, 1) : 0;
                        @endphp

                        <div class="flex items-center justify-between p-4 bg-{{ $color }}-50 rounded-xl border border-{{ $color }}-200">
                            <div class="flex items-center space-x-3">
                                <div class="w-3 h-3 bg-{{ $color }}-500 rounded-full"></div>
                                <div>
                                    <h4 class="font-medium text-slate-800">{{ ucfirst($categorie->categorie) }}</h4>
                                    <p class="text-sm text-slate-600">{{ $categorie->nombre_types }} type(s) - {{ $percentage }}%</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="text-lg font-bold text-{{ $color }}-600">{{ number_format($categorie->utilisations_totales) }}</div>
                                <div class="text-sm text-slate-500">utilisations</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Types les plus populaires -->
        <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
            <div class="p-6 border-b border-slate-200">
                <h2 class="text-xl font-bold text-slate-800 flex items-center">
                    <i class="fas fa-trophy text-amber-600 mr-2"></i>
                    Types les Plus Populaires
                </h2>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    @foreach($statistiques['plus_populaires']->take(5) as $index => $type)
                        <div class="flex items-center space-x-4 p-4 bg-gradient-to-r from-slate-50 to-slate-100 rounded-xl">
                            <div class="flex items-center justify-center w-8 h-8 bg-gradient-to-r from-amber-500 to-orange-500 text-white font-bold rounded-full text-sm">
                                {{ $index + 1 }}
                            </div>
                            <div class="flex-1">
                                <h4 class="font-medium text-slate-800">{{ $type->nom }}</h4>
                                <p class="text-sm text-slate-600">{{ $type->nombre_utilisations }} utilisation(s)</p>
                                @if($type->derniere_utilisation)
                                    <p class="text-xs text-slate-500">Dernière: {{ $type->derniere_utilisation->format('d/m/Y') }}</p>
                                @endif
                            </div>
                            <div class="text-right">
                                <div class="w-16 h-2 bg-slate-200 rounded-full overflow-hidden">
                                    @php
                                        $maxUtilisations = $statistiques['plus_populaires']->first()->nombre_utilisations;
                                        $percentage = $maxUtilisations > 0 ? ($type->nombre_utilisations / $maxUtilisations) * 100 : 0;
                                    @endphp
                                    <div class="h-full bg-gradient-to-r from-amber-400 to-orange-400 transition-all duration-300" style="width: {{ $percentage }}%"></div>
                                </div>
                            </div>
                        </div>
                    @endforeach

                    @if($statistiques['plus_populaires']->count() == 0)
                        <div class="text-center py-8">
                            <i class="fas fa-chart-line text-3xl text-slate-400 mb-2"></i>
                            <p class="text-slate-600">Aucune donnée d'utilisation disponible</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Analyse des tendances -->
        <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
            <div class="p-6 border-b border-slate-200">
                <h2 class="text-xl font-bold text-slate-800 flex items-center">
                    <i class="fas fa-chart-line text-green-600 mr-2"></i>
                    Analyse des Tendances
                </h2>
            </div>
            <div class="p-6">
                <!-- Graphique de tendance (placeholder) -->
                <div class="bg-gradient-to-br from-green-50 to-emerald-50 rounded-xl h-48 flex items-center justify-center mb-6">
                    <div class="text-center">
                        <i class="fas fa-chart-line text-4xl text-green-400 mb-2"></i>
                        <p class="text-green-700">Graphique de tendance</p>
                        <p class="text-sm text-green-600">Évolution des utilisations</p>
                    </div>
                </div>

                <!-- Métriques clés -->
                <div class="grid grid-cols-2 gap-4">
                    <div class="text-center p-4 bg-blue-50 rounded-lg">
                        <div class="text-2xl font-bold text-blue-600">{{ number_format($statistiques['par_categorie']->avg('moyenne_utilisation'), 1) }}</div>
                        <div class="text-sm text-blue-700">Moyenne d'utilisation</div>
                    </div>
                    <div class="text-center p-4 bg-purple-50 rounded-lg">
                        <div class="text-2xl font-bold text-purple-600">{{ $statistiques['par_categorie']->count() }}</div>
                        <div class="text-sm text-purple-700">Catégories actives</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Détails par type -->
    <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
        <div class="p-6 border-b border-slate-200">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <h2 class="text-xl font-bold text-slate-800 flex items-center">
                    <i class="fas fa-table text-blue-600 mr-2"></i>
                    Détails par Type de Réunion
                </h2>
                <div class="flex items-center space-x-2">
                    <select id="filterCategory" class="px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors text-sm">
                        <option value="">Toutes les catégories</option>
                        @foreach($statistiques['par_categorie'] as $categorie)
                            <option value="{{ $categorie->categorie }}">{{ ucfirst($categorie->categorie) }}</option>
                        @endforeach
                    </select>
                    <select id="sortBy" class="px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors text-sm">
                        <option value="utilisations">Trier par utilisations</option>
                        <option value="nom">Trier par nom</option>
                        <option value="categorie">Trier par catégorie</option>
                        <option value="date">Trier par date</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="p-6">
            <div class="overflow-x-auto">
                <table class="w-full text-sm" id="typesTable">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200">
                            <th class="text-left py-3 px-4 font-medium text-slate-600">Type</th>
                            <th class="text-left py-3 px-4 font-medium text-slate-600">Catégorie</th>
                            <th class="text-left py-3 px-4 font-medium text-slate-600">Utilisations</th>
                            <th class="text-left py-3 px-4 font-medium text-slate-600">Dernière utilisation</th>
                            <th class="text-left py-3 px-4 font-medium text-slate-600">Statut</th>
                            <th class="text-left py-3 px-4 font-medium text-slate-600">Performance</th>
                        </tr>
                    </thead>
                    <tbody id="typesTableBody">
                        @foreach($statistiques['plus_populaires'] as $type)
                            <tr class="border-b border-slate-100 hover:bg-slate-50/50 transition-colors" data-categorie="{{ $type->categorie ?? '' }}">
                                <td class="py-4 px-4">
                                    <div class="font-medium text-slate-900">{{ $type->nom }}</div>
                                    <div class="text-xs text-slate-500">{{ $type->code ?? 'N/A' }}</div>
                                </td>
                                <td class="py-4 px-4">
                                    @if(isset($type->categorie))
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            {{ ucfirst($type->categorie) }}
                                        </span>
                                    @else
                                        <span class="text-slate-400">-</span>
                                    @endif
                                </td>
                                <td class="py-4 px-4">
                                    <div class="font-bold text-slate-900">{{ $type->nombre_utilisations }}</div>
                                    <div class="w-20 h-2 bg-slate-200 rounded-full overflow-hidden mt-1">
                                        @php
                                            $maxUtilisations = $statistiques['plus_populaires']->first()->nombre_utilisations;
                                            $percentage = $maxUtilisations > 0 ? ($type->nombre_utilisations / $maxUtilisations) * 100 : 0;
                                        @endphp
                                        <div class="h-full bg-gradient-to-r from-blue-400 to-purple-400 transition-all duration-300" style="width: {{ $percentage }}%"></div>
                                    </div>
                                </td>
                                <td class="py-4 px-4">
                                    @if($type->derniere_utilisation)
                                        <div class="text-slate-900">{{ $type->derniere_utilisation->format('d/m/Y') }}</div>
                                        <div class="text-xs text-slate-500">{{ $type->derniere_utilisation->diffForHumans() }}</div>
                                    @else
                                        <span class="text-slate-400">Jamais utilisé</span>
                                    @endif
                                </td>
                                <td class="py-4 px-4">
                                    @php
                                        $daysSinceLastUse = $type->derniere_utilisation ? now()->diffInDays($type->derniere_utilisation) : null;
                                        if (!$type->derniere_utilisation) {
                                            $status = ['class' => 'red', 'text' => 'Inutilisé'];
                                        } elseif ($daysSinceLastUse <= 30) {
                                            $status = ['class' => 'green', 'text' => 'Actif'];
                                        } elseif ($daysSinceLastUse <= 90) {
                                            $status = ['class' => 'yellow', 'text' => 'Modéré'];
                                        } else {
                                            $status = ['class' => 'orange', 'text' => 'Inactif'];
                                        }
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-{{ $status['class'] }}-100 text-{{ $status['class'] }}-800">
                                        {{ $status['text'] }}
                                    </span>
                                </td>
                                <td class="py-4 px-4">
                                    @php
                                        if ($type->nombre_utilisations === 0) {
                                            $performance = ['icon' => 'minus', 'color' => 'slate', 'text' => 'Aucune'];
                                        } elseif ($type->nombre_utilisations >= 10) {
                                            $performance = ['icon' => 'arrow-up', 'color' => 'green', 'text' => 'Excellente'];
                                        } elseif ($type->nombre_utilisations >= 5) {
                                            $performance = ['icon' => 'arrow-right', 'color' => 'blue', 'text' => 'Bonne'];
                                        } else {
                                            $performance = ['icon' => 'arrow-down', 'color' => 'yellow', 'text' => 'Faible'];
                                        }
                                    @endphp
                                    <div class="flex items-center space-x-2">
                                        <i class="fas fa-{{ $performance['icon'] }} text-{{ $performance['color'] }}-600"></i>
                                        <span class="text-{{ $performance['color'] }}-600 font-medium">{{ $performance['text'] }}</span>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                @if($statistiques['plus_populaires']->count() == 0)
                    <div class="text-center py-12">
                        <div class="w-24 h-24 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-chart-bar text-3xl text-slate-400"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-slate-900 mb-2">Aucune donnée disponible</h3>
                        <p class="text-slate-500">Les statistiques d'utilisation apparaîtront ici une fois que les types de réunions auront été utilisés.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Recommandations -->
    <div class="bg-gradient-to-br from-blue-50 to-cyan-50 rounded-2xl shadow-lg border border-blue-200 p-6">
        <div class="flex items-start">
            <div class="flex-shrink-0">
                <i class="fas fa-lightbulb text-blue-600 text-2xl"></i>
            </div>
            <div class="ml-4">
                <h3 class="text-lg font-semibold text-blue-900 mb-3">Recommandations</h3>
                <div class="space-y-2 text-sm text-blue-800">
                    @if($statistiques['non_utilises'] > 0)
                        <div class="flex items-start">
                            <i class="fas fa-exclamation-triangle text-amber-500 mr-2 mt-0.5 flex-shrink-0"></i>
                            <span><strong>{{ $statistiques['non_utilises'] }} type(s)</strong> n'ont jamais été utilisés. Considérez leur archivage ou leur promotion.</span>
                        </div>
                    @endif

                    @if($statistiques['par_categorie']->where('moyenne_utilisation', '<', 2)->count() > 0)
                        <div class="flex items-start">
                            <i class="fas fa-chart-line text-blue-500 mr-2 mt-0.5 flex-shrink-0"></i>
                            <span>Certaines catégories ont une faible utilisation moyenne. Analysez leur pertinence.</span>
                        </div>
                    @endif

                    @if($statistiques['plus_populaires']->count() > 0)
                        <div class="flex items-start">
                            <i class="fas fa-star text-yellow-500 mr-2 mt-0.5 flex-shrink-0"></i>
                            <span>Les types les plus populaires peuvent servir de modèles pour créer de nouveaux types similaires.</span>
                        </div>
                    @endif

                    <div class="flex items-start">
                        <i class="fas fa-sync-alt text-green-500 mr-2 mt-0.5 flex-shrink-0"></i>
                        <span>Révisez régulièrement ces statistiques pour optimiser votre catalogue de types de réunions.</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Filtrage et tri du tableau
document.addEventListener('DOMContentLoaded', function() {
    const filterCategory = document.getElementById('filterCategory');
    const sortBy = document.getElementById('sortBy');
    const tableBody = document.getElementById('typesTableBody');
    const rows = Array.from(tableBody.querySelectorAll('tr'));

    function filterAndSort() {
        const categoryFilter = filterCategory.value;
        const sortCriteria = sortBy.value;

        // Filtrage
        let filteredRows = rows.filter(row => {
            if (!categoryFilter) return true;
            return row.dataset.categorie === categoryFilter;
        });

        // Tri
        filteredRows.sort((a, b) => {
            switch(sortCriteria) {
                case 'nom':
                    const nameA = a.cells[0].textContent.trim();
                    const nameB = b.cells[0].textContent.trim();
                    return nameA.localeCompare(nameB);

                case 'categorie':
                    const catA = a.cells[1].textContent.trim();
                    const catB = b.cells[1].textContent.trim();
                    return catA.localeCompare(catB);

                case 'utilisations':
                    const usageA = parseInt(a.cells[2].textContent) || 0;
                    const usageB = parseInt(b.cells[2].textContent) || 0;
                    return usageB - usageA; // Ordre décroissant

                case 'date':
                    // Tri par date de dernière utilisation
                    const dateA = a.cells[3].textContent.includes('Jamais') ? new Date(0) : new Date(a.cells[3].textContent);
                    const dateB = b.cells[3].textContent.includes('Jamais') ? new Date(0) : new Date(b.cells[3].textContent);
                    return dateB - dateA; // Plus récent en premier

                default:
                    return 0;
            }
        });

        // Mise à jour du tableau
        tableBody.innerHTML = '';
        filteredRows.forEach(row => {
            tableBody.appendChild(row);
        });

        // Animation d'apparition
        filteredRows.forEach((row, index) => {
            row.style.opacity = '0';
            row.style.transform = 'translateY(10px)';
            setTimeout(() => {
                row.style.transition = 'all 0.3s ease';
                row.style.opacity = '1';
                row.style.transform = 'translateY(0)';
            }, index * 50);
        });
    }

    filterCategory.addEventListener('change', filterAndSort);
    sortBy.addEventListener('change', filterAndSort);
});

// Export CSV
function exportToCsv() {
    const table = document.getElementById('typesTable');
    const rows = Array.from(table.querySelectorAll('tr'));

    const csvContent = rows.map(row => {
        const cells = Array.from(row.querySelectorAll('th, td'));
        return cells.map(cell => {
            // Nettoyage du contenu de la cellule
            const text = cell.textContent.trim().replace(/\s+/g, ' ');
            // Échapper les guillemets et encapsuler si nécessaire
            return text.includes(',') || text.includes('"') || text.includes('\n')
                ? `"${text.replace(/"/g, '""')}"`
                : text;
        }).join(',');
    }).join('\n');

    // Créer et télécharger le fichier
    const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
    const link = document.createElement('a');

    if (link.download !== undefined) {
        const url = URL.createObjectURL(blob);
        link.setAttribute('href', url);
        link.setAttribute('download', `statistiques-types-reunions-${new Date().toISOString().split('T')[0]}.csv`);
        link.style.visibility = 'hidden';
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    } else {
        alert('Votre navigateur ne supporte pas le téléchargement de fichiers.');
    }
}

// Animation des barres de progression au chargement
document.addEventListener('DOMContentLoaded', function() {
    const progressBars = document.querySelectorAll('[style*="width"]');

    progressBars.forEach(bar => {
        const originalWidth = bar.style.width;
        bar.style.width = '0%';
        bar.style.transition = 'width 1s ease-in-out';

        setTimeout(() => {
            bar.style.width = originalWidth;
        }, 100);
    });
});

// Effet de survol sur les cartes de statistiques
document.addEventListener('DOMContentLoaded', function() {
    const statCards = document.querySelectorAll('.hover\\:-translate-y-1');

    statCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-4px) scale(1.02)';
        });

        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0) scale(1)';
        });
    });
});
</script>

<style>
@media print {
    .no-print {
        display: none;
    }

    .bg-white\/80 {
        background-color: white !important;
    }

    .shadow-lg,
    .shadow-md {
        box-shadow: none !important;
    }

    .border {
        border: 1px solid #e2e8f0 !important;
    }
}
</style>
@endpush
@endsection
