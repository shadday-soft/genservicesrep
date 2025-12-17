<script setup lang="ts">
import { ref } from 'vue';
import Input from '@/components/Input.vue';
import RichTextEditor from '@/components/RichTextEditor.vue';
import SignaturePad from '@/components/SignaturePad.vue';
import RadioGroup from '@/components/RadioGroup.vue';
import { Button } from 'primevue';
import RadioButton from 'primevue/radiobutton';
import AppLayout from '@/layouts/AppLayout.vue';
import vueFilePond from 'vue-filepond';
import FilePondPluginImagePreview from 'filepond-plugin-image-preview';
import { BreadcrumbItem, Equipo, Solicitud } from '@/types';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { getErrorMessage, getSuccessMessage } from '@/composables/Toast';
import solicituds from '@/routes/solicituds';

const FilePond = vueFilePond(FilePondPluginImagePreview);

interface Props {
    tablero?: any | null;
    solicitud: Solicitud;
    equipo: Equipo;
    tecnico?: any | null;
}

function assignMatchingKeys(source: { [key: string]: any }, target: { [key: string]: any }) {
    // Only consider target keys that are not functions (avoid copying into form methods)
    const targetKeys = Object.keys(target).filter((k) => typeof (target as any)[k] !== 'function');

    Object.keys(source).forEach((key: string) => {
        if (targetKeys.includes(key)) {
            try {
                const targetVal = (target as any)[key];
                const sourceVal = source[key];

                // If the target expects a Date and source provides a string/number, convert it
                if (targetVal instanceof Date && (typeof sourceVal === 'string' || typeof sourceVal === 'number')) {
                    (target as any)[key] = new Date(sourceVal);
                } else {
                    (target as any)[key] = sourceVal;
                }

                console.log(`Asignando ${key}: ${sourceVal}`);
            } catch (e) {
                // Avoid throwing from this helper; log warning instead
                // eslint-disable-next-line no-console
                console.warn(`No se pudo asignar la propiedad ${key}:`, e);
            }
        }
    });
}

const props = defineProps<Props>();

// Form data basado en la migración de tableros_electricos
const form = useForm({
    solicitud_id: props.solicitud.id,
    // Datos del equipo
    tension_operacion: props.equipo.tablero_tension_operacion || '',
    corriente_nominal: props.equipo.tablero_corriente_nominal || '',
    elemento_maniobra: props.equipo.tablero_elemento_maniobra || '',
    fabricante: props.equipo.tablero_fabricante || '',
    tipo_aplicacion: props.equipo.tablero_tipo_aplicacion || '',
    control_ats: props.equipo.tablero_controlador || '',
    tipo_servicio: 'Mantenimiento',
    horometro: props.equipo.horometro || '',
    // Observaciones iniciales
    observaciones_iniciales: '',
    // Check list
    gabinete: 'N/A',
    puertas: 'N/A',
    cerraduras: 'N/A',
    bisagras: 'N/A',
    limpieza_general: 'N/A',
    pilotos_indicadores: 'N/A',
    selectores: 'N/A',
    reles: 'N/A',
    temporizadores: 'N/A',
    contactores: 'N/A',
    interruptores: 'N/A',
    conexiones_control: 'N/A',
    conexiones_potencia: 'N/A',
    barraje_potencia: 'N/A',
    barraje_neutros: 'N/A',
    barraje_tierras: 'N/A',
    plc: 'N/A',
    ats: 'N/A',
    fuentes_auxiliares_check: 'N/A',
    capacitores: 'N/A',
    analizador_de_red: 'N/A',
    // Fotos estado inicial
    Foto_uno_antes: null as File | null,
    Foto_dos_antes: null as File | null,
    Foto_tres_antes: null as File | null,
    // Actividad realizada
    actividad_realizada: '',
    // Pruebas con el equipo en operación - Tiempos
    segundos_tdes: '',
    segundos_tdne: '',
    segundos_tdtp: '',
    segundos_tden: '',
    segundos_tdec: '',
    // Ajustes
    alto_voltaje: '',
    bajo_voltaje: '',
    alta_frecuencia: '',
    baja_frecuencia: '',
    sobre_carga: '',
    sobre_corriente: '',
    // Temperatura
    cables_potencia: '',
    terminales: '',
    cuepo_contactores: '',
    cuerpo_interruptores: '',
    transformadores: '',
    punto_mas_caliente: '',
    // Observaciones
    observaciones_pruebas: '',
    pruebas_con_carga: '',
    // Voltaje
    l1_n: '',
    l2_n: '',
    l3_n: '',
    // Frecuencia
    hz: '',
    // KW
    l1_kw: '',
    l2_kw: '',
    l3_kw: '',
    avg_kw: '',
    // Corriente
    l1_corriente: '',
    l2_corriente: '',
    l3_corriente: '',
    // Factor P
    pf: '',
    // KVA
    l1_kva: '',
    l2_kva: '',
    l3_kva: '',
    avg_kva: '',
    // Fotos durante
    foto_uno_durante: null as File | null,
    foto_dos_durante: null as File | null,
    foto_tres_durante: null as File | null,
    foto_cuatro_durante: null as File | null,
    foto_cinco_durante: null as File | null,
    foto_seis_durante: null as File | null,
    // Recomendaciones
    recomendaciones: '',
    // Posición de instrumentos
    control: '',
    selector: '',
    fuentes_auxiliares_posicion: '',
    // Fotos después
    foto_uno_despues: null as File | null,
    foto_dos_despues: null as File | null,
    foto_tres_despues: null as File | null,
    // Fechas
    fecha_solicitud: props.solicitud.fecha_programada || null,
    llegada_tecnico: new Date(),
    salida_tecnico: new Date(),
    // Firmas
    nombre_tecnico: '',
    cedula_tecnico: '',
    firma_tecnico: '',
    nombre_cliente: '',
    cedula_cliente: '',
    firma_cliente: '',
    calificacion_servicio: '',
    processing: false,
    errors: {} as Record<string, string>
});

// Precargar datos si existen
if (props.tablero) {
    assignMatchingKeys(props.tablero, form);
}

if (props.tecnico) {
    form.firma_tecnico = props.tecnico.firma || '';
    form.nombre_tecnico = props.tecnico.nombre_completo || '';
    form.cedula_tecnico = props.tecnico.identificacion || '';
}

const tiposServicio = [
    { label: 'Mantenimiento', value: 'Mantenimiento' },
    { label: 'Servicio', value: 'Servicio' },
    { label: 'Inspección', value: 'Inspeccion' },
    { label: 'Soporte', value: 'Soporte' },
    { label: 'Emergencia', value: 'Emergencia' }
];

const checkOptions = ['B', 'R', 'M', 'N/A'];

// File upload handlers
const updateFilesFotoUnoAntes = (files: any) => {
    if (files && files.length > 0) {
        form.Foto_uno_antes = files[0].file;
    }
};

const updateFilesFotoDosAntes = (files: any) => {
    if (files && files.length > 0) {
        form.Foto_dos_antes = files[0].file;
    }
};

const updateFilesFotoTresAntes = (files: any) => {
    if (files && files.length > 0) {
        form.Foto_tres_antes = files[0].file;
    }
};

const updateFilesFotoUnoDurante = (files: any) => {
    if (files && files.length > 0) {
        form.foto_uno_durante = files[0].file;
    }
};

const updateFilesFotoDosDurante = (files: any) => {
    if (files && files.length > 0) {
        form.foto_dos_durante = files[0].file;
    }
};

const updateFilesFotoTresDurante = (files: any) => {
    if (files && files.length > 0) {
        form.foto_tres_durante = files[0].file;
    }
};

const updateFilesFotoCuatroDurante = (files: any) => {
    if (files && files.length > 0) {
        form.foto_cuatro_durante = files[0].file;
    }
};

const updateFilesFotoCincoDurante = (files: any) => {
    if (files && files.length > 0) {
        form.foto_cinco_durante = files[0].file;
    }
};

const updateFilesFotoSeisDurante = (files: any) => {
    if (files && files.length > 0) {
        form.foto_seis_durante = files[0].file;
    }
};

const updateFilesFotoUnoDespues = (files: any) => {
    if (files && files.length > 0) {
        form.foto_uno_despues = files[0].file;
    }
};

const updateFilesFotoDosDespues = (files: any) => {
    if (files && files.length > 0) {
        form.foto_dos_despues = files[0].file;
    }
};

const updateFilesFotoTresDespues = (files: any) => {
    if (files && files.length > 0) {
        form.foto_tres_despues = files[0].file;
    }
};

const handleSubmit = () => {
    const url = props.tablero 
        ? `/tableros-electricos/${props.tablero.id}` 
        : '/tableros-electricos';
    
    form.post(url, {
        preserveState: true,
        onSuccess: () => {
            getSuccessMessage(props.tablero ? 'Informe actualizado con éxito.' : 'Informe generado con éxito.');
        },
        onError: (error) => {
            getErrorMessage('Ocurrió un error al enviar el formulario.');
        }
    });
};

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: "Generar Informe Tablero Eléctrico #" + props.solicitud?.numero_orden,
        href: "/solicituds",
    },
];
</script>

<template>
    <AppLayout class="p-4" :breadcrumbs="breadcrumbs">
        <Head :title="`Generar Informe Tablero Eléctrico #${props.solicitud?.numero_orden}`" />
        
        <!-- Cabecera con información de la solicitud -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6 mb-6">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Información de la Solicitud -->
                <div class="flex flex-col gap-3">
                    <h3 class="text-sm font-bold text-gray-900 dark:text-white uppercase border-b pb-2">
                        Solicitud
                    </h3>
                    <div class="flex flex-col gap-2">
                        <div class="flex justify-between">
                            <span class="text-xs text-gray-600 dark:text-gray-400">Número de Orden:</span>
                            <span class="text-xs font-semibold text-gray-900 dark:text-white">{{ solicitud.numero_orden }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-xs text-gray-600 dark:text-gray-400">Fecha Programada:</span>
                            <span class="text-xs font-semibold text-gray-900 dark:text-white">{{ solicitud.fecha_programada }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-xs text-gray-600 dark:text-gray-400">Estado:</span>
                            <span class="text-xs font-semibold text-gray-900 dark:text-white">{{ solicitud.estado }}</span>
                        </div>
                        <div class="flex justify-between" v-if="solicitud.quien_solicita">
                            <span class="text-xs text-gray-600 dark:text-gray-400">Quien Solicita:</span>
                            <span class="text-xs font-semibold text-gray-900 dark:text-white">{{ solicitud.quien_solicita }}</span>
                        </div>
                    </div>
                </div>

                <!-- Información del Cliente y Sucursal -->
                <div class="flex flex-col gap-3">
                    <h3 class="text-sm font-bold text-gray-900 dark:text-white uppercase border-b pb-2">
                        Cliente / Sucursal
                    </h3>
                    <div class="flex flex-col gap-2">
                        <div class="flex justify-between" v-if="solicitud.client">
                            <span class="text-xs text-gray-600 dark:text-gray-400">Cliente:</span>
                            <span class="text-xs font-semibold text-gray-900 dark:text-white">{{ solicitud.client.enterprise_name }}</span>
                        </div>
                        <div class="flex justify-between" v-if="solicitud.client?.contact_name">
                            <span class="text-xs text-gray-600 dark:text-gray-400">Contacto:</span>
                            <span class="text-xs font-semibold text-gray-900 dark:text-white">{{ solicitud.client.contact_name }}</span>
                        </div>
                        <div class="flex justify-between" v-if="solicitud.sucursal">
                            <span class="text-xs text-gray-600 dark:text-gray-400">Sucursal:</span>
                            <span class="text-xs font-semibold text-gray-900 dark:text-white">{{ solicitud.sucursal.name }}</span>
                        </div>
                        <div class="flex justify-between" v-if="solicitud.ubicacion">
                            <span class="text-xs text-gray-600 dark:text-gray-400">Ubicación:</span>
                            <span class="text-xs font-semibold text-gray-900 dark:text-white">{{ solicitud.ubicacion }}</span>
                        </div>
                    </div>
                </div>

                <!-- Información del Equipo -->
                <div class="flex flex-col gap-3">
                    <h3 class="text-sm font-bold text-gray-900 dark:text-white uppercase border-b pb-2">
                        Equipo (Tablero Eléctrico)
                    </h3>
                    <div class="flex flex-col gap-2">
                        <div class="flex justify-between" v-if="equipo.nombre_equipo">
                            <span class="text-xs text-gray-600 dark:text-gray-400">Nombre:</span>
                            <span class="text-xs font-semibold text-gray-900 dark:text-white">{{ equipo.nombre_equipo }}</span>
                        </div>
                        <div class="flex justify-between" v-if="equipo.tipo_equipo">
                            <span class="text-xs text-gray-600 dark:text-gray-400">Tipo:</span>
                            <span class="text-xs font-semibold text-gray-900 dark:text-white">{{ equipo.tipo_equipo }}</span>
                        </div>
                        <div class="flex justify-between" v-if="equipo.tablero_fabricante">
                            <span class="text-xs text-gray-600 dark:text-gray-400">Fabricante:</span>
                            <span class="text-xs font-semibold text-gray-900 dark:text-white">{{ equipo.tablero_fabricante }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <form @submit.prevent="handleSubmit" class="flex flex-col gap-6">
            <!-- Tipo servicio -->
            <div class="flex flex-col gap-3">
                <label class="text-sm font-semibold text-gray-700 dark:text-gray-300">
                    Tipo servicio
                </label>
                <div class="flex flex-wrap gap-4">
                    <div v-for="tipo in tiposServicio" :key="tipo.value" class="flex items-center gap-2">
                        <RadioButton 
                            v-model="form.tipo_servicio" 
                            :inputId="tipo.value" 
                            :value="tipo.value"
                        />
                        <label :for="tipo.value" class="text-sm cursor-pointer">{{ tipo.label }}</label>
                    </div>
                </div>
                <span v-if="form.errors.tipo_servicio" class="text-xs italic text-red-500">
                    {{ form.errors.tipo_servicio }}
                </span>
            </div>

            <!-- DATOS DEL EQUIPO -->
            <div class="border-t pt-4">
                <h3 class="text-base font-bold text-gray-800 dark:text-gray-200 mb-4">
                    DATOS DEL EQUIPO
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <Input
                        v-model="form.tension_operacion"
                        label="Tensión de Operación"
                        :error="form.errors.tension_operacion"
                    />
                    <Input
                        v-model="form.corriente_nominal"
                        label="Corriente Nominal"
                        :error="form.errors.corriente_nominal"
                    />
                    <Input
                        v-model="form.elemento_maniobra"
                        label="Elemento de Maniobra"
                        :error="form.errors.elemento_maniobra"
                    />
                    <Input
                        v-model="form.fabricante"
                        label="Fabricante"
                        :error="form.errors.fabricante"
                    />
                    <Input
                        v-model="form.tipo_aplicacion"
                        label="Tipo de Aplicación"
                        :error="form.errors.tipo_aplicacion"
                    />
                    <Input
                        v-model="form.control_ats"
                        label="Control ATS"
                        :error="form.errors.control_ats"
                    />
                </div>
            </div>

            <!-- Observaciones iniciales -->
            <div class="border-t pt-4">
                <h3 class="text-base font-bold text-gray-800 dark:text-gray-200 mb-4">
                    OBSERVACIONES INICIALES
                </h3>
                <RichTextEditor 
                    v-model="form.observaciones_iniciales" 
                    :error="form.errors.observaciones_iniciales"
                    placeholder="Describe las observaciones iniciales..."
                />
            </div>

            <!-- CHECK LIST -->
            <div class="border-t pt-4">
                <h3 class="text-base font-bold text-gray-800 dark:text-gray-200 mb-4">
                    CHECK LIST
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <RadioGroup v-model="form.gabinete" label="Gabinete" :options="checkOptions" unique-id="gabinete" :error="form.errors.gabinete" />
                    <RadioGroup v-model="form.puertas" label="Puertas" :options="checkOptions" unique-id="puertas" :error="form.errors.puertas" />
                    <RadioGroup v-model="form.cerraduras" label="Cerraduras" :options="checkOptions" unique-id="cerraduras" :error="form.errors.cerraduras" />
                    <RadioGroup v-model="form.bisagras" label="Bisagras" :options="checkOptions" unique-id="bisagras" :error="form.errors.bisagras" />
                    <RadioGroup v-model="form.limpieza_general" label="Limpieza General" :options="checkOptions" unique-id="limpieza_general" :error="form.errors.limpieza_general" />
                    <RadioGroup v-model="form.pilotos_indicadores" label="Pilotos Indicadores" :options="checkOptions" unique-id="pilotos_indicadores" :error="form.errors.pilotos_indicadores" />
                    <RadioGroup v-model="form.selectores" label="Selectores" :options="checkOptions" unique-id="selectores" :error="form.errors.selectores" />
                    <RadioGroup v-model="form.reles" label="Relés" :options="checkOptions" unique-id="reles" :error="form.errors.reles" />
                    <RadioGroup v-model="form.temporizadores" label="Temporizadores" :options="checkOptions" unique-id="temporizadores" :error="form.errors.temporizadores" />
                    <RadioGroup v-model="form.contactores" label="Contactores" :options="checkOptions" unique-id="contactores" :error="form.errors.contactores" />
                    <RadioGroup v-model="form.interruptores" label="Interruptores" :options="checkOptions" unique-id="interruptores" :error="form.errors.interruptores" />
                    <RadioGroup v-model="form.conexiones_control" label="Conexiones de Control" :options="checkOptions" unique-id="conexiones_control" :error="form.errors.conexiones_control" />
                    <RadioGroup v-model="form.conexiones_potencia" label="Conexiones de Potencia" :options="checkOptions" unique-id="conexiones_potencia" :error="form.errors.conexiones_potencia" />
                    <RadioGroup v-model="form.barraje_potencia" label="Barraje de Potencia" :options="checkOptions" unique-id="barraje_potencia" :error="form.errors.barraje_potencia" />
                    <RadioGroup v-model="form.barraje_neutros" label="Barraje de Neutros" :options="checkOptions" unique-id="barraje_neutros" :error="form.errors.barraje_neutros" />
                    <RadioGroup v-model="form.barraje_tierras" label="Barraje de Tierras" :options="checkOptions" unique-id="barraje_tierras" :error="form.errors.barraje_tierras" />
                    <RadioGroup v-model="form.plc" label="PLC" :options="checkOptions" unique-id="plc" :error="form.errors.plc" />
                    <RadioGroup v-model="form.ats" label="ATS" :options="checkOptions" unique-id="ats" :error="form.errors.ats" />
                    <RadioGroup v-model="form.fuentes_auxiliares_check" label="Fuentes Auxiliares" :options="checkOptions" unique-id="fuentes_auxiliares" :error="form.errors.fuentes_auxiliares_check" />
                    <RadioGroup v-model="form.capacitores" label="Capacitores" :options="checkOptions" unique-id="capacitores" :error="form.errors.capacitores" />
                    <RadioGroup v-model="form.analizador_de_red" label="Analizador de Red" :options="checkOptions" unique-id="analizador_de_red" :error="form.errors.analizador_de_red" />
                </div>
            </div>

            <!-- FOTOS ESTADO INICIAL -->
            <div class="border-t pt-4">
                <h3 class="text-base font-bold text-gray-800 dark:text-gray-200 mb-4">
                    FOTOS ESTADO INICIAL
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="flex flex-col gap-2">
                        <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Foto 1</label>
                        <FilePond
                            @updatefiles="updateFilesFotoUnoAntes"
                            accepted-file-types="image/*"
                            :label-idle="'Arrastra y suelta tu archivo o <span class=\'filepond--label-action\'>Examinar</span>'"
                        />
                    </div>
                    <div class="flex flex-col gap-2">
                        <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Foto 2</label>
                        <FilePond
                            @updatefiles="updateFilesFotoDosAntes"
                            accepted-file-types="image/*"
                            :label-idle="'Arrastra y suelta tu archivo o <span class=\'filepond--label-action\'>Examinar</span>'"
                        />
                    </div>
                    <div class="flex flex-col gap-2">
                        <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Foto 3</label>
                        <FilePond
                            @updatefiles="updateFilesFotoTresAntes"
                            accepted-file-types="image/*"
                            :label-idle="'Arrastra y suelta tu archivo o <span class=\'filepond--label-action\'>Examinar</span>'"
                        />
                    </div>
                </div>
            </div>

            <!-- ACTIVIDAD REALIZADA -->
            <div class="border-t pt-4">
                <h3 class="text-base font-bold text-gray-800 dark:text-gray-200 mb-4">
                    ACTIVIDAD REALIZADA
                </h3>
                <Input
                    v-model="form.actividad_realizada"
                    type="textarea"
                    :textAreaRows="6"
                    :error="form.errors.actividad_realizada"
                    placeholder="Describe la actividad realizada..."
                />
            </div>

            <!-- PRUEBAS CON EL EQUIPO EN OPERACIÓN -->
            <div class="border-t pt-4">
                <h3 class="text-base font-bold text-gray-800 dark:text-gray-200 mb-4">
                    PRUEBAS CON EL EQUIPO EN OPERACIÓN
                </h3>

                <!-- TIEMPOS -->
                <div class="mb-6">
                    <h4 class="text-sm font-bold text-gray-700 dark:text-gray-300 mb-4">TIEMPOS (Segundos)</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                        <Input v-model="form.segundos_tdes" label="TDES" :error="form.errors.segundos_tdes" />
                        <Input v-model="form.segundos_tdne" label="TDNE" :error="form.errors.segundos_tdne" />
                        <Input v-model="form.segundos_tdtp" label="TDTP" :error="form.errors.segundos_tdtp" />
                        <Input v-model="form.segundos_tden" label="TDEN" :error="form.errors.segundos_tden" />
                        <Input v-model="form.segundos_tdec" label="TDEC" :error="form.errors.segundos_tdec" />
                    </div>
                </div>

                <!-- AJUSTES -->
                <div class="mb-6">
                    <h4 class="text-sm font-bold text-gray-700 dark:text-gray-300 mb-4">AJUSTES</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <Input v-model="form.alto_voltaje" label="Alto Voltaje" :error="form.errors.alto_voltaje" />
                        <Input v-model="form.bajo_voltaje" label="Bajo Voltaje" :error="form.errors.bajo_voltaje" />
                        <Input v-model="form.alta_frecuencia" label="Alta Frecuencia" :error="form.errors.alta_frecuencia" />
                        <Input v-model="form.baja_frecuencia" label="Baja Frecuencia" :error="form.errors.baja_frecuencia" />
                        <Input v-model="form.sobre_carga" label="Sobre Carga" :error="form.errors.sobre_carga" />
                        <Input v-model="form.sobre_corriente" label="Sobre Corriente" :error="form.errors.sobre_corriente" />
                    </div>
                </div>

                <!-- TEMPERATURA -->
                <div class="mb-6">
                    <h4 class="text-sm font-bold text-gray-700 dark:text-gray-300 mb-4">TEMPERATURA</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <Input v-model="form.cables_potencia" label="Cables de Potencia" :error="form.errors.cables_potencia" />
                        <Input v-model="form.terminales" label="Terminales" :error="form.errors.terminales" />
                        <Input v-model="form.cuepo_contactores" label="Cuerpo Contactores" :error="form.errors.cuepo_contactores" />
                        <Input v-model="form.cuerpo_interruptores" label="Cuerpo Interruptores" :error="form.errors.cuerpo_interruptores" />
                        <Input v-model="form.transformadores" label="Transformadores" :error="form.errors.transformadores" />
                        <Input v-model="form.punto_mas_caliente" label="Punto Más Caliente" :error="form.errors.punto_mas_caliente" />
                    </div>
                </div>

                <!-- OBSERVACIONES PRUEBAS -->
                <div class="mb-6">
                    <Input
                        v-model="form.observaciones_pruebas"
                        type="textarea"
                        label="Observaciones de Pruebas"
                        :textAreaRows="4"
                        :error="form.errors.observaciones_pruebas"
                    />
                </div>

                <div class="mb-6">
                    <Input
                        v-model="form.pruebas_con_carga"
                        type="textarea"
                        label="Pruebas con Carga"
                        :textAreaRows="4"
                        :error="form.errors.pruebas_con_carga"
                    />
                </div>

                <!-- VOLTAJE -->
                <div class="mb-6">
                    <h4 class="text-sm font-bold text-gray-700 dark:text-gray-300 mb-4">VOLTAJE</h4>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <Input v-model="form.l1_n" label="L1-N" :error="form.errors.l1_n" />
                        <Input v-model="form.l2_n" label="L2-N" :error="form.errors.l2_n" />
                        <Input v-model="form.l3_n" label="L3-N" :error="form.errors.l3_n" />
                    </div>
                </div>

                <!-- FRECUENCIA -->
                <div class="mb-6">
                    <h4 class="text-sm font-bold text-gray-700 dark:text-gray-300 mb-4">FRECUENCIA</h4>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <Input v-model="form.hz" label="HZ" :error="form.errors.hz" />
                    </div>
                </div>

                <!-- KW -->
                <div class="mb-6">
                    <h4 class="text-sm font-bold text-gray-700 dark:text-gray-300 mb-4">KW</h4>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <Input v-model="form.l1_kw" label="L1" :error="form.errors.l1_kw" />
                        <Input v-model="form.l2_kw" label="L2" :error="form.errors.l2_kw" />
                        <Input v-model="form.l3_kw" label="L3" :error="form.errors.l3_kw" />
                        <Input v-model="form.avg_kw" label="AVG" :error="form.errors.avg_kw" />
                    </div>
                </div>

                <!-- CORRIENTE -->
                <div class="mb-6">
                    <h4 class="text-sm font-bold text-gray-700 dark:text-gray-300 mb-4">CORRIENTE</h4>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <Input v-model="form.l1_corriente" label="L1" :error="form.errors.l1_corriente" />
                        <Input v-model="form.l2_corriente" label="L2" :error="form.errors.l2_corriente" />
                        <Input v-model="form.l3_corriente" label="L3" :error="form.errors.l3_corriente" />
                    </div>
                </div>

                <!-- FACTOR P -->
                <div class="mb-6">
                    <h4 class="text-sm font-bold text-gray-700 dark:text-gray-300 mb-4">FACTOR P</h4>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <Input v-model="form.pf" label="PF" :error="form.errors.pf" />
                    </div>
                </div>

                <!-- KVA -->
                <div class="mb-6">
                    <h4 class="text-sm font-bold text-gray-700 dark:text-gray-300 mb-4">KVA</h4>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <Input v-model="form.l1_kva" label="L1" :error="form.errors.l1_kva" />
                        <Input v-model="form.l2_kva" label="L2" :error="form.errors.l2_kva" />
                        <Input v-model="form.l3_kva" label="L3" :error="form.errors.l3_kva" />
                        <Input v-model="form.avg_kva" label="AVG" :error="form.errors.avg_kva" />
                    </div>
                </div>
            </div>

            <!-- FOTOS DURANTE -->
            <div class="border-t pt-4">
                <h3 class="text-base font-bold text-gray-800 dark:text-gray-200 mb-4">
                    FOTOS DURANTE
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <div class="flex flex-col gap-2">
                        <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Foto 1</label>
                        <FilePond @updatefiles="updateFilesFotoUnoDurante" accepted-file-types="image/*" :label-idle="'Arrastra y suelta tu archivo o <span class=\'filepond--label-action\'>Examinar</span>'" />
                    </div>
                    <div class="flex flex-col gap-2">
                        <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Foto 2</label>
                        <FilePond @updatefiles="updateFilesFotoDosDurante" accepted-file-types="image/*" :label-idle="'Arrastra y suelta tu archivo o <span class=\'filepond--label-action\'>Examinar</span>'" />
                    </div>
                    <div class="flex flex-col gap-2">
                        <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Foto 3</label>
                        <FilePond @updatefiles="updateFilesFotoTresDurante" accepted-file-types="image/*" :label-idle="'Arrastra y suelta tu archivo o <span class=\'filepond--label-action\'>Examinar</span>'" />
                    </div>
                    <div class="flex flex-col gap-2">
                        <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Foto 4</label>
                        <FilePond @updatefiles="updateFilesFotoCuatroDurante" accepted-file-types="image/*" :label-idle="'Arrastra y suelta tu archivo o <span class=\'filepond--label-action\'>Examinar</span>'" />
                    </div>
                    <div class="flex flex-col gap-2">
                        <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Foto 5</label>
                        <FilePond @updatefiles="updateFilesFotoCincoDurante" accepted-file-types="image/*" :label-idle="'Arrastra y suelta tu archivo o <span class=\'filepond--label-action\'>Examinar</span>'" />
                    </div>
                    <div class="flex flex-col gap-2">
                        <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Foto 6</label>
                        <FilePond @updatefiles="updateFilesFotoSeisDurante" accepted-file-types="image/*" :label-idle="'Arrastra y suelta tu archivo o <span class=\'filepond--label-action\'>Examinar</span>'" />
                    </div>
                </div>
            </div>

            <!-- RECOMENDACIONES -->
            <div class="border-t pt-4">
                <h3 class="text-base font-bold text-gray-800 dark:text-gray-200 mb-4">
                    RECOMENDACIONES
                </h3>
                <RichTextEditor 
                    v-model="form.recomendaciones" 
                    :error="form.errors.recomendaciones"
                    placeholder="Describe las recomendaciones..."
                />
            </div>

            <!-- POSICIÓN DE INSTRUMENTOS AL CONCLUIR EL SERVICIO -->
            <div class="border-t pt-4">
                <h3 class="text-base font-bold text-gray-800 dark:text-gray-200 mb-4">
                    POSICIÓN DE INSTRUMENTOS AL CONCLUIR EL SERVICIO
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <RadioGroup v-model="form.control" label="Control" :options="['Manual', 'Auto', 'Off']" unique-id="control" :error="form.errors.control" />
                    <RadioGroup v-model="form.selector" label="Selector" :options="['Manual', 'Auto', 'Off']" unique-id="selector" :error="form.errors.selector" />
                    <RadioGroup v-model="form.fuentes_auxiliares_posicion" label="Fuentes Auxiliares" :options="['On', 'Off']" unique-id="fuentes_aux_pos" :error="form.errors.fuentes_auxiliares_posicion" />
                </div>
            </div>

            <!-- FOTOS DESPUÉS -->
            <div class="border-t pt-4">
                <h3 class="text-base font-bold text-gray-800 dark:text-gray-200 mb-4">
                    FOTOS DESPUÉS
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="flex flex-col gap-2">
                        <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Foto 1</label>
                        <FilePond @updatefiles="updateFilesFotoUnoDespues" accepted-file-types="image/*" :label-idle="'Arrastra y suelta tu archivo o <span class=\'filepond--label-action\'>Examinar</span>'" />
                    </div>
                    <div class="flex flex-col gap-2">
                        <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Foto 2</label>
                        <FilePond @updatefiles="updateFilesFotoDosDespues" accepted-file-types="image/*" :label-idle="'Arrastra y suelta tu archivo o <span class=\'filepond--label-action\'>Examinar</span>'" />
                    </div>
                    <div class="flex flex-col gap-2">
                        <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Foto 3</label>
                        <FilePond @updatefiles="updateFilesFotoTresDespues" accepted-file-types="image/*" :label-idle="'Arrastra y suelta tu archivo o <span class=\'filepond--label-action\'>Examinar</span>'" />
                    </div>
                </div>
            </div>

            <!-- LLEGADA Y SALIDA TÉCNICO -->
            <div class="border-t pt-4">
                <h3 class="text-base font-bold text-gray-800 dark:text-gray-200 mb-4">
                    LLEGADA Y SALIDA TÉCNICO
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <Input
                        v-model:datetime="form.llegada_tecnico"
                        type="datetime"
                        label="Llegada Técnico"
                        :error="form.errors.llegada_tecnico"
                    />
                    <Input
                        v-model:datetime="form.salida_tecnico"
                        type="datetime"
                        label="Salida Técnico"
                        :error="form.errors.salida_tecnico"
                    />
                </div>
            </div>

            <!-- CALIFICACIÓN DE SERVICIO -->
            <div class="border-t pt-4">
                <h3 class="text-base font-bold text-gray-800 dark:text-gray-200 mb-4">
                    CALIFICACIÓN DE SERVICIO (Cliente)
                </h3>
                
                <RadioGroup 
                    v-model="form.calificacion_servicio"
                    :options="['Bueno', 'Regular', 'Malo']"
                    unique-id="calificacion"
                    :error="form.errors.calificacion_servicio"
                />
            </div>

            <!-- FIRMAS -->
            <div class="border-t pt-4">
                <h3 class="text-base font-bold text-gray-800 dark:text-gray-200 mb-4">
                    FIRMAS
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Técnico -->
                    <div class="flex flex-col gap-4">
                        <h4 class="text-sm font-bold text-gray-700 dark:text-gray-300">Técnico</h4>
                        <SignaturePad 
                            v-model="form.firma_tecnico" 
                            label="Firma del Técnico"
                            :error="form.errors.firma_tecnico"
                        />
                        <Input
                            v-model="form.nombre_tecnico"
                            label="Nombre del Técnico"
                            :error="form.errors.nombre_tecnico"
                        />
                        <Input
                            v-model="form.cedula_tecnico"
                            label="Cédula del Técnico"
                            :error="form.errors.cedula_tecnico"
                        />
                    </div>

                    <!-- Cliente -->
                    <div class="flex flex-col gap-4">
                        <h4 class="text-sm font-bold text-gray-700 dark:text-gray-300">Cliente</h4>
                        <SignaturePad 
                            v-model="form.firma_cliente" 
                            label="Firma del Cliente"
                            :error="form.errors.firma_cliente"
                        />
                        <Input
                            v-model="form.nombre_cliente"
                            label="Nombre del Cliente"
                            :error="form.errors.nombre_cliente"
                        />
                        <Input
                            v-model="form.cedula_cliente"
                            label="Cédula del Cliente"
                            :error="form.errors.cedula_cliente"
                        />
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex justify-end gap-3 border-t pt-4">
                <Link 
                    :href="solicituds.index()" 
                    class="no-underline">
                    <Button 
                        type="button"
                        label="Cancelar" 
                        severity="secondary" 
                        icon="pi pi-times"
                        :disabled="form.processing"
                    />
                </Link>
               
                <Button 
                    type="submit" 
                    label="Guardar" 
                    icon="pi pi-save" 
                    :loading="form.processing"
                />
            </div>
        </form>
    </AppLayout>
</template>

<style scoped>
:deep(.p-radiobutton) {
    width: 1.25rem;
    height: 1.25rem;
}

:deep(.p-radiobutton .p-radiobutton-box) {
    width: 1.25rem;
    height: 1.25rem;
}
</style>
