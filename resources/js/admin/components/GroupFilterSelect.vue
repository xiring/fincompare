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
import { computed } from 'vue';
import { useGroupListQuery } from '../queries/groups';

const props = withDefaults(
  defineProps<{
    modelValue: string;
  }>(),
  { modelValue: '' }
);

const emit = defineEmits<{
  'update:modelValue': [value: string];
}>();

const { data } = useGroupListQuery({ per_page: 1000, sort: 'name', dir: 'asc' });

const options = computed(() =>
  ((data.value?.items || []) as any[]).map((g: any) => ({
    value: g.id,
    label: g.name,
  }))
);

const internalValue = computed({
  get: () => props.modelValue,
  set: (val: string) => emit('update:modelValue', val),
});
</script>


