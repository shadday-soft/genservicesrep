<script setup lang="ts">
const props = defineProps({
  width: {
    type: String,
    default: "60rem",
  },
  footer: {
    type: Boolean,
    default: true,
  },
  maximizable: {
    type: Boolean,
    default: false,
  },
  closable: {
    type: Boolean,
    default: true,
  },
  closeOnEscape: {
    type: Boolean,
    default: true,
  },
  icon: {
    type: String,
    default: null,
  },
  title: {
    type: String,
    default: null,
  },
  baseZIndex: {
    type: Number,
    default: null,
  },
  autoZIndex: {
    type: Boolean,
    default: true,
  },
  severity: {
    type: String,
    default: "primary",
  },
  modal: {
    type: Boolean,
    default: true,
  },
});

const visible = defineModel({
  default: false,
});
</script>

<template>
  <Dialog
    pt:mask:class="backdrop-blur-sm"
    v-model:visible="visible"
    :maximizable="maximizable"
    :modal
    :closable
    :closeOnEscape
    :autoZIndex
    :baseZIndex
    :style="{ width: props.width }"
    :pt="{
      header: { class: '!h-10'},
      headerActions: { class: '!text-white' },
      content: { class: '!pt-2' },
    }"
  >
    <template #header>
      <div class="flex items-center space-x-2 ">
        <i v-if="icon" :class="icon" />
        <slot v-else name="icon" />
        <span v-if="title" class="text-xl font-bold truncate">
          {{ title }}
        </span>
        <slot v-else name="title" />
      </div>
    </template>

    <template #default>
      <div class="h-full">
        <slot />
      </div>
    </template>
    <template #footer v-if="footer">
      <slot name="footer" />
    </template>
    <template #maximizeicon="{ maximized }">
      <i :class="maximized ? 'fa-compress' : 'fa-expand'" class="text-white fa-solid"></i>
    </template>
  </Dialog>
</template>
