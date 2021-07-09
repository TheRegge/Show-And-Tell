import React from 'react'

// Redux
import { useDispatch } from 'react-redux'
import { ActionCreatorWithPayload } from '@reduxjs/toolkit'

// Material-ui
import { makeStyles } from '@material-ui/core/styles'
import ArrowUpwardIcon from '@material-ui/icons/ArrowUpward'
import ArrowDownwardIcon from '@material-ui/icons/ArrowDownward'
import IconButton from '@material-ui/core/IconButton'

const useStyles = makeStyles((theme) => ({
  root: {
    display: 'flex',
    flexDirection: 'row',
  },
}))

type Props = {
  index: number,
  dispatcher: ActionCreatorWithPayload<{ fromIndex: number, toIndex: number }, string>
}
const SortButtons: React.FC<Props> = (props: Props) => {
  const { index, dispatcher } = props
  const dispatch = useDispatch()
  const classes = useStyles()

  const moveItem = (fromIndex: number, toIndex: number) => {
    dispatch(dispatcher({ fromIndex, toIndex }))
  }

  return (
    <div className={classes.root} {...props}>
      <IconButton
        onClick={() => moveItem(index, index - 1)}
        aria-label="move up"
        size="small"
      >
        <ArrowUpwardIcon fontSize="small" color="action" />
      </IconButton>
      <IconButton
        onClick={() => moveItem(index, index + 1)}
        aria-label="move down"
        size="small"
      >
        <ArrowDownwardIcon fontSize="small" color="action" />
      </IconButton>
    </div>
  )
}

export default SortButtons
