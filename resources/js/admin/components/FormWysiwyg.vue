<template>
  <div class="mb-6">
    <label v-if="label" :for="id" class="block text-sm font-medium text-charcoal-700 mb-2">
      {{ label }}
      <span v-if="required" class="text-red-500">*</span>
    </label>
    <div :id="editorId" class="border border-charcoal-300 rounded-lg bg-white" :style="{ minHeight: height }"></div>
    <textarea
      :id="id"
      :name="name"
      :value="modelValue"
      class="hidden"
      @input="$emit('update:modelValue', ($event.target as HTMLTextAreaElement).value)"
    ></textarea>
    <p v-if="error" class="mt-1.5 text-sm text-red-600">{{ error }}</p>
    <p v-if="hint && !error" class="mt-1.5 text-xs text-charcoal-500">{{ hint }}</p>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted, onBeforeUnmount, watch, type Ref } from 'vue';
import Quill from 'quill';
import 'quill/dist/quill.snow.css';
import { adminApi } from '../services/api';

interface Props {
  id: string;
  name?: string;
  modelValue?: string;
  label?: string;
  required?: boolean;
  error?: string;
  hint?: string;
  height?: string;
}

const props = withDefaults(defineProps<Props>(), {
  name: 'content',
  modelValue: '',
  label: '',
  required: false,
  error: '',
  hint: '',
  height: '300px',
});

const emit = defineEmits<{
  'update:modelValue': [value: string];
}>();

const editorId = ref<string>(`${props.id}_editor`);
const quillInstance: Ref<Quill | null> = ref<Quill | null>(null);
const hiddenTextarea = ref<HTMLTextAreaElement | null>(null);

const initQuill = (): void => {
  const editorElement = document.getElementById(editorId.value);
  hiddenTextarea.value = document.getElementById(props.id) as HTMLTextAreaElement | null;

  if (!editorElement || !hiddenTextarea.value) {
    return;
  }

  quillInstance.value = new Quill(editorElement, {
    theme: 'snow',
    modules: {
      toolbar: [
        [{ header: [1, 2, 3, 4, 5, 6, false] }],
        [{ font: [] }],
        [{ size: ['small', false, 'large', 'huge'] }],
        ['bold', 'italic', 'underline', 'strike', 'blockquote', 'code-block'],
        [{ script: 'sub' }, { script: 'super' }],
        [{ list: 'ordered' }, { list: 'bullet' }],
        [{ indent: '-1' }, { indent: '+1' }],
        [{ direction: 'rtl' }],
        [{ color: [] }, { background: [] }],
        [{ align: [] }],
        ['link', 'image', 'video'],
        ['clean']
      ],
      history: {
        delay: 1000,
        maxStack: 500,
        userOnly: true
      },
      clipboard: {
        matchVisual: false
      }
    }
  });

  // Set initial content
  if (props.modelValue && quillInstance.value) {
    quillInstance.value.clipboard.dangerouslyPasteHTML(props.modelValue);
  }

  // Handle image upload
  if (!quillInstance.value) return;
  const toolbar = quillInstance.value.getModule('toolbar');
  toolbar.addHandler('image', () => {
    const input = document.createElement('input');
    input.setAttribute('type', 'file');
    input.setAttribute('accept', 'image/*');
    input.click();

    input.onchange = async () => {
      const file = input.files?.[0];
      if (!file) return;

      try {
        const response = await adminApi.uploads.wysiwyg(file);
        if (response.data?.url && quillInstance.value) {
          const range = quillInstance.value.getSelection(true);
          if (range) {
            quillInstance.value.insertEmbed(range.index, 'image', response.data.url);
          }
        }
      } catch (error) {
        console.error('Error uploading image:', error);
        alert('Failed to upload image');
      }
    };
  });

  // Sync changes back to textarea
  if (quillInstance.value) {
    quillInstance.value.on('text-change', () => {
      const editor = editorElement.querySelector('.ql-editor') as HTMLElement;
      if (editor && hiddenTextarea.value && hiddenTextarea.value instanceof HTMLTextAreaElement) {
        const html = editor.innerHTML;
        hiddenTextarea.value.value = html;
        emit('update:modelValue', html);
      }
    });
  }
};

// Watch for external changes to modelValue (e.g., when loading data in edit mode)
watch(() => props.modelValue, (newValue) => {
  if (quillInstance.value) {
    const editorElement = document.getElementById(editorId.value);
    if (editorElement) {
      const editor = editorElement.querySelector('.ql-editor') as HTMLElement;
      if (editor) {
        const currentContent = editor.innerHTML;
        // Only update if the content is actually different to avoid infinite loops
        if (hiddenTextarea.value && hiddenTextarea.value instanceof HTMLTextAreaElement) {
          if (newValue !== currentContent && newValue !== hiddenTextarea.value.value) {
            if (quillInstance.value) {
              quillInstance.value.clipboard.dangerouslyPasteHTML(newValue || '');
            }
            hiddenTextarea.value.value = newValue || '';
          }
        }
      }
    }
  }
}, { immediate: false });

onMounted(() => {
  // Small delay to ensure DOM is ready
  setTimeout(() => {
    initQuill();
  }, 100);
});

onBeforeUnmount(() => {
  if (quillInstance.value) {
    quillInstance.value = null;
  }
});
</script>

<style>
.ql-editor {
  min-height: 300px;
}
</style>

