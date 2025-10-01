<?php $__env->startSection('title', 'Moyens de Paiement - Dons'); ?>

<?php $__env->startPush('styles'); ?>
    <!-- Font Awesome pour les icônes -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
    .donation-section {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 8rem 0 2rem 0;
        margin-bottom: 0;
    }

    .donation-hero {
        text-align: center;
        margin-bottom: 3rem;
    }

    .donation-hero h1 {
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 1rem;
        text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
    }

    .donation-hero p {
        font-size: 1.2rem;
        opacity: 0.9;
        max-width: 600px;
        margin: 0 auto;
    }

    .services-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 1.5rem;
        padding: 0 1rem;
        max-width: 1200px;
        margin: 0 auto;
    }

    .payment-card {
        background: white;
        border-radius: 16px;
        padding: 1.5rem;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.1);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
    }

    .payment-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: var(--card-gradient);
        border-radius: 16px 16px 0 0;
    }

    .payment-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 16px 48px rgba(0, 0, 0, 0.15);
    }

    .payment-card.mobile-money {
        --card-gradient: linear-gradient(135deg, #ff6b35, #f7931e);
    }

    .payment-card.carte-bancaire {
        --card-gradient: linear-gradient(135deg, #4facfe, #00f2fe);
    }

    .payment-card.virement-bancaire {
        --card-gradient: linear-gradient(135deg, #43e97b, #38f9d7);
    }

    .payment-header {
        display: flex;
        align-items: center;
        margin-bottom: 1.25rem;
    }

    .payment-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 1rem;
        color: white;
        background: var(--card-gradient);
        font-size: 1.25rem;
        flex-shrink: 0;
    }

    .payment-info h3 {
        margin: 0 0 0.25rem 0;
        color: #1f2937;
        font-size: 1.1rem;
        font-weight: 600;
    }

    .payment-info p {
        margin: 0;
        color: #6b7280;
        font-size: 0.85rem;
        font-weight: 500;
    }

    .payment-content {
        display: flex;
        gap: 1rem;
    }

    .payment-details {
        flex: 1;
    }

    .account-number {
        background: linear-gradient(135deg, #f8fafc, #e2e8f0);
        padding: 0.875rem;
        border-radius: 10px;
        margin-bottom: 1rem;
        border-left: 3px solid;
        border-left-color: var(--card-color);
    }

    .payment-card.mobile-money .account-number {
        --card-color: #ff6b35;
    }

    .payment-card.carte-bancaire .account-number {
        --card-color: #4facfe;
    }

    .payment-card.virement-bancaire .account-number {
        --card-color: #43e97b;
    }

    .account-label {
        display: block;
        color: #6b7280;
        font-size: 0.75rem;
        text-transform: uppercase;
        font-weight: 600;
        letter-spacing: 0.5px;
        margin-bottom: 0.375rem;
    }

    .account-value {
        color: #1f2937;
        font-family: 'JetBrains Mono', 'Courier New', monospace;
        font-size: 0.95rem;
        font-weight: 600;
        word-break: break-all;
    }

    .qrcode-container {
        flex-shrink: 0;
        width: 120px;
        text-align: center;
        padding: 0.75rem;
        background: #f8fafc;
        border-radius: 10px;
        border: 2px dashed #cbd5e1;
    }

    .qrcode-container img {
        width: 100%;
        height: auto;
        border-radius: 6px;
    }

    .qr-label {
        font-size: 0.7rem;
        color: #64748b;
        font-weight: 500;
        margin-bottom: 0.5rem;
    }

    .donate-button {
        display: block;
        width: 100%;
        padding: 0.875rem;
        background: var(--card-gradient);
        color: white;
        text-decoration: none;
        border-radius: 10px;
        text-align: center;
        font-weight: 600;
        font-size: 0.9rem;
        transition: all 0.3s ease;
        margin-top: 1rem;
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
    }

    .donate-button:hover {
        color: white;
        text-decoration: none;
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
    }

    .no-payments {
        grid-column: 1 / -1;
        text-align: center;
        padding: 3rem;
        background: white;
        border-radius: 16px;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
    }

    .no-payments i {
        font-size: 3rem;
        margin-bottom: 1rem;
        color: #cbd5e1;
    }

    .no-payments h3 {
        color: #374151;
        margin-bottom: 0.5rem;
    }

    .no-payments p {
        color: #6b7280;
    }

    .instructions-section {
        background: white;
        padding: 2rem 1rem;
        max-width: 800px;
        margin: 0 auto;
        text-align: center;
        border-radius: 16px 16px 0 0;
        margin-top: -1rem;
        position: relative;
        z-index: 10;
    }

    .instructions-section h3 {
        color: #1f2937;
        margin-bottom: 1rem;
        font-size: 1.25rem;
    }

    .instructions-section p {
        color: #6b7280;
        line-height: 1.6;
        font-size: 0.95rem;
    }

    @media (max-width: 768px) {
        .donation-hero h1 {
            font-size: 2rem;
        }

        .payment-content {
            flex-direction: column;
        }

        .qrcode-container {
            width: 100%;
            max-width: 150px;
            margin: 0 auto;
        }

        .services-grid {
            grid-template-columns: 1fr;
            padding: 0 0.5rem;
        }
    }
    </style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>

<!-- Hero Section -->
<section class="donation-section">
    <div class="container">
        <div class="donation-hero">
            <h1>Soutenez Notre Cause</h1>
            <p>Choisissez votre moyen de paiement préféré pour effectuer votre don en toute sécurité</p>
        </div>

        <div class="services-grid">
            <?php $__empty_1 = true; $__currentLoopData = $parametresDons; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $parametreDon): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="payment-card <?php echo e(str_replace('_', '-', $parametreDon->type)); ?>">
                    <div class="payment-header">
                        <div class="payment-icon">
                           <?php if($parametreDon->logo): ?>
                                
                                <img src="<?php echo e(Storage::url($parametreDon->logo)); ?>"
                                    alt="Logo <?php echo e($parametreDon->operateur); ?>"
                                    style="width: 100%; height: 100%; object-fit: contain; border-radius: 5px;">
                            <?php else: ?>
                                
                                <?php switch($parametreDon->type):
                                    case ('mobile_money'): ?>
                                        <i class="fas fa-mobile-alt"></i>
                                        <?php break; ?>
                                    <?php case ('carte_bancaire'): ?>
                                        <i class="fas fa-credit-card"></i>
                                        <?php break; ?>
                                    <?php case ('virement_bancaire'): ?>
                                        <i class="fas fa-university"></i>
                                        <?php break; ?>
                                    <?php default: ?>
                                        <i class="fas fa-money-bill-wave"></i>
                                <?php endswitch; ?>
                            <?php endif; ?>
                        </div>
                        <div class="payment-info">
                            <h3><?php echo e($parametreDon->type_libelle); ?></h3>
                            <p><?php echo e($parametreDon->operateur); ?></p>
                        </div>
                    </div>

                    <div class="payment-content">
                        <div class="payment-details">
                            <div class="account-number">
                                <span class="account-label">
                                    <?php if($parametreDon->type === 'mobile_money'): ?>
                                        Numéro Mobile Money
                                    <?php elseif($parametreDon->type === 'carte_bancaire'): ?>
                                        Numéro de Carte
                                    <?php else: ?>
                                        Numéro de Compte
                                    <?php endif; ?>
                                </span>
                                <div class="account-value"><?php echo e($parametreDon->numero_compte); ?></div>
                            </div>
                        </div>

                        <?php if($parametreDon->qrcode): ?>
                            <div class="qrcode-container">
                                <div class="qr-label">
                                    <i class="fas fa-qrcode"></i> Scanner
                                </div>
                                <img src="<?php echo e(asset('storage/' . $parametreDon->qrcode)); ?>"
                                     alt="QR Code <?php echo e($parametreDon->operateur); ?>">
                            </div>
                        <?php endif; ?>
                    </div>

                    <a href="<?php echo e(route('public.donates.create', $parametreDon)); ?>"
                       class="donate-button">
                        <i class="fas fa-heart"></i> Faire un don
                    </a>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="no-payments">
                    <i class="fas fa-exclamation-triangle"></i>
                    <h3>Aucun moyen de paiement disponible</h3>
                    <p>Les moyens de paiement ne sont pas encore configurés.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>



<?php if(session('success')): ?>
    <div id="success-notification" class="success-notification">
        <div class="notification-content">
            <div class="notification-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="notification-message">
                <h4>Succès !</h4>
                <p><?php echo e(session('success')); ?></p>
            </div>
            <button class="notification-close" onclick="closeNotification()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="notification-progress"></div>
    </div>
<?php endif; ?>

<style>
/* Styles pour la notification de succès */
.success-notification {
    position: fixed;
    top: 90px;
    right: 0px;
    background: linear-gradient(135deg, #10b981, #059669);
    color: white;
    border-radius: 12px;
    box-shadow: 0 10px 40px rgba(16, 185, 129, 0.3);
    z-index: 9999;
    min-width: 350px;
    max-width: 450px;
    overflow: hidden;
    animation: slideInRight 0.5s ease-out;
}

.notification-content {
    display: flex;
    align-items: flex-start;
    padding: 1rem;
    gap: 1rem;
    position: relative;
}

.notification-icon {
    flex-shrink: 0;
    width: 30px;
    height: 30px;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
}

.notification-message {
    flex: 1;
}

.notification-message h4 {
    margin: 0 0 0.25rem 0;
    font-size: 1rem;
    font-weight: 600;
}

.notification-message p {
    margin: 0;
    font-size: 0.875rem;
    opacity: 0.95;
    line-height: 1.4;
}

.notification-close {
    position: absolute;
    top: 1rem;
    right: 1rem;
    background: none;
    border: none;
    color: rgba(255, 255, 255, 0.8);
    font-size: 1rem;
    cursor: pointer;
    padding: 0.25rem;
    border-radius: 4px;
    transition: all 0.2s ease;
    width: 24px;
    height: 24px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.notification-close:hover {
    background: rgba(255, 255, 255, 0.1);
    color: white;
}

.notification-progress {
    height: 4px;
    background: rgba(255, 255, 255, 0.3);
    position: relative;
    overflow: hidden;
}

.notification-progress::after {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    height: 100%;
    width: 100%;
    background: rgba(255, 255, 255, 0.6);
    animation: progressBar 5s linear forwards;
}

/* Animations */
@keyframes slideInRight {
    from {
        transform: translateX(100%);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}

@keyframes slideOutRight {
    from {
        transform: translateX(0);
        opacity: 1;
    }
    to {
        transform: translateX(100%);
        opacity: 0;
    }
}

@keyframes progressBar {
    from {
        transform: translateX(-100%);
    }
    to {
        transform: translateX(0);
    }
}

/* Responsive */
@media (max-width: 768px) {
    .success-notification {
        top: 10px;
        right: 10px;
        left: 10px;
        min-width: auto;
        max-width: none;
    }

    .notification-content {
        padding: 1rem;
    }
}
</style>

<script>
// JavaScript pour gérer la notification
function closeNotification() {
    const notification = document.getElementById('success-notification');
    if (notification) {
        notification.style.animation = 'slideOutRight 0.3s ease-in forwards';
        setTimeout(() => {
            notification.remove();
        }, 300);
    }
}

// Auto-fermeture après 5 secondes
document.addEventListener('DOMContentLoaded', function() {
    const notification = document.getElementById('success-notification');
    if (notification) {
        setTimeout(() => {
            closeNotification();
        }, 5000);
    }
});

// Fermeture au clic sur l'arrière-plan
document.addEventListener('click', function(e) {
    const notification = document.getElementById('success-notification');
    if (notification && !notification.contains(e.target)) {
        // Optionnel: fermer en cliquant en dehors
        // closeNotification();
    }
});
</script>


</section>

<?php if($parametresDons->isNotEmpty()): ?>
    <section class="instructions-section">
        <h3><i class="fas fa-info-circle" style="margin-right: 0.5rem; color: #3b82f6;"></i>Instructions</h3>
        <p>
            Après avoir effectué votre paiement via l'un des moyens ci-dessus,
            cliquez sur <strong>"Faire un don"</strong> pour soumettre votre preuve de paiement
            et finaliser votre contribution. Votre don sera vérifié et confirmé rapidement.
        </p>
    </section>
<?php endif; ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.public.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Dell\Desktop\MICRISERVICES\methodiste_belle_ville\resources\views/components/public/dons/index.blade.php ENDPATH**/ ?>