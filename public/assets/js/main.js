/**
 * Main JavaScript file untuk Perpustakaan Fachri
 */

// Fungsi untuk animasi fade-in elemen
function fadeInElements() {
  const fadeElements = document.querySelectorAll(".fade-in");
  fadeElements.forEach((element, index) => {
    const delay = index * 100; // 100ms delay antar elemen
    setTimeout(() => {
      element.classList.add("visible");
    }, delay);
  });
}

// Fungsi untuk menangani notifikasi
function initNotifications() {
  const notifications = document.querySelectorAll(".notification");

  notifications.forEach((notification) => {
    // Auto-dismiss setelah 5 detik
    setTimeout(() => {
      notification.classList.add("fadeout");
      setTimeout(() => {
        notification.remove();
      }, 500);
    }, 5000);

    // Tombol tutup notifikasi
    const closeBtn = notification.querySelector(".notification-close");
    if (closeBtn) {
      closeBtn.addEventListener("click", () => {
        notification.classList.add("fadeout");
        setTimeout(() => {
          notification.remove();
        }, 500);
      });
    }
  });
}

// Inisialisasi tooltips
function initTooltips() {
  const tooltips = document.querySelectorAll("[data-tooltip]");

  tooltips.forEach((tooltip) => {
    tooltip.addEventListener("mouseenter", (e) => {
      const text = e.target.getAttribute("data-tooltip");

      const tooltipEl = document.createElement("div");
      tooltipEl.className = "custom-tooltip";
      tooltipEl.textContent = text;

      document.body.appendChild(tooltipEl);

      const rect = e.target.getBoundingClientRect();
      tooltipEl.style.top = `${rect.bottom + window.scrollY + 5}px`;
      tooltipEl.style.left = `${
        rect.left + window.scrollX + rect.width / 2 - tooltipEl.offsetWidth / 2
      }px`;
    });

    tooltip.addEventListener("mouseleave", () => {
      const tooltips = document.querySelectorAll(".custom-tooltip");
      tooltips.forEach((el) => el.remove());
    });
  });
}

// Efek ripple untuk tombol
function addRippleEffect() {
  const buttons = document.querySelectorAll(".btn-ripple");

  buttons.forEach((button) => {
    button.addEventListener("click", function (e) {
      const rect = this.getBoundingClientRect();
      const x = e.clientX - rect.left;
      const y = e.clientY - rect.top;

      const circle = document.createElement("span");
      circle.classList.add("ripple");
      circle.style.top = y + "px";
      circle.style.left = x + "px";

      this.appendChild(circle);

      setTimeout(() => {
        circle.remove();
      }, 600);
    });
  });
}

// Fungsi untuk animasi counter
function animateCounters() {
  const counters = document.querySelectorAll(".counter");

  counters.forEach((counter) => {
    const target = parseInt(counter.getAttribute("data-count"), 10);
    const duration = 1500; // 1.5 detik
    const stepTime = 30;
    const steps = duration / stepTime;
    const increment = target / steps;
    let current = 0;

    const timer = setInterval(() => {
      current += increment;

      if (current >= target) {
        counter.textContent = target.toLocaleString("id-ID");
        clearInterval(timer);
      } else {
        counter.textContent = Math.floor(current).toLocaleString("id-ID");
      }
    }, stepTime);
  });
}

// Toggle sidebar mobile
function initSidebar() {
  const sidebarToggle = document.getElementById("sidebar-toggle");
  const sidebar = document.getElementById("sidebar");

  if (sidebarToggle && sidebar) {
    sidebarToggle.addEventListener("click", () => {
      sidebar.classList.toggle("sidebar-open");
    });

    // Tutup sidebar saat klik di luar
    document.addEventListener("click", (e) => {
      if (
        sidebar.classList.contains("sidebar-open") &&
        !sidebar.contains(e.target) &&
        e.target !== sidebarToggle
      ) {
        sidebar.classList.remove("sidebar-open");
      }
    });
  }
}

// Animasi scroll to element
function smoothScroll() {
  document.querySelectorAll('a[href^="#"]').forEach((anchor) => {
    anchor.addEventListener("click", function (e) {
      e.preventDefault();

      const target = document.querySelector(this.getAttribute("href"));
      if (target) {
        target.scrollIntoView({
          behavior: "smooth",
          block: "start",
        });
      }
    });
  });
}

// Lazy loading images
function lazyLoadImages() {
  if ("IntersectionObserver" in window) {
    const imgObserver = new IntersectionObserver((entries, observer) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          const img = entry.target;
          const src = img.getAttribute("data-src");

          if (src) {
            img.src = src;
            img.removeAttribute("data-src");
            img.classList.add("img-loaded");
          }

          observer.unobserve(img);
        }
      });
    });

    document.querySelectorAll("img[data-src]").forEach((img) => {
      imgObserver.observe(img);
    });
  } else {
    // Fallback for browsers without IntersectionObserver
    document.querySelectorAll("img[data-src]").forEach((img) => {
      img.src = img.getAttribute("data-src");
      img.classList.add("img-loaded");
    });
  }
}

// Dark mode toggle
function initDarkMode() {
  const darkModeToggle = document.getElementById("darkModeToggle");
  if (!darkModeToggle) return;

  const body = document.body;

  // Tambahkan ikon
  const sunIcon = document.createElement("i");
  sunIcon.className = "fas fa-sun";
  const moonIcon = document.createElement("i");
  moonIcon.className = "fas fa-moon";

  darkModeToggle.prepend(sunIcon);
  darkModeToggle.append(moonIcon);

  // Memperbarui toggle berdasarkan localStorage
  const isDarkMode = localStorage.getItem("darkMode") === "true";
  if (isDarkMode) {
    body.classList.add("dark-mode");
  }

  darkModeToggle.addEventListener("click", function () {
    body.classList.toggle("dark-mode");
    localStorage.setItem("darkMode", body.classList.contains("dark-mode"));

    // Jika ada chart, perlu di-render ulang dalam mode gelap
    updateChartsForDarkMode();
  });
}

// Fungsi untuk update chart jika dalam dark mode
function updateChartsForDarkMode() {
  if (typeof Chart !== "undefined" && Chart.instances) {
    Object.values(Chart.instances).forEach((chart) => {
      chart.update();
    });
  }
}

// Dialog/modal
function initDialogs() {
  const dialogButtons = document.querySelectorAll("[data-dialog]");

  dialogButtons.forEach((button) => {
    button.addEventListener("click", () => {
      const dialogId = button.getAttribute("data-dialog");
      const dialog = document.getElementById(dialogId);

      if (dialog) {
        dialog.classList.remove("hidden");
        dialog.classList.add("dialog-open");

        // Close button
        dialog.querySelectorAll(".dialog-close").forEach((closeBtn) => {
          closeBtn.addEventListener("click", () => {
            dialog.classList.remove("dialog-open");
            setTimeout(() => {
              dialog.classList.add("hidden");
            }, 300);
          });
        });

        // Close on outside click
        dialog.addEventListener("click", (e) => {
          if (e.target === dialog) {
            dialog.classList.remove("dialog-open");
            setTimeout(() => {
              dialog.classList.add("hidden");
            }, 300);
          }
        });
      }
    });
  });
}

// Inisialisasi semua fungsi saat DOM sudah siap
document.addEventListener("DOMContentLoaded", function () {
  fadeInElements();
  initNotifications();
  initTooltips();
  addRippleEffect();
  animateCounters();
  initSidebar();
  smoothScroll();
  lazyLoadImages();
  initDarkMode();
  initDialogs();

  // Tambahan logika khusus halaman jika dibutuhkan
  const pageSpecific = document.body.getAttribute("data-page");
  if (pageSpecific && window[`init${pageSpecific}Page`]) {
    window[`init${pageSpecific}Page`]();
  }

  console.log("Perpustakaan Fachri JS initialized! ✨");
});

// Listen for dark mode toggle from other tabs/windows
window.addEventListener("storage", function (e) {
  if (e.key === "darkMode") {
    const isDarkMode = e.newValue === "true";
    if (isDarkMode) {
      document.body.classList.add("dark-mode");
    } else {
      document.body.classList.remove("dark-mode");
    }
    updateChartsForDarkMode();
  }
});
