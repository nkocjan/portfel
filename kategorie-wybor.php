<?php
?>

<!doctype html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Wybierz kategorie wydatków</title>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous" />
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
  <link href="./public/styles/kategorie-wybor.css" rel="stylesheet" />
</head>

<body>
  <div class="container-md">
    <div class="row">
      <div class="col-12 text-align-center text-white mt-3 mb-3">
        <h1>Wybierz 5 spośród wszystkich kategorii</h1>
      </div>
    </div>
    <form id="formularz" method="POST" action="./prv/prv_kategorie.php">
      <div class="row">
        <div class="row text-black style-padding justify-content-center">
          <div class="col-xxl-2 col-lg-3 col-md-4 col-sm-6 col-6 mt-4 mb-md-4">
            <div class="card style-card mx-auto" id="jedzenie">
              <img src="public/zdjecia/jedzenie.png" class="card-img-top mx-auto" alt="..." />
              <div class="card-body mx-auto">
                <div class="row flex-nowrap">
                  <div class="col-10">
                    <h5 class="card-title">Jedzenie</h5>
                  </div>
                  <div class="col-2">
                    <input type="checkbox" name="jedzenie" />
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-xxl-2 col-lg-3 col-md-4 col-sm-6 col-6 mt-4 mb-md-4">
            <div class="card style-card mx-auto" id="rodzina">
              <img src="public/zdjecia/rodzina.png" class="card-img-top" alt="..." />
              <div class="card-body mx-auto">
                <div class="row flex-nowrap">
                  <div class="col-10">
                    <h5 class="card-title">Rodzina</h5>
                  </div>
                  <div class="col-2">
                    <input type="checkbox" name="rodzina" />
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-xxl-2 col-lg-3 col-md-4 col-sm-6 col-6 mt-4 mb-md-4">
            <div class="card style-card mx-auto" id="przyszlosc">
              <img src="public/zdjecia/przyszlosc.png" class="card-img-top" alt="..." />
              <div class="card-body mx-auto">
                <div class="row flex-nowrap">
                  <div class="col-10">
                    <h5 class="card-title">Przyszłość</h5>
                  </div>
                  <div class="col-2">
                    <input type="checkbox" name="przyszlosc" />
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-xxl-2 col-lg-3 col-md-4 col-sm-6 col-6 mt-4 mb-md-4">
            <div class="card style-card mx-auto" id="zdrowie">
              <img src="public/zdjecia/zdrowie.png" class="card-img-top" alt="..." />
              <div class="card-body mx-auto">
                <div class="row flex-nowrap">
                  <div class="col-10">
                    <h5 class="card-title">Zdrowie</h5>
                  </div>
                  <div class="col-2">
                    <input type="checkbox" name="zdrowie" />
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-xxl-2 col-lg-3 col-md-4 col-sm-6 col-6 mt-4 mb-md-4">
            <div class="card style-card mx-auto" id="hobby">
              <img src="public/zdjecia/hobby.png" class="card-img-top" alt="..." />
              <div class="card-body mx-auto">
                <div class="row flex-nowrap">
                  <div class="col-10">
                    <h5 class="card-title">Hobby</h5>
                  </div>
                  <div class="col-2">
                    <input type="checkbox" name="hobby" />
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-xxl-2 col-lg-3 col-md-4 col-sm-6 col-6 mt-4 mb-md-4">
            <div class="card style-card mx-auto" id="dom">
              <img src="public/zdjecia/dom.png" class="card-img-top" alt="..." />
              <div class="card-body mx-auto">
                <div class="row flex-nowrap">
                  <div class="col-10">
                    <h5 class="card-title">Dom</h5>
                  </div>
                  <div class="col-2">
                    <input type="checkbox" name="dom" />
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-xxl-2 col-lg-3 col-md-4 col-sm-6 col-6 mt-4 mb-md-4">
            <div class="card style-card mx-auto" id="podroze">
              <img src="public/zdjecia/podroze.png" class="card-img-top" alt="..." />
              <div class="card-body mx-auto">
                <div class="row flex-nowrap">
                  <div class="col-10">
                    <h5 class="card-title">Podróże</h5>
                  </div>
                  <div class="col-2">
                    <input type="checkbox" name="podroze" />
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-xxl-2 col-lg-3 col-md-4 col-sm-6 col-6 mt-4 mb-md-4">
            <div class="card style-card mx-auto" id="inne">
              <img src="public/zdjecia/inne.png" class="card-img-top" alt="..." />
              <div class="card-body mx-auto">
                <div class="row flex-nowrap">
                  <div class="col-10">
                    <h5 class="card-title">Inne</h5>
                  </div>
                  <div class="col-2">
                    <input type="checkbox" name="inne" />
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-12 mx-auto mt-5 mb-5">
          <button class="btn btn-primary" type="submit" name="submit" id="submit">
            Wyślij
          </button>
        </div>
      </div>
    </form>
  </div>
  <script>
    const formularz = document.getElementById("formularz");
    let checkboxy = formularz.querySelectorAll('input[type="checkbox"]');
    const submitButton = document.getElementById("submit");

    const jedzenie = document.getElementById("jedzenie");
    const rodzina = document.getElementById("rodzina");
    const przyszlosc = document.getElementById("przyszlosc");
    const zdrowie = document.getElementById("zdrowie");
    const hobby = document.getElementById("hobby");
    const dom = document.getElementById("dom");
    const podroze = document.getElementById("podroze");
    const inne = document.getElementById("inne");

    jedzenie.addEventListener("click", () => {
      if (checkboxy[0].checked) {
        checkboxy[0].checked = false;
      } else {
        checkboxy[0].checked = true;
      }
    });
    rodzina.addEventListener("click", () => {
      if (checkboxy[1].checked) {
        checkboxy[1].checked = false;
      } else {
        checkboxy[1].checked = true;
      }
    });
    przyszlosc.addEventListener("click", () => {
      if (checkboxy[2].checked) {
        checkboxy[2].checked = false;
      } else {
        checkboxy[2].checked = true;
      }
    });
    zdrowie.addEventListener("click", () => {
      if (checkboxy[3].checked) {
        checkboxy[3].checked = false;
      } else {
        checkboxy[3].checked = true;
      }
    });
    hobby.addEventListener("click", () => {
      if (checkboxy[4].checked) {
        checkboxy[4].checked = false;
      } else {
        checkboxy[4].checked = true;
      }
    });
    dom.addEventListener("click", () => {
      if (checkboxy[5].checked) {
        checkboxy[5].checked = false;
      } else {
        checkboxy[5].checked = true;
      }
    });
    podroze.addEventListener("click", () => {
      if (checkboxy[6].checked) {
        checkboxy[6].checked = false;
      } else {
        checkboxy[6].checked = true;
      }
    });
    inne.addEventListener("click", () => {
      if (checkboxy[7].checked) {
        checkboxy[7].checked = false;
      } else {
        checkboxy[7].checked = true;
      }
    });

    const maxChecked = 5;
    submitButton.addEventListener("click", event => {
      var checkedCheckboxes = formularz.querySelectorAll(
        'input[type="checkbox"]:checked'
      );
      if (checkedCheckboxes.length !== maxChecked) {
        alert(
          "Proszę zaznaczyć dokładnie " +
          maxChecked +
          " checkboxów przed wysłaniem formularza."
        );
        event.preventDefault();
      }
    });
  </script>
</body>

</html>