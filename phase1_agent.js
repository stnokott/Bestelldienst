let genoCheckOrdersSelect = document.getElementById("genoChoiceOrdersSelect");

restoreSelectionFromSessionStorage();
loadStatus();

genoCheckOrdersSelect.addEventListener("change", loadStatus);

let radioButtons = document.getElementById("phase1_radioWrapper").getElementsByTagName("input");
for (let radioButton of radioButtons) {
    // TODO: Klick auf gesamtes Div verarbeiten
    radioButton.addEventListener("click", radioClicked);
}

function loadStatus() {
    sessionStorage.setItem('phase1_agent_selectedIndex', genoCheckOrdersSelect.selectedIndex);

    if (genoCheckOrdersSelect.selectedOptions.length===0){
        return;
    }
    let selectedUserid = genoCheckOrdersSelect.selectedOptions[0].value;
    let xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (this.readyState === 4 && this.status === 200) {
            let statusJSON = JSON.parse(this.responseText);
            if (statusJSON === null || statusJSON.length === 0) {
                // Status nicht vorhanden
                return;
            }
            // Status vorhanden
            let selectedUserStatus = parseInt(statusJSON[0]);
            setRadioGroupActive(selectedUserStatus);
        }
    };
    xmlhttp.open("GET", "getstatus_agent.php?userid="+selectedUserid, true);
    xmlhttp.send();
}

/**
 * "active"-Klasse von allen radioButtons entfernen
 * @param index Integer Index of Status to activate (0=sent, 1=delivered, 2=analysis, 3=done)
 */
function setRadioGroupActive(index) {
    let radioButtons = document.getElementsByClassName("inputRadioGroup");

    // alle RadioButtons deaktivieren
    for (let i=0; i<radioButtons.length; i++) {
        let radioButton = document.getElementsByClassName("inputRadioGroup")[index].querySelectorAll('[type="radio"]')[0];
        radioButton.checked = false;
    }

    // alle "active"-Klassen entfernen und radioButtons deaktivieren
    for (let i=0; i<radioButtons.length; i++) {
        let classList = radioButtons[i].classList;
        if (i === index) {
            if (!classList.contains("active")) {
                // active-Klasse setzen
                classList.add("active");
            }
            let radioButton = document.getElementsByClassName("inputRadioGroup")[index].querySelectorAll('[type="radio"]')[0];
            radioButton.checked = true;
        } else {
            // active-Klasse entfernen
            classList.remove("active");
        }
    }
}

function restoreSelectionFromSessionStorage() {
    let selectedIndex = sessionStorage.getItem('phase1_agent_selectedIndex');
    if (selectedIndex < genoCheckOrdersSelect.getElementsByTagName("option").length) {
        genoCheckOrdersSelect.selectedIndex = selectedIndex;
    }
}

function radioClicked() {
    document.forms['statusOrderChange'].submit();
    console.log("submit");
}
