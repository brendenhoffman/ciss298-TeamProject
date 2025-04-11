let isPageLoading = false;

function loadPage(page) {
  if (isPageLoading) {
    console.log("Page is already loading. Skipping...");
    return;
  }

  isPageLoading = true;
  const extension = page.endsWith(".php") ? "php" : "html";
  const pageName = page.replace(/\.(php|html)$/, "");
  const fullFile = `${pageName}.${extension}`;

  // Determine fetch target for the main page
  const fetchUrl = extension === "php"
    ? `load.php?page=${pageName}`
    : `pages/${fullFile}`;
    console.log(fetchUrl);

  fetch(fetchUrl)
    .then(response => {
      if (!response.ok) throw new Error("Page not found");
      return response.text();
    })
    .then(data => {
      document.getElementById("content-container").innerHTML = data;
      history.pushState({ page: pageName }, pageName, `?page=${fullFile}`);

      loadAside(pageName);
      loadForm(pageName);
      loadBootstrap();
      showSession();
    })
    .catch(error => {
      console.error(`Error loading ${pageName}:`, error);
      document.getElementById("content-container").innerHTML = "<h1>404 Not Found</h1>";
    })
    .finally(() => {
      isPageLoading = false; // Reset the flag when the loading is finished
    });
}

function loadForm(page) {
  const normalizedPage = page.replace(/\.(php|html)$/, "");
  const forms = document.getElementsByClassName('loadForm');

  Array.from(forms).forEach(form => {
    console.log("Binding to form:", form);

    form.addEventListener('submit', function (e) {
      e.preventDefault(); // Prevent default form submission
      const formData = new FormData(form);

      const formPhpFile = `${normalizedPage}Form.php`;  // Dynamically build the PHP file name

      fetch(formPhpFile, {
        method: 'POST',
        body: formData,
      })
        .then(response => {
          const contentType = response.headers.get("Content-Type") || "";
          return contentType.includes("application/json")
            ? response.json()
            : response.text();
        })
        .then(result => {
          if (typeof result === "object" && result.status === "success") {
            document.getElementById('content-container').innerHTML = result.message;

            if (result.refreshHeader) {
              refreshHeader();
              showSession();
            }
          } else {
            document.getElementById('content-container').innerHTML = result;
          }
        })
        .catch(error => {
          console.error('Error:', error);
          alert('Something went wrong!');
        });
    });
  });
}

function loadAside(page) {
  const asideContainer = document.getElementById("aside-container");
  const excludedPages = ["login", "signup",];
  const normalizedPage = page.replace(/\.(php|html)$/, "");

  if (!excludedPages.includes(normalizedPage)) {
    // Show and load aside if not excluded
    if (!asideContainer.dataset.loaded) {
      fetch("load.php?page=aside")
        .then(response => response.text())
        .then(data => {
          asideContainer.innerHTML = data;
          asideContainer.classList.remove("d-none");
          asideContainer.dataset.loaded = "true";
          attachAvailabilityFormHandler();
        })
        .catch(error => console.error("Error loading aside:", error));
    } else {
      asideContainer.classList.remove("d-none");
      attachAvailabilityFormHandler();
    }
  } else {
    asideContainer.classList.add("d-none");
  }
}

// Handle refreshes
window.addEventListener("DOMContentLoaded", () => {
  if (!isPageLoading) {
    const params = new URLSearchParams(window.location.search);
    const queryPage = params.get("page") || "home";
    loadPage(queryPage);
  }
});

window.addEventListener("popstate", () => {
  if (!isPageLoading) {
    const params = new URLSearchParams(window.location.search);
    const queryPage = params.get("page") || "home";
    loadPage(queryPage);
  }
});

function attachAvailabilityFormHandler() {
  console.log("Binding availability form");
  const form = document.getElementById("availability-form");
  if (!form) return;

  form.addEventListener("submit", function (e) {
    e.preventDefault();
    const formData = new FormData(this);

    fetch("checkAvailability.php", {
      method: "POST",
      body: formData
    })
      .then(res => res.json())
      .then(data => {
        const div = document.getElementById("availability-result");
        div.innerHTML = `<p>${data.message}</p>`;

        if (data.available) {
          const btn = document.createElement("button");
          btn.className = "btn btn-primary btn-sm mt-2";
          btn.innerText = "Reserve Now";
          btn.onclick = () => loadPage("reservation.php");
          div.appendChild(btn);
        }
      })
      .catch(err => {
        console.error(err);
        document.getElementById("availability-result").innerText = "An error occurred.";
      });
  });
}

function attachReservationFormHandler() {
  console.log("Binding reservation form");

  const form = document.getElementById("reservationForm");
  if (!form) {
    console.log("❌ Reservation form not found");
    return;
  }

  form.addEventListener("submit", function (e) {
    e.preventDefault();
    const formData = new FormData(form);

      // Use fetch to send data to processReservation.php
      fetch('processReservation.php', {
        method: 'POST',
        body: formData,
      })
      .then(response => response.text())  // Handle the response as text
      .then(result => {
        // Update the content area with the result (success or error message)
        document.getElementById('content-container').innerHTML = result;
      })
      .catch(error => {
        console.error('Error:', error);
        alert('Something went wrong!');
      });
  });
}

function attachTestimonialFormHandler() {
  console.log("Binding to testimonial form");

  const form = document.getElementById("testimonialForm");
  if (!form) return;

  form.addEventListener("submit", function (e) {
    e.preventDefault();  // Prevent the default form submission

    const formData = new FormData(form);

    // Submit the form data to the same page using fetch (no full page reload)
    fetch("", {
      method: 'POST',
      body: formData,
    })
    .then(response => response.text())  // Handle the response as text
    .then(result => {
      document.getElementById('testimonialsResult').innerHTML = result;  // Update with success message or errors
    })
    .catch(error => {
      console.error('Error:', error);
      alert('Something went wrong!');
    });
  });
}

// Helper function to jumpstart any Bootstrap not working due to dynamic loading
function loadBootstrap() {
  var carouselElement = document.querySelector('#carouselExample');
  if (!carouselElement) return;
  var carousel = new bootstrap.Carousel(carouselElement);
}

function refreshHeader() {
  fetch("templates/header.php")
    .then(r => r.text())
    .then(html => {
      document.getElementById("header-container").innerHTML = html;
    })
    .catch(err => console.error("Header update failed:", err));
}

function logoutUser() {
  fetch('/logout.php')
    .then(() => {
      refreshHeader();     // reload header
      loadPage("home");    // go back to homepage or login
    });
}

function showSession() {
  fetch("session.php")
    .then(res => res.json())
    .then(data => console.log("Session Data:", data))
    .catch(err => console.error("Session debug failed:", err));
}

