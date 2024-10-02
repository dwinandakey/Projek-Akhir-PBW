var searchBtn = document.querySelector(".search-btn");
var cancelBtn = document.querySelector(".cancel-btn");
var searchBox = document.querySelector(".search-box");
var searchInput = document.querySelector("input.cari");
var searchData = document.querySelector(".search-data");
var productsBox = document.querySelector(".product-container");
var campingCategoryBtn = document.getElementById("camping-gear");
var hikingCategoryBtn = document.getElementById("hiking-gear");

searchBtn.addEventListener("click", function () {
  searchBox.classList.add("active");
  searchInput.classList.add("active");
  searchBtn.classList.add("active");
  cancelBtn.classList.add("active");

  var xhr = new XMLHttpRequest();
  xhr.onreadystatechange = function () {
    if (xhr.readyState === 4 && xhr.status === 200) {
      searchData.classList.remove("active");
      searchData.innerHTML =
        "Ini adalah hasil pencarian tentang <span style='font-weight:500;'>" +
        searchInput.value +
        "</span>";
    }
    else {
      searchData.innerHTML = "";
    }
  };
  xhr.open("GET", "search-menu.php?search=" + searchInput.value, true);
  xhr.send();
});

cancelBtn.addEventListener("click", function () {
  searchBox.classList.remove("active");
  searchInput.classList.remove("active");
  searchBtn.classList.remove("active");
  cancelBtn.classList.remove("active");
  searchData.classList.add("active");
  searchInput.value = "";

  var xhr = new XMLHttpRequest();
  xhr.onreadystatechange = function () {
    if (xhr.readyState === 4 && xhr.status === 200) {
      productsBox.innerHTML = xhr.responseText;
    }
  };
  xhr.open("GET", "search-menu-all.php", true);
  xhr.send();
});

searchInput.addEventListener("input", function () {
  var xhr = new XMLHttpRequest();
  xhr.onreadystatechange = function () {
    if (xhr.readyState === 4 && xhr.status === 200) {
      productsBox.innerHTML = xhr.responseText;
      if (searchInput.value !== "") {
        searchData.classList.remove("active");
        searchData.innerHTML =
          "Ini adalah hasil pencarian tentang <span style='font-weight:500;'>" +
          searchInput.value +
          "</span>";
      } else {
        searchData.innerHTML = "";
      }
    }
  };
  xhr.open("GET", "search-menu.php?search=" + searchInput.value, true);
  xhr.send();
});

hikingCategoryBtn.addEventListener("click", function () {
  var xhr = new XMLHttpRequest();
  xhr.onreadystatechange = function () {
    if (xhr.readyState === 4 && xhr.status === 200) {
      productsBox.innerHTML = xhr.responseText;
    }
  };
  xhr.open("GET", "search-menu-byCategory.php?category=Hiking", true);
  xhr.send();
});

campingCategoryBtn.addEventListener("click", function () {
  var xhr = new XMLHttpRequest();
  xhr.onreadystatechange = function () {
    if (xhr.readyState === 4 && xhr.status === 200) {
      productsBox.innerHTML = xhr.responseText;
    }
  };
  xhr.open("GET", "search-menu-byCategory.php?category=Camping", true);
  xhr.send();
});
