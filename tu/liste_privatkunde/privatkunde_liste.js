// ✅ Når siden lastes, hent alle privatkunder og vis dem i containeren
window.onload = function () {
    fetch("hent_privatkunder.php")
      .then(response => response.text())
      .then(data => {
        document.getElementById("privatkunde-tabell").innerHTML = data;
      })
      .catch(error => {
        console.error("Feil ved henting av tabell:", error);
      });
  };
  
  // ✅ Klikk på et kort åpner popup med detaljert info
  function visProfil(id) {
    fetch(`hent_privatkunde_med_id.php?id=${id}`)
      .then(response => response.json())
      .then(data => {
        console.log("Data mottatt:", data);
  
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
  
  // ✅ Lukk popup manuelt med X-knappen
  function lukkModal() {
    document.getElementById("profilModal").classList.add("hidden");
  }
  
  // ✅ Lukk popup med ESC
  window.addEventListener("keydown", e => {
    if (e.key === "Escape") lukkModal();
  });
  
  // ✅ Lukk popup hvis du klikker utenfor innholdet
  window.addEventListener("click", e => {
    const modal = document.getElementById("profilModal");
    if (e.target === modal) lukkModal();
  });