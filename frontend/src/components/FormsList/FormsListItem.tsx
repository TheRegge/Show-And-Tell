import React from 'react'

// Redux
import { useAppDispatch } from '../../app/hooks'

// Local imports
import { OPEN_FORM_EDITOR, SET_EDITOR_CURRENT_FORM } from '../../app/slices/formEditorSlice'
import { IForm } from '../../app/model'
import { deleteForm } from '../../app/slices/formsSlice'
import DeleteButton from '../DeleteButton'
import Settings from '../ui/iconsvg/Settings'

type Props = {
  /** The form containing the form settings and list items */
  form: IForm
}

const FormsListItem: React.FC<Props> = ({ form }) => {
  const dispatch = useAppDispatch()
  const handleClick = (form: IForm) => {
    dispatch(SET_EDITOR_CURRENT_FORM(form))
    dispatch(OPEN_FORM_EDITOR())
  }
  return (
    <>
      <div className="flex flex-row px-2 py-6 justify-between border-b-2">
        <div className="flex flex-col justify-center px-4">
          <span className="text-base font-medium">{form.title}</span>
          <span>status: {form.status}</span>
        </div>
        <div className="flex items-center">

          <DeleteButton
            identifier={form.id}
            dispatcher={deleteForm}
          />

          <div
            className="p-1"
            aria-label="edit form"
            onClick={() => handleClick(form)}
          >
            <Settings />
          </div>
        </div>
      </div>
    </>
  )
}

export default FormsListItem
