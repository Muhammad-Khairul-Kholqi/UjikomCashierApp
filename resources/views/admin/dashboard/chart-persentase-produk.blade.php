<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        #noDataMessage {
            text-align: center;
            font-size: 1.2rem;
            color: #666;
            padding: 2rem;
        }
    </style>
</head>
<body>
    <div class="border p-5 rounded-lg w-full shadow-md">
        <div></div>
        <div id="noDataMessage" style="display: none; height: 400px;">Belum ada pembelian hari ini</div>
        <div class="h-[400px]" id="chartContainer">
            <canvas id="productSalesChart"></canvas>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const produkLabels = {!! json_encode($produkLabels) !!};
            const produkPersentase = {!! json_encode($produkPersentase) !!};
            const produkData = {!! json_encode($produkData) !!};

            if (!produkLabels || produkLabels.length === 0 || produkPersentase.reduce((a, b) => a + b, 0) === 0) {
                document.getElementById('chartContainer').style.display = 'none';
                document.getElementById('noDataMessage').style.display = 'block';
            } else {
                const ctxProductSales = document.getElementById('productSalesChart').getContext('2d');

                new Chart(ctxProductSales, {
                    type: 'pie',
                    data: {
                        labels: produkLabels,
                        datasets: [{
                            data: produkPersentase,
                            backgroundColor: [
                                'rgba(255, 99, 132, 0.7)',
                                'rgba(54, 162, 235, 0.7)',
                                'rgba(255, 206, 86, 0.7)',
                                'rgba(75, 192, 192, 0.7)',
                                'rgba(153, 102, 255, 0.7)',
                                'rgba(255, 159, 64, 0.7)',
                                'rgba(199, 199, 199, 0.7)',
                                'rgba(83, 102, 255, 0.7)',
                                'rgba(40, 159, 150, 0.7)',
                                'rgba(210, 105, 30, 0.7)'
                            ],
                            borderWidth: 2
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'right'
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        let label = context.label || '';
                                        let value = context.raw || 0;
                                        let jumlah = produkData[context.dataIndex] || 0;
                                        return label + ': ' + value + '% (' + jumlah + ' item)';
                                    }
                                }
                            }
                        }
                    }
                });
            }
        });
    </script>
</body>
</html>
