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
        <form @submit.prevent="equipoService.submit(() => emit('close'))" class="grid grid-cols-2 gap-4">
            <!-- Cliente -->
            <div>
                <Input v-model="form.client_id" @select="getSucursalsForClient(form.client_id)" type="select" label="Cliente" :error="form.errors.client_id"
                    option-label="enterprise_name" option-value="id" :options="clientsList"></Input>

            </div>

            <!-- Sucursal -->
            <div>
                <Input v-model="form.sucursal_id" type="select" label="Sucursal" :error="form.errors.sucursal_id"
                    option-label="name" option-value="id" :options="sucursalesList"></Input>

            </div>

            <Input v-model="form.nombre_equipo" label="Nombre del equipo" :error="form.errors.nombre_equipo"></Input>
            <Input v-model="form.tipo_equipo" label="Tipo de equipo" :error="form.errors.tipo_equipo"></Input>
            <Input v-model="form.detalles" label="Detalles" :error="form.errors.detalles"></Input>

            <!-- Campos de Planta Eléctrica (solo si tipo_equipo == 'Planta Eléctrica') -->
            <template v-if="isPlanta">
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

            <!-- Campos de Tablero (solo si tipo_equipo != 'Planta Eléctrica') -->
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

            <!-- Insumos -->
            <Input v-model="form.filtro_aire_cantidad" label="Filtro aire (cantidad)"
                :error="form.errors.filtro_aire_cantidad"></Input>
            <Input v-model="form.filtro_aire_referencia" label="Filtro aire (ref)"
                :error="form.errors.filtro_aire_referencia"></Input>

            <div class="mt-6 flex justify-end col-span-2">
                <Button type="submit" label="Guardar" icon="pi pi-save" :loading="equipoService.form.processing">
                </Button>
            </div>
        </form>
    </div>
</template>
