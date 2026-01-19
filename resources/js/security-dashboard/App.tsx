import React, { useEffect, useState } from 'react';
import { DeviceFingerprintTable } from './DeviceFingerprintTable';
import { BlockedIPsTable } from './BlockedIPsTable';
import { ActionCenter } from './ActionCenter';
import { TrafficChart } from './TrafficChart';
import { AttackRadar } from './AttackRadar';
import { PublicActivityMonitor } from './PublicActivityMonitor';

// Types
interface Stats {
    total_requests_24h: number;
    blocked_requests_24h: number;
    recent_incidents: any[];
    top_attackers: any[];
    traffic_over_time: any[];
}

function App() {
    const [stats, setStats] = useState<Stats | null>(null);
    const [loading, setLoading] = useState(true);

    useEffect(() => {
        // Poll for stats every 5 seconds
        const fetchStats = async () => {
            try {
                const response = await fetch('/api/security/stats');
                const data = await response.json();
                setStats(data);
                setLoading(false);
            } catch (error) {
                console.error('Error fetching stats:', error);
            }
        };

        fetchStats();
        const interval = setInterval(fetchStats, 5000);
        return () => clearInterval(interval);
    }, []);

    if (loading) return <div className="text-white p-10">Loading Security Dashboard...</div>;

    return (
        <div className="min-h-screen bg-gray-900 text-white p-8">
            <header className="mb-8 border-b border-gray-700 pb-4">
                <h1 className="text-3xl font-bold text-red-500">🛡️ BP3MI Security Command Center</h1>
                <p className="text-gray-400">Real-time Threat Monitoring & Defense System</p>
            </header>

            {/* Action Center */}
            <ActionCenter />

            {/* Stats Grid */}
            <div className="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div className="bg-gray-800 p-6 rounded-lg border border-gray-700 shadow-lg">
                    <h3 className="text-gray-400 text-sm uppercase">Total Requests (24h)</h3>
                    <p className="text-4xl font-bold text-blue-400 mt-2">{stats?.total_requests_24h}</p>
                </div>
                <div className="bg-gray-800 p-6 rounded-lg border border-gray-700 shadow-lg relative overflow-hidden">
                    <div className="absolute top-0 right-0 p-2 bg-red-600 text-xs font-bold rounded-bl-lg">LIVE</div>
                    <h3 className="text-gray-400 text-sm uppercase">Blocked Attacks</h3>
                    <p className="text-4xl font-bold text-red-500 mt-2">{stats?.blocked_requests_24h}</p>
                </div>
                <div className="bg-gray-800 p-6 rounded-lg border border-gray-700 shadow-lg">
                    <h3 className="text-gray-400 text-sm uppercase">System Status</h3>
                    <p className="text-2xl font-bold text-green-400 mt-2">ACTIVE</p>
                </div>
                <div className="bg-gray-800 p-6 rounded-lg border border-gray-700 shadow-lg">
                    <h3 className="text-gray-400 text-sm uppercase">Threat Level</h3>
                    <p className="text-2xl font-bold text-yellow-400 mt-2">MODERATE</p>
                </div>
            </div>

            {/* Charts Section */}
            <div className="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div className="md:col-span-2 bg-gray-800 p-6 rounded-lg border border-gray-700">
                    <h2 className="text-lg font-bold text-white mb-4 flex items-center gap-2">
                        <span className="text-blue-400">📊</span> Traffic Analysis (24h)
                    </h2>
                    <TrafficChart data={stats?.traffic_over_time || []} />
                </div>
                <div className="bg-gray-800 p-6 rounded-lg border border-gray-700">
                    <h2 className="text-lg font-bold text-white mb-4 flex items-center gap-2">
                        <span className="text-red-400">🎯</span> Threat Vector Radar
                    </h2>
                    <AttackRadar data={stats?.top_attackers || []} />
                </div>
            </div>

            <div className="grid grid-cols-1 lg:grid-cols-2 gap-8">
                {/* Recent Incidents */}
                <div className="bg-gray-800 rounded-lg p-6 border border-gray-700">
                    <h2 className="text-xl font-semibold mb-4 text-red-400">🚨 Recent Incidents</h2>
                    <div className="overflow-x-auto">
                        <table className="w-full text-left text-sm text-gray-400">
                            <thead className="bg-gray-900 text-gray-200 uppercase text-xs">
                                <tr>
                                    <th className="px-4 py-3">Time</th>
                                    <th className="px-4 py-3">Type</th>
                                    <th className="px-4 py-3">Severity</th>
                                    <th className="px-4 py-3">Details</th>
                                </tr>
                            </thead>
                            <tbody className="divide-y divide-gray-700">
                                {stats?.recent_incidents?.map((incident: any) => (
                                    <tr key={incident.id} className="hover:bg-gray-750">
                                        <td className="px-4 py-3">{incident.created_at}</td>
                                        <td className="px-4 py-3 font-medium text-white">{incident.type}</td>
                                        <td className="px-4 py-3">
                                            <span className={`px-2 py-1 rounded text-xs font-semibold
                        ${incident.severity === 'high' ? 'bg-red-900 text-red-200' :
                                                    incident.severity === 'medium' ? 'bg-yellow-900 text-yellow-200' : 'bg-gray-700'}`}>
                                                {incident.severity}
                                            </span>
                                        </td>
                                        <td className="px-4 py-3 truncate max-w-xs">{incident.details}</td>
                                    </tr>
                                ))}
                                {(!stats?.recent_incidents || stats.recent_incidents.length === 0) && (
                                    <tr>
                                        <td colSpan={4} className="px-4 py-3 text-center text-gray-500">No recent incidents detected.</td>
                                    </tr>
                                )}
                            </tbody>
                        </table>
                    </div>
                </div>

                {/* Top Attackers */}
                <div className="bg-gray-800 rounded-lg p-6 border border-gray-700">
                    <h2 className="text-xl font-semibold mb-4 text-blue-400">🌍 Top Sources</h2>
                    <div className="overflow-x-auto">
                        <table className="w-full text-left text-sm text-gray-400">
                            <thead className="bg-gray-900 text-gray-200 uppercase text-xs">
                                <tr>
                                    <th className="px-4 py-3">IP Address</th>
                                    <th className="px-4 py-3">Attempts</th>
                                    <th className="px-4 py-3">Action</th>
                                </tr>
                            </thead>
                            <tbody className="divide-y divide-gray-700">
                                {stats?.top_attackers?.map((attacker: any, index: number) => (
                                    <tr key={index} className="hover:bg-gray-750">
                                        <td className="px-4 py-3 font-mono text-white">{attacker.ip_address}</td>
                                        <td className="px-4 py-3">{attacker.count}</td>
                                        <td className="px-4 py-3">
                                            <button className="text-red-400 hover:text-red-300 text-xs uppercase font-bold tracking-wider">
                                                Block IP
                                            </button>
                                        </td>
                                    </tr>
                                ))}
                                {(!stats?.top_attackers || stats.top_attackers.length === 0) && (
                                    <tr>
                                        <td colSpan={3} className="px-4 py-3 text-center text-gray-500">No attacker data available.</td>
                                    </tr>
                                )}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <DeviceFingerprintTable />

            <div className="mt-8">
                <BlockedIPsTable />
            </div>

            <div className="mt-8 border-t border-gray-700 pt-8">
                <PublicActivityMonitor />
            </div>
        </div>
    );
}

export default App;
