document.addEventListener("DOMContentLoaded", function () {
    // Hent header-containeren
    const headerContainer = document.getElementById("header");

    if (!headerContainer) {
        console.error("⚠️ #header finnes ikke i DOM-en!");
        return;
    }

    // Hent initialer fra serveren (fra PHP-session)
    fetch("../header/get_initialer.php")
        .then(response => {
            if (!response.ok) throw new Error("❌ Feil ved henting av initialer");
            return response.text();
        })
        .then(initialer => {
            console.log("Initialer hentet:", initialer);  // Feilsøking: Sjekk hva vi får tilbake
            const profileCircle = document.querySelector(".profile-circle");  // Riktig selektor
            if (profileCircle) {
                // Sett initialene i profile-circle
                profileCircle.textContent = initialer;
            }
        })
        .catch(error => console.warn(error.message));

    // Håndter dropdown-menyen
    const userMenuButton = document.getElementById("userMenuButton");
    const dropdownMenu = document.getElementById("dropdownMenu");
    const dropdownArrow = document.getElementById("dropdownArrow");

    if (userMenuButton && dropdownMenu && dropdownArrow) {
        // Åpne/lukk dropdown-menyen når knapp blir trykket
        userMenuButton.addEventListener("click", function (event) {
            event.stopPropagation(); // Forhindrer at klikk utenfor menyen lukker den
            dropdownMenu.classList.toggle("hidden");  // Fjern punktum her, da klassen er "hidden"

            // Endre pilen når menyen åpnes/lukkes
            if (dropdownMenu.classList.contains("hidden")) {
                dropdownArrow.textContent = "arrow_drop_down";  // Pilen peker ned
            } else {
                dropdownArrow.textContent = "arrow_drop_up";    // Pilen peker opp
            }
        });

        // Lukk dropdown-menyen når det klikkes utenfor
        document.addEventListener("click", function (event) {
            if (!userMenuButton.contains(event.target) && !dropdownMenu.contains(event.target)) {
                dropdownMenu.classList.add("hidden");
                dropdownArrow.textContent = "arrow_drop_down"; // Sett pilen tilbake til nedover
            }
        });
    } else {
        console.warn("⚠️ Dropdown-elementer ikke funnet i header.html");
    }

    // Legg til event listener for å håndtere klikk på "Profil"-lenken i dropdown-menyen
    const profileLink = document.getElementById("profileLink");
    if (profileLink) {
        profileLink.addEventListener("click", function (event) {
            event.preventDefault(); // Hindrer standard lenke-adferd
            window.location.href = "../profil/profil.php";  // Omdirigerer til profilsiden
        });
    }
});
