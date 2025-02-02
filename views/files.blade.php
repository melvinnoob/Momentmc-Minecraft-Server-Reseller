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
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia(
                '(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark')
        } else {
            document.documentElement.classList.remove('dark')
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
            text-align: center;


        }

        .file-item {
            margin-bottom: 10px;
            border: #212433 solid 1px;
            background-color: #212433;
            display: flex;
            size: auto;
            height: 43px;
            justify-content: space-between;
            align-items: center;
            border-radius: 11px;
            padding-left: 5px;

        }

        .file-item a {
            text-decoration: none;
            color: #FFFFFFFF;
            size: 100%;
            

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
        }

        .grid .grid-cols-12 .gap-4 {
            width: 100%;
            height: 100%;
        }

        .console-contenante {
            width: 100%;
            height: auto;
            margin-top: 20px;
        }

        input {
            width: calc(100% - 20px);
            padding: 10px;
            border: 1px solid var(--primary-color);
            border-radius: 4px;
            margin-top: 10px;
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

                            

                        </div>

                        <br>
                        <div class="grid grid-cols-12 gap-4" style="
    width: 100%;
    height: 100%;
">
                            <div class="lg:col-span-4 md:col-span-6 col-span-12" style="
    width: 100%;
    height: 100%;
">
                                <div class="content-box" style="
    width: 1600px;
    height: 800;
">
                                    <div >
    
                                    <form id="uploadForm" style="display: flex;justify-content: center;align-items: center;flex-wrap: nowrap;flex-direction: row;align-content: stretch;">
        <input type="file" id="fileInput" name="file" required>
        <button type="submit" class="button button-primary">Upload</button>
    </form>
                                    </div>
                                    <br>
                                    <ul id="file-list">
                                        
                                    </ul>
                                </div>
                            </div>
                        </div>
                </main>
            </div>

        </div>
    </div>
    </div>
    </div>



    <x-footer />

</body>

</html>
<script>

// Function to list files in a given directory
var currentPath = "/";

// Function to extract product and invoice ID from the URL
function extractIdsFromUrl() {
    var pathSegments = window.location.pathname.split('/');
    var invoiceId = pathSegments[pathSegments.length - 1]; // Last segment
    var productId = pathSegments[pathSegments.length - 2]; // Second-to-last segment
    return { productId, invoiceId };
}

// Function to list files in a given directory
function listFiles(filePath = "/") {
    currentPath = filePath; // Update global path
    var ids = extractIdsFromUrl(); // Get product & invoice ID

    if (!ids.productId || !ids.invoiceId) {
        alert("Invalid URL structure. Product and Invoice ID are missing.");
        return;
    }

    var xhr = new XMLHttpRequest();
    xhr.open("GET", '{{ route("extensions.momentmcreseller.files.list", ["product" => ":product", "invoice_id" => ":invoice_id"]) }}'
        .replace(":product", ids.productId) 
        .replace(":invoice_id", ids.invoiceId)  + '?path=' + encodeURIComponent(filePath));

    xhr.onload = function() {
        console.log("Backend response:", xhr.responseText); // Log the full backend response

        if (xhr.status === 200) {
            var data = JSON.parse(xhr.responseText);
            if (data.status == 'success') {
                var fileList = document.getElementById('file-list');
                fileList.innerHTML = ''; 

                if (filePath !== '') {
                    var parentLinkItem = document.createElement('li');
                    parentLinkItem.className = 'file-item';

                    var parentLink = document.createElement('a');
                    parentLink.textContent = '  ..';
                    parentLink.href = '#'; 
                    parentLink.onclick = function() {
                        var parentPath = filePath.substring(0, filePath.lastIndexOf('/'));
                        listFiles(parentPath);
                    };

                    parentLinkItem.appendChild(parentLink);
                    fileList.appendChild(parentLinkItem);
                }

                data.data.data.forEach(function(file) {
                    var attributes = file.attributes; 
                    var listItem = document.createElement('li');
                    listItem.className = 'file-item'; 

                    if (!attributes.is_file) {
                        var itemGroup = document.createElement('div');
                        itemGroup.className = 'file-item-group'; 
                        var itemGroupB = document.createElement('div');
                        itemGroupB.className = 'file-item-group';

                        var fileIcon = document.createElement('i');
                        fileIcon.className = 'ri-folder-3-line';
                        listItem.appendChild(fileIcon);

                        var dirLink = document.createElement('a');
                        dirLink.textContent = '  ' + attributes.name + '/';
                        dirLink.href = '#'; 
                        dirLink.onclick = function() {
                            listFiles(filePath + '/' + attributes.name);
                        };

                        var deleteBtn = document.createElement('button');
                        deleteBtn.innerHTML  = '<i class="ri-delete-bin-line"></i>';
                        deleteBtn.className = 'button button-danger';
                        deleteBtn.onclick = function() {
                            deleteFile(filePath + '/' + attributes.name, attributes.name, ids.productId);
                        };

                        itemGroupB.appendChild(deleteBtn);
                        itemGroup.appendChild(fileIcon);
                        itemGroup.appendChild(dirLink);
                        listItem.appendChild(itemGroup);
                        listItem.appendChild(itemGroupB);
                    } else {
                        var itemGroupA = document.createElement('div');
                        itemGroupA.className = 'file-item-group';
                        var itemGroupB = document.createElement('div');
                        itemGroupB.className = 'file-item-group';

                        var fileIcon = document.createElement('i');
                        fileIcon.className = 'ri-file-text-line';
                        fileIcon.textContent = '  '

                        itemGroupA.appendChild(fileIcon);

                        var fileLink = document.createElement('a');
                        fileLink.textContent = attributes.name;
                        fileLink.href = '#'; 
                        itemGroupA.appendChild(fileLink);
                        listItem.appendChild(itemGroupA);

                        var downloadBtn = document.createElement('button');
                        downloadBtn.innerHTML  = '<i class="ri-download-cloud-fill"></i>';
                        downloadBtn.className = 'button button-secondary';
                        downloadBtn.onclick = function() {
                            downloadFile(filePath + '/' + attributes.name);
                        };
                        itemGroupB.appendChild(downloadBtn);

                        var deleteBtn = document.createElement('button');
                        deleteBtn.innerHTML  = '<i class="ri-delete-bin-line"></i>';
                        deleteBtn.className = 'button button-danger';
                        deleteBtn.onclick = function() {
                            deleteFile(filePath + '/' + attributes.name, filePath, ids.productId);
                        };
                        itemGroupB.appendChild(deleteBtn);

                        var editBtn = document.createElement('button');
                        editBtn.innerHTML = '<i class="ri-edit-line"></i>';
                        editBtn.className = 'button button-secondary';
                        editBtn.onclick = function() {
                            editFile(filePath + '/' + attributes.name);
                        };
                        itemGroupB.appendChild(editBtn);

                        listItem.appendChild(itemGroupB);
                    }

                    fileList.appendChild(listItem);
                });
            } else {
                alert(data.message);
            }
        } else {
            alert('An error occurred while retrieving the file list.');
        }
    };
    
    xhr.onerror = function() {
        alert('An error occurred while retrieving the file list.');
    };
    
    xhr.send();
}

// Call the function initially



function downloadFile(filePath) {
            var xhr = new XMLHttpRequest();
    var ids = extractIdsFromUrl(); // Get product & invoice ID

 xhr.open("GET", '{{ route("extensions.momentmcreseller.files.download", ["product" => ":product", "invoice_id" => ":invoice_id"]) }}'
        .replace(":product", ids.productId) 
        .replace(":invoice_id", ids.invoiceId)  + '?path=' + encodeURIComponent(filePath));
            xhr.responseType = 'blob';
            xhr.onload = function() {
                if (xhr.status === 200) {
                    var link = document.createElement('a');
                    link.href = window.URL.createObjectURL(xhr.response);
                    link.download = filePath;
                    link.click();
                } else {
                    alert('An error occurred while downloading the file.');
                }
            };
            xhr.onerror = function() {
                alert('An error occurred while downloading the file.');
            };
            xhr.send();
        }


     function editFile(filePath) {
    var ids = extractIdsFromUrl(); // Get product & invoice ID

    var editUrl = '{{ route("extensions.momentmcreseller.files.edit", ["product" => ":product", "invoice_id" => ":invoice_id"]) }}'
        .replace(":product", ids.productId) 
        .replace(":invoice_id", ids.invoiceId);

    // Append the file path correctly
    editUrl += '?path=' + encodeURIComponent(filePath);
    
    window.location.href = editUrl;
}
  
 function deleteFile(filePath, filePatha) {
    var ids = extractIdsFromUrl(); // Get product & invoice ID
    var xhr = new XMLHttpRequest();

    xhr.open('POST', '{{ route("extensions.momentmcreseller.files.delete", ["product" => ":product", "invoice_id" => ":invoice_id"]) }}'
        .replace(":product", ids.productId)
        .replace(":invoice_id", ids.invoiceId)
    );

    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onload = function() {
        if (xhr.status === 200) {
            var data = JSON.parse(xhr.responseText);
            if (data.status === 'success') {
                listFiles(currentPath);

            } else {
                alert("Failed to delete file.");
            }
        } else {
            alert("Server error while deleting the file.");
        }
    };
    xhr.onerror = function() {
        alert("Network error occurred.");
    };

    xhr.send('_token={{ csrf_token() }}&file=' + encodeURIComponent(filePath) + '&filepath=' + encodeURIComponent(filePatha));
}

document.getElementById('uploadForm').addEventListener('submit', async function(event) {
    event.preventDefault();

    var ids = extractIdsFromUrl(); // Get product & invoice ID
    const fileInput = document.getElementById('fileInput');
    const file = fileInput.files[0];

    if (!file) {
        alert('Please select a file.');
        return;
    }

    const reader = new FileReader();
    reader.onload = function(e) {
        const content = e.target.result;
        const Path = currentPath.endsWith('/') ? currentPath + file.name : currentPath + '/' + file.name; 

        fetch('{{ route("extensions.momentmcreseller.files.contents.save", ["product" => ":product", "invoice_id" => ":invoice_id"]) }}'
            .replace(":product", ids.productId)
            .replace(":invoice_id", ids.invoiceId),
        {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                file: Path,  // Correctly include the directory path
                content: content
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                listFiles(currentPath); // Refresh the file list in the correct directory
            } else {
                alert("Failed to upload file.");
            }
        })
        .catch(error => {
            alert("An error occurred during the upload.");
        });
    };

    reader.readAsText(file);
});


listFiles(currentPath);

</script>



</body>

</html>