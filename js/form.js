/* js/form.js – Lógica de envío del formulario Café Forestal */

(function () {

  const API_URL = 'api/registro.php';

  function getFormData() {
    return {
      nombre:  document.getElementById('nombre').value.trim(),
      correo:  document.getElementById('correo').value.trim(),
      celular: document.getElementById('celular').value.trim(),
      ciudad:  document.getElementById('ciudad').value.trim(),
      barrio:  document.getElementById('barrio').value.trim()
    };
  }

  function setLoading(loading) {
    const btn    = document.getElementById('btn-registrar');
    const text   = document.getElementById('btn-text');
    const loader = document.getElementById('btn-loader');
    btn.disabled = loading;
    text.classList.toggle('hidden', loading);
    loader.classList.toggle('hidden', !loading);
  }

  function showSuccess(codigo) {
    document.getElementById('registro-form').classList.add('hidden');
    document.getElementById('codigo-generado').textContent = codigo;
    const screen = document.getElementById('success-screen');
    screen.classList.remove('hidden');
    screen.scrollIntoView({ behavior: 'smooth', block: 'center' });
  }

  async function handleSubmit() {
    if (!Validation.validateAll()) return;

    setLoading(true);

    try {
      const res  = await fetch(API_URL, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(getFormData())
      });

      const json = await res.json();

      if (json.success) {
        showSuccess(json.codigo);
      } else {
        if (json.error === 'celular_duplicado') {
          Validation.showError('celular', 'Este número de celular ya fue registrado.');
        } else {
          alert(json.message || 'Error al registrar. Inténtalo de nuevo.');
        }
      }
    } catch (e) {
      alert('No se pudo conectar con el servidor. Verifica tu conexión e inténtalo de nuevo.');
    } finally {
      setLoading(false);
    }
  }

  document.addEventListener('DOMContentLoaded', () => {
    Validation.attachLiveValidation();
    document.getElementById('btn-registrar').addEventListener('click', handleSubmit);
  });

})();