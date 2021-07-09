import { configureStore } from '@reduxjs/toolkit'
import formsReducer from './slices/formsSlice'
import formEditorReducer from './slices/formEditorSlice'

export const store = configureStore({
  reducer: {
    forms: formsReducer,
    formEditor: formEditorReducer,
  },
})

// Infer the `RootState` and `AppDispatch` types from the store itself
export type RootState = ReturnType<typeof store.getState>
// Inferred types: {forms: FormsState, formEditor: IFormEditorState, etc. }
export type AppDispatch = typeof store.dispatch
