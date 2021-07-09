import React from 'react'

import { useAppDispatch, useAppSelector } from '../../../../app/hooks'
import { makeStyles } from '@material-ui/core/styles'
import Paper from '@material-ui/core/Paper'
import Container from '@material-ui/core/Container'
import { useDrop } from 'react-dnd'
import { nanoid } from 'nanoid'

// local imports
import { allItemTypes } from '../../../../app/constants'
import { formEditorSelector, ADD_EDITOR_CURRENT_FORM_ITEM } from '../../../../app/slices/formEditorSlice'
import { FormPlaceHolderFactory } from '../../placeholders/FormPlaceholderFactory'
import { IFormItem } from '../../../../app/model'

const useStyles = makeStyles((theme) => ({
  dragDropZone: {
    // background: theme.palette.background.paper,
    border: (isOver) => (isOver ? 'dashed #CCC' : 'none'),
    // borderRadius: '0.5rem',
    // boxShadow: '5px 5px 10px #C0C0C0',
    height: '100%',
    // padding: '2rem',
    width: '100%',
  },
  paragraph: {
    color: '#FFF',
  },
  insideDragArea: {
    opacity: 0.7,
  },
}))

const DragDropZone: React.FC = () => {
  const dispatch = useAppDispatch()
  const { currentForm } = useAppSelector(formEditorSelector)

  // DnD hook
  const [{ isOver }, drop] = useDrop(
    () => ({
      accept: allItemTypes,
      drop: (item: IFormItem) => {
        dispatch(ADD_EDITOR_CURRENT_FORM_ITEM({
          ...item,
          id: nanoid(),
        }))
      },
      collect: (monitor) => ({
        isOver: !!monitor.isOver(),
      }),
    }),
    []
  )
  const classes = useStyles(isOver)
  const factory = new FormPlaceHolderFactory()
  const fields = currentForm.items ? currentForm.items.map((item, i) => {
    return factory.create({ item, index: i })
  }) : []


  return (
    <Paper ref={drop} className={classes.dragDropZone}>
      <Container>
        {fields}
      </Container>
    </Paper>
  )
}

export default DragDropZone
