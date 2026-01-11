<!-- Modal: Crear Cliente -->
<div id="clientModal" class="fixed inset-0 hidden items-center justify-center p-4 z-[50]">
    <div class="absolute inset-0 modal-backdrop"></div>

    <div class="relative w-full max-w-2xl bg-white shadow-2xl overflow-hidden"
         style="border-radius:0.3rem; border:1px solid #1F54DE;">
        <div class="px-5 py-[0.7rem] text-white flex items-center justify-between"
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

        <form id="clientForm" class="p-5 space-y-5"
              onsubmit="
                event.preventDefault();
                if (window.ClientModal && typeof window.ClientModal.submit === 'function') {
                    return window.ClientModal.submit(event);
                }
                alert('ClientModal.submit no está cargado. Revisa includes/layout_end.php');
                return false;
              ">
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

            <!-- Si tu repo tiene otros campos aquí, respétalos. -->
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
                               class="w-full mt-1 pl-11 pr-3 py-[0.45rem] border border-gray-300 focus:outline-none"
                               style="background:#F9FAFB;"
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
                               class="w-full mt-1 pl-11 pr-3 py-[0.45rem] border border-gray-300 focus:outline-none"
                               style="background:#F9FAFB;"
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
                               class="w-full mt-1 pl-11 pr-3 py-[0.45rem] border border-gray-300 focus:outline-none"
                               style="background:#F9FAFB;"
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
                               class="w-full mt-1 pl-11 pr-3 py-[0.45rem] border border-gray-300 focus:outline-none"
                               style="background:#F9FAFB;"
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
                               class="w-full mt-1 pl-11 pr-3 py-[0.45rem] border border-gray-300 focus:outline-none"
                               style="background:#F9FAFB;"
                               placeholder="correo@dominio.com" />
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3 pt-1">
                <button type="button"
                        class="w-full px-4 py-[0.45rem] border border-blue-200 bg-blue-50 text-blue-700 hover:bg-blue-100 transition inline-flex items-center justify-center gap-2"
                        onclick="ClientModal.close()">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="#1d4ed8" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M18 6L6 18"></path>
                        <path d="M6 6l12 12"></path>
                    </svg>
                    Cancelar
                </button>

                <button id="clientCreateBtn" type="submit"
                        class="w-full px-4 py-[0.45rem] text-white font-semibold inline-flex items-center justify-center gap-2"
                        style="background: linear-gradient(to right, #2563eb 0%, #1d4ed8 100%);">
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

<!-- ========================= -->
<!-- MODALES CATALOGOS (7)     -->
<!-- ========================= -->

<style>
    .cat-table { font-size: 0.8rem; }
    .cat-th { padding-top: 0.45rem !important; padding-bottom: 0.45rem !important; }
    .cat-td { padding-top: 0rem !important; padding-bottom: 0rem !important; }

    .money-wrap { position: relative; }
    .money-prefix {
        position: absolute;
        left: 0.75rem;
        top: 50%;
        transform: translateY(-50%);
        font-weight: 800;
        font-size: 0.85rem;
        color: #6b7280;
        pointer-events: none;
    }
    .money-input { padding-left: 2.4rem !important; }

    .field-ico{
        position:absolute;
        left:0.75rem;
        top:50%;
        transform: translateY(-50%);
        color:#9ca3af;
    }

    .action-icon svg{
        width: 1.15rem !important;
        height: 1.15rem !important;
        stroke: currentColor !important;
    }
</style>

<!-- Icono unificado del encabezado ACCIONES -->
<!-- gear icon -->
<!-- Modal: Planes Hosting -->
<div id="modalPlanesHosting" class="fixed inset-0 hidden items-center justify-center p-4 z-[50]">
    <div class="absolute inset-0 modal-backdrop"></div>
    <div class="relative w-full max-w-3xl bg-white shadow-2xl overflow-hidden" style="border:1px solid #1F54DE;">
        <div class="px-5 py-[0.7rem] text-white flex items-center justify-between" style="background: linear-gradient(to right, #2563eb 0%, #1d4ed8 100%);">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="1.8"><path d="M12 1v22"></path><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7H14"></path></svg>
                <div class="text-base font-semibold">Planes Hosting</div>
            </div>
            <button type="button" class="p-2 hover:bg-white/10 transition" data-cat-x="planes_hosting" aria-label="Cerrar">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="1.8"><path d="M18 6L6 18"></path><path d="M6 6l12 12"></path></svg>
            </button>
        </div>

        <div class="p-5 space-y-4">
            <div id="alert_planes_hosting" class="hidden border p-3 text-sm"></div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                <div class="relative">
                    <span class="field-ico">
                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="#9ca3af" stroke-width="1.8">
                            <rect x="3" y="4" width="18" height="6" rx="2"></rect>
                            <rect x="3" y="14" width="18" height="6" rx="2"></rect>
                            <path d="M7 7h.01"></path><path d="M7 17h.01"></path>
                        </svg>
                    </span>
                    <input id="in_planes_hosting_nombre" class="w-full pl-11 pr-3 py-[0.45rem] border border-gray-300 focus:outline-none" style="background:#F9FAFB;" placeholder="Nombre del plan">
                </div>

                <div class="money-wrap">
                    <span class="money-prefix">S/</span>
                    <input id="in_planes_hosting_precio" class="money-input w-full px-3 py-[0.45rem] border border-gray-300 focus:outline-none" style="background:#F9FAFB;" placeholder="0.00" inputmode="decimal">
                </div>

                <button type="button" id="btn_planes_hosting_guardar" class="w-full px-4 py-[0.45rem] text-white font-semibold inline-flex items-center justify-center gap-2"
                        style="background: linear-gradient(to right, #2563eb 0%, #1d4ed8 100%);">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="1.8"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path><path d="M17 21v-8H7v8"></path></svg>
                    Guardar
                </button>
            </div>

            <div class="overflow-x-auto border border-gray-200 cat-table">
                <table class="min-w-full">
                    <thead class="bg-gray-50 text-gray-600">
                        <tr>
                            <th class="text-left px-4 cat-th font-semibold">
                                <div class="inline-flex items-center gap-2">
                                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="#6b7280" stroke-width="1.8">
                                        <rect x="3" y="4" width="18" height="6" rx="2"></rect>
                                        <rect x="3" y="14" width="18" height="6" rx="2"></rect>
                                    </svg>
                                    NOMBRE
                                </div>
                            </th>
                            <th class="text-left px-4 cat-th font-semibold">
                                <div class="inline-flex items-center gap-2">
                                    <span class="font-extrabold text-gray-500">S/</span> PRECIO
                                </div>
                            </th>
                            <th class="text-center px-4 cat-th font-semibold">
                                <div class="inline-flex items-center gap-2 justify-center">
                                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="#6b7280" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M12 1v2"></path><path d="M12 21v2"></path>
                                        <path d="M4.22 4.22l1.42 1.42"></path><path d="M18.36 18.36l1.42 1.42"></path>
                                        <path d="M1 12h2"></path><path d="M21 12h2"></path>
                                        <circle cx="12" cy="12" r="4"></circle>
                                    </svg>
                                    ACCIONES
                                </div>
                            </th>
                        </tr>
                    </thead>
                    <tbody id="tb_planes_hosting" class="divide-y divide-gray-200"></tbody>
                </table>
            </div>

            <div class="flex items-center justify-end">
                <button type="button" class="px-3 py-[0.45rem] border border-blue-200 bg-blue-50 text-blue-700 hover:bg-blue-100 transition inline-flex items-center gap-2" data-cat-x="planes_hosting">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="#1d4ed8" stroke-width="1.8"><path d="M18 6L6 18"></path><path d="M6 6l12 12"></path></svg>
                    Cancelar
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal: Dominios TLD -->
<div id="modalTldDominios" class="fixed inset-0 hidden items-center justify-center p-4 z-[50]">
    <div class="absolute inset-0 modal-backdrop"></div>
    <div class="relative w-full max-w-3xl bg-white shadow-2xl overflow-hidden" style="border:1px solid #1F54DE;">
        <div class="px-5 py-[0.7rem] text-white flex items-center justify-between" style="background: linear-gradient(to right, #2563eb 0%, #1d4ed8 100%);">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="1.8"><circle cx="12" cy="12" r="10"></circle><path d="M2 12h20"></path></svg>
                <div class="text-base font-semibold">Dominios TLD</div>
            </div>
            <button type="button" class="p-2 hover:bg-white/10 transition" data-cat-x="tld_dominios" aria-label="Cerrar">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="1.8"><path d="M18 6L6 18"></path><path d="M6 6l12 12"></path></svg>
            </button>
        </div>

        <div class="p-5 space-y-4">
            <div id="alert_tld_dominios" class="hidden border p-3 text-sm"></div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                <div class="relative">
                    <span class="field-ico">
                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="#9ca3af" stroke-width="1.8">
                            <circle cx="12" cy="12" r="10"></circle>
                            <path d="M2 12h20"></path>
                            <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path>
                        </svg>
                    </span>
                    <input id="in_tld_dominios_nombre" class="w-full pl-11 pr-3 py-[0.45rem] border border-gray-300 focus:outline-none" style="background:#F9FAFB;" placeholder="TLD (ej: .com.pe)">
                </div>

                <div class="money-wrap">
                    <span class="money-prefix">S/</span>
                    <input id="in_tld_dominios_precio" class="money-input w-full px-3 py-[0.45rem] border border-gray-300 focus:outline-none" style="background:#F9FAFB;" placeholder="0.00" inputmode="decimal">
                </div>

                <button type="button" id="btn_tld_dominios_guardar" class="w-full px-4 py-[0.45rem] text-white font-semibold inline-flex items-center justify-center gap-2"
                        style="background: linear-gradient(to right, #2563eb 0%, #1d4ed8 100%);">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="1.8"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path><path d="M17 21v-8H7v8"></path></svg>
                    Guardar
                </button>
            </div>

            <div class="overflow-x-auto border border-gray-200 cat-table">
                <table class="min-w-full">
                    <thead class="bg-gray-50 text-gray-600">
                        <tr>
                            <th class="text-left px-4 cat-th font-semibold">
                                <div class="inline-flex items-center gap-2">
                                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="#6b7280" stroke-width="1.8">
                                        <circle cx="12" cy="12" r="10"></circle>
                                    </svg>
                                    NOMBRE
                                </div>
                            </th>
                            <th class="text-left px-4 cat-th font-semibold">
                                <div class="inline-flex items-center gap-2"><span class="font-extrabold text-gray-500">S/</span> PRECIO</div>
                            </th>
                            <th class="text-center px-4 cat-th font-semibold">
                                <div class="inline-flex items-center gap-2 justify-center">
                                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="#6b7280" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M12 1v2"></path><path d="M12 21v2"></path>
                                        <path d="M4.22 4.22l1.42 1.42"></path><path d="M18.36 18.36l1.42 1.42"></path>
                                        <path d="M1 12h2"></path><path d="M21 12h2"></path>
                                        <circle cx="12" cy="12" r="4"></circle>
                                    </svg>
                                    ACCIONES
                                </div>
                            </th>
                        </tr>
                    </thead>
                    <tbody id="tb_tld_dominios" class="divide-y divide-gray-200"></tbody>
                </table>
            </div>

            <div class="flex items-center justify-end">
                <button type="button" class="px-3 py-[0.45rem] border border-blue-200 bg-blue-50 text-blue-700 hover:bg-blue-100 transition inline-flex items-center gap-2" data-cat-x="tld_dominios">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="#1d4ed8" stroke-width="1.8"><path d="M18 6L6 18"></path><path d="M6 6l12 12"></path></svg>
                    Cancelar
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal: Registrantes -->
<div id="modalRegistrantes" class="fixed inset-0 hidden items-center justify-center p-4 z-[50]">
    <div class="absolute inset-0 modal-backdrop"></div>
    <div class="relative w-full max-w-3xl bg-white shadow-2xl overflow-hidden" style="border:1px solid #1F54DE;">
        <div class="px-5 py-[0.7rem] text-white flex items-center justify-between" style="background: linear-gradient(to right, #2563eb 0%, #1d4ed8 100%);">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="1.8"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                <div class="text-base font-semibold">Dominios Registrante</div>
            </div>
            <button type="button" class="p-2 hover:bg-white/10 transition" data-cat-x="registrantes" aria-label="Cerrar">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="1.8"><path d="M18 6L6 18"></path><path d="M6 6l12 12"></path></svg>
            </button>
        </div>

        <div class="p-5 space-y-4">
            <div id="alert_registrantes" class="hidden border p-3 text-sm"></div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                <div class="relative">
                    <span class="field-ico">
                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="#9ca3af" stroke-width="1.8">
                            <path d="M3 21h18"></path>
                            <path d="M7 21V7l5-3 5 3v14"></path>
                            <path d="M10 10h.01"></path><path d="M14 10h.01"></path>
                        </svg>
                    </span>
                    <input id="in_registrantes_nombre" class="w-full pl-11 pr-3 py-[0.45rem] border border-gray-300 focus:outline-none" style="background:#F9FAFB;" placeholder="Empresa registrante">
                </div>

                <div class="money-wrap">
                    <span class="money-prefix">S/</span>
                    <input id="in_registrantes_precio" class="money-input w-full px-3 py-[0.45rem] border border-gray-300 focus:outline-none" style="background:#F9FAFB;" placeholder="0.00" inputmode="decimal">
                </div>

                <button type="button" id="btn_registrantes_guardar" class="w-full px-4 py-[0.45rem] text-white font-semibold inline-flex items-center justify-center gap-2"
                        style="background: linear-gradient(to right, #2563eb 0%, #1d4ed8 100%);">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="1.8"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path><path d="M17 21v-8H7v8"></path></svg>
                    Guardar
                </button>
            </div>

            <div class="overflow-x-auto border border-gray-200 cat-table">
                <table class="min-w-full">
                    <thead class="bg-gray-50 text-gray-600">
                        <tr>
                            <th class="text-left px-4 cat-th font-semibold">
                                <div class="inline-flex items-center gap-2">
                                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="#6b7280" stroke-width="1.8">
                                        <path d="M3 21h18"></path><path d="M7 21V7l5-3 5 3v14"></path>
                                    </svg>
                                    NOMBRE
                                </div>
                            </th>
                            <th class="text-left px-4 cat-th font-semibold"><div class="inline-flex items-center gap-2"><span class="font-extrabold text-gray-500">S/</span> PRECIO</div></th>
                            <th class="text-center px-4 cat-th font-semibold">
                                <div class="inline-flex items-center gap-2 justify-center">
                                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="#6b7280" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M12 1v2"></path><path d="M12 21v2"></path>
                                        <path d="M4.22 4.22l1.42 1.42"></path><path d="M18.36 18.36l1.42 1.42"></path>
                                        <path d="M1 12h2"></path><path d="M21 12h2"></path>
                                        <circle cx="12" cy="12" r="4"></circle>
                                    </svg>
                                    ACCIONES
                                </div>
                            </th>
                        </tr>
                    </thead>
                    <tbody id="tb_registrantes" class="divide-y divide-gray-200"></tbody>
                </table>
            </div>

            <div class="flex items-center justify-end">
                <button type="button" class="px-3 py-[0.45rem] border border-blue-200 bg-blue-50 text-blue-700 hover:bg-blue-100 transition inline-flex items-center gap-2" data-cat-x="registrantes">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="#1d4ed8" stroke-width="1.8"><path d="M18 6L6 18"></path><path d="M6 6l12 12"></path></svg>
                    Cancelar
                </button>
            </div>
        </div>
    </div>
</div>
<!-- Modal: Tipos Correo -->
<div id="modalTiposCorreo" class="fixed inset-0 hidden items-center justify-center p-4 z-[50]">
    <div class="absolute inset-0 modal-backdrop"></div>
    <div class="relative w-full max-w-3xl bg-white shadow-2xl overflow-hidden" style="border:1px solid #1F54DE;">
        <div class="px-5 py-[0.7rem] text-white flex items-center justify-between"
             style="background: linear-gradient(to right, #2563eb 0%, #1d4ed8 100%);">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M4 4h16a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2z"></path>
                    <path d="m22 6-10 7L2 6"></path>
                </svg>
                <div class="text-base font-semibold">Tipo de Correo</div>
            </div>
            <button type="button" class="p-2 hover:bg-white/10 transition" data-cat-x="tipos_correo" aria-label="Cerrar">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="1.8">
                    <path d="M18 6L6 18"></path><path d="M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <div class="p-5 space-y-4">
            <div id="alert_tipos_correo" class="hidden border p-3 text-sm"></div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                <div class="relative">
                    <span class="field-ico">
                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="#9ca3af" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M4 4h16a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2z"></path>
                            <path d="m22 6-10 7L2 6"></path>
                        </svg>
                    </span>
                    <input id="in_tipos_correo_nombre"
                           class="w-full pl-11 pr-3 py-[0.45rem] border border-gray-300 focus:outline-none"
                           style="background:#F9FAFB;"
                           placeholder="Tipo (Zoho, Gmail...)" />
                </div>

                <div class="money-wrap">
                    <span class="money-prefix">S/</span>
                    <input id="in_tipos_correo_precio"
                           class="money-input w-full px-3 py-[0.45rem] border border-gray-300 focus:outline-none"
                           style="background:#F9FAFB;"
                           placeholder="0.00"
                           inputmode="decimal" />
                </div>

                <button type="button" id="btn_tipos_correo_guardar"
                        class="w-full px-4 py-[0.45rem] text-white font-semibold inline-flex items-center justify-center gap-2"
                        style="background: linear-gradient(to right, #2563eb 0%, #1d4ed8 100%);">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="1.8">
                        <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                        <path d="M17 21v-8H7v8"></path>
                    </svg>
                    Guardar
                </button>
            </div>

            <div class="overflow-x-auto border border-gray-200 cat-table">
                <table class="min-w-full">
                    <thead class="bg-gray-50 text-gray-600">
                        <tr>
                            <th class="text-left px-4 cat-th font-semibold">
                                <div class="inline-flex items-center gap-2">
                                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="#6b7280" stroke-width="1.8">
                                        <path d="M4 4h16a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2z"></path>
                                    </svg>
                                    NOMBRE
                                </div>
                            </th>
                            <th class="text-left px-4 cat-th font-semibold"><div class="inline-flex items-center gap-2"><span class="font-extrabold text-gray-500">S/</span> PRECIO</div></th>
                            <th class="text-center px-4 cat-th font-semibold">
                                <div class="inline-flex items-center gap-2 justify-center">
                                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="#6b7280" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M12 1v2"></path><path d="M12 21v2"></path>
                                        <path d="M4.22 4.22l1.42 1.42"></path><path d="M18.36 18.36l1.42 1.42"></path>
                                        <path d="M1 12h2"></path><path d="M21 12h2"></path>
                                        <circle cx="12" cy="12" r="4"></circle>
                                    </svg>
                                    ACCIONES
                                </div>
                            </th>
                        </tr>
                    </thead>
                    <tbody id="tb_tipos_correo" class="divide-y divide-gray-200"></tbody>
                </table>
            </div>

            <div class="flex items-center justify-end">
                <button type="button"
                        class="px-3 py-[0.45rem] border border-blue-200 bg-blue-50 text-blue-700 hover:bg-blue-100 transition inline-flex items-center gap-2"
                        data-cat-x="tipos_correo">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="#1d4ed8" stroke-width="1.8">
                        <path d="M18 6L6 18"></path><path d="M6 6l12 12"></path>
                    </svg>
                    Cancelar
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal: Diseño Web -->
<div id="modalTiposDisenoWeb" class="fixed inset-0 hidden items-center justify-center p-4 z-[50]">
    <div class="absolute inset-0 modal-backdrop"></div>
    <div class="relative w-full max-w-3xl bg-white shadow-2xl overflow-hidden" style="border:1px solid #1F54DE;">
        <div class="px-5 py-[0.7rem] text-white flex items-center justify-between"
             style="background: linear-gradient(to right, #2563eb 0%, #1d4ed8 100%);">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="4" width="18" height="16" rx="2"></rect>
                    <path d="M3 9h18"></path>
                    <path d="M9 20V9"></path>
                </svg>
                <div class="text-base font-semibold">Diseño Web</div>
            </div>
            <button type="button" class="p-2 hover:bg-white/10 transition" data-cat-x="tipos_diseno_web" aria-label="Cerrar">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="1.8">
                    <path d="M18 6L6 18"></path><path d="M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <div class="p-5 space-y-4">
            <div id="alert_tipos_diseno_web" class="hidden border p-3 text-sm"></div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                <div class="relative">
                    <span class="field-ico">
                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="#9ca3af" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="4" width="18" height="16" rx="2"></rect>
                            <path d="M3 9h18"></path>
                            <path d="M9 20V9"></path>
                        </svg>
                    </span>
                    <input id="in_tipos_diseno_web_nombre"
                           class="w-full pl-11 pr-3 py-[0.45rem] border border-gray-300 focus:outline-none"
                           style="background:#F9FAFB;"
                           placeholder="Tipo (Landing, Tienda...)" />
                </div>

                <div class="money-wrap">
                    <span class="money-prefix">S/</span>
                    <input id="in_tipos_diseno_web_precio"
                           class="money-input w-full px-3 py-[0.45rem] border border-gray-300 focus:outline-none"
                           style="background:#F9FAFB;"
                           placeholder="0.00"
                           inputmode="decimal" />
                </div>

                <button type="button" id="btn_tipos_diseno_web_guardar"
                        class="w-full px-4 py-[0.45rem] text-white font-semibold inline-flex items-center justify-center gap-2"
                        style="background: linear-gradient(to right, #2563eb 0%, #1d4ed8 100%);">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="1.8">
                        <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                        <path d="M17 21v-8H7v8"></path>
                    </svg>
                    Guardar
                </button>
            </div>

            <div class="overflow-x-auto border border-gray-200 cat-table">
                <table class="min-w-full">
                    <thead class="bg-gray-50 text-gray-600">
                        <tr>
                            <th class="text-left px-4 cat-th font-semibold">
                                <div class="inline-flex items-center gap-2">
                                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="#6b7280" stroke-width="1.8">
                                        <rect x="3" y="4" width="18" height="16" rx="2"></rect>
                                    </svg>
                                    NOMBRE
                                </div>
                            </th>
                            <th class="text-left px-4 cat-th font-semibold"><div class="inline-flex items-center gap-2"><span class="font-extrabold text-gray-500">S/</span> PRECIO</div></th>
                            <th class="text-center px-4 cat-th font-semibold">
                                <div class="inline-flex items-center gap-2 justify-center">
                                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="#6b7280" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M12 1v2"></path><path d="M12 21v2"></path>
                                        <path d="M4.22 4.22l1.42 1.42"></path><path d="M18.36 18.36l1.42 1.42"></path>
                                        <path d="M1 12h2"></path><path d="M21 12h2"></path>
                                        <circle cx="12" cy="12" r="4"></circle>
                                    </svg>
                                    ACCIONES
                                </div>
                            </th>
                        </tr>
                    </thead>
                    <tbody id="tb_tipos_diseno_web" class="divide-y divide-gray-200"></tbody>
                </table>
            </div>

            <div class="flex items-center justify-end">
                <button type="button"
                        class="px-3 py-[0.45rem] border border-blue-200 bg-blue-50 text-blue-700 hover:bg-blue-100 transition inline-flex items-center gap-2"
                        data-cat-x="tipos_diseno_web">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="#1d4ed8" stroke-width="1.8">
                        <path d="M18 6L6 18"></path><path d="M6 6l12 12"></path>
                    </svg>
                    Cancelar
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal: Diseño Gráfico -->
<div id="modalTiposDisenoGrafico" class="fixed inset-0 hidden items-center justify-center p-4 z-[50]">
    <div class="absolute inset-0 modal-backdrop"></div>
    <div class="relative w-full max-w-3xl bg-white shadow-2xl overflow-hidden" style="border:1px solid #1F54DE;">
        <div class="px-5 py-[0.7rem] text-white flex items-center justify-between"
             style="background: linear-gradient(to right, #2563eb 0%, #1d4ed8 100%);">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 21a9 9 0 1 1 0-18c5 0 9 3.6 9 8 0 2-1.5 3-3 3h-2a2 2 0 0 0-2 2c0 2-1 5-2 5z"></path>
                    <path d="M7.5 10h.01"></path>
                </svg>
                <div class="text-base font-semibold">Diseño Gráfico</div>
            </div>
            <button type="button" class="p-2 hover:bg-white/10 transition" data-cat-x="tipos_diseno_grafico" aria-label="Cerrar">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="1.8">
                    <path d="M18 6L6 18"></path><path d="M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <div class="p-5 space-y-4">
            <div id="alert_tipos_diseno_grafico" class="hidden border p-3 text-sm"></div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                <div class="relative">
                    <span class="field-ico">
                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="#9ca3af" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 21a9 9 0 1 1 0-18c5 0 9 3.6 9 8 0 2-1.5 3-3 3h-2a2 2 0 0 0-2 2c0 2-1 5-2 5z"></path>
                            <path d="M7.5 10h.01"></path>
                        </svg>
                    </span>
                    <input id="in_tipos_diseno_grafico_nombre"
                           class="w-full pl-11 pr-3 py-[0.45rem] border border-gray-300 focus:outline-none"
                           style="background:#F9FAFB;"
                           placeholder="Tipo (Logo, Video...)" />
                </div>

                <div class="money-wrap">
                    <span class="money-prefix">S/</span>
                    <input id="in_tipos_diseno_grafico_precio"
                           class="money-input w-full px-3 py-[0.45rem] border border-gray-300 focus:outline-none"
                           style="background:#F9FAFB;"
                           placeholder="0.00"
                           inputmode="decimal" />
                </div>

                <button type="button" id="btn_tipos_diseno_grafico_guardar"
                        class="w-full px-4 py-[0.45rem] text-white font-semibold inline-flex items-center justify-center gap-2"
                        style="background: linear-gradient(to right, #2563eb 0%, #1d4ed8 100%);">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="1.8">
                        <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                        <path d="M17 21v-8H7v8"></path>
                    </svg>
                    Guardar
                </button>
            </div>

            <div class="overflow-x-auto border border-gray-200 cat-table">
                <table class="min-w-full">
                    <thead class="bg-gray-50 text-gray-600">
                        <tr>
                            <th class="text-left px-4 cat-th font-semibold">
                                <div class="inline-flex items-center gap-2">
                                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="#6b7280" stroke-width="1.8">
                                        <path d="M12 21a9 9 0 1 1 0-18"></path>
                                    </svg>
                                    NOMBRE
                                </div>
                            </th>
                            <th class="text-left px-4 cat-th font-semibold"><div class="inline-flex items-center gap-2"><span class="font-extrabold text-gray-500">S/</span> PRECIO</div></th>
                            <th class="text-center px-4 cat-th font-semibold">
                                <div class="inline-flex items-center gap-2 justify-center">
                                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="#6b7280" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M12 1v2"></path><path d="M12 21v2"></path>
                                        <path d="M4.22 4.22l1.42 1.42"></path><path d="M18.36 18.36l1.42 1.42"></path>
                                        <path d="M1 12h2"></path><path d="M21 12h2"></path>
                                        <circle cx="12" cy="12" r="4"></circle>
                                    </svg>
                                    ACCIONES
                                </div>
                            </th>
                        </tr>
                    </thead>
                    <tbody id="tb_tipos_diseno_grafico" class="divide-y divide-gray-200"></tbody>
                </table>
            </div>

            <div class="flex items-center justify-end">
                <button type="button"
                        class="px-3 py-[0.45rem] border border-blue-200 bg-blue-50 text-blue-700 hover:bg-blue-100 transition inline-flex items-center gap-2"
                        data-cat-x="tipos_diseno_grafico">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="#1d4ed8" stroke-width="1.8">
                        <path d="M18 6L6 18"></path><path d="M6 6l12 12"></path>
                    </svg>
                    Cancelar
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal: Tipos Otro -->
<div id="modalTiposOtro" class="fixed inset-0 hidden items-center justify-center p-4 z-[50]">
    <div class="absolute inset-0 modal-backdrop"></div>
    <div class="relative w-full max-w-3xl bg-white shadow-2xl overflow-hidden" style="border:1px solid #1F54DE;">
        <div class="px-5 py-[0.7rem] text-white flex items-center justify-between"
             style="background: linear-gradient(to right, #2563eb 0%, #1d4ed8 100%);">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
                    <path d="M12 22V12"></path>
                </svg>
                <div class="text-base font-semibold">Otro</div>
            </div>
            <button type="button" class="p-2 hover:bg-white/10 transition" data-cat-x="tipos_otro" aria-label="Cerrar">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="1.8">
                    <path d="M18 6L6 18"></path><path d="M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <div class="p-5 space-y-4">
            <div id="alert_tipos_otro" class="hidden border p-3 text-sm"></div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                <div class="relative">
                    <span class="field-ico">
                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="#9ca3af" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
                            <path d="M12 22V12"></path>
                        </svg>
                    </span>
                    <input id="in_tipos_otro_nombre"
                           class="w-full pl-11 pr-3 py-[0.45rem] border border-gray-300 focus:outline-none"
                           style="background:#F9FAFB;"
                           placeholder="Tipo (PDF, Carátula...)" />
                </div>

                <div class="money-wrap">
                    <span class="money-prefix">S/</span>
                    <input id="in_tipos_otro_precio"
                           class="money-input w-full px-3 py-[0.45rem] border border-gray-300 focus:outline-none"
                           style="background:#F9FAFB;"
                           placeholder="0.00"
                           inputmode="decimal" />
                </div>

                <button type="button" id="btn_tipos_otro_guardar"
                        class="w-full px-4 py-[0.45rem] text-white font-semibold inline-flex items-center justify-center gap-2"
                        style="background: linear-gradient(to right, #2563eb 0%, #1d4ed8 100%);">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="1.8">
                        <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                        <path d="M17 21v-8H7v8"></path>
                    </svg>
                    Guardar
                </button>
            </div>

            <div class="overflow-x-auto border border-gray-200 cat-table">
                <table class="min-w-full">
                    <thead class="bg-gray-50 text-gray-600">
                        <tr>
                            <th class="text-left px-4 cat-th font-semibold">
                                <div class="inline-flex items-center gap-2">
                                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="#6b7280" stroke-width="1.8">
                                        <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4"></path>
                                    </svg>
                                    NOMBRE
                                </div>
                            </th>
                            <th class="text-left px-4 cat-th font-semibold"><div class="inline-flex items-center gap-2"><span class="font-extrabold text-gray-500">S/</span> PRECIO</div></th>
                            <th class="text-center px-4 cat-th font-semibold">
                                <div class="inline-flex items-center gap-2 justify-center">
                                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="#6b7280" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M12 1v2"></path><path d="M12 21v2"></path>
                                        <path d="M4.22 4.22l1.42 1.42"></path><path d="M18.36 18.36l1.42 1.42"></path>
                                        <path d="M1 12h2"></path><path d="M21 12h2"></path>
                                        <circle cx="12" cy="12" r="4"></circle>
                                    </svg>
                                    ACCIONES
                                </div>
                            </th>
                        </tr>
                    </thead>
                    <tbody id="tb_tipos_otro" class="divide-y divide-gray-200"></tbody>
                </table>
            </div>

            <div class="flex items-center justify-end">
                <button type="button"
                        class="px-3 py-[0.45rem] border border-blue-200 bg-blue-50 text-blue-700 hover:bg-blue-100 transition inline-flex items-center gap-2"
                        data-cat-x="tipos_otro">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="#1d4ed8" stroke-width="1.8">
                        <path d="M18 6L6 18"></path><path d="M6 6l12 12"></path>
                    </svg>
                    Cancelar
                </button>
            </div>
        </div>
    </div>
</div>