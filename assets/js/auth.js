async function login(event) {
  event.preventDefault();

  const nombreUsuario = document.getElementById("username").value;
  const claveUsuario = document.getElementById("password").value;

  try {
    const respuesta = await fetch("auth/login", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({
        nombreUsuario,
        claveUsuario,
      }),
    });

    const respuestaJson = await respuesta.json();

    if (respuestaJson.status === "error") {
      showAlertAuth("loginAlert", "error", respuestaJson.message);
      return false;
    }

    // Mostrar mensaje de éxito con SweetAlert
    //Swal.fire({
      //title: "Inicio de sesión exitoso!",
      //text: "Has iniciado sesión correctamente.",
      //icon: "success",
    //}).then(() => {
      //Redireccionar a la pagina web
      window.location.href = "web";
    //});
  } catch (error) {
    showAlertAuth("loginAlert", "error", "Error al iniciar sesion".error);
    return false;
  }
}

async function register(e) {
  e.preventDefault();
  const nombreCompleto = document.getElementById("full_name").value;
  const usuario = document.getElementById("username").value;
  const email= document.getElementById("email").value;
  const clave = document.getElementById("password").value;
  const confirmarClave = document.getElementById("confirm_password").value;
  const rol = document.getElementById("rol").value;

  try {
    const respuesta = await fetch("auth/register", {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify({
      nombreCompleto,
      usuario,
      email,
      clave,
      confirmarClave,
      rol
    }),
  });

  const respuestaJson = await respuesta.json();

  if (respuestaJson.status === "error"){
    showAlertAuth("registerAlert", "error", respuestaJson.message);
    return false;
  }

  showAlertAuth("registerAlert", "success", respuestaJson.message);

  setTimeout(() => {
    window.location.href = "login";
  }, 200); 
  } catch (error) {
    showAlertAuth("registerAlert", "error", "Error al Registrar: ".error);
    return false;
    
  }


  
}

function showAlertAuth(containerId, type, message) {
  const container = document.getElementById(containerId);
  container.innerHTML = `
        <div class="alert alert-${
          type === "error" ? "danger" : "success"
        } alert-dismissible fade show">
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `;

  // Auto-cerrar después de 5 segundos
  setTimeout(() => {
    const alert = container.querySelector(".alert");
    if (alert) {
      alert.remove();
    }
  }, 5000);
}
