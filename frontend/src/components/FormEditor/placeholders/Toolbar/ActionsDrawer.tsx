import React, { useState } from 'react'
import { ActionCreatorWithPayload } from '@reduxjs/toolkit'

// Material-ui
import { makeStyles } from '@material-ui/core/styles'
import SettingsIcon from '@material-ui/icons/Settings'

// local imports
import DeleteButton from '../../../DeleteButton'
import Grow from '@material-ui/core/Grow'
import IconButton from '@material-ui/core/IconButton'
import SortButtons from './SortButtons'

const useStyles = makeStyles((theme) => ({
  root: {
    display: 'flex',
    flexDirection: 'row',
  },
  actions: {
    display: 'flex',
    flexDirection: 'row',
    justifyContent: 'flex-end',
    alignItems: 'center',
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

const ActionsDrawer: React.FC<Props> = ({ index, dispatchers }) => {
  const [active, setActive] = useState(false)
  const classes = useStyles()
  const { MOVE_EDITOR_CURRENT_FORM_ITEM, REMOVE_EDITOR_CURRENT_FORM_ITEM } = dispatchers

  const openDrawer = () => {
    setActive(true)
  }

  const closeDrawer = () => {
    setActive(false)
  }

  return (
    <div className={classes.root} onMouseLeave={closeDrawer}>
      <div className={classes.actions}>
        <Grow
          in={active}
          style={{ transformOrigin: '300% 50% 0' }}
          {...(active ? { timeout: 1000 } : {})}
        >
          <DeleteButton identifier={index} dispatcher={REMOVE_EDITOR_CURRENT_FORM_ITEM} />
        </Grow>
        <Grow in={active} style={{ transformOrigin: '300% 50% 0' }}>
          <SortButtons index={index} dispatcher={MOVE_EDITOR_CURRENT_FORM_ITEM} />
        </Grow>
      </div>
      <IconButton
        onMouseEnter={openDrawer}
        aria-label="Field actions"
      >
        <SettingsIcon />
      </IconButton>
    </div>
  )
}

export default ActionsDrawer
