/* js/modal.js – Lógica del modal de autorización de datos personales */

(function () {

  const modal    = document.getElementById('modal-autorizacion');
  const btnOk    = document.getElementById('btn-aceptar-modal');
  const chk      = document.getElementById('chk-acepto');
  const errAcepto = document.getElementById('err-acepto');

  function cerrarModal() {
    modal.classList.add('opacity-0', 'pointer-events-none');
    setTimeout(() => modal.classList.add('hidden'), 300);
  }

  btnOk.addEventListener('click', () => {
    if (!chk.checked) {
      errAcepto.classList.remove('hidden');
      chk.focus();
      return;
    }
    errAcepto.classList.add('hidden');
    cerrarModal();
  });

  chk.addEventListener('change', () => {
    if (chk.checked) errAcepto.classList.add('hidden');
  });

  // Transición suave de entrada
  modal.classList.add('transition-opacity', 'duration-300');

})();