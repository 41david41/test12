let visningsmodus = 'grid';
let alleKunder = [];

window.onload = function () {
  hentOgVisPrivatkunder();
};

// Hent privatkunder fra server og lagre
function hentOgVisPrivatkunder() {
  fetch("hent_privatkunder.php")
    .then(response => response.json())
    .then(data => {
      alleKunder = data;
      visKunder(data);
    })
    .catch(error => {
      console.error("Feil ved henting av privatkunder:", error);
    });
}

// Vis kundeliste i valgt visningsmodus
function visKunder(liste) {
  const gridContainer = document.getElementById("privatkunde-grid");
  const tabell = document.getElementById("privatkunde-tabell");
  const tbody = document.getElementById("privatkunde-tbody");

  if (visningsmodus === 'grid') {
    gridContainer.style.display = "grid";
    tabell.style.display = "none";

    let html = liste.map(p => lagHTML(p)).join("");
    if (liste.length < 3) {
      const plassholdere = 3 - liste.length;
      for (let i = 0; i < plassholdere; i++) {
        html += `<div class="kundeprofil-kort placeholder-kort"></div>`;
      }
    }
    gridContainer.innerHTML = html;
  } else {
    gridContainer.style.display = "none";
    tabell.style.display = "table";

    const html = liste.map(p => lagHTML(p)).join("");
    tbody.innerHTML = html;
  }
}



// Bytt mellom grid og liste
function settVisning(modus) {
  visningsmodus = modus;
  visKunder(alleKunder);
}

// Søkefunksjon – flere felt
function filtrerKunder() {
  const sok = document.getElementById("sokefelt").value.toLowerCase();

  const filtrert = alleKunder.filter(k =>
    k.fornavn.toLowerCase().includes(sok) ||
    k.etternavn.toLowerCase().includes(sok) ||
    k.adresse1.toLowerCase().includes(sok) ||
    k.adresse2.toLowerCase().includes(sok) ||
    k.sted.toLowerCase().includes(sok) ||
    k.epost.toLowerCase().includes(sok)
  );

  visKunder(filtrert);
}

// Lag HTML for én kunde
function lagHTML(p) {
  const bilde = p.bilde ? p.bilde : "uploads/standard.png";

  const kortInnhold = `
    <div class="kort-knapper">
      <button class="rediger-kort-btn" onclick="event.stopPropagation(); window.location.href='../registrer_privatkunde/registrer_privatkunde.html?id=${p.id}'">✏️</button>
      <form action="slett_privatkunde.php" method="POST" onsubmit="event.stopPropagation(); return confirm('Er du sikker på at du vil slette denne privatkunden?')">
        <input type="hidden" name="id" value="${p.id}">
        <button type="submit" class="slett-kort-btn">🗑️</button>
      </form>
    </div>
  `;

  if (visningsmodus === 'grid') {
    return `
      <div class="kundeprofil-kort" onclick="visProfil(${p.id})">
        ${kortInnhold}
        <img src="${bilde}" class="kundeprofil-bilde">
        <h2 class="kundeprofil-navn">${p.fornavn} ${p.etternavn}</h2>
      </div>
    `;
  } else {
    return `
      <tr onclick="visProfil(${p.id})" class="kunde-tabell-rad">
        <td>${p.fornavn}</td>
        <td>${p.etternavn}</td>
        <td>${p.adresse1}</td>
        <td>${p.postnr}</td>
        <td>${p.sted}</td>
      </tr>
    `;
  }
}


// Vis detaljert modal
function visProfil(id) {
  fetch(`hent_privatkunde_med_id.php?id=${id}`)
    .then(response => response.json())
    .then(data => {
      const container = document.getElementById("modalInnhold");
      container.innerHTML = `
        <div style="text-align: left;">
          <h2 style="margin-bottom: 1rem;">${data.fornavn} ${data.etternavn}</h2>
          <p><strong>Adresse:</strong> ${data.adresse1}, ${data.adresse2}</p>
          <p><strong>Postnr/Sted:</strong> ${data.postnr} ${data.sted}</p>
          <p><strong>Telefon:</strong> ${data.telefon}</p>
          <p><strong>E-post:</strong> ${data.epost}</p>
          <p><strong>Kommentar:</strong> ${data.kommentar || "Ingen"}</p>
          ${data.bilde ? `<img src="${data.bilde}" style="max-width: 34%; margin-top: 1rem;">` : ""}
          ${data.pdf ? `<p style="margin-top: 1rem;"><a href="${data.pdf}" target="_blank">📄 Åpne PDF</a></p>` : ""}
        </div>
      `;

      document.getElementById("profilModal").classList.remove("hidden");
    })
    .catch(error => {
      console.error("Feil ved henting av profil:", error);
    });
}

// Lukk modal
function lukkModal() {
  document.getElementById("profilModal").classList.add("hidden");
}

window.addEventListener("keydown", e => {
  if (e.key === "Escape") lukkModal();
});

window.addEventListener("click", e => {
  const modal = document.getElementById("profilModal");
  if (e.target === modal) lukkModal();
});
