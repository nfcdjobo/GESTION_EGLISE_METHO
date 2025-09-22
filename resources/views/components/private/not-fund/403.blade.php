@extends('layouts.private.main')
@section('title', 'Accès Non Autorisé')

@section('content')
<div class="space-y-8 flex items-center justify-center bg-gradient-to-br from-slate-50 to-slate-100 p-4">
    <div class="max-w-3xl w-full">
        <!-- Carte principale d'erreur -->
        <div class="bg-white/80 rounded-2xl shadow-xl border border-white/20 hover:shadow-2xl transition-all duration-300 overflow-hidden">
            <!-- En-tête avec dégradé -->
            <div class="bg-gradient-to-r from-red-500 to-pink-500 p-8 text-center relative overflow-hidden">
                <!-- Éléments décoratifs -->
                <div class="absolute inset-0 opacity-10">
                    <div class="absolute top-4 left-4 w-8 h-8 border-2 border-white rounded-full"></div>
                    <div class="absolute bottom-6 right-8 w-6 h-6 border-2 border-white rounded-lg rotate-45"></div>
                    <div class="absolute top-1/2 right-4 w-4 h-4 bg-white rounded-full"></div>
                </div>

                <h1 class="text-4xl font-bold text-white mb-2">403</h1>
                <p class="text-red-100 text-xl">Accès Non Autorisé</p>
            </div>

            <!-- Contenu principal -->
            <div class="p-8">
                <!-- Message d'erreur avec style moderne -->
                <div class="text-center mb-8">
                    <div class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-red-100 to-orange-100 text-red-800 rounded-xl mb-6 shadow-md">
                        <i class="fas fa-shield-alt mr-3 text-xl"></i>
                        <span class="font-semibold text-lg">Accès Refusé</span>
                    </div>
                    <h2 class="text-3xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent mb-4">
                        Oops ! Vous n'êtes pas autorisé
                    </h2>
                </div>

                <!-- Boutons d'action principaux -->
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ route('private.dashboard') ?? '/' }}"
                       class="inline-flex items-center justify-center px-8 py-4 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-semibold text-lg rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow-lg hover:shadow-xl hover:-translate-y-1">
                        <i class="fas fa-home mr-3"></i>
                        Retour à l'accueil
                    </a>

                    <button onclick="history.back()"
                            class="inline-flex items-center justify-center px-8 py-4 bg-gradient-to-r from-slate-600 to-slate-700 text-white font-semibold text-lg rounded-xl hover:from-slate-700 hover:to-slate-800 transition-all duration-200 shadow-lg hover:shadow-xl hover:-translate-y-1">
                        <i class="fas fa-arrow-left mr-3"></i>
                        Page précédente
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Animations personnalisées */
@keyframes bounce-slow {
    0%, 20%, 50%, 80%, 100% {
        transform: translateY(0);
    }
    40% {
        transform: translateY(-10px);
    }
    60% {
        transform: translateY(-5px);
    }
}

.animate-bounce-slow {
    animation: bounce-slow 3s infinite;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Animation d'entrée en cascade
    const cards = document.querySelectorAll('[class*="hover:shadow-lg"]');
    cards.forEach((card, index) => {
        card.style.opacity = '0';

        setTimeout(() => {
            card.style.transition = 'all 0.5s ease-out';
            card.style.opacity = '1';
        }, index * 100);
    });

    // Effet parallaxe léger pour les éléments décoratifs
    window.addEventListener('mousemove', function(e) {
        const decorativeElements = document.querySelectorAll('.absolute');
        const mouseX = e.clientX / window.innerWidth;
        const mouseY = e.clientY / window.innerHeight;

        decorativeElements.forEach((element, index) => {
            const speed = (index + 1) * 0.5;
            const x = (mouseX - 0.5) * speed;
            const y = (mouseY - 0.5) * speed;

            element.style.transform = `translate(${x}px, ${y}px)`;
        });
    });
});
</script>

@endsection
