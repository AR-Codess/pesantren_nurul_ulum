// Import Chart.js dari package npm
import { Chart } from "chart.js/auto";

// Variable untuk menyimpan instance chart
let lunasPaymentChartInstance = null;

/**
 * Inisialisasi chart untuk dashboard admin
 */
export function initDashboardCharts() {
    // console.log("Initializing dashboard charts...");

    // Chart untuk Pembayaran Lunas Perbulan
    initLunasPaymentChart();
}

/**
 * Chart untuk pembayaran lunas per bulan
 */
function initLunasPaymentChart() {
    // console.log("Initializing lunas payment chart...");
    const chartElement = document.getElementById("lunasPaymentChart");
    // console.log("Chart element found:", chartElement);

    if (!chartElement) return;

    // Hapus chart sebelumnya jika ada untuk menghindari duplikasi
    if (lunasPaymentChartInstance) {
        lunasPaymentChartInstance.destroy();
        lunasPaymentChartInstance = null;
    }

    fetch("/api/dashboard/lunas-payments")
        .then((response) => {
            // console.log("API response status:", response.status);
            return response.json();
        })
        .then((data) => {
            // console.log("Lunas payment data:", data);
            // Buat chart baru dan simpan instance-nya
            lunasPaymentChartInstance = new Chart(chartElement, {
                type: "bar",
                data: {
                    labels: data.labels,
                    datasets: [
                        {
                            label: "Total Pembayaran Lunas",
                            data: data.values,
                            backgroundColor: "rgba(59, 130, 246, 0.6)", // Warna biru
                            borderColor: "rgba(59, 130, 246, 1)", // Border warna biru
                            borderWidth: 1,
                            borderRadius: 4, // Membuat ujung bar menjadi sedikit melengkung
                            maxBarThickness: 60, // Membatasi ketebalan bar
                            barPercentage: 0.7, // Persentase lebar bar
                        },
                    ],
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: true,
                            position: "top",
                            labels: {
                                boxWidth: 12,
                                usePointStyle: true,
                                pointStyle: "circle",
                                font: {
                                    family: "Figtree, sans-serif", // Font yang sama dengan UI
                                },
                            },
                        },
                        tooltip: {
                            backgroundColor: "rgba(255, 255, 255, 0.9)",
                            titleColor: "#1e40af",
                            bodyColor: "#334155",
                            borderColor: "rgba(59, 130, 246, 0.3)",
                            borderWidth: 1,
                            padding: 10,
                            boxWidth: 10,
                            usePointStyle: true,
                            callbacks: {
                                label: function (context) {
                                    return (
                                        "Total: Rp " +
                                        context.raw.toLocaleString("id-ID")
                                    );
                                },
                            },
                        },
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0,
                                font: {
                                    family: "Figtree, sans-serif",
                                },
                                color: "#64748b",
                                callback: function (value) {
                                    return (
                                        "Rp " + value.toLocaleString("id-ID")
                                    );
                                },
                            },
                            grid: {
                                color: "rgba(226, 232, 240, 0.6)",
                            },
                        },
                        x: {
                            ticks: {
                                font: {
                                    family: "Figtree, sans-serif",
                                },
                                color: "#64748b",
                            },
                            grid: {
                                display: false,
                            },
                        },
                    },
                    animation: {
                        duration: 1000,
                        easing: "easeOutQuart",
                    },
                },
            });
            // console.log("Lunas payment chart created successfully");
        })
        .catch((error) => {
            console.error("Error loading lunas payment data:", error);
            // Display a message in the chart element if there's an error
            if (chartElement) {
                const ctx = chartElement.getContext("2d");
                ctx.font = "14px Arial";
                ctx.fillStyle = "red";
                ctx.fillText(
                    "Error loading chart data. Check console for details.",
                    10,
                    50
                );
            }
        });
}

/**
 * Fungsi untuk meng-handle perubahan pada filter tahun.
 * Menggunakan event delegation agar tetap berfungsi setelah update Livewire.
 */
function handleYearFilterChange(event) {
    // Pastikan event berasal dari elemen yang kita inginkan
    if (event.target && event.target.id === "lunasYearFilter") {
        const selectedYear = event.target.value;
        // console.log(`Year selected: ${selectedYear}. Fetching new data...`);

        // Fetch data berdasarkan tahun yang dipilih
        fetch(`/api/dashboard/lunas-payments?tahun=${selectedYear}`)
            .then((response) => response.json())
            .then((data) => {
                if (lunasPaymentChartInstance) {
                    // Update data chart
                    lunasPaymentChartInstance.data.labels = data.labels;
                    lunasPaymentChartInstance.data.datasets[0].data =
                        data.values;

                    // Perbarui (refetch) chart
                    lunasPaymentChartInstance.update();
                    // console.log("Chart updated successfully.");
                } else {
                    // console.warn("Chart instance not found for update.");
                }
            });
        // .catch((error) => console.error("Error updating chart:", error));
    }
}

// Hapus event listener lama jika ada untuk mencegah duplikasi
document.removeEventListener("change", handleYearFilterChange);

// Tambahkan satu event listener ke document
document.addEventListener("change", handleYearFilterChange);

// Panggil initDashboardCharts saat halaman pertama kali dimuat
document.addEventListener("DOMContentLoaded", initDashboardCharts);

// Panggil kembali saat Livewire selesai navigasi (misalnya setelah redirect)
document.addEventListener("livewire:navigated", initDashboardCharts);

// Inisialisasi chart ketika dokumen sudah siap
document.addEventListener("DOMContentLoaded", initDashboardCharts);

// Inisialisasi ulang chart ketika Livewire update
document.addEventListener("livewire:navigated", initDashboardCharts);
document.addEventListener("livewire:load", () => {
    // Listen untuk Livewire load event
    if (window.Livewire) {
        // Setelah setiap update
        window.Livewire.hook("message.processed", () => {
            initDashboardCharts();
        });
    }
});
