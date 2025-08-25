@extends('layouts.private.main')
@section('title', 'Rapport de Suivi Pastoral')

@section('content')
<div class="space-y-8">
    <!-- Page Title -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent">Rapport de Suivi Pastoral</h1>
                <p class="text-slate-500 mt-1">Analyse détaillée et synthèse des activités de suivi pastoral</p>
            </div>
            <div class="flex items-center space-x-2">
                <button type="button" onclick="window.print()" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-slate-600 to-slate-700 text-white text-sm font-medium rounded-xl hover:from-slate-700 hover:to-slate-800 transition-all duration-200 shadow-md hover:shadow-lg">
                    <i class="fas fa-print mr-2"></i> Imprimer
                </button>
                <button type="button" onclick="exportPDF()" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-red-600 to-pink-600 text-white text-sm font-medium rounded-xl hover:from-red-700 hover:to-pink-700 transition-all duration-200 shadow-md hover:shadow-lg">
                    <i class="fas fa-file-pdf mr-2"></i> Exporter PDF
                </button>
            </div>
        </div>
    </div>

    <!-- Paramètres du rapport -->
    <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
        <div class="p-6 border-b border-slate-200">
            <h2 class="text-xl font-bold text-slate-800 flex items-center">
                <i class="fas fa-cog text-blue-600 mr-2"></i>
                Paramètres du Rapport
            </h2>
        </div>
        <div class="p-6">
            <form method="GET" action="{{ route('private.participantscultes.rapport-suivi') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Période</label>
                    <select name="periode" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        <option value="30" {{ request('periode') == '30' ? 'selected' : '' }}>30 derniers jours</option>
                        <option value="60" {{ request('periode') == '60' ? 'selected' : '' }}>60 derniers jours</option>
                        <option value="90" {{ request('periode', 90) == '90' ? 'selected' : '' }}>90 derniers jours</option>
                        <option value="180" {{ request('periode') == '180' ? 'selected' : '' }}>6 derniers mois</option>
                        <option value="365" {{ request('periode') == '365' ? 'selected' : '' }}>12 derniers mois</option>
                        <option value="custom" {{ request('periode') == 'custom' ? 'selected' : '' }}>Période personnalisée</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Type de rapport</label>
                    <select name="type_rapport" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        <option value="complet" {{ request('type_rapport', 'complet') == 'complet' ? 'selected' : '' }}>Complet</option>
                        <option value="nouveaux_visiteurs" {{ request('type_rapport') == 'nouveaux_visiteurs' ? 'selected' : '' }}>Nouveaux visiteurs uniquement</option>
                        <option value="suivi_actif" {{ request('type_rapport') == 'suivi_actif' ? 'selected' : '' }}>Suivis actifs</option>
                        <option value="baptemes" {{ request('type_rapport') == 'baptemes' ? 'selected' : '' }}>Candidats au baptême</option>
                        <option value="nouveaux_membres" {{ request('type_rapport') == 'nouveaux_membres' ? 'selected' : '' }}>Futurs nouveaux membres</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Responsable</label>
                    <select name="responsable_id" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        <option value="">Tous les responsables</option>
                        @if(isset($responsables))
                            @foreach($responsables as $responsable)
                                <option value="{{ $responsable->id }}" {{ request('responsable_id') == $responsable->id ? 'selected' : '' }}>
                                    {{ $responsable->nom }} {{ $responsable->prenom }}
                                </option>
                            @endforeach
                        @endif
                    </select>
                </div>
                <div class="flex items-end">
                    <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-xl hover:bg-blue-700 transition-colors">
                        <i class="fas fa-chart-line mr-2"></i> Générer
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Résumé exécutif -->
    <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
        <div class="p-6 border-b border-slate-200">
            <h2 class="text-xl font-bold text-slate-800 flex items-center">
                <i class="fas fa-chart-bar text-green-600 mr-2"></i>
                Résumé Exécutif
            </h2>
            <p class="text-sm text-slate-500 mt-1">
                Période: {{ request('periode', 90) }} derniers jours |
                Généré le {{ now()->format('d/m/Y à H:i') }}
            </p>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="bg-gradient-to-r from-blue-50 to-cyan-50 p-6 rounded-xl border border-blue-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-blue-600 font-medium">Total Visiteurs</p>
                            <p class="text-3xl font-bold text-blue-800">{{ $stats['total_visiteurs'] ?? 0 }}</p>
                            <p class="text-xs text-blue-600 mt-1">
                                @if(isset($stats['evolution_visiteurs']))
                                    {{ $stats['evolution_visiteurs'] > 0 ? '+' : '' }}{{ $stats['evolution_visiteurs'] }}% vs période précédente
                                @else
                                    Aucune donnée comparative
                                @endif
                            </p>
                        </div>
                        <i class="fas fa-users text-blue-500 text-2xl"></i>
                    </div>
                </div>

                <div class="bg-gradient-to-r from-green-50 to-emerald-50 p-6 rounded-xl border border-green-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-green-600 font-medium">Suivis Actifs</p>
                            <p class="text-3xl font-bold text-green-800">{{ $stats['suivis_actifs'] ?? 0 }}</p>
                            <p class="text-xs text-green-600 mt-1">
                                {{ $stats['taux_suivi'] ?? 0 }}% des visiteurs
                            </p>
                        </div>
                        <i class="fas fa-handshake text-green-500 text-2xl"></i>
                    </div>
                </div>

                <div class="bg-gradient-to-r from-purple-50 to-pink-50 p-6 rounded-xl border border-purple-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-purple-600 font-medium">Candidats Baptême</p>
                            <p class="text-3xl font-bold text-purple-800">{{ $stats['candidats_bapteme'] ?? 0 }}</p>
                            <p class="text-xs text-purple-600 mt-1">
                                {{ $stats['baptemes_planifies'] ?? 0 }} baptêmes planifiés
                            </p>
                        </div>
                        <i class="fas fa-water text-purple-500 text-2xl"></i>
                    </div>
                </div>

                <div class="bg-gradient-to-r from-amber-50 to-orange-50 p-6 rounded-xl border border-amber-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-amber-600 font-medium">Nouveaux Membres</p>
                            <p class="text-3xl font-bold text-amber-800">{{ $stats['futurs_membres'] ?? 0 }}</p>
                            <p class="text-xs text-amber-600 mt-1">
                                {{ $stats['membres_integres'] ?? 0 }} intégrés cette période
                            </p>
                        </div>
                        <i class="fas fa-heart text-amber-500 text-2xl"></i>
                    </div>
                </div>
            </div>

            <!-- Indicateurs clés -->
            <div class="mt-8">
                <h3 class="text-lg font-semibold text-slate-800 mb-4">Indicateurs Clés de Performance</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="bg-slate-50 p-4 rounded-xl">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm font-medium text-slate-700">Taux de Conversion</span>
                            <span class="text-lg font-bold text-slate-900">{{ $stats['taux_conversion'] ?? 0 }}%</span>
                        </div>
                        <div class="w-full bg-slate-200 rounded-full h-2">
                            <div class="bg-green-500 h-2 rounded-full" style="width: {{ $stats['taux_conversion'] ?? 0 }}%"></div>
                        </div>
                        <p class="text-xs text-slate-500 mt-1">Visiteurs devenus membres</p>
                    </div>

                    <div class="bg-slate-50 p-4 rounded-xl">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm font-medium text-slate-700">Taux de Rétention</span>
                            <span class="text-lg font-bold text-slate-900">{{ $stats['taux_retention'] ?? 0 }}%</span>
                        </div>
                        <div class="w-full bg-slate-200 rounded-full h-2">
                            <div class="bg-blue-500 h-2 rounded-full" style="width: {{ $stats['taux_retention'] ?? 0 }}%"></div>
                        </div>
                        <p class="text-xs text-slate-500 mt-1">Visiteurs revenus après 1ère visite</p>
                    </div>

                    <div class="bg-slate-50 p-4 rounded-xl">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm font-medium text-slate-700">Efficacité Suivi</span>
                            <span class="text-lg font-bold text-slate-900">{{ $stats['efficacite_suivi'] ?? 0 }}%</span>
                        </div>
                        <div class="w-full bg-slate-200 rounded-full h-2">
                            <div class="bg-purple-500 h-2 rounded-full" style="width: {{ $stats['efficacite_suivi'] ?? 0 }}%"></div>
                        </div>
                        <p class="text-xs text-slate-500 mt-1">Suivis menés à terme</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Détails par catégorie -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Nouveaux visiteurs -->
        <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
            <div class="p-6 border-b border-slate-200">
                <h2 class="text-xl font-bold text-slate-800 flex items-center">
                    <i class="fas fa-star text-purple-600 mr-2"></i>
                    Nouveaux Visiteurs ({{ count($nouveauxVisiteurs ?? []) }})
                </h2>
            </div>
            <div class="p-6">
                @if(isset($nouveauxVisiteurs) && count($nouveauxVisiteurs) > 0)
                    <div class="space-y-4">
                        @foreach($nouveauxVisiteurs as $visiteur)
                            <div class="bg-slate-50 p-4 rounded-lg border border-slate-200">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <h4 class="font-semibold text-slate-900">{{ $visiteur->participant->nom ?? 'N/A' }} {{ $visiteur->participant->prenom ?? '' }}</h4>
                                        <p class="text-sm text-slate-600">{{ $visiteur->culte->titre ?? 'Culte supprimé' }}</p>
                                        <p class="text-xs text-slate-500">{{ $visiteur->culte ? \Carbon\Carbon::parse($visiteur->culte->date_culte)->format('d/m/Y') : 'N/A' }}</p>
                                    </div>
                                    <div class="flex flex-col items-end space-y-1">
                                        @if($visiteur->demande_contact_pastoral)
                                            <span class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded-full">Contact demandé</span>
                                        @endif
                                        @if($visiteur->interesse_bapteme)
                                            <span class="text-xs bg-cyan-100 text-cyan-800 px-2 py-1 rounded-full">Baptême</span>
                                        @endif
                                        @if($visiteur->souhaite_devenir_membre)
                                            <span class="text-xs bg-green-100 text-green-800 px-2 py-1 rounded-full">Membre</span>
                                        @endif
                                    </div>
                                </div>
                                @if($visiteur->statut_suivi ?? false)
                                    <div class="mt-2 pt-2 border-t border-slate-200">
                                        <p class="text-xs text-slate-600">
                                            <i class="fas fa-info-circle mr-1"></i>
                                            Statut: {{ ucfirst(str_replace('_', ' ', $visiteur->statut_suivi)) }}
                                            @if($visiteur->responsable ?? false)
                                                | Responsable: {{ $visiteur->responsable->prenom ?? 'N/A' }} {{ $visiteur->responsable->nom ?? '' }}
                                            @endif
                                        </p>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <i class="fas fa-user-plus text-4xl text-slate-300 mb-4"></i>
                        <p class="text-slate-500">Aucun nouveau visiteur pour cette période</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Suivis actifs -->
        <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
            <div class="p-6 border-b border-slate-200">
                <h2 class="text-xl font-bold text-slate-800 flex items-center">
                    <i class="fas fa-handshake text-green-600 mr-2"></i>
                    Suivis Actifs ({{ count($suivisActifs ?? []) }})
                </h2>
            </div>
            <div class="p-6">
                @if(isset($suivisActifs) && count($suivisActifs) > 0)
                    <div class="space-y-4">
                        @foreach($suivisActifs as $suivi)
                            <div class="bg-slate-50 p-4 rounded-lg border border-slate-200">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <h4 class="font-semibold text-slate-900">{{ $suivi->participant->nom ?? 'N/A' }} {{ $suivi->participant->prenom ?? '' }}</h4>
                                        <p class="text-sm text-slate-600">{{ $suivi->type_suivi ?? 'Type non défini' }}</p>
                                        @if($suivi->responsable ?? false)
                                            <p class="text-xs text-slate-500">Responsable: {{ $suivi->responsable->prenom ?? 'N/A' }} {{ $suivi->responsable->nom ?? '' }}</p>
                                        @endif
                                    </div>
                                    <div class="flex flex-col items-end space-y-1">
                                        @php
                                            $prioriteColors = [
                                                'urgent' => 'bg-red-100 text-red-800',
                                                'haute' => 'bg-orange-100 text-orange-800',
                                                'normale' => 'bg-blue-100 text-blue-800',
                                                'faible' => 'bg-gray-100 text-gray-800'
                                            ];
                                        @endphp
                                        <span class="text-xs {{ $prioriteColors[$suivi->priorite ?? 'normale'] ?? 'bg-gray-100 text-gray-800' }} px-2 py-1 rounded-full">
                                            {{ ucfirst($suivi->priorite ?? 'Normale') }}
                                        </span>
                                        @if($suivi->date_planifiee ?? false)
                                            <span class="text-xs text-slate-500">
                                                {{ \Carbon\Carbon::parse($suivi->date_planifiee)->format('d/m H:i') }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                @if($suivi->objectifs ?? false)
                                    <div class="mt-2 pt-2 border-t border-slate-200">
                                        <p class="text-xs text-slate-600">{{ Str::limit($suivi->objectifs, 100) }}</p>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <i class="fas fa-handshake text-4xl text-slate-300 mb-4"></i>
                        <p class="text-slate-500">Aucun suivi actif en cours</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Analyse par responsable -->
    <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
        <div class="p-6 border-b border-slate-200">
            <h2 class="text-xl font-bold text-slate-800 flex items-center">
                <i class="fas fa-users-cog text-amber-600 mr-2"></i>
                Performance par Responsable
            </h2>
        </div>
        <div class="p-6">
            @if(isset($performanceResponsables) && count($performanceResponsables) > 0)
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b border-slate-200">
                                <th class="text-left py-3 px-4 font-semibold text-slate-700">Responsable</th>
                                <th class="text-center py-3 px-4 font-semibold text-slate-700">Suivis Assignés</th>
                                <th class="text-center py-3 px-4 font-semibold text-slate-700">Terminés</th>
                                <th class="text-center py-3 px-4 font-semibold text-slate-700">Taux Réussite</th>
                                <th class="text-center py-3 px-4 font-semibold text-slate-700">Délai Moyen</th>
                                <th class="text-center py-3 px-4 font-semibold text-slate-700">Évaluation</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($performanceResponsables as $responsable)
                                <tr class="border-b border-slate-100 hover:bg-slate-50">
                                    <td class="py-3 px-4">
                                        <div class="flex items-center space-x-2">
                                            <div class="w-8 h-8 bg-gradient-to-r from-blue-500 to-purple-500 rounded-full flex items-center justify-center">
                                                <span class="text-white text-xs font-bold">{{ substr($responsable['prenom'] ?? 'N', 0, 1) }}{{ substr($responsable['nom'] ?? 'A', 0, 1) }}</span>
                                            </div>
                                            <span class="font-medium text-slate-900">{{ $responsable['nom'] ?? 'N/A' }} {{ $responsable['prenom'] ?? '' }}</span>
                                        </div>
                                    </td>
                                    <td class="text-center py-3 px-4">{{ $responsable['suivis_assignes'] ?? 0 }}</td>
                                    <td class="text-center py-3 px-4">{{ $responsable['suivis_termines'] ?? 0 }}</td>
                                    <td class="text-center py-3 px-4">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{
                                            ($responsable['taux_reussite'] ?? 0) >= 80 ? 'bg-green-100 text-green-800' :
                                            (($responsable['taux_reussite'] ?? 0) >= 60 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800')
                                        }}">
                                            {{ $responsable['taux_reussite'] ?? 0 }}%
                                        </span>
                                    </td>
                                    <td class="text-center py-3 px-4">{{ $responsable['delai_moyen'] ?? 'N/A' }} jours</td>
                                    <td class="text-center py-3 px-4">
                                        @php
                                            $score = $responsable['score_global'] ?? 0;
                                        @endphp
                                        <div class="flex justify-center">
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="fas fa-star text-xs {{ $i <= $score ? 'text-yellow-400' : 'text-slate-300' }}"></i>
                                            @endfor
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-8">
                    <i class="fas fa-chart-bar text-4xl text-slate-300 mb-4"></i>
                    <p class="text-slate-500">Aucune donnée de performance disponible</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Recommandations et actions -->
    <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
        <div class="p-6 border-b border-slate-200">
            <h2 class="text-xl font-bold text-slate-800 flex items-center">
                <i class="fas fa-lightbulb text-yellow-600 mr-2"></i>
                Recommandations et Plan d'Action
            </h2>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Recommandations -->
                <div>
                    <h3 class="text-lg font-semibold text-slate-800 mb-4">Recommandations</h3>
                    <div class="space-y-4">
                        @if(isset($recommandations) && count($recommandations) > 0)
                            @foreach($recommandations as $recommandation)
                                <div class="p-4 {{ $recommandation['priorite'] == 'haute' ? 'bg-red-50 border-red-200' : ($recommandation['priorite'] == 'moyenne' ? 'bg-yellow-50 border-yellow-200' : 'bg-blue-50 border-blue-200') }} border rounded-lg">
                                    <div class="flex items-start space-x-3">
                                        <i class="fas {{ $recommandation['priorite'] == 'haute' ? 'fa-exclamation-triangle text-red-500' : ($recommandation['priorite'] == 'moyenne' ? 'fa-exclamation-circle text-yellow-500' : 'fa-info-circle text-blue-500') }} mt-1"></i>
                                        <div>
                                            <h4 class="font-medium text-slate-900">{{ $recommandation['titre'] ?? 'Recommandation' }}</h4>
                                            <p class="text-sm text-slate-600 mt-1">{{ $recommandation['description'] ?? 'Description par défaut' }}</p>
                                            @if(isset($recommandation['actions']))
                                                <ul class="text-xs text-slate-500 mt-2 list-disc list-inside">
                                                    @foreach($recommandation['actions'] as $action)
                                                        <li>{{ $action }}</li>
                                                    @endforeach
                                                </ul>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <!-- Recommandations par défaut basées sur les données -->
                            @if(($stats['taux_conversion'] ?? 0) < 20)
                                <div class="p-4 bg-red-50 border-red-200 border rounded-lg">
                                    <div class="flex items-start space-x-3">
                                        <i class="fas fa-exclamation-triangle text-red-500 mt-1"></i>
                                        <div>
                                            <h4 class="font-medium text-slate-900">Améliorer le taux de conversion</h4>
                                            <p class="text-sm text-slate-600 mt-1">Le taux de conversion des visiteurs en membres est faible ({{ $stats['taux_conversion'] ?? 0 }}%).</p>
                                            <ul class="text-xs text-slate-500 mt-2 list-disc list-inside">
                                                <li>Renforcer l'accueil des nouveaux visiteurs</li>
                                                <li>Améliorer le processus d'intégration</li>
                                                <li>Former les équipes d'accompagnement</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if(($stats['suivis_actifs'] ?? 0) < ($stats['total_visiteurs'] ?? 0) * 0.5)
                                <div class="p-4 bg-yellow-50 border-yellow-200 border rounded-lg">
                                    <div class="flex items-start space-x-3">
                                        <i class="fas fa-exclamation-circle text-yellow-500 mt-1"></i>
                                        <div>
                                            <h4 class="font-medium text-slate-900">Intensifier le suivi pastoral</h4>
                                            <p class="text-sm text-slate-600 mt-1">Moins de 50% des visiteurs bénéficient d'un suivi pastoral actif.</p>
                                            <ul class="text-xs text-slate-500 mt-2 list-disc list-inside">
                                                <li>Systématiser le suivi des nouveaux visiteurs</li>
                                                <li>Former plus de responsables de suivi</li>
                                                <li>Mettre en place des outils de suivi</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if(!isset($recommandations) || count($recommandations) === 0)
                                <div class="p-4 bg-green-50 border-green-200 border rounded-lg">
                                    <div class="flex items-start space-x-3">
                                        <i class="fas fa-check-circle text-green-500 mt-1"></i>
                                        <div>
                                            <h4 class="font-medium text-slate-900">Performance satisfaisante</h4>
                                            <p class="text-sm text-slate-600 mt-1">Les indicateurs de suivi pastoral sont dans les normes acceptables.</p>
                                            <ul class="text-xs text-slate-500 mt-2 list-disc list-inside">
                                                <li>Maintenir les efforts actuels</li>
                                                <li>Continuer à former les équipes</li>
                                                <li>Surveiller l'évolution des indicateurs</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endif
                    </div>
                </div>

                <!-- Plan d'action -->
                <div>
                    <h3 class="text-lg font-semibold text-slate-800 mb-4">Plan d'Action - 30 Prochains Jours</h3>
                    <div class="space-y-3">
                        @if(isset($planAction) && count($planAction) > 0)
                            @foreach($planAction as $action)
                                <div class="flex items-start space-x-3 p-3 bg-slate-50 rounded-lg">
                                    <div class="flex-shrink-0 mt-1">
                                        <div class="w-6 h-6 bg-blue-100 rounded-full flex items-center justify-center">
                                            <span class="text-blue-600 text-xs font-bold">{{ $loop->iteration }}</span>
                                        </div>
                                    </div>
                                    <div class="flex-1">
                                        <h4 class="font-medium text-slate-900">{{ $action['titre'] ?? 'Action' }}</h4>
                                        <p class="text-sm text-slate-600">{{ $action['description'] ?? 'Description par défaut' }}</p>
                                        <div class="flex items-center mt-2 text-xs text-slate-500">
                                            <i class="fas fa-calendar mr-1"></i>
                                            <span>{{ $action['echeance'] ?? 'À définir' }}</span>
                                            @if(isset($action['responsable']))
                                                <i class="fas fa-user ml-3 mr-1"></i>
                                                <span>{{ $action['responsable'] }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <!-- Plan d'action par défaut -->
                            <div class="flex items-start space-x-3 p-3 bg-slate-50 rounded-lg">
                                <div class="flex-shrink-0 mt-1">
                                    <div class="w-6 h-6 bg-blue-100 rounded-full flex items-center justify-center">
                                        <span class="text-blue-600 text-xs font-bold">1</span>
                                    </div>
                                </div>
                                <div class="flex-1">
                                    <h4 class="font-medium text-slate-900">Contacter les nouveaux visiteurs</h4>
                                    <p class="text-sm text-slate-600">Programmer des contacts avec tous les nouveaux visiteurs identifiés</p>
                                    <div class="flex items-center mt-2 text-xs text-slate-500">
                                        <i class="fas fa-calendar mr-1"></i>
                                        <span>Semaine 1</span>
                                        <i class="fas fa-user ml-3 mr-1"></i>
                                        <span>Équipe pastorale</span>
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-start space-x-3 p-3 bg-slate-50 rounded-lg">
                                <div class="flex-shrink-0 mt-1">
                                    <div class="w-6 h-6 bg-blue-100 rounded-full flex items-center justify-center">
                                        <span class="text-blue-600 text-xs font-bold">2</span>
                                    </div>
                                </div>
                                <div class="flex-1">
                                    <h4 class="font-medium text-slate-900">Formation équipe d'accueil</h4>
                                    <p class="text-sm text-slate-600">Organiser une session de formation pour améliorer l'accueil</p>
                                    <div class="flex items-center mt-2 text-xs text-slate-500">
                                        <i class="fas fa-calendar mr-1"></i>
                                        <span>Semaine 2</span>
                                        <i class="fas fa-user ml-3 mr-1"></i>
                                        <span>Pasteur principal</span>
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-start space-x-3 p-3 bg-slate-50 rounded-lg">
                                <div class="flex-shrink-0 mt-1">
                                    <div class="w-6 h-6 bg-blue-100 rounded-full flex items-center justify-center">
                                        <span class="text-blue-600 text-xs font-bold">3</span>
                                    </div>
                                </div>
                                <div class="flex-1">
                                    <h4 class="font-medium text-slate-900">Évaluation du processus</h4>
                                    <p class="text-sm text-slate-600">Réviser et optimiser le processus de suivi pastoral</p>
                                    <div class="flex items-center mt-2 text-xs text-slate-500">
                                        <i class="fas fa-calendar mr-1"></i>
                                        <span>Semaine 4</span>
                                        <i class="fas fa-user ml-3 mr-1"></i>
                                        <span>Conseil pastoral</span>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Conclusion et prochaines étapes -->
    <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
        <div class="p-6 border-b border-slate-200">
            <h2 class="text-xl font-bold text-slate-800 flex items-center">
                <i class="fas fa-flag-checkered text-red-600 mr-2"></i>
                Conclusion et Prochaines Étapes
            </h2>
        </div>
        <div class="p-6">
            <div class="prose max-w-none">
                <p class="text-slate-700 leading-relaxed mb-4">
                    Ce rapport couvre une période de {{ request('periode', 90) }} jours et présente un aperçu complet
                    des activités de suivi pastoral de notre communauté. Les données analysées montrent
                    {{ isset($stats['total_visiteurs']) && $stats['total_visiteurs'] > 0 ? 'une activité significative' : 'une activité modérée' }}
                    en matière d'accueil et de suivi des nouveaux membres.
                </p>

                <h4 class="text-lg font-semibold text-slate-800 mb-3">Points Clés :</h4>
                <ul class="list-disc list-inside text-slate-700 space-y-2 mb-6">
                    <li><strong>{{ $stats['total_visiteurs'] ?? 0 }} nouveaux visiteurs</strong> ont été accueillis durant cette période</li>
                    <li><strong>{{ $stats['suivis_actifs'] ?? 0 }} suivis pastoral</strong> sont actuellement en cours</li>
                    <li><strong>{{ $stats['candidats_bapteme'] ?? 0 }} personnes</strong> s'intéressent au baptême</li>
                    <li><strong>{{ $stats['futurs_membres'] ?? 0 }} personnes</strong> souhaitent devenir membres</li>
                </ul>

                <h4 class="text-lg font-semibold text-slate-800 mb-3">Prochaines Étapes :</h4>
                <div class="bg-blue-50 p-4 rounded-lg border border-blue-200">
                    <ul class="list-disc list-inside text-slate-700 space-y-1">
                        <li>Mettre en œuvre le plan d'action sur 30 jours</li>
                        <li>Planifier le prochain rapport dans {{ request('periode', 90) }} jours</li>
                        <li>Suivre l'évolution des indicateurs clés</li>
                        <li>Ajuster les stratégies selon les résultats obtenus</li>
                    </ul>
                </div>

                <div class="mt-6 pt-6 border-t border-slate-200 text-center">
                    <p class="text-sm text-slate-500">
                        Rapport généré automatiquement le {{ now()->format('d/m/Y à H:i') }} |
                        Période analysée: {{ request('periode', 90) }} derniers jours |
                        Prochaine révision recommandée: {{ now()->addDays(30)->format('d/m/Y') }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
@media print {
    body { -webkit-print-color-adjust: exact; }
    .no-print { display: none !important; }
    .bg-white\/80 { background-color: white !important; }
    .shadow-lg { box-shadow: none !important; }
    .hover\:shadow-xl:hover { box-shadow: none !important; }
    .page-break { page-break-before: always; }
}
</style>
@endpush

@push('scripts')
<script>
function exportPDF() {
    // Préparer les paramètres pour l'export PDF
    const urlParams = new URLSearchParams(window.location.search);
    const exportUrl = new URL(window.location.href);
    exportUrl.searchParams.append('export', 'pdf');

    // Ouvrir dans un nouvel onglet pour le téléchargement
    window.open(exportUrl.toString(), '_blank');
}

// Améliorer l'impression
window.addEventListener('beforeprint', function() {
    document.body.classList.add('printing');
});

window.addEventListener('afterprint', function() {
    document.body.classList.remove('printing');
});
</script>
@endpush
@endsection
