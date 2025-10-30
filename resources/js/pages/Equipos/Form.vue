<script setup lang="ts">
import Input from '@/components/Input.vue';
import EquipoService from '@/Services/EquiposService';
import Sucursal from '@/Services/SucursalsService';
import Client from '@/Services/ClientService';
import axios from 'axios';
import { index as sucursalsIndex } from '@/routes/sucursals';
import { Button } from 'primevue';
import { onMounted, ref, computed, watch } from 'vue';

const emit = defineEmits(['close']);

const first_mtto = ref(new Date());
const periodicidad = ref('');
const proximasMantenimientos = ref<Date[]>([]);

interface Props {
    equipo: import('@/types').Equipo | null;
}

const props = defineProps<Props>();

const equipoService = new EquipoService(props.equipo);
const sucursalService = new Sucursal(null);
const clientService = new Client(null);

const sucursalesList = ref([] as any[]);
const clientsList = ref([] as any[]);

const loadingSucursales = ref(false);
const loadingClients = ref(false);
const errorSucursales = ref('');
const errorClients = ref('');

const form = equipoService.form;

const isPlanta = computed(() => form.tipo_equipo === 'Planta Eléctrica');

const calcularProximosMantenimientos = () => {
    if (!form.fecha_primer_mantenimiento || !form.periodicidad) {
        proximasMantenimientos.value = [];
        return;
    }
    proximasMantenimientos.value = equipoService.calcularProximasMantenimientos();
    // console.log('📅 Próximas fechas de mantenimiento:', proximasMantenimientos.value);
    equipoService.actualizarFechasMantenimiento();

};


watch([form.fecha_primer_mantenimiento, form.periodicidad], () => {
    calcularProximosMantenimientos();
});

onMounted(async () => {
    // Cargar sucursales y clientes en paralelo
    loadingSucursales.value = true;
    loadingClients.value = true;
    try {
        const [sResp, cResp] = await Promise.all([
            axios.get(sucursalsIndex().url),
            clientService.getClients(),
        ]);

        sucursalesList.value = sResp?.data?.sucursals || [];

        clientsList.value = Array.isArray(cResp) ? cResp : (cResp?.clients || []);
    } catch (error: any) {
        // Distinguish errors
        if (!sucursalesList.value.length) {
            errorSucursales.value = 'No se pudieron cargar las sucursales';
        }
        if (!clientsList.value.length) {
            errorClients.value = 'No se pudieron cargar los clientes';
        }
    } finally {
        loadingSucursales.value = false;
        loadingClients.value = false;
    }
});

const getSucursalsForClient = async (clientId: string) => {
    form.sucursal_id = ''; // Reset selected sucursal
    clientService.getSucursals(clientId).then(sucursals => {
        sucursalesList.value = sucursals;
    });
};

</script>

<template>
    <div>
        <form @submit.prevent="equipoService.submit(() => emit('close'))" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <!-- Cliente -->
            <Input v-model="form.nombre_equipo" label="Nombre del equipo" :error="form.errors.nombre_equipo"></Input>

            <Input v-model="form.client_id" @select="getSucursalsForClient(form.client_id)" type="select"
                label="Cliente" :error="form.errors.client_id" option-label="enterprise_name" option-value="id"
                :options="clientsList"></Input>

            <Input v-model="form.sucursal_id" type="select" label="Sucursal" :error="form.errors.sucursal_id"
                option-label="name" option-value="id" :options="sucursalesList"></Input>


            <Input v-model="form.tipo_equipo" type="select" :options="[
                { label: 'Planta Eléctrica', value: 'Planta Eléctrica' },
                { label: 'Tablero Eléctrico', value: 'Tablero Eléctrico' }
            ]" label="Tipo de equipo" :error="form.errors.tipo_equipo"></Input>

            <template v-if="form.tipo_equipo == 'Planta Eléctrica'">
                <Input v-model="form.potencia" label="Potencia" :error="form.errors.potencia"></Input>
                <Input v-model="form.modelo_equipo" label="Modelo equipo" :error="form.errors.modelo_equipo"></Input>
                <Input v-model="form.modelo_motor" label="Modelo motor" :error="form.errors.modelo_motor"></Input>
                <Input v-model="form.tension_operacion" label="Tensión operación"
                    :error="form.errors.tension_operacion"></Input>
                <Input v-model="form.serie_equipo" label="Serie equipo" :error="form.errors.serie_equipo"></Input>
                <Input v-model="form.serie_motor" label="Serie motor" :error="form.errors.serie_motor"></Input>
                <Input v-model="form.marca_generador" label="Marca generador"
                    :error="form.errors.marca_generador"></Input>
                <Input v-model="form.horometro" label="Horómetro" :error="form.errors.horometro"></Input>
                <Input v-model="form.marca_motor" label="Marca motor" :error="form.errors.marca_motor"></Input>
            </template>

            <template v-else>
                <Input v-model="form.tablero_tipo" label="Tipo tablero" type="select" :options="[
                    { label: 'Transferencia y distribución', value: 'Transferencia y distribución' },
                    { label: 'Otro', value: 'Otro' }
                ]" :error="form.errors.tablero_tipo"></Input>
                <Input v-model="form.tablero_tension_operacion" label="Tensión tablero"
                    :error="form.errors.tablero_tension_operacion"></Input>
                <Input v-model="form.tablero_tipo_aplicacion" label="Tipo aplicación"
                    :error="form.errors.tablero_tipo_aplicacion"></Input>
                <Input v-model="form.tablero_fabricante" label="Fabricante"
                    :error="form.errors.tablero_fabricante"></Input>
                <Input v-model="form.tablero_corriente_nominal" label="Corriente nominal"
                    :error="form.errors.tablero_corriente_nominal"></Input>
                <Input v-model="form.tablero_elemento_maniobra" label="Elemento maniobra"
                    :error="form.errors.tablero_elemento_maniobra"></Input>
                <Input v-model="form.tablero_controlador" label="Controlador"
                    :error="form.errors.tablero_controlador"></Input>
            </template>

            <!-- Insumos agrupados -->
            <div class="col-sapn-1 md:col-span-4 grid grid-cols-1 md:grid-cols-2 gap-4"
                v-if="form.tipo_equipo == 'Planta Eléctrica'">
                <!-- Filtro de aire -->
                <div class="rounded-lg shadow-md border">
                    <h3 class="font-extrabold py-2 mx-4">Filtro de aire</h3>
                    <div class="grid grid-cols-2 gap-2 items-start bg-gray-100 py-1 px-4">
                        <Input v-model:numeric="form.filtro_aire_cantidad" type="number" min="0" label="Cantidad"
                            :error="form.errors.filtro_aire_cantidad"></Input>
                        <Input v-model="form.filtro_aire_referencia" label="Referencia"
                            :error="form.errors.filtro_aire_referencia"></Input>
                    </div>
                </div>

                <!-- Filtro de aceite -->
                <div class="rounded-lg shadow-md border">
                    <h3 class="font-extrabold py-2 mx-4">Filtro de aceite</h3>
                    <div class="grid grid-cols-2 gap-2 items-start bg-gray-100 py-1 px-4">
                        <Input v-model:numeric="form.filtro_aceite_cantidad" type="number" min="0" label="Cantidad"
                            :error="form.errors.filtro_aceite_cantidad"></Input>
                        <Input v-model="form.filtro_aceite_referencia" label="Referencia"
                            :error="form.errors.filtro_aceite_referencia"></Input>
                    </div>
                </div>

                <!-- Filtro combustible -->
                <div class="rounded-lg shadow-md border">
                    <h3 class="font-extrabold py-2 mx-4">Filtro combustible</h3>
                    <div class="grid grid-cols-2 gap-2 items-start bg-gray-100 py-1 px-4">
                        <Input v-model:numeric="form.filtro_combustible_cantidad" type="number" min="0" label="Cantidad"
                            :error="form.errors.filtro_combustible_cantidad"></Input>
                        <Input v-model="form.filtro_combustible_referencia" label="Referencia"
                            :error="form.errors.filtro_combustible_referencia"></Input>
                    </div>
                </div>

                <!-- Filtro separador -->
                <div class="rounded-lg shadow-md border">
                    <h3 class="font-extrabold py-2 mx-4">Filtro separador</h3>
                    <div class="grid grid-cols-2 gap-2 items-start bg-gray-100 py-1 px-4">
                        <Input v-model:numeric="form.filtro_separador_cantidad" type="number" min="0" label="Cantidad"
                            :error="form.errors.filtro_separador_cantidad"></Input>
                        <Input v-model="form.filtro_separador_referencia" label="Referencia"
                            :error="form.errors.filtro_separador_referencia"></Input>
                    </div>
                </div>

                <!-- Filtro agua -->
                <div class="rounded-lg shadow-md border">
                    <h3 class="font-extrabold py-2 mx-4">Filtro agua</h3>
                    <div class="grid grid-cols-2 gap-2 items-start bg-gray-100 py-1 px-4">
                        <Input v-model:numeric="form.filtro_agua_cantidad" type="number" min="0" label="Cantidad"
                            :error="form.errors.filtro_agua_cantidad"></Input>
                        <Input v-model="form.filtro_agua_referencia" label="Referencia"
                            :error="form.errors.filtro_agua_referencia"></Input>
                    </div>
                </div>

                <!-- Filtro aceite 2 -->
                <div class="rounded-lg shadow-md border">
                    <h3 class="font-extrabold py-2 mx-4">Cantidad de aceite</h3>
                    <div class="grid grid-cols-2 gap-2 items-start bg-gray-100 py-1 px-4">
                        <Input v-model:numeric="form.filtro_aceite_2_cantidad" type="number" min="0" label="Cantidad"
                            :error="form.errors.filtro_aceite_2_cantidad"></Input>
                        <Input v-model="form.filtro_aceite_2_referencia" label="Referencia"
                            :error="form.errors.filtro_aceite_2_referencia"></Input>
                    </div>
                </div>

                <div class="rounded-lg shadow-md border">
                    <h3 class="font-extrabold py-2 mx-4">Refrigerante</h3>
                    <div class="grid grid-cols-2 gap-2 items-start bg-gray-100 py-1 px-4">
                        <Input v-model:numeric="form.refrigerante_cantidad" type="number" min="0" label="Cantidad"
                            :error="form.errors.refrigerante_cantidad"></Input>
                        <Input v-model="form.refrigerante_referencia" label="Referencia"
                            :error="form.errors.refrigerante_referencia"></Input>
                    </div>
                </div>
            </div>
           
            <div class="col-span-1 md:col-span-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                <Input v-model="form.periodicidad" label="Periodicidad" type="select" @select="calcularProximosMantenimientos()" :options="[
                    { label: 'Semanal', value: 'Semanal' },
                    { label: 'Mensual', value: 'Mensual' },
                    { label: 'Trimestral', value: 'Trimestral' },
                    { label: 'Semestral', value: 'Semestral' },
                    { label: 'Anual', value: 'Anual' }
                ]"></Input>

                <Input v-model:date="form.fecha_primer_mantenimiento" @select="calcularProximosMantenimientos" label="Fecha del Primer Mantenimiento" type="date"></Input>

                <!-- Mostrar próximas fechas de mantenimiento -->
                <div v-if="proximasMantenimientos.length > 0" class="col-span-1 md:col-span-2">
                    <div class="rounded-lg shadow-md border p-4 bg-blue-50">
                        <h3 class="font-extrabold text-blue-900 mb-3 flex items-center gap-2">
                            <span>📅</span>
                            <span>Próximas Fechas de Mantenimiento ({{ form.periodicidad }})</span>
                            <span class="text-sm font-normal text-gray-600">
                                - {{ proximasMantenimientos.slice(1).length }} programadas
                            </span>
                        </h3>
                        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-3">
                            <div 
                                v-for="(fecha, index) in proximasMantenimientos.slice(1)"
                                :key="index"
                                class="bg-white rounded-md p-3 border border-blue-200 hover:border-blue-400 transition-colors"
                            >
                                <div class="flex flex-col gap-1">
                                    <span class="text-xs text-gray-500 font-medium">
                                        Mantenimiento #{{ index + 1 }}
                                    </span>
                                    <span class="font-bold text-blue-700 text-sm">
                                        {{ new Date(fecha).toLocaleDateString('es-CO', { 
                                            day: '2-digit', 
                                            month: 'short', 
                                            year: 'numeric' 
                                        }) }}
                                    </span>
                                    <span class="text-xs text-gray-600">
                                        {{ new Date(fecha).toLocaleDateString('es-CO', { 
                                            weekday: 'long'
                                        }) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="mt-4 p-3 bg-blue-100 rounded-md">
                            <p class="text-xs text-blue-800 font-medium">
                                ℹ️ Las fechas programadas excluyen automáticamente:
                            </p>
                            <ul class="text-xs text-blue-700 mt-1 ml-4 list-disc">
                                <li>Sábados y domingos</li>
                                <li>Festivos colombianos</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>


            <Input v-model="form.detalles" label="Detalles" type="textarea" class="col-span-1 md:col-span-4"
                :error="form.errors.detalles"></Input>
            <div class="mt-6 flex justify-end col-span-1 md:col-span-4">
                <Button type="submit" label="Guardar" icon="pi pi-save" :loading="equipoService.form.processing">
                </Button>
            </div>
        </form>
    </div>
</template>
