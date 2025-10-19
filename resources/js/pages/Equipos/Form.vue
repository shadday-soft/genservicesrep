<script setup lang="ts">
import Input from '@/components/Input.vue';
import EquipoService from '@/Services/EquiposService';
import Sucursal from '@/Services/SucursalsService';
import Client from '@/Services/ClientService';
import axios from 'axios';
import { index as sucursalsIndex } from '@/routes/sucursals';
import { Button } from 'primevue';
import { onMounted, ref, computed } from 'vue';
const emit = defineEmits(['close']);

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
        <form @submit.prevent="equipoService.submit(() => emit('close'))" class="grid grid-cols-4 gap-4">
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
                <Input v-model="form.tablero_tipo" label="Tipo tablero" :error="form.errors.tablero_tipo"></Input>
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
            <div class="col-span-4 grid grid-cols-2 gap-4" v-if="form.tipo_equipo == 'Planta Eléctrica'">
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
                        <Input v-model:numeric="form.filtro_combustible_cantidad" type="number" min="0"
                            label="Cantidad" :error="form.errors.filtro_combustible_cantidad"></Input>
                        <Input v-model="form.filtro_combustible_referencia" label="Referencia"
                            :error="form.errors.filtro_combustible_referencia"></Input>
                    </div>
                </div>

                <!-- Filtro separador -->
                <div class="rounded-lg shadow-md border">
                    <h3 class="font-extrabold py-2 mx-4">Filtro separador</h3>
                    <div class="grid grid-cols-2 gap-2 items-start bg-gray-100 py-1 px-4">
                        <Input v-model:numeric="form.filtro_separador_cantidad" type="number" min="0"
                            label="Cantidad" :error="form.errors.filtro_separador_cantidad"></Input>
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

                <!-- Refrigerante -->
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


            <Input v-model="form.detalles" label="Detalles" type="textarea" class="col-span-4" :error="form.errors.detalles"></Input>
            <div class="mt-6 flex justify-end col-span-4">
                <Button type="submit" label="Guardar" icon="pi pi-save" :loading="equipoService.form.processing">
                </Button>
            </div>
        </form>
    </div>
</template>
