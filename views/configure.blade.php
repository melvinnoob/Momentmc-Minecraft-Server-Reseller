<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<!-- Software build by https://paymenter.org -->

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <script>
        if ("{{ config('settings::theme:snow') }}" == 1) {
            document.addEventListener("DOMContentLoaded", function() {
                window.snow();
            });
        }

        window.addEventListener('keydown', function(e) {
            var ctrlDown = true;
            var ctrlKey = 17,
                enterKey = 13;
            $(document).keydown(function(e) {
                if (e.keyCode == ctrlKey) ctrlDown = true;
                if (e.keyCode == enterKey && ctrlDown) {
                    if ($('#submit').length) {
                        $('#submit').click();
                    }
                }
            }).keyup(function(e) {
                if (e.keyCode == ctrlKey) ctrlDown = false;
            });
        });

        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
 function pterodactyl_control(action) {
    // Extract `invoice_id` from the current URL
    var pathSegments = window.location.pathname.split('/');
    var invoiceId = pathSegments[pathSegments.length - 1]; // Last segment of the URL

    // Backend endpoint
    var url = '{{ route("extensions.momentmcreseller.power") }}';

    var xhr = new XMLHttpRequest();
    xhr.open('POST', url);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.setRequestHeader('X-CSRF-TOKEN', '{{ csrf_token() }}'); // Include CSRF token

    xhr.onload = function () {
        if (xhr.status === 200) {
            var data = JSON.parse(xhr.responseText);
            if (data.status !== 'success') {
                alert('Error: ' + data.message);
            }
        } else {
            alert('An error occurred while trying to perform this action. Response: ' + xhr.responseText);
        }
    };

    xhr.onerror = function () {
        alert('An error occurred while trying to perform this action. Status: ' + xhr.status + ', Response: ' + xhr.responseText);
    };

    // Send POST data (invoice_id and action)
    xhr.send('_token={{ csrf_token() }}&invoice_id=' + invoiceId + '&action=' + action);
}

      
        function parseAnsiCodes(text) {
    const styles = {
        30: 'color: black;',
        31: 'color: red;',
        32: 'color: green;',
        33: 'color: yellow;',
        34: 'color: blue;',
        35: 'color: magenta;',
        36: 'color: cyan;',
        37: 'color: white;',
        40: 'background-color: black;',
        41: 'background-color: red;',
        42: 'background-color: green;',
        43: 'background-color: yellow;',
        44: 'background-color: blue;',
        45: 'background-color: magenta;',
        46: 'background-color: cyan;',
        47: 'background-color: white;',
        0: 'color: initial; background-color: initial;'
    };

    // Function to escape HTML special characters
    function escapeHtml(text) {
        return text
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    // Start with a span with no style
    let htmlText = '<span>';

    // Replace ANSI codes with HTML styles
    htmlText += escapeHtml(text).replace(/\x1b\[([0-9;]*)m/g, (match, p1) => {
        const codes = p1.split(';');
        let styleString = '';
        codes.forEach(code => {
            if (styles[code]) {
                styleString += styles[code];
            }
        });
        // Close the previous span and start a new one with the new style
        return styleString ? `</span><span style="${styleString}">` : '';
    });

    // Close the final span
    htmlText += '</span>';

    return htmlText;
}


    </script>

    @empty($title)
    <title>{{ config('app.name', 'Paymenter') }}</title>
    @else
    <title>{{ config('app.name', 'Paymenter') . ' - ' . ucfirst($title) }}</title>
    @endempty

    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;600;700&display=swap">
    @vite(['themes/' . config('settings::theme-active') . '/js/app.js', 'themes/' . config('settings::theme-active') . '/css/app.css'], config('settings::theme-active'))
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> <!-- Include Chart.js -->
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.2.0/fonts/remixicon.css" rel="stylesheet">

    @if (config('settings::app_logo'))
    <link rel="icon" href="{{ asset(config('settings::app_logo')) }}" type="image/png">
    @else
    <link rel="icon" type="image/png" sizes="32x32" href="/img/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/img/favicon-16x16.png">
    @endif

    <meta content="{{ ucfirst($title) ?? config('settings::seo_title') }}" property="og:title">
    <meta content="{{ $description ?? config('settings::seo_description') }}" property="og:description">
    <meta content="{{ $description ?? config('settings::seo_description') }}" name="description">
    <meta content='{{ $image ?? config('settings::seo_image') }}' property='og:image'>
    <meta name="theme-color" content="#5270FD">

    <style>
        .snow {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            z-index: 2;
            pointer-events: none;
        }

        .file-list {
            margin: 20px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .file-item {
            margin-bottom: 10px;
        }

        .file-item a {
            text-decoration: none;
            color: #007bff;
        }

        .file-item button {
            background-color: #dc3545;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 4px;
            cursor: pointer;
        }

        .file-item button:hover {
            background-color: #c82333;
        }

        .upload-form {
            margin: 20px;
        }

        .upload-form input[type="file"] {
            margin-bottom: 10px;
        }
        :root {
            --secondary-50: {{ config('settings::theme:secondary-50', '#ffffff') }};
            --secondary-100: {{ config('settings::theme:secondary-100', '#fafcff') }};
            --secondary-200: {{ config('settings::theme:secondary-200', '#ebeef3') }};
            --secondary-300: {{ config('settings::theme:secondary-300', '#bbbfd2') }};
            --secondary-400: {{ config('settings::theme:secondary-400', '#808498') }};
            --secondary-500: {{ config('settings::theme:secondary-500', '#606372') }};
            --secondary-600: {{ config('settings::theme:secondary-600', '#4d4f60') }};
            --secondary-700: {{ config('settings::theme:secondary-700', '#353741') }};
            --secondary-800: {{ config('settings::theme:secondary-800', '#1c1c20') }};
            --secondary-900: {{ config('settings::theme:secondary-900', '#000000') }};

            --primary-50: {{ config('settings::theme:primary-50', '#EDF0FF') }};
            --primary-100: {{ config('settings::theme:primary-100', '#C6DBFF') }};
            --primary-200: {{ config('settings::theme:primary-200', '#9BBEFB') }};
            --primary-300: {{ config('settings::theme:primary-300', '#799CD8') }};
            --primary-400: {{ config('settings::theme:primary-400', '#5270FD') }};
        }

        .dark {
            --secondary-50: {{ config('settings::theme:secondary-50-dark', '#1E202D') }};
            --secondary-100: {{ config('settings::theme:secondary-100-dark', '#313441') }};
            --secondary-200: {{ config('settings::theme:secondary-200-dark', '#404351') }};
            --secondary-300: {{ config('settings::theme:secondary-300-dark', '#4F525E') }};
            --secondary-400: {{ config('settings::theme:secondary-400-dark', '#656874') }};
            --secondary-500: {{ config('settings::theme:secondary-500-dark', '#7D8091') }};
            --secondary-600: {{ config('settings::theme:secondary-600-dark', '#AEB2C2') }};
            --secondary-700: {{ config('settings::theme:secondary-700-dark', '#CACBD2') }};
            --secondary-800: {{ config('settings::theme:secondary-800-dark', '#F1F1F1') }};
            --secondary-900: {{ config('settings::theme:secondary-900-dark', '#ffffff') }};
        }
        .console {
            width: 100%;
            height: 300px;
            background-color: #000;
            color: #fff;
            padding: 10px;
            overflow-y: scroll;
            font-family: monospace;
            border-radius: 6px 6px 0px 0px;

        }
        #command-input {
    background-color: var(--secondary-100); /* Light mode input */
    color: black;
    border: 1px solid var(--secondary-300);
    padding: 10px;
    border-radius: 6px;
}

.dark #command-input {
    background-color: #252527; /* Dark mode input */
    color: white;
    border: 1px solid #404351;
}
        .grid .grid-cols-12 .gap-4 {
            width: 100%;
            height: 100%;
            left: 5;
            margin-left: auto;
            margin-right: auto;
        }

        .console-contenante {
            width: 100%;
            height: auto;
            margin-top: 20px;

        }

        input {
            width: 100% ;
            padding: 10px;
            border: 1px solid var(--primary-color);
            border-radius: 4px;
            
        }

        .status-badge {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 600;
            color: #fff;
            text-transform: capitalize;
        }

        .status-badge.running {
            background-color: #4CAF50;
            /* Vert pour 'running' */
        }

        .status-badge.offline {
            background-color: #f44336;
            /* Rouge pour 'offline' */
        }

        .status-badge.starting {
            background-color: #FF9800;
            /* Orange pour 'starting' */
        }

        .status-badge.stopping {
            background-color: #FFEB3B;
            /* Jaune pour 'stopping' */
        }

        @keyframes pulse {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.1);
            }

            100% {
                transform: scale(1);
            }
        }

        .status-badge.starting,
        .status-badge.stopping {
            animation: pulse 2s infinite;
        }

        .lg:col-span-4 .md:col-span-6 .col-span-12 {
            width: 100%;
            height: 100%;
        }

        .grow {
            margin-left: auto;
            margin-right: auto;
        }

        .grid {
            margin-left: auto;
            margin-right: auto;
        }

        space {
            margin-left: 670px;
        }
        .resource-usage {
        padding: 20px;
        margin-top: 20px;
        border: 1px solid #ddd;
        border-radius: 4px;
        background-color: #f9f9f9;
    }
   
    .resource-item span:first-child {
        font-weight: bold;
        margin-right: 10px;
    }
    .list-ressources{
        display: flex;
    align-items: center;
    justify-content: space-around;
    align-content: flex-end;
    flex-wrap: nowrap;
    flex-direction: row;

    margin-top: 30px;

    }
.resource-item {
    background-color: var(--secondary-200); /* Light mode background */
    color: black; /* Light mode text */
    border-radius: 20px;
    width: 400px;
    height: 75px;
    text-align: center;
}

.dark .resource-item {
    background-color: #252527; /* Dark mode background */
    color: white; /* Dark mode text */
}

      .eula-popup {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.7);
        display: flex;
        justify-content: center;
        align-items: center;
    }
    .eula-popup-content {
        background-color: #fff;
        padding: 20px;
        border-radius: 8px;
        text-align: center;
        box-shadow: 0 0 15px rgba(0, 0, 0, 0.3);
    }
    .eula-popup button {
        margin: 10px;
        padding: 10px 20px;
        font-size: 16px;
        cursor: pointer;
    }

    </style>
</head>

<body class="font-sans bg-secondary-100 dark:bg-secondary-50 text-secondary-700">
    @if (config('settings::theme:snow') == 1)
    <canvas class="snow" id="snow" width="1920" height="1080"></canvas>
    @endif

    <div id="app" class="min-h-screen">
        <x-paymenter-update />
        @include('layouts.navigation')

        <div class="@if (config('settings::sidebar') == 1) flex md:flex-nowrap flex-wrap @endif">
            @include('layouts.subnavigation')
          
                <br>
                <main class="grow">
                    <div class="content">
                        <div class="max-w-[1650px] mx-auto block md:flex items-center gap-x-10 px-5">
                            <a href='{{ route("extensions.momentmcreseller.config", [$orderProduct, $invoiceId]) }}' 
   class="md:px-2 py-3 flex items-center gap-x-2 hover:text-secondary-800 duration-300">
   <i class="ri-gamepad-line"></i> Console
</a>

<a href='{{ route("extensions.momentmcreseller.files", [$orderProduct, $invoiceId]) }}' 
   class="md:px-2 py-3 flex items-center gap-x-2 hover:text-secondary-800 duration-300">
   <i class="ri-folder-line"></i> Files
</a>

 </a>

                        </div>
                        <div class="grid grid-cols-12 gap-4" style="width: 100%; height: 100%;">
                            <div class="lg:col-span-4 md:col-span-6 col-span-12" style=" width: 100%; height: 100%;">
                                <div class="content-box" style=" width: 1608px; height: 800;">
                                    <div style="display: flex; justify-content: space-between; align-items: center;">
                                    <item1>
                                    <a>IP: {{ $ip }}:{{ $port }} - Status:
                                        <span class="status-badge {{ strtolower($serverState) }}">
                                            {{ ucfirst($serverState) }}
                                        </span>
                                    </a>
</item1>
                                    @php
                                        $isRunning = $status === 'running';
                                        $isStopped = $status === 'offline';
                                        $isStarting = $status === 'starting';
                                        $isStopping = $status === 'stopping';
                                    @endphp
                                    <item3>
<button id="start-button" class="button button-success" onclick="pterodactyl_control('start')">Start Server</button>
                                    <button id="stop-button" class="button button-danger" onclick="pterodactyl_control('stop')">Stop Server</button>
                                    <button id="restart-button" class="button button-secondary" onclick="pterodactyl_control('restart')">Restart Server</button>
                                    <button id="kill-button" class="button button-danger" onclick="pterodactyl_control('kill')">Force Stop Server</button>
                                    </item2>

                                    </div>
                                    <div class='console-contenante'>



 </div>
                                        <input id="command-input" type="text" placeholder="Type a command...">
                                    <div class='list-ressources'>
                                    <div class="resource-item">
                                    <span>CPU Load:</span>
                                        </i><span id="cpu-usage">Loading...</span>
                                    </div>
                                    <div class="resource-item">
                                    <i class="ri-ram-fill"></i><span>RAM:</span>
                                        <span id="ram-usage">Loading...</span>
                                    </div>
                                    <div class="resource-item">
                                        </i><span>Storage:</span>
                                        <span id="storage-usage">Loading...</span>
                                    </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
            </div>
            </div>
            <div id="eula-popup" class="eula-popup" style="display:none;">
    <div class="eula-popup-content">
        <h2 style="color:black;">Accept the EULA</h2>
        <p style="color:black;">You must accept the EULA before continuing. Do you want to accept it?</p>
        <button id="accept-eula-btn" class="button button-success">Accept</button>
        <button id="decline-eula-btn" class="button button-danger">Deny</button>
    </div>
</div>

  </div>

    </main>


    <x-footer />

</body>

</html>

<script>
function scrollToBottom(orderProductId) {
    var consoleElement = document.getElementById('console-' + orderProductId);
    if (consoleElement) {
        consoleElement.scrollTop = consoleElement.scrollHeight;
    }
}

function storeConsoleMessage(orderProductId, message) {
    let messages = JSON.parse(localStorage.getItem('consoleMessages-' + orderProductId) || '[]');
    messages.push(message);
    localStorage.setItem('consoleMessages-' + orderProductId, JSON.stringify(messages));
}

function restoreConsoleMessages(orderProductId) {
    let consoleOutput = document.getElementById('console-' + orderProductId);
    
    // If the console element does not exist, create it dynamically
    if (!consoleOutput) {
        console.error("❌ Console element not found! Creating it dynamically...");

        const parentContainer = document.querySelector('.console-contenante'); // Change selector if needed
        if (!parentContainer) {
            console.error("🚨 Parent container for console not found!");
            return;
        }

        consoleOutput = document.createElement('div');
        consoleOutput.id = 'console-' + orderProductId;
        consoleOutput.classList.add('console'); // Ensure proper styling
        parentContainer.appendChild(consoleOutput);

        console.log("✅ Console element created successfully!");
    }

    // Restore console messages after ensuring the element exists
    const messages = JSON.parse(localStorage.getItem('consoleMessages-' + orderProductId) || '[]');
    messages.forEach(message => {
        const formattedOutput = parseAnsiCodes(message);
        const logDiv = document.createElement('div');
        logDiv.innerHTML = formattedOutput;
        consoleOutput.appendChild(logDiv);
    });

    scrollToBottom(orderProductId);
}


function connectWebSocket(orderProductId) {
    var pathSegments = window.location.pathname.split('/');
    var invoiceId = pathSegments[pathSegments.length - 1];

    console.log("\ud83d\udccc Extracted Order Product ID:", orderProductId);
    console.log("\ud83d\udccc Extracted Invoice ID:", invoiceId);

    if (!orderProductId || !invoiceId || isNaN(orderProductId) || isNaN(invoiceId)) {
        console.error("\u274c Invalid Order Product ID or Invoice ID.");
        alert("Error: Order Product ID or Invoice ID is invalid.");
        return;
    }

    fetch(`{{ route('extensions.momentmcreseller.console', ['product' => ':orderProductId', 'invoice_id' => ':invoice_id']) }}`
        .replace(':orderProductId', orderProductId)
        .replace(':invoice_id', invoiceId))
    .then(response => response.json())
    .then(data => {
        console.log("\ud83d\udc3c Full Backend Response:", JSON.stringify(data, null, 2));

        if (!data || data.status !== "success" || !data.data || !data.data.websocket_url || !data.data.websocket_url.data) {
            console.error("\u274c WebSocket Data Missing in API Response:", data);
            alert("Error: WebSocket data is missing. Check backend API.");
            return;
        }

        const socketUrl = data.data.websocket_url.data.socket;
        const token = data.data.websocket_url.data.token;
        const serverId = data.data.server_id;

        console.log("\ud83d\udd17 WebSocket URL:", socketUrl);
        console.log("\ud83d\udd11 WebSocket Token:", token);
        console.log("\ud83d\udda5\ufe0f Server ID:", serverId);

        const socket = new WebSocket(socketUrl);

        socket.onopen = function () {
            console.log("\u2705 WebSocket Connected!");
            socket.send(JSON.stringify({ event: "auth", args: [token] }));

            setTimeout(() => {
                socket.send(JSON.stringify({ event: "send logs", args: [] }));
                console.log("\ud83d\udc3c Requested Console Logs from Server");
            }, 1000);
        };

        socket.onmessage = function(event) {
            const message = JSON.parse(event.data);
            console.log("\ud83d\udce9 WebSocket Message:", message);
 if (message.event === "stats") {
        // Parse the JSON inside args[0]
        const stats = JSON.parse(message.args[0]);

        // Update resource values in the UI
        document.getElementById('cpu-usage').textContent = stats.cpu_absolute.toFixed(2) + "%";
        document.getElementById('ram-usage').textContent = (stats.memory_bytes / 1024 / 1024).toFixed(2) + " MB";
        document.getElementById('storage-usage').textContent = (stats.disk_bytes / 1024 / 1024).toFixed(2) + " MB";

        // Update server state badge
        const statusBadge = document.querySelector('.status-badge');
        if (statusBadge) {
            statusBadge.textContent = stats.state.charAt(0).toUpperCase() + stats.state.slice(1);
            statusBadge.className = "status-badge " + stats.state.toLowerCase();
        }

        // Update button states
        updateButtons(stats.state);
    }
            if (message.event === "console output") {
                const output = message.args[0];
                let consoleOutput = document.getElementById('console-' + orderProductId);

                if (!consoleOutput) {
                    console.error("\u274c Console element not found! Retrying in 500ms...");
                    let retryInterval = setInterval(() => {
                        consoleOutput = document.getElementById('console-' + orderProductId);
                        if (consoleOutput) {
                            console.log("\u2705 Console element found! Updating console.");
                            const formattedOutput = parseAnsiCodes(output);
                            const logDiv = document.createElement('div');
                            logDiv.innerHTML = formattedOutput;
                            consoleOutput.appendChild(logDiv);
                            scrollToBottom(orderProductId);
                            clearInterval(retryInterval);
                        }
                    }, 500);
                    return;
                }

                const formattedOutput = parseAnsiCodes(output);
                const logDiv = document.createElement('div');
                logDiv.innerHTML = formattedOutput;
                consoleOutput.appendChild(logDiv);
                scrollToBottom(orderProductId);

                storeConsoleMessage(orderProductId, output);

                // Handle EULA agreement logic
                if (output.includes("You need to agree to the EULA")) {
                    document.getElementById('eula-popup').style.display = 'flex';
                    document.getElementById('accept-eula-btn').addEventListener('click', function() {
                        console.log("\u2705 Command sent to accept the EULA.");
                        document.getElementById('eula-popup').style.display = 'none';

                        fetch(`{{ route('extensions.momentmcreseller.files.contents.save', ['product' => ':orderProductId', 'invoice_id' => ':invoice_id']) }}`
                            .replace(':orderProductId', orderProductId)
                            .replace(':invoice_id', invoiceId), {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({ file: '/eula.txt', content: 'eula=True' })
                        })
                        .then(response => response.json())
                        .then(data => {
                            const feedback = document.getElementById('feedback');
                            feedback.style.color = data.status === 'success' ? 'green' : 'red';
                            feedback.textContent = data.message;
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            document.getElementById('feedback').style.color = 'red';
                            document.getElementById('feedback').textContent = 'Error saving EULA file.';
                        });
                    });

                    document.getElementById('decline-eula-btn').addEventListener('click', function() {
                        console.log("User declined the EULA.");
                        document.getElementById('eula-popup').style.display = 'none';
                    });
                }
            }
        };

        socket.onerror = function (error) {
            console.error("⚠️ WebSocket Error:", error);
        };

        socket.onclose = function () {
            console.warn("⚠️ WebSocket Connection Closed.");
        };

        document.getElementById("command-input").addEventListener("keypress", function (event) {
            if (event.key === "Enter") {
                const command = event.target.value.trim();
                if (command.length > 0) {
                    socket.send(JSON.stringify({ event: "send command", args: [command] }));
                    event.target.value = "";
                }
            }
        });

    })
    .catch(error => {
        console.error("\u274c Fetch Error:", error);
        alert("Error retrieving WebSocket data: " + error.message);
    });
}

document.addEventListener("DOMContentLoaded", function () {
    var pathSegments = window.location.pathname.split('/');
    var orderProductId = pathSegments[pathSegments.length - 2];

    console.log("\ud83d\udccc Document fully loaded, starting WebSocket...");
    connectWebSocket(orderProductId);
    restoreConsoleMessages(orderProductId);
});



    function ucfirst(str) {
        if (!str) return str;
        return str.charAt(0).toUpperCase() + str.slice(1);
    }

    function updateButtons(status) {
        const startButton = document.getElementById('start-button');
        const stopButton = document.getElementById('stop-button');
        const restartButton = document.getElementById('restart-button');
        const killButton = document.getElementById('kill-button');

        const isRunning = status === 'running';
        const isStopped = status === 'stopped';
        const isStarting = status === 'starting';
        const isStopping = status === 'stopping';

        startButton.disabled = isRunning || isStarting;
        stopButton.disabled = !isRunning || isStopped || isStopping;
        killButton.disabled = isStopped;
    }
 
  
</script>





     


                
                      

    



