<!-- Modal: Crear Cliente -->
<div id="clientModal" class="fixed inset-0 hidden items-center justify-center p-4 z-[50]">
    <!-- overlay NO cierra al click -->
    <div class="absolute inset-0 modal-backdrop"></div>

    <div class="relative w-full max-w-2xl bg-white shadow-2xl overflow-hidden"
         style="border-radius:0.3rem; border:1px solid #1F54DE;">
        <!-- Header del modal -->
        <div class="px-5 py-[0.7rem] text-white flex items-start justify-between"
             style="background: linear-gradient(to right, #2563eb 0%, #1d4ed8 100%);">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 bg-white/15 flex items-center justify-center" style="border-radius:0.3rem;">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20 21v-1a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v1"></path>
                        <path d="M12 12a4 4 0 1 0 0-8 4 4 0 0 0 0 8z"></path>
                    </svg>
                </div>
                <div class="leading-tight">
                    <div class="text-base font-semibold">Crear Cliente</div>
                    <div class="text-sm text-white/85">Agrega información básica</div>
                </div>
            </div>

            <button type="button" class="p-2 hover:bg-white/10 transition" style="border-radius:0.3rem;" data-client-x aria-label="Cerrar">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M18 6L6 18"></path>
                    <path d="M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <!-- Body del modal -->
        <form id="clientForm" class="p-5 space-y-5"
              onsubmit="
                event.preventDefault();
                if (window.ClientModal && typeof window.ClientModal.submit === 'function') {
                    return window.ClientModal.submit(event);
                }
                alert('ClientModal.submit no está cargado. Revisa includes/layout_end.php');
                return false;
              ">
            <!-- Alerta -->
            <div id="clientModalAlert" class="hidden border p-3 text-sm" style="border-radius:0.3rem;">
                <div class="flex items-start justify-between gap-3">
                    <div id="clientModalAlertText"></div>
                    <button type="button" class="p-1 hover:bg-black/5 transition" style="border-radius:0.3rem;" onclick="ClientModal.clearAlert()" aria-label="Cerrar alerta">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="#6b7280" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M18 6L6 18"></path>
                            <path d="M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-800">Cliente <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2">
                            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="#9ca3af" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M20 21v-1a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v1"></path>
                                <path d="M12 12a4 4 0 1 0 0-8 4 4 0 0 0 0 8z"></path>
                            </svg>
                        </span>
                        <input id="clientName"
                               autocomplete="off"
                               class="w-full mt-1 pl-11 pr-3 py-[0.45rem] border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-200"
                               style="border-radius:0.3rem; background:#F9FAFB;"
                               placeholder="Nombre del cliente" />
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-800">Contacto <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2">
                            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="#9ca3af" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                                <circle cx="9" cy="7" r="4"></circle>
                                <path d="M22 21v-2a4 4 0 0 0-3-3.87"></path>
                                <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                            </svg>
                        </span>
                        <input id="clientContact"
                               autocomplete="off"
                               class="w-full mt-1 pl-11 pr-3 py-[0.45rem] border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-200"
                               style="border-radius:0.3rem; background:#F9FAFB;"
                               placeholder="Contacto del cliente" />
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-800">Teléfono <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2">
                            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="#9ca3af" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.8 19.8 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6A19.8 19.8 0 0 1 2.08 4.18 2 2 0 0 1 4.06 2h3a2 2 0 0 1 2 1.72c.12.86.31 1.7.57 2.5a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.58-1.09a2 2 0 0 1 2.11-.45c.8.26 1.64.45 2.5.57A2 2 0 0 1 22 16.92z"></path>
                            </svg>
                        </span>
                        <input id="clientPhone"
                               inputmode="numeric"
                               autocomplete="off"
                               class="w-full mt-1 pl-11 pr-3 py-[0.45rem] border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-200"
                               style="border-radius:0.3rem; background:#F9FAFB;"
                               placeholder="Solo números" />
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-800">Dominio <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2">
                            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="#9ca3af" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10"></circle>
                                <path d="M2 12h20"></path>
                                <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path>
                            </svg>
                        </span>
                        <input id="clientDomain"
                               autocomplete="off"
                               class="w-full mt-1 pl-11 pr-3 py-[0.45rem] border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-200"
                               style="border-radius:0.3rem; background:#F9FAFB;"
                               placeholder="Ej: vancouver.edu.pe" />
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-800">Correo <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2">
                            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="#9ca3af" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M4 4h16a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2z"></path>
                                <path d="m22 6-10 7L2 6"></path>
                            </svg>
                        </span>
                        <input id="clientEmail"
                               type="email"
                               autocomplete="off"
                               class="w-full mt-1 pl-11 pr-3 py-[0.45rem] border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-200"
                               style="border-radius:0.3rem; background:#F9FAFB;"
                               placeholder="correo@dominio.com" />
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3 pt-1">
                <button type="button"
                        class="w-full px-4 py-[0.45rem] border border-blue-200 bg-blue-50 text-blue-700 hover:bg-blue-100 transition inline-flex items-center justify-center gap-2"
                        style="border-radius:0.3rem;"
                        onclick="ClientModal.close()">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="#1d4ed8" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M18 6L6 18"></path>
                        <path d="M6 6l12 12"></path>
                    </svg>
                    Cancelar
                </button>

                <button id="clientCreateBtn" type="submit"
                        class="w-full px-4 py-[0.45rem] text-white font-semibold inline-flex items-center justify-center gap-2"
                        style="border-radius:0.3rem; background: linear-gradient(to right, #2563eb 0%, #1d4ed8 100%);">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M15 12H9"></path>
                        <path d="M12 9v6"></path>
                        <path d="M12 21C7.03 21 3 16.97 3 12s4.03-9 9-9 9 4.03 9 9-4.03 9-9 9z"></path>
                    </svg>
                    Crear Cliente
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal: Editar Administrador -->
<div id="adminModal" class="fixed inset-0 hidden items-center justify-center p-4 z-[50]">
    <div class="absolute inset-0 modal-backdrop"></div>

    <div class="relative w-full max-w-md bg-white shadow-2xl overflow-hidden"
         style="border-radius:0.3rem; border:1px solid #1F54DE;">
        <div class="px-5 py-[0.7rem] text-white flex items-start justify-between"
             style="background: linear-gradient(to right, #2563eb 0%, #1d4ed8 100%);">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 bg-white/15 flex items-center justify-center" style="border-radius:0.3rem;">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20 21v-1a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v1"></path>
                        <path d="M12 12a4 4 0 1 0 0-8 4 4 0 0 0 0 8z"></path>
                    </svg>
                </div>
                <div class="leading-tight">
                    <div class="text-base font-semibold">Perfil Administrador</div>
                    <div class="text-sm text-white/85">Gestiona tu cuenta de acceso</div>
                </div>
            </div>

            <button type="button" class="p-2 hover:bg-white/10 transition" style="border-radius:0.3rem;" data-admin-x aria-label="Cerrar">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M18 6L6 18"></path>
                    <path d="M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <div class="p-5 space-y-4">
            <div id="adminModalAlert" class="hidden border p-3 text-sm" style="border-radius:0.3rem;">
                <div class="flex items-start justify-between gap-3">
                    <div id="adminModalAlertText"></div>
                    <button type="button" class="p-1 hover:bg-black/5 transition" style="border-radius:0.3rem;" onclick="AdminModal.clearAlert()" aria-label="Cerrar alerta">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="#6b7280" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M18 6L6 18"></path>
                            <path d="M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <div class="border border-gray-200 bg-gray-50 p-4" style="border-radius:0.3rem;">
                <div class="text-xs uppercase tracking-wider text-gray-500 mb-2">Credenciales actuales</div>
                <div class="space-y-2 text-sm">
                    <div class="flex items-center gap-2">
                        <span class="font-semibold text-gray-800 w-24">Usuario:</span>
                        <span id="adminCurrentUser" class="text-gray-700">—</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="font-semibold text-gray-800 w-24">Contraseña:</span>
                        <span id="adminCurrentPass" class="text-gray-700">******</span>
                    </div>
                </div>
            </div>

            <form id="adminEditForm" class="space-y-4" onsubmit="return AdminModal.submit(event)">
                <div>
                    <label class="block text-sm font-semibold text-gray-800">Nuevo Usuario <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2">
                            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="#9ca3af" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M20 21v-1a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v1"></path>
                                <path d="M12 12a4 4 0 1 0 0-8 4 4 0 0 0 0 8z"></path>
                            </svg>
                        </span>
                        <input id="adminNewUser"
                               class="w-full mt-1 pl-11 pr-3 py-[0.45rem] border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-200"
                               style="border-radius:0.3rem; background:#F9FAFB;"
                               placeholder="Usuario" />
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-800">Nueva Contraseña <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2">
                            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="#9ca3af" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M16 10V8a4 4 0 0 0-8 0v2"></path>
                                <rect x="5" y="10" width="14" height="11" rx="2" ry="2"></rect>
                                <path d="M12 15v2"></path>
                            </svg>
                        </span>

                        <input id="adminNewPass" type="password"
                               class="w-full mt-1 pl-11 pr-12 py-[0.45rem] border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-200"
                               style="border-radius:0.3rem; background:#F9FAFB;"
                               placeholder="Contraseña" />

                        <button type="button"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600"
                                onclick="AdminModal.toggleNewPassword()"
                                aria-label="Mostrar/Ocultar contraseña">
                            <svg id="adminPassEye" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12z"></path>
                                <circle cx="12" cy="12" r="3"></circle>
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3 pt-1">
                    <button type="button"
                            class="w-full px-4 py-[0.45rem] border border-blue-200 bg-blue-50 text-blue-700 hover:bg-blue-100 transition inline-flex items-center justify-center gap-2"
                            style="border-radius:0.3rem;"
                            onclick="AdminModal.close()">
                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="#1d4ed8" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M18 6L6 18"></path>
                            <path d="M6 6l12 12"></path>
                        </svg>
                        Cancelar
                    </button>

                    <button id="adminSaveBtn" type="submit"
                            class="w-full px-4 py-[0.45rem] text-white font-semibold inline-flex items-center justify-center gap-2"
                            style="border-radius:0.3rem; background: linear-gradient(to right, #2563eb 0%, #1d4ed8 100%);">
                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                            <path d="M17 21v-8H7v8"></path>
                            <path d="M7 3v5h8"></path>
                        </svg>
                        Guardar Cambios
                    </button>
                </div>
            </form>

            <div class="border border-red-200 bg-red-50 p-4" style="border-radius:0.3rem;">
                <div class="flex items-center gap-2 text-red-700 font-semibold">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="#b91c1c" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path>
                        <path d="M12 9v4"></path>
                        <path d="M12 17h.01"></path>
                    </svg>
                    Zona de Peligro
                </div>
                <div class="text-xs text-red-600 mt-1">Esta acción no se puede deshacer</div>

                <button type="button"
                        class="mt-3 w-full px-4 py-[0.45rem] text-white font-semibold bg-red-600 hover:bg-red-700 transition inline-flex items-center justify-center gap-2"
                        style="border-radius:0.3rem;"
                        onclick="AdminModal.confirmResetDb()">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 6h18"></path>
                        <path d="M8 6V4h8v2"></path>
                        <path d="M19 6l-1 14H6L5 6"></path>
                        <path d="M10 11v6"></path>
                        <path d="M14 11v6"></path>
                    </svg>
                    Resetear Base de Datos
                </button>
            </div>
        </div>
    </div>
</div>

<!-- ========================= -->
<!-- MODALES CATALOGOS (7)     -->
<!-- ========================= -->

<!-- Modal: Planes Hosting -->
<div id="modalPlanesHosting" class="fixed inset-0 hidden items-center justify-center p-4 z-[50]">
    <div class="absolute inset-0 modal-backdrop"></div>
    <div class="relative w-full max-w-3xl bg-white shadow-2xl overflow-hidden" style="border-radius:0.3rem; border:1px solid #1F54DE;">
        <div class="px-5 py-[0.7rem] text-white flex items-start justify-between" style="background: linear-gradient(to right, #2563eb 0%, #1d4ed8 100%);">
            <div class="text-base font-semibold">Planes Hosting</div>
            <button type="button" class="p-2 hover:bg-white/10 transition" style="border-radius:0.3rem;" data-cat-x="planes_hosting" aria-label="Cerrar">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="1.8"><path d="M18 6L6 18"></path><path d="M6 6l12 12"></path></svg>
            </button>
        </div>
        <div class="p-5 space-y-4">
            <div id="alert_planes_hosting" class="hidden border p-3 text-sm" style="border-radius:0.3rem;"></div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                <input id="in_planes_hosting_nombre" class="w-full px-3 py-[0.45rem] border border-gray-300 focus:outline-none" style="border-radius:0.3rem; background:#F9FAFB;" placeholder="Nombre del plan">
                <input id="in_planes_hosting_precio" class="w-full px-3 py-[0.45rem] border border-gray-300 focus:outline-none" style="border-radius:0.3rem; background:#F9FAFB;" placeholder="Precio" inputmode="decimal">
                <button type="button" id="btn_planes_hosting_guardar"
                        class="w-full px-4 py-[0.45rem] text-white font-semibold inline-flex items-center justify-center gap-2"
                        style="border-radius:0.3rem; background: linear-gradient(to right, #2563eb 0%, #1d4ed8 100%);">
                    Guardar
                </button>
            </div>

            <div class="overflow-x-auto border border-gray-200" style="border-radius:0.3rem;">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-50 text-gray-600">
                        <tr>
                            <th class="text-left px-4 py-3 font-semibold">NOMBRE</th>
                            <th class="text-left px-4 py-3 font-semibold">PRECIO</th>
                            <th class="text-center px-4 py-3 font-semibold">ACCIONES</th>
                        </tr>
                    </thead>
                    <tbody id="tb_planes_hosting" class="divide-y divide-gray-200"></tbody>
                </table>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <button type="button"
                        class="w-full px-4 py-[0.45rem] border border-blue-200 bg-blue-50 text-blue-700 hover:bg-blue-100 transition inline-flex items-center justify-center gap-2"
                        style="border-radius:0.3rem;"
                        data-cat-x="planes_hosting">
                    Cancelar
                </button>
                <button type="button"
                        class="w-full px-4 py-[0.45rem] text-white font-semibold inline-flex items-center justify-center gap-2"
                        style="border-radius:0.3rem; background: linear-gradient(to right, #2563eb 0%, #1d4ed8 100%);"
                        onclick="Catalogs.reset('planes_hosting')">
                    Nuevo
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal: Dominios TLD -->
<div id="modalTldDominios" class="fixed inset-0 hidden items-center justify-center p-4 z-[50]">
    <div class="absolute inset-0 modal-backdrop"></div>
    <div class="relative w-full max-w-3xl bg-white shadow-2xl overflow-hidden" style="border-radius:0.3rem; border:1px solid #1F54DE;">
        <div class="px-5 py-[0.7rem] text-white flex items-start justify-between" style="background: linear-gradient(to right, #2563eb 0%, #1d4ed8 100%);">
            <div class="text-base font-semibold">Dominios TLD</div>
            <button type="button" class="p-2 hover:bg-white/10 transition" style="border-radius:0.3rem;" data-cat-x="tld_dominios" aria-label="Cerrar">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="1.8"><path d="M18 6L6 18"></path><path d="M6 6l12 12"></path></svg>
            </button>
        </div>
        <div class="p-5 space-y-4">
            <div id="alert_tld_dominios" class="hidden border p-3 text-sm" style="border-radius:0.3rem;"></div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                <input id="in_tld_dominios_nombre" class="w-full px-3 py-[0.45rem] border border-gray-300 focus:outline-none" style="border-radius:0.3rem; background:#F9FAFB;" placeholder="TLD (ej: .com.pe)">
                <input id="in_tld_dominios_precio" class="w-full px-3 py-[0.45rem] border border-gray-300 focus:outline-none" style="border-radius:0.3rem; background:#F9FAFB;" placeholder="Precio" inputmode="decimal">
                <button type="button" id="btn_tld_dominios_guardar"
                        class="w-full px-4 py-[0.45rem] text-white font-semibold inline-flex items-center justify-center gap-2"
                        style="border-radius:0.3rem; background: linear-gradient(to right, #2563eb 0%, #1d4ed8 100%);">
                    Guardar
                </button>
            </div>

            <div class="overflow-x-auto border border-gray-200" style="border-radius:0.3rem;">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-50 text-gray-600">
                        <tr>
                            <th class="text-left px-4 py-3 font-semibold">TLD</th>
                            <th class="text-left px-4 py-3 font-semibold">PRECIO</th>
                            <th class="text-center px-4 py-3 font-semibold">ACCIONES</th>
                        </tr>
                    </thead>
                    <tbody id="tb_tld_dominios" class="divide-y divide-gray-200"></tbody>
                </table>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <button type="button"
                        class="w-full px-4 py-[0.45rem] border border-blue-200 bg-blue-50 text-blue-700 hover:bg-blue-100 transition inline-flex items-center justify-center gap-2"
                        style="border-radius:0.3rem;"
                        data-cat-x="tld_dominios">
                    Cancelar
                </button>
                <button type="button"
                        class="w-full px-4 py-[0.45rem] text-white font-semibold inline-flex items-center justify-center gap-2"
                        style="border-radius:0.3rem; background: linear-gradient(to right, #2563eb 0%, #1d4ed8 100%);"
                        onclick="Catalogs.reset('tld_dominios')">
                    Nuevo
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal: Registrantes -->
<div id="modalRegistrantes" class="fixed inset-0 hidden items-center justify-center p-4 z-[50]">
    <div class="absolute inset-0 modal-backdrop"></div>
    <div class="relative w-full max-w-3xl bg-white shadow-2xl overflow-hidden" style="border-radius:0.3rem; border:1px solid #1F54DE;">
        <div class="px-5 py-[0.7rem] text-white flex items-start justify-between" style="background: linear-gradient(to right, #2563eb 0%, #1d4ed8 100%);">
            <div class="text-base font-semibold">Dominios Registrante</div>
            <button type="button" class="p-2 hover:bg-white/10 transition" style="border-radius:0.3rem;" data-cat-x="registrantes" aria-label="Cerrar">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="1.8"><path d="M18 6L6 18"></path><path d="M6 6l12 12"></path></svg>
            </button>
        </div>
        <div class="p-5 space-y-4">
            <div id="alert_registrantes" class="hidden border p-3 text-sm" style="border-radius:0.3rem;"></div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                <input id="in_registrantes_nombre" class="w-full px-3 py-[0.45rem] border border-gray-300 focus:outline-none" style="border-radius:0.3rem; background:#F9FAFB;" placeholder="Empresa registrante">
                <input id="in_registrantes_precio" class="w-full px-3 py-[0.45rem] border border-gray-300 focus:outline-none" style="border-radius:0.3rem; background:#F9FAFB;" placeholder="Precio" inputmode="decimal">
                <button type="button" id="btn_registrantes_guardar"
                        class="w-full px-4 py-[0.45rem] text-white font-semibold inline-flex items-center justify-center gap-2"
                        style="border-radius:0.3rem; background: linear-gradient(to right, #2563eb 0%, #1d4ed8 100%);">
                    Guardar
                </button>
            </div>

            <div class="overflow-x-auto border border-gray-200" style="border-radius:0.3rem;">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-50 text-gray-600">
                        <tr>
                            <th class="text-left px-4 py-3 font-semibold">REGISTRANTE</th>
                            <th class="text-left px-4 py-3 font-semibold">PRECIO</th>
                            <th class="text-center px-4 py-3 font-semibold">ACCIONES</th>
                        </tr>
                    </thead>
                    <tbody id="tb_registrantes" class="divide-y divide-gray-200"></tbody>
                </table>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <button type="button"
                        class="w-full px-4 py-[0.45rem] border border-blue-200 bg-blue-50 text-blue-700 hover:bg-blue-100 transition inline-flex items-center justify-center gap-2"
                        style="border-radius:0.3rem;"
                        data-cat-x="registrantes">
                    Cancelar
                </button>
                <button type="button"
                        class="w-full px-4 py-[0.45rem] text-white font-semibold inline-flex items-center justify-center gap-2"
                        style="border-radius:0.3rem; background: linear-gradient(to right, #2563eb 0%, #1d4ed8 100%);"
                        onclick="Catalogs.reset('registrantes')">
                    Nuevo
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal: Tipos de Correo -->
<div id="modalTiposCorreo" class="fixed inset-0 hidden items-center justify-center p-4 z-[50]">
    <div class="absolute inset-0 modal-backdrop"></div>
    <div class="relative w-full max-w-3xl bg-white shadow-2xl overflow-hidden" style="border-radius:0.3rem; border:1px solid #1F54DE;">
        <div class="px-5 py-[0.7rem] text-white flex items-start justify-between" style="background: linear-gradient(to right, #2563eb 0%, #1d4ed8 100%);">
            <div class="text-base font-semibold">Tipo de Correo</div>
            <button type="button" class="p-2 hover:bg-white/10 transition" style="border-radius:0.3rem;" data-cat-x="tipos_correo" aria-label="Cerrar">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="1.8"><path d="M18 6L6 18"></path><path d="M6 6l12 12"></path></svg>
            </button>
        </div>
        <div class="p-5 space-y-4">
            <div id="alert_tipos_correo" class="hidden border p-3 text-sm" style="border-radius:0.3rem;"></div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                <input id="in_tipos_correo_nombre" class="w-full px-3 py-[0.45rem] border border-gray-300 focus:outline-none" style="border-radius:0.3rem; background:#F9FAFB;" placeholder="Tipo (Zoho, Gmail...)">
                <input id="in_tipos_correo_precio" class="w-full px-3 py-[0.45rem] border border-gray-300 focus:outline-none" style="border-radius:0.3rem; background:#F9FAFB;" placeholder="Precio" inputmode="decimal">
                <button type="button" id="btn_tipos_correo_guardar"
                        class="w-full px-4 py-[0.45rem] text-white font-semibold inline-flex items-center justify-center gap-2"
                        style="border-radius:0.3rem; background: linear-gradient(to right, #2563eb 0%, #1d4ed8 100%);">
                    Guardar
                </button>
            </div>

            <div class="overflow-x-auto border border-gray-200" style="border-radius:0.3rem;">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-50 text-gray-600">
                        <tr>
                            <th class="text-left px-4 py-3 font-semibold">TIPO</th>
                            <th class="text-left px-4 py-3 font-semibold">PRECIO</th>
                            <th class="text-center px-4 py-3 font-semibold">ACCIONES</th>
                        </tr>
                    </thead>
                    <tbody id="tb_tipos_correo" class="divide-y divide-gray-200"></tbody>
                </table>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <button type="button"
                        class="w-full px-4 py-[0.45rem] border border-blue-200 bg-blue-50 text-blue-700 hover:bg-blue-100 transition inline-flex items-center justify-center gap-2"
                        style="border-radius:0.3rem;"
                        data-cat-x="tipos_correo">
                    Cancelar
                </button>
                <button type="button"
                        class="w-full px-4 py-[0.45rem] text-white font-semibold inline-flex items-center justify-center gap-2"
                        style="border-radius:0.3rem; background: linear-gradient(to right, #2563eb 0%, #1d4ed8 100%);"
                        onclick="Catalogs.reset('tipos_correo')">
                    Nuevo
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal: Diseño Web -->
<div id="modalTiposDisenoWeb" class="fixed inset-0 hidden items-center justify-center p-4 z-[50]">
    <div class="absolute inset-0 modal-backdrop"></div>
    <div class="relative w-full max-w-3xl bg-white shadow-2xl overflow-hidden" style="border-radius:0.3rem; border:1px solid #1F54DE;">
        <div class="px-5 py-[0.7rem] text-white flex items-start justify-between" style="background: linear-gradient(to right, #2563eb 0%, #1d4ed8 100%);">
            <div class="text-base font-semibold">Diseño Web</div>
            <button type="button" class="p-2 hover:bg-white/10 transition" style="border-radius:0.3rem;" data-cat-x="tipos_diseno_web" aria-label="Cerrar">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="1.8"><path d="M18 6L6 18"></path><path d="M6 6l12 12"></path></svg>
            </button>
        </div>
        <div class="p-5 space-y-4">
            <div id="alert_tipos_diseno_web" class="hidden border p-3 text-sm" style="border-radius:0.3rem;"></div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                <input id="in_tipos_diseno_web_nombre" class="w-full px-3 py-[0.45rem] border border-gray-300 focus:outline-none" style="border-radius:0.3rem; background:#F9FAFB;" placeholder="Tipo (Landing, Tienda...)">
                <input id="in_tipos_diseno_web_precio" class="w-full px-3 py-[0.45rem] border border-gray-300 focus:outline-none" style="border-radius:0.3rem; background:#F9FAFB;" placeholder="Precio" inputmode="decimal">
                <button type="button" id="btn_tipos_diseno_web_guardar"
                        class="w-full px-4 py-[0.45rem] text-white font-semibold inline-flex items-center justify-center gap-2"
                        style="border-radius:0.3rem; background: linear-gradient(to right, #2563eb 0%, #1d4ed8 100%);">
                    Guardar
                </button>
            </div>

            <div class="overflow-x-auto border border-gray-200" style="border-radius:0.3rem;">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-50 text-gray-600">
                        <tr>
                            <th class="text-left px-4 py-3 font-semibold">TIPO</th>
                            <th class="text-left px-4 py-3 font-semibold">PRECIO</th>
                            <th class="text-center px-4 py-3 font-semibold">ACCIONES</th>
                        </tr>
                    </thead>
                    <tbody id="tb_tipos_diseno_web" class="divide-y divide-gray-200"></tbody>
                </table>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <button type="button"
                        class="w-full px-4 py-[0.45rem] border border-blue-200 bg-blue-50 text-blue-700 hover:bg-blue-100 transition inline-flex items-center justify-center gap-2"
                        style="border-radius:0.3rem;"
                        data-cat-x="tipos_diseno_web">
                    Cancelar
                </button>
                <button type="button"
                        class="w-full px-4 py-[0.45rem] text-white font-semibold inline-flex items-center justify-center gap-2"
                        style="border-radius:0.3rem; background: linear-gradient(to right, #2563eb 0%, #1d4ed8 100%);"
                        onclick="Catalogs.reset('tipos_diseno_web')">
                    Nuevo
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal: Diseño Gráfico -->
<div id="modalTiposDisenoGrafico" class="fixed inset-0 hidden items-center justify-center p-4 z-[50]">
    <div class="absolute inset-0 modal-backdrop"></div>
    <div class="relative w-full max-w-3xl bg-white shadow-2xl overflow-hidden" style="border-radius:0.3rem; border:1px solid #1F54DE;">
        <div class="px-5 py-[0.7rem] text-white flex items-start justify-between" style="background: linear-gradient(to right, #2563eb 0%, #1d4ed8 100%);">
            <div class="text-base font-semibold">Diseño Gráfico</div>
            <button type="button" class="p-2 hover:bg-white/10 transition" style="border-radius:0.3rem;" data-cat-x="tipos_diseno_grafico" aria-label="Cerrar">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="1.8"><path d="M18 6L6 18"></path><path d="M6 6l12 12"></path></svg>
            </button>
        </div>
        <div class="p-5 space-y-4">
            <div id="alert_tipos_diseno_grafico" class="hidden border p-3 text-sm" style="border-radius:0.3rem;"></div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                <input id="in_tipos_diseno_grafico_nombre" class="w-full px-3 py-[0.45rem] border border-gray-300 focus:outline-none" style="border-radius:0.3rem; background:#F9FAFB;" placeholder="Tipo (Logo, Video...)">
                <input id="in_tipos_diseno_grafico_precio" class="w-full px-3 py-[0.45rem] border border-gray-300 focus:outline-none" style="border-radius:0.3rem; background:#F9FAFB;" placeholder="Precio" inputmode="decimal">
                <button type="button" id="btn_tipos_diseno_grafico_guardar"
                        class="w-full px-4 py-[0.45rem] text-white font-semibold inline-flex items-center justify-center gap-2"
                        style="border-radius:0.3rem; background: linear-gradient(to right, #2563eb 0%, #1d4ed8 100%);">
                    Guardar
                </button>
            </div>

            <div class="overflow-x-auto border border-gray-200" style="border-radius:0.3rem;">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-50 text-gray-600">
                        <tr>
                            <th class="text-left px-4 py-3 font-semibold">TIPO</th>
                            <th class="text-left px-4 py-3 font-semibold">PRECIO</th>
                            <th class="text-center px-4 py-3 font-semibold">ACCIONES</th>
                        </tr>
                    </thead>
                    <tbody id="tb_tipos_diseno_grafico" class="divide-y divide-gray-200"></tbody>
                </table>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <button type="button"
                        class="w-full px-4 py-[0.45rem] border border-blue-200 bg-blue-50 text-blue-700 hover:bg-blue-100 transition inline-flex items-center justify-center gap-2"
                        style="border-radius:0.3rem;"
                        data-cat-x="tipos_diseno_grafico">
                    Cancelar
                </button>
                <button type="button"
                        class="w-full px-4 py-[0.45rem] text-white font-semibold inline-flex items-center justify-center gap-2"
                        style="border-radius:0.3rem; background: linear-gradient(to right, #2563eb 0%, #1d4ed8 100%);"
                        onclick="Catalogs.reset('tipos_diseno_grafico')">
                    Nuevo
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal: OTRO -->
<div id="modalTiposOtro" class="fixed inset-0 hidden items-center justify-center p-4 z-[50]">
    <div class="absolute inset-0 modal-backdrop"></div>
    <div class="relative w-full max-w-3xl bg-white shadow-2xl overflow-hidden" style="border-radius:0.3rem; border:1px solid #1F54DE;">
        <div class="px-5 py-[0.7rem] text-white flex items-start justify-between" style="background: linear-gradient(to right, #2563eb 0%, #1d4ed8 100%);">
            <div class="text-base font-semibold">Otro</div>
            <button type="button" class="p-2 hover:bg-white/10 transition" style="border-radius:0.3rem;" data-cat-x="tipos_otro" aria-label="Cerrar">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="1.8"><path d="M18 6L6 18"></path><path d="M6 6l12 12"></path></svg>
            </button>
        </div>
        <div class="p-5 space-y-4">
            <div id="alert_tipos_otro" class="hidden border p-3 text-sm" style="border-radius:0.3rem;"></div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                <input id="in_tipos_otro_nombre" class="w-full px-3 py-[0.45rem] border border-gray-300 focus:outline-none" style="border-radius:0.3rem; background:#F9FAFB;" placeholder="Tipo (PDF, Carátula...)">
                <input id="in_tipos_otro_precio" class="w-full px-3 py-[0.45rem] border border-gray-300 focus:outline-none" style="border-radius:0.3rem; background:#F9FAFB;" placeholder="Precio" inputmode="decimal">
                <button type="button" id="btn_tipos_otro_guardar"
                        class="w-full px-4 py-[0.45rem] text-white font-semibold inline-flex items-center justify-center gap-2"
                        style="border-radius:0.3rem; background: linear-gradient(to right, #2563eb 0%, #1d4ed8 100%);">
                    Guardar
                </button>
            </div>

            <div class="overflow-x-auto border border-gray-200" style="border-radius:0.3rem;">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-50 text-gray-600">
                        <tr>
                            <th class="text-left px-4 py-3 font-semibold">TIPO</th>
                            <th class="text-left px-4 py-3 font-semibold">PRECIO</th>
                            <th class="text-center px-4 py-3 font-semibold">ACCIONES</th>
                        </tr>
                    </thead>
                    <tbody id="tb_tipos_otro" class="divide-y divide-gray-200"></tbody>
                </table>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <button type="button"
                        class="w-full px-4 py-[0.45rem] border border-blue-200 bg-blue-50 text-blue-700 hover:bg-blue-100 transition inline-flex items-center justify-center gap-2"
                        style="border-radius:0.3rem;"
                        data-cat-x="tipos_otro">
                    Cancelar
                </button>
                <button type="button"
                        class="w-full px-4 py-[0.45rem] text-white font-semibold inline-flex items-center justify-center gap-2"
                        style="border-radius:0.3rem; background: linear-gradient(to right, #2563eb 0%, #1d4ed8 100%);"
                        onclick="Catalogs.reset('tipos_otro')">
                    Nuevo
                </button>
            </div>
        </div>
    </div>
</div>