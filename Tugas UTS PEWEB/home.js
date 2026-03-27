document.addEventListener("DOMContentLoaded", () => {
  const notifBtn = document.getElementById("notif-btn");

  // Logic untuk membuka notifikasi
  if (notifBtn) {
    notifBtn.addEventListener("click", () => {
      console.log("Notifikasi diklik");
      // Tambahkan logika popup atau dropdown di sini
    });
  }

  // Simulasi pencarian sederhana
  const searchInput = document.querySelector(".search-bar input");
  searchInput.addEventListener("keyup", (e) => {
    if (e.key === "Enter") {
      alert("Mencari: " + searchInput.value);
    }
  });
});
