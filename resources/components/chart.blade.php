@props(['title','dataLabels','dataValues'])

<div style="margin: 0 auto; max-width: 75%">
    <canvas id="myChart"></canvas>
</div>

<script src="/assets/js/chart.js"></script>

<script>
  const ctx = document.getElementById('myChart');
  const dataValues = {{ $dataValues ? Js::from($dataValues) : null}};
  const title = {{ $title ?  Js::from($title) : '' }};
  const dataLabels = {{ $dataLabels ?  Js::from($dataLabels) : '' }};
  const labels = dataLabels;


  const data = {
    labels: labels,
    datasets: [{
      label: title,
      data: dataValues ?? [],
      backgroundColor: [
      'rgba(255, 99, 132, 0.2)',
      'rgba(255, 159, 64, 0.2)',
      'rgba(255, 205, 86, 0.2)',
      'rgba(75, 192, 192, 0.2)',
      'rgba(54, 162, 235, 0.2)',
      'rgba(153, 102, 255, 0.2)',
      'rgba(201, 203, 207, 0.2)',
      'rgba(155, 181, 232, 0.2)'
    ],
    borderColor: [
      'rgb(255, 99, 132)',
      'rgb(255, 159, 64)',
      'rgb(255, 205, 86)',
      'rgb(75, 192, 192)',
      'rgb(54, 162, 235)',
      'rgb(153, 102, 255)',
      'rgb(201, 203, 207)',
      'rgb(155, 181, 232)'
    ],
      borderWidth: 1
    }]
  };

  const config = {
    type: 'bar',
    data: data,
    options: {
      scales: {
        y: {
          beginAtZero: true
        }
      }
    },
  };
  new Chart(ctx,config);
</script>