<html>
    <head>
        <title>Upcoming rocket launches</title>
        <style>
            @font-face {
                font-family: "PtMono";
                src: url("assets/PTMono-Regular.ttf");
            }
            
            td {
                border-style: none none none none; border-width: 1px; border-color: #ffffff;
            }
            
            td, th {
                font-family: PtMono;
                font-size: 10pt;
                color: white;
                padding-left: 10px;
                padding-right: 10px;
                padding-top: 2px;
                padding-bottom: 2px;
            }
            body {
                background-color: black;
            }
            img {
                height: 20pt;
            }
            #logo {
                height: 20pt;
            }
            
            /* Tooltip container */
.tooltip {
  position: relative;
  display: inline-block;
}

/* Tooltip text */
.tooltip .tooltiptext {
  visibility: hidden;
  width: 120px;
  background-color: #555;
  color: #fff;
  text-align: center;
  padding: 5px 0;
  border-radius: 6px;

  /* Position the tooltip text */
  position: absolute;
  z-index: 1;
  top: -5px;
  left: 125%; 

  /* Fade in tooltip */
  opacity: 0;
  transition: opacity 0.3s;
}

/* Tooltip arrow */
.tooltip .tooltiptext::after {
  content: "";
  position: absolute;
    top: 50%;
    right: 100%;
    margin-top: -5px;
    border-width: 5px;
    border-style: solid;
    border-color: transparent #555 transparent transparent;
}

/* Show the tooltip text when you mouse over the tooltip container */
.tooltip:hover .tooltiptext {
  visibility: visible;
  opacity: 1;
}
        </style>
    </head>
</html>

<?php
$content = fopen("upcoming.json", "r");
$json_ll = stream_get_contents($content);
fclose($content);

$data = json_decode($json_ll);
print
'
<table>
    <tr>
        <th>Time to launch</th>
        <th>Launch time (UTC)</th>
        <th>Window</th>
        <th>Status</th>
        <th>Launch Service Provider</th>
        <th>Launch vehicle</th>
        <th>Mission name</th>
        <th>Pad</th>
        <th>Webcast</th>
    </tr>';
    $i = 1;
    foreach($data->results as $item) {
        if($item->mission->name != "") {
            $name = $item->mission->name;
        } 
        else {
            $name = explode(" | ", $item->name)[1];
        }
        $date = date_create_from_format('Y-m-d\TH:i:s\Z', $item->net);
        if($i % 2 == 1) {
            $bg = "#161616";
        }
        else {
            $bg = "#161616";
        }
        
        if($item->status->id == 1) {
            $bg = "#003300";
        }
        
        if($item->status->id == 3) {
            $bg = "#006600";
        }
        
        if($item->status->id == 5) {
            $bg = "#666600";
        }
        
        if($item->status->id == 6) {
            $bg = "#003333";
        }
        
        if($item->vidURLs[0]->url == "") {
            $viz = "hidden";
        }
        else {
            $viz = "shown";
        }
        
        $endWindow = date_create_from_format('Y-m-d\TH:i:s\Z', $item->window_end);
        $startWindow = date_create_from_format('Y-m-d\TH:i:s\Z', $item->window_start);
        $window = date_diff($startWindow, $endWindow);
        $interval = "";
        if($window->format('%a') > 0) {
            $interval .= $window->format('%ad ');
        }
        if($window->format('%H') > 0 || $window->format('%a') > 0) {
            $interval .= $window->format('%Hh ');
        }
        if($window->format('%I') > 0 || $window->format('%H') > 0 || $window->format('%a') > 0) {
            $interval .= $window->format('%Imin ');
        }
        if($window->format('%S') > 0 || $window->format('%I') > 0 || $window->format('%H') > 0 || $window->format('%a') > 0) {
            $interval .= $window->format('%Ss');
        }
        if($window->format('%S') == 0 && $window->format('%I') == 0 && $window->format('%H') == 0 && $window->format('%a') == 0) {
            $interval = "1s";
        }
        print '
        <tr style="background-color: '.$bg.';">
            <td align="right"><span id="cd'.$i.'"></span></td>
            <td>'.$date->format('Y-m-d H:i:s').'</td>
            <td align="right">'.$interval.'</td>
            <td>'.$item->status->name.'</td>
            <td align="center"><a href="'.$item->launch_service_provider->info_url.'" target="_blank"><div class="tooltip"><img id="logo" src="'.$item->launch_service_provider->logo_url.'" style="-webkit-filter: drop-shadow(0px 0px 1px white)
                  drop-shadow(0px 0px 1px white);
  filter: drop-shadow(0px 0px 1px white) 
          drop-shadow(0px 0px 1px white);"></img><span class="tooltiptext">'.$item->launch_service_provider->name.'</span></div></a></td>
            <td>'.$item->rocket->configuration->full_name.'</td>
            <td>'.$name.'</td>
            <td align="center"><a href="https://google.com/maps/place/'.$item->pad->latitude.','.$item->pad->longitude.'" target="_blank"><img src="assets/map_pin.png"></img></a></td>
            <td align="center"><a href="'.$item->vidURLs[0]->url.'" target="_blank" style="visibility: '.$viz.';"><img src="assets/play_button.png"></img></a></td>
        </tr>';
        echo '<script type="text/javascript">
            var countDownDate'.$i.' = new Date("'.$item->net.'").getTime();

            var x'.$i.' = setInterval(function() {

                var now = new Date().getTime();

                var distance'.$i.' = countDownDate'.$i.' - now;

                var days'.$i.' = Math.floor(distance'.$i.' / (1000 * 60 * 60 * 24));
                var hours'.$i.' = Math.floor((distance'.$i.' % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                var minutes'.$i.' = Math.floor((distance'.$i.' % (1000 * 60 * 60)) / (1000 * 60));
                var seconds'.$i.' = Math.floor((distance'.$i.' % (1000 * 60)) / 1000);
                
                var daysz'.$i.' = "";
                var hoursz'.$i.' = "";
                var minutesz'.$i.' = "";
                var secondsz'.$i.' = "";
                
                if (hours'.$i.' < 10) {
                    hoursz'.$i.' = "0";
                }
                
                if (minutes'.$i.' < 10 && (hours'.$i.' > 0 || days'.$i.' > 0)) {
                    minutesz'.$i.' = "0";
                }
                
                if (seconds'.$i.' < 10 && (minutes'.$i.' > 0 || hours'.$i.' > 0 || days'.$i.' > 0)) {
                    secondsz'.$i.' = "0";
                }

                document.getElementById("cd'.$i.'").innerHTML = days'.$i.' + "d " + hoursz'.$i.' + hours'.$i.' + "h " + minutesz'.$i.' + minutes'.$i.' + "m " + secondsz'.$i.' + seconds'.$i.' + "s ";

                if (distance'.$i.' < 10) {
                    clearInterval(x'.$i.');
                    document.getElementById("cd'.$i.'").innerHTML = "0s";
                }
            }, 1000);
        </script>';
        $i++;
    }
print '
</table>
';
?>
