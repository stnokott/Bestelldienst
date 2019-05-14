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

let shoppingCart = document.getElementsByClassName("shoppingCart")[0];
let shoppingCartKitItem = document.getElementById("shoppingCartKit");
let shoppingCartOptionalItems = shoppingCart.getElementsByClassName("optional");
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

// ShoppingCartItems verstecken und Listener aktivieren
for (let i=0; i<shoppingCartOptionalItems.length; i++) {
    let shoppingCartOptionalItem = shoppingCartOptionalItems[i];
    shoppingCartOptionalItem.style.display = "none";
    shoppingCartOptionalItem.querySelector('button[value="removeItem"]').addEventListener("click", handleOptionalRemoveButtonPress);
}

function handleKitButtonPress() {
    shoppingCartKitItem.className = "cartItem "+dictKitClass[this.value];
    document.getElementsByClassName("cartItemName")[0].innerHTML = dictKitName[this.value];
    document.getElementsByClassName("cartItemPrice")[0].innerHTML = this.innerHTML;
    shoppingCartKitItem.getElementsByTagName("img")[0].src = "img/"+dictKitSVGName[this.value]+".svg";

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