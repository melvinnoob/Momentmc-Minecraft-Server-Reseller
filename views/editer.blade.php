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
    <link type="application/json+oembed"
        href="{{ url('/') }}/manifest.json?title={{ config('app.name', 'Paymenter') }}&author_url={{ url('/') }}&author_name={{ config('app.name', 'Paymenter') }}" />
    <meta name="twitter:card" content="@if (config('settings::seo_twitter_card')) summary_large_image @endif">
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
        #command-input{
            border-radius: 0px 0px 6px 6px; 
            background-color: #404351;

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
    .resource-item {
        margin-bottom: 10px;
        font-size: 16px;
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
.resource-item{
    background-color: #252527;
    border-radius: 20px;
    width: 400px;
    height: 75px;
    text-align: center;

}

    </style>
</head>

<body class="font-sans bg-secondary-100 dark:bg-secondary-50 text-secondary-700">

    @if (config('settings::theme:snow') == 1)
    <canvas class="snow" id="snow" width="1920" height="1080"></canvas>
    @endif
    <div id="app" class="min-h-screen">
        <x-paymenter-update />
        @if (!$clients || config('settings::sidebar') == 0)
        @include('layouts.navigation')
        @endif
        <div class="@if (config('settings::sidebar') == 1) flex md:flex-nowrap flex-wrap @endif">
            @if ($clients)
            @include('layouts.subnavigation')
            @endif
            <div class="w-full flex flex-col @if ($clients) min-h-[calc(100vh-105px)] @else min-h-[calc(100vh-64px)] @endif">
                <br>
                <main class="grow">
                    <div class="content">
                        <div class="max-w-[1650px] mx-auto block md:flex items-center gap-x-10 px-5">
                        
                           

                        </div>
                        <div class="grid grid-cols-12 gap-4" style="width: 100%; height: 100%;">
                            <div class="lg:col-span-4 md:col-span-6 col-span-12" style=" width: 100%; height: 100%;">
                                <div class="content-box" style=" width: 1608px; height: 800;">
                                    <div style="display: flex; justify-content: space-between; align-items: center;">

                                    <div class="container"> 
    <h2>{{ $filePath }}</h2>
    <input type="hidden" id="file" value="{{ $filePath }}">
    
    <!-- Hidden textarea to safely store content -->
    <textarea id="hiddenContent" style="display: none;">{{ $content }}</textarea>

    <!-- Monaco Editor -->
    <div id="editor" style="width: 100%; height: 300px;"></div>
    <br>
    
    <button id="saveButton" type="button" class="button button-primary">Save</button>
    <div id="feedback" style="color: green; margin-top: 10px;"></div>
</div>

<!-- Load Monaco Editor -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/monaco-editor/0.38.0/min/vs/loader.min.js"></script>
<script>
    var editor;
    require.config({ paths: { 'vs': 'https://cdnjs.cloudflare.com/ajax/libs/monaco-editor/0.38.0/min/vs' }}); 

    require(['vs/editor/editor.main'], function() {
        // Safely retrieve content from the hidden textarea
        var content = document.getElementById('hiddenContent').value;

        editor = monaco.editor.create(document.getElementById('editor'), {
            value: content,  // ✅ No syntax error, safe content
            language: 'javascript',
            theme: 'vs-dark'
        });
    });

    document.getElementById('saveButton').addEventListener('click', function() {
        if (!editor) {
            console.error('Monaco Editor not initialized yet!');
            return;
        }

        const filePath = document.getElementById('file').value;
        const content = editor.getValue();
        var ids = extractIdsFromUrl();

        var editUrl = '{{ route("extensions.momentmcreseller.files.contents.save", ["product" => ":product", "invoice_id" => ":invoice_id"]) }}'
            .replace(":product", ids.productId)
            .replace(":invoice_id", ids.invoiceId);

        fetch(editUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ file: filePath, content: content })
        })
        .then(async response => {
            const feedback = document.getElementById('feedback');
            let responseBody;

            try {
                responseBody = await response.json();
            } catch (e) {
                responseBody = await response.text();
            }

            console.log('🚀 Backend Response:', {
                status: response.status,
                statusText: response.statusText,
                headers: Array.from(response.headers.entries()),
                body: responseBody
            });

            if (response.ok) {
                feedback.style.color = 'green';
                feedback.textContent = responseBody.message || 'File saved successfully!';
            } else {
                feedback.style.color = 'red';
                feedback.textContent = responseBody.message || 'Error saving file.';
            }
        })
        .catch(error => {
            console.error('❌ Fetch Error:', error);
            const feedback = document.getElementById('feedback');
            feedback.style.color = 'red';
            feedback.textContent = 'Unexpected error occurred.';
        });
    });

    // Function to extract product and invoice ID from the URL
    function extractIdsFromUrl() {
        var pathSegments = window.location.pathname.split('/');
        var invoiceId = pathSegments[pathSegments.length - 2];
        var productId = pathSegments[pathSegments.length - 3];
        return { productId, invoiceId };
    }
</script>

