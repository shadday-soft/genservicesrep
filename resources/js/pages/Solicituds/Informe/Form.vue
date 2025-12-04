<script setup lang="ts">
import { ref } from 'vue';
import Input from '@/components/Input.vue';
import RichTextEditor from '@/components/RichTextEditor.vue';
import SignaturePad from '@/components/SignaturePad.vue';
import RadioGroup from '@/components/RadioGroup.vue';
import ImageThumbnail from '@/components/ImageThumbnail.vue';
import { Button } from 'primevue';
import RadioButton from 'primevue/radiobutton';
import AppLayout from '@/layouts/AppLayout.vue';
import vueFilePond from 'vue-filepond';
import FilePondPluginImagePreview from 'filepond-plugin-image-preview';
import { BreadcrumbItem, Equipo, Solicitud } from '@/types';
import { Head, Link, router, useForm } from '@inertiajs/vue3';
import { informe, StoreInforme, updateInforme } from '@/routes';
import { getErrorMessage, getSuccessMessage } from '@/composables/Toast';
import solicituds from '@/routes/solicituds';


const FilePond = vueFilePond(FilePondPluginImagePreview);
// const FilePond = vueFilePond();

const emit = defineEmits(['close']);

interface Props {
    informe?: any | null;
    solicitud: Solicitud;
    equipo: Equipo;
    tecnico?: any | null;
}

function assignMatchingKeys(source: { [key: string]: any }, target: { [key: string]: any }) {
    Object.keys(source).forEach((key: string) => {
        if (key in target) {
            target[key] = source[key];
        }
    });
}



const props = defineProps<Props>();

// Form data
const form = useForm({
    solicitud_id: props.solicitud.id,
    tipo_servicio: 'Mantenimiento',
    observaciones_iniciales: '',
    nivel_aceite: 'N/A',
    nivel_refrigerante: 'N/A',
    nivel_combustible: '',
    capacidad_tanque: '',
    drenado_tanque: 'N/A',
    fugas: '',
    mangueras: 'N/A',
    // Estado inicial - adicionales
    sellos: 'N/A',
    tuberias: 'N/A',
    radiador: 'N/A',
    guardas: 'N/A',
    correas_ventilador: 'N/A',
    correas_alternador: 'N/A',
    amortiguadores: 'N/A',
    precalentador_estado_inicial: 'N/A',
    bateria: 'N/A',
    nivel_electrolito: 'N/A',
    voltaje_bateria: 'N/A',
    estado_cargador: 'N/A',
    voltaje_cargador: '',
    tipo_control: '',
    voltaje_alternador: '',
    conexiones_control: 'N/A',
    conexiones_potencia: 'N/A',
    limpieza_general: 'N/A',
    // Filtros y cantidades (añadidos)
    cantidad_filtro_aire: props.equipo.filtro_aire_cantidad || '',
    referencia_filtro_aire: props.equipo.filtro_aire_referencia || '',
    cantidad_filtro_aceite: props.equipo.filtro_aceite_cantidad || '',
    referencia_filtro_aceite: props.equipo.filtro_aceite_referencia || '',
    cantidad_filtro_combustible: props.equipo.filtro_combustible_cantidad || '',
    referencia_filtro_combustible: props.equipo.filtro_combustible_referencia || '',
    cantidad_filtro_separador: props.equipo.filtro_separador_cantidad || '',
    referencia_filtro_separador: props.equipo.filtro_separador_referencia || '',
    cantidad_filtro_agua: props.equipo.filtro_agua_cantidad || '',
    referencia_filtro_agua: props.equipo.filtro_agua_referencia || '',
    cantidad_cantidad_aceite: '',
    referencia_cantidad_aceite: '',
    // Fotos antes
    foto_uno_antes: null as File | null,
    pie_foto_uno_antes: '',
    foto_dos_antes: null as File | null,
    pie_foto_dos_antes: '',
    foto_tres_antes: null as File | null,
    pie_foto_tres_antes: '',
    // Actividad realizada
    actividad_realizada: '',
    // Pruebas con equipo operando - Motor
    valor_presion_aceite: '',
    cantidad_presion_aceite: '',
    valor_temp_refrigerante: '',
    cantidad_temp_refrigerante: '',
    valor_temp_aceite: '',
    cantidad_temp_aceite: '',
    valor_temp_turbo: '',
    cantidad_temp_turbo: '',
    valor_rpm: '',
    cantidad_rpm: '',
    valor_voltaje_bateria: '',
    cantidad_voltaje_bateria: '',
    // Caída voltaje de batería
    valor_caida_voltaje_bat: '',
    cantidad_caida_voltaje_bat: '',
    // Generador - VAC Fases
    vac_fases_l1_l2: '',
    vac_fases_l2_l3: '',
    vac_fases_l1_l3: '',
    // Generador - Amperios
    amperios_l1: '',
    amperios_l2: '',
    amperios_l3: '',
    // Generador - VAC Fase N
    vac_fase_n_l1n: '',
    vac_fase_n_l2n: '',
    vac_fase_n_l3n: '',
    // Generador - Potencia - HZ - FP
    potencia: props.equipo.potencia || '',
    hz: '',
    fp: '',
    // Protecciones
    baja_presion: '',
    alta_temperatura: '',
    bajo_nivel_refrigerante: '',
    bajo_voltaje_ac: '',
    // Fotos durante
    foto_uno_durante: null as File | null,
    pie_foto_uno_durante: '',
    foto_dos_durante: null as File | null,
    pie_foto_dos_durante: '',
    foto_tres_durante: null as File | null,
    pie_foto_tres_durante: '',
    foto_cuatro_durante: null as File | null,
    pie_foto_cuatro_durante: '',
    foto_cinco_durante: null as File | null,
    pie_foto_cinco_durante: '',
    foto_seis_durante: null as File | null,
    pie_foto_seis_durante: '',
    foto_siete_durante: null as File | null,
    pie_foto_siete_durante: '',
    foto_ocho_durante: null as File | null,
    pie_foto_ocho_durante: '',
    foto_nueve_durante: null as File | null,
    pie_foto_nueve_durante: '',
    // Recomendaciones
    recomendaciones: '',
    // Llegada y salida técnico
    llegada_tecnico: '',
    salida_tecnico: '',
    // Calificación de servicio
    calificacion_servicio: '',
    // Posición de los instrumentos al concluir el servicio
    control: '',
    transferencia: '',
    posicion_cargador: '',
    totalizador: '',
    precalentador_posicion: '',
    // Fotos después
    foto_uno_despues: null as File | null,
    pie_foto_uno_despues: '',
    foto_dos_despues: null as File | null,
    pie_foto_dos_despues: '',
    foto_tres_despues: null as File | null,
    pie_foto_tres_despues: '',
    // Firmas y datos técnico
    firma_tecnico: '',
    nombre_tecnico: '',
    cedula_tecnico: '',
    // Firmas y datos cliente
    firma_cliente: '',
    nombre_cliente: '',
    cedula_cliente: '',
    processing: false,
    errors: {} as Record<string, string>
});

// Precargar datos del informe y técnico si están disponibles
if (props.informe) {
    assignMatchingKeys(props.informe, form);
}

console.log(props.tecnico);
if (props.tecnico) {
    form.firma_tecnico = props.tecnico.firma || '';
    form.nombre_tecnico = props.tecnico.nombre_completo || '';
    form.cedula_tecnico = props.tecnico.identificacion || '';
}

const tiposServicio = [
    { label: 'Mantenimiento', value: 'Mantenimiento' },
    { label: 'Servicio', value: 'Servicio' },
    { label: 'Inspeccion', value: 'Inspeccion' },
    { label: 'Soporte', value: 'Soporte' },
    { label: 'Emergencia', value: 'Emergencia' }
];

const nivelesOptions = ['B', 'R', 'M', 'N/A'];

const updateFilesFotoUno = (files: any) => {
    if (files && files.length > 0) {
        form.foto_uno_antes = files[0].file;
    }
};

const updateFilesFotoDos = (files: any) => {
    if (files && files.length > 0) {
        form.foto_dos_antes = files[0].file;
    }
};

const updateFilesFotoTres = (files: any) => {
    if (files && files.length > 0) {
        form.foto_tres_antes = files[0].file;
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

const updateFilesFotoSieteDurante = (files: any) => {
    if (files && files.length > 0) {
        form.foto_siete_durante = files[0].file;
    }
};

const updateFilesFotoOchoDurante = (files: any) => {
    if (files && files.length > 0) {
        form.foto_ocho_durante = files[0].file;
    }
};

const updateFilesFotoNueveDurante = (files: any) => {
    if (files && files.length > 0) {
        form.foto_nueve_durante = files[0].file;
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
    if (props.informe) {
        form.post(updateInforme.url(props.informe.id), {
            preserveState: true,
            onSuccess: () => {
                getSuccessMessage('Informe actualizado con éxito.');
            },
            onError: (error) => {
                getErrorMessage('Ocurrió un error al enviar el formulario.');
            }
        });
        return;
    }
    form.post(StoreInforme.url(), {

        preserveState: true,
        onSuccess: () => {
            getSuccessMessage('Informe generado con éxito.');
        },
        onError: (error) => {
            getErrorMessage('Ocurrió un error al enviar el formulario.');
        }
    });
    
};

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: "Generar Informe #" + props.solicitud?.numero_orden,
        href: "/solicituds",
    },
];

</script>

<template>
    <AppLayout class="p-4" :breadcrumbs="breadcrumbs">
        <Head :title="`Generar Informe #${props.solicitud?.numero_orden}`" />
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

            <!-- Observaciones iniciales -->
            <div class="flex flex-col gap-2">
                <RichTextEditor 
                    v-model="form.observaciones_iniciales" 
                    label="Observaciones iniciales"
                    :error="form.errors.observaciones_iniciales"
                    placeholder="Describe las observaciones iniciales..."
                />
            </div>

            <div class="border-t pt-4">
                <h3 class="text-base font-bold text-gray-800 dark:text-gray-200 mb-4">
                    FOTOS ANTES
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Foto uno antes -->
                    <div class="flex flex-col gap-2">
                        <div class="flex items-center gap-2">
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                Foto uno antes
                            </label>
                            <ImageThumbnail :image-url="props.informe?.foto_uno_antes" title="Ver foto uno antes" />
                        </div>
                        <FilePond
                            name="foto_uno_antes"
                            :allow-multiple="false"
                            accepted-file-types="image/*"
                            @updatefiles="updateFilesFotoUno"
                            :label-idle="'Arrastra y suelta tu archivo o <span class=\'filepond--label-action\'>Examinar</span>'"
                        />
                        <span v-if="form.errors.foto_uno_antes" class="text-xs italic text-red-500">
                            {{ form.errors.foto_uno_antes }}
                        </span>
                        <Input 
                            v-model="form.pie_foto_uno_antes" 
                            placeholder="Pie de página para esta foto" 
                            :error="form.errors.pie_foto_uno_antes"
                        />
                    </div>

                    <!-- Foto dos antes -->
                    <div class="flex flex-col gap-2">
                        <div class="flex items-center gap-2">
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                Foto dos antes
                            </label>
                            <ImageThumbnail :image-url="props.informe?.foto_dos_antes" title="Ver foto dos antes" />
                        </div>
                        <FilePond
                            name="foto_dos_antes"
                            :allow-multiple="false"
                            accepted-file-types="image/*"
                            @updatefiles="updateFilesFotoDos"
                            :label-idle="'Arrastra y suelta tu archivo o <span class=\'filepond--label-action\'>Examinar</span>'"
                        />
                        <span v-if="form.errors.foto_dos_antes" class="text-xs italic text-red-500">
                            {{ form.errors.foto_dos_antes }}
                        </span>
                        <Input 
                            v-model="form.pie_foto_dos_antes" 
                            placeholder="Pie de página para esta foto" 
                            :error="form.errors.pie_foto_dos_antes"
                        />
                    </div>

                    <!-- Foto tres antes -->
                    <div class="flex flex-col gap-2">
                        <div class="flex items-center gap-2">
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                Foto tres antes
                            </label>
                            <ImageThumbnail :image-url="props.informe?.foto_tres_antes" title="Ver foto tres antes" />
                        </div>
                        <FilePond
                            name="foto_tres_antes"
                            :allow-multiple="false"
                            accepted-file-types="image/*"
                            @updatefiles="updateFilesFotoTres"
                            :label-idle="'Arrastra y suelta tu archivo o <span class=\'filepond--label-action\'>Examinar</span>'"
                        />
                        <span v-if="form.errors.foto_tres_antes" class="text-xs italic text-red-500">
                            {{ form.errors.foto_tres_antes }}
                        </span>
                        <Input 
                            v-model="form.pie_foto_tres_antes" 
                            placeholder="Pie de página para esta foto" 
                            :error="form.errors.pie_foto_tres_antes"
                        />
                    </div>
                </div>
            </div>

            <!-- ESTADO INICIAL Section -->
            <div class="border-t pt-4">
                <h3 class="text-base font-bold text-gray-800 dark:text-gray-200 mb-4">
                    ESTADO INICIAL
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <!-- Nivel aceite -->
                    <RadioGroup 
                        v-model="form.nivel_aceite"
                        label="Nivel aceite"
                        :options="nivelesOptions"
                        unique-id="aceite"
                        :error="form.errors.nivel_aceite"
                    />

                    <!-- Nivel refrigerante -->
                    <RadioGroup 
                        v-model="form.nivel_refrigerante"
                        label="Nivel refrigerante"
                        :options="nivelesOptions"
                        unique-id="refrigerante"
                        :error="form.errors.nivel_refrigerante"
                    />

                    <!-- Nivel combustible -->
                    <div class="flex flex-col gap-2">
                        <label class="text-sm font-medium text-gray-700 dark:text-gray-300">
                            Nivel combustible
                        </label>
                        <Input 
                            v-model="form.nivel_combustible" 
                            placeholder="" 
                            :error="form.errors.nivel_combustible"
                        />
                    </div>

                    <!-- Capacidad tanque -->
                    <div class="flex flex-col gap-2">
                        <label class="text-sm font-medium text-gray-700 dark:text-gray-300">
                            Capacidad tanque
                        </label>
                        <Input 
                            v-model="form.capacidad_tanque" 
                            placeholder="" 
                            :error="form.errors.capacidad_tanque"
                        />
                    </div>

                    <!-- Fugas -->
                    <div class="flex flex-col gap-2">
                        <label class="text-sm font-medium text-gray-700 dark:text-gray-300">
                            Fugas
                        </label>
                        <Input 
                            v-model="form.fugas" 
                            type="textarea"
                            placeholder="Fugas"
                            :textAreaRows="2"
                            :error="form.errors.fugas"
                        />
                    </div>

                    <!-- Mangueras -->
                    <RadioGroup 
                        v-model="form.mangueras"
                        label="Mangueras"
                        :options="nivelesOptions"
                        unique-id="mangueras"
                        :error="form.errors.mangueras"
                    />

                    <RadioGroup 
                        v-model="form.drenado_tanque"
                        label="Drenado del Tanque"
                        :options="nivelesOptions"
                        unique-id="drenado_tanque"
                        :error="form.errors.drenado_tanque"
                    />

                    <!-- Sellos -->
                    <RadioGroup 
                        v-model="form.sellos"
                        label="Sellos"
                        :options="nivelesOptions"
                        unique-id="sellos"
                        :error="form.errors.sellos"
                    />

                    <!-- Tuberías -->
                    <RadioGroup 
                        v-model="form.tuberias"
                        label="Tuberías"
                        :options="nivelesOptions"
                        unique-id="tuberias"
                        :error="form.errors.tuberias"
                    />

                    <!-- Radiador -->
                    <RadioGroup 
                        v-model="form.radiador"
                        label="Radiador"
                        :options="nivelesOptions"
                        unique-id="radiador"
                        :error="form.errors.radiador"
                    />

                    <!-- Guardas -->
                    <RadioGroup 
                        v-model="form.guardas"
                        label="Guardas"
                        :options="nivelesOptions"
                        unique-id="guardas"
                        :error="form.errors.guardas"
                    />

                    <!-- Correas ventilador -->
                    <RadioGroup 
                        v-model="form.correas_ventilador"
                        label="Correas ventilador"
                        :options="nivelesOptions"
                        unique-id="correas_ventilador"
                        :error="form.errors.correas_ventilador"
                    />

                    <!-- Correas alternador -->
                    <RadioGroup 
                        v-model="form.correas_alternador"
                        label="Correas alternador"
                        :options="nivelesOptions"
                        unique-id="correas_alternador"
                        :error="form.errors.correas_alternador"
                    />

                    <!-- Amortiguadores -->
                    <RadioGroup 
                        v-model="form.amortiguadores"
                        label="Amortiguadores"
                        :options="nivelesOptions"
                        unique-id="amortiguadores"
                        :error="form.errors.amortiguadores"
                    />

                    <!-- Precalentador estado inicial -->
                    <RadioGroup 
                        v-model="form.precalentador_estado_inicial"
                        label="Precalentador estado inicial"
                        :options="nivelesOptions"
                        unique-id="precalentador_estado_inicial"
                        :error="form.errors.precalentador_estado_inicial"
                    />

                    <!-- Bateria -->
                    <RadioGroup 
                        v-model="form.bateria"
                        label="Batería"
                        :options="nivelesOptions"
                        unique-id="bateria"
                        :error="form.errors.bateria"
                    />

                    <!-- Nivel electrolito -->
                    <RadioGroup 
                        v-model="form.nivel_electrolito"
                        label="Nivel electrolito"
                        :options="nivelesOptions"
                        unique-id="nivel_electrolito"
                        :error="form.errors.nivel_electrolito"
                    />

                    <!-- Voltaje batería -->
                    <RadioGroup 
                        v-model="form.voltaje_bateria"
                        label="Voltaje batería"
                        :options="nivelesOptions"
                        unique-id="voltaje_bateria"
                        :error="form.errors.voltaje_bateria"
                    />

                    <!-- Estado cargador -->
                    <RadioGroup 
                        v-model="form.estado_cargador"
                        label="Estado cargador"
                        :options="nivelesOptions"
                        unique-id="estado_cargador"
                        :error="form.errors.estado_cargador"
                    />

                    <!-- Voltaje cargador -->
                    <div class="flex flex-col gap-2">
                        <label class="text-sm font-medium text-gray-700 dark:text-gray-300">
                            Voltaje cargador
                        </label>
                        <Input 
                            v-model="form.voltaje_cargador" 
                            placeholder="" 
                            :error="form.errors.voltaje_cargador"
                        />
                    </div>

                    <!-- Tipo control -->
                    <div class="flex flex-col gap-2">
                        <label class="text-sm font-medium text-gray-700 dark:text-gray-300">
                            Tipo control
                        </label>
                        <Input 
                            v-model="form.tipo_control" 
                            placeholder="" 
                            :error="form.errors.tipo_control"
                        />
                    </div>

                    <!-- Voltaje alternador -->
                    <div class="flex flex-col gap-2">
                        <label class="text-sm font-medium text-gray-700 dark:text-gray-300">
                            Voltaje alternador
                        </label>
                        <Input 
                            v-model="form.voltaje_alternador" 
                            placeholder="" 
                            :error="form.errors.voltaje_alternador"
                        />
                    </div>

                    <!-- Conexiones control -->
                    <RadioGroup 
                        v-model="form.conexiones_control"
                        label="Conexiones control"
                        :options="nivelesOptions"
                        unique-id="conexiones_control"
                        :error="form.errors.conexiones_control"
                    />

                    <!-- Conexiones potencia -->
                    <RadioGroup 
                        v-model="form.conexiones_potencia"
                        label="Conexiones potencia"
                        :options="nivelesOptions"
                        unique-id="conexiones_potencia"
                        :error="form.errors.conexiones_potencia"
                    />

                    <!-- Limpieza General -->
                    <RadioGroup 
                        v-model="form.limpieza_general"
                        label="Limpieza General"
                        :options="nivelesOptions"
                        unique-id="limpieza_general"
                        :error="form.errors.limpieza_general"
                    />
                </div>

                <!-- REPUESTOS / CANTIDADES (después de Limpieza General) -->
                <div class="mt-4">
                    <h4 class="text-sm font-bold text-gray-700 dark:text-gray-300 mb-4">Repuestos y cantidades</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <!-- FILTRO DE AIRE -->
                        <div class="p-3 border rounded bg-white dark:bg-gray-800">
                            <h5 class="font-semibold text-sm mb-2">FILTRO DE AIRE</h5>
                            <div class="grid grid-cols-2 gap-2">
                                <div>
                                    <label class="text-xs text-gray-600 dark:text-gray-400">Cantidad</label>
                                    <Input v-model="form.cantidad_filtro_aire" placeholder="" :error="form.errors.cantidad_filtro_aire" />
                                </div>
                                <div>
                                    <label class="text-xs text-gray-600 dark:text-gray-400">Referencia</label>
                                    <Input v-model="form.referencia_filtro_aire" placeholder="" :error="form.errors.referencia_filtro_aire" />
                                </div>
                            </div>
                        </div>

                        <!-- FILTRO DE ACEITE -->
                        <div class="p-3 border rounded bg-white dark:bg-gray-800">
                            <h5 class="font-semibold text-sm mb-2">FILTRO DE ACEITE</h5>
                            <div class="grid grid-cols-2 gap-2">
                                <div>
                                    <label class="text-xs text-gray-600 dark:text-gray-400">Cantidad</label>
                                    <Input v-model="form.cantidad_filtro_aceite" placeholder="" :error="form.errors.cantidad_filtro_aceite" />
                                </div>
                                <div>
                                    <label class="text-xs text-gray-600 dark:text-gray-400">Referencia</label>
                                    <Input v-model="form.referencia_filtro_aceite" placeholder="" :error="form.errors.referencia_filtro_aceite" />
                                </div>
                            </div>
                        </div>

                        <!-- FILTRO DE COMBUSTIBLE -->
                        <div class="p-3 border rounded bg-white dark:bg-gray-800">
                            <h5 class="font-semibold text-sm mb-2">FILTRO DE COMBUSTIBLE</h5>
                            <div class="grid grid-cols-2 gap-2">
                                <div>
                                    <label class="text-xs text-gray-600 dark:text-gray-400">Cantidad</label>
                                    <Input v-model="form.cantidad_filtro_combustible" placeholder="" :error="form.errors.cantidad_filtro_combustible" />
                                </div>
                                <div>
                                    <label class="text-xs text-gray-600 dark:text-gray-400">Referencia</label>
                                    <Input v-model="form.referencia_filtro_combustible" placeholder="" :error="form.errors.referencia_filtro_combustible" />
                                </div>
                            </div>
                        </div>

                        <!-- FILTRO SEPARADOR -->
                        <div class="p-3 border rounded bg-white dark:bg-gray-800">
                            <h5 class="font-semibold text-sm mb-2">FILTRO SEPARADOR</h5>
                            <div class="grid grid-cols-2 gap-2">
                                <div>
                                    <label class="text-xs text-gray-600 dark:text-gray-400">Cantidad</label>
                                    <Input v-model="form.cantidad_filtro_separador" placeholder="" :error="form.errors.cantidad_filtro_separador" />
                                </div>
                                <div>
                                    <label class="text-xs text-gray-600 dark:text-gray-400">Referencia</label>
                                    <Input v-model="form.referencia_filtro_separador" placeholder="" :error="form.errors.referencia_filtro_separador" />
                                </div>
                            </div>
                        </div>

                        <!-- FILTRO DE AGUA -->
                        <div class="p-3 border rounded bg-white dark:bg-gray-800">
                            <h5 class="font-semibold text-sm mb-2">FILTRO DE AGUA</h5>
                            <div class="grid grid-cols-2 gap-2">
                                <div>
                                    <label class="text-xs text-gray-600 dark:text-gray-400">Cantidad</label>
                                    <Input v-model="form.cantidad_filtro_agua" placeholder="" :error="form.errors.cantidad_filtro_agua" />
                                </div>
                                <div>
                                    <label class="text-xs text-gray-600 dark:text-gray-400">Referencia</label>
                                    <Input v-model="form.referencia_filtro_agua" placeholder="" :error="form.errors.referencia_filtro_agua" />
                                </div>
                            </div>
                        </div>

                        <!-- CANTIDAD DE ACEITE -->
                        <div class="p-3 border rounded bg-white dark:bg-gray-800">
                            <h5 class="font-semibold text-sm mb-2">CANTIDAD DE ACEITE</h5>
                            <div class="grid grid-cols-2 gap-2">
                                <div>
                                    <label class="text-xs text-gray-600 dark:text-gray-400">Cantidad</label>
                                    <Input v-model="form.cantidad_cantidad_aceite" placeholder="" :error="form.errors.cantidad_cantidad_aceite" />
                                </div>
                                <div>
                                    <label class="text-xs text-gray-600 dark:text-gray-400">Referencia</label>
                                    <Input v-model="form.referencia_cantidad_aceite" placeholder="" :error="form.errors.referencia_cantidad_aceite" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- FOTOS ANTES Section -->
            

            <!-- ACTIVIDAD REALIZADA Section -->
            <div class="border-t pt-4">
                <h3 class="text-base font-bold text-gray-800 dark:text-gray-200 mb-4">
                    ACTIVIDAD REALIZADA
                </h3>
                
                <Input
                    v-model="form.actividad_realizada"
                    type="textarea"
                    label="Actividad realizada"
                    :textAreaRows="6"
                    :maxLength="500"
                    :error="form.errors.actividad_realizada"
                    placeholder="Describe la actividad realizada..."
                />
            </div>

            <!-- FOTOS DURANTE Section -->
            <div class="border-t pt-4">
                <h3 class="text-base font-bold text-gray-800 dark:text-gray-200 mb-4">
                    FOTOS DURANTE
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <!-- Foto uno durante -->
                    <div class="flex flex-col gap-2">
                        <div class="flex items-center gap-2">
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                Foto uno durante
                            </label>
                            <ImageThumbnail :image-url="props.informe?.foto_uno_durante" title="Ver foto uno durante" />
                        </div>
                        <FilePond
                            name="foto_uno_durante"
                            :allow-multiple="false"
                            accepted-file-types="image/*"
                            @updatefiles="updateFilesFotoUnoDurante"
                            :label-idle="'Arrastra y suelta tu archivo o <span class=\'filepond--label-action\'>Examinar</span>'"
                        />
                        <span v-if="form.errors.foto_uno_durante" class="text-xs italic text-red-500">
                            {{ form.errors.foto_uno_durante }}
                        </span>
                        <Input 
                            v-model="form.pie_foto_uno_durante" 
                            placeholder="Pie de página para esta foto" 
                            :error="form.errors.pie_foto_uno_durante"
                        />
                    </div>

                    <!-- Foto dos durante -->
                    <div class="flex flex-col gap-2">
                        <div class="flex items-center gap-2">
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                Foto dos durante
                            </label>
                            <ImageThumbnail :image-url="props.informe?.foto_dos_durante" title="Ver foto dos durante" />
                        </div>
                        <FilePond
                            name="foto_dos_durante"
                            :allow-multiple="false"
                            accepted-file-types="image/*"
                            @updatefiles="updateFilesFotoDosDurante"
                            :label-idle="'Arrastra y suelta tu archivo o <span class=\'filepond--label-action\'>Examinar</span>'"
                        />
                        <span v-if="form.errors.foto_dos_durante" class="text-xs italic text-red-500">
                            {{ form.errors.foto_dos_durante }}
                        </span>
                        <Input 
                            v-model="form.pie_foto_dos_durante" 
                            placeholder="Pie de página para esta foto" 
                            :error="form.errors.pie_foto_dos_durante"
                        />
                    </div>

                    <!-- Foto tres durante -->
                    <div class="flex flex-col gap-2">
                        <div class="flex items-center gap-2">
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                Foto tres durante
                            </label>
                            <ImageThumbnail :image-url="props.informe?.foto_tres_durante" title="Ver foto tres durante" />
                        </div>
                        <FilePond
                            name="foto_tres_durante"
                            :allow-multiple="false"
                            accepted-file-types="image/*"
                            @updatefiles="updateFilesFotoTresDurante"
                            :label-idle="'Arrastra y suelta tu archivo o <span class=\'filepond--label-action\'>Examinar</span>'"
                        />
                        <span v-if="form.errors.foto_tres_durante" class="text-xs italic text-red-500">
                            {{ form.errors.foto_tres_durante }}
                        </span>
                        <Input 
                            v-model="form.pie_foto_tres_durante" 
                            placeholder="Pie de página para esta foto" 
                            :error="form.errors.pie_foto_tres_durante"
                        />
                    </div>

                    <!-- Foto cuatro durante -->
                    <div class="flex flex-col gap-2">
                        <div class="flex items-center gap-2">
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                Foto cuatro durante
                            </label>
                            <ImageThumbnail :image-url="props.informe?.foto_cuatro_durante" title="Ver foto cuatro durante" />
                        </div>
                        <FilePond
                            name="foto_cuatro_durante"
                            :allow-multiple="false"
                            accepted-file-types="image/*"
                            @updatefiles="updateFilesFotoCuatroDurante"
                            :label-idle="'Arrastra y suelta tu archivo o <span class=\'filepond--label-action\'>Examinar</span>'"
                        />
                        <span v-if="form.errors.foto_cuatro_durante" class="text-xs italic text-red-500">
                            {{ form.errors.foto_cuatro_durante }}
                        </span>
                        <Input 
                            v-model="form.pie_foto_cuatro_durante" 
                            placeholder="Pie de página para esta foto" 
                            :error="form.errors.pie_foto_cuatro_durante"
                        />
                    </div>

                    <!-- Foto cinco durante -->
                    <div class="flex flex-col gap-2">
                        <div class="flex items-center gap-2">
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                Foto cinco durante
                            </label>
                            <ImageThumbnail :image-url="props.informe?.foto_cinco_durante" title="Ver foto cinco durante" />
                        </div>
                        <FilePond
                            name="foto_cinco_durante"
                            :allow-multiple="false"
                            accepted-file-types="image/*"
                            @updatefiles="updateFilesFotoCincoDurante"
                            :label-idle="'Arrastra y suelta tu archivo o <span class=\'filepond--label-action\'>Examinar</span>'"
                        />
                        <span v-if="form.errors.foto_cinco_durante" class="text-xs italic text-red-500">
                            {{ form.errors.foto_cinco_durante }}
                        </span>
                        <Input 
                            v-model="form.pie_foto_cinco_durante" 
                            placeholder="Pie de página para esta foto" 
                            :error="form.errors.pie_foto_cinco_durante"
                        />
                    </div>

                    <!-- Foto seis durante -->
                    <div class="flex flex-col gap-2">
                        <div class="flex items-center gap-2">
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                Foto seis durante
                            </label>
                            <ImageThumbnail :image-url="props.informe?.foto_seis_durante" title="Ver foto seis durante" />
                        </div>
                        <FilePond
                            name="foto_seis_durante"
                            :allow-multiple="false"
                            accepted-file-types="image/*"
                            @updatefiles="updateFilesFotoSeisDurante"
                            :label-idle="'Arrastra y suelta tu archivo o <span class=\'filepond--label-action\'>Examinar</span>'"
                        />
                        <span v-if="form.errors.foto_seis_durante" class="text-xs italic text-red-500">
                            {{ form.errors.foto_seis_durante }}
                        </span>
                        <Input 
                            v-model="form.pie_foto_seis_durante" 
                            placeholder="Pie de página para esta foto" 
                            :error="form.errors.pie_foto_seis_durante"
                        />
                    </div>

                    <!-- Foto siete durante -->
                    <div class="flex flex-col gap-2">
                        <div class="flex items-center gap-2">
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                Foto siete durante
                            </label>
                            <ImageThumbnail :image-url="props.informe?.foto_siete_durante" title="Ver foto siete durante" />
                        </div>
                        <FilePond
                            name="foto_siete_durante"
                            :allow-multiple="false"
                            accepted-file-types="image/*"
                            @updatefiles="updateFilesFotoSieteDurante"
                            :label-idle="'Arrastra y suelta tu archivo o <span class=\'filepond--label-action\'>Examinar</span>'"
                        />
                        <span v-if="form.errors.foto_siete_durante" class="text-xs italic text-red-500">
                            {{ form.errors.foto_siete_durante }}
                        </span>
                        <Input 
                            v-model="form.pie_foto_siete_durante" 
                            placeholder="Pie de página para esta foto" 
                            :error="form.errors.pie_foto_siete_durante"
                        />
                    </div>

                    <!-- Foto ocho durante -->
                    <div class="flex flex-col gap-2">
                        <div class="flex items-center gap-2">
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                Foto ocho durante
                            </label>
                            <ImageThumbnail :image-url="props.informe?.foto_ocho_durante" title="Ver foto ocho durante" />
                        </div>
                        <FilePond
                            name="foto_ocho_durante"
                            :allow-multiple="false"
                            accepted-file-types="image/*"
                            @updatefiles="updateFilesFotoOchoDurante"
                            :label-idle="'Arrastra y suelta tu archivo o <span class=\'filepond--label-action\'>Examinar</span>'"
                        />
                        <span v-if="form.errors.foto_ocho_durante" class="text-xs italic text-red-500">
                            {{ form.errors.foto_ocho_durante }}
                        </span>
                        <Input 
                            v-model="form.pie_foto_ocho_durante" 
                            placeholder="Pie de página para esta foto" 
                            :error="form.errors.pie_foto_ocho_durante"
                        />
                    </div>

                    <!-- Foto nueve durante -->
                    <div class="flex flex-col gap-2">
                        <div class="flex items-center gap-2">
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                Foto nueve durante
                            </label>
                            <ImageThumbnail :image-url="props.informe?.foto_nueve_durante" title="Ver foto nueve durante" />
                        </div>
                        <FilePond
                            name="foto_nueve_durante"
                            :allow-multiple="false"
                            accepted-file-types="image/*"
                            @updatefiles="updateFilesFotoNueveDurante"
                            :label-idle="'Arrastra y suelta tu archivo o <span class=\'filepond--label-action\'>Examinar</span>'"
                        />
                        <span v-if="form.errors.foto_nueve_durante" class="text-xs italic text-red-500">
                            {{ form.errors.foto_nueve_durante }}
                        </span>
                        <Input 
                            v-model="form.pie_foto_nueve_durante" 
                            placeholder="Pie de página para esta foto" 
                            :error="form.errors.pie_foto_nueve_durante"
                        />
                    </div>
                </div>
            </div>

            <!-- PRUEBAS CON EL EQUIPO OPERANDO Section -->
            <div class="border-t pt-4">
                <h3 class="text-base font-bold text-gray-800 dark:text-gray-200 mb-4">
                    PRUEBAS CON EL EQUIPO OPERANDO
                </h3>

                <!-- MOTOR Subsection -->
                <div class="mb-6">
                    <h4 class="text-sm font-bold text-gray-700 dark:text-gray-300 mb-4 uppercase">
                        Motor
                    </h4>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <!-- Presión aceite -->
                        <div class="flex flex-col gap-2">
                            <label class="text-sm font-semibold text-gray-700 dark:text-gray-300 uppercase">
                                Presión aceite
                            </label>
                            <div class="grid grid-cols-2 gap-2">
                                <div>
                                    <label class="text-xs text-gray-600 dark:text-gray-400">Valor</label>
                                    <Input 
                                        v-model="form.valor_presion_aceite" 
                                        placeholder="" 
                                        :error="form.errors.valor_presion_aceite"
                                    />
                                </div>
                                <div>
                                    <label class="text-xs text-gray-600 dark:text-gray-400">Unidad</label>
                                    <Input 
                                        v-model="form.cantidad_presion_aceite" 
                                        placeholder="" 
                                        :error="form.errors.cantidad_presion_aceite"
                                    />
                                </div>
                            </div>
                        </div>

                        <!-- Temp de refrigerante -->
                        <div class="flex flex-col gap-2">
                            <label class="text-sm font-semibold text-gray-700 dark:text-gray-300 uppercase">
                                Temp de refrigerante
                            </label>
                            <div class="grid grid-cols-2 gap-2">
                                <div>
                                    <label class="text-xs text-gray-600 dark:text-gray-400">Valor</label>
                                    <Input 
                                        v-model="form.valor_temp_refrigerante" 
                                        placeholder="" 
                                        :error="form.errors.valor_temp_refrigerante"
                                    />
                                </div>
                                <div>
                                    <label class="text-xs text-gray-600 dark:text-gray-400">Unidad (Galones)</label>
                                    <Input 
                                        v-model="form.cantidad_temp_refrigerante" 
                                        placeholder="" 
                                        :error="form.errors.cantidad_temp_refrigerante"
                                    />
                                </div>
                            </div>
                        </div>

                        <!-- Temp de aceite -->
                        <div class="flex flex-col gap-2">
                            <label class="text-sm font-semibold text-gray-700 dark:text-gray-300 uppercase">
                                Temp de aceite
                            </label>
                            <div class="grid grid-cols-2 gap-2">
                                <div>
                                    <label class="text-xs text-gray-600 dark:text-gray-400">Valor</label>
                                    <Input 
                                        v-model="form.valor_temp_aceite" 
                                        placeholder="" 
                                        :error="form.errors.valor_temp_aceite"
                                    />
                                </div>
                                <div>
                                    <label class="text-xs text-gray-600 dark:text-gray-400">Unidad (Galones)</label>
                                    <Input 
                                        v-model="form.cantidad_temp_aceite" 
                                        placeholder="" 
                                        :error="form.errors.cantidad_temp_aceite"
                                    />
                                </div>
                            </div>
                        </div>

                        <!-- Temp del turbo -->
                        <div class="flex flex-col gap-2">
                            <label class="text-sm font-semibold text-gray-700 dark:text-gray-300 uppercase">
                                Temp del turbo
                            </label>
                            <div class="grid grid-cols-2 gap-2">
                                <div>
                                    <label class="text-xs text-gray-600 dark:text-gray-400">Valor</label>
                                    <Input 
                                        v-model="form.valor_temp_turbo" 
                                        placeholder="" 
                                        :error="form.errors.valor_temp_turbo"
                                    />
                                </div>
                                <div>
                                    <label class="text-xs text-gray-600 dark:text-gray-400">Unidad</label>
                                    <Input 
                                        v-model="form.cantidad_temp_turbo" 
                                        placeholder="" 
                                        :error="form.errors.cantidad_temp_turbo"
                                    />
                                </div>
                            </div>
                        </div>

                        <!-- RPM -->
                        <div class="flex flex-col gap-2">
                            <label class="text-sm font-semibold text-gray-700 dark:text-gray-300 uppercase">
                                RPM
                            </label>
                            <div class="grid grid-cols-2 gap-2">
                                <div>
                                    <label class="text-xs text-gray-600 dark:text-gray-400">Valor</label>
                                    <Input 
                                        v-model="form.valor_rpm" 
                                        placeholder="" 
                                        :error="form.errors.valor_rpm"
                                    />
                                </div>
                                <div>
                                    <label class="text-xs text-gray-600 dark:text-gray-400">Unidad</label>
                                    <Input 
                                        v-model="form.cantidad_rpm" 
                                        placeholder="" 
                                        :error="form.errors.cantidad_rpm"
                                    />
                                </div>
                            </div>
                        </div>

                        <!-- Voltaje de bateria -->
                        <div class="flex flex-col gap-2">
                            <label class="text-sm font-semibold text-gray-700 dark:text-gray-300 uppercase">
                                Voltaje de bateria
                            </label>
                            <div class="grid grid-cols-2 gap-2">
                                <div>
                                    <label class="text-xs text-gray-600 dark:text-gray-400">Valor</label>
                                    <Input 
                                        v-model="form.valor_voltaje_bateria" 
                                        placeholder="" 
                                        :error="form.errors.valor_voltaje_bateria"
                                    />
                                </div>
                                <div>
                                    <label class="text-xs text-gray-600 dark:text-gray-400">Unidad</label>
                                    <Input 
                                        v-model="form.cantidad_voltaje_bateria" 
                                        placeholder="" 
                                        :error="form.errors.cantidad_voltaje_bateria"
                                    />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- CAÍDA VOLTAJE DE BAT -->
                <div class="mb-6">
                    <h4 class="text-sm font-bold text-gray-700 dark:text-gray-300 mb-4 uppercase">
                        Caída Voltaje de Bat
                    </h4>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="text-xs text-gray-600 dark:text-gray-400">Valor</label>
                            <Input 
                                v-model="form.valor_caida_voltaje_bat" 
                                placeholder="" 
                                :error="form.errors.valor_caida_voltaje_bat"
                            />
                        </div>
                        <div>
                            <label class="text-xs text-gray-600 dark:text-gray-400">Unidad</label>
                            <Input 
                                v-model="form.cantidad_caida_voltaje_bat" 
                                placeholder="" 
                                :error="form.errors.cantidad_caida_voltaje_bat"
                            />
                        </div>
                    </div>
                </div>

                <!-- GENERADOR -->
                <div class="mb-6">
                    <h4 class="text-sm font-bold text-gray-700 dark:text-gray-300 mb-4 uppercase">
                        Generador
                    </h4>

                    <!-- VAC FASES -->
                    <div class="mb-4">
                        <label class="text-sm font-semibold text-gray-700 dark:text-gray-300 uppercase mb-3 block">
                            VAC Fases
                        </label>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="text-xs text-gray-600 dark:text-gray-400">L1 L2</label>
                                <Input 
                                    v-model="form.vac_fases_l1_l2" 
                                    placeholder="" 
                                    :error="form.errors.vac_fases_l1_l2"
                                />
                            </div>
                            <div>
                                <label class="text-xs text-gray-600 dark:text-gray-400">L2 L3</label>
                                <Input 
                                    v-model="form.vac_fases_l2_l3" 
                                    placeholder="" 
                                    :error="form.errors.vac_fases_l2_l3"
                                />
                            </div>
                            <div>
                                <label class="text-xs text-gray-600 dark:text-gray-400">L1 L3</label>
                                <Input 
                                    v-model="form.vac_fases_l1_l3" 
                                    placeholder="" 
                                    :error="form.errors.vac_fases_l1_l3"
                                />
                            </div>
                        </div>
                    </div>

                    <!-- AMPERIOS -->
                    <div class="mb-4">
                        <label class="text-sm font-semibold text-gray-700 dark:text-gray-300 uppercase mb-3 block">
                            Amperios
                        </label>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="text-xs text-gray-600 dark:text-gray-400">L1</label>
                                <Input 
                                    v-model="form.amperios_l1" 
                                    placeholder="" 
                                    :error="form.errors.amperios_l1"
                                />
                            </div>
                            <div>
                                <label class="text-xs text-gray-600 dark:text-gray-400">L2</label>
                                <Input 
                                    v-model="form.amperios_l2" 
                                    placeholder="" 
                                    :error="form.errors.amperios_l2"
                                />
                            </div>
                            <div>
                                <label class="text-xs text-gray-600 dark:text-gray-400">L3</label>
                                <Input 
                                    v-model="form.amperios_l3" 
                                    placeholder="" 
                                    :error="form.errors.amperios_l3"
                                />
                            </div>
                        </div>
                    </div>

                    <!-- VAC FASE N -->
                    <div class="mb-4">
                        <label class="text-sm font-semibold text-gray-700 dark:text-gray-300 uppercase mb-3 block">
                            VAC Fase N
                        </label>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="text-xs text-gray-600 dark:text-gray-400">L1</label>
                                <Input 
                                    v-model="form.vac_fase_n_l1n" 
                                    placeholder="" 
                                    :error="form.errors.vac_fase_n_l1n"
                                />
                            </div>
                            <div>
                                <label class="text-xs text-gray-600 dark:text-gray-400">L2</label>
                                <Input 
                                    v-model="form.vac_fase_n_l2n" 
                                    placeholder="" 
                                    :error="form.errors.vac_fase_n_l2n"
                                />
                            </div>
                            <div>
                                <label class="text-xs text-gray-600 dark:text-gray-400">L3</label>
                                <Input 
                                    v-model="form.vac_fase_n_l3n" 
                                    placeholder="" 
                                    :error="form.errors.vac_fase_n_l3n"
                                />
                            </div>
                        </div>
                    </div>

                    <!-- POTENCIA - HZ - FP -->
                    <div class="mb-4">
                        <label class="text-sm font-semibold text-gray-700 dark:text-gray-300 uppercase mb-3 block">
                            Potencia - HZ - FP
                        </label>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="text-xs text-gray-600 dark:text-gray-400">Potencia</label>
                                <Input 
                                    v-model="form.potencia" 
                                    placeholder="" 
                                    :error="form.errors.potencia"
                                />
                            </div>
                            <div>
                                <label class="text-xs text-gray-600 dark:text-gray-400">HZ</label>
                                <Input 
                                    v-model="form.hz" 
                                    placeholder="" 
                                    :error="form.errors.hz"
                                />
                            </div>
                            <div>
                                <label class="text-xs text-gray-600 dark:text-gray-400">FP</label>
                                <Input 
                                    v-model="form.fp" 
                                    placeholder="" 
                                    :error="form.errors.fp"
                                />
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- PROTECCIONES Section -->
            <div class="border-t pt-4">
                <h3 class="text-base font-bold text-gray-800 dark:text-gray-200 mb-4">
                    PROTECCIONES
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div class="flex flex-col gap-2">
                        <label class="text-sm font-semibold text-gray-700 dark:text-gray-300 uppercase">
                            Baja presión
                        </label>
                        <Input 
                            v-model="form.baja_presion" 
                            placeholder="" 
                            :error="form.errors.baja_presion"
                        />
                    </div>

                    <div class="flex flex-col gap-2">
                        <label class="text-sm font-semibold text-gray-700 dark:text-gray-300 uppercase">
                            Alta temperatura
                        </label>
                        <Input 
                            v-model="form.alta_temperatura" 
                            placeholder="" 
                            :error="form.errors.alta_temperatura"
                        />
                    </div>

                    <div class="flex flex-col gap-2">
                        <label class="text-sm font-semibold text-gray-700 dark:text-gray-300 uppercase">
                            Bajo nivel de regrigerante
                        </label>
                        <Input 
                            v-model="form.bajo_nivel_refrigerante" 
                            placeholder="" 
                            :error="form.errors.bajo_nivel_refrigerante"
                        />
                    </div>

                    <div class="flex flex-col gap-2">
                        <label class="text-sm font-semibold text-gray-700 dark:text-gray-300 uppercase">
                            Bajo voltaje de AC
                        </label>
                        <Input 
                            v-model="form.bajo_voltaje_ac" 
                            placeholder="" 
                            :error="form.errors.bajo_voltaje_ac"
                        />
                    </div>
                </div>
            </div>

            

            <!-- RECOMENDACIONES Section -->
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

            <!-- LLEGADA Y SALIDA TÉCNICO Section -->
            <div class="border-t pt-4">
                <h3 class="text-base font-bold text-gray-800 dark:text-gray-200 mb-4">
                    LLEGADA Y SALIDA TÉCNICO
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="flex flex-col gap-2">
                        <label class="text-sm font-semibold text-gray-700 dark:text-gray-300 uppercase">
                            Llegada técnico
                        </label>
                        <Input 
                            v-model="form.llegada_tecnico" 
                            type="datetime"
                            placeholder="" 
                            :error="form.errors.llegada_tecnico"
                        />
                    </div>

                    <div class="flex flex-col gap-2">
                        <label class="text-sm font-semibold text-gray-700 dark:text-gray-300 uppercase">
                            Salida técnico
                        </label>
                        <Input 
                            v-model="form.salida_tecnico" 
                            type="datetime"
                            placeholder="" 
                            :error="form.errors.salida_tecnico"
                        />
                    </div>
                </div>
            </div>

            <!-- CALIFICACIÓN DE SERVICIO (Cliente) Section -->
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

            <!-- POSICIÓN DE LOS INSTRUMENTOS AL CONCLUIR EL SERVICIO Section -->
            <div class="border-t pt-4">
                <h3 class="text-base font-bold text-gray-800 dark:text-gray-200 mb-4">
                    POSICIÓN DE LOS INSTRUMENTOS AL CONCLUIR EL SERVICIO
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <!-- Control -->
                    <RadioGroup 
                        v-model="form.control"
                        label="Control"
                        :options="['M', 'A', 'OFF']"
                        unique-id="control"
                        :error="form.errors.control"
                    />

                    <!-- Transferencia -->
                    <RadioGroup 
                        v-model="form.transferencia"
                        label="Transferencia"
                        :options="['M', 'A', 'OFF']"
                        unique-id="transferencia"
                        :error="form.errors.transferencia"
                    />

                    <!-- Posición cargador -->
                    <RadioGroup 
                        v-model="form.posicion_cargador"
                        label="Posición cargador"
                        :options="['ON', 'OFF']"
                        unique-id="cargador"
                        :error="form.errors.posicion_cargador"
                    />

                    <!-- Totalizador -->
                    <RadioGroup 
                        v-model="form.totalizador"
                        label="Totalizador"
                        :options="['ON', 'OFF']"
                        unique-id="totalizador"
                        :error="form.errors.totalizador"
                    />

                    <!-- Precalentador posición -->
                    <RadioGroup 
                        v-model="form.precalentador_posicion"
                        label="Precalentador posición"
                        :options="['ON', 'OFF']"
                        unique-id="precalentador"
                        :error="form.errors.precalentador_posicion"
                    />
                </div>
            </div>

            <!-- FOTOS DESPUÉS Section -->
            <div class="border-t pt-4">
                <h3 class="text-base font-bold text-gray-800 dark:text-gray-200 mb-4">
                    FOTOS DESPUÉS
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Foto uno despues -->
                    <div class="flex flex-col gap-2">
                        <div class="flex items-center gap-2">
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                Foto uno despues
                            </label>
                            <ImageThumbnail :image-url="props.informe?.foto_uno_despues" title="Ver foto uno después" />
                        </div>
                        <FilePond
                            name="foto_uno_despues"
                            :allow-multiple="false"
                            accepted-file-types="image/*"
                            @updatefiles="updateFilesFotoUnoDespues"
                            :label-idle="'Arrastra y suelta tu archivo o <span class=\'filepond--label-action\'>Examinar</span>'"
                        />
                        <span v-if="form.errors.foto_uno_despues" class="text-xs italic text-red-500">
                            {{ form.errors.foto_uno_despues }}
                        </span>
                        <Input 
                            v-model="form.pie_foto_uno_despues" 
                            placeholder="Pie de página para esta foto" 
                            :error="form.errors.pie_foto_uno_despues"
                        />
                    </div>

                    <!-- Foto dos despues -->
                    <div class="flex flex-col gap-2">
                        <div class="flex items-center gap-2">
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                Foto dos despues
                            </label>
                            <ImageThumbnail :image-url="props.informe?.foto_dos_despues" title="Ver foto dos después" />
                        </div>
                        <FilePond
                            name="foto_dos_despues"
                            :allow-multiple="false"
                            accepted-file-types="image/*"
                            @updatefiles="updateFilesFotoDosDespues"
                            :label-idle="'Arrastra y suelta tu archivo o <span class=\'filepond--label-action\'>Examinar</span>'"
                        />
                        <span v-if="form.errors.foto_dos_despues" class="text-xs italic text-red-500">
                            {{ form.errors.foto_dos_despues }}
                        </span>
                        <Input 
                            v-model="form.pie_foto_dos_despues" 
                            placeholder="Pie de página para esta foto" 
                            :error="form.errors.pie_foto_dos_despues"
                        />
                    </div>

                    <!-- Foto tres despues -->
                    <div class="flex flex-col gap-2">
                        <div class="flex items-center gap-2">
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                Foto tres despues
                            </label>
                            <ImageThumbnail :image-url="props.informe?.foto_tres_despues" title="Ver foto tres después" />
                        </div>
                        <FilePond
                            name="foto_tres_despues"
                            :allow-multiple="false"
                            accepted-file-types="image/*"
                            @updatefiles="updateFilesFotoTresDespues"
                            :label-idle="'Arrastra y suelta tu archivo o <span class=\'filepond--label-action\'>Examinar</span>'"
                        />
                        <span v-if="form.errors.foto_tres_despues" class="text-xs italic text-red-500">
                            {{ form.errors.foto_tres_despues }}
                        </span>
                        <Input 
                            v-model="form.pie_foto_tres_despues" 
                            placeholder="Pie de página para esta foto" 
                            :error="form.errors.pie_foto_tres_despues"
                        />
                    </div>
                </div>
            </div>

            <!-- FIRMAS Section -->
            <div class="border-t pt-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Técnico -->
                    <div class="flex flex-col gap-4">
                        <h3 class="text-base font-bold text-gray-800 dark:text-gray-200">
                            Técnico
                        </h3>
                        
                        <!-- Firma técnico -->
                        <SignaturePad
                            v-model="form.firma_tecnico"
                            label="Firma técnico"
                            :width="500"
                            :height="200"
                            :error="form.errors.firma_tecnico"
                        />

                        <!-- Nombre técnico -->
                        <div class="flex flex-col gap-2">
                            <label class="text-sm font-semibold text-gray-700 dark:text-gray-300">
                                Nombre técnico
                            </label>
                            <Input 
                                v-model="form.nombre_tecnico" 
                                placeholder="" 
                                :error="form.errors.nombre_tecnico"
                            />
                        </div>

                        <!-- Cédula técnico -->
                        <div class="flex flex-col gap-2">
                            <label class="text-sm font-semibold text-gray-700 dark:text-gray-300">
                                Cédula
                            </label>
                            <Input 
                                v-model="form.cedula_tecnico" 
                                placeholder="" 
                                :error="form.errors.cedula_tecnico"
                            />
                        </div>
                    </div>

                    <!-- Cliente -->
                    <div class="flex flex-col gap-4">
                        <h3 class="text-base font-bold text-gray-800 dark:text-gray-200">
                            Cliente
                        </h3>
                        
                        <!-- Firma cliente -->
                        <SignaturePad
                            v-model="form.firma_cliente"
                            label="Firma cliente"
                            :width="500"
                            :height="200"
                            :error="form.errors.firma_cliente"
                        />

                        <!-- Nombre cliente -->
                        <div class="flex flex-col gap-2">
                            <label class="text-sm font-semibold text-gray-700 dark:text-gray-300">
                                Nombre cliente
                            </label>
                            <Input 
                                v-model="form.nombre_cliente" 
                                placeholder="" 
                                :error="form.errors.nombre_cliente"
                            />
                        </div>

                        <!-- Cédula cliente -->
                        <div class="flex flex-col gap-2">
                            <label class="text-sm font-semibold text-gray-700 dark:text-gray-300">
                                Cédula
                            </label>
                            <Input 
                                v-model="form.cedula_cliente" 
                                placeholder="" 
                                :error="form.errors.cedula_cliente"
                            />
                        </div>
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
                    @click="emit('close')"
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
