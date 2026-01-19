/**
 * Jarik Lurik - Forensics & Attribution Module
 * Captures device fingerprint and WebRTC local IPs.
 */

(async function () {
    const DEBUG = true;
    const ENDPOINT = '/api/security/forensics';

    // 1. Canvas Fingerprinting
    function getCanvasFingerprint() {
        try {
            const canvas = document.createElement('canvas');
            const ctx = canvas.getContext('2d');
            canvas.height = 60;
            canvas.width = 400;

            // Text with special properties (shadow, transparency, font)
            ctx.textBaseline = "top";
            ctx.font = "14px 'Arial'";
            ctx.textBaseline = "alphabetic";
            ctx.fillStyle = "#f60";
            ctx.fillRect(125, 1, 62, 20);
            ctx.fillStyle = "#069";
            ctx.fillText("JarikLurik Forensics 1.0 <canvas>", 2, 15);
            ctx.fillStyle = "rgba(102, 204, 0, 0.7)";
            ctx.fillText("JarikLurik Forensics 1.0 <canvas>", 4, 17);

            return canvas.toDataURL();
        } catch (e) {
            return null;
        }
    }

    // 2. WebGL Fingerprinting
    function getWebGLFingerprint() {
        try {
            const canvas = document.createElement('canvas');
            const gl = canvas.getContext('webgl') || canvas.getContext('experimental-webgl');
            if (!gl) return null;

            const debugInfo = gl.getExtension('WEBGL_debug_renderer_info');
            return {
                vendor: gl.getParameter(debugInfo.UNMASKED_VENDOR_WEBGL),
                renderer: gl.getParameter(debugInfo.UNMASKED_RENDERER_WEBGL)
            };
        } catch (e) {
            return null;
        }
    }

    // 3. WebRTC Local IP Leak (Bypasses VPN often)
    async function getLocalIPs() {
        return new Promise((resolve) => {
            const ips = [];
            // Add STUN server to get public candidates as well
            const pc = new RTCPeerConnection({ iceServers: [{ urls: 'stun:stun.l.google.com:19302' }] });

            pc.createDataChannel('');

            pc.onicecandidate = (e) => {
                if (!e.candidate) {
                    pc.close();
                    resolve([...new Set(ips)]);
                    return;
                }
                const line = e.candidate.candidate;
                // Parse IP from candidate string
                const ipRegex = /([0-9]{1,3}(\.[0-9]{1,3}){3}|[a-f0-9]{1,4}(:[a-f0-9]{1,4}){7})/;
                const match = line.match(ipRegex);
                if (match) {
                    ips.push(match[0]);
                }
            };

            pc.createOffer()
                .then((sdp) => pc.setLocalDescription(sdp))
                .catch(() => resolve([]));

            // Timeout after 2s
            setTimeout(() => {
                pc.close();
                resolve([...new Set(ips)]);
            }, 2000);
        });
    }

    // 4. Collect Data
    async function collectData() {
        // Spam Prevention: Only collect once per session
        if (sessionStorage.getItem('jarik_forensics_sent')) {
            if (DEBUG) console.log('Forensics already sent this session.');
            return;
        }

        let batteryInfo = {};
        if (navigator.getBattery) {
            try {
                const battery = await navigator.getBattery();
                batteryInfo = {
                    level: battery.level,
                    charging: battery.charging
                };
            } catch (e) { }
        }

        const fingerprint = {
            canvas_hash: getCanvasFingerprint() ? btoa(getCanvasFingerprint()).slice(0, 64) : 'error',
            webgl: getWebGLFingerprint(),
            local_ips: await getLocalIPs(), // Now includes STUN candidates
            screen: {
                width: window.screen.width,
                height: window.screen.height,
                availWidth: window.screen.availWidth,
                availHeight: window.screen.availHeight,
                depth: window.screen.colorDepth,
                pixelRatio: window.devicePixelRatio,
                orientation: window.screen.orientation ? window.screen.orientation.type : 'unknown'
            },
            network: navigator.connection ? {
                effectiveType: navigator.connection.effectiveType,
                rtt: navigator.connection.rtt,
                downlink: navigator.connection.downlink,
                saveData: navigator.connection.saveData
            } : {},
            battery: batteryInfo,
            timezone: Intl.DateTimeFormat().resolvedOptions().timeZone,
            language: navigator.language,
            cores: navigator.hardwareConcurrency,
            memory: navigator.deviceMemory,
            platform: navigator.platform,
            webdriver: navigator.webdriver, // Bot detection
            url: window.location.href,
            referrer: document.referrer
        };

        if (DEBUG) console.log('Forensics Data:', fingerprint);

        // 5. Send to Server
        fetch(ENDPOINT, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify(fingerprint)
        }).then(res => {
            if (res.ok) {
                // Mark as sent for this session
                sessionStorage.setItem('jarik_forensics_sent', 'true');
            }
        }).catch(e => console.error('Forensics upload failed', e));
    }

    // Run on load, but delay slightly to not impact LCP
    if (document.readyState === 'complete') {
        setTimeout(collectData, 1000);
    } else {
        window.addEventListener('load', () => setTimeout(collectData, 1000));
    }

})();
