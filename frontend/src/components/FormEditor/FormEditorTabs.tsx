import React from 'react'

// Material-ui
import { createMuiTheme, ThemeProvider } from '@material-ui/core/styles'
import { makeStyles, Theme } from '@material-ui/core/styles'
import AppBar from '@material-ui/core/AppBar'
import Box from '@material-ui/core/Box'
import Button from '@material-ui/core/Button'
import CloseOutlinedIcon from '@material-ui/icons/CloseOutlined'
import Container from '@material-ui/core/Container'
import Dialog from '@material-ui/core/Dialog'
import DialogActions from '@material-ui/core/DialogActions'
import DialogContent from '@material-ui/core/DialogContent'
import DialogTitle from '@material-ui/core/DialogTitle'
import IconButton from '@material-ui/core/IconButton'
import SaveOutlinedIcon from '@material-ui/icons/SaveOutlined'
import Tab from '@material-ui/core/Tab'
import Tabs from '@material-ui/core/Tabs'
import Typography from '@material-ui/core/Typography'

// Local imports
import FormBuilder from './FormBuilder'
import FormSettings from './FormSettings'
import {
  CLOSE_FORM_EDITOR,
  formEditorSelector,
  TOGGLE_EDITOR_MODAL,
  createForm,
  updateForm,
} from '../../app/slices/formEditorSlice'
import { fetchForms } from '../../app/slices/formsSlice'
import { useAppDispatch, useAppSelector } from '../../app/hooks'
interface TabPanelProps {
  children?: React.ReactNode
  index: any
  value: any
}

const customDialogTheme = createMuiTheme({
  overrides: {
    MuiDialog: {
      root: {
        zIndex: '100000 !important' as any,
      },
      container: {
        background: 'rgba(0, 0, 0, 0.2)'
      }
    }
  },
})

function TabPanel(props: TabPanelProps) {
  const { children, value, index, ...other } = props

  return (
    <div
      role="tabpanel"
      hidden={value !== index}
      id={`formeditor-tabpanel-${index}`}
      aria-labelledby={`formeditor-tab-${index}`}
      {...other}
    >
      {value === index && (
        <Box p={3}>
          <Typography>{children}</Typography>
        </Box>
      )}
    </div>
  )
}

function a11yProps(index: any) {
  return {
    id: `formeditor-tab-${index}`,
    'aria-controls': `formeditor-tabpanel-${index}`,
  }
}

const useStyles = makeStyles((theme: Theme) => ({
  root: {
    flexGrow: 1,
    backgroundColor: theme.palette.background.paper,
  },
  rightChild: {
    marginLeft: 'auto',
    marginRight: theme.spacing(2),
  },
  modalPaper: {
    position: 'absolute',
    width: 600,
    background: 'rgba(0, 0, 0, 0.1)',
    border: 'none',
    boxShadow: theme.shadows[3],
    padding: theme.spacing(2, 4, 3),
  },
  textPrimary: {
    color: 'white',
    '&:hover': {
      color: 'tomato'
    }
  }

}))

export default function FormEditorTabs() {
  const classes = useStyles()
  const [tabValue, setTabValue] = React.useState(0)
  const dispatch = useAppDispatch()
  const { dirty, modalOpen, currentForm } = useAppSelector(formEditorSelector)

  const handleClose = () => {
    if (dirty) {
      dispatch(TOGGLE_EDITOR_MODAL())
    } else {
      dispatch(CLOSE_FORM_EDITOR())
    }
  }

  const handleChange = (event: React.ChangeEvent<{}>, newIndex: number) => {
    setTabValue(newIndex)
  }

  const handleCancelDialog = (e: any) => {
    handleDialogClose(e, '');
  }

  const handleOkDialog = (e: any) => {
    handleDialogClose(e, 'save')
  }

  const handleDialogClose = (event: any, answer?: string) => {

    if (answer) {
      dispatch(CLOSE_FORM_EDITOR())
    }
    dispatch(TOGGLE_EDITOR_MODAL())
  }

  const handleSaveForm = async () => {
    if (currentForm.id) {
      await dispatch(updateForm(currentForm))
    } else {
      await dispatch(createForm(currentForm))
    }
    dispatch(fetchForms())
  }


  return (
    <div className={classes.root}>
      <AppBar position="static">
        <Tabs value={tabValue} onChange={handleChange} aria-label="Form Editor Tabs">
          <Tab label="Form Settings" {...a11yProps(0)} />
          <Tab label="Form Builder" {...a11yProps(1)} />
          {dirty && (<p>dirty!!!</p>)}
          <div className={classes.rightChild}>
            <Button
              classes={{
                textPrimary: classes.textPrimary
              }}
              color="primary"
              variant="text"
              endIcon={<SaveOutlinedIcon />}
              aria-label={window.sat_textstrings['Save form']}
              onClick={handleSaveForm}
            >
              {window.sat_textstrings['Save form']}
            </Button>
            <IconButton color="inherit" onClick={handleClose}>
              <CloseOutlinedIcon />
            </IconButton>
          </div>
        </Tabs>
      </AppBar>
      <TabPanel value={tabValue} index={0}>
        <Container>
          <FormSettings />
        </Container>
      </TabPanel>
      <TabPanel value={tabValue} index={1}>
        <FormBuilder />
      </TabPanel>
      <ThemeProvider theme={customDialogTheme}>
        <Dialog
          disableBackdropClick
          disableEscapeKeyDown
          maxWidth="sm"
          // onEntering={handleEntering}
          aria-labelledby="confirmation-dialog-title"
          open={modalOpen}
          onClose={handleDialogClose}
        >
          <DialogTitle id="confirmation-dialog-title">{window.sat_textstrings['Your form was not saved']}</DialogTitle>
          <DialogContent>
            <p>{window.sat_textstrings['Close Editor Modal Title']}</p>
            <p>{window.sat_textstrings['close_editor_message']}</p>
          </DialogContent>
          <DialogActions>
            <Button autoFocus onClick={handleCancelDialog} variant="outlined" color="primary">
              {window.sat_textstrings['Continue Editing']}
            </Button>
            <Button onClick={handleOkDialog} variant="outlined" color="secondary">
              {window.sat_textstrings['Close Without Saving']}
            </Button>
          </DialogActions>
        </Dialog>
      </ThemeProvider>
    </div>
  )
}
