import React from 'react'
import Paragraph from '../../../components/ui/iconsvg/Paragraph'

const Story = {
  title: 'Components/ui/iconsvg/Paragraph',
  component: Paragraph,
  argTypes: {
    color: { control: 'color' },
  },
}

const Template = (args) => <Paragraph {...args} />
const Default = Template.bind({})
Default.args = {
  color: 'black',
}

export default Story
export { Default }
