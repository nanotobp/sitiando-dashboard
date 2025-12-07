document.addEventListener('DOMContentLoaded', () => {
    if (typeof Chart === 'undefined') return;

    const payload = window.dashboardCharts || {};
    const labels = payload.labels || [];
    const revenue = payload.revenue || [];
    const orders = payload.orders || [];

    // REVENUE CHART (línea)
    const revenueCanvas = document.getElementById('revenueChart');
    if (revenueCanvas) {
        new Chart(revenueCanvas.getContext('2d'), {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Ingresos',
                    data: revenue,
                    tension: 0.35,
                    fill: true,
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });
    }

    // ORDERS CHART (barras)
    const ordersCanvas = document.getElementById('ordersChart');
    if (ordersCanvas) {
        new Chart(ordersCanvas.getContext('2d'), {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Órdenes',
                    data: orders,
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });
    }

    // Sparklines (simple: reutilizamos revenue y orders)
    const sparkRevenue = document.getElementById('spark-revenue');
    if (sparkRevenue) {
        new Chart(sparkRevenue.getContext('2d'), {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    data: revenue,
                    borderWidth: 1,
                    pointRadius: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    x: { display: false },
                    y: { display: false }
                }
            }
        });
    }

    const sparkOrders = document.getElementById('spark-orders');
    if (sparkOrders) {
        new Chart(sparkOrders.getContext('2d'), {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    data: orders,
                    borderWidth: 1,
                    pointRadius: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    x: { display: false },
                    y: { display: false }
                }
            }
        });
    }

    // Ticket promedio sparkline (placeholder: usamos revenue)
    const sparkTicket = document.getElementById('spark-ticket');
    if (sparkTicket) {
        new Chart(sparkTicket.getContext('2d'), {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    data: revenue,
                    borderWidth: 1,
                    pointRadius: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    x: { display: false },
                    y: { display: false }
                }
            }
        });
    }

    // Conversión sparkline (placeholder simple)
    const sparkConversion = document.getElementById('spark-conversion');
    if (sparkConversion) {
        const convData = orders.map(v => v > 0 ? 100 : 0);
        new Chart(sparkConversion.getContext('2d'), {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    data: convData,
                    borderWidth: 1,
                    pointRadius: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    x: { display: false },
                    y: { display: false }
                }
            }
        });
    }
});