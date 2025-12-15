<template>
  <div :class="['flex flex-col gap-2', dense ? '' : 'mb-6']">
    <label v-if="label" class="block text-sm font-medium text-charcoal-700" :class="dense ? '' : 'mb-1'" :for="id">
      {{ label }}
      <span v-if="required" class="text-red-500">*</span>
    </label>
    <FormSelect
      :id="id"
      v-model="internalValue"
      :options="options"
      option-value="value"
      option-label="label"
      :placeholder="placeholder"
      :error="error"
      :disabled="loading || disabled"
      :required="required"
    />
  </div>
</template>

<script setup lang="ts">
import { computed, onMounted } from 'vue';
import { useGroupsStore } from '../stores';
import FormSelect from './FormSelect.vue';

interface Props {
  id?: string;
  modelValue: string | number | null;
  label?: string;
  placeholder?: string;
  error?: string;
  required?: boolean;
  dense?: boolean;
  disabled?: boolean;
}

const props = withDefaults(defineProps<Props>(), {
  id: 'group_id',
  modelValue: null,
  label: '',
  placeholder: '-- Select Group --',
  error: '',
  required: false,
  dense: false,
  disabled: false,
});

const emit = defineEmits<{
  'update:modelValue': [value: string | number | null];
}>();

const groupsStore = useGroupsStore();
const loading = computed(() => groupsStore.loading);

const options = computed(() =>
  groupsStore.items.map((g: any) => ({
    value: g.id,
    label: g.name,
  }))
);

const internalValue = computed({
  get: () => props.modelValue,
  set: (val) => emit('update:modelValue', val),
});

onMounted(async () => {
  if (!groupsStore.items.length) {
    await groupsStore.fetchItems({ per_page: 1000, sort: 'name', dir: 'asc' }).catch(() => {});
  }
});
</script>


