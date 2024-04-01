<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hinaguimitan Flood Monitoring</title>
    <link rel="stylesheet" type="text/css" href="webstyles.css">
    <link rel="stylesheet" type="text/css" href="resolution.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"/>
</head>
<body>
    <header class="header">
        <img id="logo" src="logo.png">
        <div id="location">
            <h3 class="title1">FLOOD WARNING SYSTEM</h3>
            <p class="title2"> Brgy. Hinaguimitan, Sision Surigao del Norte</p>
            <a id="login" style="--clr:#05f5d9" href="login.html"><span>LOGIN</span><i></i></a>
        </div>
    </header>

    <div id="col">
        <div class="col1">
                <table class="content-table">
                    <thead>
                      <tr>
                        <th>DATE AND TIME</th>
                        <th>WATER LEVEL</th>
                        <th>TEMPERATURE</th>
                        <th>HUMIDITY</th>
                      </tr>
                    </thead>
                    <tbody id="data-body"></tbody>
                </table>

        <div class="col2">
            <div id="data-result">  <h3>Water Level</h3>
                <div class="gauge1"> 
                    <div class="gauge__body">
                      <div class="gauge__fill"></div>
                      <div class="gauge__cover"></div>
                    </div>
                </div>
            </div>
            <div id="data-result"> <h3>Temperature</h3>
                <div class="gauge2">  
                    <div class="gauge__body">
                      <div class="gauge__fill"></div>
                      <div class="gauge__cover"></div>
                    </div>
                </div>
            </div>
            <div id="data-result"> <h3>Humidity</h3>
                <div class="gauge3"> 
                    <div class="gauge__body">
                      <div class="gauge__fill"></div>
                      <div class="gauge__cover"></div>
                    </div>
                </div>
            </div>
            <div id="info">
                <div class="displayed-text">
                    <h2>NOTICE:</h2>
                    <p id="displayedText"></p>
                </div>
            </div>
            <div id="info">
                <p id="map"><iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d62930.766698947904!2d125.45217158466654!3d9.666262112746287!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x33013959273d41b9%3A0x3d6f862380e0db2e!2sSison%2C%20Surigao%20del%20Norte!5e0!3m2!1sen!2sph!4v1704985725027!5m2!1sen!2sph" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe></p>
            </div><br>
        </div>
    </div>

    <script>
        async function fetchData() {
            try {
                const response = await fetch('https://sisonfloodmonitoring.000webhostapp.com/fetch_data_notice.php?getData=1');
    
                if (!response.ok) {
                    throw new Error(`HTTP error! Status: ${response.status}`);
                }
    
                const data = await response.json();
                document.getElementById('displayedText').textContent = data.displayed_text;
            } catch (error) {
                console.error('Error fetching data:', error.message);
            }
        }
    
        fetchData();
    </script>
    

    <script>
        const setGaugeValue1 = (gauge, value) => {
            value = Math.min(8, Math.max(0, value));
            const normalizedValue = value / 8;
            gauge.querySelector(".gauge__fill").style.transform = `rotate(${normalizedValue / 2}turn)`;
            gauge.querySelector(".gauge__cover").textContent = `${value} m`;
        };

        const setGaugeValue2 = (gauge, value) => {
            value = Math.min(50, Math.max(0, value));
            const normalizedValue = value / 50;
            gauge.querySelector(".gauge__fill").style.transform = `rotate(${normalizedValue / 2}turn)`;
            gauge.querySelector(".gauge__cover").textContent = `${value} °C`;
        };

        const setGaugeValue3 = (gauge, value) => {
            value = Math.min(100, Math.max(0, value));
            const normalizedValue = value / 100;
            gauge.querySelector(".gauge__fill").style.transform = `rotate(${normalizedValue / 2}turn)`;
            gauge.querySelector(".gauge__cover").textContent = `${value} %`;
        };

        const fetchDataAndUpdate = async () => {
            try {
                const response = await fetch('https://sisonfloodmonitoring.000webhostapp.com/fetch_data.php?getData=1');

                if (!response.ok) {
                    throw new Error(`HTTP error! Status: ${response.status}`);
                }

                const data = await response.json();

                // Sort fetched data in descending order based on timestamp
                data.sort((a, b) => new Date(b.timestamp) - new Date(a.timestamp));

                // Assuming the latest data is the first element
                const latestData = data[0];

                // Update Gauges
                const gaugeElement1 = document.querySelector(".gauge1");
                setGaugeValue1(gaugeElement1, latestData.waterlevel);

                const gaugeElement2 = document.querySelector(".gauge2");
                setGaugeValue2(gaugeElement2, latestData.temperature);

                const gaugeElement3 = document.querySelector(".gauge3");
                setGaugeValue3(gaugeElement3, latestData.humidity);

                // Update Table
                const latestTableData = data.slice(0, 60);
    
                document.getElementById('data-body').innerHTML = latestTableData.map(row => `
                    <tr>
                        <td>${row.timestamp}</td>
                        <td>${row.waterlevel} METER</td>
                        <td>${row.temperature} °C</td>
                        <td>${row.humidity} %</td>
                    </tr>
                `).join('');

            } catch (error) {
                console.error('Error fetching and updating data:', error);
            }
        };

        fetchDataAndUpdate();
        setInterval(fetchDataAndUpdate, 2000);
    </script>

    <div class="footer">
        <div id="contact">
            <h3 id="foot1">Contact<span> Us</span></h3>
            <p id="foot3"><i class="fa fa-map-marker"></i>Sison, Surigao del Norte</p>
            <p id="foot3"><i class="fa fa-phone"></i>0998-765-4321</p>
            <p id="foot4"><i class="fa fa-envelope"></i><a href="https://www.facebook.com/">info@sisonmunicipality.gov</a></p>
        </div>
        
        <div id="follow">
            <h3>Follow<span> Us</span></h3>
            <div class="wrapper">
                <div class="icon facebook">
                    <div class="tooltip">Facebook</div>
                    <a href="https://www.facebook.com/your-facebook-page-link" target="_blank">
                        <span><i class="fab fa-facebook-f"></i></span>
                    </a>
                </div>
                <div class="icon twitter">
                    <div class="tooltip">Twitter</div>
                    <a href="https://twitter.com/your-twitter-page-link" target="_blank">
                        <span><i class="fab fa-twitter"></i></span>
                    </a>
                </div>
                <div class="icon instagram">
                    <div class="tooltip">Instagram</div>
                    <a href="https://www.instagram.com/your-instagram-page-link" target="_blank">
                        <span><i class="fab fa-instagram"></i></span>
                    </a>
                </div>
                <div class="icon youtube">
                    <div class="tooltip">YouTube</div>
                    <a href="https://www.youtube.com/your-youtube-channel-link" target="_blank">
                        <span><i class="fab fa-youtube"></i></span>
                    </a>
                </div>
            </div>
        </div>
        
        <div id="about">
            <h3>About<span> Us</span></h3>
            <p>""Your source for real-time flood forecasts at Hinaguimitan River, Sison, Surigao del Norte, ensuring proactive monitoring and safety measures.""</p>
            <a class="rules" href="">Privacy Policy</a>
            <a class="rules" href="">Terms and Conditions</a>
        </div>
            <p id="vertical_line"></p>
            <p id="copyright">© 2023 Municipality of Sison. All rights reserved.</p>
        </div>
    </div>
</body>
</html>