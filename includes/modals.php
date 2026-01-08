<!-- Modal: Crear Cliente -->
<div id="clientModal" class="fixed inset-0 hidden items-center justify-center p-4 z-[50]">
    <!-- overlay NO cierra al click -->
    <div class="absolute inset-0 modal-backdrop"></div>

    <div class="relative w-full max-w-md bg-white shadow-2xl overflow-hidden"
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
        <form id="clientForm" class="p-5 space-y-6">
            <!-- Alerta de error -->
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

            <!-- Campos -->
            <div>
                <label class="block text-sm font-semibold text-gray-800">Cliente <span class="text-red-500">*</span></label>
                <input id="clientName"
                       class="w-full mt-1 px-3 py-[0.45rem] border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-200"
                       style="border-radius:0.3rem; background:#F9FAFB;"
                       placeholder="Nombre del cliente" />
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-800">Contacto</label>
                <input id="clientContact"
                       class="w-full mt-1 px-3 py-[0.45rem] border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-200"
                       style="border-radius:0.3rem; background:#F9FAFB;"
                       placeholder="Contacto del cliente" />
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-800">Teléfono</label>
                <input id="clientPhone"
                       type="tel"
                       class="w-full mt-1 px-3 py-[0.45rem] border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-200"
                       style="border-radius:0.3rem; background:#F9FAFB;"
                       placeholder="Teléfono" />
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-800">Dominio <span class="text-red-500">*</span></label>
                <input id="clientDomain"
                       class="w-full mt-1 px-3 py-[0.45rem] border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-200"
                       style="border-radius:0.3rem; background:#F9FAFB;"
                       placeholder="example.com" />
            </div>

            <!-- Botones -->
            <div class="grid grid-cols-2 gap-3 pt-1">
                <button type="button"
                        class="w-full px-4 py-[0.45rem] border border-gray-300 text-gray-700 hover:bg-gray-50 transition inline-flex items-center justify-center gap-2"
                        style="border-radius:0.3rem;"
                        onclick="ClientModal.close()">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="#6b7280" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M18 6L6 18"></path>
                        <path d="M6 6l12 12"></path>
                    </svg>
                    Cancelar
                </button>

                <button id="clientCreateBtn" type="button"
                        class="w-full px-4 py-[0.45rem] text-white font-semibold inline-flex items-center justify-center gap-2"
                        style="border-radius:0.3rem; background: linear-gradient(to right, #2563eb 0%, #1d4ed8 100%);"
                        onclick="ClientModal.submit()">
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
    <!-- overlay NO cierra al click -->
    <div class="absolute inset-0 modal-backdrop"></div>

    <div class="relative w-full max-w-md bg-white shadow-2xl overflow-hidden"
         style="border-radius:0.3rem; border:1px solid #1F54DE;">
        <!-- Header -->
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

            <!-- X -->
            <button type="button" class="p-2 hover:bg-white/10 transition" style="border-radius:0.3rem;" data-admin-x aria-label="Cerrar">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M18 6L6 18"></path>
                    <path d="M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <div class="p-5 space-y-4">
            <!-- Alert -->
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

            <!-- Credenciales actuales -->
            <div class="border border-gray-200 bg-gray-50 p-4" style="border-radius:0.3rem;">
                <div class="text-xs uppercase tracking-wider text-gray-500 mb-2">Credenciales actuales</div>
                <div class="space-y-2 text-sm">
                    <div class="flex items-center gap-2">
                        <span class="font-semibold text-gray-800 w-24">Usuario:</span>
                        <!-- CAMBIO: muestra el usuario actual (lo llena layout_end.php al abrir) -->
                        <span id="adminCurrentUser" class="text-gray-700">—</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="font-semibold text-gray-800 w-24">Contraseña:</span>
                        <!-- CAMBIO: se muestra la contraseña actual (siempre enmascarada) -->
                        <span id="adminCurrentPass" class="text-gray-700">******</span>
                    </div>
                </div>
            </div>

            <!-- Form -->
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
                            class="w-full px-4 py-[0.45rem] border border-gray-300 text-gray-700 hover:bg-gray-50 transition inline-flex items-center justify-center gap-2"
                            style="border-radius:0.3rem;"
                            onclick="AdminModal.close()">
                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="#6b7280" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
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

            <!-- Zona de peligro -->
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

                <!-- CAMBIO: llama a confirmResetDb (abre confirmación en AppModal) -->
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