<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Registro – Café Forestal</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Lato:wght@300;400;700&display=swap" rel="stylesheet" />
  <script>
    tailwind.config = {
      theme: {
        extend: {
          fontFamily: {
            display: ['Playfair Display', 'serif'],
            body: ['Lato', 'sans-serif'],
          },
          colors: {
            cafe: {
              50:  '#fdf6e3',
              100: '#f9eed0',
              200: '#f0d9a0',
              300: '#d4a96a',
              400: '#c47a2e',
              500: '#8b4e1a',
              600: '#6b3510',
              700: '#4a2408',
              800: '#2c1605',
              900: '#1a0e05',
            }
          }
        }
      }
    }
  </script>
</head>

<body class="min-h-screen bg-cafe-900 flex items-center justify-center p-4 font-body">

  <!-- ══════════════════════════════════════════
       MODAL – Autorización de datos personales
  ══════════════════════════════════════════ -->
  <div id="modal-autorizacion" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-cafe-900/95">
    <div class="bg-cafe-50 rounded-2xl shadow-2xl border border-cafe-300/30 w-full max-w-lg overflow-hidden">

      <!-- Cabecera -->
      <div class="bg-gradient-to-br from-cafe-800 via-cafe-700 to-cafe-600 px-6 py-5 text-center">
        <div class="w-14 h-14 mx-auto mb-3 rounded-full bg-white/10 border border-white/20 flex items-center justify-center">
          <i class="fas fa-shield-halved text-cafe-200 text-2xl"></i>
        </div>
        <h2 class="font-display text-xl font-bold text-cafe-100">Autorización de Datos Personales</h2>
        <p class="text-cafe-200/70 text-xs mt-1 tracking-wide">Ley 1581 de 2012 – Habeas Data</p>
      </div>

      <!-- Cuerpo -->
      <div class="px-6 py-5">

        <!-- Texto de autorización con scroll -->
        <div class="bg-white border border-cafe-200 rounded-xl p-4 max-h-44 overflow-y-auto text-sm text-cafe-700 leading-relaxed">
          <p>
            Manifiesto de manera libre, expresa, informada y voluntaria que autorizo a la sociedad
            <strong class="text-cafe-800">GRUPO MANZANARES S.A.S.</strong> para recolectar, almacenar,
            usar, circular, suprimir y, en general, realizar el tratamiento de mis datos personales
            conforme a lo establecido en la <strong class="text-cafe-800">Ley 1581 de 2012</strong>
            y demás normas concordantes.
          </p>
          <p class="mt-3">
            La presente autorización se otorga para que la empresa utilice mis datos en el desarrollo
            de sus actividades comerciales, administrativas y legales, garantizando en todo momento
            la confidencialidad, seguridad y protección de la información.
          </p>
        </div>

        <!-- Checkbox -->
        <label class="flex items-start gap-3 mt-4 cursor-pointer group">
          <input type="checkbox" id="chk-acepto"
                 class="mt-0.5 w-4 h-4 accent-cafe-600 cursor-pointer flex-shrink-0" />
          <span class="text-xs text-cafe-700 leading-relaxed group-hover:text-cafe-800 transition">
            He leído y acepto la autorización para el tratamiento de mis datos personales.
          </span>
        </label>
        <p class="hidden text-xs text-red-600 font-semibold mt-1" id="err-acepto">
          <i class="fas fa-circle-exclamation mr-1"></i>Debes aceptar la autorización para continuar.
        </p>

        <!-- Botón aceptar -->
        <button type="button" id="btn-aceptar-modal"
                class="w-full mt-5 bg-gradient-to-r from-cafe-700 to-cafe-500 hover:from-cafe-800 hover:to-cafe-600 text-cafe-50 font-bold text-sm uppercase tracking-widest py-3 rounded-lg shadow-lg transition">
          <i class="fas fa-check mr-2"></i>Acepto y continuar
        </button>



      </div>
    </div>
  </div>
  <!-- /Modal -->


  <!-- ══════════════════════════════════════════
       FORMULARIO PRINCIPAL
  ══════════════════════════════════════════ -->
  <div class="w-full max-w-lg">

    <!-- Tarjeta -->
    <div class="bg-cafe-50 rounded-2xl shadow-2xl overflow-hidden border border-cafe-300/30">

      <!-- Encabezado -->
      <div class="bg-gradient-to-br from-cafe-800 via-cafe-700 to-cafe-600 px-8 pt-10 pb-8 text-center">

        <div class="w-24 h-24 mx-auto mb-5 rounded-full bg-white/10 border border-white/20 flex items-center justify-center overflow-hidden">
          <img src="img/logo.png"
               onerror="this.onerror=null;this.src='img/logo.jpg'"
               alt="Café Forestal"
               class="max-w-[72px] max-h-[72px] object-contain" />
        </div>

        <h1 class="font-display text-2xl font-bold text-cafe-100 leading-tight">
          Descubre el sabor<br/>de nuestro origen
        </h1>
        <p class="text-cafe-200/80 text-sm font-light mt-2 tracking-wide">
          Regístrate y recibe una taza de café de cortesía
        </p>

        <div class="flex items-center justify-center gap-3 mt-5">
          <div class="h-px w-10 bg-cafe-400/60"></div>
          <i class="fas fa-mug-hot text-cafe-400 text-sm"></i>
          <div class="h-px w-10 bg-cafe-400/60"></div>
        </div>

      </div>
      <!-- /Encabezado -->

      <!-- Cuerpo -->
      <div class="px-8 py-8">

        <!-- FORMULARIO -->
        <div id="registro-form">

          <!-- Nombre -->
          <div class="mb-4">
            <label for="nombre" class="block text-xs font-bold text-cafe-700 uppercase tracking-widest mb-1">
              Nombre completo <span class="text-red-500">*</span>
            </label>
            <input type="text" id="nombre" name="nombre"
                   placeholder="Tu nombre y apellido"
                   autocomplete="name"
                   class="w-full px-3 py-2 border border-cafe-300 rounded-lg text-sm text-cafe-800 bg-white placeholder-cafe-300 focus:outline-none focus:ring-2 focus:ring-cafe-500 focus:border-cafe-500 transition" />
            <p class="hidden text-xs text-red-600 font-semibold mt-1" id="err-nombre"></p>
          </div>

          <!-- Correo -->
          <div class="mb-4">
            <label for="correo" class="block text-xs font-bold text-cafe-700 uppercase tracking-widest mb-1">
              Correo electrónico <span class="text-red-500">*</span>
            </label>
            <input type="email" id="correo" name="correo"
                   placeholder="correo@ejemplo.com"
                   autocomplete="email"
                   class="w-full px-3 py-2 border border-cafe-300 rounded-lg text-sm text-cafe-800 bg-white placeholder-cafe-300 focus:outline-none focus:ring-2 focus:ring-cafe-500 focus:border-cafe-500 transition" />
            <p class="hidden text-xs text-red-600 font-semibold mt-1" id="err-correo"></p>
          </div>

          <!-- Celular -->
          <div class="mb-4">
            <label for="celular" class="block text-xs font-bold text-cafe-700 uppercase tracking-widest mb-1">
              Celular <span class="text-red-500">*</span>
            </label>
            <input type="tel" id="celular" name="celular"
                   placeholder="3XX XXX XXXX"
                   autocomplete="tel"
                   maxlength="10"
                   class="w-full px-3 py-2 border border-cafe-300 rounded-lg text-sm text-cafe-800 bg-white placeholder-cafe-300 focus:outline-none focus:ring-2 focus:ring-cafe-500 focus:border-cafe-500 transition" />
            <p class="hidden text-xs text-red-600 font-semibold mt-1" id="err-celular"></p>
          </div>

          <!-- Ciudad / Barrio -->
          <div class="grid grid-cols-2 gap-4 mb-6">
            <div>
              <label for="ciudad" class="block text-xs font-bold text-cafe-700 uppercase tracking-widest mb-1">
                Ciudad <span class="text-red-500">*</span>
              </label>
              <input type="text" id="ciudad" name="ciudad"
                     placeholder="Tu ciudad"
                     class="w-full px-3 py-2 border border-cafe-300 rounded-lg text-sm text-cafe-800 bg-white placeholder-cafe-300 focus:outline-none focus:ring-2 focus:ring-cafe-500 focus:border-cafe-500 transition" />
              <p class="hidden text-xs text-red-600 font-semibold mt-1" id="err-ciudad"></p>
            </div>
            <div>
              <label for="barrio" class="block text-xs font-bold text-cafe-700 uppercase tracking-widest mb-1">
                Barrio <span class="text-red-500">*</span>
              </label>
              <input type="text" id="barrio" name="barrio"
                     placeholder="Tu barrio"
                     class="w-full px-3 py-2 border border-cafe-300 rounded-lg text-sm text-cafe-800 bg-white placeholder-cafe-300 focus:outline-none focus:ring-2 focus:ring-cafe-500 focus:border-cafe-500 transition" />
              <p class="hidden text-xs text-red-600 font-semibold mt-1" id="err-barrio"></p>
            </div>
          </div>

          <!-- Botón registrar -->
          <button type="button" id="btn-registrar"
                  class="w-full bg-gradient-to-r from-cafe-700 to-cafe-500 hover:from-cafe-800 hover:to-cafe-600 text-cafe-50 font-bold text-sm uppercase tracking-widest py-3 rounded-lg shadow-lg transition disabled:opacity-50 disabled:cursor-not-allowed">
            <span id="btn-text"><i class="fas fa-leaf mr-2"></i>Registrarme</span>
            <span id="btn-loader" class="hidden"><i class="fas fa-spinner fa-spin mr-2"></i>Procesando…</span>
          </button>

        </div>
        <!-- /FORMULARIO -->

        <!-- Pantalla de éxito -->
        <div id="success-screen" class="hidden text-center py-4">

          <div class="w-20 h-20 mx-auto mb-5 rounded-full bg-gradient-to-br from-cafe-700 to-cafe-500 flex items-center justify-center shadow-lg">
            <i class="fas fa-mug-hot text-cafe-100 text-3xl"></i>
          </div>

          <h2 class="font-display text-2xl font-bold text-cafe-800 mb-1">¡Registro exitoso!</h2>
          <p class="text-sm text-cafe-600 mb-4">Tu código de cortesía es:</p>

          <div id="codigo-generado"
               class="inline-block px-8 py-3 mx-auto bg-cafe-800 text-cafe-100 font-mono text-xl font-bold tracking-[0.25em] rounded-lg border-2 border-cafe-400 shadow-lg select-all">
          </div>

          <p class="text-xs text-cafe-600/70 mt-4 leading-relaxed">
            Presenta este código y disfruta tu café de cortesía.<br />
            O toma una captura y redímelo en cualquiera de nuestras tiendas.<br />

            Código válido del 22 de marzo al 30 de abril de 2026  
          </p>

          <button type="button" onclick="location.reload()"
                  class="mt-6 px-6 py-2 border border-cafe-600 text-cafe-700 text-sm font-bold uppercase tracking-widest rounded-lg hover:bg-cafe-700 hover:text-cafe-50 transition">
            Nuevo registro
          </button>

        </div>
        <!-- /success-screen -->

      </div>
      <!-- /Cuerpo -->

    </div>
    <!-- /Tarjeta -->

    <p class="text-center text-xs text-cafe-400/40 mt-5">© Café Forestal 2026</p>

  </div>

  <script src="js/validation.js"></script>
  <script src="js/form.js"></script>
  <script src="js/modal.js"></script>
</body>
</html>