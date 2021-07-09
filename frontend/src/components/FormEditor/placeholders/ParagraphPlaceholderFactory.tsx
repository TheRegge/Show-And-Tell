// Material-ui
import { makeStyles } from '@material-ui/core/styles'
import Box from '@material-ui/core/Box'
import InputLabel from '@material-ui/core/InputLabel'

// local imports
import { PlaceholderItemType } from '../../../app/constants'
import {
  MOVE_EDITOR_CURRENT_FORM_ITEM,
  REMOVE_EDITOR_CURRENT_FORM_ITEM,
} from '../../../app/slices/formEditorSlice'
import { } from '../../../app/slices/formEditorSlice'
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
    borderColor: theme.palette.text.disabled,
    height: '9ch',
  },
  label: {
    fontWeight: 700,
    color: 'black',
  },
  required: {
    color: 'tomato',
    fontWeight: 700,
  },
}))

const ParagraphPlaceholder = ({ item, index }: IFormItemCreator) => {
  const classes = useStyles()
  const dispatchers = {
    MOVE_EDITOR_CURRENT_FORM_ITEM,
    REMOVE_EDITOR_CURRENT_FORM_ITEM,
  }

  const required = item.required && (<span className={classes.required}>*</span>)
  const labelText = item.label || item.name

  return (
    <Box border={1} borderRadius={6} mt={2} className={classes.root} id={item.id}>
      <Toolbar index={index} dispatchers={dispatchers} />
      <Box component="div">
        <InputLabel className={classes.label}>
          {labelText} {required}
        </InputLabel>
        <Box my={2} borderRadius={3} border={1} className={classes.input}></Box>
      </Box>
    </Box>
  )
}

export class ParagraphPlaceholderFactory {
  get type() {
    return PlaceholderItemType.FORM_PLACEHOLDER_PARAGRAPH
  }

  create({ item, index }: IFormItemCreator) {
    return <ParagraphPlaceholder item={item} index={index} />
  }
}

