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
      k.postnr.toLowerCase().includes(sok) ||
      k.telefon.toLowerCase().includes(sok) ||
      k.epost.toLowerCase().includes(sok)
    );

    visKunder(filtrert);
  }

  // Lag HTML for én kunde
  function lagHTML(p) {
    const bilde = p.bilde ? p.bilde : "uploads/standard.png";

    const kortInnhold = `
      <div class="kort-knapper">
        <button class="rediger-kort-btn" onclick="event.stopPropagation(); window.location.href='../registrer_privatkunde/registrer_privatkundehtml.php?id=${p.id}'">✏️</button>
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
          <td>${p.epost}</td>
          <td>${p.telefon}</td>
          <td>${p.adresse1}</td>
          <td>${p.adresse2}</td>
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

  function velgVisning(modus) {
    visningsmodus = modus;
    settVisning(modus); // denne er allerede definert i koden din

    // Oppdater visuell knapp-status
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

  document.getElementById("filter").addEventListener("click", () => {
    document.getElementById("filterPopup").classList.remove("hidden");
  });
  
  document.getElementById("lukkFilter").addEventListener("click", () => {
    document.getElementById("filterPopup").classList.add("hidden");
  });
  
  document.getElementById("filterForm").addEventListener("submit", function (e) {
    e.preventDefault();
  
    const form = new FormData(this);
  
    const filtrert = alleKunder.filter(kunde =>
      (!form.get("fornavn") || kunde.fornavn.toLowerCase().includes(form.get("fornavn").toLowerCase())) &&
      (!form.get("etternavn") || kunde.etternavn.toLowerCase().includes(form.get("etternavn").toLowerCase())) &&
      (!form.get("epost") || kunde.epost.toLowerCase().includes(form.get("epost").toLowerCase())) &&
      (!form.get("telefon") || kunde.telefon.includes(form.get("telefon"))) &&
      (!form.get("adresse1") || kunde.adresse1.toLowerCase().includes(form.get("adresse1").toLowerCase())) &&
      (!form.get("adresse2") || kunde.adresse2.toLowerCase().includes(form.get("adresse2").toLowerCase())) &&
      (() => {
        const min = parseInt(form.get("postnrMin")) || 0;
        const max = parseInt(form.get("postnrMax")) || 9999;
        const postnr = parseInt(kunde.postnr);
        return isNaN(postnr) ? false : postnr >= min && postnr <= max;
      })() &&
      (!form.get("sted") || kunde.sted.toLowerCase().includes(form.get("sted").toLowerCase())) &&
      (!form.get("kommentar") || (kunde.kommentar || "").toLowerCase().includes(form.get("kommentar").toLowerCase()))
    );
  
    visKunder(filtrert);
    document.getElementById("filterPopup").classList.add("hidden");
  });
  
  // Function to switch between grid and list view
function settVisning(visningType) {
    const isGridView = visningType === 'grid';
    const grid = document.getElementById('privatkunde-grid');
    const table = document.getElementById('privatkunde-tabell');
    const exportBtn = document.getElementById('exportBtn');
    
    // Toggle the visibility of the table and grid
    if (isGridView) {
        grid.style.display = 'block';
        table.style.display = 'none';
        exportBtn.classList.add('disabled');  // Disable the export button in grid view
    } else {
        grid.style.display = 'none';
        table.style.display = 'block';
        exportBtn.classList.remove('disabled');  // Enable the export button in list view
    }
}

// Function to export the table content to a CSV file
function exportToCSV() {
    const isGridView = document.getElementById('privatkunde-grid').style.display !== 'none';
    
    // If in grid view, show an alert to change to list view
    if (isGridView) {
        alert("Du må bytte til listevisning for å eksportere til CSV.");
        return; // Prevent further action if in grid view
    }

    const rows = [];
    
    // If in table view, get the table data
    if (!isGridView) {
        const table = document.getElementById('privatkunde-tabell');
        
        const headers = table.querySelectorAll('thead th');
        const headerRow = [];
        for (let i = 0; i < headers.length; i++) {
            headerRow.push(headers[i].innerText.trim());
        }
        rows.push(headerRow.join(';'));
        
        const tbody = table.querySelector('tbody');
        const tableRows = tbody.querySelectorAll('tr');
        tableRows.forEach(row => {
            const cols = row.querySelectorAll('td');
            const rowData = [];
            for (let i = 0; i < cols.length; i++) {
                rowData.push(cols[i].innerText.trim());
            }
            rows.push(rowData.join(';'));
        });
    } else {
        const gridItems = document.querySelectorAll('#privatkunde-grid .kundeprofil');
        const headerRow = ['Fornavn', 'Etternavn', 'Adresse', 'Postnummer', 'Sted'];
        rows.push(headerRow.join(';'));

        gridItems.forEach(item => {
            const fornavn = item.querySelector('.fornavn').innerText.trim();
            const etternavn = item.querySelector('.etternavn').innerText.trim();
            const adresse = item.querySelector('.adresse').innerText.trim();
            const postnummer = item.querySelector('.postnummer').innerText.trim();
            const sted = item.querySelector('.sted').innerText.trim();
            rows.push([fornavn, etternavn, adresse, postnummer, sted].join(';'));
        });
    }

    const csvString = rows.join('\n');
    const blob = new Blob([csvString], { type: 'text/csv' });
    const link = document.createElement('a');
    link.href = URL.createObjectURL(blob);
    link.download = 'privatkunde_liste.csv';
    link.click();
}