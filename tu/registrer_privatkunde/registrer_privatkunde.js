document.addEventListener("DOMContentLoaded", () => {
  // Hent ID fra URL hvis den finnes (brukes ved redigering)
  const urlParams = new URLSearchParams(window.location.search);
  const id = urlParams.get("id");

  if (id) {
    // Endre formens action til oppdaterings-URL
    document.getElementById("bedriftForm").action = `../liste_privatkunde/oppdater_privatkunde.php?id=${id}`;

    // Hent eksisterende privatkunde-data fra server
    fetch(`../liste_privatkunde/hent_privatkunde_med_id.php?id=${id}`)
      .then(response => response.json())
      .then(data => {
        if (!data) return;

        // Sett verdier i inputfeltene
        document.querySelector("input[name='fornavn']").value = data.fornavn || "";
        document.querySelector("input[name='etternavn']").value = data.etternavn || "";
        document.querySelector("input[name='adresse1']").value = data.adresse1 || "";
        document.querySelector("input[name='adresse2']").value = data.adresse2 || "";
        document.querySelector("input[name='postnr']").value = data.postnr || "";
        document.querySelector("input[name='sted']").value = data.sted || "";
        document.querySelector("input[name='telefon']").value = data.telefon || "";
        document.querySelector("input[name='epost']").value = data.epost || "";
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
        console.error("Feil ved henting av privatkundedata:", err);
      });
  } else {
    // Hvis ingen ID i URL, bruk registreringsaction
    document.getElementById("bedriftForm").action = "registrer_privatkunde.php";
  }
});
