// ===============================
// JavaScript for visning og filtrering av borettslag
// ===============================

let visningsmodus = 'grid';           // Standard visningsmodus
let alleBorettslag = [];              // Alle hentede borettslag
let visteKunder = [];                 // Borettslag som vises i grensesnittet

// ===============================
// Henter borettslag ved innlasting av siden
// ===============================
window.onload = function () {
  hentOgVisBorettslag();
};

function hentOgVisBorettslag() {
  fetch("hent_borettslag.php")
    .then(response => response.json())
    .then(data => {
      alleBorettslag = data;
      visKunder(data);
    })
    .catch(error => {
      console.error("Feil ved henting av borettslag:", error);
    });
}

// ===============================
// Viser kundelisten (grid eller tabell)
// ===============================
function visKunder(liste) {
  visteKunder = liste;

  const gridContainer = document.getElementById("borettslag-grid");
  const tabell = document.getElementById("borettslag-tabell");
  const tbody = document.getElementById("borettslag-tbody");

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
  visKunder(alleBorettslag);
}

// ===============================
// Bytter visningsmodus og markerer aktiv knapp
// ===============================
function velgVisning(modus) {
  visningsmodus = modus;
  settVisning(modus);

  const grid = document.getElementById('gridBtn');
  const liste = document.getElementById('listeBtn');

  if (modus === 'grid') {
    grid.classList.add('selected');
    liste.classList.remove('selected');
  } else {
    liste.classList.add('selected');
    grid.classList.remove('selected');
  }
}

// ===============================
// Søker i borettslagslisten
// ===============================
function filtrerKunder() {
  const sok = document.getElementById("sokefelt").value.toLowerCase();

  const filtrert = alleBorettslag.filter(b =>
    b.orgnr.toLowerCase().includes(sok) ||
    b.navn.toLowerCase().includes(sok) ||
    b.styreleder.toLowerCase().includes(sok) ||
    b.adresse1.toLowerCase().includes(sok) ||
    b.adresse2.toLowerCase().includes(sok) ||
    b.postnr.toLowerCase().includes(sok) ||
    b.sted.toLowerCase().includes(sok) ||
    b.epost.toLowerCase().includes(sok) ||
    b.telefon.toLowerCase().includes(sok) ||
    b.kontaktperson.toLowerCase().includes(sok) ||
    b.kontaktpersonTlf.toLowerCase().includes(sok)
  );

  visKunder(filtrert);
}

// ===============================
// Filter-popup: åpne/lukke og avansert filtrering
// ===============================
document.getElementById("filter").addEventListener("click", () => {
  document.getElementById("filterPopup").classList.remove("hidden");
});

document.getElementById("lukkFilter").addEventListener("click", () => {
  document.getElementById("filterPopup").classList.add("hidden");
});

document.getElementById("filterForm").addEventListener("submit", function (e) {
  e.preventDefault();

  const form = new FormData(this);

  const filtrert = alleBorettslag.filter(kunde =>
    (!form.get("navn") || kunde.navn.toLowerCase().includes(form.get("navn").toLowerCase())) &&
    (!form.get("adresse") || kunde.adresse1.toLowerCase().includes(form.get("adresse").toLowerCase())) &&
    (() => {
      const min = parseInt(form.get("postnrMin")) || 0;
      const max = parseInt(form.get("postnrMax")) || 9999;
      const postnr = parseInt(kunde.postnr);
      return isNaN(postnr) ? false : postnr >= min && postnr <= max;
    })() &&
    (!form.get("sted") || kunde.sted.toLowerCase().includes(form.get("sted").toLowerCase())) &&
    (!form.get("styreleder") || kunde.styreleder.toLowerCase().includes(form.get("styreleder").toLowerCase())) &&
    (!form.get("styrelederTlf") || kunde.telefon.includes(form.get("styrelederTlf"))) &&
    (!form.get("epost") || kunde.epost.toLowerCase().includes(form.get("epost").toLowerCase())) &&
    (!form.get("kontaktperson") || kunde.kontaktperson.toLowerCase().includes(form.get("kontaktperson").toLowerCase())) &&
    (!form.get("kontaktpersonTlf") || kunde.kontaktpersonTlf.includes(form.get("kontaktpersonTlf")))
  );

  visKunder(filtrert);
  document.getElementById("filterPopup").classList.add("hidden");
});

// ===============================
// Genererer HTML for ett borettslag (kort eller tabellrad)
// ===============================
function lagHTML(b) {
  const bilde = b.bilde ? b.bilde : "uploads/standard.png";

  const kortInnhold = `
    <div class="kort-knapper">
      <button class="rediger-kort-btn" onclick="event.stopPropagation(); window.location.href='../registrer_borettslag/registrer_borettslaghtml.php?id=${b.id}'">✏️</button>
      <form action="slett_borettslag.php" method="POST" onsubmit="event.stopPropagation(); return confirm('Er du sikker på at du vil slette dette borettslaget?')">
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
        <h2 class="kundeprofil-navn">${b.navn}</h2>
      </div>
    `;
  } else {
    return `
      <tr onclick="visProfil(${b.id})" class="kunde-tabell-rad">
        <td>${b.orgnr}</td>
        <td>${b.navn}</td>
        <td>${b.styreleder}</td>
        <td>${b.adresse1}</td>
        <td>${b.adresse2}</td>
        <td>${b.postnr}</td>
        <td>${b.sted}</td>
        <td>${b.epost}</td>
        <td>${b.telefon}</td>
        <td>${b.kontaktperson}</td>
        <td>${b.kontaktpersonTlf}</td>
      </tr>
    `;
  }
}

// ===============================
// Viser profilinfo for valgt borettslag i modal
// ===============================
function visProfil(id) {
  fetch(`hent_borettslag_med_id.php?id=${id}`)
    .then(response => response.json())
    .then(data => {
      const container = document.getElementById("modalInnhold");
      container.innerHTML = `
        <div style="text-align: left;">
          <h2 style="margin-bottom: 1rem;">${data.navn}</h2>
          <p><strong>Organisasjonsnummer:</strong> ${data.orgnr}</p>
          <p><strong>Styreleder:</strong> ${data.styreleder}</p>
          <p><strong>Adresse:</strong> ${data.adresse1}, ${data.adresse2}</p>
          <p><strong>Postnr/Sted:</strong> ${data.postnr} ${data.sted}</p>
          <p><strong>Telefon:</strong> ${data.telefon}</p>
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

// ===============================
// Modal-lukking med esc eller klikk utenfor
// ===============================
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

// ===============================
// Eksport til CSV-fil
// ===============================
function exportToCSV() {
  if (!visteKunder || visteKunder.length === 0) {
    alert("Ingen borettslag å eksportere.");
    return;
  }

  const headers = [
    'Organisasjonsnummer', 'Navn', 'Styreleder', 'Adresse 1', 'Adresse 2',
    'Postnummer', 'Sted', 'E-post', 'Telefon', 'Kontaktperson', 'Tlf kontaktperson'
  ];
  const rows = [headers.join(';')];

  visteKunder.forEach(b => {
    rows.push([
      b.orgnr || '',
      b.navn || '',
      b.styreleder || '',
      b.adresse1 || '',
      b.adresse2 || '',
      b.postnr || '',
      b.sted || '',
      b.epost || '',
      b.telefon || '',
      b.kontaktperson || '',
      b.kontaktpersonTlf || ''
    ].map(val => `"${val.replace(/"/g, '""')}"`).join(';'));
  });

  const csvString = rows.join('\n');
  const blob = new Blob([csvString], { type: 'text/csv' });
  const link = document.createElement('a');
  link.href = URL.createObjectURL(blob);
  link.download = 'borettslag_liste.csv';
  link.click();
}
