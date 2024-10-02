function validasiTanggal() {
  const rentalStart = document.getElementById("rental_start").value;
  const rentalEnd = document.getElementById("rental_end").value;

  if (new Date(rentalEnd) <= new Date(rentalStart)) {
    alert("Rental End Date harus lebih minimal 1 hari dari Rental Start Date");
    return false;
  }
  return true;
}

document.getElementById("select-image").addEventListener("click", function () {
  document.getElementById("file").click();
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

document.addEventListener("DOMContentLoaded", function () {
  const equipmentSelect = document.getElementById("equipment_id");
  const rentalStartInput = document.getElementById("rental_start");
  const rentalEndInput = document.getElementById("rental_end");
  const totalAmountInput = document.getElementById("total_amount");
  const paymentAmountDisplay = document.getElementById("payment_amount");

  function calculateTotalAmount() {
    const selectedOption =
      equipmentSelect.options[equipmentSelect.selectedIndex];
    const price = parseFloat(selectedOption.getAttribute("data-price"));
    const rentalStart = new Date(rentalStartInput.value);
    const rentalEnd = new Date(rentalEndInput.value);
    const rentalDays = Math.ceil(
      (rentalEnd - rentalStart) / (1000 * 60 * 60 * 24)
    );

    if (!isNaN(price) && rentalDays > 0) {
      const dailyRate = 0.025 * price;
      const totalAmount = rentalDays * dailyRate;
      totalAmountInput.value = totalAmount.toFixed(2);
      paymentAmountDisplay.textContent = totalAmount.toFixed(2);
    } else {
      totalAmountInput.value = "";
      paymentAmountDisplay.textContent = "0.00";
    }
  }

  equipmentSelect.addEventListener("change", calculateTotalAmount);
  rentalStartInput.addEventListener("change", calculateTotalAmount);
  rentalEndInput.addEventListener("change", calculateTotalAmount);
});
