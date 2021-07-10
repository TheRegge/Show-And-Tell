import React from 'react'

import { useAppDispatch } from '../app/hooks'
import { IForm, IFormItem } from '../app/model'

import Button from './ui/Button'
import Plus from './ui/iconsvg/Plus'
import {
  OPEN_FORM_EDITOR,
  SET_EDITOR_CURRENT_FORM,
  SET_EDITOR_DIRTY,
} from '../app/slices/formEditorSlice'
import { FormStatus } from '../app/constants'

const NewFormButton: React.FC = () => {
  const dispatch = useAppDispatch()

  const handleClick = () => {
    const newForm: IForm = {
      id: '',
      items: [] as IFormItem[],
      multiplesubmits: false,
      status: FormStatus.FORM_STATUS_DRAFT,
      title: 'Untitled Form'
    }
    dispatch(SET_EDITOR_CURRENT_FORM(newForm))
    dispatch(SET_EDITOR_DIRTY())
    dispatch(OPEN_FORM_EDITOR())
  }
  const icon = <Plus size={18} />

  return (
    <Button
      callback={handleClick}
      text={window.sat_textstrings['New Form']}
      ariaLabel={window.sat_textstrings['New Form']}
      icon={icon}
      classes="text-xl"
      variant="primary"
    />
  )
}

export default NewFormButton
