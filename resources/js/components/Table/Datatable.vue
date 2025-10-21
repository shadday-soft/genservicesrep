<script setup lang="ts">
import DataTable from "primevue/datatable";
import Column from "primevue/column";
import type { Column as TypeColumn } from "@/types";
import { FilterMatchMode } from "@primevue/core/api";
import { ref } from "vue";
import Input from "@/components/Input.vue";
import ColumnsTypes from "@/components/Table/ColumnsTypes.vue";
import InputIcon from "primevue/inputicon";
import IconField from "primevue/iconfield";
import Button from "primevue/button";

interface props {
  data: unknown[];
  columns: TypeColumn[];
  defaultRows?: number;
  noShowHeader?: boolean;
}
const props = defineProps<props>();

const filters = ref<Record<string, { value: string; matchMode: string }>>({
  global: { value: "", matchMode: FilterMatchMode.CONTAINS },
});

const initializeFilters = () => {
  for (const column of props.columns) {
    if (column.filter) {
      filters.value[column.field] = {
        value: "",
        matchMode: FilterMatchMode.CONTAINS,
      };
    }
  }
};

initializeFilters();
</script>

<template>
  <DataTable v-model:filters="filters" :globalFilterFields="columns.map((column: TypeColumn) => column.field)"
    :value="data" resizableColumns columnResizeMode="expand" :paginator="false" filterDisplay="menu" scrollable
    scrollHeight="flex" removableSort :rows="defaultRows ?? 50" size="small">
    <template #empty>
      <div class="flex flex-col justify-center items-center py-4">
        <img src="/svg/undraw_empty.svg" alt="" class="w-1/2 h-[30vh]" />
        <p class="text-gray-500 italic dark:text-gray-100 text-sm">No hay datos disponibles</p>
      </div>
    </template>
    <template #header v-if="noShowHeader !== true">
      <div class="flex justify-between items-center">
        <IconField>
          <InputIcon>
            <i class="pi pi-search" />
          </InputIcon>
          <Input v-model="filters['global'].value" placeholder="Buscar..." />
        </IconField>
        <slot name="filtersHeader" />
        <slot name="addButton" />
      </div>
    </template>
    <span v-for="column in columns" :key="column.field">
      <Column :show-filter-match-modes="false" :sortable="column.sortable" :field="column.field" :pt="{
        headerContent: { class: '!h-6' },
        headerCell: { class: '!p-0.5' },
      }">
        <template #header>
          <p v-tooltip="`${column.header}`" class="text-sm capitalize font-bold truncate px-2 ">
            {{ column.header }}
          </p>
        </template>
        <template #filter="{ filterModel }" v-if="column.filter">
          <Input v-model="filterModel.value" type="text" placeholder="Buscar" />
        </template>
        <template #filterclear="{ filterCallback }">
          <Button type="button" size="small" text icon="pi pi-times" @click="filterCallback()"
            severity="danger"></Button>
        </template>
        <template #filterapply="{ filterCallback }">
          <Button type="button" text size="small" label="Aplicar" icon="pi pi-check" @click="filterCallback()"
            severity="success"></Button>
        </template>
        <template #body="{ data }">
          <ColumnsTypes :type="column.type" :column="column" :data="column.field.includes('.')
              ? column.field.split('.').reduce((acc, key) => acc?.[key], data)
              : data[column.field]
            "></ColumnsTypes>
        </template>
      </Column>
    </span>
    <Column frozen alignFrozen="right" class="">
      <template #body="{ data }">
        <slot name="actions" :data="data" />
      </template>
    </Column>
  </DataTable>
</template>
