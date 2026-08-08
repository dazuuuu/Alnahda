// =============================
// NAVBAR TOGGLE (Hamburger Menu)
// =============================
document.addEventListener("DOMContentLoaded", () => {
  const navToggle = document.querySelector(".nav-toggle");
  const siteNav = document.querySelector(".site-nav");
  const navLinks = document.querySelectorAll(".site-nav a");

  if (navToggle && siteNav) {
    navToggle.addEventListener("click", (e) => {
      e.stopPropagation();
      const isOpen = navToggle.getAttribute("aria-expanded") === "true";
      navToggle.setAttribute("aria-expanded", String(!isOpen));
      siteNav.classList.toggle("is-open", !isOpen);
    });

    navLinks.forEach((link) => {
      link.addEventListener("click", () => {
        navToggle.setAttribute("aria-expanded", "false");
        siteNav.classList.remove("is-open");
      });
    });

    document.addEventListener("click", (e) => {
      if (
        siteNav.classList.contains("is-open") &&
        !siteNav.contains(e.target) &&
        e.target !== navToggle
      ) {
        navToggle.setAttribute("aria-expanded", "false");
        siteNav.classList.remove("is-open");
      }
    });
  }
});


// =====================================
// BRAND SHOWCASE SLIDER (Auto + Manual)
// =====================================
document.addEventListener("DOMContentLoaded", () => {
  const slides = document.querySelectorAll(".brand-showcase-slide");
  const prevBtn = document.querySelector(".showcase-prev");
  const nextBtn = document.querySelector(".showcase-next");

  if (!slides.length) return;

  let currentIndex = 0;
  let autoSlide;

  function showSlide(index) {
    slides.forEach((slide, i) => {
      slide.style.display = i === index ? "block" : "none";
    });
  }

  function nextSlide() {
    currentIndex = (currentIndex + 1) % slides.length;
    showSlide(currentIndex);
  }

  function prevSlide() {
    currentIndex = (currentIndex - 1 + slides.length) % slides.length;
    showSlide(currentIndex);
  }

  function startAutoSlide() {
    autoSlide = setInterval(nextSlide, 10000);
  }

  function resetAutoSlide() {
    clearInterval(autoSlide);
    startAutoSlide();
  }

  showSlide(currentIndex);
  startAutoSlide();

  if (nextBtn) nextBtn.addEventListener("click", () => { nextSlide(); resetAutoSlide(); });
  if (prevBtn) prevBtn.addEventListener("click", () => { prevSlide(); resetAutoSlide(); });
});


// =====================
// COUNT-UP ANIMATIONS
// =====================
document.addEventListener("DOMContentLoaded", () => {
  const counters = document.querySelectorAll(".counter");

  const animateCounter = (counter) => {
    const target = +counter.getAttribute("data-count");
    const suffix = counter.getAttribute("data-suffix") || "";
    const duration = 2000;
    const stepTime = Math.max(Math.floor(duration / target), 20);
    let current = 0;

    const timer = setInterval(() => {
      current += 1;
      counter.textContent = current + suffix;
      if (current >= target) clearInterval(timer);
    }, stepTime);
  };

  const observer = new IntersectionObserver((entries) => {
    entries.forEach((entry) => {
      if (entry.isIntersecting) {
        animateCounter(entry.target);
        observer.unobserve(entry.target);
      }
    });
  }, { threshold: 0.5 });

  counters.forEach((counter) => observer.observe(counter));
});


// ======================================
// TESTIMONIALS CAROUSEL (prev/next scroll)
// ======================================
document.addEventListener("DOMContentLoaded", () => {
  const track = document.querySelector(".testimonial-track");
  const controls = document.querySelector(".testimonial-controls");
  if (!track || !controls) return;

  const scrollByCard = (direction) => {
    const card = track.querySelector(".testimonial-card");
    const distance = card ? card.getBoundingClientRect().width + 24 : track.clientWidth * 0.9;
    track.scrollBy({ left: direction * distance, behavior: "smooth" });
  };

  controls.querySelectorAll(".control-btn").forEach((btn) => {
    btn.addEventListener("click", () => {
      scrollByCard(btn.getAttribute("data-direction") === "prev" ? -1 : 1);
    });
  });
});

document.addEventListener("DOMContentLoaded", () => {
  const yearSelect = document.getElementById("returnYear");
  if (!yearSelect) return;

  const currentYear = new Date().getFullYear();
  const startYear = 1990; // Adjust if needed

  for (let y = currentYear; y >= startYear; y--) {
    const opt = document.createElement("option");
    opt.value = y;
    opt.textContent = y;
    yearSelect.appendChild(opt);
  }
});


// ===========================================
// SAUDIA ARABIA LOGIC — Dynamic Form Behavior
// ===========================================
document.addEventListener("DOMContentLoaded", () => {
  const saudiaRadios = document.querySelectorAll('input[name="travelledSaudia"]');
  const saudiaOnlyWrap = document.querySelector(".saudia-only");
  const willingLabel = document.getElementById("willingLabel");
  if (!saudiaRadios.length || !willingLabel || !saudiaOnlyWrap) return; // this form isn't on the current page

  function toggleSaudiaSections(value) {
    if (value === "yes") {
      saudiaOnlyWrap.style.display = "grid";
      willingLabel.textContent = "Willing to Go Back to SAUDIA? *";
    } else {
      saudiaOnlyWrap.style.display = "none";
      willingLabel.textContent = "Willing to Go to SAUDIA? *";
    }
  }

  saudiaRadios.forEach((radio) => {
    radio.addEventListener("change", () => {
      if (radio.checked) toggleSaudiaSections(radio.value);
    });
  });

  // Initialize on page load
  const selected = document.querySelector('input[name="travelledSaudia"]:checked');
  toggleSaudiaSections(selected ? selected.value : "no");
});


// ==========================================
// SPONSOR ISSUE — reveal the explanation box
// ==========================================
document.addEventListener("DOMContentLoaded", () => {
  const issueRadios = document.querySelectorAll('input[name="issueWithSponsor"]');
  const explain = document.getElementById("contractExplain");
  if (!issueRadios.length || !explain) return;

  issueRadios.forEach((radio) => {
    radio.addEventListener("change", () => {
      explain.classList.toggle("hidden", radio.value !== "yes" || !radio.checked);
    });
  });
});


// ==================================
// REGISTRATION FORM SUBMISSION LOGIC
// ==================================
(function () {
  const form = document.getElementById("registration-form");
  if (!form) return;

  const formFeedback = form.querySelector(".form-feedback");
  const submitButton = document.querySelector(".application-submit");

  function setFeedback(type, message) {
    if (!formFeedback) return;
    formFeedback.textContent = message || "";
    formFeedback.classList.remove("error", "success");
    if (type) formFeedback.classList.add(type);
  }

  function validateRequiredFields() {
    const requiredFields = Array.from(form.querySelectorAll("[required]"));
    let firstInvalid = null;

    requiredFields.forEach((field) => {
      let valid = true;
      const val = (field.value || "").trim();

      if (field.type === "checkbox") valid = field.checked;
      else if (field.type === "number") valid = val !== "" && !isNaN(Number(val)) && field.checkValidity();
      else valid = val !== "" && field.checkValidity();

      if (!valid) {
        if (!firstInvalid) firstInvalid = field;
        field.setAttribute("aria-invalid", "true");
      } else {
        field.removeAttribute("aria-invalid");
      }
    });

    return { hasInvalid: !!firstInvalid, firstInvalid };
  }

  form.addEventListener("input", (e) => {
    const f = e.target;
    if (f && f.required) {
      const val = (f.value || "").trim();
      let valid = true;
      if (f.type === "checkbox") valid = f.checked;
      else if (f.type === "number") valid = val !== "" && !isNaN(Number(val)) && f.checkValidity();
      else valid = val !== "" && f.checkValidity();
      if (valid) f.removeAttribute("aria-invalid");
    }
    if (formFeedback?.classList.contains("error")) setFeedback("", "");
  });

  form.addEventListener("submit", async (event) => {
    event.preventDefault();

    const basic = validateRequiredFields();
    if (basic.hasInvalid) {
      setFeedback("error", "Please complete all required fields before submitting.");
      basic.firstInvalid?.focus();
      return;
    }

    if (submitButton) submitButton.disabled = true;

    try {
      const fd = new FormData(form);
      const action = form.getAttribute("data-action") || form.action || "/api/applications";
      const res = await fetch(action, { method: "POST", body: fd, headers: { Accept: "application/json" } });
      const data = await res.json().catch(() => null);

      if (res.ok && data && data.success) {
        setFeedback("success", data.message || "✅ Application submitted successfully! Our recruiters will be in touch.");
        form.reset();
        toggleSaudiaSectionsAfterReset();
      } else {
        setFeedback("error", (data && data.message) || "We could not save your application. Please try again.");
      }
    } catch (err) {
      console.error(err);
      setFeedback("error", "We could not save your application. Please try again.");
    } finally {
      if (submitButton) submitButton.disabled = false;
    }
  });

  function toggleSaudiaSectionsAfterReset() {
    const wrap = document.querySelector(".saudia-only");
    if (wrap) wrap.style.display = "none";
    const explain = document.getElementById("contractExplain");
    if (explain) explain.classList.add("hidden");
    const label = document.getElementById("willingLabel");
    if (label) label.textContent = "Willing to Go to SAUDIA? *";
  }
})();


// =======================================
// BOOKING APPOINTMENT CALENDAR + MODAL
// =======================================
(function () {
  const calendarRoot = document.getElementById("checklist-calendar");
  if (!calendarRoot) return;

  const monthLabel = calendarRoot.querySelector(".calendar-current");
  const body = calendarRoot.querySelector(".calendar-body");
  const prevBtn = calendarRoot.querySelector(".calendar-nav--prev");
  const nextBtn = calendarRoot.querySelector(".calendar-nav--next");
  const bookingModal = document.getElementById("bookingModal");
  const closeBooking = document.getElementById("closeBooking");
  const appointmentDate = document.getElementById("appointment-date");
  const confirmButton = bookingModal?.querySelector(".confirm-btn");
  const bookingMessage = document.getElementById("bookingMessage");

  if (confirmButton) {
    confirmButton.addEventListener("click", () => {
      const modalDate = appointmentDate.value;
      const modalTime = document.getElementById("appointment-time").value;

      document.getElementById("appointment-date-hidden").value = modalDate;
      document.getElementById("appointment-time-hidden").value = modalTime;

      if (bookingMessage) bookingMessage.classList.remove("hidden");

      setTimeout(() => {
        bookingModal.classList.add("hidden");
        bookingMessage.classList.add("hidden");
      }, 2000);
    });
  }

  let today = new Date();
  let visibleYear = today.getFullYear();
  let visibleMonth = today.getMonth();

  function renderCalendar(year, month) {
    body.innerHTML = "";
    const firstDay = new Date(year, month, 1).getDay();
    const lastDate = new Date(year, month + 1, 0).getDate();

    let row = document.createElement("div");
    row.className = "calendar-row";

    for (let i = 0; i < firstDay; i++) {
      const empty = document.createElement("div");
      empty.className = "calendar-cell is-empty";
      row.appendChild(empty);
    }

    for (let d = 1; d <= lastDate; d++) {
      if (row.children.length === 7) {
        body.appendChild(row);
        row = document.createElement("div");
        row.className = "calendar-row";
      }

      const cell = document.createElement("div");
      cell.className = "calendar-cell";
      cell.textContent = d;

      if (
        d === today.getDate() &&
        month === today.getMonth() &&
        year === today.getFullYear()
      ) {
        cell.classList.add("is-today");
      }

      cell.addEventListener("click", () => {
        appointmentDate.value = `${year}-${String(month + 1).padStart(2, "0")}-${String(d).padStart(2, "0")}`;
        bookingModal.classList.remove("hidden");
      });

      row.appendChild(cell);
    }

    while (row.children.length < 7) {
      const empty = document.createElement("div");
      empty.className = "calendar-cell is-empty";
      row.appendChild(empty);
    }
    body.appendChild(row);

    const monthName = new Intl.DateTimeFormat("en", { month: "long" }).format(new Date(year, month));
    monthLabel.textContent = `${monthName} ${year}`;
  }

  prevBtn.addEventListener("click", () => {
    visibleMonth--;
    if (visibleMonth < 0) {
      visibleMonth = 11;
      visibleYear--;
    }
    renderCalendar(visibleYear, visibleMonth);
  });

  nextBtn.addEventListener("click", () => {
    visibleMonth++;
    if (visibleMonth > 11) {
      visibleMonth = 0;
      visibleYear++;
    }
    renderCalendar(visibleYear, visibleMonth);
  });

  closeBooking.addEventListener("click", () => bookingModal.classList.add("hidden"));
  renderCalendar(visibleYear, visibleMonth);
})();
