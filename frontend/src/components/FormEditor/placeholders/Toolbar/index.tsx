import React from 'react'
import { ActionCreatorWithPayload } from '@reduxjs/toolkit'
// Material-ui
import { makeStyles } from '@material-ui/core/styles'

// Local imports
import ActionsDrawer from './ActionsDrawer'

const useStyles = makeStyles((theme) => ({
  root: {
    backgroundColor: 'transparent',
    display: 'flex',
    flexDirection: 'row',
    justifyContent: 'flex-end',
  },
}))

type Props = {
  index: number,
  dispatchers: {
    MOVE_EDITOR_CURRENT_FORM_ITEM: ActionCreatorWithPayload<{
      fromIndex: number;
      toIndex: number;
    }, string>;
    REMOVE_EDITOR_CURRENT_FORM_ITEM: ActionCreatorWithPayload<number, string>;
  }
}
const Toolbar: React.FC<Props> = ({ index, dispatchers }) => {
  const classes = useStyles()

  return (
    <div className={classes.root}>
      <ActionsDrawer index={index} dispatchers={dispatchers} />
    </div>
  )
}

export default Toolbar
