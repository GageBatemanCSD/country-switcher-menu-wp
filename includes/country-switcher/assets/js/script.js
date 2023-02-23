function toggleCountryDisplayMobile() {
  let countriesDisplayMobile = document.getElementById("countriesMobile");
  if (countriesDisplayMobile.style.display == "none") {
    countriesDisplayMobile.style.display = "flex";
  } else {
    countriesDisplayMobile.style.display = "none";
  }
}

function toggleCountryDisplay() {
  let countriesDisplay = document.getElementById("countries");
  if (countriesDisplay.style.display == "none") {
    countriesDisplay.style.display = "flex";
  } else {
    countriesDisplay.style.display = "none";
  }
}
