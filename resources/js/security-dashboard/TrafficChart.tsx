import React from 'react';
import ReactECharts from 'echarts-for-react';

interface Props {
    data: any[];
}

export const TrafficChart: React.FC<Props> = ({ data }) => {
    if (!data || data.length === 0) return <div className="text-gray-500 h-64 flex items-center justify-center">No Traffic Data</div>;

    const hours = data.map(d => d.time_slot);
    const blocked = data.map(d => parseInt(d.blocked));
    const allowed = data.map(d => parseInt(d.allowed));

    const option = {
        backgroundColor: 'transparent',
        tooltip: {
            trigger: 'axis',
            axisPointer: { type: 'cross' }
        },
        legend: {
            data: ['Allowed Requests', 'Blocked Threats'],
            textStyle: { color: '#ccc' }
        },
        grid: {
            left: '3%',
            right: '4%',
            bottom: '3%',
            containLabel: true
        },
        xAxis: {
            type: 'category',
            boundaryGap: false,
            data: hours,
            axisLine: { lineStyle: { color: '#555' } },
            axisLabel: { color: '#aaa' }
        },
        yAxis: {
            type: 'value',
            splitLine: { lineStyle: { color: '#333' } },
            axisLine: { lineStyle: { color: '#555' } },
            axisLabel: { color: '#aaa' }
        },
        series: [
            {
                name: 'Allowed Requests',
                type: 'line',
                smooth: true,
                showSymbol: false,
                lineStyle: { width: 2, color: '#3b82f6' },
                areaStyle: {
                    color: {
                        type: 'linear', x: 0, y: 0, x2: 0, y2: 1,
                        colorStops: [{ offset: 0, color: 'rgba(59, 130, 246, 0.5)' }, { offset: 1, color: 'rgba(59, 130, 246, 0.1)' }]
                    }
                },
                data: allowed
            },
            {
                name: 'Blocked Threats',
                type: 'line',
                smooth: true,
                showSymbol: false,
                lineStyle: { width: 2, color: '#ef4444' },
                areaStyle: {
                    color: {
                        type: 'linear', x: 0, y: 0, x2: 0, y2: 1,
                        colorStops: [{ offset: 0, color: 'rgba(239, 68, 68, 0.5)' }, { offset: 1, color: 'rgba(239, 68, 68, 0.1)' }]
                    }
                },
                data: blocked
            }
        ]
    };

    return (
        <ReactECharts option={option} style={{ height: '300px', width: '100%' }} />
    );
};
