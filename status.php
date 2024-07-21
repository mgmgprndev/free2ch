<?php
if(isset($_GET["getdata"]) && $_GET["getdata"] == "1" ){

    header('Content-Type: application/json');

    function getCPUUsage() {
        $load = sys_getloadavg();
        $cores = 2;
        return ($cores > 0) ? ($load[0] * 100 / $cores) : 0;
    }
    

    function getRAMUsage() {
        $free = shell_exec('free -m');
        $lines = explode("\n", $free);
        $data = preg_split('/\s+/', $lines[1]);
        $total = $data[1];
        $used = $data[2];
        return [
            'total' => $total,
            'used' => $used
        ];
    }

    function getDiskUsage() {
        $total = disk_total_space('/');
        $free = disk_free_space('/');
        $used = $total - $free;
        return [
            'total' => $total / (1024 ** 2),
            'used' => $used / (1024 ** 2)
        ];
    }

    function getUptime() {
        $uptime = shell_exec('uptime -p');
        return $uptime ? trim($uptime) : 'N/A';
    }

    $response = [
        'CPU_Load' => getCPUUsage(),
        'RAM' => getRAMUsage(),
        'Disk' => getDiskUsage(),
        'Uptime' => getUptime()
    ];

    echo json_encode($response);


    exit;
}
?>

<meta charset="utf-8">
<style>
    * { margin: 0; padding: 0; box-sizing: border-box; }

    body { 
        background-color: black;
    }

    .status {
        max-width: 30rem;
        max-height: 100%;
        width: 100%;
        height: 100%;
        background-color: white;
        display: flex;
        flex-direction: column;
        padding: 1.25rem;
        gap: 1.25rem;

        position: relative;
    }

    .data {
        width: 100%;
        overflow: auto; 
        background-color: whitesmoke;
        border: 1px gray solid;
        border-radius: 5px;
        padding: 1.25rem;
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
    }

    .data_vid {
        width: 100%;
        aspect-ratio: 16 / 9;
        background-color: whitesmoke;
        border: 1px gray solid;
        border-radius: 5px;
    }

    .bar {
        height: 1.25rem;
        width: 100%;
        background-color: lightgray;
        border-radius: 5px;
        overflow: hidden;
    }

    .inbar {
        height: 1.25rem;
        background-color: lime;
    }

    .flex {
        display: flex;
        flex-direction: row;
        width: 100%;
    }


    .p_er {
        position: absolute;
        bottom: 0;
        left: 0;
        padding: 1.25rem;
        width: 100%;
    }

    .error {
        width: 100%;
        overflow: auto; 
        background-color: red;
        border: 1px gray solid;
        color: white;
        border-radius: 5px;
        padding: 1.25rem;
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
        box-sizing: border-box;
    }

</style>

<meta name="viewport" content="width=device-width,initial-scale=1">
<title>サーバーステータス</title>

<body>
    <div class="status">
        <h1 style="text-align: center;">サーバーステータス</h1>
        <div class="data">
            <div class="flex">
                <p>CPU</p>
                <p style="margin-left: auto;" id="cpu_percent"></p>
            </div>
            <div class="bar">
                <div class="inbar" id="cpu_bar"></div>
            </div>
        </div>

        <div class="data">
            <div class="flex">
                <p>RAM</p>
                <p style="margin-left: auto;" id="ram_usage"></p>
            </div>
            <div class="bar">
                <div class="inbar" id="ram_bar"></div>
            </div>
        </div>

        <div class="data">
            <div class="flex">
                <p>Disk</p>
                <p style="margin-left: auto;" id="disk_usage"></p>
            </div>
            <div class="bar">
                <div class="inbar" id="disk_bar"></div>
            </div>
        </div>

        <div class="data">
            <p>Uptime</p>
            <p style="font-weight: 800;" id="uptime"></p>
        </div>

        <p style="text-align: center;">ふりー2ちゃんねるを支えるサーバーさんですよ...</p>




        <video class="data_vid" autoplay loop muted>
          <source src="https://img.free2ch.net/?img=aHR0cHMlM0ElMkYlMkZmcmVlMmNoLm5ldCUyRmJsdWUtYXJjaGl2ZS5tcDQ=" type="video/mp4" />
        </video>

        <div class="p_er" style="display: none;" id="error">
            <div class="error">
                <p>ERROR</p>
                <p id="error_text"></p>
            </div>
        </div>
    </div>
</body>

<script>
setInterval(() => {
    get();
}, 1000);

function get() {
    function mbToGb(mb) {
        const gb = mb / 1024;
        return parseFloat(gb.toFixed(1));
    }

    function fix(inp) {
        return parseFloat(inp.toFixed(1));
    }

    fetch("/status.php?getdata=1&byps=" + [...Array(16)].map(() => (Math.random() * 16 | 0).toString(16)).join('')).then((response) => response.json()).then((data) => {
        document.getElementById('cpu_percent').innerHTML =  fix(data["CPU_Load"]) + "%";
        document.getElementById('cpu_bar').style.width = data["CPU_Load"] + "%";

        var RamUse = data["RAM"]["used"];
        var RamTotal = data["RAM"]["total"];
        var RamPercent = (RamUse / RamTotal) * 100;
        document.getElementById('ram_usage').innerHTML = mbToGb(RamUse) + "Gb/" + mbToGb(RamTotal) + "Gb (" + fix(RamPercent) + ")%";
        document.getElementById('ram_bar').style.width = RamPercent + "%";

        var DiskUse = data["Disk"]["used"];
        var DiskTotal = data["Disk"]["total"];
        var DiskPercent = (DiskUse / DiskTotal) * 100;
        document.getElementById('disk_usage').innerHTML = mbToGb(DiskUse) + "Gb/" + mbToGb(DiskTotal) + "Gb (" + fix(DiskPercent) + "%)";
        document.getElementById('disk_bar').style.width = DiskPercent + "%";

        document.getElementById('uptime').innerHTML = data["Uptime"].replace("up","");

        document.getElementById('error').style.display = "none";

        document.querySelectorAll(".inbar").forEach((ib) => {
            var i = parseInt(ib.style.width.replace("%",""));
            var color = "lime";
            if( i > 30 ) {
                color = "green";
            }
            if( i > 50 ) {
                color = "yellow";
            }
            if( i > 70 ) {
                color = "orange";
            }
            if( i > 90 ) {
                color = "red";
            }
            ib.style.backgroundColor = color;
        });
    }).catch((error) => {
        document.getElementById('error').style.display = "";
        document.getElementById('error_text').innerHTML = error;
    });
}

get();
</script>