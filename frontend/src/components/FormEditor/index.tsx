import React from 'react'
import { useAppSelector } from '../../app/hooks'

// material-ui
import { createMuiTheme, ThemeProvider } from '@material-ui/core/styles'
// import { Container } from '@material-ui/core'
import Dialog from '@material-ui/core/Dialog'

// Local imports
import FormEditorTabs from './FormEditorTabs'

const customTheme = createMuiTheme({
  overrides: {
    MuiDialog: {
      root: {
        zIndex: '100000 !important' as any,
      },
      container: {
        background: '#F3F3F3'
      }
    }
  },
})

const FormEditor: React.FC = () => {
  const { open } = useAppSelector((state) => state.formEditor)

  return (
    <ThemeProvider theme={customTheme}>
      <Dialog fullScreen open={open}>
        <FormEditorTabs />
      </Dialog>
    </ThemeProvider>

  )
}

export default FormEditor
