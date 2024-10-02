const menuButton = document.getElementById("menubutton"); // Mengambil elemen dengan ID "menubutton"
const menuList = document.getElementById("menulist"); // Mengambil elemen dengan ID "menulist"
const menuBtnIcon = menuButton.querySelector("i");

menuButton.addEventListener("click", () => {
  menuList.classList.toggle("close");

  const isClose = menuList.classList.contains("close");
  menuBtnIcon.setAttribute(
    "class",
    isClose ? "ri-menu-line menu-icon" : "ri-close-line menu-icon"
  );
});

document.addEventListener("click", (event) => {
  const isClickInsideMenu =
    menuList.contains(event.target) || menuButton.contains(event.target);
  if (!isClickInsideMenu) {
    menuList.classList.add("close");
    menuBtnIcon.setAttribute("class", "ri-menu-line menu-icon");
  }
});
