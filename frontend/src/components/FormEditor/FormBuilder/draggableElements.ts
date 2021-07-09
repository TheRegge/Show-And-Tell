import { PlaceholderItemType } from '../../../app/constants'
import { IFormItem } from '../../../app/model'

// TODO add Icons
const DraggableElements: Array<IFormItem> = [
  {
    name: window.sat_textstrings['Single line text'],
    type: PlaceholderItemType.FORM_PLACEHOLDER_SINGLELINETEXT,
    required: false,
    id: '1',
  },
  {
    name: window.sat_textstrings['Single checkbox'],
    type: PlaceholderItemType.FORM_PLACEHOLDER_SINGLECHECKBOX,
    required: false,
    id: '2'
  },
  {
    name: window.sat_textstrings['Paragraph Text'],
    type: PlaceholderItemType.FORM_PLACEHOLDER_PARAGRAPH,
    required: false,
    id: '3'
  }
]

export default DraggableElements
