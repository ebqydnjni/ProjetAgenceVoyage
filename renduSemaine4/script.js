function closeSignIn() {
  document.getElementById("popup2").style.display = "none";
}

function openSignUp() {
  document.getElementById("popup").style.display = "block";
}

function closeSignUp() {
  document.getElementById("popup").style.display = "none";
}

function openSignIn() {
  document.getElementById("popup2").style.display = "block";
}

document
  .getElementById("openPopup")
  .addEventListener("click", function openSignUp() {
    document.getElementById("popup").style.display = "block";
  });

document
  .getElementById("closePopup")
  .addEventListener("click", function closeSignUp() {
    document.getElementById("popup").style.display = "none";
  });

document
  .getElementById("openPopup2")
  .addEventListener("click", function openSignIn() {
    document.getElementById("popup2").style.display = "block";
  });

document
  .getElementById("closePopup2")
  .addEventListener("click", function closeSignUp() {
    document.getElementById("popup2").style.display = "none";
  });

document.getElementById("signup-link").addEventListener("click", function () {
  closeSignIn();
  openSignUp();
});

document.getElementById("signin-link").addEventListener("click", function () {
  closeSignUp();
  openSignIn();
});
$(document).ready(function () {
  $(window).scroll(function () {
    var scrollTop = $(window).scrollTop();
    var divHeight = $("#Recherche").height() * 1.5;
    $("#Recherche").css("opacity", 1 - scrollTop / divHeight);
  });
});
$(document).ready(function () {
  $(window).scroll(function () {
    var scrollTop = $(window).scrollTop();
    var divHeight = $("#destination").height() * 1.5;
    $("#destination").css("opacity", 1 - scrollTop / divHeight);
  });
});

function logout() {
  window.location.href = "accueil.html";
}
