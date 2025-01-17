<div class="card card-rounded">
  <div class="card-body">
    <div class="d-sm-flex justify-content-between align-items-start">
      <div>
       <h4 class="card-title card-title-dash">Rekap Pendapatan</h4>
       <p class="card-subtitle card-subtitle-dash">Perbandingan pendapatan bersih antara minggu ini dengan minggu sebelumnya</p>
      </div>
      <div id="performance-line-legend"></div>
    </div>
    <div class="chartjs-wrapper mt-5">
      <canvas id="performaneLine"></canvas>
      <canvas id="performance-line-legend"></canvas>
    </div>
  </div>
</div>

<script>
  // Ambil data dari Laravel ke JavaScript
  let labels = [];
  let thisWeekData = [];
  let lastWeekData = [];

  $.ajax({
    type: "GET",
    url: "{{ route('log.keuangan.chart') }}",
    success: function (response) {
      labels = response.labels;
      thisWeekData = response.thisWeekData;
      lastWeekData = response.lastWeekData;

      // Implementasi Chart.js
      if (document.getElementById("performaneLine")) {
          var graphGradient = document.getElementById("performaneLine").getContext('2d');
          var graphGradient2 = document.getElementById("performaneLine").getContext('2d');
          var saleGradientBg = graphGradient.createLinearGradient(5, 0, 5, 150);
          saleGradientBg.addColorStop(0, 'rgba(26, 115, 232, 0.5)');
          saleGradientBg.addColorStop(1, 'rgba(26, 115, 232, 0.1)');
          var saleGradientBg2 = graphGradient2.createLinearGradient(100, 0, 50, 150);
          saleGradientBg2.addColorStop(0, 'rgba(0, 208, 255, 0.19)');
          saleGradientBg2.addColorStop(1, 'rgba(0, 208, 255, 0.03)');

          var chartData = {
              labels: labels,
              datasets: [{
                  label: 'Minggu ini',
                  data: thisWeekData,
                  backgroundColor: saleGradientBg,
                  borderColor: [
                      '#1F3BB3',
                  ],
                  borderWidth: 1.5,
                  fill: true, // 3: no fill
                  pointBorderWidth: 1,
                  pointRadius: [4, 4, 4, 4, 4,4, 4, 4, 4, 4,4, 4, 4],
                  pointHoverRadius: [2, 2, 2, 2, 2,2, 2, 2, 2, 2,2, 2, 2],
                  pointBackgroundColor: ['#1F3BB3)', '#1F3BB3', '#1F3BB3', '#1F3BB3','#1F3BB3)', '#1F3BB3', '#1F3BB3', '#1F3BB3','#1F3BB3)', '#1F3BB3', '#1F3BB3', '#1F3BB3','#1F3BB3)'],
                  pointBorderColor: ['#fff','#fff','#fff','#fff','#fff','#fff','#fff','#fff','#fff','#fff','#fff','#fff','#fff',],
              },{
                label: 'Minggu sebelumnya',
                data: lastWeekData,
                backgroundColor: saleGradientBg2,
                borderColor: [
                    '#52CDFF',
                ],
                borderWidth: 1.5,
                fill: true, // 3: no fill
                pointBorderWidth: 1,
                pointRadius: [4, 4, 4, 4, 4,4, 4, 4, 4, 4,4, 4, 4],
                pointHoverRadius: [2, 2, 2, 2, 2,2, 2, 2, 2, 2,2, 2, 2],
                pointBackgroundColor: ['#52CDFF)', '#52CDFF', '#52CDFF', '#52CDFF','#52CDFF)', '#52CDFF', '#52CDFF', '#52CDFF','#52CDFF)', '#52CDFF', '#52CDFF', '#52CDFF','#52CDFF)'],
                  pointBorderColor: ['#fff','#fff','#fff','#fff','#fff','#fff','#fff','#fff','#fff','#fff','#fff','#fff','#fff',],
            }]
          };

          var chartOptions = {
            responsive: true,
            maintainAspectRatio: false,
              scales: {
                  yAxes: [{
                      gridLines: {
                          display: true,
                          drawBorder: false,
                          color:"#F0F0F0",
                          zeroLineColor: '#F0F0F0',
                      },
                      ticks: {
                        beginAtZero: false,
                        autoSkip: true,
                        maxTicksLimit: 4,
                        fontSize: 10,
                        color:"#6B778C"
                      }
                  }],
                  xAxes: [{
                    gridLines: {
                        display: false,
                        drawBorder: false,
                    },
                    ticks: {
                      beginAtZero: false,
                      autoSkip: true,
                      maxTicksLimit: 7,
                      fontSize: 10,
                      color:"#6B778C"
                    }
                }],
              },
              legend:false,
              legendCallback: function (chart) {
                var text = [];
                text.push('<div class="chartjs-legend"><ul>');
                for (var i = 0; i < chart.data.datasets.length; i++) {
                  text.push('<li>');
                  text.push('<span style="background-color:' + chart.data.datasets[i].borderColor + '">' + '</span>');
                  text.push(chart.data.datasets[i].label);
                  text.push('</li>');
                }
                text.push('</ul></div>');
                return text.join("");
              },

              elements: {
                  line: {
                      tension: 0.4,
                  }
              },
              tooltips: {
                  backgroundColor: 'rgba(31, 59, 179, 1)',
                  callbacks: {
                    label: function(context) {
                        // Ambil nilai y (data tooltip)
                        let value = context.yLabel;

                        // Format ke Rupiah
                        let formattedValue = new Intl.NumberFormat('id-ID', {
                            style: 'currency',
                            currency: 'IDR',
                            minimumFractionDigits: 0
                        }).format(value);

                        // Return label
                        return `${formattedValue}`;
                    }
                }
              }
          };

          var myChart = new Chart(graphGradient, {
              type: 'line',
              data: chartData,
              options: chartOptions,
          });

          document.getElementById('performance-line-legend').innerHTML = myChart.generateLegend();
      }
    }
  });

</script>

