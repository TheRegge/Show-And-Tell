import React, { useEffect } from 'react'
import { useAppSelector, useAppDispatch } from '../../app/hooks'

// material-ui
import Skeleton from '@material-ui/lab/Skeleton'

// Local imports
import FormsListItem from './FormsListItem'
import { fetchForms, formsSelector } from '../../app/slices/formsSlice'

// TODO: maybe use react-window for long list optimization? https://github.com/bvaughn/react-window


const FormsList: React.FC = () => {
  const dispatch = useAppDispatch()
  const { forms, loading } = useAppSelector(formsSelector)

  useEffect(() => {
    dispatch(fetchForms())
  }, [dispatch])

  return (
    <div aria-label="forms" className="bg-white">
      {loading && (
        <div className="flex flex-column">
          <Skeleton component="div" variant="rect" height={76} />
          <br />
          <Skeleton component="div" variant="rect" height={76} />
          <br />
          <Skeleton component="div" variant="rect" height={76} />
        </div>
      )}
      {forms.map((form) => (
        <FormsListItem form={form} />
      ))}
    </div>
  )
}

export default FormsList
