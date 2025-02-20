const yearSelect = document.getElementById('year-select');

    // Rango de años: puedes ajustar el año inicial y cuántos años a futuro incluir
const startYear = 2000; // Año inicial
const endYear = new Date().getFullYear(); // Año actual

// Generar las opciones
for (let year = startYear; year <= endYear; year++) {
    const option = document.createElement('option');
    option.value = year;
    option.textContent = year;
    yearSelect.appendChild(option);
}

// Establecer un valor predeterminado (opcional)
yearSelect.value = endYear;