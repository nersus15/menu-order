<div class="card">
    <div class="card-body">
        <p id="loading-bulanan">Loading</p>
        <canvas style="display: none;" id="grafik-bulanan"></canvas>
    </div>
</div>

<script>
    $(document).ready(function(){
        $.get(path + 'ws/dashboard/bulanan', function(res){
            $("#loading-bulanan").hide();
            $("#grafik-bulanan").show();
            var data = res.data;
            var config = {};
            if(data){
                var value = [];
                Object.keys(data.data).forEach(k => value.push(data.data[k]));
                console.log(value);
                config = {
                    labels: Object.keys(data.data),
                    datasets: [{
                        label: 'Pesanan',
                        data: value,
                        fill: false,
                        borderColor: 'rgb(75, 192, 192)',
                    }]
                }

                var ctx = document.getElementById('grafik-bulanan').getContext('2d');
                var myChart = new Chart(ctx, {
                    type: 'line',
                    data: config,
                    options: {
                        scaleShowValues: true,
                        scales: {
                            yAxes: [{
                            ticks: { min: 0 },
                            }], 
                            xAxes: [{
                            ticks: {
                                autoSkip: false
                            }
                            }]
                        }
                    }
                });
            }
        }).fail(function(){
            $("#loading-bulanan").text("Error");
        });
        

    });
</script>