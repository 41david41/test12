// ===============================
// Script som håndterer redigering eller ny registrering av bedriftkunde
// ===============================

document.addEventListener("DOMContentLoaded", () => {
  const urlParams = new URLSearchParams(window.location.search);
  const id = urlParams.get("id");

  // Hvis vi har en ID i URL-en, last eksisterende data og sett action til oppdatering
  if (id) {
    document.getElementById("bedriftForm").action = `../liste_bedriftkunde/oppdater_bedriftkunde.php?id=${id}`;

    fetch(`../liste_bedriftkunde/hent_bedriftkunde_med_id.php?id=${id}`)
      .then(response => response.json())
      .then(data => {
        if (!data) return;

        // Fyll inn skjema med eksisterende verdier
        document.querySelector("input[name='orgnr']").value = data.orgnr || "";
        document.querySelector("input[name='bedriftsnavn']").value = data.bedriftsnavn || "";
        document.querySelector("input[name='adresse1']").value = data.adresse1 || "";
        document.querySelector("input[name='adresse2']").value = data.adresse2 || "";
        document.querySelector("input[name='postnr']").value = data.postnr || "";
        document.querySelector("input[name='sted']").value = data.sted || "";
        document.querySelector("input[name='epost']").value = data.epost || "";
        document.querySelector("input[name='kontaktperson']").value = data.kontaktperson || "";
        document.querySelector("input[name='kontaktpersonTlf']").value = data.kontaktpersonTlf || "";
        document.querySelector("textarea[name='kommentar']").value = data.kommentar || "";

        // Viser eksisterende bilde dersom det finnes
        if (data.bilde) {
          const img = document.createElement("img");
          img.src = "../" + data.bilde;
          img.alt = "Eksisterende bilde";
          img.style.maxWidth = "100px";
          document.getElementById("bildePreview").appendChild(img);
        }

        // Viser lenke til eksisterende PDF dersom det finnes
        if (data.pdf) {
          const link = document.createElement("a");
          link.href = "../" + data.pdf;
          link.target = "_blank";
          link.textContent = "Se tidligere opplastet PDF";
          document.getElementById("pdfPreview").appendChild(link);
        }
      })
      .catch(err => {
        console.error("Feil ved henting av bedriftskundedata:", err);
      });
  } else {
    // Hvis ingen ID er angitt, bruk registreringsaction
    document.getElementById("bedriftForm").action = "registrer_bedriftkunde.php";
  }
});
