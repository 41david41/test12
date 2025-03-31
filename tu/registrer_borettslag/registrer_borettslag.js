document.addEventListener("DOMContentLoaded", () => {
    const urlParams = new URLSearchParams(window.location.search);
    const id = urlParams.get("id");
  
    if (id) {
      // Bytt overskrift til "Rediger profil"
      document.querySelector(".headline-left").textContent = "REDIGER PROFIL";
  
      // Endre form action til oppdaterings-URL
      document.getElementById("bedriftForm").action = `../liste_borettslag/oppdater_borettslag.php?id=${id}`;
  
      // Hent data for gitt ID
      fetch(`../liste_borettslag/hent_borettslag_med_id.php?id=${id}`)
        .then(response => response.json())
        .then(data => {
          if (!data) return;
  
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
  
          // Forhåndsvis bilde og pdf hvis tilgjengelig
          if (data.bilde) {
            const img = document.createElement("img");
            img.src = "../" + data.bilde;
            img.alt = "Eksisterende bilde";
            img.style.maxWidth = "100px";
            document.getElementById("bildePreview").appendChild(img);
          }
  
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
      // Hvis vi ikke redigerer, bruk vanlig registrering
      document.getElementById("bedriftForm").action = "registrer_borettslag.php";
    }
  });