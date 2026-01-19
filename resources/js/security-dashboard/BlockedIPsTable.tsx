import React, { useEffect, useState } from 'react';

interface BlockedIP {
    id: number;
    ip_address: string;
    reason: string;
    expires_at: string;
    created_at: string;
}

export function BlockedIPsTable() {
    const [blockedIPs, setBlockedIPs] = useState<BlockedIP[]>([]);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState<string | null>(null);

    const fetchBlockedIPs = () => {
        fetch('/api/security/blocked-ips')
            .then(res => {
                if (!res.ok) {
                    throw new Error(`HTTP ${res.status}`);
                }
                return res.json();
            })
            .then(data => {
                setBlockedIPs(Array.isArray(data) ? data : []);
                setLoading(false);
                setError(null);
            })
            .catch(err => {
                console.error('Failed to load blocked IPs', err);
                setBlockedIPs([]);
                setLoading(false);
                setError(err.message || 'Network error');
            });
    };

    useEffect(() => {
        fetchBlockedIPs();
        const interval = setInterval(fetchBlockedIPs, 10000); // Refresh every 10s
        return () => clearInterval(interval);
    }, []);

    const handleUnblock = async (id: number, ip: string) => {
        if (!confirm(`Are you sure you want to unblock ${ip}?`)) {
            return;
        }

        try {
            const response = await fetch(`/api/security/blocked-ips/${id}`, {
                method: 'DELETE'
            });
            const result = await response.json();

            if (response.ok) {
                alert(`✅ ${result.message}`);
                fetchBlockedIPs(); // Refresh list
            } else {
                alert(`❌ Failed to unblock IP: ${result.message}`);
            }
        } catch (error) {
            console.error('Error unblocking IP:', error);
            alert('❌ Failed to unblock IP');
        }
    };

    if (loading && !error) {
        return <div className="text-white p-4">Loading blocked IPs...</div>;
    }

    return (
        <div className="bg-gray-800 rounded-lg p-6 shadow-lg">
            <h2 className="text-2xl font-bold text-red-500 mb-4">🚫 Blocked IP Addresses</h2>

            {error && (
                <div className="bg-yellow-900/50 border border-yellow-700 text-yellow-200 px-4 py-3 rounded mb-4">
                    <p className="font-bold">⚠️ Unable to load blocked IPs</p>
                    <p className="text-sm">Error: {error}</p>
                </div>
            )}

            {blockedIPs.length === 0 && !error ? (
                <p className="text-gray-400">No IPs are currently blocked.</p>
            ) : (
                <div className="overflow-x-auto">
                    <table className="w-full text-left">
                        <thead>
                            <tr className="border-b border-gray-700">
                                <th className="px-4 py-3 text-gray-300">IP Address</th>
                                <th className="px-4 py-3 text-gray-300">Reason</th>
                                <th className="px-4 py-3 text-gray-300">Blocked At</th>
                                <th className="px-4 py-3 text-gray-300">Expires At</th>
                                <th className="px-4 py-3 text-gray-300">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            {blockedIPs.map(ip => (
                                <tr key={ip.id} className="border-b border-gray-700 hover:bg-gray-750">
                                    <td className="px-4 py-3 text-white font-mono">{ip.ip_address}</td>
                                    <td className="px-4 py-3 text-gray-300">{ip.reason}</td>
                                    <td className="px-4 py-3 text-gray-400 text-sm">
                                        {new Date(ip.created_at).toLocaleString()}
                                    </td>
                                    <td className="px-4 py-3 text-gray-400 text-sm">
                                        {new Date(ip.expires_at).toLocaleString()}
                                    </td>
                                    <td className="px-4 py-3">
                                        <button
                                            onClick={() => handleUnblock(ip.id, ip.ip_address)}
                                            className="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded transition-colors"
                                        >
                                            Unblock
                                        </button>
                                    </td>
                                </tr>
                            ))}
                        </tbody>
                    </table>
                </div>
            )}

            <div className="mt-4 text-sm text-gray-400">
                <p>💡 Tip: IPs are automatically unblocked when their expiration time is reached.</p>
            </div>
        </div>
    );
}
