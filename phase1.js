loadStatus();

window.setInterval (loadStatus, 5000);

function loadStatus() {
    let xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (this.readyState === 4 && this.status === 200) {
            let statusJSON = JSON.parse(this.responseText);
            if (statusJSON.length === 0) {
                // Status nicht vorhanden
                return;
            }
            // Status vorhanden
            let status = parseInt(statusJSON[0]);
            setStatusActive(status);
        }
    };
    xmlhttp.open("GET", "getstatus_session.php", true);
    xmlhttp.send();
}
//SETSTATUSINTERVAL nutzen
/**
 * "active"-Klasse von allen radioButtons entfernen
 * @param activeIndex Integer Index of Status to activate (0=sent, 1=delivered, 2=analysis, 3=done)
 */
function setStatusActive(activeIndex) {
    let progressItems = document.getElementsByClassName("progresssteps")[0].getElementsByTagName("li");

    for (let i=0; i<progressItems.length; i++) {
        let progressItem = progressItems[i];
        let progressItemClasses = progressItem.classList;

        progressItemClasses.remove("active");
        progressItemClasses.remove("animate");

        if (i <= activeIndex) {
            progressItemClasses.add("active");
        } else if (i===activeIndex+1) {
            progressItemClasses.add("animate");
        }
    }

    // Button aktivieren, wenn Status komplett
    document.getElementById("getGenoCheckResults").disabled = (activeIndex !== progressItems.length-1);
}