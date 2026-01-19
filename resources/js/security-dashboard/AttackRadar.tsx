import React from 'react';
import ReactECharts from 'echarts-for-react';

interface Props {
    data: any[];
}

export const AttackRadar: React.FC<Props> = ({ data }) => {
    // If no data, use mock data to show the visualization (demo mode)
    const chartData = (data && data.length > 0) ? data : [
        { ip: '192.168.1.5', count: 45 },
        { ip: '10.0.0.3', count: 32 },
        { ip: '172.16.0.1', count: 28 },
        { ip: 'BotNet-X', count: 15 },
        { ip: 'Scanner-Y', count: 12 }
    ];

    const option = {
        backgroundColor: 'transparent',
        color: ['#ef4444'],
        title: {
            text: 'Threat Radar',
            textStyle: { color: '#fff', fontSize: 14 },
            left: 'center',
            top: 10
        },
        tooltip: {},
        radar: {
            indicator: chartData.map((d: any) => ({ name: d.ip_address || d.ip, max: 100 })),
            shape: 'circle',
            splitNumber: 5,
            axisName: { color: '#ef4444' },
            splitLine: {
                lineStyle: {
                    color: [
                        'rgba(239, 68, 68, 0.1)', 'rgba(239, 68, 68, 0.2)',
                        'rgba(239, 68, 68, 0.4)', 'rgba(239, 68, 68, 0.6)',
                        'rgba(239, 68, 68, 0.8)', 'rgba(239, 68, 68, 1)'
                    ].reverse()
                }
            },
            splitArea: { show: false },
            axisLine: { lineStyle: { color: 'rgba(239, 68, 68, 0.5)' } }
        },
        series: [{
            name: 'Threat Intensity',
            type: 'radar',
            lineStyle: { width: 1, opacity: 0.5 },
            data: [
                {
                    value: chartData.map((d: any) => d.count),
                    name: 'Threat Count',
                    symbol: 'none',
                    itemStyle: { color: '#f87171' },
                    areaStyle: { opacity: 0.3 }
                }
            ]
        }]
    };

    return (
        <ReactECharts option={option} style={{ height: '300px', width: '100%' }} />
    );
};
