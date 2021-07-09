import { createSlice, PayloadAction } from '@reduxjs/toolkit'
import { timestampStringToDayObject } from '../utils/dates'
// Local imports
import { RootState, AppDispatch } from '../store'
import formsAPI from '../formsAPI'
import { IFormsState } from '../model'

// First create Thunk for asynch data fetch
// export const fetchFormsByUserId = createAsyncThunk(
//   'forms/fetchByUserId',
//   async (userId, thunkAPI) => {
//     const response = await formsAPI.fetchByUserId(userId)
//     return response.data
//   }
// )

// TODO: Define in interface for the form
// type to use in forms: Array<T>

const initialState: IFormsState = {
  loading: false,
  hasErrors: false,
  forms: []
}

const formsSlice = createSlice({
  name: 'forms',
  initialState,
  reducers: {
    // Syncro reducers here
    DELETE_FORM: (state) => {
      state.loading = true
    },

    DELETE_FORM_SUCCESS: (state, action: PayloadAction<string>) => {
      const deletedId = action.payload
      state.forms = state.forms.filter(form => {
        return form.id !== deletedId
      })
      state.loading = false
      state.hasErrors = false
    },

    DELETE_FORM_FAILURE: (state) => {
      // TODO: Maybe pass an action payload with the error in it?
      state.loading = false
      state.hasErrors = true
    },

    GET_FORMS: (state) => {
      state.loading = true
    },

    GET_FORMS_SUCCESS: (state, action: PayloadAction<Array<any>>) => {
      state.forms = action.payload
      state.loading = false
      state.hasErrors = false
    },

    GET_FORMS_FAILURE: (state) => {
      // TODO: Maybe pass an action payload with the error in it?
      state.loading = false
      state.hasErrors = true
    },
  }
})

// Actions generated from the slice
export const {
  DELETE_FORM,
  DELETE_FORM_FAILURE,
  DELETE_FORM_SUCCESS,
  GET_FORMS,
  GET_FORMS_FAILURE,
  GET_FORMS_SUCCESS,
} = formsSlice.actions

// Selectors
export const formsSelector = (state: RootState) => state.forms

// The reducer
export default formsSlice.reducer

// Asynchronous thunk action
export function fetchForms() {
  return async (dispatch: AppDispatch) => {
    dispatch(GET_FORMS())

    try {
      const forms = await formsAPI.getAllForms()

      const formattedData = forms.map((form: any) => ({
        disabledate: (form.disabledate === '0000-00-00 00:00:00' || form.disabledate === null)
          ? null
          : timestampStringToDayObject(form.disabledate),
        id: form.form_id,
        multiplesubmits: form.manysubmits === '0' ? false : true,
        status: form.status,
        title: form.title,
        userId: form.user_id,
        items: form.items || [],
      }))
      dispatch(GET_FORMS_SUCCESS(formattedData))
    } catch (error) {
      dispatch(GET_FORMS_FAILURE())
    }
  }
}

export function deleteForm(formId: string) {
  return async (dispatch: AppDispatch) => {
    dispatch(DELETE_FORM())

    try {
      const result = await formsAPI.deleteForm(formId)
      dispatch(DELETE_FORM_SUCCESS(result.item.form_id))
    } catch (error) {
      dispatch(DELETE_FORM_FAILURE())
    }
  }
}
