import "./bootstrap.js";
import "./styles/app.scss";

function initWatchlist() {
  const watchlistLink = document.getElementById("watchlist");
  if (!watchlistLink) return;

  if (watchlistLink.dataset.bound === "1") return;
  watchlistLink.dataset.bound = "1";

  watchlistLink.addEventListener("click", addToWatchlist);
}

function addToWatchlist(e) {
  e.preventDefault();

  const watchlistLink = e.currentTarget;
  const link = watchlistLink.href;

  fetch(link, {
    headers: { "X-Requested-With": "XMLHttpRequest" },
    credentials: "same-origin",
    cache: "no-store",
  })
    .then((res) => res.json())
    .then((data) => {
      const watchlistIcon = watchlistLink.querySelector("i");
      if (!watchlistIcon) return;

      if (data.isInWatchlist) {
        watchlistIcon.classList.remove("bi-heart");
        watchlistIcon.classList.add("bi-heart-fill");
      } else {
        watchlistIcon.classList.remove("bi-heart-fill");
        watchlistIcon.classList.add("bi-heart");
      }
    })
    .catch((err) => console.error("watchlist ajax error", err));
}

document.addEventListener("DOMContentLoaded", initWatchlist);
document.addEventListener("swup:contentReplaced", initWatchlist);

console.log("assets/app.js loaded ✅");
