import { createSlice, PayloadAction } from '@reduxjs/toolkit'

import { Day } from 'react-modern-calendar-datepicker'

// local imports
import { AppDispatch, RootState } from '../store'
import { IFormEditorState, IForm, IFormItem } from '../model'
import { FormStatus } from '../constants'
import formsAPI from '../formsAPI'

const initialState: IFormEditorState = {
  currentForm: {} as IForm,
  dirty: false,
  hasErrors: false,
  loading: false,
  modalOpen: false,
  open: false,
}

const formEditorSlice = createSlice({
  name: 'formEditor',
  initialState,
  reducers: {
    CLOSE_FORM_EDITOR: (state) => {
      // @TODO: Create close editor logic
      // state.dirty = initialState.dirty
      state.currentForm = {} as IForm
      state.open = false
      state.dirty = false
    },

    EDIT_EDITOR_CURRENT_FORM_ITEM: (state, action: PayloadAction<IFormItem>) => {
      // TODO: implement

      // const editItem = { ...action.payload }
      // const newFormItems = state.formItems.map(item => {
      //   if (item.id === editItem.id) {
      //     return editItem
      //   }
      //   return item
      // })
      // state.formItems = newFormItems;
    },

    OPEN_FORM_EDITOR: (state) => {
      state.open = true
    },

    MOVE_EDITOR_CURRENT_FORM_ITEM: (state, action: PayloadAction<{ fromIndex: number, toIndex: number }>) => {
      const { fromIndex, toIndex } = action.payload
      if (toIndex >= 0 && toIndex < state.currentForm.items.length) {
        // remove the item from the list
        const item = state.currentForm.items.splice(fromIndex, 1)[0]
        // and place the item at the new index
        state.currentForm.items.splice(toIndex, 0, item)
        state.dirty = true
      }
    },

    REMOVE_EDITOR_CURRENT_FORM_ITEM: (state, action: PayloadAction<number>) => {
      state.currentForm.items.splice(action.payload, 1)
      state.dirty = true
    },

    SAVE_EDITOR_CURRENT_FORM: (state) => {
      state.loading = true
    },

    SAVE_EDITOR_CURRENT_FORM_SUCCESS: (state) => {
      state.loading = false
    },

    SAVE_EDITOR_CURRENT_FORM_FAILURE: (state) => {
      state.loading = false
      state.hasErrors = true
    },

    SET_EDITOR_CURRENT_FORM: (state, action: PayloadAction<IForm>) => {
      state.currentForm = action.payload
    },

    SET_EDITOR_CURRENT_FORM_DISABLE_DATE: (state, action: PayloadAction<Day>) => {
      state.currentForm.disabledate = action.payload
      state.dirty = true
    },

    SET_EDITOR_CURRENT_FORM_ID: (state, action: PayloadAction<string>) => {
      state.currentForm.id = action.payload
    },

    SET_EDITOR_CURRENT_FORM_ITEMS: (state, action: PayloadAction<IFormItem[]>) => {
      state.currentForm.items = [...action.payload]
    },

    ADD_EDITOR_CURRENT_FORM_ITEM: (state, action: PayloadAction<IFormItem>) => {
      state.currentForm.items.push(action.payload)
      state.dirty = true
    },

    SET_EDITOR_CURRENT_FORM_STATUS: (state, action: PayloadAction<FormStatus>) => {
      state.currentForm.status = action.payload
      state.dirty = true
    },

    SET_EDITOR_CURRENT_FORM_TITLE: (state, action: PayloadAction<string>) => {
      state.currentForm.title = action.payload
      state.dirty = true
    },

    SET_EDITOR_DIRTY: (state) => {
      state.dirty = true
    },

    SET_EDITOR_CLEAN: (state) => {
      state.dirty = false
    },

    TOGGLE_EDITOR_CURRENT_FORM_MULTIPLE_SUBMITS: (state) => {
      state.currentForm.multiplesubmits = !state.currentForm.multiplesubmits
      state.dirty = true
    },

    TOGGLE_EDITOR_MODAL: (state) => {
      state.modalOpen = !state.modalOpen
    }
  },
})

// Actions generated from the slice
export const {
  ADD_EDITOR_CURRENT_FORM_ITEM,
  CLOSE_FORM_EDITOR,
  EDIT_EDITOR_CURRENT_FORM_ITEM,
  MOVE_EDITOR_CURRENT_FORM_ITEM,
  OPEN_FORM_EDITOR,
  REMOVE_EDITOR_CURRENT_FORM_ITEM,
  SAVE_EDITOR_CURRENT_FORM,
  SAVE_EDITOR_CURRENT_FORM_FAILURE,
  SAVE_EDITOR_CURRENT_FORM_SUCCESS,
  SET_EDITOR_CURRENT_FORM_DISABLE_DATE,
  SET_EDITOR_CURRENT_FORM_ITEMS,
  SET_EDITOR_CURRENT_FORM_STATUS,
  SET_EDITOR_CURRENT_FORM_TITLE,
  SET_EDITOR_CURRENT_FORM,
  SET_EDITOR_CURRENT_FORM_ID,
  SET_EDITOR_CLEAN,
  SET_EDITOR_DIRTY,
  TOGGLE_EDITOR_CURRENT_FORM_MULTIPLE_SUBMITS,
  TOGGLE_EDITOR_MODAL,
} = formEditorSlice.actions

// Selector(s)
export const formEditorSelector = (state: RootState) => state.formEditor

export function createForm(form: IForm) {
  return async (dispatch: AppDispatch) => {
    dispatch(SAVE_EDITOR_CURRENT_FORM())
    try {
      const { form_id } = await formsAPI.createForm(form)
      dispatch(SET_EDITOR_CURRENT_FORM_ID(form_id))
      dispatch(SET_EDITOR_CLEAN())
      dispatch(SAVE_EDITOR_CURRENT_FORM_SUCCESS())
    } catch (error) {
      // TODO: test error case(s)
      dispatch(SAVE_EDITOR_CURRENT_FORM_FAILURE())
    }
  }
}

export function updateForm(form: IForm) {
  return async (dispatch: AppDispatch) => {
    dispatch(SAVE_EDITOR_CURRENT_FORM())
    try {
      await formsAPI.updateForm(form)
      dispatch(SET_EDITOR_CLEAN())
      dispatch(SAVE_EDITOR_CURRENT_FORM_SUCCESS())
    } catch (error) {
      dispatch(SAVE_EDITOR_CURRENT_FORM_FAILURE)
    }
  }
}


// The reducer
export default formEditorSlice.reducer


