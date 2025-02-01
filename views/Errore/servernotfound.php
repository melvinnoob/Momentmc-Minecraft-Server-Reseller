<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <style>
        body {
            font-family: 'Nunito', sans-serif;
            background-color: #f8f9fa;
            color: #343a40;
            text-align: center;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            padding: 50px;
        }
        .container {
            max-width: 600px;
            background-color: #fff;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        h1 {
            font-size: 2.5rem;
            font-weight: 700;
            color: #dc3545; /* Red for emphasis */
            margin-bottom: 20px;
        }
        p {
            font-size: 1.25rem;
            color: #343a40;
            margin-bottom: 30px;
        }
        .btn {
            display: inline-block;
            padding: 15px 30px;
            font-size: 1.25rem;
            color: #fff;
            background-color: #007bff;
            border: none;
            border-radius: 8px;
            text-decoration: none;
            transition: background-color 0.3s ease;
            cursor: pointer;
        }
        .btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Server Not Found or Still Installing</h1>
        <p>The server you are looking for may not exist or is still setting up.</p>
        <p>Please try reloading the page. If you believe this is an error, contact support.</p>
        <a href="javascript:location.reload();" class="btn">Reload Page</a>
        <br><br>
<a href="javascript:window.close();" class="btn" style="background-color: #6c757d;">Close Page</a>
    </div>
</body>
</html>
