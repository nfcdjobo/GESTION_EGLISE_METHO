@extends('errors.generic', [
    'statusCode' => [CODE],
    'title' => '[TITRE]',
    'color' => '[COULEUR: red|orange|yellow|blue|purple]',
    'icon' => '[ICONE: fa-xxx]',
    'message' => '[MESSAGE COURT]',
    'description' => '[DESCRIPTION DÉTAILLÉE]',
    // Options selon le besoin :
    'contact_admin' => true,
    'refresh_required' => true,
    'retry_after' => 60,
    'support_info' => 'support@example.com'
])

@section('content')
    @parent

    // Sections spécialisées si nécessaire
    @push('specific_sections')
        <div class="bg-gradient-to-r from-[color]-50 to-[color]-50 rounded-xl p-6 mb-8 border border-[color]-200">
            // Contenu spécialisé
        </div>
    @endpush

    // Boutons supplémentaires si nécessaire
    @push('additional_buttons')
        <a href="#" class="inline-flex items-center justify-center px-8 py-4 bg-gradient-to-r from-[color]-600 to-[color]-600 text-white font-semibold text-lg rounded-xl">
            <i class="fas fa-[icon] mr-3"></i>
            [Texte du bouton]
        </a>
    @endpush
@endsection

