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
  const container = document.getElementById("bedriftkunde-tabell");
  container.className = visningsmodus === 'grid' ? 'kundeprofil-grid' : 'kundeprofil-liste';

  let html = liste.map(b => lagHTML(b)).join("");

  if (visningsmodus === 'grid' && liste.length < 3) {
    const antallPlassholdere = 3 - liste.length;
    for (let i = 0; i < antallPlassholdere; i++) {
      html += `<div class="kundeprofil-kort placeholder-kort"></div>`;
    }
  }

  container.innerHTML = html;
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
      <div class="kunde-rad" onclick="visProfil(${b.id})">
        ${kortInnhold}
        <strong>${b.bedriftsnavn}</strong> – ${b.adresse1}, ${b.postnr} ${b.sted}
      </div>
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
