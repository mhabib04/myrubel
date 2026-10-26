// ===== Nomor WA admin diambil dari <body data-wa-number> (diatur di panel admin) =====
const WA_NUMBER = document.body.dataset.waNumber || "";

// ===== Ikon (lucide) =====
const ICONS = {
  check: '<path d="M20 6 9 17l-5-5"/>',
  chat: '<path d="M7.9 20A9 9 0 1 0 4 16.1L2 22Z"/>',
  arrow: '<path d="M5 12h14"/><path d="m12 5 7 7-7 7"/>',
  left: '<path d="m15 18-6-6 6-6"/>',
  right: '<path d="m9 18 6-6-6-6"/>',
  home: '<path d="M15 21v-8a1 1 0 0 0-1-1h-4a1 1 0 0 0-1 1v8"/><path d="M3 10a2 2 0 0 1 .709-1.528l7-5.999a2 2 0 0 1 2.582 0l7 5.999A2 2 0 0 1 21 10v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>',
  trophy: '<path d="M6 9H4.5a2.5 2.5 0 0 1 0-5H6"/><path d="M18 9h1.5a2.5 2.5 0 0 0 0-5H18"/><path d="M4 22h16"/><path d="M10 14.66V17c0 .55-.47.98-.97 1.21C7.85 18.75 7 20.24 7 22"/><path d="M14 14.66V17c0 .55.47.98.97 1.21C16.15 18.75 17 20.24 17 22"/><path d="M18 2H6v7a6 6 0 0 0 12 0V2Z"/>',
  users: '<path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>',
  award: '<circle cx="12" cy="8" r="6"/><path d="M15.477 12.89 17 22l-5-3-5 3 1.523-9.11"/>',
  calendar: '<rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/>',
  close: '<path d="M18 6 6 18M6 6l12 12"/>',
  clock: '<circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/>',
  menu: '<path d="M4 7h16M4 12h16M4 17h16"/>',
  share: '<circle cx="18" cy="5" r="3"/><circle cx="6" cy="12" r="3"/><circle cx="18" cy="19" r="3"/><path d="m8.59 13.51 6.83 3.98M15.41 6.51l-6.82 3.98"/>',
  pin: '<path d="M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0"/><circle cx="12" cy="10" r="3"/>',
  ig: '<rect x="3" y="3" width="18" height="18" rx="5"/><circle cx="12" cy="12" r="4"/><path d="M17.5 6.5h.01"/>',
  tiktok: '<path d="M16 3c.4 2.4 1.9 3.9 4 4.1v3.1a7 7 0 0 1-4-1.3v6.4a5.7 5.7 0 1 1-5.7-5.7h.4v3.2a2.6 2.6 0 1 0 2.2 2.5V3Z"/>',
};
document.body.insertAdjacentHTML("afterbegin",
  `<svg width="0" height="0" style="position:absolute" aria-hidden="true">${
    Object.entries(ICONS).map(([k, p]) => `<symbol id="i-${k}" viewBox="0 0 24 24">${p}</symbol>`).join("")
  }</svg>`);

// ===== Link WhatsApp (nomor cabang lewat data-wa-number, selain itu nomor utama) =====
document.querySelectorAll("[data-wa]").forEach((el) => {
  el.href = `https://wa.me/${el.dataset.waNumber || WA_NUMBER}?text=${encodeURIComponent(el.dataset.wa)}`;
  el.target = "_blank";
  el.rel = "noopener";
});

// ===== Pilihan kontak WhatsApp (muncul jika ada lebih dari satu nomor) =====
const picker = document.getElementById("wa-pick");
if (picker) {
  const waLink = (number, text) => `https://wa.me/${number}?text=${encodeURIComponent(text)}`;
  document.querySelectorAll("[data-wa-pick]").forEach((el) => el.addEventListener("click", (e) => {
    e.preventDefault();
    picker.querySelectorAll("[data-number]").forEach((a) => { a.href = waLink(a.dataset.number, el.dataset.wa); });
    picker.showModal();
  }));
  picker.querySelector(".wa-pick-close").addEventListener("click", () => picker.close());
  picker.addEventListener("click", (e) => { if (e.target === picker) picker.close(); }); // klik di luar kotak
  picker.querySelectorAll("[data-number]").forEach((a) => a.addEventListener("click", () => picker.close()));
}

// ===== Tombol Bagikan: menu bagikan bawaan HP, atau salin link di komputer =====
const toast = (message) => {
  const el = document.createElement("div");
  el.className = "toast";
  el.setAttribute("role", "status");
  el.textContent = message;
  document.body.append(el);
  setTimeout(() => el.remove(), 2500);
};
document.querySelectorAll("[data-share]").forEach((btn) => btn.addEventListener("click", async () => {
  const data = { title: btn.dataset.shareTitle || document.title, url: location.href };
  if (navigator.share) {
    try { await navigator.share(data); } catch { /* dibatalkan pengguna */ }
    return;
  }
  try {
    await navigator.clipboard.writeText(data.url);
    toast("Link disalin");
  } catch {
    window.prompt("Salin link halaman ini:", data.url);
  }
}));

// ===== Tombol Kembali: pakai riwayat browser agar beranda langsung di posisi terakhir =====
const samePath = (a, b) => a.replace(/\/$/, "") === b.replace(/\/$/, "");
document.querySelectorAll("[data-back]").forEach((link) => link.addEventListener("click", (e) => {
  const from = document.referrer ? new URL(document.referrer) : null;
  if (from && from.origin === location.origin && samePath(from.pathname, new URL(link.href).pathname)) {
    e.preventDefault();
    history.back();
  }
}));

// Gulir halus baru dipakai setelah halaman siap: saat dibuka/kembali, posisi langsung tanpa animasi.
addEventListener("load", () => setTimeout(() => document.documentElement.classList.add("smooth-scroll"), 200));

// ===== Navbar: menu HP & menu aktif sesuai section =====
const burger = document.querySelector(".burger");
const navLinks = document.getElementById("nav-links");
if (burger && navLinks) {
  burger.addEventListener("click", () => {
    const open = navLinks.classList.toggle("open");
    burger.setAttribute("aria-expanded", open);
  });
  navLinks.querySelectorAll("a").forEach((a) => a.addEventListener("click", () => {
    navLinks.classList.remove("open");
    burger.setAttribute("aria-expanded", false);
  }));
}
const anchors = [...document.querySelectorAll('.nav-links a[href^="#"]')];
if (anchors.length) {
  const sections = anchors.map((a) => document.querySelector(a.getAttribute("href")));
  const onScroll = () => {
    let i = 0;
    sections.forEach((s, k) => { if (s && scrollY >= s.offsetTop - 120) i = k; });
    anchors.forEach((a, k) => a.classList.toggle("active", k === i));
  };
  addEventListener("scroll", onScroll, { passive: true });
  onScroll();
}

// ===== Carousel « » =====
// Slide berjalan otomatis tiap 4 detik, berhenti saat disentuh/diarahkan kursor.
const AUTOPLAY_MS = 4000;
const reduceMotion = matchMedia("(prefers-reduced-motion: reduce)").matches;

document.querySelectorAll(".carousel").forEach((c) => {
  const track = c.querySelector(".track");
  const bar = c.parentElement.querySelector(".bar span");

  // geser satu slide; di ujung kembali ke awal
  const step = (dir) => {
    const slide = track.firstElementChild;
    if (!slide) return;
    const gap = parseFloat(getComputedStyle(track).columnGap) || 0;
    const width = slide.getBoundingClientRect().width + gap;
    const max = track.scrollWidth - track.clientWidth;
    if (dir > 0 && track.scrollLeft >= max - 4) track.scrollTo({ left: 0, behavior: "smooth" });
    else if (dir < 0 && track.scrollLeft <= 4) track.scrollTo({ left: max, behavior: "smooth" });
    else track.scrollBy({ left: dir * width, behavior: "smooth" });
  };

  let timer = null;
  let visible = false;
  const stop = () => { clearInterval(timer); timer = null; };
  const start = () => {
    stop();
    if (!reduceMotion && visible) timer = setInterval(() => step(1), AUTOPLAY_MS);
  };

  c.querySelectorAll(".arrow").forEach((b) => b.addEventListener("click", () => {
    step(Number(b.dataset.dir));
    start(); // mulai ulang hitungan setelah diklik
  }));
  c.addEventListener("mouseenter", stop);
  c.addEventListener("mouseleave", start);
  track.addEventListener("touchstart", stop, { passive: true });
  track.addEventListener("touchend", () => setTimeout(start, AUTOPLAY_MS), { passive: true });

  // hanya berjalan saat carousel terlihat di layar
  new IntersectionObserver(([e]) => { visible = e.isIntersecting; visible ? start() : stop(); }, { threshold: 0.3 }).observe(c);
  if (bar) {
    track.addEventListener("scroll", () => {
      const max = track.scrollWidth - track.clientWidth;
      bar.style.transform = `translateX(${max ? (track.scrollLeft / max) * 200 : 0}%)`;
    }, { passive: true });
  }
});
