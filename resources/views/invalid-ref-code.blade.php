<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 Not Found</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f5f5f5;
            color: #333;
            text-align: center;
            margin: 150px 0;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            border-radius: 8px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: #e44d26;
            font-size: 40px;
            margin: 0;
        }

        p {
            font-size: 18px;
            margin-bottom: 20px;
        }

        a {
            text-decoration: none;
            color: #e44d26;
            font-weight: bold;
        }

        a:hover {
            text-decoration: underline;
        }

        .url
    </style>
</head>
<body>
<div class="container">
    <h1>Ref code not found</h1>
    <p>
        A wrong referral code was provided in the site address. Check if it has exactly 10 characters ⬇️
    </p>
    <p class="url">
        {{url()->current()}}
    </p>
</div>
</body>
</html>
