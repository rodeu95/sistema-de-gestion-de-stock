document.addEventListener('DOMContentLoaded', function () {
    const requiredElements = document.querySelectorAll('input[required], select[required], textarea[required]');
  
    requiredElements.forEach(element => {
      let label;
  
      // Buscar el label asociado al input/select/textarea
      if (element.type === 'radio') {
        // Para radios, buscamos el label "general" dentro del mismo bloque
        label = element.closest('.mb-3')?.querySelector('label');
      } else {
        // Para inputs normales, selects y textarea
        label = document.querySelector(`label[for="${element.id}"]`);
      }
  
      if (!label) return;
  
      const asterisco = label.querySelector('.asterisk');
      if (!asterisco) return;
  
      // Función para verificar si el campo tiene valor
      const checkValue = () => {
        let hasValue = false;
  
        if (element.type === 'radio') {
          const radios = document.querySelectorAll(`input[name="${element.name}"]`);
          hasValue = Array.from(radios).some(r => r.checked);
        } else {
          hasValue = element.value && element.value.trim() !== '';
        }
  
        asterisco.style.display = hasValue ? 'none' : 'inline';
      };
  
      // Evento change para todos
      element.addEventListener('change', checkValue);
  
      // Comprobación inicial al cargar la página
      checkValue();
    });
  });
  