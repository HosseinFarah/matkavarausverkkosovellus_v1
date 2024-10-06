function setActiveLink() {
    const currentUrl = window.location.href;
      const navLinks = document.querySelectorAll("#myTopnav a");
    navLinks.forEach((link) => {
      link.classList.remove("active");
    });
    navLinks.forEach((link) => {
  
      if (link.href === currentUrl) {
        link.classList.add("active");
      }
    });
  }
  window.onload = setActiveLink;
  
  // // Get all the nav links
  // var navLinks = document.querySelectorAll("#myTopnav a");
  // // Loop through all the links
  // for (var i = 0; i < navLinks.length; i++) {
  //   // Add a click event listener to each link
  //   navLinks[i].addEventListener("click", function () {
  //     // Remove the active class from all links
  //     for (var i = 0; i < navLinks.length; i++) {
  //       navLinks[i].classList.remove("active");
  //     }
  //     // Add the active class to the clicked link
  //     this.classList.add("active");
  //   });
  // }
  
  function myFunction() {
    var x = document.getElementById("myTopnav");
    if (x.className === "topnav") {
      x.className += " responsive";
    } else {
      x.className = "topnav";
    }
  }

  
  // Automatically reset layout when resizing to desktop view
  window.addEventListener('resize', function() {
    var x = document.getElementById("myTopnav");
    if (window.innerWidth > 900) {
      x.className = "topnav"; // Reset to flex layout
    }
  });
  
  
  (() => {
    "use strict";
  
    // Fetch all the forms we want to apply custom Bootstrap validation styles to
    const forms = document.querySelectorAll(".needs-validation");
  
    // Loop over them and prevent submission
    Array.from(forms).forEach((form) => {
      form.addEventListener(
        "submit",
        (event) => {
          if (!form.checkValidity()) {
            event.preventDefault();
            event.stopPropagation();
          }
  
          form.classList.add("was-validated");
        },
        false
      );
    });
  })();
  
  // location.pathname.split("/").pop() === "index.html"
  
  // Fetching data from the server for the data list
  fetch("kaupungit.json")
    .then((res) => res.json())
    .then((data) => {
      const kaupungitList = data.kaupungit; 
      const kaupungitElement = document.querySelector("#kaupungit");
  
      kaupungitList.forEach((kaupunki) => {
        let opti = document.createElement("option");
        opti.value = kaupunki.value;
        kaupungitElement.append(opti);
      });
  
      console.log(kaupungitElement);
    })
    .catch((err) => {
      console.error(`Error >>> ${err}`);
    });
  
  

  //   document.getElementById('read-more-btn').addEventListener('click', function(event) {
  //     event.preventDefault();
  //     var moreContent = document.querySelector('.more-content');
  //     var button = this;

  //     if (moreContent.style.display === 'none') {
  //         moreContent.style.display = 'inline';
  //         button.textContent = 'Read Less';
  //     } else {
  //         moreContent.style.display = 'none';
  //         button.textContent = 'Read More';
  //     }
  // });

// mapbox