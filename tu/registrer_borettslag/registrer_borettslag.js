document.addEventListener("DOMContentLoaded", () => {
  // Hent ID fra URL hvis den finnes (brukes for redigering)
  const urlParams = new URLSearchParams(window.location.search);
  const id = urlParams.get("id");

  if (id) {
    // Endre form action til å sende til oppdateringsfil
    document.getElementById("bedriftForm").action = `../liste_borettslag/oppdater_borettslag.php?id=${id}`;

    // Hent eksisterende data for borettslaget med gitt ID
    fetch(`../liste_borettslag/hent_borettslag_med_id.php?id=${id}`)
      .then(response => response.json())
      .then(data => {
        if (!data) return;

        // Fyll inn verdier i inputfeltene
        document.querySelector("input[name='orgnr']").value = data.orgnr || "";
        document.querySelector("input[name='navn']").value = data.navn || "";
        document.querySelector("input[name='styreleder']").value = data.styreleder || "";
        document.querySelector("input[name='adresse1']").value = data.adresse1 || "";
        document.querySelector("input[name='adresse2']").value = data.adresse2 || "";
        document.querySelector("input[name='postnr']").value = data.postnr || "";
        document.querySelector("input[name='sted']").value = data.sted || "";
        document.querySelector("input[name='epost']").value = data.epost || "";
        document.querySelector("input[name='telefon']").value = data.telefon || "";
        document.querySelector("input[name='kontaktperson']").value = data.kontaktperson || "";
        document.querySelector("input[name='kontaktpersonTlf']").value = data.kontaktpersonTlf || "";
        document.querySelector("textarea[name='kommentar']").value = data.kommentar || "";

        // Forhåndsvis bilde hvis det finnes
        if (data.bilde) {
          const img = document.createElement("img");
          img.src = "../" + data.bilde;
          img.alt = "Eksisterende bilde";
          img.style.maxWidth = "100px";
          document.getElementById("bildePreview").appendChild(img);
        }

        // Forhåndsvis PDF hvis det finnes
        if (data.pdf) {
          const link = document.createElement("a");
          link.href = "../" + data.pdf;
          link.target = "_blank";
          link.textContent = "Se tidligere opplastet PDF";
          document.getElementById("pdfPreview").appendChild(link);
        }
      })
      .catch(err => {
        console.error("Feil ved henting av borettslagsdata:", err);
      });
  } else {
    // Hvis ingen ID er angitt, bruk registreringsmodus
    document.getElementById("bedriftForm").action = "registrer_borettslag.php";
  }
});
