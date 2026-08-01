import Chart from 'chart.js/auto';

document.addEventListener('DOMContentLoaded', () => {
    const data = window.__reportData || null;
    if (!data) return;

    const pieCtx = document.getElementById('pieChart');
    if (pieCtx && data.pieLabels.length) {
        new Chart(pieCtx, {
            type: 'pie',
            data: {
                labels: data.pieLabels,
                datasets: [{
                    data: data.pieValues,
                    backgroundColor: data.pieColors,
                    borderWidth: 2,
                    borderColor: '#ffffff',
                }],
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { boxWidth: 12, usePointStyle: true },
                    },
                    tooltip: {
                        callbacks: {
                            label: (context) => {
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percent = total > 0 ? ((context.parsed / total) * 100).toFixed(1) : '0';
                                return ` ${context.label}: Rp ${context.parsed.toLocaleString('id-ID')} (${percent}%)`;
                            },
                        },
                    },
                },
            },
        });
    }

    const barCtx = document.getElementById('barChart');
    if (barCtx) {
        new Chart(barCtx, {
            type: 'bar',
            data: {
                labels: data.barLabels,
                datasets: [
                    {
                        label: 'Pemasukan',
                        data: data.barIncome,
                        backgroundColor: '#16a34a',
                        borderRadius: 4,
                    },
                    {
                        label: 'Pengeluaran',
                        data: data.barExpense,
                        backgroundColor: '#dc2626',
                        borderRadius: 4,
                    },
                ],
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: (value) => `Rp ${value.toLocaleString('id-ID')}`,
                        },
                    },
                },
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { boxWidth: 12, usePointStyle: true },
                    },
                    tooltip: {
                        callbacks: {
                            label: (context) => ` ${context.dataset.label}: Rp ${context.parsed.y.toLocaleString('id-ID')}`,
                        },
                    },
                },
            },
        });
    }
});
