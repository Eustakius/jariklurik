import React, { useEffect, useState } from 'react';
import { format } from 'date-fns';
import { ForensicsDetailModal } from './ForensicsDetailModal';

interface Fingerprint {
    id: number;
    ip_address: string;
    device_hash: string;
    screen_resolution: string;
    timezone: string;
    local_ips: string[];
    created_at: string;
}




export const DeviceFingerprintTable: React.FC = () => {
    const [data, setData] = useState<Fingerprint[]>([]);
    const [loading, setLoading] = useState(true);
    const [selectedDevice, setSelectedDevice] = useState<Fingerprint | null>(null);

    const [errorMsg, setErrorMsg] = useState<string | null>(null);

    useEffect(() => {
        fetch('/api/security/forensics')
            .then(async res => {
                if (!res.ok) {
                    const text = await res.text();
                    throw new Error(`API Error ${res.status}: ${text}`);
                }
                return res.json();
            })
            .then(data => {
                if (Array.isArray(data)) {
                    setData(data);
                } else {
                    console.error("Forensics API returned non-array:", data);
                    setErrorMsg("Invalid Data Format: " + JSON.stringify(data));
                    setData([]);
                }
                setLoading(false);
            })
            .catch(err => {
                console.error("Failed to load forensics", err);
                setErrorMsg(err.message);
                setLoading(false);
            });
    }, []);

    if (loading) return <div className="p-4 text-white">Loading Forensics Data...</div>;

    return (
        <div className="bg-gray-800 p-6 rounded-lg shadow-lg border border-gray-700 mt-6">
            <h2 className="text-xl font-bold text-white mb-4 flex items-center gap-2">
                <span className="text-blue-400">📱</span> Device Level Analytics (Forensics)
            </h2>
            <div className="overflow-x-auto">
                <table className="w-full text-sm text-left text-gray-400">
                    <thead className="text-xs text-gray-200 uppercase bg-gray-700">
                        <tr>
                            <th className="px-6 py-3">Time</th>
                            <th className="px-6 py-3">Public IP</th>
                            <th className="px-6 py-3">Local IPs (WebRTC Leak)</th>
                            <th className="px-6 py-3">Device Hash</th>
                            <th className="px-6 py-3">Screen</th>
                            <th className="px-6 py-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        {data?.map((item) => (
                            <tr key={item.id} className="bg-gray-800 border-b border-gray-700 hover:bg-gray-750">
                                <td className="px-6 py-4">
                                    {format(new Date(item.created_at), 'MMM dd HH:mm:ss')}
                                </td>
                                <td className="px-6 py-4 font-mono text-blue-400">
                                    {item.ip_address}
                                </td>
                                <td className="px-6 py-4 font-mono text-yellow-400">
                                    {item.local_ips && item.local_ips.length > 0
                                        ? item.local_ips.join(', ')
                                        : <span className="text-gray-600">No Leak</span>}
                                </td>
                                <td className="px-6 py-4 font-mono text-xs">
                                    {item.device_hash.substring(0, 12)}...
                                </td>
                                <td className="px-6 py-4">{item.screen_resolution}</td>
                                <td className="px-6 py-4">
                                    <button
                                        onClick={() => setSelectedDevice(item)}
                                        className="text-xs bg-blue-600 hover:bg-blue-500 text-white px-2 py-1 rounded"
                                    >
                                        View Details
                                    </button>
                                </td>
                            </tr>
                        ))}
                        {errorMsg ? (
                            <tr>
                                <td colSpan={6} className="px-6 py-4 text-center text-red-500 font-bold">
                                    Error loading data: {errorMsg}
                                </td>
                            </tr>
                        ) : (!data || data.length === 0) && (
                            <tr>
                                <td colSpan={6} className="px-6 py-4 text-center">No forensics data captured yet.</td>
                            </tr>
                        )}
                    </tbody>
                </table>
            </div>

            {selectedDevice && (
                <ForensicsDetailModal
                    data={selectedDevice}
                    onClose={() => setSelectedDevice(null)}
                />
            )}
        </div>
    );
};
