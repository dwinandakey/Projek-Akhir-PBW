document.getElementById("select-image").addEventListener("click", function () {
  document.getElementById("file").click();
});

document.getElementById("select-image-item").addEventListener("click", function () {
  document.getElementById("file-item").click();
});

document.getElementById("file").addEventListener("change", function (event) {
  const imgArea = document.querySelector(".img-area");
  const file = event.target.files[0];
  if (file && file.size <= 2 * 1024 * 1024) {
    const reader = new FileReader();
    reader.onload = function (e) {
      const allImg = imgArea.querySelectorAll("img");
      allImg.forEach((item) => item.remove());
      const imgUrl = reader.result;
      const img = document.createElement("img");
      img.src = imgUrl;
      img.style.width = "100%";
      img.style.height = "auto";
      img.style.borderRadius = "0.5rem";
      imgArea.appendChild(img);
      imgArea.classList.add("active");
      imgArea.dataset.img = file.name;

      // Menyembunyikan elemen "Upload Image" dan pesan
      imgArea.querySelector("i").style.display = "none";
      imgArea.querySelector("h3").style.display = "none";
      imgArea.querySelector("p").style.display = "none";
    };
    reader.readAsDataURL(file);
  } else {
    imgArea.innerHTML =
      "<p>Ukuran gambar harus kurang dari <span>2MB</span></p>";
  }
});

document.getElementById("file-item").addEventListener("change", function (event) {
  const imgArea = document.querySelector(".img-area-item");
  const file = event.target.files[0];
  if (file && file.size <= 2 * 1024 * 1024) {
    const reader = new FileReader();
    reader.onload = function (e) {
      const allImg = imgArea.querySelectorAll("img");
      allImg.forEach((item) => item.remove());
      const imgUrl = reader.result;
      const img = document.createElement("img");
      img.src = imgUrl;
      img.style.width = "100%";
      img.style.height = "auto";
      img.style.borderRadius = "0.5rem";
      imgArea.appendChild(img);
      imgArea.classList.add("active");
      imgArea.dataset.img = file.name;

      // Menyembunyikan elemen "Upload Image" dan pesan
      imgArea.querySelector("i").style.display = "none";
      imgArea.querySelector("h3").style.display = "none";
      imgArea.querySelector("p").style.display = "none";
    };
    reader.readAsDataURL(file);
  } else {
    imgArea.innerHTML =
      "<p>Ukuran gambar harus kurang dari <span>2MB</span></p>";
  }
});

function updateTotalAmount() {
  const itemDescription = document.getElementById('item_description');
  const selectedItem = itemDescription.options[itemDescription.selectedIndex];
  const price = selectedItem.getAttribute('data-price');
  document.getElementById('total_amount').value = parseFloat(price).toFixed(2);
}

