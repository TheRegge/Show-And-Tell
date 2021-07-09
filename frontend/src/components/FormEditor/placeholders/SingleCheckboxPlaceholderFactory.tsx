// Material-ui
import { makeStyles } from '@material-ui/core/styles'
import Box from '@material-ui/core/Box'
import InputLabel from '@material-ui/core/InputLabel'

// Local imports
import { PlaceholderItemType } from '../../../app/constants'
import {
  MOVE_EDITOR_CURRENT_FORM_ITEM,
  REMOVE_EDITOR_CURRENT_FORM_ITEM,
} from '../../../app/slices/formEditorSlice'
import { IFormItemCreator } from '../../../app/model'
import Toolbar from './Toolbar'

const useStyles = makeStyles((theme) => ({
  root: {
    borderColor: 'white',
    margin: '1rem 0',
    padding: '1rem',
    '&:hover': {
      borderColor: theme.palette.text.disabled,
    },
  },
  input: {
    backgroundColor: '#f5f5f5',
    height: '1.2rem',
    margin: '0 1em',
    width: '1.2rem',
  },
  required: {
    color: 'tomato',
    fontWeight: 700,
  },
}))

const SingleChechboxPlaceHolder = ({ item, index }: IFormItemCreator) => {
  const classes = useStyles()
  const dispatchers = {
    MOVE_EDITOR_CURRENT_FORM_ITEM,
    REMOVE_EDITOR_CURRENT_FORM_ITEM,
  }

  const required = item.required && (<span className={classes.required}>*</span>)
  const labelText = item.label || item.name

  return (
    <Box border={1} borderRadius={6} className={classes.root}>
      <Toolbar index={index} dispatchers={dispatchers} />
      <Box my={2} display="flex" flexDirection="row">
        <Box border={1} borderRadius={3} className={classes.input}></Box>
        <InputLabel>{labelText} {required}</InputLabel>
      </Box>
    </Box>
  )
}

export class SingleCheckboxPlaceholderFactory {
  get type() {
    return PlaceholderItemType.FORM_PLACEHOLDER_SINGLECHECKBOX
  }

  create({ item, index }: IFormItemCreator) {
    return <SingleChechboxPlaceHolder item={item} index={index} />
  }
}
