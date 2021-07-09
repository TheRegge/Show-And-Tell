import React from 'react'

// material-ui
import { makeStyles } from '@material-ui/core/styles'

// local imports
import useDraggable from '../../../app/hooks/useDraggable'
import { IFormItem } from '../../../app/model'

// CSS Styles function with parameter
const useStyles = makeStyles((theme) => ({
  root: {
    background: (isDragging) => (isDragging ? '#ccc' : '#333'),
    borderRadius: '0.3em',
    color: '#fff',
    cursor: 'move',
    display: 'flex',
    fontSize: '0.9rem',
    margin: '1rem',
    padding: 0,
    width: '250px',
  },
  icon: {
    background: 'rgba(0, 0, 0, 0.25)',
    padding: '1em',
    textAlign: 'center',
    width: '20px',
  },
  body: {
    padding: '1em',
  },
}))

type Props = {
  formItem: IFormItem
}
const Draggable: React.FC<Props> = ({ formItem }) => {
  const { drag, isDragging } = useDraggable(formItem)
  const classes = useStyles(isDragging)

  return (
    <div ref={drag} className={classes.root}>
      <div className={classes.icon}>*</div>
      <div className={classes.body}>{formItem.name}</div>
    </div>
  )
}

export default Draggable
