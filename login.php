<?php
session_start();
session_destroy();
session_start();
if (!isset($_SESSION["error"])) {
  $_SESSION["error"] = "";
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Zaloguj się</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous" />
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
  </script>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

  <link href="./public/styles/login.css" rel="stylesheet" />
</head>

<body style="background-color: #343a40">
  <div class="container-fluid text-align-center mt-5">
    <div class="row text-white">
      <div class="col-sm-4 custom-col-sm-1"></div>
      <div class="col-sm-4 custom-col-sm-2 bg-dark">
        <fieldset>
          <div class="row">
            <form method="POST" action="prv/prv_login.php">
              <div class="row">
                <div class="col-lg-4 mt-5">
                  <label for="login">Podaj login</label>
                </div>
                <div class="col-lg-8 mt-lg-5 mt-1">
                  <input type="text" name="login" id="login" />
                </div>
              </div>
              <div class="row">
                <div class="col-lg-4 mt-lg-3 mt-3">
                  <label for="password">Podaj hasło</label>
                </div>
                <div class="col-lg-8 mt-lg-3 mt-1">
                  <input type="password" name="password" id="password" />
                </div>
              </div>
              <div class="col-12 mt-3 mb-lg-3">
                <button type="submit">Zaloguj się</button>
              </div>
              <div class="row">
                <div class="col style-font mt-2"> Jeśli nie masz jeszcze konta, <a href="register.php" class="text-red">zarejestruj się</a>
                </div>
              </div>
              <div class="row">
                <div class="col mb-5 mt-1 text-red">
                  <?php
                  echo $_SESSION["error"];
                  $_SESSION["error"] = "";
                  ?>
                </div>
              </div>
            </form>
          </div>

        </fieldset>
      </div>
      <div class="col-sm-4 custom-col-sm-1"></div>
    </div>
  </div>
  <div class="toast fade 
  <?php
  if ($_SESSION["message"]) {
    echo "show";
  }
  ?>
  " style="position: fixed;top: 5px;right: 5px;">
    <div class="toast-header">
      <strong class="me-auto">Wiadomość</strong>
      <button type="button" class="btn-close" data-bs-dismiss="toast"></button>
    </div>
    <div class="toast-body">
      <p>
        <?php
        echo $_SESSION["message"];
        $_SESSION["message"] = false;
        ?>
      </p>
    </div>
  </div>
</body>

</html>