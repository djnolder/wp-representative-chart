document.addEventListener('DOMContentLoaded', function () {

    if ( document.querySelector('.regions_wrapper') ) {
        let taxonomyDataString = document.querySelector('.regions_wrapper').getAttribute('data-taxonomy');
        let location_data = JSON.parse(taxonomyDataString);

        google.charts.load('current', {
            'packages':['geochart']
        });

        google.charts.setOnLoadCallback(drawRegionsMap);

        function drawRegionsMap() {

            var chartData = [];
            location_data.forEach(function(item) {
                chartData.push([item[0], item[1]]);
            });

            var data = google.visualization.arrayToDataTable([
                    ['State', 'Representative'],
                    ...chartData
            ]);

            // Custom Legend
            // var legend = document.createElement('div');
            // legend.classList.add('custom-legend');
            // legend.innerHTML = `
            //     <div><span style="background-color: #D3D3D3;"></span>No Data</div>
            //     <div><span style="background-color: #e0f7fa;"></span>1-2</div>
            //     <div><span style="background-color: #a8d1d5;"></span>2-4</div>
            //     <div><span style="background-color: #51979a;"></span>4-6</div>
            //     <div><span style="background-color: #38868a;"></span>6-8</div>
            //     <div><span style="background-color: #006064;"></span>10+</div>`;
                
            // document.querySelector('.regions_wrapper').appendChild(legend);

            var options = {
                region: 'US',
                displayMode: 'regions',
                resolution: 'provinces', // 'metro'
                legend: 'none',
                colorAxis: {
                    values: [0],
                    colors: ['#006064'],
                },
                animation: {
                    easing: 'inAndOut',
                    startup: true,
                    duration: 2500,
                }
            };

            var chart = new google.visualization.GeoChart(document.getElementById('regions_data'));

            // Add the event listener
            google.visualization.events.addListener(chart, 'select', function(e) {

                var selection = chart.getSelection();
                if (selection.length > 0) {
                    var selectedItem = selection[0];
                    var country = data.getValue(selectedItem.row, 0);
                    var popularity = data.getValue(selectedItem.row, 1);

                    jQuery.ajax({
                        type: 'POST',
                        url: repchart_ajax.ajax_url,
                        data: {
                            action: 'representative_ajax_handle',
                            nonce: repchart_ajax.nonce,
                            country : country,
                        },
                        beforeSend: function() {
                            document.querySelector('.page-loader').style.display = 'flex';
                        },
                        success: function(response) {      
                            
                            let totalPost = response.data.total_posts;
                            document.querySelector('.total-sales-reps').innerHTML = totalPost + ' Sales reps';
                            
                            if ( response.data.status === true ) {
                                document.querySelector('.rep-sales-rep-content').innerHTML = response.data.data;
                            } else {
                                document.querySelector('.rep-sales-rep-content').innerHTML = `
                                <div class="error-response">
                                    <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 256 256" enable-background="new 0 0 256 256" xml:space="preserve">
                                        <g>
                                            <g>
                                                <g>
                                                    <path fill="#000000" d="M98.7,8.1C57,12.7,22,44,12.6,85.1C2.2,130.1,24,176.4,65.4,197.2c21.1,10.5,46.2,13.3,68.9,7.5l7.2-1.8l3.4,2.2c8.5,5.6,16.7,8,27.1,8c7.9,0.1,14.3-1.4,20.9-4.7l3.7-1.8l21.1,21l21.1,21.1l2.3-0.4c3-0.5,4-1.5,4.5-4.5l0.4-2.3l-20.5-20.5l-20.4-20.5l3.5-4.2c9.4-11.4,13.2-26.1,10.3-40.3c-1.3-6.4-5.4-15.4-9.1-20.1l-2.7-3.3l0.9-4c0.5-2.2,1.3-6.8,1.6-10.1c4.6-41.4-17.4-81.5-54.8-100.2C137.7,9.4,117.9,5.9,98.7,8.1z M105,47.7v29.5h-19h-19l1.2-5.6c2.5-11.5,7.3-24,12.7-33c4.4-7.4,12.7-15.7,18.3-18.3c2.3-1.1,4.5-2,4.9-2C104.8,18.2,105,24.5,105,47.7z M120.6,20.1c13.5,6.5,25.9,26.9,31.3,51.5l1.2,5.6h-19h-19V47.6c0-21.1,0.2-29.5,0.7-29.5C116.3,18.2,118.4,19,120.6,20.1z M77.6,25.9c-8.9,10.9-15.4,26.9-20.4,49.8l-0.3,1.5H41H25.2l2.2-5.1c7.4-17.3,20.9-32.5,37.7-42.5c3.7-2.2,14.1-7,14.5-6.6C79.7,23,78.8,24.4,77.6,25.9z M154.8,29.4c16.9,9.8,30.6,25.3,38,42.7l2.2,5.1h-15.8h-15.9l-0.3-1.5c-3.5-16.1-6.2-24.4-11.5-35c-2.2-4.6-5.6-10.3-7.5-12.9c-1.9-2.5-3.5-4.8-3.5-4.9C140.5,22.3,150.6,26.9,154.8,29.4z M55.1,89.9c-0.6,3.5-0.6,31.7,0,35.2l0.4,2.7H38.8H22.1l-0.8-5.3c-0.5-2.9-0.9-9.6-0.9-15c0-5.4,0.4-12.2,0.9-15.1l0.8-5.2h16.7h16.7L55.1,89.9z M105,107.6v20.3H85.4H65.8l-0.5-2.4c-0.6-3-0.6-32.7,0-35.8l0.5-2.3h19.6H105V107.6L105,107.6z M154.9,89.6c0.3,1.3,0.5,8.6,0.5,16l0.1,13.6l-4.6,2.3c-2.5,1.3-5.8,3.3-7.2,4.3l-2.7,2H128h-12.9v-20.3V87.3h19.7h19.6L154.9,89.6z M198.9,91.8c1.2,7.2,1.5,19.3,0.6,26.4c-0.4,3.6-1,6.4-1.2,6.3c-0.2-0.1-2.5-1.3-5.1-2.6c-6.7-3.3-13.4-4.9-20.9-4.9h-6.2l-0.4-13.5c-0.3-7.4-0.6-14.1-0.8-14.9l-0.4-1.4h16.7h16.8L198.9,91.8z M183.7,129.1c15.6,5.3,26,19.7,26,35.8c0,10.8-3.6,19.4-11.4,26.9c-5,4.9-10,8.1-15.8,9.8c-20.7,6.1-42.7-6.8-47.7-28.1c-1.1-4.7-1-12.9,0.3-17.8c2.5-9.6,10.1-19.5,18.6-24C162.6,127,174.5,126,183.7,129.1z M57.1,139.4c1.7,7.8,3.8,15.8,5.5,20.8c2.7,8.2,9.4,21.6,13.6,27.1c1.9,2.5,3.5,4.8,3.5,4.9c0,0.5-10.4-4.2-14.5-6.6c-16.7-9.7-30.7-25.5-38-42.7l-2-4.9H41h15.9L57.1,139.4z M105,167.6V197l-1.4-0.4c-6.8-2-15.2-9-20.8-17.3c-6.2-9.3-11.7-22.9-14.5-35.8l-1.2-5.5h19h19L105,167.6L105,167.6z M129.4,141.9c-7,13.1-7.8,28.4-2,42.3l2,4.8l-2.4,2.1c-2.3,2-7.3,4.7-10.3,5.6l-1.5,0.4v-29.5V138h8.2h8.2L129.4,141.9z"/>
                                                </g>
                                            </g>
                                        </g>
                                    </svg>
                                    ${response.data.message}
                                </div>`;
                            }
                        },
                        complete: function() {
                            document.querySelector('.page-loader').style.display = 'none';
                        }
                    });
                }
            });
            
            chart.draw(data, options);
        }
    }
});
