import { useDrag } from 'react-dnd'

// local imports
import { IFormItem } from '../model'
/**
 * Custom hook to wrap the React DnD dragging functionality
 *
 * @param {IFormItem} The item
 * @returns mixed
 */
const useDraggable = (formItem: IFormItem) => {
  const [{ isDragging }, drag] = useDrag(() => ({
    type: formItem.type,
    item: () => {
      return formItem
    },
    collect: (monitor) => ({
      isDragging: !!monitor.isDragging(),
    }),
  }))

  return { drag, isDragging }
}

export default useDraggable
