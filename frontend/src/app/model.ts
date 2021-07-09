import { PlaceholderItemType, FormStatus } from './constants'
import { Day } from 'react-modern-calendar-datepicker'


export interface IFormsState {
  loading: boolean,
  hasErrors: boolean,
  forms: IForm[]
}

export interface IForm {
  disabledate?: Day,
  id: string,
  items: IFormItem[],
  multiplesubmits: boolean,
  status?: FormStatus,
  title: string,
}
export interface IFormItemCreator {
  item: IFormItem,
  index: number
}

export interface IFormItem {
  default_value?: string,
  form_id?: string,
  id: string,
  label_pos?: string,
  label?: string,
  name: string,
  order?: number,
  required: boolean,
  type: PlaceholderItemType,
}

/**
 * Interface: IFormEditorState
 *
 * Note: Derived state
 * The Editor keeps its own copy
 * of the current form's state, which does not
 * affect the real form state until it is saved
 */
export interface IFormEditorState {
  currentForm: IForm, // derived state on purpose
  dirty: boolean,
  hasErrors: boolean,
  loading: boolean,
  modalOpen: boolean,
  open: boolean,
}
