import React from 'react'

// React DnD
import { DndProvider } from 'react-dnd'
import { HTML5Backend } from 'react-dnd-html5-backend'

// Material-ui
import Box from '@material-ui/core/Box'
import Container from '@material-ui/core/Container'
import Grid from '@material-ui/core/Grid'

// Local imports
import DraggableElements from './draggableElements'
import DragDropZone from './DragDropZone'
import Draggable from './Draggable'

const FormBuilder: React.FC = () => {
  const draggableElements: JSX.Element[] = DraggableElements.map(el => (
    <Draggable formItem={el} />
  ))

  return (
    <DndProvider backend={HTML5Backend}>
      <Container>
        <Grid container direction="row" spacing={3}>
          <Grid item xs={6}>
            <Box
              component="div"
              display="flex"
              flexDirection="row"
              alignItems="flex-end"
              flexWrap="wrap"
            >
              {draggableElements}
            </Box>
          </Grid>
          <Grid item xs={6}>
            <DragDropZone />
          </Grid>
        </Grid>
      </Container>
    </DndProvider>
  )
}

export default FormBuilder