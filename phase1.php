<!DOCTYPE html>
<html lang="de">
  <!-- GENOCHECK bestellen -->

  <head>
    <meta charset="UTF-8">
    <title>GenoChoice&trade; - GenoCheck&trade; bestellen</title>
    <link rel="shortcut icon" type="image/x-icon" href="img/favicon.ico">
  </head>

  <body>
    <header>
      <h1>GenoChoice</h1>
      <hr>
    </header>

    <h2>GenoCheck&trade; bestellen</h2>
    <p>
      Fordern Sie heute ihren <strong>kostenlosen</strong> GenoChoice&trade;-Gentest an.<br>
      Ein Team aus professionellen Genforschern prüft mit unserem patentierten GenoCheck&trade;-Verfahren die Stärken und Schwächen ihres zukünftigen Kindes.
    </p>
    <section class="genoCheckOrder">
      <form class="form" action="phase1tophase2.html" name="formGenoCheck[]" method="post">
        <label for="inputFirstName">Vorname</label>
        <input type="text" id="inputFirstName" name="inputFirstName" value="Max">
        <label for="inputLastName">Name</label>
        <input type="text" id="inputLastName" name="inputLastName" value="Mustermann"><br>
        <label for="inputStreet">Straße und Hausnummer</label>
        <input type="text" id="inputStreet" name="inputStreet" value="Musterstraße">
        <input type="text" id="inputStreetNum" name="inputStreetNum" value="6" style="width:30px"><br>
        <label for="inputCity">Stadt</label>
        <input type="text" id="inputCity" name="inputCity" value="Musterstadt"><br>
        <label for="inputZipcode">PLZ</label>
        <input type="text" id="inputZipcode" name="inputZipcode" value="12345"><br>
        <hr>
        <button type="submit" onclick="alert('Bestellung aufgegeben!')">GenoCheck&trade; bestellen</button>
      </form>
    </section>
  </body>

</html>
