@extends('layouts.private.main')
@section('title', 'Détails de l\'Intervention')

@section('content')
    <div class="space-y-8">
        <!-- Page Title & Breadcrumb -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent">
                {{ $intervention->titre }}</h1>
            <nav class="flex mt-2" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="{{ route('private.interventions.index') }}"
                            class="inline-flex items-center text-sm font-medium text-slate-700 hover:text-blue-600 transition-colors">
                            <i class="fas fa-microphone mr-2"></i>
                            Interventions
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <i class="fas fa-chevron-right text-slate-400 mx-2"></i>
                            <span class="text-sm font-medium text-slate-500">Détails</span>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>

        <!-- Actions rapides -->
        <div class="flex flex-wrap gap-3 mb-6">
            @can('interventions.update')
                <a href="{{ route('private.interventions.edit', $intervention) }}"
                    class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-yellow-600 to-orange-600 text-white text-sm font-medium rounded-xl hover:from-yellow-700 hover:to-orange-700 transition-all duration-200 shadow-md hover:shadow-lg">
                    <i class="fas fa-edit mr-2"></i> Modifier
                </a>
            @endcan
            @can('interventions.change-status')
                <form method="POST" action="{{ route('private.interventions.change-statut', $intervention) }}"
                    class="inline-block">
                    @csrf
                    @method('PATCH')
                    <select name="statut" onchange="this.form.submit()"
                        class="px-4 py-2 bg-gradient-to-r from-blue-600 to-purple-600 text-white border-0 rounded-xl text-sm font-medium hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow-md hover:shadow-lg">
                        @foreach (\App\Models\Intervention::STATUTS as $key => $label)
                            <option value="{{ $key }}" {{ $intervention->statut == $key ? 'selected' : '' }}
                                class="bg-white text-slate-900">
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </form>
            @endcan
            @can('interventions.delete')
                <button type="button" onclick="deleteIntervention()"
                    class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-red-600 to-pink-600 text-white text-sm font-medium rounded-xl hover:from-red-700 hover:to-pink-700 transition-all duration-200 shadow-md hover:shadow-lg">
                    <i class="fas fa-trash mr-2"></i> Supprimer
                </button>
            @endcan
            <a href="{{ route('private.interventions.index') }}"
                class="inline-flex items-center px-4 py-2 bg-slate-600 text-white text-sm font-medium rounded-xl hover:bg-slate-700 transition-colors">
                <i class="fas fa-arrow-left mr-2"></i> Retour
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Informations principales -->
            <div class="lg:col-span-2 space-y-6">
                <!-- En-tête de l'intervention -->
                <div
                    class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                    <div class="p-6">
                        <div class="flex items-start justify-between mb-6">
                            <div class="flex-1">
                                <h2 class="text-2xl font-bold text-slate-800 mb-2">{{ $intervention->titre }}</h2>
                                <div class="flex flex-wrap items-center gap-3 mb-4">
                                    <span
                                        class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                        <i class="fas fa-tag mr-2"></i>
                                        {{ $intervention->type_intervention_label }}
                                    </span>
                                    @if ($intervention->statut == 'prevue')
                                        <span
                                            class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                                            <i class="fas fa-clock mr-2"></i> {{ $intervention->statut_label }}
                                        </span>
                                    @elseif($intervention->statut == 'terminee')
                                        <span
                                            class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                            <i class="fas fa-check mr-2"></i> {{ $intervention->statut_label }}
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                            <i class="fas fa-times mr-2"></i> {{ $intervention->statut_label }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        @if ($intervention->description)
                            <div class="mb-6">
                                <h3 class="text-lg font-semibold text-slate-800 mb-3 flex items-center">
                                    <i class="fas fa-align-left text-blue-600 mr-2"></i>
                                    Description
                                </h3>
                                <div class="bg-slate-50 rounded-xl p-4 border border-slate-200">
                                    <p class="text-slate-700 leading-relaxed">{{ $intervention->description }}</p>
                                </div>
                            </div>
                        @endif

                        @if ($intervention->passage_biblique)
                            <div class="mb-6">
                                <h3 class="text-lg font-semibold text-slate-800 mb-3 flex items-center">
                                    <i class="fas fa-book-open text-purple-600 mr-2"></i>
                                    Passage Biblique
                                </h3>
                                <div
                                    class="bg-gradient-to-r from-purple-50 to-blue-50 rounded-xl p-4 border border-purple-200">
                                    <p class="text-purple-800 font-medium text-lg">{{ $intervention->passage_biblique }}
                                    </p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Détails du planning -->
                <div
                    class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                    <div class="p-6 border-b border-slate-200">
                        <h2 class="text-xl font-bold text-slate-800 flex items-center">
                            <i class="fas fa-calendar-alt text-green-600 mr-2"></i>
                            Planning et Timing
                        </h2>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                            @if ($intervention->ordre_passage)
                                <div class="text-center">
                                    <div
                                        class="w-16 h-16 bg-gradient-to-r from-blue-500 to-cyan-500 rounded-full flex items-center justify-center mx-auto mb-2">
                                        <i class="fas fa-sort-numeric-up text-white text-xl"></i>
                                    </div>
                                    <div class="text-2xl font-bold text-slate-800">{{ $intervention->ordre_passage }}</div>
                                    <div class="text-sm text-slate-500">Ordre de passage</div>
                                </div>
                            @endif

                            @if ($intervention->heure_debut)
                                <div class="text-center">
                                    <div
                                        class="w-16 h-16 bg-gradient-to-r from-green-500 to-emerald-500 rounded-full flex items-center justify-center mx-auto mb-2">
                                        <i class="fas fa-clock text-white text-xl"></i>
                                    </div>
                                    <div class="text-2xl font-bold text-slate-800">
                                        {{ $intervention->heure_debut->format('H:i') }}</div>
                                    <div class="text-sm text-slate-500">Heure de début</div>
                                </div>
                            @endif

                            <div class="text-center">
                                <div
                                    class="w-16 h-16 bg-gradient-to-r from-yellow-500 to-orange-500 rounded-full flex items-center justify-center mx-auto mb-2">
                                    <i class="fas fa-stopwatch text-white text-xl"></i>
                                </div>
                                <div class="text-2xl font-bold text-slate-800">{{ $intervention->duree_minutes }}</div>
                                <div class="text-sm text-slate-500">Minutes</div>
                            </div>

                            @if ($intervention->heure_debut && $intervention->heure_fin)
                                <div class="text-center">
                                    <div
                                        class="w-16 h-16 bg-gradient-to-r from-purple-500 to-pink-500 rounded-full flex items-center justify-center mx-auto mb-2">
                                        <i class="fas fa-flag-checkered text-white text-xl"></i>
                                    </div>
                                    <div class="text-2xl font-bold text-slate-800">
                                        {{ $intervention->heure_fin->format('H:i') }}</div>
                                    <div class="text-sm text-slate-500">Heure de fin</div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Événement associé -->
                <div
                    class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                    <div class="p-6 border-b border-slate-200">
                        <h2 class="text-xl font-bold text-slate-800 flex items-center">
                            <i class="fas fa-calendar-check text-indigo-600 mr-2"></i>
                            Événement Associé
                        </h2>
                    </div>
                    <div class="p-6">
                        @if ($intervention->culte)
                            <div
                                class="flex items-center p-4 bg-gradient-to-r from-blue-50 to-cyan-50 rounded-xl border border-blue-200">
                                <div class="flex-shrink-0">
                                    <div
                                        class="w-12 h-12 bg-gradient-to-r from-blue-500 to-cyan-500 rounded-full flex items-center justify-center">
                                        <i class="fas fa-church text-white text-xl"></i>
                                    </div>
                                </div>
                                <div class="ml-4 flex-1">
                                    <h3 class="text-lg font-semibold text-blue-800">{{ $intervention->culte->nom }}</h3>
                                    @if ($intervention->culte->date_culte)
                                        <p class="text-blue-600">{{ $intervention->culte->date_culte->format('l d F Y') }}
                                        </p>
                                    @endif
                                    @if ($intervention->culte->description)
                                        <p class="text-blue-700 text-sm mt-1">
                                            {{-- {{ Str::limit($intervention->culte->description, 100) }} --}}
                                            {{ Str::limit(strip_tags($intervention->culte->description), 100) }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                        @elseif($intervention->reunion)
                            <div
                                class="flex items-center p-4 bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl border border-green-200">
                                <div class="flex-shrink-0">
                                    <div
                                        class="w-12 h-12 bg-gradient-to-r from-green-500 to-emerald-500 rounded-full flex items-center justify-center">
                                        <i class="fas fa-users text-white text-xl"></i>
                                    </div>
                                </div>
                                <div class="ml-4 flex-1">
                                    <h3 class="text-lg font-semibold text-green-800">{{ $intervention->reunion->nom }}
                                    </h3>
                                    @if ($intervention->reunion->date_reunion)
                                        <p class="text-green-600">
                                            {{ $intervention->reunion->date_reunion->format('l d F Y') }}</p>
                                    @endif
                                    @if ($intervention->reunion->description)
                                        <p class="text-green-700 text-sm mt-1">
                                            {{-- {{ Str::limit($intervention->reunion->description, 100) }} --}}
                                            {{ Str::limit(strip_tags($intervention->reunion->description), 100) }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Intervenant -->
                <div
                    class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                    <div class="p-6 border-b border-slate-200">
                        <h2 class="text-xl font-bold text-slate-800 flex items-center">
                            <i class="fas fa-user text-purple-600 mr-2"></i>
                            Intervenant
                        </h2>
                    </div>
                    <div class="p-6">
                        <div class="flex items-center space-x-4">
                            <div
                                class="w-16 h-16 bg-gradient-to-r from-purple-500 to-pink-500 rounded-full flex items-center justify-center text-white text-2xl font-bold">
                                {{ strtoupper(substr($intervention->intervenant->nom, 0, 1)) }}
                            </div>
                            <div class="flex-1">
                                <h3 class="text-lg font-semibold text-slate-800">{{ $intervention->intervenant->nom }}
                                </h3>
                                <p class="text-slate-600">{{ $intervention->intervenant->telephone_1 }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Informations système -->
                <div
                    class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                    <div class="p-6 border-b border-slate-200">
                        <h2 class="text-xl font-bold text-slate-800 flex items-center">
                            <i class="fas fa-cog text-gray-600 mr-2"></i>
                            Informations Système
                        </h2>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-medium text-slate-700">ID:</span>
                            <code
                                class="px-2 py-1 text-xs bg-slate-100 text-slate-800 rounded">{{ Str::limit($intervention->id, 8) }}</code>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-medium text-slate-700">Créée le:</span>
                            <span
                                class="text-sm text-slate-600">{{ $intervention->created_at->format('d/m/Y H:i') }}</span>
                        </div>
                        @if ($intervention->updated_at != $intervention->created_at)
                            <div class="flex justify-between items-center">
                                <span class="text-sm font-medium text-slate-700">Modifiée le:</span>
                                <span
                                    class="text-sm text-slate-600">{{ $intervention->updated_at->format('d/m/Y H:i') }}</span>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Actions rapides -->
                <div
                    class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                    <div class="p-6 border-b border-slate-200">
                        <h2 class="text-xl font-bold text-slate-800 flex items-center">
                            <i class="fas fa-bolt text-amber-600 mr-2"></i>
                            Actions Rapides
                        </h2>
                    </div>
                    <div class="p-6 space-y-3">
                        @can('interventions.update')
                            <a href="{{ route('private.interventions.edit', $intervention) }}"
                                class="w-full inline-flex items-center justify-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-xl hover:bg-blue-700 transition-colors">
                                <i class="fas fa-edit mr-2"></i> Modifier
                            </a>
                        @endcan

                        @can('interventions.by-event')
                            @if ($intervention->culte)
                                <a href="{{ route('private.interventions.par-evenement', ['culte_id' => $intervention->culte->id]) }}"
                                    class="w-full inline-flex items-center justify-center px-4 py-2 bg-cyan-600 text-white text-sm font-medium rounded-xl hover:bg-cyan-700 transition-colors">
                                    <i class="fas fa-church mr-2"></i> Autres interventions du culte
                                </a>
                            @endif

                            @if ($intervention->reunion)
                                <a href="{{ route('private.interventions.par-evenement', ['reunion_id' => $intervention->reunion->id]) }}"
                                    class="w-full inline-flex items-center justify-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-xl hover:bg-green-700 transition-colors">
                                    <i class="fas fa-users mr-2"></i> Autres interventions de la réunion
                                </a>
                            @endif
                        @endcan

                        <a href="{{ route('private.interventions.index', ['intervenant_id' => $intervention->intervenant->id]) }}"
                            class="w-full inline-flex items-center justify-center px-4 py-2 bg-purple-600 text-white text-sm font-medium rounded-xl hover:bg-purple-700 transition-colors">
                            <i class="fas fa-user mr-2"></i> Interventions de {{ $intervention->intervenant->nom }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @can('interventions.delete')
    <!-- Modal de confirmation de suppression -->
    <div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-xl max-w-md w-full">
            <div class="p-6">
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center mr-4">
                        <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-slate-900">Confirmer la suppression</h3>
                </div>
                <p class="text-slate-600 mb-2">Êtes-vous sûr de vouloir supprimer cette intervention ?</p>
                <p class="text-red-600 font-medium">Cette action peut être annulée depuis la corbeille.</p>
            </div>
            <div class="flex items-center justify-end space-x-3 p-6 border-t border-slate-200">
                <button type="button" onclick="closeDeleteModal()"
                    class="px-4 py-2 text-slate-700 bg-slate-100 rounded-xl hover:bg-slate-200 transition-colors">
                    Annuler
                </button>
                <button type="button" onclick="confirmDelete()"
                    class="px-4 py-2 bg-red-600 text-white rounded-xl hover:bg-red-700 transition-colors">
                    Supprimer
                </button>
            </div>
        </div>
    </div>
    @endcan

    <script>
        // Modal functions
        function showDeleteModal() {
            document.getElementById('deleteModal').classList.remove('hidden');
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.add('hidden');
        }

        function deleteIntervention() {
            showDeleteModal();
        }

        function confirmDelete() {
            fetch(`{{ route('private.interventions.destroy', $intervention) }}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    closeDeleteModal();
                    if (data.success) {
                        window.location.href = '{{ route('private.interventions.index') }}';
                    } else {
                        alert(data.message);
                    }
                })
                .catch(error => {
                    console.error('Erreur:', error);
                    alert('Une erreur est survenue');
                    closeDeleteModal();
                });
        }

        // Close modal when clicking outside
        document.getElementById('deleteModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeDeleteModal();
            }
        });
    </script>

@endsection
