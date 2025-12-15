export type Option = { id: string | number | null; name: string };

/**
 * Centralized option lists for selects across the admin UI.
 */
export class ConstantOptions {
  static blogStatuses(): Option[] {
    return [
      { id: '', name: 'All statuses' },
      { id: 'draft', name: 'Draft' },
      { id: 'published', name: 'Published' },
      { id: 'archived', name: 'Archived' },
    ];
  }

  static cmsStatuses(): Option[] {
    return [
      { id: '', name: 'All statuses' },
      { id: 'draft', name: 'Draft' },
      { id: 'published', name: 'Published' },
    ];
  }

  static leadStatuses(): Option[] {
    return [
      { id: '', name: 'All statuses' },
      { id: 'new', name: 'New' },
      { id: 'contacted', name: 'Contacted' },
      { id: 'qualified', name: 'Qualified' },
      { id: 'converted', name: 'Converted' },
      { id: 'rejected', name: 'Rejected' },
    ];
  }

  private static activeInactive(): Option[] {
    return [
      { id: 'active', name: 'Active' },
      { id: 'inactive', name: 'Inactive' },
    ];
  }

  static productStatuses(): Option[] {
    return this.activeInactive();
  }

  static partnerStatuses(): Option[] {
    return this.activeInactive();
  }

  static formStatuses(): Option[] {
    return this.activeInactive();
  }

  static formTypes(): Option[] {
    return [
      { id: 'pre_form', name: 'Pre Form' },
      { id: 'post_form', name: 'Post Form' },
    ];
  }

  static formInputTypes(): Option[] {
    return [
      { id: 'text', name: 'Text' },
      { id: 'textarea', name: 'Textarea' },
      { id: 'dropdown', name: 'Dropdown' },
      { id: 'checkbox', name: 'Checkbox' },
    ];
  }

  static attributeDataTypes(): Option[] {
    return [
      { id: 'text', name: 'Text' },
      { id: 'number', name: 'Number' },
      { id: 'percentage', name: 'Percentage' },
      { id: 'boolean', name: 'Boolean' },
      { id: 'json', name: 'JSON' },
    ];
  }

  static activityLogNames(): Option[] {
    return [
      { id: '', name: 'All logs' },
      { id: 'products', name: 'Products' },
      { id: 'partners', name: 'Partners' },
      { id: 'users', name: 'Users' },
      { id: 'leads', name: 'Leads' },
      { id: 'forms', name: 'Forms' },
      { id: 'blogs', name: 'Blogs' },
    ];
  }
}


