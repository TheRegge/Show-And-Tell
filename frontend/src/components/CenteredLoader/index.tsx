import React from 'react'
import { makeStyles } from '@material-ui/core/styles'
import Box from '@material-ui/core/Box'
import CircularProgress from '@material-ui/core/CircularProgress'

const useStyles = makeStyles(() => ({
  root: {
    display: 'flex',
    justifyContent: 'center',
    alignItems: 'center',
    width: '100%',
    height: '100%',
  },
}))

const CenteredLoader: React.FC = () => {
  const classes = useStyles()

  return (
    <Box className={classes.root}>
      <CircularProgress />
    </Box>
  )
}

export default CenteredLoader
