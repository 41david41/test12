<!DOCTYPE html>
<html lang="no">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>privatkunde_liste</title>
    <link rel="stylesheet" href="../css/liste.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600&display=swap">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&icon_names=arrow_drop_down" />
    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
    <script src="../redirectToPage.js"></script>
    <style>
        #header {
            width: 100%;
            margin: 0;
            padding: 0;
        }
    
        #header header {
            width: 100%;
        }
    </style>   
</head>

<body>
<div id="header">
  <?php include("../header/header.php"); ?>
</div>

<div class="headline-container">
    <h1 class="text-3xl font-light headline-left">PRIVATKUNDER</h1>
    
    <div class="button-container">
         <!-- Export to CSV button -->
        <div class="dropdown">
            <button class="dropdown-btn" id="kundegruppeBtn"><span class="material-symbols-outlined pil">arrow_drop_down</span></button>
            <div class="dropdown-content">
                <a href="#" onclick="redirectToPage('liste_borettslag/borettslag_liste.php')">Borettslag</a>
                <a href="#" onclick="redirectToPage('liste_bedriftkunde/bedriftkunde_liste.php')">Bedriftskunder</a>
            </div>
        </div>
        <a href="#" onclick="redirectToPage('registrer_privatkunde/registrer_privatkundehtml.php')">
        <button class="secondaryBTN" id="nyKundeBtn">
                <span class="material-icons pil">add</span>
            </button>
        </a>
        <button id="exportBtn" onclick="exportToCSV()" class="secondaryBTN">CSV</button>

    </div>
</div>

<div class="container">
    <div class="visning-sok-wrapper">
        <div class="left-spacer"></div> <!-- tom plass på venstre side -->
        
        <div class="view-options">
          <button onclick="settVisning('grid')">🔲</button>
          <button onclick="settVisning('liste')">☰</button>
        </div>
      
        <div class="search-container">
          <input type="text" id="sokefelt" placeholder="Søk..." oninput="filtrerKunder()">
        </div>
      </div>        

<!-- Tabellvisning -->
<table class="kunde-tabell" id="privatkunde-tabell" style="display: none;">
  <thead>
    <tr>
      <th>Fornavn</th>
      <th>Etternavn</th>
      <th>Adresse</th>
      <th>Postnummer</th>
      <th>Sted</th>
    </tr>
  </thead>
  <tbody id="privatkunde-tbody">
    <!-- JS fyller inn <tr> her -->
  </tbody>
</table>

<!-- Grid-visning -->
<div id="privatkunde-grid" class="kundeprofil-grid"></div>

</div>

<div id="profilModal" class="modal hidden">
    <div class="modal-content">
      <span class="close" onclick="lukkModal()">&times;</span>
      <div id="modalInnhold"></div>
    </div>
</div>

<script>
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
</script>

<script src="privatkunde_liste.js"></script>
</body>
</html>
