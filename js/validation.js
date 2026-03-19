/* js/validation.js – Reglas de validación del formulario Café Forestal */

const Validation = (() => {

  const rules = {
    nombre:  { validate: v => v.trim().length >= 3,                           msg: 'Ingresa tu nombre completo (mín. 3 caracteres).' },
    correo:  { validate: v => /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v.trim()),  msg: 'Ingresa un correo electrónico válido.' },
    celular: { validate: v => /^3\d{9}$/.test(v.trim()),                     msg: 'Ingresa un celular válido (10 dígitos, inicia en 3).' },
    ciudad:  { validate: v => v.trim().length >= 2,                           msg: 'Ingresa tu ciudad.' },
    barrio:  { validate: v => v.trim().length >= 2,                           msg: 'Ingresa tu barrio.' }
  };

  function showError(fieldId, msg) {
    const input = document.getElementById(fieldId);
    const err   = document.getElementById('err-' + fieldId);
    if (!input || !err) return;
    input.classList.add('border-red-400', 'ring-2', 'ring-red-300');
    input.classList.remove('border-cafe-300');
    err.textContent = msg;
    err.classList.remove('hidden');
  }

  function clearError(fieldId) {
    const input = document.getElementById(fieldId);
    const err   = document.getElementById('err-' + fieldId);
    if (!input || !err) return;
    input.classList.remove('border-red-400', 'ring-2', 'ring-red-300');
    input.classList.add('border-cafe-300');
    err.textContent = '';
    err.classList.add('hidden');
  }

  function validateAll() {
    let valid = true;
    for (const [field, rule] of Object.entries(rules)) {
      const el = document.getElementById(field);
      if (!el) continue;
      if (rule.validate(el.value)) clearError(field);
      else { showError(field, rule.msg); valid = false; }
    }
    return valid;
  }

  function attachLiveValidation() {
    for (const field of Object.keys(rules)) {
      const el = document.getElementById(field);
      if (!el) continue;
      el.addEventListener('blur', () => {
        rules[field].validate(el.value) ? clearError(field) : showError(field, rules[field].msg);
      });
      if (field === 'celular') {
        el.addEventListener('input', () => { el.value = el.value.replace(/\D/g, '').slice(0, 10); });
      }
    }
  }

  return { validateAll, attachLiveValidation, clearError, showError };

})();