import React from 'react'
import * as _ from 'underscore'
import 'react-modern-calendar-datepicker/lib/DatePicker.css'
import DatePicker, { Day } from 'react-modern-calendar-datepicker'

import {
  SET_EDITOR_CURRENT_FORM_DISABLE_DATE,
  SET_EDITOR_CURRENT_FORM_STATUS,
  SET_EDITOR_CURRENT_FORM_TITLE,
  TOGGLE_EDITOR_CURRENT_FORM_MULTIPLE_SUBMITS,
} from '../../app/slices/formEditorSlice'
import { FormStatus } from '../../app/constants'
import { useAppDispatch, useAppSelector } from '../../app/hooks'
import LargeSwitch from '../ui/LargeSwitch'
import { titleCase } from '../../app/utils'
import { formEditorSelector } from '../../app/slices/formEditorSlice'


const FormSettings: React.FC = () => {
  const { currentForm } = useAppSelector(formEditorSelector)
  const dispatch = useAppDispatch()

  const handleMultipleSubmitsChange = (event: React.ChangeEvent<HTMLInputElement>) => {
    dispatch(TOGGLE_EDITOR_CURRENT_FORM_MULTIPLE_SUBMITS())
  }

  const handleFormTitleChange = (event: React.ChangeEvent<HTMLInputElement>) => {
    dispatch(SET_EDITOR_CURRENT_FORM_TITLE(event.target.value))
  }

  const handleFormTitleChangeThrottled = _.throttle(handleFormTitleChange, 200)

  const handleFormTitleOnBlur = () => {
    dispatch(SET_EDITOR_CURRENT_FORM_TITLE(currentForm.title.trim()))
  }

  const handleFormDisabledateChange = (day: Day) => {
    dispatch(SET_EDITOR_CURRENT_FORM_DISABLE_DATE(day))
  }

  const handleStatusChange = (event: React.ChangeEvent<HTMLInputElement>) => {
    const status = currentForm.status === FormStatus.FORM_STATUS_DRAFT ?
      FormStatus.FORM_STATUS_PUBLISHED : FormStatus.FORM_STATUS_DRAFT
    dispatch(SET_EDITOR_CURRENT_FORM_STATUS(status))
  }


  if (currentForm) {
    return (
      <form noValidate autoComplete="off">

        <div className="border-b-2 py-8">
          <LargeSwitch
            checked={currentForm.status === FormStatus.FORM_STATUS_PUBLISHED}
            onChange={handleStatusChange}
            aria-label="Toggle form status"
            id="formStatusCheckbox"
            label={currentForm.status ? titleCase(currentForm.status) : titleCase(FormStatus.FORM_STATUS_DRAFT)}
          />
        </div>


        <div className="border-b-2 py-8">
          <label className="block" htmlFor="title">
            <span>{window.sat_textstrings['Form Title']}</span>
            <input
              className="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
              type="text"
              onChange={handleFormTitleChangeThrottled}
              onBlur={handleFormTitleOnBlur}
              name="title"
              id="title"
              value={currentForm.title}
            />
          </label>
        </div>


        <div className="border-b-2 py-8">
          <label className="flex items-center space-x-2">
            <input
              className="form-checkbox text-color-indigo-500"
              type="checkbox"
              checked={currentForm.multiplesubmits}
              onChange={handleMultipleSubmitsChange}
              name="manysubmits"
            />
            <span className="ml-2">{window.sat_textstrings['Accepts multiple submissions']}</span>
          </label>

        </div>


        <div className="border-b-2 py-8">
          <div style={{ maxWidth: '250px' }}>
            <label htmlFor="disabledate">{window.sat_textstrings['Disable form after this date']}</label>
            {/* <input
          style={{ width: '250px' }}
          type="date"
          onChange={handleFormDisabledateChange}
          name="disabledate"
          id="disabledate"
          value={form.formDisabledate}
        /> */}
            <DatePicker
              value={currentForm.disabledate}
              onChange={handleFormDisabledateChange}
              inputPlaceholder={window.sat_textstrings['Select a day']}
              shouldHighlightWeekends
            />
          </div>
        </div>

      </form >
    )
  }
  return <p>{window.sat_textstrings['No form was selected']}</p>
}

export default FormSettings
