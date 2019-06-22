let dictKittypeClass = {
    0: "confirmed",
    1: "extraction",
    2: "incubate",
    3: "meiosis",
    4: "embryo",
    5: "analysis",
    6: "choiceready"
};
let dictOptionalsClass = {
    0: "insertion",
    1: "sickness",
    2: "social"
};

loadStatus();

window.setInterval (loadStatus, 5000);

//Orderoptionals setzen
let optionalItems = document.getElementsByClassName("statusoptionals")[0].getElementsByTagName("li");

function loadStatus() {
    "use strict";
    let xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (this.readyState === 4 && this.status === 200) {
            let statusJSON = JSON.parse(this.responseText);
            if (statusJSON.length === 0) {
                // Status nicht vorhanden
                return;
            }
            // Status vorhanden
            let kitStatus = parseInt(statusJSON["status"]);
            setKitStatus(kitStatus);
            setOptionalsStatus(statusJSON["optionals"]);
            checkAllIsDone();
        }
    };
    xmlhttp.open("GET", "getstatuschoice_session.php", true);
    xmlhttp.send();
}

/**
 * "active"-Klasse von allen radioButtons entfernen
 * @param activeIndex Integer Index of Status to activate (0=sent, 1=delivered, 2=analysis, 3=done)
 */
function setKitStatus(activeIndex) {
    "use strict";
    let kitStatusCount = 7;

    for (let i=0; i<kitStatusCount; i++) {
        let progressItem = document.getElementsByClassName(dictKittypeClass[i])[0];
        let progressItemClasses = progressItem.classList;

        progressItemClasses.remove("active");
        progressItemClasses.remove("animate");

        if (i <= activeIndex) {
            progressItemClasses.add("active");
        } else if (i===activeIndex+1) {
            progressItemClasses.add("animate");
        }
    }
}

function setOptionalsStatus(optionalsDict) {
    let maxOptionalsStatusCount = 3;

    for (let i=0; i<optionalItems.length; i++) {
        let optionalItem = optionalItems[i];
        let progressItemClasses = optionalItem.classList;

        progressItemClasses.remove("active");
    }

    for (let i=0; i<maxOptionalsStatusCount; i++) {
        let optionalDone = optionalsDict[i];
        if (optionalDone != null && optionalDone === "1") {
            document.getElementsByClassName(dictOptionalsClass[i])[0].classList.add("active");
        }
    }
}

function checkAllIsDone() {
    let done = true;

    // Button aktivieren, wenn Status komplett
    if (!document.getElementsByClassName("choiceready")[0].classList.contains("active")
    || !optionalItems[optionalItems.length-1].classList.contains("active")) {
        done = false;
    }

    document.getElementById("getGenoChoiceResults").disabled = !done;
}
