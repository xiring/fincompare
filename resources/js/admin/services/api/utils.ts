/**
 * API Utility Functions
 */

/**
 * Convert object to FormData, handling file fields and JSON fields
 */
export function toFormData(
  data: Record<string, any>,
  fileFields: string[] = [],
  jsonFields: string[] = []
): FormData {
  const formData = new FormData();

  Object.keys(data).forEach((key) => {
    const value = data[key];

    // Skip null/undefined values
    if (value === null || value === undefined) {
      return;
    }

    // Handle file fields
    if (fileFields.includes(key) && value instanceof File) {
      formData.append(key, value);
      return;
    }

    // Handle JSON fields (arrays, objects)
    if (jsonFields.includes(key)) {
      if (Array.isArray(value) || typeof value === 'object') {
        formData.append(key, JSON.stringify(value));
        return;
      }
    }

    // Handle arrays (not JSON fields)
    if (Array.isArray(value)) {
      value.forEach((item, index) => {
        formData.append(`${key}[${index}]`, item);
      });
      return;
    }

    // Handle objects (not JSON fields)
    if (typeof value === 'object' && !(value instanceof File)) {
      Object.keys(value).forEach((subKey) => {
        formData.append(`${key}[${subKey}]`, value[subKey]);
      });
      return;
    }

    // Handle primitive values
    formData.append(key, value.toString());
  });

  return formData;
}

