document.addEventListener("DOMContentLoaded", function () {
    // 🔹 Hent header-containeren for å sette inn dynamisk innhold
    const headerContainer = document.getElementById("header");

    if (!headerContainer) {
        console.error("⚠️ #header finnes ikke i DOM-en!");
        return;
    }

    // 🔹 Hent initialer fra PHP-session via API-endepunkt
    fetch("../header/get_initialer.php")
        .then(response => {
            if (!response.ok) throw new Error("❌ Feil ved henting av initialer");
            return response.text();
        })
        .then(initialer => {
            console.log("Initialer hentet:", initialer);
            const profileCircle = document.querySelector(".profile-circle");
            if (profileCircle) {
                profileCircle.textContent = initialer;  // Sett initialene i sirkelen
            }
        })
        .catch(error => console.warn(error.message));

    // 🔹 Dropdown-elementer
    const userMenuButton = document.getElementById("userMenuButton");
    const dropdownMenu = document.getElementById("dropdownMenu");
    const dropdownArrow = document.getElementById("dropdownArrow");

    if (userMenuButton && dropdownMenu && dropdownArrow) {
        // Åpne/lukk dropdown-meny ved klikk
        userMenuButton.addEventListener("click", function (event) {
            event.stopPropagation(); // Forhindrer at menyen lukkes umiddelbart
            dropdownMenu.classList.toggle("hidden");

            // Endre pilikon basert på synlighet
            dropdownArrow.textContent = dropdownMenu.classList.contains("hidden")
                ? "arrow_drop_down"
                : "arrow_drop_up";
        });

        // Lukk menyen hvis man klikker utenfor
        document.addEventListener("click", function (event) {
            if (!userMenuButton.contains(event.target) && !dropdownMenu.contains(event.target)) {
                dropdownMenu.classList.add("hidden");
                dropdownArrow.textContent = "arrow_drop_down";
            }
        });
    } else {
        console.warn("⚠️ Dropdown-elementer ikke funnet i header.html");
    }

    // 🔹 Håndter klikk på 'Profil'-lenken i menyen
    const profileLink = document.getElementById("profileLink");
    if (profileLink) {
        profileLink.addEventListener("click", function (event) {
            event.preventDefault();  // Hindrer standard navigasjon
            window.location.href = "../profil/profile.php";  // Naviger til profilsiden
        });
    }
});
