let genoChoiceOrdersSelect = document.getElementById("genoChoiceOrdersSelect");

restoreSelectionFromSessionStorage();
loadStatus();

genoChoiceOrdersSelect.addEventListener("change", loadStatus);

let radioGroups = document.getElementsByClassName("inputRadioGroup");
for (let radioGroup of radioGroups) {
    // Klick auf kompletten Div verarbeiten
    radioGroup.addEventListener("click", submitForm);
}
let checkboxes = document.getElementById("phase4_checkWrapper").getElementsByTagName("input");
for (let checkbox of checkboxes) {
    checkbox.addEventListener("click", submitForm);
}

function loadStatus() {
    sessionStorage.setItem('phase4_agent_selectedIndex', genoChoiceOrdersSelect.selectedIndex);

    if (genoChoiceOrdersSelect.selectedOptions.length===0){
        return;
    }
    let selectedUserid = genoChoiceOrdersSelect.selectedOptions[0].value;
    let xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (this.readyState === 4 && this.status === 200) {
            let statusJSON = JSON.parse(this.responseText);
            if (statusJSON === null || statusJSON.length === 0) {
                // Status nicht vorhanden
                return;
            }
            // Status vorhanden
            let selectedUserStatus = parseInt(statusJSON["status"]);
            setRadioGroupActive(selectedUserStatus);
            setCheckboxesActive(statusJSON["optionals"]);
        }
    };
    xmlhttp.open("GET", "getstatuschoice_agent.php?userid="+selectedUserid, true);
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

function setCheckboxesActive(optionals_status_list) {
    for (let checkbox of checkboxes) {
        let optionaltype = checkbox.value;
        checkbox.checked = (optionals_status_list[optionaltype] === "1");
    }
}

function restoreSelectionFromSessionStorage() {
    let selectedIndex = sessionStorage.getItem('phase4_agent_selectedIndex');
    if (selectedIndex < genoChoiceOrdersSelect.getElementsByTagName("option").length) {
        genoChoiceOrdersSelect.selectedIndex = selectedIndex;
    }
}

function submitForm(event) {
    let associatedRadioButton = event.target.getElementsByTagName("input")[0];
    associatedRadioButton.checked = true;
    document.forms['statusOrderChange'].submit();
}
