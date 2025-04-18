// Grunnbane for alle interne videresendinger
const BASE_PATH = "/test12/tu/";

// Funksjon for å videresende til en spesifikk underside basert på base-stien
function redirectToPage(pageUrl) {
  window.location.href = BASE_PATH + pageUrl;
}
