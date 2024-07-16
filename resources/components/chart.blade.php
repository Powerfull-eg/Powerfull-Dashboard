@props(['data-labels','dataValues'])

<div style="margin: 0 auto; max-width: 75%">
    <canvas id="myChart"></canvas>
</div>

<script src="/assets/js/chart.js"></script>

<script>
    const ctx = document.getElementById('myChart');
    let datasets = {{ Js::from($datasets) }};

    console.log(datasets)
    new Chart(ctx, {
      type: 'bar',
      data: {
        labels: Object.keys(datasets),
        datasets: [{
          label: '# of Votes',
        //   data: [12, 19, 3, 5, 2, 3],
          borderWidth: 1
        }]
      },
      options: {
        scales: {
          y: {
            beginAtZero: true
          }
        }
      }
    });
</script>