import React from 'react'

import { useAppDispatch } from '../app/hooks'

import Trash from './ui/iconsvg/Trash'



type Props = {
  /**
   * Identifier can be a database id or a list index
   */
  identifier: any,
  dispatcher: any
}
const DeleteButton: React.FC<Props> = (props) => {
  const { identifier, dispatcher } = props
  const dispatch = useAppDispatch()

  const removeItem = (identifier: number) => {
    dispatch(dispatcher(identifier))
  }

  return (
    <div className="p-2 m-1 transition-bg duration-100 hover:bg-gray-100 rounded-full cursor-pointer" {...props}>
      <div
        onClick={() => removeItem(identifier)}
        aria-label="delete"
      >
        <Trash size={20} />
      </div>
    </div>
  )
}

export default DeleteButton
