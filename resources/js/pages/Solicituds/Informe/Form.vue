<script setup lang="ts">
import { ref } from 'vue';
import Input from '@/components/Input.vue';
import RichTextEditor from '@/components/RichTextEditor.vue';
import SignaturePad from '@/components/SignaturePad.vue';
import { Button } from 'primevue';
import RadioButton from 'primevue/radiobutton';
import AppLayout from '@/layouts/AppLayout.vue';
import vueFilePond from 'vue-filepond';

const FilePond = vueFilePond();

const emit = defineEmits(['close']);

interface Props {
    informe?: any | null;
}

const props = defineProps<Props>();

// Form data
const form = ref({
    tipo_servicio: 'Mantenimiento',
    observaciones_iniciales: '',
    nivel_aceite: '',
    nivel_refrigerante: '',
    nivel_combustible: '',
    capacidad_tanque: '',
    fugas: '',
    mangueras: '',
    // Estado inicial - adicionales
    sellos: '',
    tuberias: '',
    radiador: '',
    guardas: '',
    correas_ventilador: '',
    correas_alternador: '',
    amortiguadores: '',
    precalentador_estado_inicial: '',
    bateria: '',
    nivel_electrolito: '',
    voltaje_bateria_estado: '',
    estado_cargador: '',
    voltaje_cargador: '',
    tipo_control: '',
    voltaje_alternador: '',
    conexiones_control: '',
    conexiones_potencia: '',
    limpieza_general: '',
    // Fotos antes
    foto_uno_antes: null as File | null,
    foto_dos_antes: null as File | null,
    foto_tres_antes: null as File | null,
    // Actividad realizada
    actividad_realizada: '',
    // Pruebas con equipo operando - Motor
    presion_aceite_valor: '',
    presion_aceite_unidad: '',
    temp_refrigerante_valor: '',
    temp_refrigerante_unidad: '',
    temp_aceite_valor: '',
    temp_aceite_unidad: '',
    temp_turbo_valor: '',
    temp_turbo_unidad: '',
    rpm_valor: '',
    rpm_unidad: '',
    voltaje_bateria_valor: '',
    voltaje_bateria_unidad: '',
    // Caída voltaje de batería
    caida_voltaje_bat_valor: '',
    caida_voltaje_bat_unidad: '',
    // Generador - VAC Fases
    vac_fases_l1_l2: '',
    vac_fases_l2_l3: '',
    vac_fases_l1_l3: '',
    // Generador - Amperios
    amperios_l1: '',
    amperios_l2: '',
    amperios_l3: '',
    // Generador - VAC Fase N
    vac_fase_n_l1: '',
    vac_fase_n_l2: '',
    vac_fase_n_l3: '',
    // Generador - Potencia - HZ - FP
    potencia: '',
    hz: '',
    fp: '',
    // Protecciones
    baja_presion: '',
    alta_temperatura: '',
    bajo_nivel_regrigerante: '',
    bajo_voltaje_ac: '',
    // Fotos durante
    foto_uno_durante: null as File | null,
    foto_dos_durante: null as File | null,
    foto_tres_durante: null as File | null,
    foto_cuatro_durante: null as File | null,
    foto_cinco_durante: null as File | null,
    foto_seis_durante: null as File | null,
    foto_siete_durante: null as File | null,
    foto_ocho_durante: null as File | null,
    foto_nueve_durante: null as File | null,
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
    foto_dos_despues: null as File | null,
    foto_tres_despues: null as File | null,
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

const tiposServicio = [
    { label: 'Mantenimiento', value: 'Mantenimiento' },
    { label: 'Servicio', value: 'Servicio' },
    { label: 'Inspeccion', value: 'Inspeccion' },
    { label: 'Soporte', value: 'Soporte' },
    { label: 'Emergencia', value: 'Emergencia' }
];

const nivelesOptions = ['B', 'R', 'M', 'N/A'];

const myFilesFotoUno = ref<any[]>([]);
const myFilesFotoDos = ref<any[]>([]);
const myFilesFotoTres = ref<any[]>([]);
const myFilesFotoUnoDurante = ref<any[]>([]);
const myFilesFotoDosDurante = ref<any[]>([]);
const myFilesFotoTresDurante = ref<any[]>([]);
const myFilesFotoCuatroDurante = ref<any[]>([]);
const myFilesFotoCincoDurante = ref<any[]>([]);
const myFilesFotoSeisDurante = ref<any[]>([]);
const myFilesFotoSieteDurante = ref<any[]>([]);
const myFilesFotoOchoDurante = ref<any[]>([]);
const myFilesFotoNueveDurante = ref<any[]>([]);
const myFilesFotoUnoDespues = ref<any[]>([]);
const myFilesFotoDosDespues = ref<any[]>([]);
const myFilesFotoTresDespues = ref<any[]>([]);

const updateFilesFotoUno = (files: any) => {
    if (files && files.length > 0) {
        form.value.foto_uno_antes = files[0].file;
    }
};

const updateFilesFotoDos = (files: any) => {
    if (files && files.length > 0) {
        form.value.foto_dos_antes = files[0].file;
    }
};

const updateFilesFotoTres = (files: any) => {
    if (files && files.length > 0) {
        form.value.foto_tres_antes = files[0].file;
    }
};

const updateFilesFotoUnoDurante = (files: any) => {
    if (files && files.length > 0) {
        form.value.foto_uno_durante = files[0].file;
    }
};

const updateFilesFotoDosDurante = (files: any) => {
    if (files && files.length > 0) {
        form.value.foto_dos_durante = files[0].file;
    }
};

const updateFilesFotoTresDurante = (files: any) => {
    if (files && files.length > 0) {
        form.value.foto_tres_durante = files[0].file;
    }
};

const updateFilesFotoCuatroDurante = (files: any) => {
    if (files && files.length > 0) {
        form.value.foto_cuatro_durante = files[0].file;
    }
};

const updateFilesFotoCincoDurante = (files: any) => {
    if (files && files.length > 0) {
        form.value.foto_cinco_durante = files[0].file;
    }
};

const updateFilesFotoSeisDurante = (files: any) => {
    if (files && files.length > 0) {
        form.value.foto_seis_durante = files[0].file;
    }
};

const updateFilesFotoSieteDurante = (files: any) => {
    if (files && files.length > 0) {
        form.value.foto_siete_durante = files[0].file;
    }
};

const updateFilesFotoOchoDurante = (files: any) => {
    if (files && files.length > 0) {
        form.value.foto_ocho_durante = files[0].file;
    }
};

const updateFilesFotoNueveDurante = (files: any) => {
    if (files && files.length > 0) {
        form.value.foto_nueve_durante = files[0].file;
    }
};

const updateFilesFotoUnoDespues = (files: any) => {
    if (files && files.length > 0) {
        form.value.foto_uno_despues = files[0].file;
    }
};

const updateFilesFotoDosDespues = (files: any) => {
    if (files && files.length > 0) {
        form.value.foto_dos_despues = files[0].file;
    }
};

const updateFilesFotoTresDespues = (files: any) => {
    if (files && files.length > 0) {
        form.value.foto_tres_despues = files[0].file;
    }
};

const handleSubmit = () => {
    form.value.processing = true;
    // Aquí iría la lógica de envío del formulario
    console.log('Form data:', form.value);
    
    setTimeout(() => {
        form.value.processing = false;
        emit('close');
    }, 1000);
};
</script>

<template>
    <AppLayout class="p-4">
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

            <!-- ESTADO INICIAL Section -->
            <div class="border-t pt-4">
                <h3 class="text-base font-bold text-gray-800 dark:text-gray-200 mb-4">
                    ESTADO INICIAL
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <!-- Nivel aceite -->
                    <div class="flex flex-col gap-2">
                        <label class="text-sm font-medium text-gray-700 dark:text-gray-300">
                            Nivel aceite
                        </label>
                        <div class="flex flex-wrap gap-3">
                            <div v-for="nivel in nivelesOptions" :key="'aceite_' + nivel" class="flex items-center gap-2">
                                <RadioButton 
                                    v-model="form.nivel_aceite" 
                                    :inputId="'aceite_' + nivel" 
                                    :value="nivel"
                                />
                                <label :for="'aceite_' + nivel" class="text-sm cursor-pointer">{{ nivel }}</label>
                            </div>
                        </div>
                        <span v-if="form.errors.nivel_aceite" class="text-xs italic text-red-500">
                            {{ form.errors.nivel_aceite }}
                        </span>
                    </div>

                    <!-- Nivel refrigerante -->
                    <div class="flex flex-col gap-2">
                        <label class="text-sm font-medium text-gray-700 dark:text-gray-300">
                            Nivel refrigerante
                        </label>
                        <div class="flex flex-wrap gap-3">
                            <div v-for="nivel in nivelesOptions" :key="'refrigerante_' + nivel" class="flex items-center gap-2">
                                <RadioButton 
                                    v-model="form.nivel_refrigerante" 
                                    :inputId="'refrigerante_' + nivel" 
                                    :value="nivel"
                                />
                                <label :for="'refrigerante_' + nivel" class="text-sm cursor-pointer">{{ nivel }}</label>
                            </div>
                        </div>
                        <span v-if="form.errors.nivel_refrigerante" class="text-xs italic text-red-500">
                            {{ form.errors.nivel_refrigerante }}
                        </span>
                    </div>

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
                    <div class="flex flex-col gap-2">
                        <label class="text-sm font-medium text-gray-700 dark:text-gray-300">
                            Mangueras
                        </label>
                        <div class="flex flex-wrap gap-3">
                            <div v-for="nivel in nivelesOptions" :key="'mangueras_' + nivel" class="flex items-center gap-2">
                                <RadioButton 
                                    v-model="form.mangueras" 
                                    :inputId="'mangueras_' + nivel" 
                                    :value="nivel"
                                />
                                <label :for="'mangueras_' + nivel" class="text-sm cursor-pointer">{{ nivel }}</label>
                            </div>
                        </div>
                        <span v-if="form.errors.mangueras" class="text-xs italic text-red-500">
                            {{ form.errors.mangueras }}
                        </span>
                    </div>

                    <!-- Sellos -->
                    <div class="flex flex-col gap-2">
                        <label class="text-sm font-medium text-gray-700 dark:text-gray-300">
                            Sellos
                        </label>
                        <div class="flex flex-wrap gap-3">
                            <div v-for="nivel in nivelesOptions" :key="'sellos_' + nivel" class="flex items-center gap-2">
                                <RadioButton 
                                    v-model="form.sellos" 
                                    :inputId="'sellos_' + nivel" 
                                    :value="nivel"
                                />
                                <label :for="'sellos_' + nivel" class="text-sm cursor-pointer">{{ nivel }}</label>
                            </div>
                        </div>
                        <span v-if="form.errors.sellos" class="text-xs italic text-red-500">
                            {{ form.errors.sellos }}
                        </span>
                    </div>

                    <!-- Tuberías -->
                    <div class="flex flex-col gap-2">
                        <label class="text-sm font-medium text-gray-700 dark:text-gray-300">
                            Tuberías
                        </label>
                        <div class="flex flex-wrap gap-3">
                            <div v-for="nivel in nivelesOptions" :key="'tuberias_' + nivel" class="flex items-center gap-2">
                                <RadioButton 
                                    v-model="form.tuberias" 
                                    :inputId="'tuberias_' + nivel" 
                                    :value="nivel"
                                />
                                <label :for="'tuberias_' + nivel" class="text-sm cursor-pointer">{{ nivel }}</label>
                            </div>
                        </div>
                        <span v-if="form.errors.tuberias" class="text-xs italic text-red-500">
                            {{ form.errors.tuberias }}
                        </span>
                    </div>

                    <!-- Radiador -->
                    <div class="flex flex-col gap-2">
                        <label class="text-sm font-medium text-gray-700 dark:text-gray-300">
                            Radiador
                        </label>
                        <div class="flex flex-wrap gap-3">
                            <div v-for="nivel in nivelesOptions" :key="'radiador_' + nivel" class="flex items-center gap-2">
                                <RadioButton 
                                    v-model="form.radiador" 
                                    :inputId="'radiador_' + nivel" 
                                    :value="nivel"
                                />
                                <label :for="'radiador_' + nivel" class="text-sm cursor-pointer">{{ nivel }}</label>
                            </div>
                        </div>
                        <span v-if="form.errors.radiador" class="text-xs italic text-red-500">
                            {{ form.errors.radiador }}
                        </span>
                    </div>

                    <!-- Guardas -->
                    <div class="flex flex-col gap-2">
                        <label class="text-sm font-medium text-gray-700 dark:text-gray-300">
                            Guardas
                        </label>
                        <div class="flex flex-wrap gap-3">
                            <div v-for="nivel in nivelesOptions" :key="'guardas_' + nivel" class="flex items-center gap-2">
                                <RadioButton 
                                    v-model="form.guardas" 
                                    :inputId="'guardas_' + nivel" 
                                    :value="nivel"
                                />
                                <label :for="'guardas_' + nivel" class="text-sm cursor-pointer">{{ nivel }}</label>
                            </div>
                        </div>
                        <span v-if="form.errors.guardas" class="text-xs italic text-red-500">
                            {{ form.errors.guardas }}
                        </span>
                    </div>

                    <!-- Correas ventilador -->
                    <div class="flex flex-col gap-2">
                        <label class="text-sm font-medium text-gray-700 dark:text-gray-300">
                            Correas ventilador
                        </label>
                        <div class="flex flex-wrap gap-3">
                            <div v-for="nivel in nivelesOptions" :key="'correas_ventilador_' + nivel" class="flex items-center gap-2">
                                <RadioButton 
                                    v-model="form.correas_ventilador" 
                                    :inputId="'correas_ventilador_' + nivel" 
                                    :value="nivel"
                                />
                                <label :for="'correas_ventilador_' + nivel" class="text-sm cursor-pointer">{{ nivel }}</label>
                            </div>
                        </div>
                        <span v-if="form.errors.correas_ventilador" class="text-xs italic text-red-500">
                            {{ form.errors.correas_ventilador }}
                        </span>
                    </div>

                    <!-- Correas alternador -->
                    <div class="flex flex-col gap-2">
                        <label class="text-sm font-medium text-gray-700 dark:text-gray-300">
                            Correas alternador
                        </label>
                        <div class="flex flex-wrap gap-3">
                            <div v-for="nivel in nivelesOptions" :key="'correas_alternador_' + nivel" class="flex items-center gap-2">
                                <RadioButton 
                                    v-model="form.correas_alternador" 
                                    :inputId="'correas_alternador_' + nivel" 
                                    :value="nivel"
                                />
                                <label :for="'correas_alternador_' + nivel" class="text-sm cursor-pointer">{{ nivel }}</label>
                            </div>
                        </div>
                        <span v-if="form.errors.correas_alternador" class="text-xs italic text-red-500">
                            {{ form.errors.correas_alternador }}
                        </span>
                    </div>

                    <!-- Amortiguadores -->
                    <div class="flex flex-col gap-2">
                        <label class="text-sm font-medium text-gray-700 dark:text-gray-300">
                            Amortiguadores
                        </label>
                        <div class="flex flex-wrap gap-3">
                            <div v-for="nivel in nivelesOptions" :key="'amortiguadores_' + nivel" class="flex items-center gap-2">
                                <RadioButton 
                                    v-model="form.amortiguadores" 
                                    :inputId="'amortiguadores_' + nivel" 
                                    :value="nivel"
                                />
                                <label :for="'amortiguadores_' + nivel" class="text-sm cursor-pointer">{{ nivel }}</label>
                            </div>
                        </div>
                        <span v-if="form.errors.amortiguadores" class="text-xs italic text-red-500">
                            {{ form.errors.amortiguadores }}
                        </span>
                    </div>

                    <!-- Precalentador estado inicial -->
                    <div class="flex flex-col gap-2">
                        <label class="text-sm font-medium text-gray-700 dark:text-gray-300">
                            Precalentador estado inicial
                        </label>
                        <div class="flex flex-wrap gap-3">
                            <div v-for="nivel in nivelesOptions" :key="'precalentador_estado_inicial_' + nivel" class="flex items-center gap-2">
                                <RadioButton 
                                    v-model="form.precalentador_estado_inicial" 
                                    :inputId="'precalentador_estado_inicial_' + nivel" 
                                    :value="nivel"
                                />
                                <label :for="'precalentador_estado_inicial_' + nivel" class="text-sm cursor-pointer">{{ nivel }}</label>
                            </div>
                        </div>
                        <span v-if="form.errors.precalentador_estado_inicial" class="text-xs italic text-red-500">
                            {{ form.errors.precalentador_estado_inicial }}
                        </span>
                    </div>

                    <!-- Bateria -->
                    <div class="flex flex-col gap-2">
                        <label class="text-sm font-medium text-gray-700 dark:text-gray-300">
                            Batería
                        </label>
                        <div class="flex flex-wrap gap-3">
                            <div v-for="nivel in nivelesOptions" :key="'bateria_' + nivel" class="flex items-center gap-2">
                                <RadioButton 
                                    v-model="form.bateria" 
                                    :inputId="'bateria_' + nivel" 
                                    :value="nivel"
                                />
                                <label :for="'bateria_' + nivel" class="text-sm cursor-pointer">{{ nivel }}</label>
                            </div>
                        </div>
                        <span v-if="form.errors.bateria" class="text-xs italic text-red-500">
                            {{ form.errors.bateria }}
                        </span>
                    </div>

                    <!-- Nivel electrolito -->
                    <div class="flex flex-col gap-2">
                        <label class="text-sm font-medium text-gray-700 dark:text-gray-300">
                            Nivel electrolito
                        </label>
                        <div class="flex flex-wrap gap-3">
                            <div v-for="nivel in nivelesOptions" :key="'nivel_electrolito_' + nivel" class="flex items-center gap-2">
                                <RadioButton 
                                    v-model="form.nivel_electrolito" 
                                    :inputId="'nivel_electrolito_' + nivel" 
                                    :value="nivel"
                                />
                                <label :for="'nivel_electrolito_' + nivel" class="text-sm cursor-pointer">{{ nivel }}</label>
                            </div>
                        </div>
                        <span v-if="form.errors.nivel_electrolito" class="text-xs italic text-red-500">
                            {{ form.errors.nivel_electrolito }}
                        </span>
                    </div>

                    <!-- Voltaje batería -->
                    <div class="flex flex-col gap-2">
                        <label class="text-sm font-medium text-gray-700 dark:text-gray-300">
                            Voltaje batería
                        </label>
                        <div class="flex flex-wrap gap-3">
                            <div v-for="nivel in nivelesOptions" :key="'voltaje_bateria_estado_' + nivel" class="flex items-center gap-2">
                                <RadioButton 
                                    v-model="form.voltaje_bateria_estado" 
                                    :inputId="'voltaje_bateria_estado_' + nivel" 
                                    :value="nivel"
                                />
                                <label :for="'voltaje_bateria_estado_' + nivel" class="text-sm cursor-pointer">{{ nivel }}</label>
                            </div>
                        </div>
                        <span v-if="form.errors.voltaje_bateria_estado" class="text-xs italic text-red-500">
                            {{ form.errors.voltaje_bateria_estado }}
                        </span>
                    </div>

                    <!-- Estado cargador -->
                    <div class="flex flex-col gap-2">
                        <label class="text-sm font-medium text-gray-700 dark:text-gray-300">
                            Estado cargador
                        </label>
                        <div class="flex flex-wrap gap-3">
                            <div v-for="nivel in nivelesOptions" :key="'estado_cargador_' + nivel" class="flex items-center gap-2">
                                <RadioButton 
                                    v-model="form.estado_cargador" 
                                    :inputId="'estado_cargador_' + nivel" 
                                    :value="nivel"
                                />
                                <label :for="'estado_cargador_' + nivel" class="text-sm cursor-pointer">{{ nivel }}</label>
                            </div>
                        </div>
                        <span v-if="form.errors.estado_cargador" class="text-xs italic text-red-500">
                            {{ form.errors.estado_cargador }}
                        </span>
                    </div>

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
                    <div class="flex flex-col gap-2">
                        <label class="text-sm font-medium text-gray-700 dark:text-gray-300">
                            Conexiones control
                        </label>
                        <div class="flex flex-wrap gap-3">
                            <div v-for="nivel in nivelesOptions" :key="'conexiones_control_' + nivel" class="flex items-center gap-2">
                                <RadioButton 
                                    v-model="form.conexiones_control" 
                                    :inputId="'conexiones_control_' + nivel" 
                                    :value="nivel"
                                />
                                <label :for="'conexiones_control_' + nivel" class="text-sm cursor-pointer">{{ nivel }}</label>
                            </div>
                        </div>
                        <span v-if="form.errors.conexiones_control" class="text-xs italic text-red-500">
                            {{ form.errors.conexiones_control }}
                        </span>
                    </div>

                    <!-- Conexiones potencia -->
                    <div class="flex flex-col gap-2">
                        <label class="text-sm font-medium text-gray-700 dark:text-gray-300">
                            Conexiones potencia
                        </label>
                        <div class="flex flex-wrap gap-3">
                            <div v-for="nivel in nivelesOptions" :key="'conexiones_potencia_' + nivel" class="flex items-center gap-2">
                                <RadioButton 
                                    v-model="form.conexiones_potencia" 
                                    :inputId="'conexiones_potencia_' + nivel" 
                                    :value="nivel"
                                />
                                <label :for="'conexiones_potencia_' + nivel" class="text-sm cursor-pointer">{{ nivel }}</label>
                            </div>
                        </div>
                        <span v-if="form.errors.conexiones_potencia" class="text-xs italic text-red-500">
                            {{ form.errors.conexiones_potencia }}
                        </span>
                    </div>

                    <!-- Limpieza General -->
                    <div class="flex flex-col gap-2">
                        <label class="text-sm font-medium text-gray-700 dark:text-gray-300">
                            Limpieza General
                        </label>
                        <div class="flex flex-wrap gap-3">
                            <div v-for="nivel in nivelesOptions" :key="'limpieza_general_' + nivel" class="flex items-center gap-2">
                                <RadioButton 
                                    v-model="form.limpieza_general" 
                                    :inputId="'limpieza_general_' + nivel" 
                                    :value="nivel"
                                />
                                <label :for="'limpieza_general_' + nivel" class="text-sm cursor-pointer">{{ nivel }}</label>
                            </div>
                        </div>
                        <span v-if="form.errors.limpieza_general" class="text-xs italic text-red-500">
                            {{ form.errors.limpieza_general }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- FOTOS ANTES Section -->
            <div class="border-t pt-4">
                <h3 class="text-base font-bold text-gray-800 dark:text-gray-200 mb-4">
                    FOTOS ANTES
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Foto uno antes -->
                    <div class="flex flex-col gap-2">
                        <label class="text-sm font-medium text-gray-700 dark:text-gray-300">
                            Foto uno antes
                        </label>
                        <FilePond
                            name="foto_uno_antes"
                            :files="myFilesFotoUno"
                            :allow-multiple="false"
                            accepted-file-types="image/*"
                            @updatefiles="updateFilesFotoUno"
                            :label-idle="'Arrastra y suelta tu archivo o <span class=\'filepond--label-action\'>Examinar</span>'"
                        />
                        <span v-if="form.errors.foto_uno_antes" class="text-xs italic text-red-500">
                            {{ form.errors.foto_uno_antes }}
                        </span>
                    </div>

                    <!-- Foto dos antes -->
                    <div class="flex flex-col gap-2">
                        <label class="text-sm font-medium text-gray-700 dark:text-gray-300">
                            Foto dos antes
                        </label>
                        <FilePond
                            name="foto_dos_antes"
                            :files="myFilesFotoDos"
                            :allow-multiple="false"
                            accepted-file-types="image/*"
                            @updatefiles="updateFilesFotoDos"
                            :label-idle="'Arrastra y suelta tu archivo o <span class=\'filepond--label-action\'>Examinar</span>'"
                        />
                        <span v-if="form.errors.foto_dos_antes" class="text-xs italic text-red-500">
                            {{ form.errors.foto_dos_antes }}
                        </span>
                    </div>

                    <!-- Foto tres antes -->
                    <div class="flex flex-col gap-2">
                        <label class="text-sm font-medium text-gray-700 dark:text-gray-300">
                            Foto tres antes
                        </label>
                        <FilePond
                            name="foto_tres_antes"
                            :files="myFilesFotoTres"
                            :allow-multiple="false"
                            accepted-file-types="image/*"
                            @updatefiles="updateFilesFotoTres"
                            :label-idle="'Arrastra y suelta tu archivo o <span class=\'filepond--label-action\'>Examinar</span>'"
                        />
                        <span v-if="form.errors.foto_tres_antes" class="text-xs italic text-red-500">
                            {{ form.errors.foto_tres_antes }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- ACTIVIDAD REALIZADA Section -->
            <div class="border-t pt-4">
                <h3 class="text-base font-bold text-gray-800 dark:text-gray-200 mb-4">
                    ACTIVIDAD REALIZADA
                </h3>
                
                <RichTextEditor 
                    v-model="form.actividad_realizada" 
                    :error="form.errors.actividad_realizada"
                    placeholder="Describe la actividad realizada..."
                />
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
                                        v-model="form.presion_aceite_valor" 
                                        placeholder="" 
                                        :error="form.errors.presion_aceite_valor"
                                    />
                                </div>
                                <div>
                                    <label class="text-xs text-gray-600 dark:text-gray-400">Unidad</label>
                                    <Input 
                                        v-model="form.presion_aceite_unidad" 
                                        placeholder="" 
                                        :error="form.errors.presion_aceite_unidad"
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
                                        v-model="form.temp_refrigerante_valor" 
                                        placeholder="" 
                                        :error="form.errors.temp_refrigerante_valor"
                                    />
                                </div>
                                <div>
                                    <label class="text-xs text-gray-600 dark:text-gray-400">Unidad (Galones)</label>
                                    <Input 
                                        v-model="form.temp_refrigerante_unidad" 
                                        placeholder="" 
                                        :error="form.errors.temp_refrigerante_unidad"
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
                                        v-model="form.temp_aceite_valor" 
                                        placeholder="" 
                                        :error="form.errors.temp_aceite_valor"
                                    />
                                </div>
                                <div>
                                    <label class="text-xs text-gray-600 dark:text-gray-400">Unidad (Galones)</label>
                                    <Input 
                                        v-model="form.temp_aceite_unidad" 
                                        placeholder="" 
                                        :error="form.errors.temp_aceite_unidad"
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
                                        v-model="form.temp_turbo_valor" 
                                        placeholder="" 
                                        :error="form.errors.temp_turbo_valor"
                                    />
                                </div>
                                <div>
                                    <label class="text-xs text-gray-600 dark:text-gray-400">Unidad</label>
                                    <Input 
                                        v-model="form.temp_turbo_unidad" 
                                        placeholder="" 
                                        :error="form.errors.temp_turbo_unidad"
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
                                        v-model="form.rpm_valor" 
                                        placeholder="" 
                                        :error="form.errors.rpm_valor"
                                    />
                                </div>
                                <div>
                                    <label class="text-xs text-gray-600 dark:text-gray-400">Unidad</label>
                                    <Input 
                                        v-model="form.rpm_unidad" 
                                        placeholder="" 
                                        :error="form.errors.rpm_unidad"
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
                                        v-model="form.voltaje_bateria_valor" 
                                        placeholder="" 
                                        :error="form.errors.voltaje_bateria_valor"
                                    />
                                </div>
                                <div>
                                    <label class="text-xs text-gray-600 dark:text-gray-400">Unidad</label>
                                    <Input 
                                        v-model="form.voltaje_bateria_unidad" 
                                        placeholder="" 
                                        :error="form.errors.voltaje_bateria_unidad"
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
                                v-model="form.caida_voltaje_bat_valor" 
                                placeholder="" 
                                :error="form.errors.caida_voltaje_bat_valor"
                            />
                        </div>
                        <div>
                            <label class="text-xs text-gray-600 dark:text-gray-400">Unidad</label>
                            <Input 
                                v-model="form.caida_voltaje_bat_unidad" 
                                placeholder="" 
                                :error="form.errors.caida_voltaje_bat_unidad"
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
                                    v-model="form.vac_fase_n_l1" 
                                    placeholder="" 
                                    :error="form.errors.vac_fase_n_l1"
                                />
                            </div>
                            <div>
                                <label class="text-xs text-gray-600 dark:text-gray-400">L2</label>
                                <Input 
                                    v-model="form.vac_fase_n_l2" 
                                    placeholder="" 
                                    :error="form.errors.vac_fase_n_l2"
                                />
                            </div>
                            <div>
                                <label class="text-xs text-gray-600 dark:text-gray-400">L3</label>
                                <Input 
                                    v-model="form.vac_fase_n_l3" 
                                    placeholder="" 
                                    :error="form.errors.vac_fase_n_l3"
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
                            v-model="form.bajo_nivel_regrigerante" 
                            placeholder="" 
                            :error="form.errors.bajo_nivel_regrigerante"
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

            <!-- FOTOS DURANTE Section -->
            <div class="border-t pt-4">
                <h3 class="text-base font-bold text-gray-800 dark:text-gray-200 mb-4">
                    FOTOS DURANTE
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <!-- Foto uno durante -->
                    <div class="flex flex-col gap-2">
                        <label class="text-sm font-medium text-gray-700 dark:text-gray-300">
                            Foto uno durante
                        </label>
                        <FilePond
                            name="foto_uno_durante"
                            :files="myFilesFotoUnoDurante"
                            :allow-multiple="false"
                            accepted-file-types="image/*"
                            @updatefiles="updateFilesFotoUnoDurante"
                            :label-idle="'Arrastra y suelta tu archivo o <span class=\'filepond--label-action\'>Examinar</span>'"
                        />
                        <span v-if="form.errors.foto_uno_durante" class="text-xs italic text-red-500">
                            {{ form.errors.foto_uno_durante }}
                        </span>
                    </div>

                    <!-- Foto dos durante -->
                    <div class="flex flex-col gap-2">
                        <label class="text-sm font-medium text-gray-700 dark:text-gray-300">
                            Foto dos durante
                        </label>
                        <FilePond
                            name="foto_dos_durante"
                            :files="myFilesFotoDosDurante"
                            :allow-multiple="false"
                            accepted-file-types="image/*"
                            @updatefiles="updateFilesFotoDosDurante"
                            :label-idle="'Arrastra y suelta tu archivo o <span class=\'filepond--label-action\'>Examinar</span>'"
                        />
                        <span v-if="form.errors.foto_dos_durante" class="text-xs italic text-red-500">
                            {{ form.errors.foto_dos_durante }}
                        </span>
                    </div>

                    <!-- Foto tres durante -->
                    <div class="flex flex-col gap-2">
                        <label class="text-sm font-medium text-gray-700 dark:text-gray-300">
                            Foto tres durante
                        </label>
                        <FilePond
                            name="foto_tres_durante"
                            :files="myFilesFotoTresDurante"
                            :allow-multiple="false"
                            accepted-file-types="image/*"
                            @updatefiles="updateFilesFotoTresDurante"
                            :label-idle="'Arrastra y suelta tu archivo o <span class=\'filepond--label-action\'>Examinar</span>'"
                        />
                        <span v-if="form.errors.foto_tres_durante" class="text-xs italic text-red-500">
                            {{ form.errors.foto_tres_durante }}
                        </span>
                    </div>

                    <!-- Foto cuatro durante -->
                    <div class="flex flex-col gap-2">
                        <label class="text-sm font-medium text-gray-700 dark:text-gray-300">
                            Foto cuatro durante
                        </label>
                        <FilePond
                            name="foto_cuatro_durante"
                            :files="myFilesFotoCuatroDurante"
                            :allow-multiple="false"
                            accepted-file-types="image/*"
                            @updatefiles="updateFilesFotoCuatroDurante"
                            :label-idle="'Arrastra y suelta tu archivo o <span class=\'filepond--label-action\'>Examinar</span>'"
                        />
                        <span v-if="form.errors.foto_cuatro_durante" class="text-xs italic text-red-500">
                            {{ form.errors.foto_cuatro_durante }}
                        </span>
                    </div>

                    <!-- Foto cinco durante -->
                    <div class="flex flex-col gap-2">
                        <label class="text-sm font-medium text-gray-700 dark:text-gray-300">
                            Foto cinco durante
                        </label>
                        <FilePond
                            name="foto_cinco_durante"
                            :files="myFilesFotoCincoDurante"
                            :allow-multiple="false"
                            accepted-file-types="image/*"
                            @updatefiles="updateFilesFotoCincoDurante"
                            :label-idle="'Arrastra y suelta tu archivo o <span class=\'filepond--label-action\'>Examinar</span>'"
                        />
                        <span v-if="form.errors.foto_cinco_durante" class="text-xs italic text-red-500">
                            {{ form.errors.foto_cinco_durante }}
                        </span>
                    </div>

                    <!-- Foto seis durante -->
                    <div class="flex flex-col gap-2">
                        <label class="text-sm font-medium text-gray-700 dark:text-gray-300">
                            Foto seis durante
                        </label>
                        <FilePond
                            name="foto_seis_durante"
                            :files="myFilesFotoSeisDurante"
                            :allow-multiple="false"
                            accepted-file-types="image/*"
                            @updatefiles="updateFilesFotoSeisDurante"
                            :label-idle="'Arrastra y suelta tu archivo o <span class=\'filepond--label-action\'>Examinar</span>'"
                        />
                        <span v-if="form.errors.foto_seis_durante" class="text-xs italic text-red-500">
                            {{ form.errors.foto_seis_durante }}
                        </span>
                    </div>

                    <!-- Foto siete durante -->
                    <div class="flex flex-col gap-2">
                        <label class="text-sm font-medium text-gray-700 dark:text-gray-300">
                            Foto siete durante
                        </label>
                        <FilePond
                            name="foto_siete_durante"
                            :files="myFilesFotoSieteDurante"
                            :allow-multiple="false"
                            accepted-file-types="image/*"
                            @updatefiles="updateFilesFotoSieteDurante"
                            :label-idle="'Arrastra y suelta tu archivo o <span class=\'filepond--label-action\'>Examinar</span>'"
                        />
                        <span v-if="form.errors.foto_siete_durante" class="text-xs italic text-red-500">
                            {{ form.errors.foto_siete_durante }}
                        </span>
                    </div>

                    <!-- Foto ocho durante -->
                    <div class="flex flex-col gap-2">
                        <label class="text-sm font-medium text-gray-700 dark:text-gray-300">
                            Foto ocho durante
                        </label>
                        <FilePond
                            name="foto_ocho_durante"
                            :files="myFilesFotoOchoDurante"
                            :allow-multiple="false"
                            accepted-file-types="image/*"
                            @updatefiles="updateFilesFotoOchoDurante"
                            :label-idle="'Arrastra y suelta tu archivo o <span class=\'filepond--label-action\'>Examinar</span>'"
                        />
                        <span v-if="form.errors.foto_ocho_durante" class="text-xs italic text-red-500">
                            {{ form.errors.foto_ocho_durante }}
                        </span>
                    </div>

                    <!-- Foto nueve durante -->
                    <div class="flex flex-col gap-2">
                        <label class="text-sm font-medium text-gray-700 dark:text-gray-300">
                            Foto nueve durante
                        </label>
                        <FilePond
                            name="foto_nueve_durante"
                            :files="myFilesFotoNueveDurante"
                            :allow-multiple="false"
                            accepted-file-types="image/*"
                            @updatefiles="updateFilesFotoNueveDurante"
                            :label-idle="'Arrastra y suelta tu archivo o <span class=\'filepond--label-action\'>Examinar</span>'"
                        />
                        <span v-if="form.errors.foto_nueve_durante" class="text-xs italic text-red-500">
                            {{ form.errors.foto_nueve_durante }}
                        </span>
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
                
                <div class="flex flex-wrap gap-4">
                    <div class="flex items-center gap-2">
                        <RadioButton 
                            v-model="form.calificacion_servicio" 
                            inputId="calificacion_bueno" 
                            value="Bueno"
                        />
                        <label for="calificacion_bueno" class="text-sm cursor-pointer">Bueno</label>
                    </div>
                    <div class="flex items-center gap-2">
                        <RadioButton 
                            v-model="form.calificacion_servicio" 
                            inputId="calificacion_regular" 
                            value="Regular"
                        />
                        <label for="calificacion_regular" class="text-sm cursor-pointer">Regular</label>
                    </div>
                    <div class="flex items-center gap-2">
                        <RadioButton 
                            v-model="form.calificacion_servicio" 
                            inputId="calificacion_malo" 
                            value="Malo"
                        />
                        <label for="calificacion_malo" class="text-sm cursor-pointer">Malo</label>
                    </div>
                </div>
                <span v-if="form.errors.calificacion_servicio" class="text-xs italic text-red-500">
                    {{ form.errors.calificacion_servicio }}
                </span>
            </div>

            <!-- POSICIÓN DE LOS INSTRUMENTOS AL CONCLUIR EL SERVICIO Section -->
            <div class="border-t pt-4">
                <h3 class="text-base font-bold text-gray-800 dark:text-gray-200 mb-4">
                    POSICIÓN DE LOS INSTRUMENTOS AL CONCLUIR EL SERVICIO
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <!-- Control -->
                    <div class="flex flex-col gap-2">
                        <label class="text-sm font-semibold text-gray-700 dark:text-gray-300 uppercase">
                            Control
                        </label>
                        <div class="flex flex-wrap gap-3">
                            <div class="flex items-center gap-2">
                                <RadioButton 
                                    v-model="form.control" 
                                    inputId="control_m" 
                                    value="M"
                                />
                                <label for="control_m" class="text-sm cursor-pointer">M</label>
                            </div>
                            <div class="flex items-center gap-2">
                                <RadioButton 
                                    v-model="form.control" 
                                    inputId="control_a" 
                                    value="A"
                                />
                                <label for="control_a" class="text-sm cursor-pointer">A</label>
                            </div>
                            <div class="flex items-center gap-2">
                                <RadioButton 
                                    v-model="form.control" 
                                    inputId="control_off" 
                                    value="OFF"
                                />
                                <label for="control_off" class="text-sm cursor-pointer">OFF</label>
                            </div>
                        </div>
                        <span v-if="form.errors.control" class="text-xs italic text-red-500">
                            {{ form.errors.control }}
                        </span>
                    </div>

                    <!-- Transferencia -->
                    <div class="flex flex-col gap-2">
                        <label class="text-sm font-semibold text-gray-700 dark:text-gray-300 uppercase">
                            Transferencia
                        </label>
                        <div class="flex flex-wrap gap-3">
                            <div class="flex items-center gap-2">
                                <RadioButton 
                                    v-model="form.transferencia" 
                                    inputId="transferencia_m" 
                                    value="M"
                                />
                                <label for="transferencia_m" class="text-sm cursor-pointer">M</label>
                            </div>
                            <div class="flex items-center gap-2">
                                <RadioButton 
                                    v-model="form.transferencia" 
                                    inputId="transferencia_a" 
                                    value="A"
                                />
                                <label for="transferencia_a" class="text-sm cursor-pointer">A</label>
                            </div>
                            <div class="flex items-center gap-2">
                                <RadioButton 
                                    v-model="form.transferencia" 
                                    inputId="transferencia_off" 
                                    value="OFF"
                                />
                                <label for="transferencia_off" class="text-sm cursor-pointer">OFF</label>
                            </div>
                        </div>
                        <span v-if="form.errors.transferencia" class="text-xs italic text-red-500">
                            {{ form.errors.transferencia }}
                        </span>
                    </div>

                    <!-- Posición cargador -->
                    <div class="flex flex-col gap-2">
                        <label class="text-sm font-semibold text-gray-700 dark:text-gray-300 uppercase">
                            Posición cargador
                        </label>
                        <div class="flex flex-wrap gap-3">
                            <div class="flex items-center gap-2">
                                <RadioButton 
                                    v-model="form.posicion_cargador" 
                                    inputId="cargador_on" 
                                    value="ON"
                                />
                                <label for="cargador_on" class="text-sm cursor-pointer">ON</label>
                            </div>
                            <div class="flex items-center gap-2">
                                <RadioButton 
                                    v-model="form.posicion_cargador" 
                                    inputId="cargador_off" 
                                    value="OFF"
                                />
                                <label for="cargador_off" class="text-sm cursor-pointer">OFF</label>
                            </div>
                        </div>
                        <span v-if="form.errors.posicion_cargador" class="text-xs italic text-red-500">
                            {{ form.errors.posicion_cargador }}
                        </span>
                    </div>

                    <!-- Totalizador -->
                    <div class="flex flex-col gap-2">
                        <label class="text-sm font-semibold text-gray-700 dark:text-gray-300 uppercase">
                            Totalizador
                        </label>
                        <div class="flex flex-wrap gap-3">
                            <div class="flex items-center gap-2">
                                <RadioButton 
                                    v-model="form.totalizador" 
                                    inputId="totalizador_on" 
                                    value="ON"
                                />
                                <label for="totalizador_on" class="text-sm cursor-pointer">ON</label>
                            </div>
                            <div class="flex items-center gap-2">
                                <RadioButton 
                                    v-model="form.totalizador" 
                                    inputId="totalizador_off" 
                                    value="OFF"
                                />
                                <label for="totalizador_off" class="text-sm cursor-pointer">OFF</label>
                            </div>
                        </div>
                        <span v-if="form.errors.totalizador" class="text-xs italic text-red-500">
                            {{ form.errors.totalizador }}
                        </span>
                    </div>

                    <!-- Precalentador posición -->
                    <div class="flex flex-col gap-2">
                        <label class="text-sm font-semibold text-gray-700 dark:text-gray-300 uppercase">
                            Precalentador posición
                        </label>
                        <div class="flex flex-wrap gap-3">
                            <div class="flex items-center gap-2">
                                <RadioButton 
                                    v-model="form.precalentador_posicion" 
                                    inputId="precalentador_on" 
                                    value="ON"
                                />
                                <label for="precalentador_on" class="text-sm cursor-pointer">ON</label>
                            </div>
                            <div class="flex items-center gap-2">
                                <RadioButton 
                                    v-model="form.precalentador_posicion" 
                                    inputId="precalentador_off" 
                                    value="OFF"
                                />
                                <label for="precalentador_off" class="text-sm cursor-pointer">OFF</label>
                            </div>
                        </div>
                        <span v-if="form.errors.precalentador_posicion" class="text-xs italic text-red-500">
                            {{ form.errors.precalentador_posicion }}
                        </span>
                    </div>
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
                        <label class="text-sm font-medium text-gray-700 dark:text-gray-300">
                            Foto uno despues
                        </label>
                        <FilePond
                            name="foto_uno_despues"
                            :files="myFilesFotoUnoDespues"
                            :allow-multiple="false"
                            accepted-file-types="image/*"
                            @updatefiles="updateFilesFotoUnoDespues"
                            :label-idle="'Arrastra y suelta tu archivo o <span class=\'filepond--label-action\'>Examinar</span>'"
                        />
                        <span v-if="form.errors.foto_uno_despues" class="text-xs italic text-red-500">
                            {{ form.errors.foto_uno_despues }}
                        </span>
                    </div>

                    <!-- Foto dos despues -->
                    <div class="flex flex-col gap-2">
                        <label class="text-sm font-medium text-gray-700 dark:text-gray-300">
                            Foto dos despues
                        </label>
                        <FilePond
                            name="foto_dos_despues"
                            :files="myFilesFotoDosDespues"
                            :allow-multiple="false"
                            accepted-file-types="image/*"
                            @updatefiles="updateFilesFotoDosDespues"
                            :label-idle="'Arrastra y suelta tu archivo o <span class=\'filepond--label-action\'>Examinar</span>'"
                        />
                        <span v-if="form.errors.foto_dos_despues" class="text-xs italic text-red-500">
                            {{ form.errors.foto_dos_despues }}
                        </span>
                    </div>

                    <!-- Foto tres despues -->
                    <div class="flex flex-col gap-2">
                        <label class="text-sm font-medium text-gray-700 dark:text-gray-300">
                            Foto tres despues
                        </label>
                        <FilePond
                            name="foto_tres_despues"
                            :files="myFilesFotoTresDespues"
                            :allow-multiple="false"
                            accepted-file-types="image/*"
                            @updatefiles="updateFilesFotoTresDespues"
                            :label-idle="'Arrastra y suelta tu archivo o <span class=\'filepond--label-action\'>Examinar</span>'"
                        />
                        <span v-if="form.errors.foto_tres_despues" class="text-xs italic text-red-500">
                            {{ form.errors.foto_tres_despues }}
                        </span>
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
                <Button 
                    type="button" 
                    label="Cancelar" 
                    severity="secondary" 
                    @click="emit('close')"
                    :disabled="form.processing"
                />
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
