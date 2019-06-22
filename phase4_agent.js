let genoChoiceOrdersSelect = document.getElementById("genoChoiceOrdersSelect");

restoreSelectionFromSessionStorage();
loadStatus();

genoChoiceOrdersSelect.addEventListener("change", loadStatus);

window.setInterval(function() {location.reload(true)}, 5000);

let radioGroups = document.getElementsByClassName("inputRadioGroup");
for (let radioGroup of radioGroups) {
    // Klick auf kompletten Div verarbeiten
    radioGroup.addEventListener("click", submitForm);
}
let checkboxes = document.getElementById("phase4_checkWrapper").getElementsByTagName("input");
for (let checkbox of checkboxes) {
    checkbox.addEventListener("click", submitForm);
}

// Listener für Navbar-Responsive
document.getElementById("btnWrapNavbar").addEventListener("click", toggleNavbarResponsive);

function loadStatus() {
    "use strict";
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
    "use strict";
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
    "use strict";
    for (let checkbox of checkboxes) {
        let optionaltype = checkbox.value;
        if (optionals_status_list[optionaltype] != null) {
            checkbox.parentElement.hidden = false;
            checkbox.checked = (optionals_status_list[optionaltype] === "1");
        } else {
            checkbox.parentElement.hidden = true;
        }
    }
}

function restoreSelectionFromSessionStorage() {
    "use strict";
    let selectedIndex = sessionStorage.getItem('phase4_agent_selectedIndex');
    if (selectedIndex < genoChoiceOrdersSelect.getElementsByTagName("option").length) {
        genoChoiceOrdersSelect.selectedIndex = selectedIndex;
    }
}

function submitForm(event) {
    "use strict";
    if (event.target.tagName === "DIV") {
        let associatedRadioButton = event.target.getElementsByTagName("input")[0];
        associatedRadioButton.checked = true;
    } else if (event.target.tagName === "LABEL") {
        let associatedRadioButton = event.target.previousElementSibling;
        associatedRadioButton.checked = true;
    }
    document.forms['statusOrderChange'].submit();
}

function toggleNavbarResponsive() {
    let navbar = document.getElementsByClassName("navlist")[0];
    if (navbar.classList.contains("responsive")) {
        navbar.classList.remove("responsive");
    } else {
        navbar.classList.add("responsive");
    }
}
