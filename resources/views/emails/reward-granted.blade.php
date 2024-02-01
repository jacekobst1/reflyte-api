<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Congratulations! You've Won a Reward</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            color: #333;
        }

        h1 {
            color: #fdd619;
        }

        p {
            font-size: 18px;
            line-height: 1.6;
            color: #555;
        }
    </style>
</head>
<body>
    <main>
        <h1>Gratulacje!</h1>
        <p>
            Dzięki Twoim staraniom, do newslettera {{ $newsletterName }} dołączyło już <b>{{ $rewardPoints }}</b> nowych
            osób
            🙌
        </p>
        <p>Otrzymujesz nagrodę: <b>{{ $rewardName }}</b></p>
        <br>
        <p>Oto wiadomość od twórcy:</p>
        <p>{{ $mailText }}</p>
    </main>
</body>
</html>
