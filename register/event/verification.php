<?php include('server.php') ?>

<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Buy Ticket</title>
        <link href="index.css" rel="stylesheet">
    </head>
    <body>
        <h1>Buy Ticket</h1>
        <div class="container">
            <div class="links">
                <form action="" method="POST">
                    <?php include('errors.php') ?>
                    <div class="form-otp">
                        <label for="otp">OTP</label>
                        <input type="text" name="otp" id="otp" maxlength="6" required>
                    </div>
                    <div class="form-submit">
                        <input type="submit" value="SUBMIT" name="otp_submit">
                    </div>
                </form>
            </div>
        </div>
    </body>
</html>