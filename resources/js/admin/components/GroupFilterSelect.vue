<template>
  <select
    v-model="internalValue"
    class="min-w-[200px] px-4 py-2 border border-charcoal-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-white text-charcoal-900"
  >
    <option value="">All groups</option>
    <option v-for="group in options" :key="group.value" :value="String(group.value)">
      {{ group.label }}
    </option>
  </select>
</template>

<script setup lang="ts">
import { computed, onMounted } from 'vue';
import { useGroupsStore } from '../stores';

const props = withDefaults(
  defineProps<{
    modelValue: string;
  }>(),
  { modelValue: '' }
);

const emit = defineEmits<{
  'update:modelValue': [value: string];
}>();

const groupsStore = useGroupsStore();

const options = computed(() =>
  groupsStore.items.map((g: any) => ({
    value: g.id,
    label: g.name,
  }))
);

const internalValue = computed({
  get: () => props.modelValue,
  set: (val: string) => emit('update:modelValue', val),
});

onMounted(async () => {
  if (!groupsStore.items.length) {
    await groupsStore.fetchItems({ per_page: 1000, sort: 'name', dir: 'asc' }).catch(() => {});
  }
});
</script>


