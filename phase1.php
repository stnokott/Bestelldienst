<!DOCTYPE html>
<html lang="de">

  <head>
    <meta charset="UTF-8">
    <title>GenoChoice&trade; - GenoCheck&trade; bestellen</title>
    <link rel="shortcut icon" type="image/x-icon" href="img/favicon.ico">
    <link rel="stylesheet" href="main.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link href="https://fonts.googleapis.com/css?family=Raleway|Roboto" rel="stylesheet">
  </head>

  <body>
    <div>
      <ul class="navlist">
        <li class="active"><a href="#">Phase 1</a></li>
        <li><a href="#">Phase 2</a></li>
        <li><a href="#">Phase 3</a></li>
      </ul>
    </div>

    <header>
      <span class="headerTitle">Genochoice</span><br>
      <span class="headerSubtitle">Legacy by Design</span>
    </header>

    <section>
      <span class="sectionHeader">GenoCheck&trade; bestellen</span>
      <p>
        Fordern Sie heute ihren <strong>kostenlosen</strong> GenoChoice&trade;-Gentest an.<br> Ein Team
        aus professionellen Genforschern prüft mit unserem patentierten GenoCheck&trade;-Verfahren die
        Stärken und Schwächen ihres zukünftigen Kindes.
      </p>
      <figure>
        <img src="img/family.jpg" alt="Diese fröhliche Familie könnten Sie sein!">
        <figcaption>Malte & Sombra Trontheim sind zufrieden mit ihrer GenoChoice&trade;-Entscheidung</figcaption>
      </figure>
    </section>
    <section>
      <span class="sectionHeader">Persönliche Daten</span>
      <form id="genoCheckForm" name="genoCheckForm[]" action="phase1tophase2.html" method="post">
        <label>Vorname</label>
        <input type="text" id="inputFirstName" name="inputFirstName" value="Max" required autofocus>

        <label>Name</label>
        <input type="text" id="inputLastName" name="inputLastName" value="Musterhalfen" required>

        <label>Straße & Hausnummer</label>
        <input type="text" id="inputStreet" name="inputStreet" value="Musterstraße" required>

        <label>Stadt</label>
        <input type="text" id="inputCity" name="inputCity" value="Musterstadt" required>

        <label>PLZ</label>
        <input type="text" id="inputZipcode" name="inputZipcode" value="12345" pattern="\d{5}" required>

        <label>E-Mail</label>
        <input type="email" id="inputEmail" name="inputEmail" value="m.mustermann@gmail.com" required>

        <button id="genoCheckSubmit" type="submit">
          GenoCheck&trade; bestellen
        </button>
      </form>
    </section>
  </body>

</html>
