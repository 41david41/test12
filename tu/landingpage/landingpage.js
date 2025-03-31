document.addEventListener("DOMContentLoaded", function () {
    console.log("✅ JavaScript Loaded!");

    function safeAddListener(id, url) {
        const element = document.getElementById(id);
        if (element) {
            element.style.cursor = "pointer"; 
            element.addEventListener("click", function () {
                console.log(`🔗 Skal redirecte til: ${url}`); // Logg URL
                window.location.href = url;
            });
            console.log(`🎯 Event listener lagt til for: ${id}`);
        } else {
            console.warn(`⚠️ Element med ID '${id}' ikke funnet.`);
        }
    }

    safeAddListener("privatkunde", "../liste_privatkunde/privatkunde_liste.php");
    safeAddListener("bedriftkunde", "../liste_bedriftkunde/bedriftkunde_liste.php");
    safeAddListener("borettslag", "../liste_borettslag/borettslag_liste.php");
    safeAddListener("nyKundeBtn", "../registrer_borettslag/registrer_borettslag.php");
});
