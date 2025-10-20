<template>
  <div class="flex flex-col gap-y-1">
    <label class="font-bold">{{ label }}</label>
    <InputNumber
      v-if="type == 'currency'"
      v-model="valueNumeric"
      :invalid="(error?.length ?? 0) > 0"
      :disabled
      mode="currency"
      currency="COP"
      locale="es-co"
      :minFractionDigits="2"
      fluid
    ></InputNumber>
    <InputNumber
      v-else-if="type == 'number'"
      :invalid="(error?.length ?? 0) > 0"
      :disabled
      :useGrouping="false"
      v-model="valueNumeric"
      fluid
    ></InputNumber>

    <Select
      filter
      :disabled
      :invalid="(error?.length ?? 0) > 0"
      v-else-if="type == 'select'"
      :placeholder
      @change="emit('select')"
      :optionLabel="optionLabel ?? 'label'"
      :optionValue="optionValue ?? 'value'"
      :options
      v-model="value"
      checkmark
    />

    <MultiSelect :disabled :invalid="(error?.length ?? 0) > 0" v-else-if="type == 'multiselect'" v-model="value" :options="options" :optionLabel="optionLabel ?? 'label'" :optionValue="optionValue ?? 'value'" filter  :placeholder display="chip"
    :maxSelectedLabels="3" selectedItemsLabel="{0} Selecciones" />

    <DatePicker
      v-else-if="type == 'date'"
      dateFormat="dd/mm/yy"
      :disabled
      v-model="valueDate"
      :invalid="(error?.length ?? 0) > 0"
      class="w-full"
      :placeholder
    ></DatePicker>
    
    <DatePicker
      v-else-if="type == 'dateRange'"
      dateFormat="dd/mm/yy"
      v-model="valueDateRange"
      class="w-full"
      :disabled
      :invalid="(error?.length ?? 0) > 0"
      selectionMode="range"
      :placeholder
    >
    </DatePicker>

    <DatePicker
      v-else-if="type == 'datetime'"
      dateFormat="dd/mm/yy"
      showTime hourFormat="24" fluid
      v-model="valueDatetime"
      class="w-full"
      :stepMinute="5"
      :showOnFocus="false"
      :showIcon="true"
      :disabled
      :invalid="(error?.length ?? 0) > 0"
      :placeholder
    ></DatePicker>

   

    <Textarea :disabled v-model="value" autoResize :rows="textAreaRows"  v-else-if="type == 'textarea'" />
    <Password v-model="value"  v-else-if="type == 'password'"  class="w-full" :pt="{
      pcInputText: {
        root: '!w-full '
      },
      root: '!w-full '

    }" />
    <InputText v-else v-model="value" class="w-full" :placeholder :invalid="(error?.length ?? 0) > 0" :disabled></InputText>

    <span class="text-xs italic text-red-500">{{ error }}</span>
  </div>
</template>
<script setup lang="ts">
import DatePicker from "primevue/datepicker";
import InputText from "primevue/inputtext";
import InputNumber from "primevue/inputnumber";
import Select from "primevue/select";
import { MultiSelect, Password, Textarea } from "primevue";
const emit= defineEmits(['select']);

interface Option {
  label?: string;
  value: string | number | boolean;
}

interface Props {
  label?: string;
  type?: string;
  placeholder?: string;
  options?: Option[] | any[];
  optionLabel?: string | 'label';
  optionValue?: string | 'value';
  error?: string;
  textAreaRows?: number;
  disabled?: boolean | false;
}

defineProps<Props>();

const value = defineModel({
  default: "",
});

const valueNumeric = defineModel("numeric", {
  default: 0,
});

const valueDate = defineModel("date", {
  default: new Date(),
});


const valueDatetime = defineModel("datetime", {
  default: new Date(),
});

const valueDateRange = defineModel("dateRange", {
  default: [new Date(), new Date()],
});

const valueBool = defineModel("bool", {
  default: true
})
</script>
