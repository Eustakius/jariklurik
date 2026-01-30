import React, { useState } from 'react';

// Types
interface ScanCheck {
    name: string;
    status: 'PASS' | 'WARNING' | 'FAIL';
    detail: string;
    icon: string;
    description?: string;
    recommendation?: string;
}

interface BlacklistData {
    summary: { [key: string]: any };
    recent_blocks: Array<{ ip_address: string, created_at: string, url: string, user_agent?: string }>;
}

interface HealthMetric {
    category: string;
    value: string;
    status: 'PASS' | 'WARNING' | 'FAIL' | 'CRITICAL' | 'INFO';
    detail: string;
}

interface ActionResult {
    type: 'scan' | 'blacklist' | 'health';
    title: string;
    data: any;
    timestamp?: string;
}

const ScanResultModal: React.FC<{ result: ActionResult; onClose: () => void }> = ({ result, onClose }) => {
    return (
        <div className="fixed inset-0 bg-black bg-opacity-70 flex items-center justify-center z-50 p-4">
            <div className="bg-gray-800 rounded-lg shadow-2xl border border-gray-600 max-w-2xl w-full max-h-[90vh] overflow-y-auto">
                <div className="flex justify-between items-center p-4 border-b border-gray-700">
                    <h3 className="text-xl font-bold text-white flex items-center gap-2">
                        {result.type === 'scan' ? '🛡️' : result.type === 'blacklist' ? '🚫' : '🩺'}
                        {result.title}
                    </h3>
                    <button onClick={onClose} className="text-gray-400 hover:text-white">&times;</button>
                </div>

                <div className="p-6">
                    {/* Security Scan View */}
                    {result.type === 'scan' && (
                        <div className="space-y-4">
                            {result.data.map((check: ScanCheck, idx: number) => (
                                <details key={idx} className="group bg-gray-750 rounded border border-gray-700 overflow-hidden cursor-pointer">
                                    <summary className="p-4 flex justify-between items-center outline-none list-none hover:bg-gray-700 transition">
                                        <div className="flex items-center gap-3">
                                            <span className="text-xl opacity-70 group-open:opacity-100 transition-opacity">
                                                {check.status === 'PASS' ? '✅' : check.status === 'WARNING' ? '⚠️' : '🚨'}
                                            </span>
                                            <div>
                                                <h4 className="font-semibold text-gray-200">{check.name}</h4>
                                                <p className="text-sm text-gray-400 mt-1">{check.detail}</p>
                                            </div>
                                        </div>
                                        <div className="flex items-center gap-3">
                                            <span className={`px-2 py-1 rounded text-xs font-bold ${check.status === 'PASS' ? 'bg-green-900 text-green-300' :
                                                check.status === 'WARNING' ? 'bg-yellow-900 text-yellow-300' : 'bg-red-900 text-red-300'
                                                }`}>
                                                {check.status}
                                            </span>
                                            <span className="text-gray-500 transform group-open:rotate-180 transition-transform">▼</span>
                                        </div>
                                    </summary>

                                    <div className="px-4 pb-4 pt-0 border-t border-gray-700 bg-gray-800">
                                        <div className="mt-3 grid grid-cols-1 gap-3">
                                            <div className="bg-gray-900 p-3 rounded border border-gray-700">
                                                <span className="text-xs text-gray-400 uppercase font-bold tracking-wider mb-1 block">Description</span>
                                                <p className="text-gray-300 text-sm">{check.description || 'No description available.'}</p>
                                            </div>

                                            {check.status !== 'PASS' && (
                                                <div className="bg-blue-900/20 p-3 rounded border border-blue-800/50">
                                                    <span className="text-xs text-blue-400 uppercase font-bold tracking-wider mb-1 block">Recommendation</span>
                                                    <p className="text-blue-200 text-sm">{check.recommendation || 'No specific recommendation available.'}</p>
                                                </div>
                                            )}
                                        </div>
                                    </div>
                                </details>
                            ))}
                        </div>
                    )}

                    {/* Blacklist View */}
                    {result.type === 'blacklist' && (
                        <div>
                            <div className="grid grid-cols-3 gap-4 mb-6">
                                {Object.entries(result.data.summary).map(([key, val]: any) => (
                                    <div key={key} className="bg-gray-900 p-3 rounded text-center">
                                        <div className="text-xs text-gray-500 uppercase">{key}</div>
                                        <div className="font-bold text-white">{val}</div>
                                    </div>
                                ))}
                            </div>
                            <h4 className="text-sm font-bold text-gray-400 mb-2 uppercase">Recent Blocks</h4>
                            <div className="space-y-2">
                                {result.data.recent_blocks.map((block: any, idx: number) => (
                                    <div key={idx} className="flex justify-between text-sm py-2 border-b border-gray-700 last:border-0">
                                        <span className="font-mono text-red-400">{block.ip_address}</span>
                                        <span className="text-gray-500">{block.user_agent ? block.user_agent.substring(0, 20) + '...' : 'N/A'}</span>
                                        <span className="text-gray-400">{block.created_at}</span>
                                    </div>
                                ))}
                            </div>
                        </div>
                    )}

                    {/* System Health View */}
                    {result.type === 'health' && (
                        <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                            {result.data.map((metric: HealthMetric, idx: number) => (
                                <div key={idx} className="bg-gray-750 p-4 rounded border border-gray-700 relative overflow-hidden">
                                    <div className={`absolute left-0 top-0 bottom-0 w-1 ${metric.status === 'PASS' ? 'bg-green-500' :
                                        metric.status === 'CRITICAL' ? 'bg-red-500' :
                                            metric.status === 'WARNING' ? 'bg-yellow-500' : 'bg-blue-500'
                                        }`}></div>
                                    <div className="ml-2">
                                        <div className="text-xs text-gray-500 uppercase mb-1">{metric.category}</div>
                                        <div className="text-2xl font-bold text-white mb-1">{metric.value}</div>
                                        <div className="text-xs text-gray-400">{metric.detail}</div>
                                    </div>
                                </div>
                            ))}
                        </div>
                    )}
                </div>

                <div className="p-4 border-t border-gray-700 flex justify-end">
                    <button onClick={onClose} className="bg-gray-700 hover:bg-gray-600 px-4 py-2 rounded text-white text-sm">
                        Close Report
                    </button>
                </div>
            </div>
        </div>
    );
};

export const ActionCenter: React.FC = () => {
    const [scanning, setScanning] = useState(false);
    const [resultData, setResultData] = useState<ActionResult | null>(null);

    const handleAction = async (action: 'scan' | 'blacklist' | 'health') => {
        setScanning(true);
        setResultData(null);

        let url = '';
        let method = 'GET';

        if (action === 'scan') {
            url = '/api/security/quick-scan';
            method = 'POST';
        } else if (action === 'blacklist') {
            url = '/api/security/check-blacklist';
        } else if (action === 'health') {
            url = '/api/security/system-health';
        }

        try {
            const res = await fetch(url, { method });
            if (!res.ok) throw new Error(`HTTP Error ${res.status}`);

            const data = await res.json();

            // Handle different response formats
            let resultPayload;
            if (action === 'scan') {
                resultPayload = data.checks || [];
            } else if (action === 'blacklist') {
                resultPayload = { summary: {}, recent_blocks: [] };
            } else if (action === 'health') {
                resultPayload = [
                    { category: 'Status', value: data.status || 'Unknown', status: 'PASS', detail: 'System operational' },
                    { category: 'Uptime', value: data.uptime || 'N/A', status: 'PASS', detail: 'Database uptime' },
                    { category: 'DB Latency', value: data.latency || 'N/A', status: 'INFO', detail: 'Query response time' },
                    { category: 'Memory', value: data.memory || 'N/A', status: 'INFO', detail: 'App memory usage' },
                    { category: 'Disk Space', value: data.disk || 'N/A', status: 'INFO', detail: 'Storage usage' }
                ];
            }

            setResultData({
                type: action,
                title: action === 'scan' ? 'Security Scan Results' : action === 'blacklist' ? 'Blacklist Check' : 'System Health',
                data: resultPayload,
                timestamp: new Date().toISOString()
            });
        } catch (e) {
            console.error(e);
            alert(`Action Failed: ${e instanceof Error ? e.message : 'Unknown Error'}`);
        } finally {
            setScanning(false);
        }
    };

    return (
        <div className="bg-gray-800 p-6 rounded-lg shadow-lg border border-gray-700 mb-8">
            <h2 className="text-xl font-bold text-white mb-4 flex items-center gap-2">
                <span className="text-purple-400">⚡</span> Action Center
            </h2>
            <div className="grid grid-cols-1 md:grid-cols-3 gap-4">
                <button
                    onClick={() => handleAction('scan')}
                    disabled={scanning}
                    className={`p-4 rounded-lg border flex flex-col items-center justify-center transition
                        ${scanning ? 'bg-purple-900 border-purple-700 cursor-wait' : 'bg-gray-750 hover:bg-gray-700 border-gray-600'}
                    `}
                >
                    <span className="text-2xl mb-2">🛡️</span>
                    <span className="font-bold text-white">{scanning ? 'Running...' : 'Quick Security Scan'}</span>
                    <span className="text-xs text-gray-400 mt-1">Malware & Vulnerabilities</span>
                </button>

                <button
                    onClick={() => handleAction('blacklist')}
                    disabled={scanning}
                    className="p-4 rounded-lg bg-gray-750 hover:bg-gray-700 border border-gray-600 flex flex-col items-center justify-center transition">
                    <span className="text-2xl mb-2">🚫</span>
                    <span className="font-bold text-white">Check Blacklist</span>
                    <span className="text-xs text-gray-400 mt-1">Domain & IP Status</span>
                </button>

                <button
                    onClick={() => handleAction('health')}
                    disabled={scanning}
                    className="p-4 rounded-lg bg-gray-750 hover:bg-gray-700 border border-gray-600 flex flex-col items-center justify-center transition">
                    <span className="text-2xl mb-2">🩺</span>
                    <span className="font-bold text-white">System Health</span>
                    <span className="text-xs text-gray-400 mt-1">Performance & Uptime</span>
                </button>
            </div>

            {resultData && <ScanResultModal result={resultData} onClose={() => setResultData(null)} />}
        </div>
    );
};
