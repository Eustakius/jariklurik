<!-- Reactbits-style Alert System -->
<div id="alert-container" class="fixed top-4 left-1/2 -translate-x-1/2 z-[9999] flex flex-col gap-3 max-w-md pointer-events-none">
    <!-- Alerts will be dynamically inserted here -->
</div>

<style>
/* Reactbits Alert Animations */
@keyframes slideInRight {
    from {
        transform: translateX(400px);
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
        transform: translateX(400px);
        opacity: 0;
    }
}

.alert-slide-in {
    animation: slideInRight 0.4s cubic-bezier(0.16, 1, 0.3, 1) forwards;
}

.alert-slide-out {
    animation: slideOutRight 0.3s cubic-bezier(0.4, 0, 1, 1) forwards;
}

/* Progress Bar */
@keyframes progressShrink {
    from { width: 100%; }
    to { width: 0%; }
}

.alert-progress {
    animation: progressShrink var(--duration) linear forwards;
}

/* Alert hover effect */
.alert-box:hover {
    transform: translateY(-2px);
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.2), 0 10px 10px -5px rgba(0, 0, 0, 0.1);
}
</style>

<script>
// Reactbits Alert Manager
window.Alert = (function() {
    const container = document.getElementById('alert-container');
    let alertCounter = 0;

    const alertConfig = {
        success: {
            icon: '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>',
            bgGradient: 'linear-gradient(135deg, #10b981 0%, #059669 100%)',
            iconBg: 'rgba(255, 255, 255, 0.25)',
            progressBg: 'rgba(255, 255, 255, 0.4)'
        },
        error: {
            icon: '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>',
            bgGradient: 'linear-gradient(135deg, #ef4444 0%, #dc2626 100%)',
            iconBg: 'rgba(255, 255, 255, 0.25)',
            progressBg: 'rgba(255, 255, 255, 0.4)'
        },
        warning: {
            icon: '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>',
            bgGradient: 'linear-gradient(135deg, #f59e0b 0%, #d97706 100%)',
            iconBg: 'rgba(255, 255, 255, 0.25)',
            progressBg: 'rgba(255, 255, 255, 0.4)'
        },
        info: {
            icon: '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>',
            bgGradient: 'linear-gradient(135deg, #3b82f6 0%, #2563eb 100%)',
            iconBg: 'rgba(255, 255, 255, 0.25)',
            progressBg: 'rgba(255, 255, 255, 0.4)'
        }
    };

    function playNotificationSound() {
        try {
            // Create audio context for notification sound
            const audioContext = new (window.AudioContext || window.webkitAudioContext)();
            
            // Create oscillator for "tuliluttt" sound
            const oscillator = audioContext.createOscillator();
            const gainNode = audioContext.createGain();
            
            oscillator.connect(gainNode);
            gainNode.connect(audioContext.destination);
            
            // Configure sound
            oscillator.type = 'sine';
            
            const now = audioContext.currentTime;
            
            // "Tu-li-lu-ttt" melody
            oscillator.frequency.setValueAtTime(800, now);           // Tu
            oscillator.frequency.setValueAtTime(1000, now + 0.1);    // li
            oscillator.frequency.setValueAtTime(1200, now + 0.2);    // lu
            oscillator.frequency.setValueAtTime(1000, now + 0.3);    // ttt
            
            // Volume envelope
            gainNode.gain.setValueAtTime(0.3, now);
            gainNode.gain.exponentialRampToValueAtTime(0.01, now + 0.5);
            
            oscillator.start(now);
            oscillator.stop(now + 0.5);
        } catch (e) {
            // Silently fail if AudioContext not available or blocked
            console.debug('Notification sound not available:', e.message);
        }
    }

    function show(message, type = 'info', duration = 5000) {
        if (!container) {
            console.error('Alert container not found!');
            return;
        }

        // Play notification sound
        try {
            playNotificationSound();
        } catch (e) {
            console.log('Sound not available:', e);
        }

        const config = alertConfig[type] || alertConfig.info;
        const alertId = 'alert-' + (++alertCounter);
        
        const alertEl = document.createElement('div');
        alertEl.id = alertId;
        alertEl.className = 'pointer-events-auto alert-box alert-slide-in';
        alertEl.style.cssText = `
            --duration: ${duration}ms;
            background: ${config.bgGradient};
            border-radius: 12px;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.15), 0 4px 6px -2px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            min-width: 320px;
            max-width: 420px;
        `;
        
        alertEl.innerHTML = `
            <div class="p-4 relative">
                <div class="flex items-start gap-3">
                    <div class="flex-shrink-0 rounded-full p-2 text-white" style="background: ${config.iconBg};">
                        ${config.icon}
                    </div>
                    <div class="flex-1 text-white pt-0.5">
                        <div class="text-sm font-semibold leading-relaxed">
                            ${escapeHtml(message)}
                        </div>
                    </div>
                </div>
            </div>
            <div class="h-1 alert-progress" style="background: ${config.progressBg};"></div>
        `;
        
        container.appendChild(alertEl);
        
        setTimeout(() => dismiss(alertId), duration);
        
        return alertId;
    }

    function dismiss(alertId) {
        const alert = document.getElementById(alertId);
        if (alert) {
            alert.classList.remove('alert-slide-in');
            alert.classList.add('alert-slide-out');
            setTimeout(() => {
                if (alert.parentNode) {
                    alert.parentNode.removeChild(alert);
                }
            }, 300);
        }
    }

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    // Auto-handle PHP flash messages
    document.addEventListener('DOMContentLoaded', function() {
        <?php if (session()->has('message-backend')): ?>
            Alert.success(<?= json_encode(session('message-backend')) ?>);
        <?php endif; ?>
        
        <?php if (session()->has('error-backend')): ?>
            Alert.error(<?= json_encode(session('error-backend')) ?>);
        <?php endif; ?>
        
        <?php if (session()->has('warning-backend')): ?>
            Alert.warning(<?= json_encode(session('warning-backend')) ?>);
        <?php endif; ?>
        
        <?php if (session()->has('info-backend')): ?>
            Alert.info(<?= json_encode(session('info-backend')) ?>);
        <?php endif; ?>
        
        <?php if (session()->has('errors-backend')): ?>
            <?php 
            $errors = session('errors-backend');
            if (is_array($errors) && count($errors) > 0):
                // Show first error as toast, or combine if multiple
                $errorMessage = count($errors) === 1 
                    ? reset($errors) 
                    : count($errors) . ' validation errors occurred. Please check the form.';
            ?>
                Alert.error(<?= json_encode($errorMessage) ?>, 7000);
            <?php endif; ?>
        <?php endif; ?>
    });

    // Public API
    return {
        show: show,
        success: (msg, duration) => show(msg, 'success', duration),
        error: (msg, duration) => show(msg, 'error', duration),
        warning: (msg, duration) => show(msg, 'warning', duration),
        info: (msg, duration) => show(msg, 'info', duration),
        dismiss: dismiss
    };
})();

// Backward compatibility aliases
window.ToastManager = window.Alert;
</script>
