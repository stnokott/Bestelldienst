// Listener für Navbar-Responsive
document.getElementById("btnWrapNavbar").addEventListener("click", function() {
    let navbar = document.getElementsByClassName("navlist")[0];
    if (navbar.classList.contains("responsive")) {
        navbar.classList.remove("responsive");
    } else {
        navbar.classList.add("responsive");
    }
});