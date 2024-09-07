import React from "react";
import Chart from "react-apexcharts";

interface ChartProps {
    chart: any
}

// Mapping chart types to their respective configurations
const chartConfigMap: { [key: string]: (chart: any) => JSX.Element } = {
    static: (chart: any) => (
        <div className="bg-gray-800 p-4 rounded-lg">
            <h3 className="text-white text-lg font-bold">{chart.chart_name}</h3>
            <p className="text-white text-4xl font-bold mt-4">
                {chart.chart_values[0]}
            </p>
        </div>
    ),
    seriesChart: (chart: any) => (
        <div className="bg-gray-800 p-4 rounded-lg">
            <h3 className="text-white text-lg font-bold">{chart.chart_name}</h3>
            <Chart
                options={{
                    chart: {
                        id: "basic-bar"
                    },
                    xaxis: {
                        categories: chart.chart_labels
                    },
                }}
                series={[
                    {
                        name: "series-1",
                        data: chart.chart_values
                    }
                ]}
                type={chart.chart_type}
                width="500"
            />
        </div>
    ),
    nonSeriesChart: (chart: any) => (
        <div className="bg-gray-800 p-4 rounded-lg">
            <h3 className="text-white text-lg font-bold">{chart.chart_name}</h3>
            <Chart
                options={{
                    chart: {
                        id: "basic-bar"
                    },
                    labels: chart.chart_labels,
                }}
                series={chart.chart_values}
                type={chart.chart_type}
                width="500"
            />
        </div>
    )
};

// Helper function to determine the type of chart to render
const getChartType = (chartType: string) => {
    const seriesCharts = ['line', 'area', 'bar'];
    const nonSeriesCharts = ['pie', 'donut', 'radialBar'];

    if (seriesCharts.includes(chartType)) return 'seriesChart';
    if (nonSeriesCharts.includes(chartType)) return 'nonSeriesChart';
    return chartType; // Fallback for 'static' or other custom types
};

const ChartDetail = ({ chart }: ChartProps) => {
    // Determine the correct chart type from the map
    const chartRenderer = chartConfigMap[getChartType(chart.chart_type)];

    // Fallback for unsupported chart types
    return chartRenderer
        ? chartRenderer(chart)
        : (
            <div className="bg-gray-800 p-4 rounded-lg">
                <h3 className="text-white text-lg font-bold">{chart.chart_name}</h3>
                <p className="text-white">Unsupported chart type</p>
            </div>
        );
};

export default ChartDetail;
