<!DOCTYPE html>
<html lang="ru">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}" />
  <script src="https://code.highcharts.com/highcharts.js"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
  <title>Курс рубля к доллару</title>
  <link rel="stylesheet" href={{ asset('css/app.css') }}>
</head>

<body>
  <div class="find_block container">
    <span role="button" title="За какое количество дней вы хотите увидеть курс">Диапазон дней (?)</span>
    <div class="input-group">
      <input id="days_input" class="form-control" type="number" min="1" value="5" required>
      <button id="update_date_btn-js" class="btn btn-outline-primary">Просмотр</button>
    </div>
  </div>
  <div id="container" style="width:100%; height:400px;"></div>


  <script src="{{ asset('js/app.js') }}"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      // Настройка графика
      let chart_options = {
        chart: {
          type: 'line'
        },
        title: {
          text: 'Курс доллара'
        },
        xAxis: {
          categories: @json($dates)
        },
        yAxis: {
          title: {
            text: 'Стоимость одного доллара'
          }
        },
        series: [{
          name: 'Рублей за 1 доллар',
          data: @json($values)
        }]
      };
      const chart = Highcharts.chart('container', chart_options); // Построение графика


      // Обновление данных и графика
      $("#update_date_btn-js").on("click", function() {
        let days = $("#days_input").val();

        if (days != '') {
          let data = {
            days: days
          };

          $.ajax({
            url: '{{ route("update_date") }}',
            method: 'get',
            data: data,
            headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(data) {
              data = JSON.parse(data);

              // Обновление графика
              chart.update({
                series: [
                  {
                    'data': data['value']
                  }
                ],
                xAxis: {
                  categories: data['date']
                }
              });
            }
          })
        }
      })
    });
  </script>
</body>

</html>