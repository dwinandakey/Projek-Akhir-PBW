let slideIndex = 1;
let manualSlideTimer = null;

// Show the first slide initially
showSlides(slideIndex);

// Function to show the next or previous slide
function plusSlides(n) {
  clearTimeout(manualSlideTimer);
  showSlides((slideIndex += n));
}

// Function to show a specific slide
function currentSlide(n) {
  clearTimeout(manualSlideTimer);
  showSlides((slideIndex = n));
}

// Function to show slides automatically with a set interval
function showSlides(n) {
  let i;
  let slides = document.getElementsByClassName("mySlides");
  let dots = document.getElementsByClassName("dot");

  // Ensure slideIndex is within the range of slides
  if (n > slides.length) {
    slideIndex = 1;
  }
  if (n < 1) {
    slideIndex = slides.length;
  }

  // Hide all slides and remove "active" class from dots
  for (i = 0; i < slides.length; i++) {
    slides[i].style.display = "none";
  }
  for (i = 0; i < dots.length; i++) {
    dots[i].className = dots[i].className.replace(" active", "");
  }

  // Show the current slide and add "active" class to the corresponding dot
  slides[slideIndex - 1].style.display = "block";
  dots[slideIndex - 1].className += " active";

  // Reset and restart the timer for automatic slide change
  manualSlideTimer = setTimeout(() => {
    plusSlides(1);
  }, 3000);
}

// Event listeners for the dots
let dots = document.getElementsByClassName("dot");
for (let i = 0; i < dots.length; i++) {
  dots[i].addEventListener("click", () => {
    currentSlide(i + 1);
  });
}
