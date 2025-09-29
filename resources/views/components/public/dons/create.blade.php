@extends('layouts.public.main')
@section('title', 'Soumettre preuve de paiement - Dons')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
/* ===== SECTION PRINCIPALE ===== */
.donation-section {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 8rem 0 1rem 0;
    margin-bottom: 0;
    min-height: 100vh;
}

.donation-hero {
    text-align: center;
    margin-bottom: 1rem;
}

.donation-hero h1 {
    font-size: 1.8rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
    text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
}

.donation-hero p {
    font-size: 0.95rem;
    opacity: 0.9;
    margin: 0 auto;
}

/* ===== LAYOUT PRINCIPAL ===== */
.main-layout {
    display: grid;
    grid-template-columns: 1fr 2fr;
    gap: 1.5rem;
    max-width: 1400px;
    margin: 0 auto;
    padding: 0 1rem;
}

/* ===== RÉSUMÉ PAIEMENT ===== */
.payment-summary {
    background: white;
    border-radius: 12px;
    padding: 1.5rem;
    height: fit-content;
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    position: sticky;
    top: 1rem;
}

.payment-summary h3 {
    color: #1f2937;
    margin-bottom: 1rem;
    font-size: 1.1rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.payment-method-card {
    display: flex;
    align-items: center;
    background: #f8fafc;
    padding: 1rem;
    border-radius: 10px;
    margin-bottom: 1rem;
}

.payment-method-icon {
    width: 45px;
    height: 45px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 1rem;
    color: white;
    font-size: 1.1rem;
}

.mobile-money {
    background: linear-gradient(135deg, #ff6b35, #f7931e);
}

.carte-bancaire {
    background: linear-gradient(135deg, #4facfe, #00f2fe);
}

.virement-bancaire {
    background: linear-gradient(135deg, #43e97b, #38f9d7);
}

.payment-method-info h4 {
    margin: 0 0 0.25rem 0;
    color: #1f2937;
    font-size: 1rem;
    font-weight: 600;
}

.payment-method-info p {
    margin: 0;
    color: #6b7280;
    font-size: 0.85rem;
}

.account-display {
    background: white;
    padding: 0.75rem;
    border-radius: 6px;
    font-family: 'JetBrains Mono', 'Courier New', monospace;
    font-weight: 600;
    color: #374151;
    margin-top: 0.5rem;
    border: 2px solid #e5e7eb;
    word-break: break-all;
}

/* ===== CONTAINER FORMULAIRE ===== */
.form-container {
    background: white;
    border-radius: 12px;
    padding: 1.5rem;
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    height: fit-content;
}

.form-sections-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 2rem;
    margin-bottom: 1.5rem;
}

/* ===== SECTIONS FORMULAIRE ===== */
.form-section {
    margin-bottom: 0;
}

.section-title {
    color: #1f2937;
    font-size: 1rem;
    font-weight: 600;
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding-bottom: 0.5rem;
    border-bottom: 2px solid #f3f4f6;
}

.compact-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
    margin-bottom: 1rem;
}

/* ===== CHAMPS FORMULAIRE ===== */
.form-group {
    margin-bottom: 1rem;
}

.form-label {
    display: block;
    margin-bottom: 0.4rem;
    color: #374151;
    font-weight: 500;
    font-size: 0.85rem;
}

.form-label .required {
    color: #ef4444;
    margin-left: 2px;
}

.form-control {
    width: 100%;
    padding: 0.6rem 0.8rem;
    border: 2px solid #e5e7eb;
    border-radius: 6px;
    font-size: 0.85rem;
    transition: all 0.3s ease;
    background: white;
    box-sizing: border-box;
}

.form-control:focus {
    outline: none;
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.form-control.is-invalid {
    border-color: #ef4444;
    background-color: #fef2f2;
}

.invalid-feedback {
    color: #ef4444;
    font-size: 0.75rem;
    margin-top: 0.25rem;
    display: block;
}

/* ===== UPLOAD FICHIER ===== */
.file-upload-section {
    grid-column: 1 / -1;
    margin-top: 1rem;
}

.file-upload-area {
    border: 2px dashed #cbd5e1;
    border-radius: 8px;
    padding: 1.5rem;
    text-align: center;
    background: #f8fafc;
    transition: all 0.3s ease;
    cursor: pointer;
    position: relative;
}

.file-upload-area:hover {
    border-color: #667eea;
    background: #f0f4ff;
}

.file-upload-icon {
    font-size: 2rem;
    color: #cbd5e1;
    margin-bottom: 0.5rem;
}

.file-upload-text {
    color: #6b7280;
    margin-bottom: 0.25rem;
    font-size: 0.9rem;
}

.file-upload-hint {
    color: #9ca3af;
    font-size: 0.75rem;
}

.file-input {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    opacity: 0;
    cursor: pointer;
}

.file-preview {
    margin-top: 1rem;
    padding: 0.75rem;
    background: #f9fafb;
    border-radius: 6px;
    border: 1px solid #e5e7eb;
    display: none;
}

.file-preview.show {
    display: block;
}

.file-info {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.file-icon {
    width: 35px;
    height: 35px;
    background: #667eea;
    color: white;
    border-radius: 4px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.file-details h4 {
    margin: 0 0 0.25rem 0;
    color: #374151;
    font-size: 0.85rem;
}

.file-details p {
    margin: 0;
    color: #6b7280;
    font-size: 0.75rem;
}

/* ===== BOUTONS ===== */
.form-actions {
    display: flex;
    gap: 1rem;
    justify-content: center;
    margin-top: 1.5rem;
    grid-column: 1 / -1;
}

.btn-primary {
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
    border: none;
    padding: 0.75rem 1.5rem;
    border-radius: 8px;
    font-weight: 600;
    font-size: 0.9rem;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
}

.btn-secondary {
    background: #f3f4f6;
    color: #374151;
    border: 1px solid #d1d5db;
    padding: 0.75rem 1.5rem;
    border-radius: 8px;
    font-weight: 500;
    font-size: 0.9rem;
    cursor: pointer;
    transition: all 0.3s ease;
    text-decoration: none;
    display: inline-block;
}

.btn-secondary:hover {
    background: #e5e7eb;
    text-decoration: none;
}

/* ===== ALERTES ===== */
.alert {
    padding: 0.75rem;
    border-radius: 6px;
    margin-bottom: 1rem;
    font-size: 0.85rem;
}

.alert-success {
    background: #f0fdf4;
    color: #166534;
    border: 1px solid #bbf7d0;
}

.alert-danger {
    background: #fef2f2;
    color: #dc2626;
    border: 1px solid #fecaca;
}

/* ===== RESPONSIVE ===== */
@media (max-width: 1024px) {
    .main-layout {
        grid-template-columns: 1fr;
        gap: 1rem;
    }

    .payment-summary {
        position: static;
    }

    .form-sections-grid {
        grid-template-columns: 1fr;
        gap: 1.5rem;
    }
}

@media (max-width: 768px) {
    .donation-hero h1 {
        font-size: 1.5rem;
    }

    .compact-row {
        grid-template-columns: 1fr;
    }

    .main-layout {
        padding: 0 0.5rem;
    }

    .form-actions {
        flex-direction: column;
    }
}
</style>
@endpush

@section('content')
<section class="donation-section">
    <div class="container">
        <div class="donation-hero">
            <h1>Finaliser votre Don</h1>
            <p>Remplissez ce formulaire pour soumettre votre preuve de paiement</p>
        </div>

        <div class="main-layout">
            <!-- Résumé du paiement -->
            <div class="payment-summary">
                <h3><i class="fas fa-credit-card"></i>Méthode sélectionnée</h3>
                <div class="payment-method-card">
                    <div class="payment-method-icon {{ str_replace('_', '-', $parametreDon->type) }}">
                        @if($parametreDon->logo)
                            {{-- Afficher le logo si disponible --}}
                            <img src="{{ Storage::url($parametreDon->logo) }}"
                                alt="Logo {{ $parametreDon->operateur }}"
                                style="width: 100%; height: 100%; object-fit: contain; border-radius: 5px;">
                        @else
                            {{-- Icône par défaut si pas de logo --}}
                            @switch($parametreDon->type)
                                @case('mobile_money')
                                    <i class="fas fa-mobile-alt"></i>
                                    @break
                                @case('carte_bancaire')
                                    <i class="fas fa-credit-card"></i>
                                    @break
                                @case('virement_bancaire')
                                    <i class="fas fa-university"></i>
                                    @break
                                @default
                                    <i class="fas fa-money-bill-wave"></i>
                            @endswitch
                        @endif
                    </div>
                    <div class="payment-method-info">
                        <h4>{{ $parametreDon->type_libelle }}</h4>
                        <p>{{ $parametreDon->operateur }}</p>
                    </div>
                </div>
                <div class="account-display">{{ $parametreDon->numero_compte }}</div>

                @if($parametreDon->qrcode)
                    <div style="text-align: center; margin-top: 1rem;">
                        <p style="font-size: 0.8rem; color: #6b7280; margin-bottom: 0.5rem;">
                            <i class="fas fa-qrcode"></i> Scanner pour payer
                        </p>
                        <img src="{{ asset('storage/' . $parametreDon->qrcode) }}"
                             alt="QR Code" style="max-width: 150px; border-radius: 6px;">
                    </div>
                @endif
            </div>

            <!-- Formulaire -->
            <div class="form-container">
                @if(session('success'))
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle" style="margin-right: 0.5rem;"></i>
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-triangle" style="margin-right: 0.5rem;"></i>
                        {{ session('error') }}
                    </div>
                @endif

                <form action="{{ route('public.donates.store') }}" method="POST" enctype="multipart/form-data" id="donationForm">
                    @csrf
                    <input type="hidden" name="parametre_fond_id" value="{{ $parametreDon->id }}">

                    <div class="form-sections-grid">
                        <!-- Informations personnelles -->
                        <div class="form-section">
                            <h3 class="section-title">
                                <i class="fas fa-user"></i>
                                Informations personnelles
                            </h3>

                            <div class="compact-row">
                                <div class="form-group">
                                    <label class="form-label" for="nom_donateur">
                                        Nom <span class="required">*</span>
                                    </label>
                                    <input type="text"
                                           class="form-control @error('nom_donateur') is-invalid @enderror"
                                           id="nom_donateur"
                                           name="nom_donateur"
                                           value="{{ old('nom_donateur') }}"
                                           required>
                                    @error('nom_donateur')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label class="form-label" for="prenom_donateur">
                                        Prénom <span class="required">*</span>
                                    </label>
                                    <input type="text"
                                           class="form-control @error('prenom_donateur') is-invalid @enderror"
                                           id="prenom_donateur"
                                           name="prenom_donateur"
                                           value="{{ old('prenom_donateur') }}"
                                           required>
                                    @error('prenom_donateur')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="compact-row">
                                <div class="form-group">
                                    <label class="form-label" for="telephone_1">
                                        Téléphone principal <span class="required">*</span>
                                    </label>
                                    <input type="tel"
                                           class="form-control @error('telephone_1') is-invalid @enderror"
                                           id="telephone_1"
                                           name="telephone_1"
                                           value="{{ old('telephone_1') }}"
                                           required>
                                    @error('telephone_1')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label class="form-label" for="telephone_2">
                                        Téléphone secondaire
                                    </label>
                                    <input type="tel"
                                           class="form-control @error('telephone_2') is-invalid @enderror"
                                           id="telephone_2"
                                           name="telephone_2"
                                           value="{{ old('telephone_2') }}">
                                    @error('telephone_2')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Informations du don -->
                        <div class="form-section">
                            <h3 class="section-title">
                                <i class="fas fa-donate"></i>
                                Informations du don
                            </h3>

                            <div class="compact-row">
                                <div class="form-group">
                                    <label class="form-label" for="montant">
                                        Montant <span class="required">*</span>
                                    </label>
                                    <input type="number"
                                           class="form-control @error('montant') is-invalid @enderror"
                                           id="montant"
                                           name="montant"
                                           value="{{ old('montant') }}"
                                           min="0.01"
                                           step="0.01"
                                           required>
                                    @error('montant')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label class="form-label" for="devise">
                                        Devise <span class="required">*</span>
                                    </label>
                                    <select class="form-control @error('devise') is-invalid @enderror"
                                            id="devise"
                                            name="devise"
                                            required>
                                        <option value="">Choisir devise</option>
                                        <option value="XOF" {{ old('devise') == 'XOF' ? 'selected' : '' }}>CFA (XOF)</option>
                                        <option value="EUR" {{ old('devise') == 'EUR' ? 'selected' : '' }} disabled>Euro (EUR)</option>
                                        <option value="USD" {{ old('devise') == 'USD' ? 'selected' : '' }} disabled>Dollar US (USD)</option>
                                    </select>
                                    @error('devise')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>



                    <!-- Section upload de fichier mise à jour -->
                    <div class="file-upload-section">
                        <h3 class="section-title">
                            <i class="fas fa-receipt"></i>
                            Preuve de paiement
                        </h3>

                        <div class="form-group">
                            <div class="file-upload-area">
                                <div class="file-upload-icon">
                                    <i class="fas fa-cloud-upload-alt"></i>
                                </div>
                                <div class="file-upload-text">
                                    Sélectionner votre justificatif
                                </div>
                                <div class="file-upload-hint">
                                    JPG, JPEG, PNG uniquement (Max: 5MB)
                                </div>
                                <input type="file"
                                    class="file-input @error('preuve') is-invalid @enderror"
                                    id="preuve"
                                    name="preuve"
                                    accept=".jpg,.jpeg,.png,image/jpeg,image/png"
                                    required>
                            </div>

                            <div class="file-preview" id="filePreview">
                                <div class="file-info">
                                    <div class="file-icon">
                                        <i class="fas fa-file"></i>
                                    </div>
                                    <div class="file-details">
                                        <h4 id="fileName"></h4>
                                        <p id="fileSize"></p>
                                    </div>
                                </div>
                            </div>

                            @error('preuve')
                                <div class="invalid-feedback" style="display: block;">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-actions">
                        <a href="{{ route('public.donates.index') }}" class="btn-secondary">
                            <i class="fas fa-arrow-left" style="margin-right: 0.5rem;"></i>
                            Retour
                        </a>
                        <button type="submit" class="btn-primary">
                            <i class="fas fa-paper-plane" style="margin-right: 0.5rem;"></i>
                            Soumettre le don
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Attendre que la page soit complètement chargée
            setTimeout(function() {
                initializeFileUpload();
            }, 200);

            function initializeFileUpload() {
                const fileInput = document.getElementById('preuve');
                const filePreview = document.getElementById('filePreview');
                const fileName = document.getElementById('fileName');
                const fileSize = document.getElementById('fileSize');
                const uploadArea = document.querySelector('.file-upload-area');

                if (!fileInput || !filePreview || !fileName || !fileSize || !uploadArea) {
                    console.error('Éléments requis non trouvés');
                    return;
                }

                // SOLUTION: Masquer complètement l'input file et le déplacer hors de la zone
                fileInput.style.cssText = `
                    position: fixed !important;
                    top: -9999px !important;
                    left: -9999px !important;
                    width: 1px !important;
                    height: 1px !important;
                    opacity: 0 !important;
                    pointer-events: none !important;
                `;

                // Réinitialiser l'état
                fileInput.value = '';
                filePreview.classList.remove('show');

                // Gérer le clic sur la zone d'upload
                uploadArea.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    console.log('Clic sur zone upload détecté');

                    // Créer un nouvel input temporaire pour éviter les conflits
                    const tempInput = document.createElement('input');
                    tempInput.type = 'file';
                    tempInput.accept = '.jpg,.jpeg,.png,image/jpeg,image/png';
                    tempInput.style.display = 'none';

                    tempInput.addEventListener('change', function(e) {
                        if (e.target.files.length > 0) {
                            // Transférer le fichier vers l'input principal
                            const dt = new DataTransfer();
                            dt.items.add(e.target.files[0]);
                            fileInput.files = dt.files;

                            // Déclencher le traitement
                            handleFileSelect();
                        }
                        // Nettoyer l'input temporaire
                        document.body.removeChild(tempInput);
                    });

                    // Ajouter temporairement au DOM et déclencher le clic
                    document.body.appendChild(tempInput);
                    tempInput.click();
                });

                // Garder l'event listener sur l'input principal pour les cas de drag & drop
                fileInput.addEventListener('change', function(e) {
                    handleFileSelect();
                });

                // Gestion du drag & drop
                uploadArea.addEventListener('dragover', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    uploadArea.style.borderColor = '#667eea';
                    uploadArea.style.backgroundColor = '#f0f4ff';
                });

                uploadArea.addEventListener('dragleave', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    uploadArea.style.borderColor = '#cbd5e1';
                    uploadArea.style.backgroundColor = '#f8fafc';
                });

                uploadArea.addEventListener('drop', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    uploadArea.style.borderColor = '#cbd5e1';
                    uploadArea.style.backgroundColor = '#f8fafc';

                    const files = e.dataTransfer.files;
                    if (files.length > 0) {
                        // Utiliser DataTransfer pour assigner le fichier
                        const dt = new DataTransfer();
                        dt.items.add(files[0]);
                        fileInput.files = dt.files;
                        handleFileSelect();
                    }
                });
            }

            function handleFileSelect() {
                const fileInput = document.getElementById('preuve');
                const filePreview = document.getElementById('filePreview');
                const fileName = document.getElementById('fileName');
                const fileSize = document.getElementById('fileSize');
                const file = fileInput.files[0];

                console.log('Fichier sélectionné:', file ? file.name : 'Aucun');

                if (!file) {
                    filePreview.classList.remove('show');
                    clearImagePreview();
                    return;
                }

                // Validation du type de fichier
                const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png'];
                const fileType = file.type.toLowerCase();

                if (!allowedTypes.includes(fileType)) {
                    alert('Seuls les fichiers JPG, JPEG et PNG sont autorisés. Les GIF ne sont pas acceptés.');
                    fileInput.value = '';
                    filePreview.classList.remove('show');
                    clearImagePreview();
                    return;
                }

                // Validation de la taille (5MB max)
                const maxSize = 5 * 1024 * 1024;
                if (file.size > maxSize) {
                    alert('Le fichier ne doit pas dépasser 5MB.');
                    fileInput.value = '';
                    filePreview.classList.remove('show');
                    clearImagePreview();
                    return;
                }

                // Afficher les informations du fichier
                fileName.textContent = file.name;
                fileSize.textContent = formatFileSize(file.size);

                // Mettre à jour l'icône
                const fileIcon = document.querySelector('.file-icon i');
                if (fileIcon) {
                    fileIcon.className = 'fas fa-file-image';
                }

                // Créer et afficher la prévisualisation
                displayImagePreview(file);
                filePreview.classList.add('show');
            }

            function displayImagePreview(file) {
                clearImagePreview();

                const filePreview = document.getElementById('filePreview');
                const reader = new FileReader();

                reader.onload = function(e) {
                    const imagePreview = document.createElement('div');
                    imagePreview.className = 'image-preview';
                    imagePreview.style.cssText = `
                        margin-top: 0.75rem;
                        text-align: center;
                        border: 1px solid #e5e7eb;
                        border-radius: 6px;
                        padding: 0.5rem;
                        background: white;
                    `;

                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.alt = 'Prévisualisation';
                    img.style.cssText = `
                        max-width: 200px;
                        max-height: 200px;
                        width: auto;
                        height: auto;
                        border-radius: 4px;
                        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
                        cursor: pointer;
                    `;

                    img.addEventListener('click', function() {
                        // Permettre d'agrandir l'image en cliquant dessus
                        window.open(e.target.result, '_blank');
                    });

                    imagePreview.appendChild(img);
                    filePreview.appendChild(imagePreview);
                };

                reader.onerror = function() {
                    console.error('Erreur lors de la lecture du fichier');
                };

                reader.readAsDataURL(file);
            }

            function clearImagePreview() {
                const filePreview = document.getElementById('filePreview');
                const existingPreview = filePreview.querySelector('.image-preview');
                if (existingPreview) {
                    existingPreview.remove();
                }
            }

            function formatFileSize(bytes) {
                if (bytes === 0) return '0 Bytes';
                const k = 1024;
                const sizes = ['Bytes', 'KB', 'MB', 'GB'];
                const i = Math.floor(Math.log(bytes) / Math.log(k));
                return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
            }
        });
    </script>
@endsection
