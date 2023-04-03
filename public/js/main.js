let sidenav = document.getElementById("mySidenav");
let openBtn = document.getElementById("openBtn");
let closeBtn = document.getElementById("closeBtn");
let blockNavBar  = document.getElementById("blockNavBar")

openBtn.onclick = openNav;
closeBtn.onclick = closeNav;
blockNavBar.onclick = openNav;
/* Set the width of the side navigation to 250px */
function openNav() {
    sidenav.classList.add("active");
    blockNavBar.classList.add("active");
}

/* Set the width of the side navigation to 0 */
function closeNav() {
    sidenav.classList.remove("active");
    blockNavBar.classList.remove("active");
}