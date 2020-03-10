<html>
    <head>
        <meta charset="utf-8">
        <title>Buy Ticket</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="index.css" rel="stylesheet">
        <meta name="google-signin-client_id" content="297000245408-g56uu074uupgtv7obvrecefbpgm47i2l.apps.googleusercontent.com">
        <script src="https://apis.google.com/js/platform.js"></script>
          <style type="text/css">
            #gSignInWrapper {
                margin-bottom: 10px;
            }
            #customBtn {
              display: inline-block;
              background: white;
              color: #444;
              width: 99%;
              border-radius: 5px;
              border: thin solid #888;
              text-align: center;
              padding: 10px 0;
            }
            #customBtn:hover {
              cursor: pointer;
              background: #4285f4;
              color: white;
            }

            span.buttonText {
                display: inline-block;
                margin:  0 auto;
                font-size: 1.2em;
                font-weight: bold;
                font-family: 'Roboto', sans-serif;
            }
          </style>
    </head>
    <body>
        <h1>Buy Ticket</h1>
        <div class="container">
            <div class="links">
                <center><h4 style="font-weight: normal; margin-top: 0; margin-bottom:5px">Log in with</h4></center>
                <div id="gSignInWrapper">
                    <div id="customBtn" class="customGPlusSignIn">
                      <span class="buttonText">Google</span>
                    </div>
                </div>
                <div id="name"></div>
                <center><h4 style="font-weight: normal; margin-top: 0; margin-bottom:10px">Or</h4></center>
                <a href="login.php">Login</a>
                <a href="signup.php">Sign Up</a>
            </div>
        </div>
        <script type="text/javascript">
            function onSignIn(googleUser) {
                var id_token = googleUser.getAuthResponse().id_token;
                var xhr = new XMLHttpRequest();
                xhr.open('POST', 'http://localhost/register/event/oauth2callback.php');
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhr.onload = function() {
                  if (xhr.responseText === 'DONE') {
                    window.location.replace("http://localhost/register/event/ticket.php")
                  }
                };
                xhr.send('id_token=' + id_token);
            }
        </script>
        <script>
          var googleUser = {};
            gapi.load('auth2', function(){
              // Retrieve the singleton for the GoogleAuth library and set up the client.
              auth2 = gapi.auth2.init({
                client_id: '297000245408-g56uu074uupgtv7obvrecefbpgm47i2l.apps.googleusercontent.com',
                cookiepolicy: 'single_host_origin',
                // Request scopes in addition to 'profile' and 'email'
                //scope: 'additional_scope'
              });
              attachSignin(document.getElementById('customBtn'));
            });

          function attachSignin(element) {
            console.log(element.id);
            auth2.attachClickHandler(element, {},
                function(googleUser) {
                    onSignIn(googleUser);
                }, function(error) {
                    alert(JSON.stringify(error, undefined, 2));
                });
          }
        </script>
    </body>
</html>