// Wenn Dropdown-Element geändert, rufe "loadStatus" auf
document.getElementById("genoCheckOrdersSelect").addEventListener("change", loadStatus);

function loadStatus() {
    let selectedUserid = document.getElementById("genoCheckOrdersSelect").selectedOptions[0].value;
    let xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (this.readyState === 4 && this.status === 200) {
            let statusJSON = JSON.parse(this.responseText);
            if (statusJSON.length === 0) {
                // Status nicht vorhanden
                return;
            }
            // Status vorhanden
            let selectedUserStatus = parseInt(statusJSON[0]);
            setRadioGroupActive(selectedUserStatus);
        }
    };
    xmlhttp.open("GET", "getstatus.php?userid="+selectedUserid, true);
    xmlhttp.send();
}

/**
 * "active"-Klasse von allen radioButtons entfernen
 */
function setRadioGroupActive(index) {
    let radioButtons = document.getElementsByClassName("inputRadioGroup");

    // alle "active"-Klassen entfernen
    for (let i=0; i<radioButtons.length; i++) {
        let classList = radioButtons[i].classList;
        if (i === index && !classList.contains("active")) {
            classList.add("active");
        } else {
            classList.remove("active");
        }
    }
}
