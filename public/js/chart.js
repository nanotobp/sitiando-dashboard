document.addEventListener("DOMContentLoaded", () => {

    /* -------------------------------------------
       GRÁFICO: Revenue Growth (línea)
    ------------------------------------------- */
    const revenueCtx = document.getElementById("revenueChart");
    if (revenueCtx) {
        new Chart(revenueCtx, {
            type: "line",
            data: {
                labels: ["Ene","Feb","Mar","Abr","May","Jun","Jul","Ago","Sep","Oct","Nov","Dic"],
                datasets: [{
                    label: "Ingresos",
                    data: window.revenueData || [1200, 1500, 1800, 1700, 2100, 2500, 2400, 2600, 3000, 3200, 3500, 4000],
                    borderColor: "#3b82f6",
                    backgroundColor: "rgba(59,130,246,0.25)",
                    tension: 0.4,
                    fill: true,
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: {
                    x: { ticks: { color: "#777" }},
                    y: { ticks: { color: "#777" }}
                }
            }
        });
    }


    /* -------------------------------------------
       GRÁFICO: Retención de clientes (barras)
    ------------------------------------------- */
    const retentionCtx = document.getElementById("retentionChart");
    if (retentionCtx) {
        new Chart(retentionCtx, {
            type: "bar",
            data: {
                labels: ["Ene","Feb","Mar","Abr","May","Jun"],
                datasets: [{
                    label: "Retención",
                    data: window.retentionData || [70, 75, 68, 80, 82, 78],
                    backgroundColor: "#2563eb"
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: {
                    x: { ticks: { color: "#777" }},
                    y: { ticks: { color: "#777" }}
                }
            }
        });
    }

});
/* ===================================================
   SPARKLINES PARA TARJETAS KPI
=================================================== */

function createSparkline(id, data, color) {
    const ctx = document.getElementById(id);
    if (!ctx) return;

    new Chart(ctx, {
        type: "line",
        data: {
            labels: data.map((_, i) => i + 1),
            datasets: [{
                data,
                borderColor: color,
                backgroundColor: "transparent",
                borderWidth: 2,
                tension: 0.4,
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


// Datos simulados (luego podemos pasar reales desde el controller)
createSparkline("sparkUsuarios",    [5, 8, 6, 9, 12, 15, 14], "#3b82f6");
createSparkline("sparkProductos",   [2, 3, 4, 8, 6, 7, 9],   "#10b981");
createSparkline("sparkOrders",      [10, 9, 7, 8, 7, 6, 7],  "#ef4444");
createSparkline("sparkAffiliates",  [1, 3, 4, 6, 8, 12, 15], "#8b5cf6");
createSparkline("sparkVentasMes",   [200,300,250,400,350,500,600], "#f59e0b");
