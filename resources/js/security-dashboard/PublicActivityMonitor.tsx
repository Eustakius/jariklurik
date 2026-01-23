import React, { useEffect, useState } from 'react';

interface PublicStats {
    active_visitors: number;
    today_views: number;
    popular_pages: Array<{ page_url: string, views: string }>;
    recent_activity: Array<{
        ip_address: string,
        page_url: string,
        user_agent: string,
        last_activity: string,
        device_type?: string,
        platform?: string
    }>;
}

const getDeviceIcon = (type?: string) => {
    switch (type) {
        case 'Mobile': return '📱';
        case 'Tablet': return '📱'; // Tablet specific if needed
        case 'Desktop': return '💻';
        case 'Unknown': return '🤖';
        default: return '🌐';
    }
};

export const PublicActivityMonitor: React.FC = () => {
    const [stats, setStats] = useState<PublicStats | null>(null);
    const [loading, setLoading] = useState(true);

    const fetchStats = async () => {
        try {
            const res = await fetch('/api/security/public-activity');
            const data = await res.json();
            setStats(data);
            setLoading(false);
        } catch (e) {
            console.error("Failed to load public activity", e);
            setLoading(false);
        }
    };

    useEffect(() => {
        fetchStats();
        const interval = setInterval(fetchStats, 5000); // 5s poll
        return () => clearInterval(interval);
    }, []);

    if (loading) return <div className="p-4 text-center text-gray-500">Loading Audience Analytics...</div>;
    if (!stats) return null;

    return (
        <div className="bg-gray-800 p-6 rounded-lg border border-gray-700 shadow-lg mb-8">
            <h2 className="text-xl font-bold text-white mb-6 flex items-center gap-2">
                <span className="text-cyan-400">🌐</span> Public User Activity
                <span className="text-xs bg-cyan-900 text-cyan-200 px-2 py-1 rounded ml-2">Live Monitor</span>
            </h2>

            {/* Metrics Grid */}
            <div className="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                <div className="bg-gradient-to-r from-cyan-900/50 to-blue-900/50 p-4 rounded-lg border border-cyan-800 flex items-center justify-between">
                    <div>
                        <p className="text-cyan-300 text-sm uppercase font-bold tracking-wider">Live Visitors</p>
                        <p className="text-xs text-cyan-500">Last 15 Minutes</p>
                    </div>
                    <div className="text-4xl font-mono text-white font-bold animate-pulse">{stats.active_visitors}</div>
                </div>

                <div className="bg-gray-750 p-4 rounded-lg border border-gray-600 flex items-center justify-between">
                    <div>
                        <p className="text-gray-400 text-sm uppercase font-bold tracking-wider">Page Views</p>
                        <p className="text-xs text-gray-500">Today (Since Midnight)</p>
                    </div>
                    <div className="text-4xl font-mono text-white font-bold">{stats.today_views}</div>
                </div>
            </div>

            <div className="grid grid-cols-1 lg:grid-cols-2 gap-6">
                {/* Popular Pages */}
                <div>
                    <h3 className="text-sm font-bold text-gray-400 uppercase mb-3 border-b border-gray-700 pb-2">Most Visited Content</h3>
                    <div className="space-y-2">
                        {stats.popular_pages?.map((page, idx) => (
                            <div key={idx} className="flex justify-between items-center bg-gray-900/50 p-3 rounded hover:bg-gray-700/50 transition">
                                <div className="flex items-center gap-3 overflow-hidden">
                                    <span className="text-gray-500 font-mono text-xs">#{idx + 1}</span>
                                    <span className="text-cyan-400 text-sm truncate font-medium" title={page.page_url}>
                                        {page.page_url || '/'}
                                    </span>
                                </div>
                                <span className="text-white font-bold bg-gray-700 px-2 py-1 rounded text-xs">
                                    {page.views} hits
                                </span>
                            </div>
                        ))}
                        {(!stats.popular_pages || stats.popular_pages.length === 0) && <div className="text-gray-500 italic text-sm">No page views recorded today.</div>}
                    </div>
                </div>

                {/* Recent Feed */}
                <div>
                    <h3 className="text-sm font-bold text-gray-400 uppercase mb-3 border-b border-gray-700 pb-2">Real-time Feed</h3>
                    <div className="space-y-2 h-[250px] overflow-y-auto custom-scrollbar pr-2">
                        {stats.recent_activity?.map((log, idx) => (
                            <div key={idx} className="text-xs border-l-2 border-cyan-800 pl-3 py-2 hover:border-cyan-500 transition bg-black/20 mb-1 rounded-r">
                                <div className="flex justify-between mb-1">
                                    <div className="flex items-center gap-2">
                                        <span className="text-lg" title={log.device_type || 'Unknown Device'}>{getDeviceIcon(log.device_type)}</span>
                                        <span className="text-gray-300 font-mono font-bold">{log.ip_address}</span>
                                        {log.platform && <span className="bg-gray-700 text-gray-300 px-1 rounded text-[10px]">{log.platform}</span>}
                                    </div>
                                    <span className="text-cyan-500 font-mono">{new Date(log.last_activity).toLocaleTimeString()}</span>
                                </div>
                                <div className="text-cyan-400 font-medium truncate mb-1 pl-7">{log.page_url || 'Home'}</div>
                                <div className="text-gray-600 truncate pl-7 text-[10px]" title={log.user_agent}>
                                    {log.user_agent.substring(0, 50)}...
                                </div>
                            </div>
                        ))}
                        {(!stats.recent_activity || stats.recent_activity.length === 0) && <div className="text-gray-500 italic text-sm">No recent activity.</div>}
                    </div>
                </div>
            </div>
        </div>
    );
};
