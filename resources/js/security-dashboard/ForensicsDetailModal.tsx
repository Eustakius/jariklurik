import React from 'react';

interface Props {
    data: any;
    onClose: () => void;
}

const DetailRow: React.FC<{ label: string, value: any }> = ({ label, value }) => (
    <div className="flex justify-between py-2 border-b border-gray-800 last:border-0 hover:bg-white/5 px-2">
        <span className="text-gray-500 text-xs uppercase font-semibold tracking-wider">{label}</span>
        <span className="text-gray-300 font-mono text-sm max-w-xs truncate text-right" title={String(value)}>
            {typeof value === 'object' ? JSON.stringify(value) : String(value)}
        </span>
    </div>
);

const Section: React.FC<{ title: string, children: React.ReactNode }> = ({ title, children }) => (
    <div className="mb-6">
        <h4 className="text-xs uppercase text-blue-500 font-bold mb-3 border-b border-blue-900/50 pb-1">{title}</h4>
        <div className="bg-gray-950 rounded border border-gray-800 p-2">
            {children}
        </div>
    </div>
);

export const ForensicsDetailModal: React.FC<Props> = ({ data, onClose }) => {
    if (!data) return null;

    // Parse raw data if it's a string (back-compat) or use as object
    const raw = typeof data.raw_data === 'string' ? JSON.parse(data.raw_data) : data.raw_data;

    return (
        <div className="fixed inset-0 bg-black/90 flex items-center justify-center z-50 p-4 backdrop-blur-sm">
            <div className="bg-gray-900 rounded-xl shadow-2xl border border-gray-700 w-full max-w-4xl max-h-[90vh] flex flex-col">
                {/* Header */}
                <div className="p-5 border-b border-gray-700 flex justify-between items-center bg-gray-800 rounded-t-xl">
                    <div className="flex items-center gap-3">
                        <div className="bg-blue-900/30 p-2 rounded-lg text-blue-400 text-xl">
                            🔍
                        </div>
                        <div>
                            <h3 className="text-xl font-bold text-white tracking-tight">Digital Forensic Report</h3>
                            <p className="text-xs text-gray-400 uppercase tracking-widest">{data.device_hash}</p>
                        </div>
                    </div>
                    <button onClick={onClose} className="text-gray-400 hover:text-white hover:bg-gray-700 p-2 rounded-full transition">
                        ✕
                    </button>
                </div>

                {/* Content */}
                <div className="p-6 overflow-y-auto custom-scrollbar flex-1 grid grid-cols-1 md:grid-cols-2 gap-6">

                    {/* Left Column: Core Identity */}
                    <div>
                        <Section title="Network Identity">
                            <DetailRow label="Public IP" value={data.ip_address} />
                            <DetailRow label="Local IPs (WebRTC)" value={data.local_ips?.join(', ') || 'N/A'} />
                            <DetailRow label="User Agent" value={raw.ua || raw.userAgent || 'Unknown'} />
                            <DetailRow label="Timezone" value={data.timezone} />
                        </Section>

                        <Section title="Device Hardware">
                            <DetailRow label="Screen Resolution" value={data.screen_resolution} />
                            <DetailRow label="CPU Cores" value={raw.hardwareConcurrency || 'N/A'} />
                            <DetailRow label="Device Memory (RAM)" value={raw.deviceMemory ? `~${raw.deviceMemory} GB` : 'N/A'} />
                            <DetailRow label="Platform" value={raw.platform || 'Unknown'} />
                        </Section>
                    </div>

                    {/* Right Column: Browser Fingerprint */}
                    <div>
                        <Section title="Browser Capability">
                            <DetailRow label="Language" value={raw.language || 'N/A'} />
                            <DetailRow label="Cookies Enabled" value={raw.cookieEnabled ? 'Yes' : 'No'} />
                            <DetailRow label="Do Not Track" value={raw.doNotTrack || 'Unspecified'} />
                            <DetailRow label="Touch Support" value={raw.touchSupport ? 'Yes' : 'No'} />
                        </Section>

                        <Section title="Graphics (Entropy High)">
                            <DetailRow label="Renderer" value={raw.webgl?.renderer || 'N/A'} />
                            <DetailRow label="Vendor" value={raw.webgl?.vendor || 'N/A'} />
                            <DetailRow label="Canvas Hash" value={data.canvas_hash || raw.canvas_hash || 'N/A'} />
                        </Section>
                    </div>

                    {/* Full Raw Object Dump at bottom */}
                    <div className="col-span-1 md:col-span-2">
                        <Section title="Full JSON Payload">
                            <pre className="text-[10px] text-green-400 font-mono bg-black p-4 rounded overflow-x-auto max-h-40">
                                {JSON.stringify(raw, null, 2)}
                            </pre>
                        </Section>
                    </div>

                </div>

                {/* Footer */}
                <div className="p-4 border-t border-gray-700 bg-gray-800/50 flex justify-between items-center rounded-b-xl">
                    <span className="text-xs text-gray-500">Generated: {data.created_at}</span>
                    <button onClick={onClose} className="px-6 py-2 bg-blue-600 hover:bg-blue-500 text-white rounded font-medium shadow-lg shadow-blue-900/20 transition">
                        Close Report
                    </button>
                </div>
            </div>
        </div>
    );
};
