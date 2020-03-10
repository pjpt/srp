<?php
    include('server.php');

    if (isset($_SESSION['user'])) {
        header('location: ticket.php');
    }
?>

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
                <?php include('errors.php') ?>
                <form action="" method="POST">
                    <div class="form-name">
                        <div class="form-firstname">
                            <label for="name">First Name</label>
                            <input type="text" name="firstname" id="name" required>
                        </div>
                        <div class="form-lastname">
                            <label for="name">Last Name</label>
                            <input type="text" name="lastname" id="name" required>
                        </div>
                    </div>
                    <div class="form-email">
                        <label for="email">Email</label>
                        <input type="email" name="email" id="email" required>
                    </div>
                    <div class="form-submit">
                        <input type="submit" value="SIGN UP" name="signup">
                    </div>
                </form>
            </div>
        </div>
    </body>
</html>