export enum PlaceholderItemType {
  FORM_PLACEHOLDER_SINGLELINETEXT = 'placeholder_single_line_text',
  FORM_PLACEHOLDER_SINGLECHECKBOX = 'placeholder_single_checkbox',
  FORM_PLACEHOLDER_PARAGRAPH = 'placeholder_paragraph',
}

export enum FormItemType {
  FORM_INPUT_TEXT = 'form_input_text',
  FORM_INPUT_CHECKBOX = 'form_input_checkbox',
  FORM_INPUT_TEXTAREA = 'form_input_textarea',
}

export enum FormStatus {
  FORM_STATUS_PUBLISHED = 'published',
  FORM_STATUS_DRAFT = 'draft',
  FORM_STATUS_DISABLED = 'disabled',
  FORM_STATUS_DELETED = 'deleted'
}

export const allItemTypes = [
  ...Object.values(PlaceholderItemType),
  ...Object.values(FormItemType),
]

export enum StringBoolean {
  STRING_BOOL_TRUE = '1',
  STRING_BOOL_FALSE = '0',
}
