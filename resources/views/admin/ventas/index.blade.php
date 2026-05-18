@extends('layouts.app')

@section('title', 'Ventas')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8" style="background:#F2F4F7; font-family: 'Poppins', sans-serif;">

    <div class="flex flex-col md:flex-row items-start md:items-center justify-between mb-6 gap-4">
        <h1 class="text-2xl font-semibold text-gray-800">Punto de Venta - Administrador</h1>
        <div class="w-full md:w-2/5 relative">
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                <input type="search" id="pos-search" placeholder="Buscar por Nombre, SKU o Código de Barras..." class="w-full pl-10 pr-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-[#1E3A8A] focus:border-[#1E3A8A] outline-none transition-all shadow-sm font-medium" autocomplete="off" />
            </div>
            <!-- Floating Results -->
            <div id="search-results" class="absolute top-full left-0 right-0 bg-white border border-gray-200 rounded-xl mt-2 max-h-[400px] overflow-y-auto z-50 hidden shadow-2xl divide-y divide-gray-50">
                <!-- Injected via JS -->
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left: Search Results & Messages (2/3) -->
        <div class="lg:col-span-2 flex flex-col gap-4">
            <!-- Loading indicator -->
            <div id="loading-indicator" class="hidden justify-center py-12">
                <svg class="animate-spin h-10 w-10 text-[#1E3A8A]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            </div>
            
            <!-- Default / Empty State Message -->
            <div id="no-results-msg" class="bg-white rounded-2xl shadow-sm border border-gray-100 p-16 text-center text-gray-500 flex flex-col items-center justify-center">
                <div class="bg-blue-50 p-4 rounded-full mb-4">
                    <svg class="w-12 h-12 text-[#1E3A8A] opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2 7h14l-2-7M10 21a1 1 0 100-2 1 1 0 000 2zm6 0a1 1 0 100-2 1 1 0 000 2z"></path></svg>
                </div>
                <p class="text-xl font-semibold text-gray-700">El carrito está esperando</p>
                <p class="text-sm mt-2 text-gray-500 max-w-md">Utiliza la barra de búsqueda superior para encontrar insumos por Nombre, SKU o escaneando el Código de Barras.</p>
            </div>
        </div>

        <!-- Right: Ticket (1/3) -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-2xl shadow-lg p-6 flex flex-col sticky top-4 border-t-4 border-[#1E3A8A]">
                <div class="flex items-center justify-between mb-5 border-b border-gray-100 pb-4">
                    <h2 class="text-xl font-bold text-gray-800 flex items-center gap-2">
                        <svg class="w-5 h-5 text-[#1E3A8A]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                        Resumen de Venta
                    </h2>
                    <button id="btn-limpiar" class="text-sm text-red-500 hover:text-red-700 font-bold px-3 py-1.5 rounded-lg bg-red-50 hover:bg-red-100 transition-colors shadow-sm">Limpiar</button>
                </div>

                <!-- Items list -->
                <div id="ticket-items" class="flex-1 mb-6 overflow-y-auto max-h-[450px] space-y-3 pr-2 scrollbar-thin scrollbar-thumb-gray-200">
                    <div class="py-10 text-center">
                        <p class="text-gray-400 text-sm italic font-medium">No hay artículos en la cuenta</p>
                    </div>
                </div>

                <div class="border-t border-gray-200 pt-5 space-y-3">
                    <div class="flex justify-between text-sm text-gray-600 font-medium px-1">
                        <span>Subtotal</span>
                        <span id="subtotal" class="font-bold">$0.00</span>
                    </div>
                    <div class="flex justify-between text-sm text-gray-600 font-medium px-1">
                        <span>IVA (16%)</span>
                        <span id="iva" class="font-bold">$0.00</span>
                    </div>
                    <div class="flex justify-between text-2xl font-black text-[#1E3A8A] mt-3 pt-3 border-t border-dashed border-gray-300 px-1 bg-blue-50/50 rounded-lg p-2">
                        <span>Total</span>
                        <span id="total">$0.00</span>
                    </div>

                    <button id="btn-cobrar" class="mt-6 w-full bg-[#108981] hover:bg-teal-700 text-white font-bold py-4 rounded-xl text-lg transition-all shadow-md hover:shadow-lg disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:shadow-md flex items-center justify-center gap-2" disabled>
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        Procesar Cobro
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const IVA_PERCENT = 0.16;
    let carrito = [];
    let searchTimeout;

    // Elementos DOM
    const posSearch = document.getElementById('pos-search');
    const searchResults = document.getElementById('search-results');
    const loadingIndicator = document.getElementById('loading-indicator');
    const noResultsMsg = document.getElementById('no-results-msg');
    
    const ticketItems = document.getElementById('ticket-items');
    const subtotalEl = document.getElementById('subtotal');
    const ivaEl = document.getElementById('iva');
    const totalEl = document.getElementById('total');
    const btnCobrar = document.getElementById('btn-cobrar');
    const btnLimpiar = document.getElementById('btn-limpiar');

    // Cerrar resultados flotantes al hacer clic afuera
    document.addEventListener('click', (e) => {
        if (!posSearch.contains(e.target) && !searchResults.contains(e.target)) {
            searchResults.classList.add('hidden');
        }
    });

    // Input con Debounce para Búsqueda
    posSearch.addEventListener('input', (e) => {
        const query = e.target.value.trim();
        
        clearTimeout(searchTimeout);
        
        if (query.length < 2) {
            searchResults.classList.add('hidden');
            searchResults.innerHTML = '';
            // Solo mostramos el mensaje de "esperando" si el carrito está vacío (opcional, pero mejora la UI)
            if(carrito.length === 0) noResultsMsg.classList.remove('hidden');
            loadingIndicator.classList.add('hidden');
            return;
        }

        // Mostrar loading
        searchResults.classList.add('hidden');
        noResultsMsg.classList.add('hidden');
        loadingIndicator.classList.remove('hidden');
        loadingIndicator.classList.add('flex');

        searchTimeout = setTimeout(() => {
            realizarBusqueda(query);
        }, 300); // 300ms de Debounce
    });

    // Petición AJAX (Fetch) hacia la API
    async function realizarBusqueda(query) {
        try {
            const response = await fetch(`/pos/buscar-productos?q=${encodeURIComponent(query)}`, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            
            if (!response.ok) throw new Error('Network error');
            
            const productos = await response.json();
            
            loadingIndicator.classList.add('hidden');
            loadingIndicator.classList.remove('flex');
            
            if (productos.length === 0) {
                searchResults.innerHTML = `
                    <div class="p-6 text-center text-gray-500 font-medium flex flex-col items-center">
                        <svg class="w-10 h-10 text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        No se encontraron insumos para "${query}"
                    </div>
                `;
            } else {
                renderizarResultados(productos);
            }
            searchResults.classList.remove('hidden');
            
        } catch (error) {
            console.error('Error al buscar:', error);
            loadingIndicator.classList.add('hidden');
            loadingIndicator.classList.remove('flex');
            searchResults.innerHTML = `<div class="p-4 text-center text-red-500 font-medium">Error de conexión. Intenta nuevamente.</div>`;
            searchResults.classList.remove('hidden');
        }
    }

    // Dibujar resultados en el DOM
    function renderizarResultados(productos) {
        searchResults.innerHTML = productos.map(p => {
            const outOfStock = p.stock_actual <= 0;
            return `
            <div class="p-4 hover:bg-blue-50 transition-colors flex items-center justify-between ${outOfStock ? 'opacity-50 bg-gray-50 hover:bg-gray-50 cursor-not-allowed' : 'cursor-pointer'}" 
                 onclick="${outOfStock ? '' : `agregarAlCarrito(${p.id}, '${p.nombre.replace(/'/g, "\\'")}', ${p.precio_venta}, ${p.stock_actual})`}">
                <div class="flex-1">
                    <h4 class="font-bold text-gray-800 text-sm mb-1">${p.nombre}</h4>
                    <div class="flex items-center gap-2 text-[11px] text-gray-500 font-medium">
                        <span class="bg-gray-100 text-gray-600 px-2 py-0.5 rounded shadow-sm border border-gray-200">SKU: ${p.sku}</span> 
                        ${p.codigo_barras ? `<span class="bg-gray-100 text-gray-600 px-2 py-0.5 rounded shadow-sm border border-gray-200">CB: ${p.codigo_barras}</span>` : ''}
                        <span class="text-[#1E3A8A] uppercase">${p.categoria}</span>
                    </div>
                </div>
                <div class="text-right ml-4">
                    <p class="font-black text-[#1E3A8A] text-xl tracking-tight">$${p.precio_venta.toFixed(2)}</p>
                    <p class="text-xs font-bold mt-1 inline-flex items-center gap-1 ${outOfStock ? 'text-red-500 bg-red-50 px-2 py-0.5 rounded' : 'text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded'}">
                        ${outOfStock ? 'AGOTADO' : `<span class="w-2 h-2 rounded-full bg-emerald-500"></span> ${p.stock_actual} disp.`}
                    </p>
                </div>
            </div>
        `}).join('');
    }

    // Agregar al Carrito (Expuesto globalmente)
    window.agregarAlCarrito = function(id, nombre, precio, stock) {
        const index = carrito.findIndex(item => item.id === id);
        
        if (index > -1) {
            // Incrementar si hay stock
            if (carrito[index].cantidad < stock) {
                carrito[index].cantidad++;
            } else {
                alert(`Inventario insuficiente. Solo quedan ${stock} piezas de "${nombre}".`);
                return;
            }
        } else {
            // Añadir nuevo
            if (stock > 0) {
                carrito.push({
                    id: id,
                    nombre: nombre,
                    precio: parseFloat(precio),
                    cantidad: 1,
                    stock: parseInt(stock)
                });
            }
        }
        
        // Reset de búsqueda para el siguiente escaneo/input
        posSearch.value = '';
        searchResults.classList.add('hidden');
        noResultsMsg.classList.add('hidden');
        posSearch.focus();
        
        renderizarCarrito();
    }

    // Modificar cantidad en carrito
    window.cambiarCantidad = function(id, delta) {
        const index = carrito.findIndex(item => item.id === id);
        if (index > -1) {
            const item = carrito[index];
            const nuevaCantidad = item.cantidad + delta;
            
            if (nuevaCantidad <= 0) {
                carrito.splice(index, 1);
            } else if (nuevaCantidad <= item.stock) {
                item.cantidad = nuevaCantidad;
            } else {
                Swal.fire({
                    title: 'Stock Insuficiente',
                    text: `No puedes exceder el stock disponible (${item.stock} piezas).`,
                    icon: 'warning',
                    confirmButtonColor: '#1E3A8A'
                });
            }
            renderizarCarrito();
        }
    }

    // Eliminar producto por completo
    window.eliminarDelCarrito = function(id) {
        carrito = carrito.filter(item => item.id !== id);
        renderizarCarrito();
    }

    // Botón Limpiar
    btnLimpiar.addEventListener('click', () => {
        if(carrito.length > 0) {
            Swal.fire({
                title: '¿Limpiar carrito?',
                text: '¿Estás seguro de cancelar la venta y vaciar el carrito?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#EF4444',
                cancelButtonColor: '#6B7280',
                confirmButtonText: 'Sí, vaciar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    carrito = [];
                    renderizarCarrito();
                    noResultsMsg.classList.remove('hidden');
                    posSearch.focus();
                }
            });
        }
    });

    // Procesar Cobro
    btnCobrar.addEventListener('click', async () => {
        if (carrito.length === 0) return;

        // Deshabilitar botón temporalmente para evitar doble clic
        const originalText = btnCobrar.innerHTML;
        btnCobrar.disabled = true;
        btnCobrar.innerHTML = `<svg class="animate-spin h-5 w-5" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Procesando...`;

        try {
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            
            const payload = {
                productos: carrito.map(item => ({
                    producto_id: item.id,
                    cantidad: item.cantidad
                }))
            };

            const response = await fetch('/pos/cobrar', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': token
                },
                body: JSON.stringify(payload)
            });

            const data = await response.json();

            if (response.ok && data.success) {
                // Alerta de éxito
                Swal.fire({
                    title: '¡Venta Realizada con Éxito! 🎉',
                    html: `<b>Folio:</b> ${data.folio}<br><br><span class="text-2xl text-emerald-600 font-black">Total Cobrado: $${data.total.toFixed(2)}</span>`,
                    icon: 'success',
                    confirmButtonColor: '#10B981'
                });
                
                // Vaciar el carrito y restaurar UI
                carrito = [];
                renderizarCarrito();
                posSearch.focus();
                noResultsMsg.classList.remove('hidden');
                
            } else {
                Swal.fire({
                    title: 'Venta Rechazada',
                    text: data.error || 'Intente nuevamente.',
                    icon: 'error',
                    confirmButtonColor: '#EF4444'
                });
            }
        } catch (error) {
            console.error('Error:', error);
            Swal.fire({
                title: 'Error de Conexión',
                text: 'Hubo un problema al contactar con el servidor.',
                icon: 'error',
                confirmButtonColor: '#EF4444'
            });
        } finally {
            // Restaurar estado original del botón
            btnCobrar.innerHTML = originalText;
            if (carrito.length > 0) btnCobrar.disabled = false;
        }
    });

    // Render HTML del Carrito y Cálculos
    function renderizarCarrito() {
        if (carrito.length === 0) {
            ticketItems.innerHTML = `
                <div class="py-10 text-center">
                    <p class="text-gray-400 text-sm italic font-medium">No hay artículos en la cuenta</p>
                </div>`;
            subtotalEl.textContent = '$0.00';
            ivaEl.textContent = '$0.00';
            totalEl.textContent = '$0.00';
            btnCobrar.disabled = true;
            return;
        }

        let subtotal = 0;
        
        ticketItems.innerHTML = carrito.map(item => {
            const totalItem = item.precio * item.cantidad;
            subtotal += totalItem;
            
            return `
            <div class="bg-slate-50 rounded-xl p-3 border border-gray-200 shadow-sm flex flex-col gap-2 relative group hover:border-[#1E3A8A]/30 transition-colors">
                <div class="flex justify-between items-start pr-6">
                    <span class="font-bold text-gray-800 text-sm leading-tight">${item.nombre}</span>
                    <button onclick="eliminarDelCarrito(${item.id})" class="absolute top-3 right-3 text-gray-300 hover:text-red-500 hover:bg-red-50 p-1.5 rounded transition-colors" title="Eliminar producto">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                    </button>
                </div>
                <div class="flex justify-between items-end mt-1">
                    <div class="flex items-center bg-white border border-gray-300 rounded-lg shadow-sm overflow-hidden h-8">
                        <button onclick="cambiarCantidad(${item.id}, -1)" class="w-8 h-full flex items-center justify-center text-gray-600 hover:bg-gray-100 hover:text-red-600 transition-colors font-bold text-lg select-none">-</button>
                        <span class="w-8 text-sm font-black text-gray-800 text-center select-none">${item.cantidad}</span>
                        <button onclick="cambiarCantidad(${item.id}, 1)" class="w-8 h-full flex items-center justify-center text-gray-600 hover:bg-gray-100 hover:text-green-600 transition-colors font-bold text-lg select-none" ${item.cantidad >= item.stock ? 'disabled' : ''}>+</button>
                    </div>
                    <div class="text-right">
                        <span class="block text-[11px] text-gray-500 font-semibold mb-0.5">$${item.precio.toFixed(2)} c/u</span>
                        <span class="block font-black text-[#1E3A8A] text-lg leading-none">$${totalItem.toFixed(2)}</span>
                    </div>
                </div>
            </div>
            `;
        }).join('');

        const iva = subtotal * IVA_PERCENT;
        const total = subtotal + iva;

        subtotalEl.textContent = '$' + subtotal.toFixed(2);
        ivaEl.textContent = '$' + iva.toFixed(2);
        totalEl.textContent = '$' + total.toFixed(2);
        
        btnCobrar.disabled = false;
    }
});
</script>
@endsection
