<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>

    <style>
        body {
            padding: 0;
            margin: 0;
            box-sizing: border-box;
        }

        .container {
            width: 100vw;
            height: 100vh;
            display: flex;
            justify-content: center;
            margin: auto;
        }

        .error {
            font-size: 1em;
            color: red;
        }

        .success {
            font-size: 1em;
            color: green;
        }
    </style>
    <div class="container">
        @yield('content')
    </div>
</body>

</html>
