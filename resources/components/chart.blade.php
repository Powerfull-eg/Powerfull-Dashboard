@props(['title','dataLabels','dataValues'])

<div style="margin: 0 auto; max-width: 75%">
    <canvas id="chart-{{$title}}"></canvas>
</div>

<script src="/assets/js/chart.js"></script>

<script>
  
  
  setTimeout(() => {
    chartData = {
      "dataValues" : {{ $dataValues ? Js::from($dataValues) : null}},
      "title" : {{ $title ? Js::from($title) : null}},
      "dataLabels" : {{ $dataLabels ? Js::from($dataLabels) : null}}
    };
    prepareChart('chart-{{$title}}', chartData);
  }, 1000);
</script>