@extends('layouts.private.main')
@section('title', 'Tableau de bord')
@section('content')

<div class="space-y-8">
    <!-- Page Header -->
    <div class="mb-8">
        <div class="bg-white rounded-xl shadow-lg p-6 border border-slate-200">
            <h2 class="text-4xl font-bold text-slate-800 mb-2">Tableau de bord général</h2>
            <p class="text-slate-600">Vue d'ensemble des activités de l'église - {{ \Carbon\Carbon::now()->format('l d F Y') }}</p>
        </div>
    </div>

    <!-- Notifications/Alertes -->
    @if(!empty($notifications))
    <div class="mb-6">
        @foreach($notifications as $notification)
        <div class="mb-3 p-4 rounded-lg border-l-4 @if($notification['type'] == 'warning') bg-yellow-50 border-yellow-400 @elseif($notification['type'] == 'info') bg-blue-50 border-blue-400 @elseif($notification['type'] == 'success') bg-green-50 border-green-400 @else bg-gray-50 border-gray-400 @endif">
            <div class="flex justify-between items-center">
                <div>
                    <h4 class="font-semibold @if($notification['type'] == 'warning') text-yellow-800 @elseif($notification['type'] == 'info') text-blue-800 @elseif($notification['type'] == 'success') text-green-800 @else text-gray-800 @endif">
                        {{ $notification['titre'] }}
                    </h4>
                    <p class="@if($notification['type'] == 'warning') text-yellow-700 @elseif($notification['type'] == 'info') text-blue-700 @elseif($notification['type'] == 'success') text-green-700 @else text-gray-700 @endif">
                        {{ $notification['message'] }}
                    </p>
                </div>
                <a href="{{ $notification['action_url'] }}" class="px-4 py-2 text-sm font-medium rounded-lg @if($notification['type'] == 'warning') bg-yellow-200 text-yellow-800 hover:bg-yellow-300 @elseif($notification['type'] == 'info') bg-blue-200 text-blue-800 hover:bg-blue-300 @elseif($notification['type'] == 'success') bg-green-200 text-green-800 hover:bg-green-300 @else bg-gray-200 text-gray-800 hover:bg-gray-300 @endif transition-colors">
                    Voir
                </a>
            </div>
        </div>
        @endforeach
    </div>
    @endif

    <!-- Statistiques Générales -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Membres -->
        <div class="bg-white rounded-xl shadow-lg p-6 border border-slate-200 hover:shadow-xl transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-slate-600 text-sm font-medium">Total Membres</p>
                    <p class="text-3xl font-bold text-slate-800">{{ number_format($statistiques_generales['total_membres']) }}</p>
                    <p class="text-sm text-green-600 mt-1">+{{ $statistiques_generales['nouveaux_membres_mois'] }} ce mois</p>
                </div>
                <div class="bg-blue-100 p-3 rounded-full">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Cultes ce mois -->
        <div class="bg-white rounded-xl shadow-lg p-6 border border-slate-200 hover:shadow-xl transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-slate-600 text-sm font-medium">Cultes ce mois</p>
                    <p class="text-3xl font-bold text-slate-800">{{ $statistiques_generales['total_cultes_mois'] }}</p>
                    <p class="text-sm text-slate-500 mt-1">Moyenne: {{ number_format($statistiques_generales['moyenne_participants_culte'], 0) }} participants</p>
                </div>
                <div class="bg-purple-100 p-3 rounded-full">
                    <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Offrandes du mois -->
        <div class="bg-white rounded-xl shadow-lg p-6 border border-slate-200 hover:shadow-xl transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-slate-600 text-sm font-medium">Offrandes du mois</p>
                    <p class="text-3xl font-bold text-slate-800">{{ number_format($statistiques_generales['offrandes_mois'], 0, ',', ' ') }} FCFA</p>
                    <p class="text-sm text-slate-500 mt-1">{{ $finances['transactions_en_attente'] }} en attente</p>
                </div>
                <div class="bg-green-100 p-3 rounded-full">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Événements planifiés -->
        <div class="bg-white rounded-xl shadow-lg p-6 border border-slate-200 hover:shadow-xl transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-slate-600 text-sm font-medium">Événements à venir</p>
                    <p class="text-3xl font-bold text-slate-800">{{ $statistiques_generales['evenements_planifies'] }}</p>
                    <p class="text-sm text-slate-500 mt-1">Planifiés et ouverts</p>
                </div>
                <div class="bg-orange-100 p-3 rounded-full">
                    <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Graphiques et Analyses -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Graphique des performances mensuelles -->
        <div class="bg-white rounded-xl shadow-lg p-6 border border-slate-200">
            <h3 class="text-xl font-bold text-slate-800 mb-4">Performances des 6 derniers mois</h3>
            <div class="space-y-4">
                @foreach($performances_mensuelles as $perf)
                <div class="flex items-center justify-between p-3 bg-slate-50 rounded-lg">
                    <div class="flex-1">
                        <p class="font-medium text-slate-700">{{ $perf['mois'] }}</p>
                        <div class="flex space-x-4 text-sm text-slate-600 mt-1">
                            <span>{{ $perf['cultes'] }} cultes</span>
                            <span>{{ $perf['participants_moyens'] }} participants moy.</span>
                            <span>{{ number_format($perf['nouveaux_membres']) }} nouveaux</span>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="font-bold text-green-600">{{ number_format($perf['offrandes'], 0, ',', ' ') }} F</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Répartition des membres -->
        <div class="bg-white rounded-xl shadow-lg p-6 border border-slate-200">
            <h3 class="text-xl font-bold text-slate-800 mb-4">Répartition des membres</h3>

            <!-- Par statut -->
            <div class="mb-6">
                <h4 class="font-semibold text-slate-700 mb-2">Par statut</h4>
                @foreach($membres_statistiques['par_statut'] as $statut)
                <div class="flex justify-between items-center py-2">
                    <span class="text-slate-600 capitalize">{{ str_replace('_', ' ', $statut->statut_membre) }}</span>
                    <span class="font-semibold text-slate-800">{{ $statut->total }}</span>
                </div>
                @endforeach
            </div>

            <!-- Par sexe -->
            <div class="mb-6">
                <h4 class="font-semibold text-slate-700 mb-2">Par sexe</h4>
                @foreach($membres_statistiques['par_sexe'] as $sexe)
                <div class="flex justify-between items-center py-2">
                    <span class="text-slate-600 capitalize">{{ $sexe->sexe }}</span>
                    <span class="font-semibold text-slate-800">{{ $sexe->total }}</span>
                </div>
                @endforeach
            </div>

            <!-- Informations additionnelles -->
            <div class="pt-4 border-t border-slate-200">
                <div class="grid grid-cols-2 gap-4 text-center">
                    <div>
                        <p class="text-2xl font-bold text-blue-600">{{ $membres_statistiques['anniversaires_mois'] }}</p>
                        <p class="text-sm text-slate-600">Anniversaires ce mois</p>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-green-600">{{ $membres_statistiques['nouveaux_visiteurs'] }}</p>
                        <p class="text-sm text-slate-600">Nouveaux visiteurs</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sections d'activités -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <!-- Activités récentes -->
        <div class="bg-white rounded-xl shadow-lg p-6 border border-slate-200">
            <h3 class="text-xl font-bold text-slate-800 mb-4">Activités récentes</h3>
            <div class="space-y-3">
                @foreach($activites_recentes->take(8) as $activite)
                <div class="flex items-start space-x-3 p-3 rounded-lg hover:bg-slate-50">
                    <div class="flex-shrink-0">
                        <div class="w-3 h-3 rounded-full @if($activite['type'] == 'culte') bg-purple-500 @elseif($activite['type'] == 'evenement') bg-blue-500 @else bg-green-500 @endif"></div>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="font-medium text-slate-800 text-sm">{{ $activite['titre'] }}</p>
                        <p class="text-xs text-slate-600">{{ $activite['details'] }}</p>
                        <p class="text-xs text-slate-500">{{ \Carbon\Carbon::parse($activite['date'])->format('d/m/Y') }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Événements à venir -->
        <div class="bg-white rounded-xl shadow-lg p-6 border border-slate-200">
            <h3 class="text-xl font-bold text-slate-800 mb-4">Prochains événements</h3>
            <div class="space-y-3">
                @foreach($evenements_a_venir->take(6) as $event)
                <div class="p-3 border border-slate-200 rounded-lg hover:shadow-md transition-shadow">
                    <h4 class="font-semibold text-slate-800 text-sm mb-1">{{ $event->titre }}</h4>
                    <div class="text-xs text-slate-600 space-y-1">
                        <p><span class="font-medium">Date:</span> {{ \Carbon\Carbon::parse($event->date_debut)->format('d/m/Y') }}</p>
                        <p><span class="font-medium">Lieu:</span> {{ $event->lieu_nom }}</p>
                        @if($event->nombre_inscrits)
                        <p><span class="font-medium">Inscrits:</span> {{ $event->nombre_inscrits }}{{ $event->places_disponibles ? '/'.$event->places_disponibles : '' }}</p>
                        @endif
                        <span class="inline-block px-2 py-1 text-xs rounded-full @if($event->jours_restants <= 7) bg-red-100 text-red-700 @elseif($event->jours_restants <= 30) bg-yellow-100 text-yellow-700 @else bg-green-100 text-green-700 @endif">
                            Dans {{ $event->jours_restants }} jour(s)
                        </span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Annonces importantes -->
        <div class="bg-white rounded-xl shadow-lg p-6 border border-slate-200">
            <h3 class="text-xl font-bold text-slate-800 mb-4">Annonces importantes</h3>
            <div class="space-y-3">
                @foreach($annonces_importantes as $annonce)
                <div class="p-3 rounded-lg @if($annonce->niveau_priorite == 'urgent') bg-red-50 border border-red-200 @elseif($annonce->niveau_priorite == 'important') bg-yellow-50 border border-yellow-200 @else bg-blue-50 border border-blue-200 @endif">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <h4 class="font-semibold @if($annonce->niveau_priorite == 'urgent') text-red-800 @elseif($annonce->niveau_priorite == 'important') text-yellow-800 @else text-blue-800 @endif text-sm mb-1">
                                {{ $annonce->titre }}
                            </h4>
                            <p class="text-xs @if($annonce->niveau_priorite == 'urgent') text-red-700 @elseif($annonce->niveau_priorite == 'important') text-yellow-700 @else text-blue-700 @endif mb-2">
                                {{ Str::limit($annonce->contenu, 120) }}
                            </p>
                            @if($annonce->date_evenement)
                            <p class="text-xs text-slate-600">
                                <span class="font-medium">Date:</span> {{ \Carbon\Carbon::parse($annonce->date_evenement)->format('d/m/Y') }}
                            </p>
                            @endif
                        </div>
                        <span class="ml-2 px-2 py-1 text-xs font-medium rounded-full @if($annonce->niveau_priorite == 'urgent') bg-red-200 text-red-800 @elseif($annonce->niveau_priorite == 'important') bg-yellow-200 text-yellow-800 @else bg-blue-200 text-blue-800 @endif">
                            {{ ucfirst($annonce->niveau_priorite) }}
                        </span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Cultes récents et Finances -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Cultes récents -->
        <div class="bg-white rounded-xl shadow-lg p-6 border border-slate-200">
            <h3 class="text-xl font-bold text-slate-800 mb-4">Cultes récents</h3>
            <div class="space-y-4">
                @foreach($cultes_recents as $culte)
                <div class="p-4 border border-slate-200 rounded-lg hover:shadow-md transition-shadow">
                    <div class="flex justify-between items-start mb-2">
                        <h4 class="font-semibold text-slate-800">{{ $culte->titre }}</h4>
                        <span class="px-2 py-1 text-xs font-medium rounded-full @if($culte->statut == 'termine') bg-green-100 text-green-700 @else bg-yellow-100 text-yellow-700 @endif">
                            {{ ucfirst($culte->statut) }}
                        </span>
                    </div>
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <p class="text-slate-600">Date: {{ \Carbon\Carbon::parse($culte->date_culte)->format('d/m/Y') }}</p>
                            <p class="text-slate-600">Type: {{ str_replace('_', ' ', $culte->type_culte) }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-slate-600">Participants: <span class="font-semibold">{{ $culte->nombre_participants ?: 'N/A' }}</span></p>
                            @if($culte->nombre_conversions)
                            <p class="text-green-600">Conversions: <span class="font-semibold">{{ $culte->nombre_conversions }}</span></p>
                            @endif
                        </div>
                    </div>
                    @if($culte->offrande_totale)
                    <div class="mt-2 pt-2 border-t border-slate-200">
                        <p class="text-sm text-slate-600">Offrandes: <span class="font-semibold text-green-600">{{ number_format($culte->offrande_totale, 0, ',', ' ') }} FCFA</span></p>
                    </div>
                    @endif
                </div>
                @endforeach
            </div>
        </div>

        <!-- Statistiques financières -->
        <div class="bg-white rounded-xl shadow-lg p-6 border border-slate-200">
            <h3 class="text-xl font-bold text-slate-800 mb-4">Aperçu financier</h3>

            <!-- Comparaison mensuelle -->
            <div class="grid grid-cols-2 gap-4 mb-6">
                <div class="text-center p-4 bg-green-50 rounded-lg">
                    <p class="text-sm text-slate-600 mb-1">Offrandes ce mois</p>
                    <p class="text-2xl font-bold text-green-600">{{ number_format($finances['offrandes_mois_actuel'], 0, ',', ' ') }}</p>
                    <p class="text-xs text-slate-500">FCFA</p>
                </div>
                <div class="text-center p-4 bg-blue-50 rounded-lg">
                    <p class="text-sm text-slate-600 mb-1">Dîmes ce mois</p>
                    <p class="text-2xl font-bold text-blue-600">{{ number_format($finances['dimes_mois_actuel'], 0, ',', ' ') }}</p>
                    <p class="text-xs text-slate-500">FCFA</p>
                </div>
            </div>

            <!-- Comparaison avec le mois précédent -->
            @php
                $evolutionOffrandes = $finances['offrandes_mois_precedent'] > 0
                    ? (($finances['offrandes_mois_actuel'] - $finances['offrandes_mois_precedent']) / $finances['offrandes_mois_precedent'] * 100)
                    : 0;
            @endphp
            <div class="mb-6 p-3 bg-slate-50 rounded-lg">
                <p class="text-sm text-slate-600 mb-2">Évolution vs mois précédent</p>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-slate-700">Offrandes:</span>
                    <span class="flex items-center @if($evolutionOffrandes >= 0) text-green-600 @else text-red-600 @endif">
                        @if($evolutionOffrandes >= 0)
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 17l9.2-9.2M17 17V7H7"/>
                            </svg>
                        @else
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 7l-9.2 9.2M7 7v10h10"/>
                            </svg>
                        @endif
                        {{-- {{ abs(number_format($evolutionOffrandes, 1)) }}% --}}
                       {{ number_format(abs($evolutionOffrandes), 1) }}%
                    </span>
                </div>
            </div>

            <!-- Répartition par type -->
            @if($finances['repartition_par_type']->count() > 0)
            <div>
                <h4 class="font-semibold text-slate-700 mb-3">Répartition par type ce mois</h4>
                @foreach($finances['repartition_par_type'] as $type)
                <div class="flex justify-between items-center py-2">
                    <span class="text-sm text-slate-600 capitalize">{{ str_replace(['_', 'offrande'], [' ', 'off.'], $type->type_transaction) }}</span>
                    <span class="font-semibold text-slate-800">{{ number_format($type->total, 0, ',', ' ') }} F</span>
                </div>
                @endforeach
            </div>
            @endif

            <!-- Informations supplémentaires -->
            <div class="mt-6 pt-4 border-t border-slate-200">
                <div class="grid grid-cols-2 gap-4 text-center">
                    <div>
                        <p class="text-xl font-bold text-orange-600">{{ $finances['transactions_en_attente'] }}</p>
                        <p class="text-xs text-slate-600">Transactions en attente</p>
                    </div>
                    <div>
                        <p class="text-xl font-bold text-purple-600">{{ $finances['donateurs_reguliers_count'] }}</p>
                        <p class="text-xs text-slate-600">Donateurs réguliers</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Multimédia récents -->
    @if($multimedia_recents->count() > 0)
    <div class="bg-white rounded-xl shadow-lg p-6 border border-slate-200 mb-8">
        <h3 class="text-xl font-bold text-slate-800 mb-4">Médias récents</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($multimedia_recents as $media)
            <div class="group relative overflow-hidden rounded-lg border border-slate-200 hover:shadow-md transition-all">
                @if($media->miniature)
                <div class="aspect-video bg-slate-100 overflow-hidden">
                    <img src="{{ $media->miniature }}" alt="{{ $media->titre }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform">
                </div>
                @else
                <div class="aspect-video bg-slate-100 flex items-center justify-center">
                    <svg class="w-12 h-12 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        @if($media->type_media == 'video')
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                        @elseif($media->type_media == 'audio')
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M9 12a3 3 0 106 0v5a3 3 0 11-6 0V7a3 3 0 013-3z"/>
                        @else
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        @endif
                    </svg>
                </div>
                @endif
                <div class="p-3">
                    <h4 class="font-medium text-slate-800 text-sm mb-1">{{ Str::limit($media->titre, 50) }}</h4>
                    <div class="flex justify-between items-center text-xs text-slate-500">
                        <span class="capitalize">{{ str_replace('_', ' ', $media->type_media) }}</span>
                        <span>{{ $media->nombre_vues }} vues</span>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

</div>

@endsection
