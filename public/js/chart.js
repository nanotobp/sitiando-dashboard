document.addEventListener("DOMContentLoaded", () => {

    if (!window.dashboardCharts) {
        console.error("❌ No se encontró window.dashboardCharts");
        return;
    }

    const charts = window.dashboardCharts;

    /* ============================================================
       SPARKLINES (KPIs)
    ============================================================ */
    function renderSparkline(id, data) {
        const el = document.getElementById(id);
        if (!el) return;

        new Chart(el, {
            type: "line",
            data: {
                labels: data.map((_, i) => i),
                datasets: [
                    {
                        data: data,
                        borderColor: "#3B82F6",
                        borderWidth: 2,
                        pointRadius: 0,
                        tension: 0.35,
                    },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    x: { display: false },
                    y: { display: false },
                },
            },
        });
    }

    renderSparkline("sparkRevenue", charts.sparkRevenue);
    renderSparkline("sparkOrders", charts.sparkOrders);
    renderSparkline("sparkTicket", charts.sparkTicket);
    renderSparkline("sparkUsers", charts.sparkUsers);
    renderSparkline("sparkAffiliates", charts.sparkAffiliates);


    /* ============================================================
       GRÁFICO PRINCIPAL — INGRESOS (Revenue)
    ============================================================ */
    const chartRevenueEl = document.getElementById("chartRevenue");
    if (chartRevenueEl) {
        new Chart(chartRevenueEl, {
            type: "line",
            data: {
                labels: charts.revenue.map((item) => item.date),
                datasets: [
                    {
                        label: "Ingresos",
                        data: charts.revenue.map((item) => item.value),
                        borderColor: "#2563EB",
                        backgroundColor: "rgba(37, 99, 235, 0.15)",
                        borderWidth: 2,
                        fill: true,
                        tension: 0.3,
                    },
                ],
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: false,
                        grid: { color: "rgba(0,0,0,0.05)" },
                    },
                    x: {
                        grid: { display: false },
                    },
                },
            },
        });
    }


    /* ============================================================
       GRÁFICO PRINCIPAL — ÓRDENES POR DÍA
    ============================================================ */
    const chartOrdersEl = document.getElementById("chartOrders");
    if (chartOrdersEl) {
        new Chart(chartOrdersEl, {
            type: "bar",
            data: {
                labels: charts.orders.map((item) => item.date),
                datasets: [
                    {
                        label: "Órdenes",
                        data: charts.orders.map((item) => item.value),
                        backgroundColor: "rgba(59, 130, 246, 0.3)",
                        borderColor: "#3B82F6",
                        borderWidth: 1,
                        borderRadius: 4,
                    },
                ],
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: "rgba(0,0,0,0.05)" },
                    },
                    x: {
                        grid: { display: false },
                    },
                },
            },
        });
    }
});
