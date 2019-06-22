let dictOptionalType = {
    "clinic": 0,
    "drone": 1,
    "insurance": 2
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
    "use strict";
    shoppingCartKitItem.className = "cartItem "+this.dataset.cssclass;
    document.getElementsByClassName("cartItemName")[0].innerHTML = this.dataset.name;
    document.getElementsByClassName("cartItemPrice")[0].innerHTML = this.dataset.price+"€";
    shoppingCartKitItem.getElementsByTagName("img")[0].src = "data:image/svg+xml;utf8,"+this.dataset.bg;
    selectedKitType = this.dataset.kitid;

    // zu Optionals scrollen
    document.getElementById("chooseOptionalsHeader").scrollIntoView({ left: 0, block: 'start', behavior: 'smooth' });

    calculateTotal();
}

function handleOptionalButtonPress() {
    "use strict";
    let cartItem = shoppingCart.querySelector("#"+this.value);
    cartItem.style.display = "grid";
    this.disabled = true;

    calculateTotal();
}

function handleOptionalRemoveButtonPress() {
    "use strict";
    let cartItem = this.parentElement;
    document.getElementById("optionalsContainer").querySelector('button[value="'+cartItem.id+'"]').disabled = false;
    cartItem.style.display = "none";

    calculateTotal();
}

function handleKitChangeButtonPress() {
    "use strict";
    document.getElementById("chooseKitHeader").scrollIntoView({ left: 0, block: 'start', behavior: 'smooth' });
}

function calculateTotal() {
    "use strict";
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
    "use strict";
    //Entfernen der Optionals
    for (let i = 0; i < shoppingCartOptionalItems.length; i++){
      let shoppingCartOptionalItem = shoppingCartOptionalItems[i];
      shoppingCartOptionalItem.style.display = "none";
      document.getElementById("optionalsContainer").querySelector('button[value="'+shoppingCartOptionalItem.id+'"]').disabled = false;
    }

    //Zurücksetzen auf BasicKit
    let defaultKitButton = document.getElementById("kitContainer").getElementsByClassName("bookable")[0].getElementsByTagName("button")[0];
    shoppingCartKitItem.className = "cartItem "+defaultKitButton.dataset.cssclass;
    document.getElementsByClassName("cartItemName")[0].innerHTML = defaultKitButton.dataset.name;
    document.getElementsByClassName("cartItemPrice")[0].innerHTML = defaultKitButton.dataset.price;
    shoppingCartKitItem.getElementsByTagName("img")[0].src = "data:image/svg+xml;utf8,"+defaultKitButton.dataset.bg;

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
    "use strict";
    this.disabled = true;
    let selectedOptionals = [];
    for (let i=0; i<shoppingCartOptionalItems.length; i++) {
        let shoppingCartOptionalItem = shoppingCartOptionalItems[i];
        if (shoppingCartOptionalItem.style.display !== "none") {
            selectedOptionals[i] = dictOptionalType[shoppingCartOptionalItem.id];
        }
    }

    let form = document.createElement('form');
    document.body.appendChild(form);
    form.method = 'post';
    form.action = "phase4.php";
    let inputKittype = document.createElement('input');
    inputKittype.setAttribute("type", "hidden");
    inputKittype.name = "kittype";
    inputKittype.value = selectedKitType;
    form.appendChild(inputKittype);

    let inputOptionals = document.createElement("select");
    inputOptionals.setAttribute("type", "hidden");
    inputOptionals.name = "selectedoptionals[]";
    inputOptionals.multiple = true;
    form.appendChild(inputOptionals);

    for (let optional in selectedOptionals) {
        let inputOptionalsItem = document.createElement('option');
        inputOptionalsItem.type = 'hidden';
        console.log(optional);
        inputOptionalsItem.value = optional;
        inputOptionalsItem.innerHTML = optional;
        inputOptionalsItem.selected = true;
        inputOptionals.appendChild(inputOptionalsItem);
    }
    form.submit();

    this.disabled = false;
}
