@extends('super::layouts.main')

@push('header-css')
<style>
  .chart-block {
    margin-top: 20px;
    padding: 10px;
    border-radius: 10px;
    box-shadow: 2px 2px 2px 3px rgb(0 0 0 / 50%);
  }
  .chart-block h3 {
    text-transform: uppercase;
    margin-left: 10px;
    font-weight: 600;
  }
</style>
    
@endpush

@section('extra_top')
<div class="main-content container">
  <div class="row">
    <div class="col-lg-2 col-xs-6">
      <div class="count-blocks">
        <span class="stats">
          <i class="fa fa-user"></i>{{ $creators ?? 0 }}
        </span>
        <span class="stat-desc">Creators</span>
      </div>
    </div>
    <div class="col-lg-2 col-xs-6">
      <div class="count-blocks">
        <span class="stats">
          <i class="fa fa-users"></i> {{ $fans ?? 0}}
        </span>
        <span class="stat-desc">Users</span>
      </div>
    </div>
    <div class="col-lg-2 col-xs-6">
      <div class="count-blocks">
        <span class="stats">
          <i class="fa fa-ticket"></i> {{ $subscriptions ?? 0 }}
        </span>
        <span class="stat-desc">Subscriptions</span>
      </div>
    </div>
    <div class="col-lg-2 col-xs-6">
      <div class="count-blocks">
        <span class="stats">
          <i class="fa fa-gbp"></i> {{ $totalTips ?? 0}}
        </span>
        <span class="stat-desc">Total Tips</span>
      </div>
    </div>

    <div class="col-lg-2 col-xs-6">
      <div class="count-blocks">
        <span class="stats">
          <i class="fa fa-gbp"></i> {{  number_format($monthEarnings ?? 0, 2) }}
        </span>
        <span class="stat-desc">Month Income</span>
      </div>
    </div>
  </div>

  <div class="chart-block">
    <h3>pre register Stastics</h3>
    <div id="pre-data-chart"></div>
  </div>
</div>

@endsection
@push('footer-script')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
  const df = '{{ $default }}';
  const fan = '{{ $fan }}';
  const cr = '{{ $creator }}';
  const crp = '{{ $creatorPro }}';

  var options = {
    series: [{
      name: "Pre Registration",
      data: [df, fan, cr, crp].reverse()
    }],
    chart: {
      type: 'bar',
      height: 350
    },
    plotOptions: {
      bar: {
        borderRadius: 4,
        horizontal: true,
      }
    },
    dataLabels: {
      enabled: true,
      formatter: function(val, opt){
          const l = opt.w.config.series[opt.seriesIndex].data[opt.dataPointIndex].l
          return `${val}`;
      }
    },
    xaxis: {
      categories: ['Default', 'Fan', 'Creator', 'Creator Pro'].reverse(),
    },
    fill: {
      colors: ['#000000']
    }
  };

  var chart = new ApexCharts(document.querySelector("#pre-data-chart"), options);
  chart.render();
</script>
@endpush