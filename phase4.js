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
let dictOptionalsName = {
    "clinic": "Klinikgeburt",
    "drone": "Lieferung per Drohne",
    "heron": "Lieferung per Storch"
};
let dictOptionalsSVGName = {
    "clinic": "pregnant",
    "drone": "drone-delivery",
    "heron": "heron"
};

let shoppingCart = document.getElementsByClassName("shoppingCart")[0];
let shoppingCartKitItem = document.getElementById("shoppingCartKit");
let shoppingCartTotalPrice =  document.getElementsByClassName("shoppingCartTotal")[0].getElementsByClassName("value")[0];
let kits = document.getElementById("kitContainer").getElementsByClassName("bookable");
let optionals = document.getElementById("optionalsContainer").getElementsByClassName("bookable");

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

// Listener für Warenkorb-Änderungen
let observer = new MutationObserver(function(mutations) {
    // TODO Mutations ignorieren
    mutations.forEach(function(mutation) {
        calculateTotal();
    });
});
observer.observe(shoppingCart, { childList: true });

function handleKitButtonPress() {
    shoppingCartKitItem.className = "cartItem "+dictKitClass[this.value];
    document.getElementsByClassName("cartItemName")[0].innerHTML = dictKitName[this.value];
    document.getElementsByClassName("cartItemPrice")[0].innerHTML = this.innerHTML;
    shoppingCartKitItem.getElementsByTagName("img")[0].src = "img/"+dictKitSVGName[this.value]+".svg";

    // zu Optionals scrollen
    document.getElementById("chooseOptionalsHeader").scrollIntoView({ left: 0, block: 'start', behavior: 'smooth' });
}

function handleOptionalButtonPress() {
    // prüfe, ob Item bereits vorhanden
    if (shoppingCart.querySelector("#"+this.value) == null) {
        let cartItem = document.createElement("div");
        cartItem.className = "cartItem optional";
        cartItem.id = this.value;

        let imgItem = document.createElement("img");
        imgItem.src = "img/"+dictOptionalsSVGName[this.value]+".svg";
        imgItem.alt = "";
        cartItem.appendChild(imgItem);

        let nameItem = document.createElement("div");
        nameItem.className = "cartItemName";
        nameItem.innerHTML = dictOptionalsName[this.value];
        cartItem.appendChild(nameItem);

        let priceItem = document.createElement("div");
        priceItem.className = "cartItemPrice";
        priceItem.innerHTML = this.innerHTML;
        cartItem.appendChild(priceItem);

        let removeButton = document.createElement("button");
        removeButton.className = "noshadow";
        removeButton.value = "removeItem";
        let removeButtonIcon = document.createElement("i");
        removeButtonIcon.className = "material-icons";
        removeButtonIcon.innerHTML = "clear";
        removeButton.appendChild(removeButtonIcon);
        removeButton.addEventListener("click", handleOptionalRemoveButtonPress);
        cartItem.appendChild(removeButton);

        shoppingCart.appendChild(cartItem);

        this.disabled = true;

        /*
        <div class="cartItem optional">
            <img src="img/drone-delivery.svg" alt=""/>
            <div class="cartItemName">Lieferung per Drohne</div>
            <div class="cartItemPrice">249.99€</div>
            <button class="noshadow" value="removeItem"><i class="material-icons">clear</i></button>
        </div>
         */
    }
}

function handleOptionalRemoveButtonPress() {
    let cartItem = this.parentElement;
    document.getElementById("optionalsContainer").querySelector('button[value="'+cartItem.id+'"]').disabled = false;
    shoppingCart.removeChild(cartItem);
}

function handleKitChangeButtonPress() {
    document.getElementById("chooseKitHeader").scrollIntoView({ left: 0, block: 'start', behavior: 'smooth' });
}

function calculateTotal() {
    let total = 0.0;
    for (let i=0; i<shoppingCart.children.length; i++) {
        let priceString = shoppingCart.children[i].getElementsByClassName("cartItemPrice")[0].innerHTML;
        total += parseFloat(priceString.substring(0, priceString.length-1));
    }

    shoppingCartTotalPrice.innerHTML = total.toFixed(2)+"€";
}