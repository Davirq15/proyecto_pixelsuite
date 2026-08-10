const lista = document.getElementById("lista");
const slot1 = document.getElementById("slot1");
const slot2 = document.getElementById("slot2");
const btnComparar = document.getElementById("btnComparar");
const btnLimpiar = document.getElementById("btnLimpiar");
const resultado = document.getElementById("resultado");
const buscador = document.getElementById("buscador");
const botonesCategoria = document.querySelectorAll(".categorias button");
const tituloLista = document.getElementById("tituloLista");
const listaInfo = document.getElementById("listaInfo");
const statTotal = document.getElementById("statTotal");
const btnMostrarFormulario = document.getElementById("btnMostrarFormulario");
const formAgregar = document.getElementById("formAgregar");
const mensajeFormulario = document.getElementById("mensajeFormulario");
const categoriaNueva = document.getElementById("categoriaNueva");

let seleccionados = [];
let categoriaActual = "CPU";
let datos = { CPU: [], GPU: [], RAM: [] };

document.getElementById("btnCPU").onclick = () => cambiarCategoria("CPU");
document.getElementById("btnGPU").onclick = () => cambiarCategoria("GPU");
document.getElementById("btnRAM").onclick = () => cambiarCategoria("RAM");

buscador.addEventListener("input", () => renderLista()); //Buscar componentes mediante vaya escribiendo
btnComparar.addEventListener("click", comparar);
btnLimpiar.addEventListener("click", limpiarSeleccion);
btnMostrarFormulario.addEventListener("click", alternarFormulario);
formAgregar.addEventListener("submit", guardarComponente);

function cambiarCategoria(tipo){
    categoriaActual = tipo;
    buscador.value = "";
    categoriaNueva.value = tipo;
    limpiarSeleccion(false);
    renderLista();
}

function limpiarSeleccion(resetResultado = true){
    seleccionados = [];
    slot1.textContent = "Componente 1";
    slot2.textContent = "Componente 2";
    btnComparar.disabled = true;

    document.querySelectorAll(".card").forEach(card => {
        card.classList.remove("selected");
    });

    if(resetResultado){
        resultado.innerHTML = `
            <h2>Resultado del duelo</h2>
            <p class="resultado-texto">Todavía no hay comparación. Selecciona dos componentes para empezar.</p>
        `;
    }
}

function renderLista(){
    const filtro = buscador.value.trim().toLowerCase();
    const catalogo = Array.isArray(datos[categoriaActual]) ? datos[categoriaActual] : [];
    const componentes = catalogo.filter(comp =>
        comp.nombre.toLowerCase().includes(filtro) //SE realiazan estos
    );

    botonesCategoria.forEach(boton => {
        boton.classList.toggle("active", boton.id === `btn${categoriaActual}`);
    });

    tituloLista.textContent = `${categoriaActual} disponibles`;
    listaInfo.textContent = `${componentes.length} componente(s) encontrados en ${categoriaActual}.`;
    statTotal.textContent = catalogo.length;

    lista.innerHTML = "";

    if(componentes.length === 0){
        lista.innerHTML = `
            <div class="empty-state">
                <h3>Sin resultados</h3>
                <p>No encontramos componentes con ese nombre. Prueba otra búsqueda.</p>
            </div>
        `;
        return;
    }

    componentes.forEach(comp => {
        const yaSeleccionado = seleccionados.some(item => item.nombre === comp.nombre);

        const card = document.createElement("div");
        card.className = `card${yaSeleccionado ? " selected" : ""}`;
        card.innerHTML = `
            <div class="card-top">
                <h3>${comp.nombre}</h3>
                <span class="badge">${comp.gama}</span>
            </div>
            <p class="specs">${comp.specs}</p>
            <div class="card-bottom">
                <span>Rendimiento</span>
                <strong>${comp.rendimiento}/10</strong>
            </div>
        `;

        card.onclick = () => seleccionar(comp, card);
        lista.appendChild(card);
    });
}

async function cargarComponentes(){
    lista.innerHTML = `
        <div class="empty-state">
            <h3>Cargando...</h3>
            <p>Estamos leyendo el catálogo de componentes.</p>
        </div>
    `;

    try{
        const respuesta = await fetch("componentes.json");

        if(!respuesta.ok){
            throw new Error("No se pudo cargar el catálogo local.");
        }

        const contenido = await respuesta.json();
        datos = contenido;
        renderLista();
    }catch(error){
        lista.innerHTML = `
            <div class="empty-state">
                <h3>Error</h3>
                <p>${error.message}</p>
            </div>
        `;
        listaInfo.textContent = "No se pudo cargar el catálogo.";
    }
}

function alternarFormulario(){
    formAgregar.classList.toggle("oculto");
    mensajeFormulario.classList.remove("error", "success");

    if(formAgregar.classList.contains("oculto")){
        mensajeFormulario.textContent = "Aquí podrás crear nuevos componentes y guardarlos en el JSON.";
        return;
    }

    categoriaNueva.value = categoriaActual;
    mensajeFormulario.textContent = "Llena el formulario y guarda el nuevo componente.";
}

function guardarComponente(event){
    event.preventDefault();

    const formData = new FormData(formAgregar);
    const nuevo = {
        categoria: formData.get("categoria"),
        nombre: formData.get("nombre").trim(),
        gama: formData.get("gama").trim(),
        specs: formData.get("specs").trim(),
        rendimiento: Number(formData.get("rendimiento"))
    };

    if(!nuevo.nombre || !nuevo.gama || !nuevo.specs || Number.isNaN(nuevo.rendimiento)){
        mensajeFormulario.classList.add("error");
        mensajeFormulario.textContent = "Completa todos los campos correctamente.";
        return;
    }

    if(!datos[nuevo.categoria]){
        datos[nuevo.categoria] = [];
    }

    datos[nuevo.categoria].push(nuevo);
    formAgregar.reset();
    formAgregar.classList.add("oculto");
    mensajeFormulario.classList.remove("error");
    mensajeFormulario.classList.add("success");
    mensajeFormulario.textContent = "Componente agregado a la vista local.";
    cambiarCategoria(nuevo.categoria);
}

function seleccionar(comp, card){
    if(seleccionados.some(item => item.nombre === comp.nombre)) return;
    if(seleccionados.length >= 2) return;

    seleccionados.push(comp);
    card.classList.add("selected");

    if(seleccionados.length === 1){
        slot1.textContent = comp.nombre;
    }

    if(seleccionados.length === 2){
        slot2.textContent = comp.nombre;
        btnComparar.disabled = false;
    }
}

function comparar(){
    const c1 = seleccionados[0];
    const c2 = seleccionados[1];

    const c1Class = c1.rendimiento === c2.rendimiento ? "tie" : (c1.rendimiento > c2.rendimiento ? "winner" : "loser");
    const c2Class = c1.rendimiento === c2.rendimiento ? "tie" : (c2.rendimiento > c1.rendimiento ? "winner" : "loser");

    let ganador = "";
    let mensaje = "";
    const diferencia = Math.abs(c1.rendimiento - c2.rendimiento);

    if(c1.rendimiento > c2.rendimiento){
        ganador = c1.nombre;
        mensaje = `${c1.nombre} gana la comparación de ${categoriaActual} por ${diferencia} punto(s).`;
    } else if(c2.rendimiento > c1.rendimiento){
        ganador = c2.nombre;
        mensaje = `${c2.nombre} gana la comparación de ${categoriaActual} por ${diferencia} punto(s).`;
    } else {
        ganador = "Empate técnico";
        mensaje = `Ambos componentes ofrecen el mismo rendimiento en ${categoriaActual}.`;
    }

    const c1Progress = c1.rendimiento * 10;
    const c2Progress = c2.rendimiento * 10;

    resultado.innerHTML = `
        <div class="resultado-header">
            <div class="resultado-title">
                <h2>${ganador}</h2>
                <span class="resultado-badge">${categoriaActual}</span>
            </div>
            <p class="resultado-texto">${mensaje}</p>
        </div>
        <div class="resultado-grid">
            <article class="resultado-card ${c1Class}">
                <div class="card-hero">
                    <span>Componente 1</span>
                    <strong>${c1.nombre}</strong>
                </div>
                <p>${c1.specs}</p>
                <div class="stat-line">
                    <span>Rendimiento</span>
                    <strong>${c1.rendimiento}/10</strong>
                </div>
                <div class="stat-bar"><span style="width:${c1Progress}%"></span></div>
                <div class="stat-meta">
                    <span class="badge">${c1.gama}</span>
                    <span>${c1Progress}% potencial</span>
                </div>
            </article>
            <article class="resultado-card ${c2Class}">
                <div class="card-hero">
                    <span>Componente 2</span>
                    <strong>${c2.nombre}</strong>
                </div>
                <p>${c2.specs}</p>
                <div class="stat-line">
                    <span>Rendimiento</span>
                    <strong>${c2.rendimiento}/10</strong>
                </div>
                <div class="stat-bar"><span style="width:${c2Progress}%"></span></div>
                <div class="stat-meta">
                    <span class="badge">${c2.gama}</span>
                    <span>${c2Progress}% potencial</span>
                </div>
            </article>
        </div>
    `;
}

categoriaNueva.value = categoriaActual;
cargarComponentes();
