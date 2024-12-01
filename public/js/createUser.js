const seleccion = document.getElementById('tipoUsuario');
if (seleccion) {
    seleccion.addEventListener('change', function () {
        const atributoContainer = document.getElementById('atributos-container');
        const atributoLabel = document.getElementById('atributos');

        atributoContainer.innerHTML = '';

        // Oculta el contenedor de atributos inicialmente
        atributoContainer.style.display = 'none';
        atributoLabel.style.display = 'none';

        if (this.value) {
            atributoContainer.style.display = 'block';
            atributoLabel.style.display = 'block';

            const rolNombre = this.options[this.selectedIndex].text; // Obtener el nombre del rol

            if (rolNombre === 'Administrador') {
                atributoContainer.innerHTML = '<p>El Administrador tiene todos los permisos automáticamente.</p>';
            } else {
                cargarPermisos(this.value); // Carga permisos dinámicamente
            }
        }
    });
}

function cargarPermisos(rolId) {
    const atributosContainer = document.getElementById('atributos-container');

    fetch('http://localhost/sistema/public/roles/' + rolId + '/permissions')
        .then(response => {
            if (!response.ok) {
                throw new Error('Error al cargar permisos');
            }
            return response.json();
        })
        .then(data => {
            atributosContainer.innerHTML = ''; // Limpia el contenedor
            // Verifica si hay permisos
            if (data.length > 0) {
                data.forEach(permission => {
                    const checkbox = document.createElement('div');
                    checkbox.className = 'permission-checkbox';
                    checkbox.innerHTML = `
                    <input type="checkbox" name="permissions[]" class="form-check-input" value="${permission.id}">
                    <label class="form-check-label">${permission.description}</label><br>
                    `;
                    atributosContainer.appendChild(checkbox);
                });
                atributosContainer.style.display = 'block'; // Muestra el contenedor de atributos
            } else {
                atributosContainer.innerHTML = '<p>No hay permisos disponibles para este rol.</p>';
            }
        })
        .catch(error => console.error('Error al cargar permisos:', error));
}