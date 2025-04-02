let visningsmodus = 'grid';
let alleKunder = [];

window.onload = function () {
  hentOgVisBedriftskunder();
};

function hentOgVisBedriftskunder() {
  fetch("hent_bedriftkunde.php")
    .then(response => response.json())
    .then(data => {
      alleKunder = data;
      visKunder(data);
    })
    .catch(error => {
      console.error("Feil ved henting av bedriftskunder:", error);
    });
}

function visKunder(liste) {
  const gridContainer = document.getElementById("bedriftkunde-grid");
  const tabell = document.getElementById("bedriftkunde-tabell");
  const tbody = document.getElementById("bedriftkunde-tbody");

  if (visningsmodus === 'grid') {
    gridContainer.style.display = "grid";
    tabell.style.display = "none";

    let html = liste.map(b => lagHTML(b)).join("");
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

    const html = liste.map(b => lagHTML(b)).join("");
    tbody.innerHTML = html;
  }
}



function settVisning(modus) {
  visningsmodus = modus;
  visKunder(alleKunder);
}

function filtrerKunder() {
  const sok = document.getElementById("sokefelt").value.toLowerCase();

  const filtrert = alleKunder.filter(b =>
    b.bedriftsnavn.toLowerCase().includes(sok) ||
    b.adresse1.toLowerCase().includes(sok) ||
    b.adresse2.toLowerCase().includes(sok) ||
    b.sted.toLowerCase().includes(sok) ||
    b.kontaktperson.toLowerCase().includes(sok) ||
    b.epost.toLowerCase().includes(sok) ||
    (b.orgnr && b.orgnr.toLowerCase().includes(sok))
  );

  visKunder(filtrert);
}

function lagHTML(b) {
  const bilde = b.bilde ? b.bilde : "uploads/standard.png";

  const kortInnhold = `
    <div class="kort-knapper">
      <button class="rediger-kort-btn" onclick="event.stopPropagation(); window.location.href='../registrer_bedriftkunde/registrer_bedriftkunde.html?id=${b.id}'">✏️</button>
      <form action="slett_bedriftkunde.php" method="POST" onsubmit="event.stopPropagation(); return confirm('Er du sikker på at du vil slette denne bedriftskunden?')">
        <input type="hidden" name="id" value="${b.id}">
        <button type="submit" class="slett-kort-btn">🗑️</button>
      </form>
    </div>
  `;

  if (visningsmodus === 'grid') {
    return `
      <div class="kundeprofil-kort" onclick="visProfil(${b.id})">
        ${kortInnhold}
        <img src="${bilde}" class="kundeprofil-bilde">
        <h2 class="kundeprofil-navn">${b.bedriftsnavn}</h2>
      </div>
    `;
  } else {
    return `
    <tr onclick="visProfil(${b.id})" class="kunde-tabell-rad">
      <td>${b.bedriftsnavn}</td>
      <td>${b.adresse1}</td>
      <td>${b.postnr}</td>
      <td>${b.sted}</td>
    </tr>
    `;
  }
}

function visProfil(id) {
  fetch(`hent_bedriftskunde_med_id.php?id=${id}`)
    .then(response => response.json())
    .then(data => {
      const container = document.getElementById("modalInnhold");
      container.innerHTML = `
        <div style="text-align: left;">
          <h2 style="margin-bottom: 1rem;">${data.bedriftsnavn}</h2>
          <p><strong>Organisasjonsnummer:</strong> ${data.orgnr}</p>
          <p><strong>Adresse:</strong> ${data.adresse1}, ${data.adresse2}</p>
          <p><strong>Postnr/Sted:</strong> ${data.postnr} ${data.sted}</p>
          <p><strong>E-post:</strong> ${data.epost}</p>
          <p><strong>Kontaktperson:</strong> ${data.kontaktperson} (${data.kontaktpersonTlf})</p>
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
