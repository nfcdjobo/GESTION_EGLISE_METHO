{{--
    Composant partiel pour afficher une carte de culte

    Props attendues:
    - $culte: Instance du modèle Culte
    - $showActions: Boolean pour afficher les actions (default: true)
    - $compact: Boolean pour un affichage compact (default: false)
    - $clickable: Boolean pour rendre la carte cliquable (default: true)
--}}

@php
    $showActions = $showActions ?? true;
    $compact = $compact ?? false;
    $clickable = $clickable ?? true;

    $statutColors = [
        'planifie' => 'bg-blue-100 text-blue-800',
        'en_preparation' => 'bg-yellow-100 text-yellow-800',
        'en_cours' => 'bg-orange-100 text-orange-800',
        'termine' => 'bg-green-100 text-green-800',
        'annule' => 'bg-red-100 text-red-800',
        'reporte' => 'bg-purple-100 text-purple-800'
    ];
@endphp

<div class="bg-gradient-to-br from-white to-slate-50 rounded-xl border border-slate-200 p-{{ $compact ? '4' : '6' }} hover:shadow-lg transition-all duration-300 hover:-translate-y-1 {{ $clickable ? 'cursor-pointer' : '' }}"
     @if($clickable) onclick="window.location.href='{{ route('private.cultes.show', $culte) }}'" @endif>

    <!-- Header -->
    <div class="flex items-start justify-between mb-{{ $compact ? '3' : '4' }}">
        <div class="flex-1">
            <h3 class="text-{{ $compact ? 'base' : 'lg' }} font-bold text-slate-900 mb-1">{{ $culte->titre }}</h3>
            <p class="text-sm text-slate-600">{{ $culte->type_culte_libelle }}</p>
        </div>
        <div class="flex flex-col items-end space-y-2">
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statutColors[$culte->statut] ?? 'bg-gray-100 text-gray-800' }}">
                {{ $culte->statut_libelle }}
            </span>
            @if($culte->est_public)
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-cyan-100 text-cyan-800">
                    <i class="fas fa-globe mr-1"></i> Public
                </span>
            @endif
            @if($culte->diffusion_en_ligne)
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                    <i class="fas fa-broadcast-tower mr-1"></i> Live
                </span>
            @endif
        </div>
    </div>

    <!-- Détails -->
    <div class="space-y-{{ $compact ? '2' : '3' }} mb-{{ $compact ? '3' : '4' }}">
        <div class="flex items-center text-sm text-slate-600">
            <i class="fas fa-calendar-alt w-4 mr-2"></i>
            <span>{{ \Carbon\Carbon::parse($culte->date_culte)->format('d/m/Y') }}</span>
            <i class="fas fa-clock w-4 ml-4 mr-2"></i>
            <span>{{ \Carbon\Carbon::parse($culte->heure_debut)->format('H:i') }}</span>
            @if($culte->heure_fin)
                <span> - {{ \Carbon\Carbon::parse($culte->heure_fin)->format('H:i') }}</span>
            @endif
        </div>

        @if($culte->lieu !== 'Église principale')
            <div class="flex items-center text-sm text-slate-600">
                <i class="fas fa-map-marker-alt w-4 mr-2"></i>
                <span>{{ $culte->lieu }}</span>
            </div>
        @endif

        @if($culte->pasteurPrincipal && !$compact)
            <div class="flex items-center text-sm text-slate-600">
                <i class="fas fa-user w-4 mr-2"></i>
                <span>{{ $culte->pasteurPrincipal->nom }} {{ $culte->pasteurPrincipal->prenom }}</span>
            </div>
        @endif

        @if($culte->nombre_participants)
            <div class="flex items-center text-sm text-slate-600">
                <i class="fas fa-users w-4 mr-2"></i>
                <span>{{ $culte->nombre_participants }} participants</span>
            </div>
        @endif

        @if($culte->titre_message && !$compact)
            <div class="flex items-center text-sm text-slate-600">
                <i class="fas fa-bible w-4 mr-2"></i>
                <span class="truncate">{{ $culte->titre_message }}</span>
            </div>
        @endif
    </div>

    @if($showActions)
        <!-- Actions -->
        <div class="flex items-center justify-between pt-{{ $compact ? '3' : '4' }} border-t border-slate-200">
            <div class="flex items-center space-x-2">
                @can('cultes.read')
                    <a href="{{ route('private.cultes.show', $culte) }}"
                       onclick="event.stopPropagation()"
                       class="inline-flex items-center justify-center w-8 h-8 text-cyan-600 bg-cyan-100 rounded-lg hover:bg-cyan-200 transition-colors"
                       title="Voir">
                        <i class="fas fa-eye text-sm"></i>
                    </a>
                @endcan

                @can('cultes.update')
                    <a href="{{ route('private.cultes.edit', $culte) }}"
                       onclick="event.stopPropagation()"
                       class="inline-flex items-center justify-center w-8 h-8 text-yellow-600 bg-yellow-100 rounded-lg hover:bg-yellow-200 transition-colors"
                       title="Modifier">
                        <i class="fas fa-edit text-sm"></i>
                    </a>
                @endcan

                @if($culte->statut !== 'termine')
                    <button type="button"
                            onclick="event.stopPropagation(); openStatusModal('{{ $culte->id }}', '{{ $culte->statut }}')"
                            class="inline-flex items-center justify-center w-8 h-8 text-blue-600 bg-blue-100 rounded-lg hover:bg-blue-200 transition-colors"
                            title="Changer statut">
                        <i class="fas fa-exchange-alt text-sm"></i>
                    </button>
                @endif

                @can('cultes.create')
                    <button type="button"
                            onclick="event.stopPropagation(); openDuplicateModal('{{ $culte->id }}')"
                            class="inline-flex items-center justify-center w-8 h-8 text-purple-600 bg-purple-100 rounded-lg hover:bg-purple-200 transition-colors"
                            title="Dupliquer">
                        <i class="fas fa-copy text-sm"></i>
                    </button>
                @endcan

                @if($culte->lien_diffusion_live)
                    <a href="{{ $culte->lien_diffusion_live }}"
                       target="_blank"
                       onclick="event.stopPropagation()"
                       class="inline-flex items-center justify-center w-8 h-8 text-green-600 bg-green-100 rounded-lg hover:bg-green-200 transition-colors"
                       title="Diffusion live">
                        <i class="fas fa-broadcast-tower text-sm"></i>
                    </a>
                @endif
            </div>

            @can('cultes.delete')
                @if($culte->statut !== 'en_cours')
                    <button type="button"
                            onclick="event.stopPropagation(); deleteCulte('{{ $culte->id }}')"
                            class="inline-flex items-center justify-center w-8 h-8 text-red-600 bg-red-100 rounded-lg hover:bg-red-200 transition-colors"
                            title="Supprimer">
                        <i class="fas fa-trash text-sm"></i>
                    </button>
                @endif
            @endcan
        </div>
    @endif

    @if(!$compact && ($culte->note_globale || $culte->offrande_totale))
        <!-- Indicateurs supplémentaires -->
        <div class="mt-4 pt-4 border-t border-slate-200">
            <div class="flex items-center justify-between text-sm">
                @if($culte->note_globale)
                    <div class="flex items-center text-amber-600">
                        <i class="fas fa-star mr-1"></i>
                        <span>{{ $culte->note_globale }}/10</span>
                    </div>
                @endif

                @if($culte->offrande_totale)
                    <div class="flex items-center text-green-600">
                        <i class="fas fa-hand-holding-heart mr-1"></i>
                        <span>{{ number_format($culte->offrande_totale, 0) }}€</span>
                    </div>
                @endif
            </div>
        </div>
    @endif

    @if(!$compact && $culte->description)
        <!-- Description (tronquée) -->
        <div class="mt-3 pt-3 border-t border-slate-200">
            <p class="text-xs text-slate-600 line-clamp-2">{{ $culte->description }}</p>
        </div>
    @endif
</div>

@push('styles')
<style>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* Animation de hover améliorée */
.hover\:-translate-y-1:hover {
    transform: translateY(-4px);
    box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
}

/* Amélioration de l'accessibilité */
.cursor-pointer:focus-within {
    outline: 2px solid #3b82f6;
    outline-offset: 2px;
}

/* Responsive */
@media (max-width: 640px) {
    .flex.items-center.space-x-2 {
        flex-wrap: wrap;
        gap: 0.25rem;
    }

    .w-8.h-8 {
        width: 1.75rem;
        height: 1.75rem;
    }
}
</style>
@endpush
