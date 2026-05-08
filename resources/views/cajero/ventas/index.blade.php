@extends('layouts.app')

@section('title','Punto de Venta')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-3xl font-bold mb-6 text-gray-800">Punto de Venta</h1>
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Panel de Búsqueda -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-lg p-6 border-2 border-gray-300">
                <label class="block text-sm font-bold text-gray-900 mb-3">
                    Buscar producto
                </label>
                <div class="relative mb-4">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-500" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <input 
                        id="pos-search" 
                        type="text" 
                        placeholder="Buscar por Nombre, SKU o Categoría..." 
                        class="w-full pl-10 pr-4 py-3 border-2 border-gray-400 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white font-semibold"
                        autocomplete="off"
                    >
                    <div id="search-results" class="absolute top-full left-0 right-0 bg-white border-2 border-gray-400 rounded-lg mt-1 max-h-64 overflow-y-auto z-50 hidden shadow-2xl" style="background-color: rgba(255, 255, 255, 1) !important;"></div>
                </div>

                <div class="text-center text-gray-500 py-8" id="no-results-msg">
                    <p>Ingresa un término de búsqueda</p>
                </div>
            </div>
        </div>

        <!-- Panel de Carrito -->
        <div class="lg:col-span-1">
            <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-lg shadow-lg p-6 sticky top-4">
                <div id="ticket-items" class="space-y-2 mb-4 max-h-96 overflow-y-auto">
                    <p class="text-center text-gray-500 text-sm py-4">Sin productos</p>
                </div>

                <div class="border-t-2 border-blue-200 pt-4 space-y-3">
                    <div class="flex justify-between text-gray-700">
                        <span>Subtotal:</span>
                        <span id="subtotal">$0.00</span>
                    </div>
                    <div class="flex justify-between text-gray-700">
                        <span>IVA (16%):</span>
                        <span id="iva">$0.00</span>
                    </div>
                    <div class="flex justify-between text-2xl font-bold text-blue-600 bg-white p-3 rounded-lg">
                        <span>Total:</span>
                        <span id="total">$0.00</span>
                    </div>
                </div>

                <div class="mt-6 space-y-2">
                    <button 
                        id="btn-cobrar" 
                        class="w-full bg-green-600 text-white py-3 rounded-lg font-semibold hover:bg-green-700 transition disabled:bg-gray-400 disabled:cursor-not-allowed"
                        disabled
                    >
                        Cobrar
                    </button>
                    <button 
                        id="btn-limpiar" 
                        class="w-full bg-gray-400 text-white py-2 rounded-lg font-semibold hover:bg-gray-500 transition"
                    >
                        Limpiar
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Confirmación de Pago -->
<div id="modal-pago" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-lg shadow-2xl p-5 max-w-md w-full mx-4 max-h-[85vh] overflow-y-auto">
        <h2 class="text-xl font-bold text-gray-800 mb-3">Confirmación de Pago</h2>
        
        <div class="bg-green-50 border-2 border-green-200 rounded-lg p-3 mb-3">
            <p class="text-gray-600 text-xs mb-1">Total a cobrar:</p>
            <p class="text-3xl font-bold text-green-600" id="modal-total">$0.00</p>
        </div>

        <div class="space-y-2 mb-3">
            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Monto recibido:</label>
                <input 
                    type="number" 
                    id="monto-recibido" 
                    class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500"
                    placeholder="0.00"
                    step="0.01"
                    min="0"
                >
            </div>

            <div class="bg-gray-100 border border-gray-300 py-0 px-3 rounded-lg">
                <p class="text-2xl font-bold text-green-700 leading-none m-0" id="cambio" style="color: #16a34a !important; font-size: 1.75rem !important; font-weight: bold !important; margin-top: 2px !important;">$0.00</p>
            </div>
        </div>

        <div class="space-y-2">
            <button 
                id="btn-confirmar-pago" 
                class="w-full bg-green-600 text-white py-2 text-sm rounded-lg font-semibold hover:bg-green-700 transition disabled:bg-gray-400"
                disabled
            >
                Confirmar Venta
            </button>
            <button 
                id="btn-cancelar-pago" 
                class="w-full bg-gray-400 text-white py-2 text-sm rounded-lg font-semibold hover:bg-gray-500 transition"
            >
                Cancelar
            </button>
        </div>
    </div>
</div>

<!-- Modal de Venta Exitosa -->
<div id="modal-exito" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-lg shadow-2xl p-8 max-w-md w-full mx-4 text-center">
        <div class="text-6xl mb-4">✓</div>
        <h2 class="text-2xl font-bold text-green-600 mb-2">¡Venta Completada!</h2>
        <p class="text-gray-600 mb-6" id="folio-venta">Folio: VNT-20260506-XXXXXX</p>
        <p class="text-gray-700 mb-6 font-semibold" id="total-venta">Total: $0.00</p>
        <button 
            id="btn-nuevo-ticket" 
            class="w-full bg-blue-600 text-white py-3 rounded-lg font-semibold hover:bg-blue-700 transition"
        >
            Nuevo Ticket
        </button>
    </div>
</div>

<script>
const IVA_PERCENT = 0.16;
let carrito = [];
let productosBuscados = [];

// Elementos del DOM
const posSearch = document.getElementById('pos-search');
const searchResults = document.getElementById('search-results');
const ticketItems = document.getElementById('ticket-items');
const btnCobrar = document.getElementById('btn-cobrar');
const btnLimpiar = document.getElementById('btn-limpiar');
const modalPago = document.getElementById('modal-pago');
const modalExito = document.getElementById('modal-exito');
const montoRecibido = document.getElementById('monto-recibido');
const btnConfirmarPago = document.getElementById('btn-confirmar-pago');
const btnCancelarPago = document.getElementById('btn-cancelar-pago');
const btnNuevoTicket = document.getElementById('btn-nuevo-ticket');

// Buscar productos
posSearch.addEventListener('input', async (e) => {
    const query = e.target.value.trim();
    
    if (query.length < 2) {
        searchResults.classList.add('hidden');
        document.getElementById('no-results-msg').classList.remove('hidden');
        return;
    }

    try {
        const response = await fetch('/cajero/ventas/search', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
            },
            body: JSON.stringify({ q: query })
        });

        const productos = await response.json();
        productosBuscados = productos;

        if (productos.length === 0) {
            searchResults.classList.add('hidden');
            document.getElementById('no-results-msg').innerHTML = '<p>No se encontraron productos</p>';
            document.getElementById('no-results-msg').classList.remove('hidden');
            return;
        }

        searchResults.innerHTML = productos.map(p => `
            <div class="px-4 py-3 hover:bg-blue-50 cursor-pointer border-b last:border-b-0 transition bg-white" data-producto-id="${p.id}">
                <div class="flex justify-between items-start">
                    <div>
                        <div class="font-bold text-gray-900 text-base">${p.nombre}</div>
                        <div class="text-xs text-gray-600 mt-1 font-medium">SKU: ${p.sku} <span class="mx-1 text-gray-300">•</span> <span class="bg-gray-100 px-2 py-0.5 rounded text-gray-700 border border-gray-200">${p.categoria}</span></div>
                    </div>
                    <div class="text-right">
                        <div class="text-lg font-bold text-blue-700 leading-none">$${p.precio_venta.toFixed(2)}</div>
                        <div class="text-xs font-bold mt-1 ${p.stock_actual > 0 ? 'text-green-600 bg-green-50 px-2 rounded border border-green-200' : 'text-red-600 bg-red-50 px-2 rounded border border-red-200'}">
                            ${p.stock_actual} en stock
                        </div>
                    </div>
                </div>
            </div>
        `).join('');

        searchResults.classList.remove('hidden');
        document.getElementById('no-results-msg').classList.add('hidden');
    } catch (error) {
        console.error('Error en búsqueda:', error);
        searchResults.classList.add('hidden');
    }
});

// Event delegation para resultados de búsqueda
searchResults.addEventListener('click', (e) => {
    const item = e.target.closest('[data-producto-id]');
    if (item) {
        const productoId = parseInt(item.dataset.productoId);
        agregarProducto(productoId);
    }
});

// Agregar producto al carrito
function agregarProducto(productoId) {
    const producto = productosBuscados.find(p => p.id === productoId);
    if (!producto) return;

    if (producto.stock_actual === 0) {
        alert('Producto sin stock disponible');
        return;
    }

    const itemExistente = carrito.find(i => i.id === productoId);
    
    if (itemExistente) {
        if (itemExistente.cantidad < producto.stock_actual) {
            itemExistente.cantidad++;
        } else {
            alert('Stock insuficiente');
            return;
        }
    } else {
        carrito.push({
            id: productoId,
            nombre: producto.nombre,
            precio: producto.precio_venta,
            cantidad: 1,
            stock: producto.stock_actual
        });
    }

    posSearch.value = '';
    searchResults.classList.add('hidden');
    document.getElementById('no-results-msg').classList.remove('hidden');
    actualizarCarrito();
}

// Actualizar vista del carrito
function actualizarCarrito() {
    if (carrito.length === 0) {
        ticketItems.innerHTML = '<p class="text-center text-gray-500 text-sm py-4">Sin productos</p>';
        btnCobrar.disabled = true;
        document.getElementById('subtotal').textContent = '$0.00';
        document.getElementById('iva').textContent = '$0.00';
        document.getElementById('total').textContent = '$0.00';
        return;
    }

    ticketItems.innerHTML = carrito.map((item, idx) => {
        const subtotal = item.precio * item.cantidad;
        return `
            <div class="bg-white p-3 rounded-lg border border-gray-200 flex justify-between items-center" data-carrito-idx="${idx}">
                <div class="flex-1">
                    <div class="font-semibold text-gray-800 text-sm">${item.nombre}</div>
                    <div class="text-xs text-gray-600">$${item.precio.toFixed(2)} x ${item.cantidad}</div>
                </div>
                <div class="text-right">
                    <div class="font-bold text-blue-600 mb-1">$${subtotal.toFixed(2)}</div>
                    <div class="flex gap-1">
                        <button class="btn-menos bg-red-500 text-white px-2 py-1 rounded text-xs hover:bg-red-600 transition">-</button>
                        <button class="btn-mas bg-green-500 text-white px-2 py-1 rounded text-xs hover:bg-green-600 transition" ${item.cantidad >= item.stock ? 'disabled' : ''}>+</button>
                        <button class="btn-eliminar bg-gray-400 text-white px-2 py-1 rounded text-xs hover:bg-gray-500 transition">×</button>
                    </div>
                </div>
            </div>
        `;
    }).join('');

    const subtotal = carrito.reduce((sum, item) => sum + (item.precio * item.cantidad), 0);
    const iva = subtotal * IVA_PERCENT;
    const total = subtotal + iva;

    document.getElementById('subtotal').textContent = '$' + subtotal.toFixed(2);
    document.getElementById('iva').textContent = '$' + iva.toFixed(2);
    document.getElementById('total').textContent = '$' + total.toFixed(2);

    btnCobrar.disabled = false;
}

// Event delegation para botones de carrito
ticketItems.addEventListener('click', (e) => {
    const carritoPadre = e.target.closest('[data-carrito-idx]');
    if (!carritoPadre) return;
    
    const idx = parseInt(carritoPadre.dataset.carritoIdx);
    
    if (e.target.closest('.btn-menos')) {
        cambiarCantidad(idx, -1);
    } else if (e.target.closest('.btn-mas')) {
        cambiarCantidad(idx, 1);
    } else if (e.target.closest('.btn-eliminar')) {
        eliminarProducto(idx);
    }
});

function cambiarCantidad(idx, delta) {
    const item = carrito[idx];
    const nuevaCantidad = item.cantidad + delta;

    if (nuevaCantidad < 1) {
        carrito.splice(idx, 1);
    } else if (nuevaCantidad <= item.stock) {
        item.cantidad = nuevaCantidad;
    }

    actualizarCarrito();
}

function eliminarProducto(idx) {
    carrito.splice(idx, 1);
    actualizarCarrito();
}

// Botón Limpiar Carrito
btnLimpiar.addEventListener('click', () => {
    carrito = [];
    actualizarCarrito();
    posSearch.focus();
});

// Botón Cobrar
btnCobrar.addEventListener('click', () => {
    const total = carrito.reduce((sum, item) => sum + (item.precio * item.cantidad), 0) * (1 + IVA_PERCENT);
    document.getElementById('modal-total').textContent = '$' + total.toFixed(2);
    montoRecibido.value = '';
    document.getElementById('cambio').textContent = '$0.00';
    btnConfirmarPago.disabled = true;
    modalPago.classList.remove('hidden');
    montoRecibido.focus();
});

// Calcular cambio
function actualizarCambio() {
    const total = carrito.reduce((sum, item) => sum + (item.precio * item.cantidad), 0) * (1 + IVA_PERCENT);
    const monto = parseFloat(montoRecibido.value) || 0;
    const cambio = monto - total;
    const cambioElement = document.getElementById('cambio');
    
    cambioElement.textContent = '$' + cambio.toFixed(2);
    cambioElement.style.color = cambio >= 0 ? '#16a34a' : '#dc2626';
    btnConfirmarPago.disabled = cambio < 0;
}

montoRecibido.addEventListener('input', actualizarCambio);
montoRecibido.addEventListener('change', actualizarCambio);
montoRecibido.addEventListener('keyup', actualizarCambio);

// Confirmar pago
btnConfirmarPago.addEventListener('click', async () => {
    const total = carrito.reduce((sum, item) => sum + (item.precio * item.cantidad), 0) * (1 + IVA_PERCENT);
    
    const productos = carrito.map(item => ({
        producto_id: item.id,
        cantidad: item.cantidad
    }));

    try {
        const response = await fetch('/cajero/ventas/store', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
            },
            body: JSON.stringify({ productos })
        });

        const data = await response.json();
        
        if (response.ok) {
            modalPago.classList.add('hidden');
            document.getElementById('folio-venta').textContent = 'Folio: ' + data.folio;
            document.getElementById('total-venta').textContent = 'Total: $' + data.total.toFixed(2);
            modalExito.classList.remove('hidden');
            carrito = [];
            actualizarCarrito();
        } else {
            alert('Error: ' + (data.error || 'No se pudo procesar la venta'));
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Error al procesar la venta');
    }
});

// Cancelar pago
btnCancelarPago.addEventListener('click', () => {
    modalPago.classList.add('hidden');
});

// Nuevo ticket
btnNuevoTicket.addEventListener('click', () => {
    modalExito.classList.add('hidden');
    posSearch.value = '';
    searchResults.classList.add('hidden');
    document.getElementById('no-results-msg').classList.remove('hidden');
    posSearch.focus();
});
</script>


<style>
@media (max-width: 1024px) {
    .grid-cols-1.lg\:col-span-2 { grid-column: span 1; }
    .grid-cols-1.lg\:col-span-1 { grid-column: span 1; }
    .sticky { position: relative; }
}

@media (max-width: 640px) {
    .text-3xl { font-size: 1.5rem; }
    .max-h-96 { max-height: 250px; }
}
</style>
@endsection
