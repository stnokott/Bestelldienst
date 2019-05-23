let dictKitType = {
    "basic": 0,
    "comfort": 1,
    "social": 2,
    "premium": 3,
    "custom": 4
};
let dictOptionalType = {
    "clinic": 0,
    "drone": 1,
    "insurance": 2
};
let dictKitClass = {
    "basic": "kitBasic",
    "comfort": "kitComfort",
    "social": "kitSocial",
    "premium": "kitPremium",
    "custom": "kitCustom"
};
let dictKitName = {
    "basic": "Basic Kit",
    "comfort": "Comfort Kit",
    "social": "Social Kit",
    "premium": "Premium Kit",
    "custom": "Custom Kit"
};
let dictKitSVGName = {
    "basic": "wrench",
    "comfort": "couch",
    "social": "laugh",
    "premium": "trophy",
    "custom": "growth"
};
let dictKitPrice = {
  "basic": "5999.99€",
  "comfort": "7999.99€",
  "social": "8499.99€",
  "premium": "14999.99€",
  "custom": "24999.99€"
};

let shoppingCart = document.getElementsByClassName("shoppingCart")[0];
let shoppingCartKitItem = document.getElementById("shoppingCartKit");
let shoppingCartOptionalItems = shoppingCart.getElementsByClassName("optional");
let shoppingCartTotalPrice =  document.getElementsByClassName("shoppingCartTotal")[0].getElementsByClassName("value")[0];
let kits = document.getElementById("kitContainer").getElementsByClassName("bookable");
let optionals = document.getElementById("optionalsContainer").getElementsByClassName("bookable");
let selectedKitType = 0;

// Listener für Kit-Buttons
for (let i=0; i<kits.length; i++) {
    let button = kits[i].getElementsByTagName("button")[0];
    button.addEventListener("click", handleKitButtonPress);
}
// Listener für Optional-Buttons
for (let i=0; i<optionals.length; i++) {
    let button = optionals[i].getElementsByTagName("button")[0];
    button.addEventListener("click", handleOptionalButtonPress);
}
// Listener für Kit-Ändern-Button
document.querySelector('button[value="changeKit"').addEventListener("click", handleKitChangeButtonPress);

// ShoppingCartItems verstecken und Listener aktivieren
for (let i=0; i<shoppingCartOptionalItems.length; i++) {
    let shoppingCartOptionalItem = shoppingCartOptionalItems[i];
    shoppingCartOptionalItem.style.display = "none";
    shoppingCartOptionalItem.querySelector('button[value="removeItem"]').addEventListener("click", handleOptionalRemoveButtonPress);
}

//Listener für das Entfernen aller Optionals und zurücksetzen auf das BasicKit
document.getElementById("deleteCart").addEventListener("click", deleteShoppingCart);

// Listener für Fortfahren-Button
document.getElementById("confirmGenoCheckOrder").addEventListener("click", sendOrder);

function handleKitButtonPress() {
    shoppingCartKitItem.className = "cartItem "+dictKitClass[this.value];
    document.getElementsByClassName("cartItemName")[0].innerHTML = dictKitName[this.value];
    document.getElementsByClassName("cartItemPrice")[0].innerHTML = this.innerHTML;
    shoppingCartKitItem.getElementsByTagName("img")[0].src = "img/"+dictKitSVGName[this.value]+".svg";
    selectedKitType = dictKitType[this.value];

    // zu Optionals scrollen
    document.getElementById("chooseOptionalsHeader").scrollIntoView({ left: 0, block: 'start', behavior: 'smooth' });

    calculateTotal();
}

function handleOptionalButtonPress() {
    let cartItem = shoppingCart.querySelector("#"+this.value);
    cartItem.style.display = "grid";
    this.disabled = true;

    calculateTotal();
}

function handleOptionalRemoveButtonPress() {
    let cartItem = this.parentElement;
    document.getElementById("optionalsContainer").querySelector('button[value="'+cartItem.id+'"]').disabled = false;
    cartItem.style.display = "none";

    calculateTotal();
}

function handleKitChangeButtonPress() {
    document.getElementById("chooseKitHeader").scrollIntoView({ left: 0, block: 'start', behavior: 'smooth' });
}

function calculateTotal() {
    let total = 0.0;
    let iterationCount = shoppingCart.children.length;
    for (let i=0; i<iterationCount; i++) {
        if (shoppingCart.children[i].style.display !== "none") {
            let priceString = shoppingCart.children[i].getElementsByClassName("cartItemPrice")[0].innerHTML;
            total += parseFloat(priceString.substring(0, priceString.length-1));
        }
    }
    shoppingCartTotalPrice.innerHTML = total.toFixed(2)+"€";
}

//Shoppingcart löschen
function deleteShoppingCart(){
    //Entfernen der Optionals
    for (let i = 0; i < shoppingCartOptionalItems.length; i++){
      let shoppingCartOptionalItem = shoppingCartOptionalItems[i];
      shoppingCartOptionalItem.style.display = "none";
      document.getElementById("optionalsContainer").querySelector('button[value="'+shoppingCartOptionalItem.id+'"]').disabled = false;
    }

    //Zurücksetzen auf BasicKit
    shoppingCartKitItem.className = "cartItem "+dictKitClass["basic"];
    document.getElementsByClassName("cartItemName")[0].innerHTML = dictKitName["basic"];
    document.getElementsByClassName("cartItemPrice")[0].innerHTML = dictKitPrice["basic"];
    shoppingCartKitItem.getElementsByTagName("img")[0].src = "img/"+dictKitSVGName["basic"]+".svg";

    calculateTotal();
}

/**
 * Sendet POST-Request mit Bestelldaten an Phase4.php
 *
 * Format:
 * "root":
 *    0:
 *       "kittype": 3
 *    1:
 *       "kit1": 1
 *       "kit2": 2
 *
 *  Die Values sind jeweils die IDs des Kits / der Optionals, siehe Dictionary am Anfang der Datei
 */
function sendOrder() {
    this.disabled = true;
    let selectedOptionals = {};
    for (let i=0; i<shoppingCartOptionalItems.length; i++) {
        let shoppingCartOptionalItem = shoppingCartOptionalItems[i];
        if (shoppingCartOptionalItem.style.display !== "none") {
            selectedOptionals["kit"+i] = dictOptionalType[shoppingCartOptionalItem.id];
        }
    }

    let data = [{
        "kittype": selectedKitType,
        selectedOptionals
    )];

    let xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState === XMLHttpRequest.DONE ) {
            if(xmlhttp.status === 200){
                console.log('POST-Request erfolgreich');
                /*
                var form = document.createElement('form');
                document.body.appendChild(form);
                form.method = 'post';
                form.action = url;
                for (var name in data) {
                    var input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = name;
                    input.value = data[name];
                    form.appendChild(input);
                }
                form.submit();
                 */
            }
            else if(xmlhttp.status === 400) {
                console.log("Error-Code 400 bei POST-Request");
            }
            else {
                console.log("Nicht spezifizierter Error-Code bei POST-Request");
            }
        }
    }

    xmlhttp.open("post", "phase4.php", true);
    xmlhttp.send(JSON.stringify(data));

    this.disabled = false;
}
