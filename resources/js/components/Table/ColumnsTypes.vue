<template>
  <div class="text-sm ">
    <span v-if="data == null">
      <span class="text-gray-500 dark:text-gray-400 italic">Sin datos</span>
    </span>
    <div v-else>
      <span class="text-left w-full" v-if="type == 'currency'">
        {{ Array.isArray(data) ? 'Sin datos' : currencyFormat(typeof data === "string" ? parseFloat(data) : data) }}
      </span>
      <span v-else-if="type == 'decimal'">
        {{ Number(data).toFixed(2) }}
      </span>
      <span v-else-if="type == 'date'">
        {{ formatDate(String(data)) }}
      </span>
      <span v-else-if="type == 'dateTime'">
        {{ formatDateTime(String(data)) }}
      </span>
      <span v-else-if="type == 'image'">

        <div class="card flex justify-center">
          <Image  alt="Image" preview>
            <template #previewicon>
              <i class="pi pi-camera"></i>
            </template>
            <template #image>
              <img :src="'/storage/' + String(data)" alt="image" class="rounded-lg p-2 bg-gray-200 size-12 object-cover" />
            </template>
            <template #preview="slotProps">
              <img :src="'/storage/' + String(data)" alt="preview" :style="slotProps.style"
                @click="slotProps.onClick" />
            </template>
          </Image>
        </div>
      </span>


      <a v-else-if="type == 'document'" :href="String(data)" target="_blank" rel="noopener noreferrer"
        class="text-blue-500 italic">Ver Documento</a>
      <span v-else-if="type == 'tag'">
        <Tag v-if="column?.tags && column.tags.find(tag => tag.value === data)"
          :severity="column.tags.find(tag => tag.value === data)?.severity"
          :value="column.tags.find(tag => tag.value === data)?.label"></Tag>
        <span v-else class="text-gray-500 text-xs italic">Sin etiqueta</span>
      </span>
      <div v-else-if="type == 'roles'" class="flex flex-wrap gap-1">
        <span v-if="Array.isArray(data) && data.length > 0">
          <span v-for="role in data" :key="role.id"
            class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
            {{ role.name }}
          </span>
        </span>
        <span v-else class="text-gray-500 text-xs italic">Sin roles</span>
      </div>
      <span v-else>
        {{ data }}
      </span>
    </div>
  </div>
</template>
<script setup lang="ts">
import { useCommonUtilities } from "@/composables/useCommonUtilities";
import { Image } from "primevue";

const { currencyFormat, formatDate, formatDateTime } = useCommonUtilities();

interface TagType {
  value: string | number;
  label: string;
  severity: string;
}

interface ColumnType {
  tags?: TagType[];
  [key: string]: any;
}

interface props {
  data: number | string | any[];
  type?: string;
  column?: ColumnType;
}
const props = defineProps<props>();
</script>
