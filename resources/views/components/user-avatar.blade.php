@props([
    'user',
    'size' => 'md',
    'showStatus' => false,
    'showName' => false,
    'clickable' => false,
    'class' => ''
])

@php
    $sizeClasses = [
        'xs' => 'h-6 w-6 text-xs',
        'sm' => 'h-8 w-8 text-sm',
        'md' => 'h-10 w-10 text-sm',
        'lg' => 'h-12 w-12 text-base',
        'xl' => 'h-16 w-16 text-lg',
        '2xl' => 'h-20 w-20 text-xl',
        '3xl' => 'h-24 w-24 text-2xl'
    ];

    $avatarSize = $sizeClasses[$size] ?? $sizeClasses['md'];
    $initials = '';

    if ($user) {
        $prenom = $user->prenom ?? '';
        $nom = $user->nom ?? '';
        $initials = substr($prenom, 0, 1) . substr($nom, 0, 1);
    }

    $containerClass = $clickable ? 'cursor-pointer group' : '';
    $component = $clickable ? 'a' : 'div';
    $href = $clickable && $user ? route('private.users.show', $user) : '#';
@endphp

<{{ $component }}
    @if($clickable && $user) href="{{ $href }}" @endif
    class="flex items-center {{ $containerClass }} {{ $class }}"
>
    <div class="relative flex-shrink-0">
        @if($user && $user->photo_profil)
            {{-- Photo de profil existante --}}
            <img
                class="{{ $avatarSize }} rounded-full object-cover ring-2 ring-white shadow-sm @if($clickable) group-hover:ring-indigo-500 transition-all duration-200 @endif"
                src="{{ Storage::url($user->photo_profil) }}"
                alt="{{ $user->nom_complet ?? 'Photo de profil' }}"
                loading="lazy"
            >
        @elseif($user)
            {{-- Avatar avec initiales --}}
            <div class="{{ $avatarSize }} rounded-full flex items-center justify-center font-medium shadow-sm ring-2 ring-white
                @if($user->sexe === 'feminin')
                    bg-gradient-to-br from-pink-400 to-purple-500 text-white
                @else
                    bg-gradient-to-br from-blue-400 to-indigo-500 text-white
                @endif
                @if($clickable) group-hover:ring-indigo-500 group-hover:scale-105 transition-all duration-200 @endif
            ">
                {{ strtoupper($initials) }}
            </div>
        @else
            {{-- Avatar par défaut (membres non défini) --}}
            <div class="{{ $avatarSize }} rounded-full flex items-center justify-center bg-gray-300 text-gray-600 font-medium shadow-sm ring-2 ring-white">
                <i class="fas fa-user"></i>
            </div>
        @endif

        {{-- Indicateur de statut --}}
        @if($showStatus && $user)
            <span class="absolute bottom-0 right-0 block
                @if($size === 'xs' || $size === 'sm') h-2 w-2 @else h-3 w-3 @endif
                rounded-full ring-2 ring-white
                @if($user->actif)
                    bg-green-400
                @else
                    bg-gray-400
                @endif
            "></span>
        @endif
    </div>

    {{-- Nom et informations --}}
    @if($showName && $user)
        <div class="ml-3 min-w-0 flex-1">
            <p class="text-sm font-medium text-gray-900 truncate @if($clickable) group-hover:text-indigo-600 transition-colors duration-200 @endif">
                {{ $user->nom_complet }}
            </p>
            @if($user->email)
                <p class="text-sm text-gray-500 truncate">
                    {{ $user->email }}
                </p>
            @endif

            {{-- Badges de statut --}}
            <div class="flex items-center space-x-1 mt-1">
                @if($user->roles->count() > 0)
                    @foreach($user->roles->take(2) as $role)
                        <span class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium bg-indigo-100 text-indigo-800">
                            {{ $role->name }}
                        </span>
                    @endforeach
                    @if($user->roles->count() > 2)
                        <span class="text-xs text-gray-500">+{{ $user->roles->count() - 2 }}</span>
                    @endif
                @endif
            </div>
        </div>
    @endif

    {{-- Icône de lien externe si clickable --}}
    @if($clickable && $showName)
        <div class="flex-shrink-0">
            <i class="fas fa-external-link-alt text-gray-400 group-hover:text-indigo-500 transition-colors duration-200"></i>
        </div>
    @endif
</{{ $component }}>

{{-- Exemples d'utilisation :

{{-- Avatar simple --}}
<x-user-avatar :user="$user" />

{{-- Avatar avec statut --}}
<x-user-avatar :user="$user" :showStatus="true" />

{{-- Avatar avec nom et cliquable --}}
<x-user-avatar :user="$user" :showName="true" :clickable="true" />

{{-- Avatar grande taille --}}
<x-user-avatar :user="$user" size="xl" :showStatus="true" />

{{-- Avatar avec classe personnalisée --}}
<x-user-avatar :user="$user" size="lg" class="mx-auto" />

{{-- Dans une liste --}}
@foreach($users as $user)
    <div class="flex items-center space-x-3 p-3 hover:bg-gray-50 rounded-lg">
        <x-user-avatar :user="$user" :showStatus="true" />
        <div class="flex-1 min-w-0">
            <p class="text-sm font-medium text-gray-900 truncate">{{ $user->nom_complet }}</p>
            <p class="text-sm text-gray-500 truncate">{{ $user->email }}</p>
        </div>
        <div class="flex-shrink-0">
            <a href="{{ route('private.users.show', $user) }}" class="text-indigo-600 hover:text-indigo-500">
                <i class="fas fa-arrow-right"></i>
            </a>
        </div>
    </div>
@endforeach


